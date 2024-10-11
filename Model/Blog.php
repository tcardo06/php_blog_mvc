<?php

namespace TestProject\Model;

class Blog
{
    protected $oDb;

    public function __construct()
    {
      if (session_status() == PHP_SESSION_NONE) {
          session_start();
      }
        $this->oDb = new \TestProject\Engine\Db;
    }

    public function get($iOffset, $iLimit)
    {
        $oStmt = $this->oDb->prepare('SELECT * FROM Posts ORDER BY createdDate DESC LIMIT :offset, :limit');
        $oStmt->bindParam(':offset', $iOffset, \PDO::PARAM_INT);
        $oStmt->bindParam(':limit', $iLimit, \PDO::PARAM_INT);
        $oStmt->execute();
        return $oStmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getAll()
    {
        $oStmt = $this->oDb->query('SELECT * FROM Posts ORDER BY createdDate DESC');
        return $oStmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function add(array $aData, array $tagIds = [])
    {
        $oStmt = $this->oDb->prepare('INSERT INTO posts (title, body, preview, createdDate) VALUES(:title, :body, :preview, :created_date)');

        // Include preview in the execution array
        $result = $oStmt->execute($aData);

        // Get the last inserted post ID
        if ($result) {
            $postId = $this->oDb->lastInsertId();
            $this->addPostTags($postId, $tagIds);
        }

        return $result;
    }

    public function getById($iId)
    {
        $oStmt = $this->oDb->prepare('SELECT * FROM Posts WHERE id = :postId LIMIT 1');
        $oStmt->bindParam(':postId', $iId, \PDO::PARAM_INT);
        $oStmt->execute();
        return $oStmt->fetch(\PDO::FETCH_OBJ);
    }

    public function getTagsByPostId($postId)
    {
        $sql = 'SELECT t.name FROM tags t
                INNER JOIN post_tags pt ON t.id = pt.tag_id
                WHERE pt.post_id = :postId';
        $oStmt = $this->oDb->prepare($sql);
        $oStmt->execute([':postId' => $postId]);

        return $oStmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function update(array $aData, array $newTagIds = [])
    {
        $oStmt = $this->oDb->prepare('UPDATE posts SET title = :title, body = :body, preview = :preview, updatedDate = NOW() WHERE id = :postId LIMIT 1');
        $oStmt->bindValue(':postId', $aData['post_id'], \PDO::PARAM_INT);
        $oStmt->bindValue(':title', $aData['title']);
        $oStmt->bindValue(':body', $aData['body']);
        $oStmt->bindValue(':preview', $aData['preview']);

        // Update the post and return the result
        $result = $oStmt->execute();

        if ($result) {
            // Handle the tag updates
            $this->updatePostTags($aData['post_id'], $newTagIds);
        }

        return $result;
    }

    private function updatePostTags($postId, array $newTagIds)
    {
        // Fetch current tags for the post
        $currentTagIds = $this->getPostTags($postId);

        // Determine which tags need to be added
        $tagsToAdd = array_diff($newTagIds, $currentTagIds);

        // Determine which tags need to be removed
        $tagsToRemove = array_diff($currentTagIds, $newTagIds);

        // Add new tags
        if (!empty($tagsToAdd)) {
            $oStmt = $this->oDb->prepare('INSERT INTO post_tags (post_id, tag_id) VALUES (:postId, :tagId)');
            foreach ($tagsToAdd as $tagId) {
                $oStmt->execute([':postId' => $postId, ':tagId' => $tagId]);
            }
        }

        // Remove tags that are no longer selected
        if (!empty($tagsToRemove)) {
            $oStmt = $this->oDb->prepare('DELETE FROM post_tags WHERE post_id = :postId AND tag_id = :tagId');
            foreach ($tagsToRemove as $tagId) {
                $oStmt->execute([':postId' => $postId, ':tagId' => $tagId]);
            }
        }
    }


    public function delete($iId)
    {
        $oStmt = $this->oDb->prepare('DELETE FROM Posts WHERE id = :postId LIMIT 1');
        $oStmt->bindParam(':postId', $iId, \PDO::PARAM_INT);
        return $oStmt->execute();
    }

    private function addPostTags($postId, $tagIds)
    {
        $oStmt = $this->oDb->prepare('INSERT INTO post_tags (post_id, tag_id) VALUES (:postId, :tagId)');

        foreach ($tagIds as $tagId) {
            $oStmt->execute([':postId' => $postId, ':tagId' => $tagId]);
        }
    }

    public function getAllTags()
    {
        $oStmt = $this->oDb->query('SELECT * FROM tags ORDER BY name ASC');
        return $oStmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getPostTags($postId)
    {
        $oStmt = $this->oDb->prepare('SELECT tag_id FROM post_tags WHERE post_id = :postId');
        $oStmt->bindParam(':postId', $postId, \PDO::PARAM_INT);
        $oStmt->execute();
        return $oStmt->fetchAll(\PDO::FETCH_COLUMN); // Returns an array of tag IDs
    }

    public function searchByName($searchQuery)
    {
        $sql = 'SELECT * FROM posts WHERE title LIKE :title ORDER BY createdDate DESC';
        $oStmt = $this->oDb->prepare($sql);
        $oStmt->execute([':title' => '%' . $searchQuery . '%']);

        return $oStmt->fetchAll(\PDO::FETCH_OBJ);
    }

    // Get approved comments for a specific post
    public function getApprovedComments($iPostId)
    {
        $oStmt = $this->oDb->prepare('SELECT * FROM comments WHERE post_id = :post_id AND status = "approved" ORDER BY created_at ASC');
        $oStmt->bindParam(':post_id', $iPostId, \PDO::PARAM_INT);
        $oStmt->execute();
        return $oStmt->fetchAll(\PDO::FETCH_OBJ);
    }

    // Add a new comment (with status as 'pending')
    public function addComment($aData)
    {
        $oStmt = $this->oDb->prepare('INSERT INTO comments (post_id, user_id, comment, status, created_at) VALUES (:post_id, :user_id, :comment, :status, NOW())');
        $oStmt->bindValue(':post_id', $aData['post_id'], \PDO::PARAM_INT);
        $oStmt->bindValue(':user_id', $aData['user_id'], \PDO::PARAM_INT);
        $oStmt->bindValue(':comment', $aData['comment']);
        $oStmt->bindValue(':status', $aData['status']);

        // Debugging to check if the query executed successfully
        if ($oStmt->execute()) {
            echo "Comment successfully inserted.";
        } else {
            echo "Failed to insert comment.";
            print_r($oStmt->errorInfo()); // Display any database error
        }
        exit(); // Stop execution to inspect the output
    }
}

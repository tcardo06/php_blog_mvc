<?php

namespace TestProject\Model;

class Blog
{
    protected $oDb;

    // Attributes for encapsulation
    private $title;
    private $body;
    private $preview;
    private $authorId;
    private $createdDate;
    private $updatedDate;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->oDb = new \TestProject\Engine\Db;
    }

    // Getter and Setter for Title
    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    // Getter and Setter for Body
    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    // Getter and Setter for Preview
    public function getPreview()
    {
        return $this->preview;
    }

    public function setPreview($preview)
    {
        $this->preview = $preview;
    }

    // Getter and Setter for Created Date
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
    }

    // Getter and Setter for Updated Date
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }

    public function setUpdatedDate($updatedDate)
    {
        $this->updatedDate = $updatedDate;
    }

    // Getter and Setter for Author ID
        public function getAuthorId()
    {
        return $this->authorId;
    }

    public function setAuthorId($authorId)
    {
        $this->authorId = $authorId;
    }

    // Getter and Setter for Comments
    private $commentData = [];

    public function setCommentData($commentData)
    {
        $this->commentData = $commentData;
    }

    public function getCommentData()
    {
        return $this->commentData;
    }

    // Get posts with pagination
    public function get($iOffset, $iLimit)
    {
        $oStmt = $this->oDb->prepare('SELECT * FROM Posts ORDER BY createdDate DESC LIMIT :offset, :limit');
        $oStmt->bindParam(':offset', $iOffset, \PDO::PARAM_INT);
        $oStmt->bindParam(':limit', $iLimit, \PDO::PARAM_INT);
        $oStmt->execute();
        return $oStmt->fetchAll(\PDO::FETCH_OBJ);
    }

    // Get all posts
    public function getAll()
    {
        $oStmt = $this->oDb->query('SELECT * FROM Posts ORDER BY createdDate DESC');
        return $oStmt->fetchAll(\PDO::FETCH_OBJ);
    }

    // Add a new post
    public function add(array $tagIds = [])
    {
        $oStmt = $this->oDb->prepare('INSERT INTO posts (title, body, preview, createdDate, author_id) VALUES(:title, :body, :preview, :created_date, :author_id)');
        $oStmt->bindValue(':title', $this->getTitle(), \PDO::PARAM_STR);
        $oStmt->bindValue(':body', $this->getBody(), \PDO::PARAM_STR);
        $oStmt->bindValue(':preview', $this->getPreview(), \PDO::PARAM_STR);
        $oStmt->bindValue(':created_date', $this->getCreatedDate(), \PDO::PARAM_STR);
        $oStmt->bindValue(':author_id', $this->getAuthorId(), \PDO::PARAM_INT);

        $result = $oStmt->execute();

        if ($result) {
            $postId = $this->oDb->lastInsertId();
            $this->addPostTags($postId, $tagIds);
        }

        return $result;
    }

    // Get a post by its ID
    public function getById($iId)
    {
        $sql = 'SELECT p.*, u.name AS author_name
                FROM posts p
                JOIN users u ON p.author_id = u.id
                WHERE p.id = :postId LIMIT 1';
        $oStmt = $this->oDb->prepare($sql);
        $oStmt->bindParam(':postId', $iId, \PDO::PARAM_INT);
        $oStmt->execute();
        return $oStmt->fetch(\PDO::FETCH_OBJ);
    }

    // Get tags for a post
    public function getTagsByPostId($postId)
    {
        $sql = 'SELECT t.name FROM tags t
                INNER JOIN post_tags pt ON t.id = pt.tag_id
                WHERE pt.post_id = :postId';
        $oStmt = $this->oDb->prepare($sql);
        $oStmt->execute([':postId' => $postId]);

        return $oStmt->fetchAll(\PDO::FETCH_OBJ);
    }

    // Update a post and its tags
    public function update($postId, array $newTagIds = [])
    {
        $oStmt = $this->oDb->prepare('UPDATE posts SET title = :title, body = :body, preview = :preview, updatedDate = NOW(), author_id = :author_id WHERE id = :postId LIMIT 1');
        $oStmt->bindValue(':postId', $postId, \PDO::PARAM_INT);
        $oStmt->bindValue(':title', $this->getTitle(), \PDO::PARAM_STR);
        $oStmt->bindValue(':body', $this->getBody(), \PDO::PARAM_STR);
        $oStmt->bindValue(':preview', $this->getPreview(), \PDO::PARAM_STR);
        $oStmt->bindValue(':author_id', $this->getAuthorId(), \PDO::PARAM_INT);

        $result = $oStmt->execute();

        if ($result) {
            $this->updatePostTags($postId, $newTagIds);
        }

        return $result;
    }

    // Helper function to add post tags
    private function addPostTags($postId, $tagIds)
    {
        $oStmt = $this->oDb->prepare('INSERT INTO post_tags (post_id, tag_id) VALUES (:postId, :tagId)');

        foreach ($tagIds as $tagId) {
            $oStmt->execute([':postId' => $postId, ':tagId' => $tagId]);
        }
    }

    // Update the tags of a post
    private function updatePostTags($postId, array $newTagIds)
    {
        $currentTagIds = $this->getPostTags($postId);
        $tagsToAdd = array_diff($newTagIds, $currentTagIds);
        $tagsToRemove = array_diff($currentTagIds, $newTagIds);

        if (!empty($tagsToAdd)) {
            $oStmt = $this->oDb->prepare('INSERT INTO post_tags (post_id, tag_id) VALUES (:postId, :tagId)');
            foreach ($tagsToAdd as $tagId) {
                $oStmt->execute([':postId' => $postId, ':tagId' => $tagId]);
            }
        }

        if (!empty($tagsToRemove)) {
            $oStmt = $this->oDb->prepare('DELETE FROM post_tags WHERE post_id = :postId AND tag_id = :tagId');
            foreach ($tagsToRemove as $tagId) {
                $oStmt->execute([':postId' => $postId, ':tagId' => $tagId]);
            }
        }
    }

    // Delete a post
    public function delete($iId)
    {
        $oStmt = $this->oDb->prepare('DELETE FROM Posts WHERE id = :postId LIMIT 1');
        $oStmt->bindParam(':postId', $iId, \PDO::PARAM_INT);
        return $oStmt->execute();
    }

    // Get all tags
    public function getAllTags()
    {
        $oStmt = $this->oDb->query('SELECT * FROM tags ORDER BY name ASC');
        return $oStmt->fetchAll(\PDO::FETCH_OBJ);
    }

    // Get the tag IDs of a post
    public function getPostTags($postId)
    {
        $oStmt = $this->oDb->prepare('SELECT tag_id FROM post_tags WHERE post_id = :postId');
        $oStmt->bindParam(':postId', $postId, \PDO::PARAM_INT);
        $oStmt->execute();
        return $oStmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function getPostsWithAuthors()
    {
        $sql = 'SELECT p.*, u.name AS author_name
                FROM posts p
                JOIN users u ON p.author_id = u.id
                ORDER BY p.createdDate DESC';
        $oStmt = $this->oDb->query($sql);
        return $oStmt->fetchAll(\PDO::FETCH_OBJ);
    }

    // Search posts by title
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

    // Add a new comment with status 'pending'
    public function addComment($aData)
    {
        // Use prepared statements to avoid SQL injection
        $oStmt = $this->oDb->prepare('INSERT INTO comments (post_id, user_id, comment, status, created_at) VALUES (:post_id, :user_id, :comment, :status, NOW())');

        // Bind the values safely
        $oStmt->bindValue(':post_id', $aData['post_id'], \PDO::PARAM_INT);
        $oStmt->bindValue(':user_id', $aData['user_id'], \PDO::PARAM_INT);
        $oStmt->bindValue(':comment', $aData['comment'], \PDO::PARAM_STR);
        $oStmt->bindValue(':status', $aData['status'], \PDO::PARAM_STR);

        return $oStmt->execute(); // Return whether the insertion was successful
    }

    public function getAllCommentsWithPostTitles()
    {
        $sql = 'SELECT c.id, c.comment, c.created_at, c.user_id, c.status, p.title AS post_title, p.id AS post_id
                FROM comments c
                JOIN posts p ON c.post_id = p.id
                ORDER BY c.created_at DESC';
        $oStmt = $this->oDb->query($sql);
        return $oStmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getApprovedCommentsWithUsers($postId)
    {
        $sql = 'SELECT c.id, c.comment, c.created_at, c.status, u.name AS user_name
                FROM comments c
                JOIN users u ON c.user_id = u.id
                WHERE c.post_id = :postId AND c.status = "approved"
                ORDER BY c.created_at ASC';
        $oStmt = $this->oDb->prepare($sql);
        $oStmt->bindValue(':postId', $postId, \PDO::PARAM_INT);
        $oStmt->execute();
        $comments = $oStmt->fetchAll(\PDO::FETCH_OBJ);

        $this->setCommentData($comments);
        return $this->getCommentData();
    }

    public function approveComment($commentId)
    {
        $oStmt = $this->oDb->prepare('UPDATE comments SET status = "approved" WHERE id = :commentId LIMIT 1');
        $oStmt->bindValue(':commentId', $commentId, \PDO::PARAM_INT);
        return $oStmt->execute();
    }

    public function deleteComment($commentId)
    {
        $oStmt = $this->oDb->prepare('DELETE FROM comments WHERE id = :commentId LIMIT 1');
        $oStmt->bindValue(':commentId', $commentId, \PDO::PARAM_INT);
        return $oStmt->execute();
    }

}

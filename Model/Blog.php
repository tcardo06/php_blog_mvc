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

    public function add(array $aData)
    {
        $oStmt = $this->oDb->prepare('INSERT INTO Posts (title, body, createdDate) VALUES(:title, :body, :created_date)');
        return $oStmt->execute($aData);
    }

    public function getById($iId)
    {
        $oStmt = $this->oDb->prepare('SELECT * FROM Posts WHERE id = :postId LIMIT 1');
        $oStmt->bindParam(':postId', $iId, \PDO::PARAM_INT);
        $oStmt->execute();
        return $oStmt->fetch(\PDO::FETCH_OBJ);
    }

    public function update(array $aData)
    {
        $oStmt = $this->oDb->prepare('UPDATE Posts SET title = :title, body = :body WHERE id = :postId LIMIT 1');
        $oStmt->bindValue(':postId', $aData['post_id'], \PDO::PARAM_INT);
        $oStmt->bindValue(':title', $aData['title']);
        $oStmt->bindValue(':body', $aData['body']);
        return $oStmt->execute();
    }

    public function delete($iId)
    {
        $oStmt = $this->oDb->prepare('DELETE FROM Posts WHERE id = :postId LIMIT 1');
        $oStmt->bindParam(':postId', $iId, \PDO::PARAM_INT);
        return $oStmt->execute();
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

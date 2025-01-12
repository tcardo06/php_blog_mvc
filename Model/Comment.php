<?php

namespace TestProject\Model;

class Comment
{
    protected $oDb;

    private $comment;
    private $createdAt;
    private $status;
    private $postId;
    private $userId;

    public function __construct()
    {
        $this->oDb = new \TestProject\Engine\Db;
    }

    // Getters and setters
    public function getComment()
    {
        return $this->comment;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getPostId()
    {
        return $this->postId;
    }

    public function setPostId($postId)
    {
        $this->postId = $postId;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    // Fetch approved comments for a post
    public function getApprovedComments($postId)
    {
        $oStmt = $this->oDb->prepare('SELECT * FROM comments WHERE post_id = :postId AND status = "approved" ORDER BY created_at ASC');
        $oStmt->bindValue(':postId', $postId, \PDO::PARAM_INT);
        $oStmt->execute();
        return $oStmt->fetchAll(\PDO::FETCH_OBJ);
    }

    // Add a new comment
    public function addComment($data)
    {
        $oStmt = $this->oDb->prepare('INSERT INTO comments (post_id, user_id, comment, status, created_at) VALUES (:post_id, :user_id, :comment, :status, NOW())');

        return $oStmt->execute([
            ':post_id' => $data['post_id'],
            ':user_id' => $data['user_id'],
            ':comment' => $data['comment'],
            ':status' => $data['status']
        ]);
    }

    // Approve a comment
    public function approveComment($commentId)
    {
        $oStmt = $this->oDb->prepare('UPDATE comments SET status = "approved" WHERE id = :commentId');
        $oStmt->bindValue(':commentId', $commentId, \PDO::PARAM_INT);
        return $oStmt->execute();
    }

    // Fetch approved comments with user details for a specific post
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

        return $oStmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getAllCommentsWithPostTitles()
    {
        $sql = 'SELECT c.id, c.comment, c.created_at, c.user_id, c.status, p.title AS post_title, p.id AS post_id
                FROM comments c
                JOIN posts p ON c.post_id = p.id
                ORDER BY c.created_at DESC';

        $oStmt = $this->oDb->prepare($sql);
        $oStmt->execute();

        return $oStmt->fetchAll(\PDO::FETCH_OBJ);
    }

    // Delete a comment
    public function deleteComment($commentId)
    {
        $oStmt = $this->oDb->prepare('DELETE FROM comments WHERE id = :commentId');
        $oStmt->bindValue(':commentId', $commentId, \PDO::PARAM_INT);
        return $oStmt->execute();
    }
}

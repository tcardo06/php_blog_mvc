<?php

namespace TestProject\Model;

class Post
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

    // Update a post
    public function update($postId, array $newTagIds = [])
    {
        $oStmt = $this->oDb->prepare('UPDATE posts SET title = :title, body = :body, preview = :preview, updatedDate = NOW(), author_id = :author_id WHERE id = :postId LIMIT 1');
        $oStmt->bindValue(':postId', $postId, \PDO::PARAM_INT);
        $oStmt->bindValue(':title', $this->getTitle(), \PDO::PARAM_STR);
        $oStmt->bindValue(':body', $this->getBody(), \PDO::PARAM_STR);
        $oStmt->bindValue(':preview', $this->getPreview(), \PDO::PARAM_STR);
        $oStmt->bindValue(':author_id', $this->getAuthorId(), \PDO::PARAM_INT);

        $result = $oStmt->execute();
        return $result;
    }

    // Delete a post
    public function delete($iId)
    {
        $oStmt = $this->oDb->prepare('DELETE FROM Posts WHERE id = :postId LIMIT 1');
        $oStmt->bindParam(':postId', $iId, \PDO::PARAM_INT);
        return $oStmt->execute();
    }

    // Search posts by title
    public function searchByName($searchQuery)
    {
        $sql = 'SELECT * FROM posts WHERE title LIKE :title ORDER BY createdDate DESC';
        $oStmt = $this->oDb->prepare($sql);
        $oStmt->execute([':title' => '%' . $searchQuery . '%']);
        return $oStmt->fetchAll(\PDO::FETCH_OBJ);
    }

    // Get posts with authors
    public function getPostsWithAuthors()
    {
        $sql = 'SELECT p.*, u.name AS author_name
                FROM posts p
                JOIN users u ON p.author_id = u.id
                ORDER BY p.createdDate DESC';

        $oStmt = $this->oDb->prepare($sql);
        $oStmt->execute();
        return $oStmt->fetchAll(\PDO::FETCH_OBJ);
    }
}

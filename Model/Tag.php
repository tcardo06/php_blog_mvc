<?php

namespace TestProject\Model;

class Tag
{
    protected $oDb;

    private $name;

    public function __construct()
    {
        $this->oDb = new \TestProject\Engine\Db;
    }

    // Getter and setter for name
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    // Get all tags
    public function getAllTags()
    {
        $oStmt = $this->oDb->query('SELECT * FROM tags ORDER BY name ASC');
        return $oStmt->fetchAll(\PDO::FETCH_OBJ);
    }

    // Get tags for a specific post
    public function getTagsByPostId($postId)
    {
        $oStmt = $this->oDb->prepare('SELECT t.name FROM tags t INNER JOIN post_tags pt ON t.id = pt.tag_id WHERE pt.post_id = :postId');
        $oStmt->bindValue(':postId', $postId, \PDO::PARAM_INT);
        $oStmt->execute();
        return $oStmt->fetchAll(\PDO::FETCH_OBJ);
    }
}

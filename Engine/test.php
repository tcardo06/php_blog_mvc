<?php

require 'Db.php';
require 'Config.php';

try {
    $db = new TestProject\Engine\Db();
    $stmt = $db->query('SELECT * FROM posts LIMIT 1');
    $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    if ($result) {
        echo 'Database connection successful. Sample data: ';
        print_r($result);
    } else {
        echo 'Database connection successful but no data found.';
    }
} catch (\PDOException $e) {
    echo 'Database error: ' . $e->getMessage();
}

<?php

define("SERVER", "localhost");
define("USER", "jeraldpangan");
define("PASSWORD", "balmondiyotmiya");
define("DB", "db_student_service");
define("CHARSET", "utf8mb4");
class Connection {
    static $connection = false;

    public function connect(){
    $cnString = "mysql:host=" . SERVER . ";dbname=" . DB . ";charset=" . CHARSET;
    $options = [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        \PDO::ATTR_EMULATE_PREPARES => false,
        \PDO::ATTR_STRINGIFY_FETCHES => false,
        \PDO::ATTR_PERSISTENT => true
    ];

    try {
        static::$connection = new \PDO($cnString, USER, PASSWORD, $options);
    }
    catch(\PDOException $er){
        echo "Connection failed: " . $er->getMessage();
    }
    return static::$connection;
    }

    public function closeConnection(){
        // clear static connection reference
        static::$connection = null;
        return null;
    }
}
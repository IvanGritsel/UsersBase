<?php

namespace App\Connection;

use App\Exception\ConnectionException;
use PDO;
use PDOException;

class ConnectionFactory
{
    private static ?ConnectionFactory $instance = null;

    private string $PATH_TO_CONFIG = __DIR__ . '/db_config.json';

    private string $SERVER_NAME;

    private string $DB_NAME;

    private string $USER_NAME;

    private string $PASSWORD;

    private function __construct()
    {
        $fileContents = json_decode(file_get_contents($this->PATH_TO_CONFIG), true);
        $this->SERVER_NAME = $fileContents['servername'];
        $this->DB_NAME = $fileContents['dbname'];
        $this->USER_NAME = $fileContents['username'];
        $this->PASSWORD = $fileContents['password'];
    }

//    private function __clone()
//    {
//    }
//    private function __wakeup()
//    {
//    }

    public static function getInstance(): ConnectionFactory
    {
        if (is_null(self::$instance)) {
            self::$instance = new ConnectionFactory();
        }

        return self::$instance;
    }

    /**
     * @throws ConnectionException
     */
    public function getConnection(): PDO
    {
        try {
            $connection = new PDO(
                "mysql:host=" . $this->SERVER_NAME . ";dbname=" . $this->DB_NAME,
                $this->USER_NAME,
                $this->PASSWORD
            );
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $connection;
        } catch (PDOException $e) {
            $problem = $e->getMessage();
            throw new ConnectionException("Unable to get connection to database, cause: $problem", 0, $e);
        }
    }
}

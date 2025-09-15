<?php
class Database
{
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $conn;
    private $port;

    public function __construct()
    {
        $this->host = "153.92.15.44";
        $this->port = "3306";
        $this->db_name = "u617196622_topiksir";
        $this->username = "u617196622_root_topiksir";
        $this->password = "V3:24q3be>v";
    }

    public function getConnection()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name,
                $this->username,
                $this->password,
                [PDO::ATTR_PERSISTENT => true]
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $exception) {
            error_log($exception->getMessage());
            die("Connection error. Please try again later.");
        }

        return $this->conn;
    }
}

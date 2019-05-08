<?php


abstract class DB
{
    const SERVERNAME = "mysql";
    const USERNAME = "root";
    const PASSWORD = "root";
    const DBNAME = "divider";

    protected $connection;

    public function __construct()
    {
        // Create connection
        $this->connection = mysqli_connect(self::SERVERNAME, self::USERNAME, self::PASSWORD, self::DBNAME);
        // Check connection
        if (!$this->connection) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }

    abstract public function getFirst();

    public function __destruct() {
        mysqli_close($this->connection);
    }
}

?>
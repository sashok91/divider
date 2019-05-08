<?php

require_once 'DB.php';


class Model extends DB
{
    const SQL_SELECT_POST = "SELECT id,html FROM posts";

    public function getFirst()
    {
        $result = mysqli_query($this->connection, self::SQL_SELECT_POST);

        return mysqli_fetch_assoc($result);
    }

}
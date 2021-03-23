<?php

namespace phpr\mysqli;

class db
{
    private $database;
    function __construct($database)
    {
        if ($database == true) {
            $this->database = mysqli_connect(DB_SETUP['HOST'], DB_SETUP['USER'], DB_SETUP['PASS'], DB_SETUP['DB']);
        }
    }
    function all($sql)
    {
        $db = $this->database;
        if ($db->connect_errno) {
            echo "Failed to connect to MySQL: " . $db->connect_error;
            exit();
        }
        return mysqli_fetch_all(mysqli_query($this->database, $sql), MYSQLI_ASSOC);
    }
    function assoc($sql)
    {
        $db = $this->database;
        if ($db->connect_errno) {
            echo "Failed to connect to MySQL: " . $db->connect_error;
            exit();
        }
        return mysqli_fetch_assoc(mysqli_query($this->database, $sql));
    }
    function num($sql)
    {
        $db = $this->database;
        if ($db->connect_errno) {
            echo "Failed to connect to MySQL: " . $db->connect_error;
            exit();
        }
        return mysqli_num_rows(mysqli_query($this->database, $sql));
    }
    function array_($sql)
    {
        $db = $this->database;
        if ($db->connect_errno) {
            echo "Failed to connect to MySQL: " . $db->connect_error;
            exit();
        }
        return mysqli_fetch_array(mysqli_query($this->database, $sql), MYSQLI_ASSOC);
    }
    function query($sql)
    {
        $db = $this->database;
        if ($db->connect_errno) {
            echo "Failed to connect to MySQL: " . $db->connect_error;
            exit();
        }
        return mysqli_query($this->database, $sql);
    }
}

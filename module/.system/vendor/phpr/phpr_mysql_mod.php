<?php

namespace phpr\mysqli\mod;

function mysqli_mod($sql){
    $sql = str_replace('[<]'    ,'INSERT INTO'  ,$sql);
    $sql = str_replace('[=]'    ,'VALUES'       ,$sql);
    $sql = str_replace('[:]'    ,'SET'          ,$sql);
    
    $sql = str_replace('[^]'    ,'UPDATE'       ,$sql);
    $sql = str_replace('[!]'    ,'DELETE FROM'  ,$sql);
    $sql = str_replace('[>]'    ,'SELECT'       ,$sql);
    $sql = str_replace('[>>]'   ,'FROM'         ,$sql);
    $sql = str_replace('[??]'   ,'WHERE'        ,$sql);
    $sql = str_replace('[,]'    ,'AND'          ,$sql);
    $sql = str_replace('[L]'    ,'LIMIT'        ,$sql);
    $sql = str_replace('[O]'    ,'ORDER BY'     ,$sql);
    $sql = str_replace('[+]'    ,'ASC'          ,$sql);
    $sql = str_replace('[-]'    ,'DESC'         ,$sql);

    $sql = str_replace('[C]'    ,'COUNT(*) as count'          ,$sql);
    return $sql;
}

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
        $sql = mysqli_mod($sql);
        $db = $this->database;
        if ($db->connect_errno) {
            echo "Failed to connect to MySQL: " . $db->connect_error;
            exit();
        }
        return mysqli_fetch_all(mysqli_query($this->database, $sql), MYSQLI_ASSOC);
    }
    function assoc($sql)
    {
        $sql = mysqli_mod($sql);
        $db = $this->database;
        if ($db->connect_errno) {
            echo "Failed to connect to MySQL: " . $db->connect_error;
            exit();
        }
        return mysqli_fetch_assoc(mysqli_query($this->database, $sql));
    }
    function num($sql)
    {
        $sql = mysqli_mod($sql);
        $db = $this->database;
        if ($db->connect_errno) {
            echo "Failed to connect to MySQL: " . $db->connect_error;
            exit();
        }
        return mysqli_num_rows(mysqli_query($this->database, $sql));
    }
    function array_($sql)
    {
        $sql = mysqli_mod($sql);
        $db = $this->database;
        if ($db->connect_errno) {
            echo "Failed to connect to MySQL: " . $db->connect_error;
            exit();
        }
        return mysqli_fetch_array(mysqli_query($this->database, $sql), MYSQLI_ASSOC);
    }
    function query($sql)
    {
        $sql = mysqli_mod($sql);
        $db = $this->database;
        if ($db->connect_errno) {
            echo "Failed to connect to MySQL: " . $db->connect_error;
            exit();
        }
        return mysqli_query($this->database, $sql);
    }
}

<?php
namespace phpr\sqlite;
use SQLite3;
use SQLite3Result;
use SQLite3Stmt;


class open 
{
    private $file_path;
    function __construct($file_path)
    {
        $this->sqlite = new SQLite3($file_path);
    }

    function query($sql)
    {
        $phpr_sqlite = $this->sqlite;
        return $phpr_sqlite->query($sql);
    }

    function extension($file_path)
    {
        $phpr_sqlite = $this->sqlite;
        return $phpr_sqlite->loadExtension($file_path);
    }

    function single($sql)
    {
        $phpr_sqlite = $this->sqlite;
        return $phpr_sqlite->querySingle($sql);
    }
}
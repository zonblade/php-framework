<?php
use phpr\database\models as models;

$user_table = array(
    "id"        => "INT(80) NOT NULL AUTO_INCREMENT",
    "name"      => "VARCHAR(255) NOT NULL",
    "username"  => "VARCHAR(255) NOT NULL",
    "password"  => "VARCHAR(255) NOT NULL",
    "role"      => "TINYINT(1) NOT NULL",
    "KEY"       => "PRIMARY KEY(`id`)",
    "UNIQUE"    => "UNIQUE `username` (`username`)",
);
models\DataModels(true,'users',$user_table);

models\DataModels(true,'con_limiter',[
    "time" => "VARCHAR(255) NOT NULL"
]);

<?php
session_start();error_reporting(E_ALL);ini_set('display_errors', 'On');
define("ROOT_FOLDER",__DIR__);
define("MODL_FOLDER",__DIR__.'/module');
$SERVERY = parse_url($_SERVER['REQUEST_URI']);
$SERVERX = $SERVERY['path'];
$SERVERZ = substr($SERVERX, -1);
if($SERVERZ != '/'){
    header("Location: $SERVERX/");
    die();
}
include ROOT_FOLDER.'/module/urls.php';
<?php
namespace phpr\fetch\native;

function get($param){
    return $_GET[$param];
}

function post($param){
    return $_POST[$param];
}

function path($param){
    $SERVERURI  = parse_url($_SERVER['REQUEST_URI']);
    $PATHURI    = $SERVERURI['path'];
    $QUERYURI   = $SERVERURI['query'];
    switch($param){
        case 'query':
            return $QUERYURI;
            break;
        case 'path':
            return $PATHURI;
            break;
        default:
            return $PATHURI;
    }
}
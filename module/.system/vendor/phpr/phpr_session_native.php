<?php

namespace phpr\session\native;

function set($array)
{
    foreach ($array as $key => $val ){
        $_SESSION[$key] = $val;
    }
}

function get($session_name){
    if(isset($session_name)){
        if($session_name != null){
            return $_SESSION[$session_name];
        }else{
            return false;
        }
    }else{
        return false;
    }
}

function check($session_name, $value)
{
    if (isset($_SESSION[$session_name])) {
        if ($_SESSION[$session_name] == $value) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function is_login($session_name,$redirect_path,$type)
{
    if($type == false)
    {
        if(!isset($_SESSION[$session_name]))
        {
            header('Location:'.$redirect_path);
            die();
        }
        elseif (isset($_SESSION[$session_name])) 
        {
            if ($_SESSION[$session_name] != true) 
            {
                header('Location:'.$redirect_path);
                die();
            }
        }
    }
    elseif($type == true)
    {
        if (isset($_SESSION[$session_name])) 
        {
            if ($_SESSION[$session_name] == true) 
            {
                header('Location:'.$redirect_path);
                die();
            }
        }
    }
}

function del($session_name)
{
    $_SESSION[$session_name] = '';
    unset($_SESSION[$session_name]);
}

function purge()
{
    $_SESSION = array();
    session_destroy();
}

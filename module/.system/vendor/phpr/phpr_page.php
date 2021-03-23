<?php

namespace phpr\page\display;

function strips($string, $start, $end)
{
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

function Alias($get_path, $alias)
{
    return $GLOBALS["$alias"] = $get_path;
}

function Display($get_path, $file_path)
{
    if ($file_path != false) {
        if ($get_path == false) {
            include $file_path;
            die();
        } elseif (strpos($get_path, '?') !== false) {
            $GetParam = strips($get_path, "?", "=");
            $ParamVal = substr($get_path, strpos($get_path, "=") + 1);
            if (isset($_GET[$GetParam])) {
                if ($_GET[$GetParam] == $ParamVal) {
                    include $file_path;
                    die();
                }
            }
        }
    }
}

function URI($url_path, $file_path)
{
    $SERVERURI = parse_url($_SERVER['REQUEST_URI']);
    $SERVERURI = $SERVERURI['path'];
    if ($url_path == false) {
        include $file_path;
        die();
    } else {
        if (URIFOLDER != '/') {
            $this_url = str_replace(URIFOLDER, '', $SERVERURI);
        } else {
            $this_url = $SERVERURI;
        }
        if (strpos($url_path, '[SLUG]/') !== false) {
            $SLUG = str_replace('[SLUG]/', '', $url_path);
            $SLUG = preg_replace('#' . $SLUG . '#', '', $SERVERURI);
            $SLUG = preg_replace('#' . URIFOLDER . '#', '', $SLUG);
            $SLUG = str_replace('/', '', $SLUG);

            $this_url = str_replace($SLUG, '[SLUG]', $SERVERURI);
            $this_url = str_replace(URIFOLDER, '', $this_url);
        } elseif (strpos($url_path, '[INT]/') !== false) {
            $INT = str_replace('[INT]/', '', $url_path);
            $INT = preg_replace('#' . $INT . '#', '', $SERVERURI);
            $INT = preg_replace('#' . URIFOLDER . '#', '', $INT);
            $INT = str_replace('/', '', $INT);
            $INT = preg_replace('/[^0-9.]+/', '', $INT);

            $this_url = str_replace($INT, '[INT]', $SERVERURI);
            $this_url = str_replace(URIFOLDER, '', $this_url);
        }
        switch ($this_url) {
            case $url_path:
                if (strpos($url_path, '[SLUG]/') !== false) {
                    define('SLUG',$SLUG);
                } elseif (strpos($url_path, '[INT]/') !== false) {
                    define('INT',$INT);
                }
                if (file_exists($file_path)) {
                    include $file_path;
                    die();
                } else {
                    include __DIR__ . '/default_page/error_route.html';
                    die();
                }
                die();
                break;
            default:
        }
    }
}

function RunAll($default)
{
    /*
    foreach (glob(__DIR__."/apps/.../urls.php") as $urls)
    {
        include $urls;
    }
    */
    $global_apps_array = GLOBAPPSARRAY;
    foreach ($global_apps_array as $appname => $apppath) {
        if ($appname != $default && $appname != 'phpr-default') {
            include $apppath . 'urls.php';
        }
    }
    foreach ($global_apps_array as $appname => $apppath) {
        if ($appname == $default) {
            include $apppath . 'urls.php';
        }
    }
}

function response($val, $apptorun){
    $url_path   = $apptorun[0];
    $file_path  = $apptorun[1];
    if($val == 'POST'){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            URI($url_path, $file_path);
        }
    }
    if($val == 'GET'){
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            URI($url_path, $file_path);
        }
    }
}

function request($array){
    foreach($array as $key=>$val){
        $url_path = $key;
        $file_path = $val;
        URI($url_path, $file_path);
    }
}

function route($array){
    $SERVERURI = parse_url($_SERVER['REQUEST_URI']);
    $SERVERURI = $SERVERURI['path'];
    if (URIFOLDER != '/') {
        $this_url = str_replace(URIFOLDER, '', $SERVERURI);
    } else {
        $this_url = $SERVERURI;
    }
    foreach($array as $key=>$val){
        $url_path = $key;
        $file_path = $val;
        foreach($array as $key=>$val){
            $url_path = $key;
            $file_path = $val;
            URI($url_path, $file_path);
        }
    }
}

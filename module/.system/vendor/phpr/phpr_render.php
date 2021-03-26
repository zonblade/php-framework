<?php

namespace phpr\page\render;

class render
{
    protected $_file;
    protected $_data = array();

    public function __construct($file = null)
    {
        $this->_file = $file;
    }

    public function set($key, $value)
    {
        $this->_data[$key] = $value;
        return $this;
    }

    public function render()
    {
        extract($this->_data);
        ob_start();
        include($this->_file);
        return ob_get_clean();
    }
}

function render_php($file_path, $context)
{
    $render = new render($file_path . '.php');
    if (isset($context)) {
        foreach ($context as $key_cu => $cu) {
            $render->set($key_cu, $cu);
        }
    }
    echo $render->render();
}
function path($path, $alias)
{
    return define($alias, $path);
}
function string($file_path)
{
    return $file_path . '.php';
}
function php($file_path)
{
    include $file_path . '.php';
}
function html($file_path)
{
    header('Content-type: text/html');
    include $file_path . '.html';
}
function css($file_path)
{
    header('Content-type: text/css');
    include $file_path . '.css';
}
function js($file_path)
{
    header('Content-type: text/javascript');
    include $file_path . '.js';
}
function POST($type, $param, $value, $file_path, $context)
{
    if (isset($_POST[$param])) {
        if ($_POST[$param] == $value) {
            if ($type == 'php') {
                render_php($file_path, $context);
                die();
            }
            if ($type == 'html') {
                html($file_path);
                die();
            }
            if ($type == 'css') {
                css($file_path);
                die();
            }
            if ($type == 'js') {
                js($file_path);
                die();
            }
        }
    }
}
function GET($type, $param, $value, $file_path, $context)
{
    if ($type == 'redirect') {
        header('Location:' . $param);
    } elseif (isset($_GET[$param])) {
        if ($_GET[$param] == $value) {
            if ($type == 'php') {
                render_php($file_path, $context);
                die();
            }
            if ($type == 'html') {
                html($file_path);
                die();
            }
            if ($type == 'css') {
                css($file_path);
                die();
            }
            if ($type == 'js') {
                js($file_path);
                die();
            }
            if ($type == 'img/jpeg') {
                if (file_exists($file_path)) {
                    $fileSize = filesize($file_path);
                    header("Cache-Control: private");
                    header('Content-type: image/jpeg');
                    header("Content-Length: " . $fileSize);
                    // Output file.
                    readfile($file_path);
                    die();
                } else {
                    echo 'not found';
                    die();
                }
            }
            if ($type == 'img/png') {
                if (file_exists($file_path)) {
                    $fileSize = filesize($file_path);
                    header("Cache-Control: private");
                    header("Content-Type: image/png");
                    header("Content-Length: " . $fileSize);
                    // Output file.
                    readfile($file_path);
                    die();
                } else {
                    echo 'not found';
                    die();
                }
            }
        }
    }
}
function GET_FLAWLESS($type, $file_path)
{
    if ($type == 'php') {
        php($file_path);
        die();
    }
    if ($type == 'html') {
        html($file_path);
        die();
    }
    if ($type == 'css') {
        css($file_path);
        die();
    }
    if ($type == 'js') {
        js($file_path);
        die();
    }
    if ($type == 'img/jpeg') {
        if (file_exists($file_path)) {
            $fileSize = filesize($file_path);
            header("Cache-Control: private");
            header('Content-type: image/jpeg');
            header("Content-Length: " . $fileSize);
            // Output file.
            readfile($file_path);
            die();
        } else {
            echo 'not found';
            die();
        }
    }
    if ($type == 'img/png') {
        if (file_exists($file_path)) {
            $fileSize = filesize($file_path);
            header("Cache-Control: private");
            header("Content-Type: image/png");
            header("Content-Length: " . $fileSize);
            // Output file.
            readfile($file_path);
            die();
        } else {
            echo 'not found';
            die();
        }
    }
}

function response($val, $apptorun)
{
    if ($val == 'POST') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $type       = '';
            $param      = '';
            $value      = '';
            $file_path  = '';
            $context    = [];
            
            foreach($apptorun as $apt => $apv){
                if($apt == 'run'){
                    $file_path = $apv;
                }
                if($apt == 'type'){
                    $type = $apv;
                }
                if($apt == 'init'){
                    foreach($apv as $apkey => $apval){
                        $param = $apkey;
                        $value = $apval;
                    }
                }
                if($apt == 'context'){
                    $context = $apv;
                }
            }
            POST($type, $param, $value, $file_path, $context);
        }
    }
    if ($val == 'GET') {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $type       = '';
            $param      = '';
            $value      = '';
            $file_path  = '';
            $context    = [];
            
            foreach($apptorun as $apt => $apv){
                if($apt == 'run'){
                    $file_path = $apv;
                }
                if($apt == 'type'){
                    $type = $apv;
                }
                if($apt == 'init'){
                    foreach($apv as $apkey => $apval){
                        $param = $apkey;
                        $value = $apval;
                    }
                }
                if($apt == 'context'){
                    $context = $apv;
                }
            }
            GET($type, $param, $value, $file_path, $context);
        }
    }
    if ($val == 'PASS') {
        $file_path = $apptorun[0];
        $context  = $apptorun[1];
        render_php($file_path, $context);
    }
}

<?php
namespace phpr\loop;


class Template
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

function foreach_($array){

    $context    = null;
    $path       = '';
    $fo_return  = null;
    $input      = [];
    $i          = 0;

    foreach($array as $key => $value){
        if($key == 'context'){
            if($value != false){
                $context = $value;
            }
        }
        if($key == 'file'){
            $path = $value;
        }
        if($key == 'loop'){
            $input = $value;
        }
    }

    $template = new Template($path);
    foreach($input as $x_key => $x_val){
        if($context != false){
            foreach($context as $key_cu => $cu){
                $template->set($key_cu  , $cu);
            }
        }
        $template->set('fo_val' , $x_val);
        $template->set('count'  , $i++);
        $fo_return .= $template->render();
    }
    return $fo_return;
}

function for_(){

}

function while_($array){

    $context    = null;
    $path       = '';
    $fo_return  = null;
    $input      = null;
    $i          = 0;

    foreach($array as $key => $value){
        if($key == 'context'){
            if($value != false){
                $context = $value;
            }
        }
        if($key == 'file'){
            $path = $value;
        }
        if($key == 'loop'){
            $input = $value;
        }
    }
    $template = new Template($path);
    while($input){
        if($context != false){
            foreach($context as $key_cu => $cu){
                $template->set($key_cu  , $cu);
            }
        }
        $template->set('count'  , $i++);
        $fo_return .= $template->render();
    }
    return $fo_return;
}
<?php
function autoload($className)
{
    $className = ltrim($className, "\\");
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strripos($className, "\\")) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace("\\", DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.class.php';

    require $fileName;
}

function config($conf){
  $array = explode(".", $conf);
  if(count($array) == 2){
    $config = require_once __DIR__ . "/../Config/" . $array[0] . ".php";
    if(array_key_exists($array[1], $config)){
      return $config[$array[1]];
    }

    return null;
  }
  return null;
}

function view($fileName, $args = null){  
  if(!is_null($args)){
    foreach ($args as $key => $value) {
      $$key = $value;
    }
  }
  
   include  __DIR__ . "/../views/" . $fileName . ".php";
}
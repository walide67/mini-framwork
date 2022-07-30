<?php

$router = new Classes\Router(new Classes\Request);

$router->get('/', function(){
  $data = array(1,2,3);
  return view('index', compact('data'));
});

$router->get('/about', function(){
  return view('about');
});
<?php

namespace Classes;

class App{

  public function __construct()
  {
    echo "<h1>starting App</h1>";
    echo config("config.host");
  }
}
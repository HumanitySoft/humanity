<?php
spl_autoload_register(function($class){
  require_once(__DIR__.'/../core/'.$class.'.php');
});

(new Apps(__DIR__.'/../apps/'))->router();
?>

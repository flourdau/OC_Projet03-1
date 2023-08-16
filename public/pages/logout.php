<?php
  namespace Gen;

  require_once	'../../app/class/Autoload.php';
  Autoload::register();
  $env['conf']['session'] = Session::getInstance();
  $env['conf']['session']->delete('nom');
  $env['conf']['session']->delete('prenom');
  $env['conf']['session']->delete('username');
  Lib::redirect('../index.php');
?>

<?php
  namespace Gen;

  require_once	'../app/class/Autoload.php';
  Autoload::register();
  $env['conf']['session'] = Session::getInstance();
  if (!$env['conf']['session']->read("username"))
    Lib::redirect("pages/login.php");
  Lib::redirect("pages/list.php");

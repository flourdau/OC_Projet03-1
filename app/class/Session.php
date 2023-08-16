<?php
/*
  * Session Management
  */
namespace Gen;

class Session {

  private static $instance;

  function  __construct($config = NULL) {
    if (session_status() == PHP_SESSION_NONE)
       self::$instance = session_start();
    if ($config)
      self::write('config', $config);
    return  self::$instance;
  }

  static  function  getInstance() {
    if (!self::$instance)
      self::$instance = new Session();
    return  self::$instance;
  }

  static  function  write($key, $value) {
    $_SESSION[$key] = $value;
  }

  static  function  read($key) {
    if (isset($_SESSION[$key]))
      return  ($_SESSION[$key]);
    return  NULL;
  }

  static  function  delete($key) {
    unset($_SESSION[$key]);
  }
}

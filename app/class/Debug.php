<?php
/*
* Debug::print_debug(new Debug);
* die;
*/
namespace Gen;

/*
* Debug Functions
*/
class Debug{
  private $tab;

  public function __construct(){
    $this->maxDebug();
    $this->getTab();
  }

  public function  getTab(){
    $tab = $this->tab;
    return ($tab);
  }

  private function ft_ini_php(){
    $inipath = php_ini_loaded_file();

    if ($inipath)
      return ("Loaded php.ini : $inipath");
    else
      return ('A php.ini file is not loaded');
  }

  private function maxDebug(){
    $this->tab = array( 'init'          => $this->ft_ini_php(),
                        'dir'           => __DIR__,
                        'file'          => __FILE__,
                        'namespace'     => __NAMESPACE__,
                        'class'         => __CLASS__,
                        'trait'         => __TRAIT__,
                        'method'        => __METHOD__,
                        'function name' => __FUNCTION__,
                        'line'          => __LINE__,
                        'session'       => $_SESSION,
                        'env'           => $_ENV,
                        'post'          => $_POST,
                        'get'           => $_GET,
                        'files'         => $_FILES,
                        'Int Max'       => PHP_INT_MAX,
                        'cookie'        => $_COOKIE,
                        'server'        => $_SERVER);
  }

  static function debug($value){
    return (Html::surround("pre", NULL, print_r($value, true)));
  }

  static function print_debug($value){
    echo ("<div style=\"background-color:black;color:green\">" . (Html::surround("pre", NULL, print_r($value, true))) . "</div>");
  }

  static function print_debug_die($value){
    die ("<div style=\"background-color:black;color:green\">" . (Html::surround("pre", NULL, print_r($value, true))) . "</div>");
  }
}

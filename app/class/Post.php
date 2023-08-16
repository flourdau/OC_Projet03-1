<?php
/*
  * Lib Functions
  */
namespace Gen;

class  Post  {

  static  function  strClean($str = NULL)  {
    return  preg_replace('#[^[:alnum:]]#u', '', $str);
  }

  static  function  check_unik(&$tabErrors, $str, $db, $table, $field)  {
    $ret = $db->query("SELECT id_user FROM $table WHERE $field = ?", [$str])->fetch();
    if ($ret)
      $tabErrors += Errors::errorTab(0, $field . "unik");
  }

  static  function  verifCleanNom(&$tabErrors, $str = NULL)  {
    $str = self::strClean($str);
    $str = preg_replace('#[[:digit:]]#u', '', $str);
    $str = strtoupper($str);
    $str = strip_tags($str);

    if (strlen($str) < 1 || strlen($str) > 64 || !filter_var($str, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH))
      $tabErrors += Errors::errorTab(0, "nom");
    return $str;
  }

  static  function  verifCleanPrenom(&$tabErrors, $str = NULL)  {
    $str = self::strClean($str);
    $str = preg_replace('#[[:digit:]]#u', '', $str);
    $str = strtolower($str);
    $str = ucfirst($str);
    $str = strip_tags($str);

    if (strlen($str) < 1 || strlen($str) > 64 || !filter_var($str, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH))
      $tabErrors += Errors::errorTab(0, "prenom");
    return $str;
  }

  static  function  forgetUsername(&$tabErrors, $db = NULL, $str = NULL)  {
    $str = self::strClean($str);
    if (strlen($str) < 8 || strlen($str) > 16 || !filter_var($str, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH))
      $tabErrors += Errors::errorTab(0, "username");

    $ret = $db->query("SELECT `id_user`, `question` FROM `users` WHERE `username` = ?", [$str])->fetch();
    if (isset($ret->id_user))
      return $ret;
    return FALSE;
  }

  static  function  checkUsername(&$tabErrors, $db = NULL, $str = NULL)  {
    $str = self::strClean($str);
    if (strlen($str) < 8 || strlen($str) > 16 || !filter_var($str, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH))
      $tabErrors += Errors::errorTab(0, "username");

    $ret = $db->query("SELECT `id_user`, `nom`, `prenom`, `username`, `password` FROM `users` WHERE `username` = ?", [$str])->fetch();
    if (isset($ret->id_user))
      return $ret;
    return FALSE;
  }

  static  function  verifCleanUsername(&$tabErrors, $db = NULL, $str = NULL, $flagUnik = NULL)  {
    $str = self::strClean($str);
    if ($flagUnik)
      self::check_unik($tabErrors, $str, $db, "users", "username");
    if (strlen($str) < 8 || strlen($str) > 16 || !filter_var($str, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH))
      $tabErrors += Errors::errorTab(0, "username");
    return $str;
  }

  static  function  verifCleanQuestion(&$tabErrors, $str = NULL)  {
    $str = trim($str);
    $str = strtolower($str);
    $str = ucfirst($str);
    $str = strip_tags($str);
    $str = addslashes($str);
    if (strlen($str) < 4 || strlen($str) > 128 || !filter_var($str, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH))
      $tabErrors += Errors::errorTab(0, "question");
    return $str;
  }

  static  function  forgetReponse(&$tabErrors, $db, $username = NULL, $question = NULL, $reponse = NULL)  {
    $str = strtolower($reponse);
    $str = ucfirst($reponse);
    $str = strip_tags($str);
    $str = addslashes($str);
    $ret = $db->query("SELECT `id_user`, `nom`, `prenom`, `username` FROM `users` WHERE `username` = ? AND `question` = ? AND `reponse` = ?", [$username, $question, $reponse])->fetch();
    if (isset($ret->id_user))
      return $ret;
    return FALSE;
  }

  static  function  verifCleanReponse(&$tabErrors, $str = NULL)  {
    $str = trim($str);
    $str = strtolower($str);
    $str = ucfirst($str);
    $str = strip_tags($str);
    $str = addslashes($str);
    if (strlen($str) < 4 || strlen($str) > 128 || !filter_var($str, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH))
      $tabErrors += Errors::errorTab(0, "reponse");
    return $str;
  }

  static  function  verifPassword(&$tabErrors, $pass1 = NULL, $pass2 = NULL)  {

    if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,16}$/', $pass1) || empty($pass1) || empty($pass2))
      $tabErrors += Errors::errorTab(0, "pass");

    if (Lib::checkCrypt($_POST['PASSWORD'], $pass))
      return TRUE;
    return FALSE;
  }

  static  function  verifCryptPassword(&$tabErrors, $pass1 = NULL, $pass2 = NULL)  {

    if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,16}$/', $pass1) || empty($pass1) || empty($pass2))
      $tabErrors += Errors::errorTab(0, "pass");
    if ($pass1 !== $pass2)
      $tabErrors += Errors::errorTab(0, "passConf");
    return Lib::crypt($pass1);
  }
}

<?php
/*
  * Errors Management
  */
namespace Gen;

class  Errors {

  static  $setupErrors = [
                          "0" => "Invalid ",
                          "1" => "Config already deployed ",
                          "2" => "File missing : ",
                          "3" => "Creation Log "
                         ];

  static  $errors =      [
                          "0" => "Invalid ",
                          "1" => "Co.db is DOWN!"];

  static  function  setupError($nb_error, $error = NULL) {
    die ("Setup Error : " . self::$setupErrors[$nb_error] . $error . "!<br>");
  }

  static  function  error($nb_error, $error = NULL) {
    return  "Error : ". self::$errors[$nb_error] . $error . "!<br>";
  }

  static  function  errorDie($nb_error, $error = NULL) {
    die  ("Error : ". self::$errors[$nb_error] . $error . "!<br>");
  }

  static  function  errorTab($nb_error, $error = NULL) {
    return  ["$error" => $error . " " . self::$errors[$nb_error] . "!"];
  }
}

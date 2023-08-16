<?php
/*
  * Lib Functions
  */
namespace Gen;

class  Lib  {
  static  function  checkCrypt($post = NULL, $pass = NULL)  {
    return  password_verify(hash('whirlpool', $post), $pass);
  }

  static  function  crypt($pass = NULL)  {
    return  password_hash(hash('whirlpool', $pass), PASSWORD_BCRYPT);
  }

  static  function  redirect($page)  {
     die  (header("location: $page"));
  }
}

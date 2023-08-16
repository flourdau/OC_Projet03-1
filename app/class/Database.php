<?php
/*
  * Creation & Request Database
  */
namespace Gen;

use \PDO;
use \PDOException;

class  Database {

  private $db;

  function  __construct($DB_USER, $DB_PASSWORD, $DB_DSN, $DB_HOST = 'localhost') {
    try {
      $this->db = new PDO("mysql:dbname=$DB_DSN;host=$DB_HOST", $DB_USER, $DB_PASSWORD);
      $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    }
    catch (PDOException $e) {
      die (Errors::Error(0));
    }
  }

  function  __destruct() {
    return $this->db;
  }

  function  query($query, $params = false) {
    try {
      if ($params) {
        $req = $this->db->prepare($query);
        $req->execute($params);
      }
      else {
        $req = $this->db->query($query);
      }
    }
    catch (PDOException $error) {
      die ($error->getMessage());
    }
    return $req;
  }

  function  lastInsertId() {
    return $this->db->lastInsertId();
  }
}

<?php

/**
 *
 */
class DB
{
  private static $instance = NULL;
  private static $dbh      = "mysql:host=localhost;dbname=fliptest;";
  private static $db_user  = 'root';
  private static $db_pass  = '';

  function __construct()
  {
    // code...
  }

  public static function initialize() {
    if (!self::$instance)
    {
      self::$instance = new PDO(self::$dbh, self::$db_user, self::$db_pass);
      self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    return self::$instance;
  }

  public function query($query,$data='')
  {
    if (self::$instance)
    {
      $stmt= self::$instance->prepare($query);
      if (empty($data)) {
        if ($stmt->execute()) {
          $stmt->closeCursor();
          return 'true';
        }
      }else {
        if ($stmt->execute($data)) {
          $stmt->closeCursor();
          return 'true';
        }
      }
      return 'false';
    }
    else {
      echo 'No connection';
      return 'No connection';
    }
  }
}

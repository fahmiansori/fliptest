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
        $stmt->execute();
      }else {
        $stmt->execute($data);
      }
      $stmt->closeCursor();
    }
    else {
      echo 'No connection';
      return 'No connection';
    }
  }
}

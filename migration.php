<?php
require_once 'DB.php';
/**
 *
 */
class Migration
{
  public $sql;
  public $db;

  function __construct()
  {
    $this->db = DB::initialize();

    $this->sql = "CREATE TABLE IF NOT EXISTS `disbursement` (
      `id` INT(11) NOT NULL,
      `amount` INT(11) NULL DEFAULT NULL,
      `status` VARCHAR(50) NULL DEFAULT NULL,
      `timestam` DATETIME NOT NULL,
      `bank_code` VARCHAR(10) NULL DEFAULT NULL,
      `account_number` VARCHAR(20) NULL DEFAULT NULL,
      `beneficiary_name` VARCHAR(100) NULL DEFAULT NULL,
      `remark` VARCHAR(50) NULL DEFAULT NULL,
      `receipt` VARCHAR(255) NULL DEFAULT NULL,
      `time_served` DATETIME NULL DEFAULT NULL,
      `fee` INT(11) NULL DEFAULT NULL,
      INDEX `id` (`id`)
      )
      ";
  }

  public function importTable()
  {
    $runQ = $this->db->query($this->sql);
    var_dump($runQ);
    if ($runQ == 'true') {
      return 'Table imported';
    }else {
      return 'Failed while importing table!';
    }
  }
}

$mig = new Migration();
echo $mig->importTable();

<?php
require_once 'DB.php';
/**
 *
 */
class FlipTest
{
  public $baseUrl;
  public $secretKey;
  public $page;
  public $transaction_id;
  public $bank_code;
  public $account_number;
  public $amount;
  public $remark;

  public $db;

  function __construct($args)
  {
    $this->db = DB::initialize();

    $this->baseUrl = 'https://nextar.flip.id';
    $this->secretKey = 'HyzioY7LP6ZoO7nTYKbG8O4ISkyWnX1JvAEVAhtWKZumooCzqp41';
    $this->page = '/disburse';
    $this->transaction_id = '';
    if (isset($args['transaction_id'])) {
      $this->transaction_id = $args['transaction_id'];
    }

    $bank_code = '';
    $account_number = '';
    $amount = '';
    $remark = '';
    if (isset($args['bank_code'])) {
      $bank_code = $args['bank_code'];
    }
    if (isset($args['account_number'])) {
      $account_number = $args['account_number'];
    }
    if (isset($args['amount'])) {
      $amount = $args['amount'];
    }
    if (isset($args['remark'])) {
      $remark = $args['remark'];
    }

    $this->bank_code = $bank_code;
    $this->account_number = $account_number;
    $this->amount = $amount;
    $this->remark = $remark;
  }

  public function sendData()
  {
    if (empty($this->bank_code) || empty($this->account_number) || empty($this->amount) || empty($this->remark)) {
      return 'Some fields are empty!';
    }

    $ch = curl_init();
    $auth = base64_encode($this->secretKey.':');
    $headers = array(
        'Content-Type: application/x-www-form-urlencoded',
        'Authorization: basic '.$auth
    );
    $urlEndPoint = $this->baseUrl.$this->page;
    $data = 'bank_code='.$this->bank_code.'&account_number='.$this->account_number.'&amount='.$this->amount.'&remark='.$this->remark;
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_URL, $urlEndPoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $output = curl_exec($ch);
    $dataResponse = json_decode($output);

    if ($this->db) {
      /*
      $data = [
        ':id' => $dataResponse->id,
        ':amount' => $dataResponse->amount,
        ':status' => $dataResponse->status,
        ':timestam' => $dataResponse->timestamp,
        ':bank_code' => $dataResponse->bank_code,
        ':account_number' => $dataResponse->account_number,
        ':beneficiary_name' => $dataResponse->beneficiary_name,
        ':remark' => $dataResponse->remark,
        ':receipt' => $dataResponse->receipt,
        ':time_served' => $dataResponse->time_served,
        ':fee' => $dataResponse->fee
      ];
      */

      $query = "INSERT INTO disbursement (id, amount, status, timestam, bank_code, account_number, beneficiary_name, remark, receipt, time_served, fee)
      VALUES (".$dataResponse->id.", ".$dataResponse->amount.", '".$dataResponse->status."', '".$dataResponse->timestamp."',
         '".$dataResponse->bank_code."', '".$dataResponse->account_number."', '".$dataResponse->beneficiary_name."',
          '".$dataResponse->remark."', '".$dataResponse->receipt."', '".$dataResponse->time_served."', ".$dataResponse->fee.")";
      $this->db->query($query);
    }

    curl_close($ch);

    return 'done';
  }

  public function getStatus()
  {
    if (empty($this->transaction_id)) {
      return 'Require transaction ID!';
    }

    $ch = curl_init();
    $auth = base64_encode($this->secretKey.':');
    $headers = array(
        'Content-Type: application/x-www-form-urlencoded',
        'Authorization: basic '.$auth
    );
    $urlEndPoint = $this->baseUrl.$this->page.'/'.$this->transaction_id;
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_URL, $urlEndPoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    $dataResponse = json_decode($output);
    if ($this->db) {
      /*
      $data = [
        $dataResponse->status,
        $dataResponse->receipt,
        $dataResponse->time_served,
        $dataResponse->id
      ];
      */

      $query = "UPDATE disbursement SET status='".$dataResponse->status."', receipt='".$dataResponse->receipt."', time_served='".$dataResponse->time_served."'
      WHERE id=".$dataResponse->id;
      $this->db->query($query);
    }
    curl_close($ch);

    return 'done';
  }
}

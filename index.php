<?php
require_once 'FlipTest.php';

$args = getopt(null, ["req::","transaction_id::","bank_code::","account_number::","amount::","remark::"]);

$request = 'send';
if (isset($args['req'])) {
  if ($args['req'] == 'check-disbursement') {
    $request = 'check_disbursement_status';
  }
}

$flip = new FlipTest($args);
if ($request == 'send') {
  echo $flip->sendData();
}elseif ($request == 'check_disbursement_status') {
  echo $flip->getStatus();
}else {
  echo "Unknow request.";
}

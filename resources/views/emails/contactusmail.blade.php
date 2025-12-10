<?php
$root = $_SERVER['DOCUMENT_ROOT'];
$file = file_get_contents($root . '/mailers/contactmail.html', 'r');

$file = str_replace('#customer_name', $data['customer_name'], $file);
$file = str_replace('#customer_email', $data['customer_email'], $file);
$file = str_replace('#customer_phone', $data['customer_phone'], $file);
$file = str_replace('#brand', $data['brand'], $file);
$file = str_replace('#model', $data['model'], $file);
$file = str_replace('#device_condition', $data['device_condition'], $file);
$file = str_replace('#imei_1', $data['imei_1'], $file);
$file = str_replace('#imei_2', $data['imei_2'], $file);
$file = str_replace('#expected_amt', $data['expected_amt'], $file);
$file = str_replace('#message', $data['message'], $file);
$file = str_replace('#pickup_date', date('d-m-Y', strtotime($data['pickup_date'])), $file);
$file = str_replace('#pickup_time', $data['pickup_time'], $file);
$file = str_replace('#address', $data['address'], $file);

echo $file;
?>

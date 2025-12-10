<?php

use App\Http\Controllers\api;

$root = $_SERVER['DOCUMENT_ROOT'];
$file = file_get_contents($root . '/mailers/inquiryemail.html', 'r');

$file = str_replace('#Name', $data['Name'], $file);
$file = str_replace('#Email', $data['Email'], $file);
$file = str_replace('#Mobile', $data['Mobile'], $file);
$file = str_replace('#Message', $data['Message'], $file);
$file = str_replace('#Subject', $data['Subject'], $file);

echo $file;

// exit();

?>

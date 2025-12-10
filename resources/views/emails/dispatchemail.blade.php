<?php

$root = $_SERVER['DOCUMENT_ROOT'];
$server = $_SERVER['SERVER_NAME'];

// Determine file path based on server
if ($server === '127.0.0.1') {
    $filePath = $root . '/mailers/dispatchemail.html';
} elseif ($server === 'www.getdemo.in') {
    $filePath = $root . '/oro_veda/mailers/dispatchemail.html';
} else {
    $filePath = $root . '/mailers/dispatchemail.html';
}

// Check if file exists
if (!file_exists($filePath)) {
    die('Email template not found.');
}

// Load template
$file = file_get_contents($filePath);

$file = str_replace('#orderNo', $data['orderNo'], $file);
$file = str_replace('#courierName', $data['courierName'], $file);
$file = str_replace('#docketNo', $data['docketNo'], $file);
$file = str_replace('#link', $data['link'], $file);

// Output final content
echo $file;

?>

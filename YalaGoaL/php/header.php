<?php
session_start(); 
?>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/style.css">
  <?php
    $page_title = isset($page_title) ? $page_title : 'YalaGoaL';
  ?>
  <title><?= $page_title ?></title>
</head>

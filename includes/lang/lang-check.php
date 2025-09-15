<?php
session_start();
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'si';
}

$lang = $_SESSION['lang'];
$translations = include("lang/{$lang}.php");

if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
    $url = strtok($_SERVER['REQUEST_URI'], '?');
    header('Location: ' . $url);
    exit();
}

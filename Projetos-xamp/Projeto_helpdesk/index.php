<?php
session_start();

$page = 'login';
$pageTitle = 'App Help Desk — Entrar';
$loginStatus = $_GET['login'] ?? null;
$contentTemplate = __DIR__ . '/templates/login.php';
$showNav = false;
$mainClass = 'd-flex align-items-center justify-content-center min-vh-100 py-5';

require __DIR__ . '/templates/layout.php';

<?php

require_once __DIR__ . '/../includes/app.php';

$_SESSION = [];

session_destroy();

header('Location: ' . base_url('/'));
exit;
?>
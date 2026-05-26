<?php
require_once 'config.php';

// Zruš session
session_unset();
session_destroy();

// Přesměruj na login
presmeruj('index.php');
?>
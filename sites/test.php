<?php
session_start();
include '../config/config.php';
echo '<pre>';
print_r($_SESSION);
echo '</pre>';
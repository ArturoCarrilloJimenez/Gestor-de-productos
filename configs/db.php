<?php
session_start();
require_once 'config.php';

$conn = new mysqli("db", $MYSQL_USER, $MYSQL_PASSWORD, $MYSQL_DATABASE);

// Check conexión
if ($conn->connect_error) die("Conexión fallida: " . $conn->connect_error);

<?php
session_start();

// Create conexión
$MYSQL_USER='user';
$MYSQL_PASSWORD='user_password';
$MYSQL_DATABASE='my_database';

$conn = new mysqli("db", $MYSQL_USER, $MYSQL_PASSWORD, $MYSQL_DATABASE);

// Check conexión
if ($conn->connect_error) die("Conexión fallida: " . $conn->connect_error);

<?php

$host = "localhost";
$user = "root";
$password = "root";
$database = "aula_crud";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Erro na conexão com o banco: " . $conn->connect_error);
};

$conn->set_charset("utf8mb4");
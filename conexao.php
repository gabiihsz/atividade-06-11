<?php
$host = 'localhost'; 
$db = 'db_gereciamentos';
$user = 'localhost'; 
$pass = '';     
try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
    die();
}

?>
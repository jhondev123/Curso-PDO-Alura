<?php

use Alura\Pdo\infrastructer\persistence\ConnectionCreator;

require_once "vendor/autoload.php";

$pdo = ConnectionCreator::createConnection();

$sql = "DELETE FROM students WHERE id = ?;";
$statement = $pdo->prepare($sql);
$statement->bindValue(1,4,PDO::PARAM_INT);

var_dump($statement->execute());

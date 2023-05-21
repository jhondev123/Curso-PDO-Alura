<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\infrastructer\persistence\ConnectionCreator;


require_once 'vendor/autoload.php';

$pdo = ConnectionCreator::createConnection();

$student = new Student(null,
    "Jhonattan",
    new DateTimeImmutable('2003-06-09'));

$sqlInsert = "INSERT INTO students (nome,birth_day)VALUES(:name,:birth_day)";
$statement=$pdo->prepare($sqlInsert);
$statement->bindValue(":name",$student->name());
$statement->bindValue(":birth_day",$student->birthDate()->format("Y-m-d"));
if($statement->execute()){
    echo "Deu certo";
}
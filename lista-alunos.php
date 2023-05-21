<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\infrastructer\persistence\ConnectionCreator;

require_once "vendor/autoload.php";

$pdo = ConnectionCreator::createConnection();
$repository = new \Alura\Pdo\infrastructer\Repository\PdoStudentRepository($pdo);
$studentList = $repository->allStudents();


    var_dump($studentList);






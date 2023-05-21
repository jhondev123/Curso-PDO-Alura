<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\infrastructer\persistence\ConnectionCreator;
use Alura\Pdo\infrastructer\Repository\PdoStudentRepository;

require_once "vendor/autoload.php";
try {
$connection = ConnectionCreator::createConnection();

$studentRepository = new PdoStudentRepository($connection);

    $connection->beginTransaction();

    $aSutend = new Student(null, "Fiat uno", new DateTimeImmutable("2001-10-23"));
    $studentRepository->saveStudent($aSutend);

    $anotherStudend = new Student(null, "Gol quadradado", new DateTimeImmutable("1976-09-14"));
    $studentRepository->saveStudent($anotherStudend);
}catch (PDOException $e){
    echo $e->getMessage();
    $connection->rollBack();
}
$connection->commit();



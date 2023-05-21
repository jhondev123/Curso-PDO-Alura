<?php

use Alura\Pdo\Domain\Model\Student;

require_once 'vendor/autoload.php';

$student = new Student(
    null,
    'Jhonattan curtarelli',
    new \DateTimeImmutable('2003-06-09')
);

echo $student->age();

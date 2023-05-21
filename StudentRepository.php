<?php

namespace Alura\Pdo\Domain\Repository;

use Alura\Pdo\Domain\Model\Student;

interface StudentRepository
{
    public function students():array;

    public function studentsBirth_day(\DateTimeInterface $birthday):array;

    public function saveStudent(Student $student):bool;

    public function removeStudent(Student $student):bool;

    public function studentsWithPhone():array;
}
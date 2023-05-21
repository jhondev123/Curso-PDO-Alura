<?php
require_once "vendor/autoload.php";
use Alura\Pdo\infrastructer\persistence\ConnectionCreator;

$pdo = ConnectionCreator::createConnection();
echo "Connectei";
$pdo->exec("INSERT INTO phones(area_code,number,student_id) VALUES ('24','9933-8406',1),('45','999884422',2)");
exit();

$createTable =
    "CREATE TABLE IF NOT EXISTS students (
        id INTEGER PRIMARY KEY,
        nome TEXT,
        birth_day TEXT
                                     
     );
     CREATE TABLE IF NOT EXISTS phones (
        id INTEGER PRIMARY KEY,
        area_code TEXT,
        number TEXT,
        student_id INTEGER,
        FOREIGN KEY(student_id) REFERENCES students(id)
    );

";
$pdo->exec($createTable);



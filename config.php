<?php

$host =  '127.0.0.1';
$db = 'todo_list';
$users = 'root';
$pass = '';
$charset = 'utf8';



$dsn = "mysql:host=$host;dbname=$db;charset=$charset";



$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// PDO это расширение в php для взаимодействия с bd(БаЗа ДАННЫХ)

$pdo = new PDO( $dsn, $users, $pass, $opt);






?>
<?php
// MySQL ONLY - db_455fprc7h
$host='localhost'; $dbname='db_455fprc7h'; $user='root'; $pass='';
try{
  $pdo=new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4",$user,$pass,[
    PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC
  ]);
}catch(Exception $e){ die("DB Error: ".$e->getMessage()); }
?>

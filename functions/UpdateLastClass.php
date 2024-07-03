<?php
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
require_once "../connect.php" ;
function UpdateLastClass($Code,$Last_class) {
  global $con ;
    $UpdateLastClass = $con->prepare("UPDATE students set Last_class=?  WHERE Code =?");
    $UpdateLastClass->execute(array($Last_class,$Code));
    $UpdateLastClassrowCount = $UpdateLastClass->rowCount();
    if ($UpdateLastClassrowCount > 0) {
      return json_encode($Code.'Update Last_class sucssefuly');
    }
}

$students_stmt = $con->prepare("SELECT * FROM students");
$students_stmt->execute();
$students = $students_stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($students as $value) {

$history_stmt = $con->prepare("SELECT * FROM `history` WHERE `S_code` =?  ORDER BY `date` DESC");
$history_stmt->execute(array($value['Code']));
$Last_class = $history_stmt->fetch(PDO::FETCH_ASSOC);
$Last_class_rowCount = $history_stmt->rowCount();


  if ($Last_class_rowCount > 0) {
    if ($Last_class['date'] == NULL OR $Last_class['date'] == 'NULL' OR $Last_class['date'] == '' OR $Last_class['date'] == '1970-01-01' ) {
      $Last_class = 'Undefined';
    }else {
      $Last_class = $Last_class['date'];
    }
    echo UpdateLastClass($value['Code'],$Last_class)."<br>";

  }else {
    echo UpdateLastClass($value['Code'],'Undefined')."<br>";
  }
}

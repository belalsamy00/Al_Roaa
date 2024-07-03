<?php
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
require_once "../connect.php" ;
function UpdateFirstClass($Code,$First_class) {
  global $con ;
    $UpdateFirstClass = $con->prepare("UPDATE students set First_class=?  WHERE Code =?");
    $UpdateFirstClass->execute(array($First_class,$Code));
    $UpdateFirstClassrowCount = $UpdateFirstClass->rowCount();
    if ($UpdateFirstClassrowCount > 0) {
      return json_encode($Code.'Update First_class sucssefuly');
    }
}

$students_stmt = $con->prepare("SELECT * FROM students");
$students_stmt->execute();
$students = $students_stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($students as $value) {

$history_stmt = $con->prepare("SELECT * FROM `history` WHERE `S_code` =?  ORDER BY `date` ASC");
$history_stmt->execute(array($value['Code']));
$First_class = $history_stmt->fetch(PDO::FETCH_ASSOC);
$First_class_rowCount = $history_stmt->rowCount();


if ($value['First_class'] =='Undefined') {
  if ($First_class_rowCount > 0) {
    if ($First_class['date'] == NULL OR $First_class['date'] == 'NULL' OR $First_class['date'] == '' OR $First_class['date'] == '1970-01-01' ) {
      $First_class = 'Undefined Date';
    }else {
      $First_class = $First_class['date'];
    }
    echo UpdateFirstClass($value['Code'],$First_class)."<br>";

  }
}
}

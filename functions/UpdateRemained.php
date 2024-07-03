<?php
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
require_once "../connect.php" ;
function UpdateRemained($Code,$Remained) {
  global $con ;
    $UpdateRemained = $con->prepare("UPDATE students set Remained=?  WHERE Code =?");
    $UpdateRemained->execute(array($Remained,$Code));
    $UpdateRemainedrowCount = $UpdateRemained->rowCount();
    if ($UpdateRemainedrowCount > 0) {
      return json_encode($Code.'Update Remained sucssefuly');
    }
}

$students_stmt = $con->prepare("SELECT * FROM students");
$students_stmt->execute();
$students = $students_stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($students as $value) {
  $GetRemained = $con->prepare("SELECT Remained FROM Remained  WHERE Code =?");
  $GetRemained->execute(array($value['Code']));
  $Remained = $GetRemained->fetch();
  $Remained_rowCount = $GetRemained->rowCount();
  if ($Remained_rowCount > 0) {
    if ($Remained['Remained'] == NULL OR $Remained['Remained'] == 'NULL' OR $Remained['Remained'] == '' ) {
      $Remained = 0;
    }else {
      $Remained = $Remained['Remained'];
    }
    echo UpdateRemained($value['Code'],$Remained)."<br>";

  }else {
    echo UpdateRemained($value['Code'],0)."<br>";
  }
}

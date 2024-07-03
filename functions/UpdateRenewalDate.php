<?php
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
require_once "../connect.php" ;
function UpdateRenewalDate($Code,$Renewal_date) {
  global $con ;
    $UpdateRenewal_date = $con->prepare("UPDATE students set Renewal_date=?  WHERE Code =?");
    $UpdateRenewal_date->execute(array($Renewal_date,$Code));
    $UpdateRenewal_daterowCount = $UpdateRenewal_date->rowCount();
    if ($UpdateRenewal_daterowCount > 0) {
      return json_encode($Code.'Update Renewal_date sucssefuly');
    }
}

$students_stmt = $con->prepare("SELECT * FROM students");
$students_stmt->execute();
$students = $students_stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($students as $value) {
  $GetRenewal_date = $con->prepare("SELECT Renewal_date FROM Remained  WHERE Code =?");
  $GetRenewal_date->execute(array($value['Code']));
  $Renewal_date = $GetRenewal_date->fetch();
  $Renewal_rowCount = $GetRenewal_date->rowCount();
  if ($Renewal_rowCount > 0) {
    if ($Renewal_date['Renewal_date'] == NULL OR $Renewal_date['Renewal_date'] == 'NULL'
     OR $Renewal_date['Renewal_date'] == '2000-01-01' 
     OR $Renewal_date['Renewal_date'] == '2000-02-01' 
     OR $Renewal_date['Renewal_date'] == '2000-03-01' 
     OR $Renewal_date['Renewal_date'] == '2000-04-01' 
     OR $Renewal_date['Renewal_date'] == '2000-05-01' 
     OR $Renewal_date['Renewal_date'] == '2000-06-01' 
     ) {
      $Renewal_date = 'Undefined';
    }else {
      $Renewal_date = $Renewal_date['Renewal_date'];
    }
    echo UpdateRenewalDate($value['Code'],$Renewal_date)."<br>";
  }else {
    echo UpdateRenewalDate($value['Code'],'Undefined')."<br>";
  }
}

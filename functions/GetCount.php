<?php

require_once "connect.php" ;
function GetStudentscount($Who)
{
  global $con;
  if ($Who =="All") {
    $stmt = " ";
    $execute = array() ;
  }else {
    $stmt = "WHERE `Who`= ?";
    $execute = array($Who) ;
  }

  $GetStudentscount_stmt = $con->prepare("SELECT * FROM students $stmt ");
  $GetStudentscount_stmt->execute($execute);
  $GetStudentscount = $GetStudentscount_stmt->rowCount();
  
  return $GetStudentscount ;
  
}
function GetStudentsActivecount($Who)
{
  global $con;
  if ($Who =="All") {
    $stmt = "WHERE `status`=?";
    $execute = array("Active") ;
  }else {
    $stmt = "WHERE `Who`= ? AND `status`=?";
    $execute = array($Who,"Active") ;
  }

  $Active_stmt = $con->prepare("SELECT * FROM students $stmt ");
  $Active_stmt->execute($execute);
  $Active_count = $Active_stmt->rowCount();
  
  return $Active_count ;
  
}
function GetStudentsCancelcount($Who)
{
  global $con;
  if ($Who =="All") {
    $stmt = "WHERE `status`=?";
    $execute = array("Cancel") ;
  }else {
    $stmt = "WHERE `Who`= ? AND `status`=?";
    $execute = array($Who,"Cancel") ;
  }

  $Cancel_stmt = $con->prepare("SELECT * FROM students $stmt ");
  $Cancel_stmt->execute($execute);
  $Cancel_count = $Cancel_stmt->rowCount();
  
  return $Cancel_count ;
  
}
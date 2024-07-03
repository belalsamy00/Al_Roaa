<?php
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
require_once "connect.php" ;

function ActivitySend($ForWho,$Message,$Who,$Status)
{
  global $con;


  
    $Timestamp = date('Y-m-d H:i:s');
    $ID = rand(0,1000).date("d").rand(9,99);
    $ActivitySend = $con->prepare("INSERT INTO `Activity` (`Timestamp`, `ID` , `Status` , `Who` , `ForWho` , `Message` ) VALUES (?,?,?,?,?,?)");
    $ActivitySend->execute(array($Timestamp,$ID,$Status,$Who,$ForWho,$Message));
    $ActivitySend_count = $ActivitySend->rowCount();
  

  
};
if ($_SERVER ['REQUEST_METHOD'] =='POST' AND isset($_POST['myAjax'])){
  $Code = $_POST['Code'];
  $Message = $_POST['Message'];
  $Who = $_POST['Who'];
  $Status = $_POST['Status'];

  ActivitySend($Code,$Message,$Who,$Status);
  echo $Message;
  exit; 
}
<?php
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "connect.php" ;

function NotificationSend($ForWho,$Message,$Url,$Category)
{
  global $con;

  $Seen = NULL ;
  $Url = $Url ;

  $WhoExplodearray = explode(',',$ForWho);

  for ($i=0; $i < count($WhoExplodearray) ; $i++) { 
    $Timestamp = date('Y-m-d H:i:s');
    $ID = rand(0,1000).date("d").rand(9,99);
    $GetNotification = $con->prepare("INSERT INTO `Notification` (`Timestamp`, `ID` , Seen , `Url` , `ForWho` , `Message` , `Category`) VALUES (?,?,?,?,?,?,?)");
    $GetNotification->execute(array($Timestamp,$ID,$Seen,$Url,$WhoExplodearray[$i],$Message,$Category));
    $GetNotification_count = $GetNotification->rowCount();
  }

  
};
if ($_SERVER ['REQUEST_METHOD'] =='POST' AND isset($_POST['ForWho']) AND isset($_POST['Message'])AND isset($_POST['Category'])){
  $ForWho = $_POST['ForWho'];
  $Message = $_POST['Message'];
  $Category = $_POST['Category'];

  if (!is_array($ForWho)) {
    $ForWho = array($ForWho);
  }
  $ForWhostr = str_replace(" ",",",implode(" ",$ForWho));
  NotificationSend($ForWhostr,$Message,"No",$Category);
  $_SESSION['Emessage'] = 'تم إرسال الإشعار بنجاح  ';
  header('Location: ' . $_SERVER['HTTP_REFERER']);
  exit; 
}
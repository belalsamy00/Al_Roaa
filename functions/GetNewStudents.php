<?php
if ($_SERVER ['REQUEST_METHOD'] =='POST') {
  require_once "../connect.php" ;
}else {
  require_once "connect.php" ;
}
function GetNewStudents() 
{
  global $con;

  $GetNewStudents = $con->prepare("SELECT *  FROM  NewStudents ORDER BY `Timestamp` DESC");
  $GetNewStudents->execute(array());
  $RenewalCount = $GetNewStudents->rowCount();
  $GetNewStudentsfetchAll = $GetNewStudents->fetchAll(PDO::FETCH_ASSOC);
  
  return $GetNewStudentsfetchAll  ;
  
}
function ActivitySend($ID)
{
  global $con;

    $ActivitySend = $con->prepare("UPDATE NewStudents SET `Status`=? WHERE ID  = ?");
    $ActivitySend->execute(array(1,$ID));
    $ActivitySend_count = $ActivitySend->rowCount();
};
if ($_SERVER ['REQUEST_METHOD'] =='POST' AND isset($_POST['myAjax'])){
  $ID = $_POST['ID'];
  ActivitySend($ID);
  echo "تم تأكيد التواصل مع الطالب";
  exit; 
}
if ($_SERVER ['REQUEST_METHOD'] =='POST' AND isset($_POST['GetNewStudents'])){
      $Data = [];
      foreach (GetNewStudents() as $key => $value) {
      $Data[$key]['ID'] = $value['ID'];
      $Data[$key]['Timestamp'] = $value['Timestamp'];
      $Data[$key]['Country'] = $value['Country'];
      $Data[$key]['Email'] = $value['Email'];
      $Data[$key]['Name'] = $value['Name'];
      $Data[$key]['Phone'] = $value['Phone'];
      $Data[$key]['Sex']= $value['Sex'];
      $Data[$key]['Status']= $value['Status'];
      }


    $GetNewStudents = $con->prepare("SELECT *  FROM  NewStudents WHERE Status =?");
    $GetNewStudents->execute(array(0));
    $RenewalCount = $GetNewStudents->rowCount();

      array_unshift($Data,(object)[
      'Total' => $RenewalCount
    ]);

  echo json_encode($Data);
  exit; 
}
if ($_SERVER ['REQUEST_METHOD'] =='POST' AND isset($_POST['GetNewStudentsCount'])){

    $GetNewStudents = $con->prepare("SELECT *  FROM  NewStudents WHERE Status =?");
    $GetNewStudents->execute(array(0));
    $RenewalCount = $GetNewStudents->rowCount();

    $Data=[];
      array_unshift($Data,(object)[
      'Total' => $RenewalCount
    ]);

  echo json_encode($Data);
  exit; 
}
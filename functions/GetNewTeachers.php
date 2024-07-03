<?php
if ($_SERVER ['REQUEST_METHOD'] =='POST') {
  require_once "../connect.php" ;
}else {
  require_once "connect.php" ;
}
function GetNewTeachers() 
{
  global $con;

  $GetNewTeachers = $con->prepare("SELECT *  FROM  NewTeachers ORDER BY `Timestamp` DESC");
  $GetNewTeachers->execute(array());
  $RenewalCount = $GetNewTeachers->rowCount();
  $GetNewTeachersfetchAll = $GetNewTeachers->fetchAll(PDO::FETCH_ASSOC);
  
  return $GetNewTeachersfetchAll  ;
  
}
function ActivitySend($ID)
{
  global $con;

    $ActivitySend = $con->prepare("UPDATE NewTeachers SET `Status`=? WHERE ID  = ?");
    $ActivitySend->execute(array(1,$ID));
    $ActivitySend_count = $ActivitySend->rowCount();
};
if ($_SERVER ['REQUEST_METHOD'] =='POST' AND isset($_POST['myAjax'])){
  $ID = $_POST['ID'];
  ActivitySend($ID);
  echo "تم تأكيد التواصل مع المعلم";
  exit; 
}
if ($_SERVER ['REQUEST_METHOD'] =='POST' AND isset($_POST['GetNewTeachers'])){
      $Data = [];
      foreach (GetNewTeachers() as $key => $value) {
      $Data[$key]['ID'] = $value['ID'];
      $Data[$key]['Timestamp'] = $value['Timestamp'];
      $Data[$key]['Address'] = $value['Address'];
      $Data[$key]['Email'] = $value['Email'];
      $Data[$key]['Name'] = $value['Name'];
      $Data[$key]['Phone'] = $value['Phone'];
      $Data[$key]['Sex'] = $value['Sex'];
      $Data[$key]['Age'] = $value['Age'];
      $Data[$key]['Qualifications'] = $value['Qualifications'];
      $Data[$key]['AnotherJob'] = $value['AnotherJob'];
      $Data[$key]['HafsLicense'] = $value['HafsLicense'];
      $Data[$key]['AnotherLicense'] = $value['AnotherLicense'];
      $Data[$key]['MaritalStatus'] = $value['MaritalStatus'];
      $Data[$key]['Documents'] = $value['Documents'];
      $Data[$key]['Status'] = $value['Status'];
      }


    $GetNewTeachers = $con->prepare("SELECT *  FROM  NewTeachers WHERE Status =?");
    $GetNewTeachers->execute(array(0));
    $RenewalCount = $GetNewTeachers->rowCount();

      array_unshift($Data,(object)[
      'Total' => $RenewalCount
    ]);

  echo json_encode($Data);
  exit; 
}
if ($_SERVER ['REQUEST_METHOD'] =='POST' AND isset($_POST['GetNewTeachersCount'])){

    $GetNewTeachers = $con->prepare("SELECT *  FROM  NewTeachers WHERE Status =?");
    $GetNewTeachers->execute(array(0));
    $RenewalCount = $GetNewTeachers->rowCount();

    $Data=[];
      array_unshift($Data,(object)[
      'Total' => $RenewalCount
    ]);

  echo json_encode($Data);
  exit; 
}
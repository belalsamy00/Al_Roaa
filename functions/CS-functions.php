<?php
  if (file_exists("../connect.php")) {
    require_once "../connect.php" ;
  }
  if (file_exists("connect.php")) {
    require_once "connect.php" ;
  }
function GetExceedingInDate($Who)
{

    global $con;
    if ($Who =="All") {
      $stmt = " ";
      $execute = array("Active",date("Y-m-d")) ;
    }else {
      $stmt = "AND Who= ?";
      $execute = array("Active",date("Y-m-d"),$Who) ;
    }
  
    $GetExceedingInDate = $con->prepare("SELECT * FROM students WHERE `status`=? AND Renewal_date <= ? AND Remained > 0 $stmt");
    $GetExceedingInDate->execute($execute);
    $GetExceedingInDatefetch = $GetExceedingInDate->fetchAll(PDO::FETCH_ASSOC);
    
    $ExceedingInDate = [];
    foreach ($GetExceedingInDatefetch as $key => $value) {
    
        $ExceedingInDate[$key]['LastClasses'] =  $value['Last_class'] ;
        $ExceedingInDate[$key]['Code'] = $value['Code'] ;
        $ExceedingInDate[$key]['Remained'] = $value['Remained'] ;
        $ExceedingInDate[$key]['Renewal_date'] = date('Y-m-d', strtotime($value['Renewal_date'])) ;
      
    }
    return $ExceedingInDate  ;
}

function GetNotExceedingInDate($Who)
{

    global $con;
    if ($Who =="All") {
      $stmt = " ";
      $execute = array("Active",date("Y-m-d")) ;
    }else {
      $stmt = "AND Who= ?";
      $execute = array("Active",date("Y-m-d"),$Who) ;
    }
  
    $GetNotExceedingInDate = $con->prepare("SELECT * FROM students WHERE `status`=? AND Renewal_date <= ? AND Remained < 0 $stmt");
    $GetNotExceedingInDate->execute($execute);
    $GetNotExceedingInDatefetch = $GetNotExceedingInDate->fetchAll(PDO::FETCH_ASSOC);
    
    $NotExceedingInDate = [];
    foreach ($GetNotExceedingInDatefetch as  $key => $value) {
    
        $NotExceedingInDate[$key]['LastClasses'] =  $value['Last_class'] ;
        $NotExceedingInDate[$key]['Code'] = $value['Code'] ;
        $NotExceedingInDate[$key]['Remained'] = $value['Remained'] ;
        $NotExceedingInDate[$key]['Renewal_date'] = date('Y-m-d', strtotime($value['Renewal_date'])) ;
      
    }
    return $NotExceedingInDate  ;
}

function GetNotInRenewal($Who)
{
  global $con;
  if ($Who =="All") {
    $stmt = "";
    $execute = array('Undefined',"Active" ) ;
  }else {
    $stmt = "AND Who = ?";
    $execute = array('Undefined',"Active",$Who  ) ;
  }

  $GetNotInRenewal = $con->prepare("SELECT *  FROM students   WHERE Renewal_date=?  AND `status` =?$stmt  ");
  $GetNotInRenewal->execute($execute);
  $GetNotInRenewalrowCount = $GetNotInRenewal->rowCount();
  $GetNotInRenewalf = $GetNotInRenewal->fetchAll(PDO::FETCH_ASSOC);
    
  $NotInRenewal = [];
  foreach ($GetNotInRenewalf as $key => $value) {
  
      $NotInRenewal[$key]['Code'] = $value['Code'] ;
      $NotInRenewal[$key]['Remained'] = '?' ;
      $NotInRenewal[$key]['Renewal_date'] = '?';

    
  }
  return $NotInRenewal  ;
  
}

function GetExceedingOutDate($Who)
{

    global $con;
    if ($Who =="All") {
      $stmt = " ";
      $execute = array("Active",date("Y-m-d")) ;
    }else {
      $stmt = "AND Who= ?";
      $execute = array("Active",date("Y-m-d"),$Who) ;
    }
  
    $GetExceedingOutDate = $con->prepare("SELECT * FROM  students WHERE `status`=? AND Renewal_date > ? AND Remained > 0 $stmt");
    $GetExceedingOutDate->execute($execute);
    $GetExceedingOutDate_count = $GetExceedingOutDate->rowCount();
    $GetExceedingOutDatefetch = $GetExceedingOutDate->fetchAll(PDO::FETCH_ASSOC);
    
    $ExceedingOutDate = [];
    foreach ($GetExceedingOutDatefetch as $key => $value) {
    
        $ExceedingOutDate[$key]['LastClasses'] =  $value['Last_class'] ;
        $ExceedingOutDate[$key]['Code'] = $value['Code'] ;
        $ExceedingOutDate[$key]['Remained'] = $value['Remained'] ;
        $ExceedingOutDate[$key]['Renewal_date'] = date('Y-m-d', strtotime($value['Renewal_date'])) ;
      
    }
    return $ExceedingOutDate  ;
}

function GetRenewal($Who) 
{
  global $con;
  if ($Who =="All") {
    $stmt = " ";
    $execute = array("Active",date("Y-m-d")) ;
  }else {
    $stmt = "AND Who= ?";
    $execute = array("Active",date("Y-m-d"),$Who) ;
  }
  $GetRenewal = $con->prepare("SELECT *  FROM  students   WHERE `status`=? AND Renewal_date <= ? $stmt");
  $GetRenewal->execute($execute);
  $RenewalCount = $GetRenewal->rowCount();
  $GetRenewalfetch = $GetRenewal->fetchAll(PDO::FETCH_ASSOC);

  $date = date("Y-m-01 H:i:s");
  $Activity = $con->prepare("SELECT ForWho FROM Activity WHERE `Status` = ? AND `Timestamp` >  ?");
  $Activity->execute(array(1,"$date %"));
  $Activity_fetch = $Activity->fetchAll(PDO::FETCH_ASSOC);  

  $Renewal = [];
  foreach ($GetRenewalfetch as $key => $value) {

      $Renewal[$key]['LastClasses'] =  $value['Last_class'] ;
      $Renewal[$key]['Code'] = $value['Code'] ;
      $Renewal[$key]['ID'] = $value['ID'] ;
      $Renewal[$key]['Remained'] = $value['Remained'] ;
      $Renewal[$key]['Renewal_date'] = date('Y-m-d', strtotime($value['Renewal_date'])) ;

      $Activity = $con->prepare("SELECT ForWho FROM Activity WHERE ForWho=? AND `Status` = ? AND `Timestamp` >=  ? LIMIT 1");
      $Activity->execute(array($value['Code'],1,date('Y-m-d', strtotime($value['Renewal_date']))));
      $Activity_fetch = $Activity->rowCount();  

      if ($Activity_fetch > 0 ) {
        $Renewal[$key]['Status'] = 1;
      }else {
        $Renewal[$key]['Status'] = 0 ;
      }
    
  }
  return $Renewal  ;
  
}

function GetLastClasses($Who)
{
  global $con;
  if ($Who =="All") {
    $stmt = " ";
    $execute = array("Active",date("Y-m-d",strtotime("-15 day"))) ;
  }else {
    $stmt = "AND Who= ?";
    $execute = array("Active",date("Y-m-d",strtotime("-15 day")),$Who) ;
  }

  $GetLastClasses = $con->prepare("SELECT * FROM  students WHERE `status`=? AND Last_class < ?  $stmt ");
  $GetLastClasses->execute($execute);
  $GetLastClasses_count = $GetLastClasses->rowCount();
  $GetLastClassesfetch = $GetLastClasses->fetchAll(PDO::FETCH_ASSOC);
  
  $LastClasses = [];
  foreach ($GetLastClassesfetch as $key => $value) {
  
      $LastClasses[$key]['Code'] = $value['Code'];
      $LastClasses[$key]['Remained'] = $value['Remained'] ;
      $LastClasses[$key]['Last_class'] = $value['Last_class'] ;
      $LastClasses[$key]['Renewal_date'] = date('Y-m-d', strtotime($value['Renewal_date'])) ;
    
  }
  return $LastClasses  ;
  
}

function GetCancel($Who)
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
  $Cancel_fetch = $Cancel_stmt->fetchAll();
  $Cancel_count = $Cancel_stmt->rowCount();
  $GetCancel = [];

  if ($Cancel_count > 0 ) {
    foreach ($Cancel_fetch as $key => $value) {
            $GetCancel[$key]['LastClasses'] = $value['Last_class'] ;
            $GetCancel[$key]['Code'] = $value['Code'] ;
            $GetCancel[$key]['Remained'] = $value['Remained'];
            $GetCancel[$key]['Renewal_date'] = $value['Renewal_date'] ;

      }
    }

  return $GetCancel ;
  
}

function UpdateFirstClass($Code,$First_class) {
    global $con ;
    $UpdateFirstClass = $con->prepare("UPDATE students set First_class=?  WHERE Code =?");
    $UpdateFirstClass->execute(array($First_class,$Code));
    $UpdateFirstClassrowCount = $UpdateFirstClass->rowCount();
    if ($UpdateFirstClassrowCount > 0) {
    return json_encode($Code.'Update First_class sucssefuly');
    }
}

function UpdateLastClass($Code,$Last_class) {
    global $con ;
    $UpdateLastClass = $con->prepare("UPDATE students set Last_class=?  WHERE Code =?");
    $UpdateLastClass->execute(array($Last_class,$Code));
    $UpdateLastClassrowCount = $UpdateLastClass->rowCount();
    if ($UpdateLastClassrowCount > 0) {
    return json_encode($Code.'Update Last_class sucssefuly');
    }
}

function IncreaseRemained($code) {
    global $con ;
    $ID = rand(0,1000).date("d").rand(9,99);
    $GetRemained = $con->prepare("SELECT * FROM students WHERE Code=? ");
    $GetRemained->execute(array($code));
    $GetRemained_count = $GetRemained->rowCount();
    $GetRemainedfetch = $GetRemained->fetch();

    if ($GetRemained_count > 0) {
    $num = $GetRemainedfetch['Remained']+1;
    $remained = $con->prepare("UPDATE  students SET  `Remained`=?  WHERE Code = ?");
    $remained->execute(array($num,$code));
    $remained_count = $remained->rowCount();

    }

}

function DecreaseRemained($code) {
    global $con ;
    $ID = rand(0,1000).date("d").rand(9,99);
    $GetRemained = $con->prepare("SELECT * FROM students WHERE Code=? ");
    $GetRemained->execute(array($code));
    $GetRemained_count = $GetRemained->rowCount();
    $GetRemainedfetch = $GetRemained->fetch();

    if ($GetRemained_count > 0) {
    $num = $GetRemainedfetch['Remained']-1;
    $remained = $con->prepare("UPDATE  students SET  `Remained`=?  WHERE Code = ?");
    $remained->execute(array($num,$code));
    $remained_count = $remained->rowCount();

    }

}

function UpdateRenewalDate($Code,$Renewal_date) {
    global $con ;
    $UpdateRenewal_date = $con->prepare("UPDATE students set Renewal_date=?  WHERE Code =?");
    $UpdateRenewal_date->execute(array($Renewal_date,$Code));
    $UpdateRenewal_daterowCount = $UpdateRenewal_date->rowCount();
    if ($UpdateRenewal_daterowCount > 0) {
    return json_encode($Code.'Update Renewal_date sucssefuly');
    }
}

function SetCancel($student)
{
  global $con ;

  $Cancel_stmt = $con->prepare("SELECT * FROM class WHERE `Student`= ? AND `status` = ? AND `type` =2 ");
  $Cancel_stmt->execute(array($student,"Active") );
  $Cancel_count = $Cancel_stmt->rowCount();
  if ($Cancel_count == 0) {
    $update_stmt = $con->prepare("SELECT * FROM students WHERE Code = ?  ");
    $update_stmt->execute(array($student));
    $update=$update_stmt->fetch();
    $update_count = $update_stmt->rowCount();
    if ($update_count > 0 ) {
        $ID = $update['ID'];
        $stmt = $con->prepare("UPDATE  students SET `status` ='Cancel'    WHERE ID =? ");
        $stmt->execute(array($ID));
        $xcount = $stmt->rowCount();
      }
  }
}

function SetActive($student)
{
  global $con ;

  $Cancel_stmt = $con->prepare("SELECT * FROM class WHERE `Student`= ? AND `status` = ?  ");
  $Cancel_stmt->execute(array($student,"Active") );
  $Cancel_count = $Cancel_stmt->rowCount();
  if ($Cancel_count > 0) {
    $update_stmt = $con->prepare("SELECT * FROM students WHERE Code = ?  ");
    $update_stmt->execute(array($student));
    $update=$update_stmt->fetch();
    $update_count = $update_stmt->rowCount();
    if ($update_count > 0 ) {
        $ID = $update['ID'];
        $stmt = $con->prepare("UPDATE  students SET `status` ='Active'    WHERE ID =? ");
        $stmt->execute(array($ID));
        $xcount = $stmt->rowCount();
      }
  }
}

function SetActual($student)
{
  global $con ;
  $Actual_stmt = $con->prepare("SELECT * FROM students WHERE `Code`= ? ");
  $Actual_stmt->execute(array($student) );
  $Actual_count = $Actual_stmt->rowCount();
  $Actual_Days=$Actual_stmt->fetch();
  
  $Classes_Days_stmt = $con->prepare("SELECT SUM(Days) FROM class WHERE `Student`= ? AND `status` = ?  ");
  $Classes_Days_stmt->execute(array($student,"Active") );
  $Classes_Days_count = $Classes_Days_stmt->rowCount();
  $Classes_Days=$Classes_Days_stmt->fetch();
  if ($Actual_Days['status'] == "Cancel") {
    $Classes_Days['SUM(Days)'] = 0;
  }
  if ($Classes_Days['SUM(Days)'] == NULL) {
    $Classes_Days['SUM(Days)'] = 0;
  }
  if ($Actual_count == 0) {
    $Actual_Days['Actual_Days'] = 0;
  }
  if ($Actual_Days['Actual_Days'] === $Classes_Days['SUM(Days)']) {
  }else {
  $ID = $Actual_Days['ID'] ;
  $UPDATE_stmt = $con->prepare("UPDATE  students SET `Actual_Days` =? WHERE ID =? ");
  $UPDATE_stmt->execute(array( $Classes_Days['SUM(Days)'] , $ID));
  
  }
}

if (isset($_GET['GetLastClasses'])) {
  $GetLastClasses['Total'] = count(GetLastClasses($_GET['Who']));
  echo json_encode($GetLastClasses);
}

if (isset($_GET['GetRenewal'])) {
    $GetRenewal['Total'] = count(GetRenewal($_GET['Who']));
    echo json_encode($GetRenewal);
}

if (isset($_GET['GetExceedingOutDate'])) {
    $GetExceedingOutDate['Total'] = count(GetExceedingOutDate($_GET['Who']));
    echo json_encode($GetExceedingOutDate);
}
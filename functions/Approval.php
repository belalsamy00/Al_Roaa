<?php

require_once "connect.php" ;

function Approval()
{

  global $con;

  $Timestamp = date('Y-m-d H:i:s');
  $ID = rand(0,1000).date("d").rand(9,99);

  if (date("Y-m-d") >= date("Y-m-10") And date("Y-m-d") < date("Y-m-20")) {
    if (!isset($_SESSION['Approval'.date("Y-m-10")])) {
      $GetApproval = $con->prepare("SELECT * FROM Journal WHERE `Date_of_Payment`=? AND `Type` = ? ");
      $GetApproval->execute(array(date("Y-m-10"),6));
      $GetApproval_count = $GetApproval->rowCount();
      if ($GetApproval_count == 0) {
  
        $UpdateJournal = $con->prepare("UPDATE  Journal SET `Type` = ? WHERE `Type` = ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=?");
        $UpdateJournal->execute(array(5,1,date("Y-m-01"),date("Y-m-10")));
        $UpdateJournal_count = $UpdateJournal->rowCount();
  
        $Increase = $con->prepare("INSERT INTO Journal (`Timestamp`, `ID` , Code , `Name` , `Renewal_VS_Trail` , Date_of_Payment , `Amount`,`Payment_Way`, `Note` , `Subscription`,`Type`,Who) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
        $Increase->execute(array($Timestamp , $ID, "Approval" , "Approval"  , "Approval" ,date("Y-m-10") ,0, 'Cash' ,"Approval",0,6,"Approval"));
        $Increase_count = $Increase->rowCount();
  
        $_SESSION['Approval'.date("Y-m-10")] = 'Approval';
      }else {
        $_SESSION['Approval'.date("Y-m-10")] = 'Approval';
      }
    }
  }elseif (date("Y-m-d") >= date("Y-m-20") ) {

    if (!isset($_SESSION['Approval'.date("Y-m-20")])) {
      $GetApproval = $con->prepare("SELECT * FROM Journal WHERE `Date_of_Payment`=? AND `Type` = ? ");
      $GetApproval->execute(array(date("Y-m-20"),6));
      $GetApproval_count = $GetApproval->rowCount();
      if ($GetApproval_count == 0) {
  
        $UpdateJournal = $con->prepare("UPDATE  Journal SET `Type` = ? WHERE `Type` = ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=?");
        $UpdateJournal->execute(array(5,1,date("Y-m-11"),date("Y-m-20")));
        $UpdateJournal_count = $UpdateJournal->rowCount();
  
        $Increase = $con->prepare("INSERT INTO Journal (`Timestamp`, `ID` , Code , `Name` , `Renewal_VS_Trail` , Date_of_Payment , `Amount`,`Payment_Way`, `Note` , `Subscription`,`Type`,Who) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
        $Increase->execute(array($Timestamp , $ID, "Approval" , "Approval"  , "Approval" ,date("Y-m-20") ,0, 'Cash' ,"Approval",0,6,"Approval"));
        $Increase_count = $Increase->rowCount();
  
        $_SESSION['Approval'.date("Y-m-20")] = 'Approval';
      }else {
        $_SESSION['Approval'.date("Y-m-20")] = 'Approval';
      }
    }

  }elseif (date("Y-m-d") < date("Y-m-10")  ) {

    if (!isset($_SESSION['Approval'.date("Y-m-28",strtotime("-1 month"))])) {
      $GetApproval = $con->prepare("SELECT * FROM Journal WHERE `Date_of_Payment`=? AND `Type` = ? ");
      $GetApproval->execute(array(date("Y-m-28",strtotime("-1 month")),6));
      $GetApproval_count = $GetApproval->rowCount();
      echo $GetApproval_count ;
      if ($GetApproval_count == 0) {
  
        $UpdateJournal = $con->prepare("UPDATE  Journal SET `Type` = ? WHERE `Type` = ? AND `Date_of_Payment`>=? AND `Date_of_Payment` < ?");
        $UpdateJournal->execute(array(5,1,date("Y-m-21",strtotime("-1 month")),date("Y-m-01")));
        $UpdateJournal_count = $UpdateJournal->rowCount();
  
        $Increase = $con->prepare("INSERT INTO Journal (`Timestamp`, `ID` , Code , `Name` , `Renewal_VS_Trail` , Date_of_Payment , `Amount`,`Payment_Way`, `Note` , `Subscription`,`Type`,Who) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
        $Increase->execute(array($Timestamp , $ID, "Approval" , "Approval"  , "Approval" ,date("Y-m-28",strtotime("-1 month")) ,0, 'Cash' ,"Approval",0,6,"Approval"));
        $Increase_count = $Increase->rowCount();
  
        $_SESSION['Approval'.date("Y-m-28",strtotime("-1 month"))] = 'Approval';
      }else {
        $_SESSION['Approval'.date("Y-m-28",strtotime("-1 month"))] = 'Approval';
      }
    }

  }


  
}
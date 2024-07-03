<?php

require_once "connect.php" ;
function Temporary()
{
  global $con;
  $Journal = $con->prepare("SELECT * FROM Journal  WHERE `Type`=? AND Date_of_Payment<=? ");
  $Journal->execute(array(8, date("Y-m-d") ));
  $Journal_count = $Journal->rowCount();
  $Journalfetch = $Journal->fetchAll();
  if ($Journal_count > 0) {
    foreach ($Journalfetch as $key => $value) {

      $GetRemained = $con->prepare("SELECT * FROM students WHERE Code=? ");
      $GetRemained->execute(array($value['Code']));
      $GetRemained_count = $GetRemained->rowCount();
      $GetRemainedfetch = $GetRemained->fetch();
  
  
      if ($GetRemained_count > 0) {
        $num = $GetRemainedfetch['Remained']+$value['Subscription'];

        $Journal = $con->prepare("UPDATE  Journal SET  `Type`=? WHERE ID = ?");
        $Journal->execute(array(9,$value['ID']));

        
        $remained = $con->prepare("UPDATE  students SET  `Remained`=? WHERE Code = ?");
        $remained->execute(array($num,$value['Code']));


      }
    }
  }

  
}
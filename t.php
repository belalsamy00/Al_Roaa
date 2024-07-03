<?php
    include "connect.php"; 
    // $stmt = $con->prepare("SELECT * FROM history");
    // $stmt->execute(array());
    // $stmtn =$stmt->fetchAll();
    // $count = $stmt->rowCount();

    // $rowCount = 0 ;
    // $i = 1 ;
    // foreach ($stmtn as $key => $value) {

    //   $Timestamp = $value["Timestamp"];
    //   $stringTimestamp = str_replace(" ","",$Timestamp);
    //   $stringTimestamp2 = str_replace(":","",$stringTimestamp);
    //   $id = $i;

    //   $ArchivedStudents = $con->prepare("UPDATE history SET `ID`=? WHERE ID=?");
    //   $ArchivedStudents->execute(array($id,$value['ID']));
    //   $rowCount += $ArchivedStudents->rowCount();
    //   $i++;
    // }
    // echo $rowCount ;

    $stmt2 = $con->prepare("SELECT * FROM ArchivedHistory");
    $stmt2->execute(array());
    $stmt2n =$stmt2->fetchAll();
    $count2 = $stmt2->rowCount();
    $x = 22657 ;
    $rowCount2 = 0 ;
    foreach ($stmt2n as $key => $value) {

      $Timestamp2 = $value["Timestamp"];
      $stringTimestamp2 = str_replace(" ","",$Timestamp2);
      $stringTimestamp22 = str_replace(":","",$stringTimestamp2);
      $id2 = $x;

      $ArchivedStudents2 = $con->prepare("UPDATE IGNORE ArchivedHistory SET `ID`=? WHERE ID=?");
      $ArchivedStudents2->execute(array($id2,$value['ID']));
      $rowCount2 += $ArchivedStudents2->rowCount();
      $x++;
    }
    echo $rowCount2 ;
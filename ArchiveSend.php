<?php
  include "connect.php" ;
  require_once "functions/CS-functions.php";
function ArchiveHistory(){
    global $con ;
    $day_120 = date("Y-m-d H:i:s",strtotime("-120 day")) ;

    $ArchiveHistory = $con->prepare("INSERT INTO ArchivedHistory SELECT * FROM history WHERE `Timestamp` < ?");
    $ArchiveHistory->execute(array($day_120));
    $ArchiveHistory_rowCount = $ArchiveHistory->rowCount();
      
    $DELETEArchivedHistory = $con->prepare("DELETE FROM history WHERE `Timestamp` < ?");
    $DELETEArchivedHistory->execute(array($day_120));
    $DELETEArchivedHistory_rowCount = $DELETEArchivedHistory->rowCount();
    echo "<h1> Archive History Done </h1>" ;
    ArchiveSend();
};
function UpdateFirst(){
    global $con ;
    $students_stmt = $con->prepare("SELECT * FROM students WHERE First_class = ?");
    $students_stmt->execute(array('NORecord'));
    $students = $students_stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($students as $value) {

        $history_stmt = $con->prepare("SELECT * FROM `history` WHERE `S_code` =?  ORDER BY `date` ASC");
        $history_stmt->execute(array($value['Code']));
        $First_class = $history_stmt->fetchAll(PDO::FETCH_ASSOC);
        $First_class_rowCount = $history_stmt->rowCount();

        
        if ($value['First_class'] =='NORecord') {
          
          if ($First_class_rowCount > 0) {
              foreach ($First_class as  $val) {
    
                if ($val['date'] == NULL OR $val['date'] == 'NULL' OR $val['date'] == '' OR $val['date'] == '1970-01-01' ) {
                  continue ;
                }else {
                  $FirstClassDate = $val['date'];
                  break ;
                }
      
              }

              UpdateFirstClass($value['Code'],$FirstClassDate)."<br>";
        
          }else {
              UpdateFirstClass($value['Code'],'NORecord')."<br>";
          }
        }
    }
    echo "<h1> Update First Done </h1>";
    UpdateLast();
};
function UpdateLast(){
    global $con ;
    $students_stmt = $con->prepare("SELECT * FROM students");
    $students_stmt->execute(array());
    $students = $students_stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($students as $value) {

        $history_stmt = $con->prepare("SELECT * FROM `history` WHERE `S_code` =?  ORDER BY `date` DESC");
        $history_stmt->execute(array($value['Code']));
        $Last_class = $history_stmt->fetch();
        $Last_class_rowCount = $history_stmt->rowCount();


        if ($Last_class_rowCount > 0) {
            if ($Last_class['date'] == NULL OR $Last_class['date'] == 'NULL' OR $Last_class['date'] == '' OR $Last_class['date'] == '1970-01-01' ) {
            $Last_class = 'Incorrect';
            }else {
            $Last_class = $Last_class['date'];
            }
              UpdateLastClass($value['Code'],$Last_class)."<br>";

        }else {
              UpdateLastClass($value['Code'],'NORecord')."<br>";
        }
    }
    echo "<h1> Update Last Done </h1>";
    ArchiveHistory();

};
function ArchiveSend(){ 
    global $con ;
    $students_stmt = $con->prepare("SELECT * FROM students");
    $students_stmt->execute(array());
    $students = $students_stmt->fetchAll(PDO::FETCH_ASSOC); 
    foreach ($students as $value) {
      $day_120 = date("Y-m-d",strtotime("-120 day")) ;
      $day_7 = date("Y-m-d",strtotime("-7 day")) ;
      $Activity = $con->prepare("SELECT * FROM Activity WHERE ForWho = ? AND `Status` =? AND `Timestamp` >= ? ORDER BY `Timestamp` DESC");
      $Activity->execute(array($value['Code'],2,$day_7));
      $Activity_count = $Activity->rowCount();
      $Activity_fetch = $Activity->fetchAll();
      if ($Activity_count == 0) {
        if ($value['Last_class'] == 'NORecord' AND $value['First_class']== 'NORecord' ) {
            $ArchivedStudents = $con->prepare("INSERT INTO ArchivedStudents SELECT * FROM students WHERE ID = ?");
            $ArchivedStudents->execute(array($value['ID']));
            $ArchivedStudents_rowCount = $ArchivedStudents->rowCount();
  
            $DELETEArchivedStudents = $con->prepare("DELETE FROM students WHERE ID = ?");
            $DELETEArchivedStudents->execute(array($value['ID']));
            $DELETEArchivedStudents_rowCount = $DELETEArchivedStudents->rowCount();
                           
            $DELETEArchivedclass = $con->prepare("DELETE FROM `class` WHERE Student = ?");
            $DELETEArchivedclass->execute(array($value['Code']));
            $DELETEArchivedclass_rowCount = $DELETEArchivedclass->rowCount();
           
            $DELETEArchivedActivity = $con->prepare("DELETE FROM Activity WHERE ForWho = ?");
            $DELETEArchivedActivity->execute(array($value['Code']));
            $DELETEArchivedActivity_rowCount = $DELETEArchivedActivity->rowCount();
           
            $DELETEArchivedRescheduleRequest = $con->prepare("DELETE FROM RescheduleRequest WHERE Student = ?");
            $DELETEArchivedRescheduleRequest->execute(array($value['Code']));
            $DELETEArchivedRescheduleRequest_rowCount = $DELETEArchivedRescheduleRequest->rowCount();
        }else {
            if ($value['Last_class'] <= $day_120 ) {
                $ArchivedHistory = $con->prepare("INSERT INTO ArchivedHistory SELECT * FROM history WHERE S_code = ?");
                $ArchivedHistory->execute(array($value['Code']));
                $ArchivedHistory_rowCount = $ArchivedHistory->rowCount();

                $DELETEArchivedHistory = $con->prepare("DELETE FROM history WHERE S_code = ?");
                $DELETEArchivedHistory->execute(array($value['Code']));
                $DELETEArchivedHistory_rowCount = $DELETEArchivedHistory->rowCount();

                $ArchivedStudents = $con->prepare("INSERT INTO ArchivedStudents SELECT * FROM students WHERE ID = ?");
                $ArchivedStudents->execute(array($value['ID']));
                $ArchivedStudents_rowCount = $ArchivedStudents->rowCount();

                $DELETEArchivedStudents = $con->prepare("DELETE FROM students WHERE ID = ?");
                $DELETEArchivedStudents->execute(array($value['ID']));
                $DELETEArchivedStudents_rowCount = $DELETEArchivedStudents->rowCount();

                $DELETEArchivedclass = $con->prepare("DELETE FROM class WHERE Student = ?");
                $DELETEArchivedclass->execute(array($value['Code']));
                $DELETEArchivedclass_rowCount = $DELETEArchivedclass->rowCount();

                $DELETEArchivedActivity = $con->prepare("DELETE FROM Activity WHERE ForWho = ?");
                $DELETEArchivedActivity->execute(array($value['Code']));
                $DELETEArchivedActivity_rowCount = $DELETEArchivedActivity->rowCount();

                $DELETEArchivedRescheduleRequest = $con->prepare("DELETE FROM RescheduleRequest WHERE Student = ?");
                $DELETEArchivedRescheduleRequest->execute(array($value['Code']));
                $DELETEArchivedRescheduleRequest_rowCount = $DELETEArchivedRescheduleRequest->rowCount();

              }
              
        }
        $ArchivedStudents = $con->prepare("UPDATE ArchivedStudents SET `status`=?");
        $ArchivedStudents->execute(array('Cancel'));
        $ArchivedStudents_rowCount = $ArchivedStudents->rowCount();
      }

    }
    echo "<h1> Archive Send Done </h1>";
};
UpdateFirst();
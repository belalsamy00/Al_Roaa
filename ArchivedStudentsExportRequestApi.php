<?php
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
require_once "connect.php" ;
require_once "ActivitySend.php" ;
require_once "NotificationSend.php" ;

function ArchivedStudentsExportRequest($Code,$Who)
{
  global $con ;

  $Timestamp = date('Y-m-d H:i:s');
  $ID = rand(0,1000).date("d").rand(9,99);

  $Increase = $con->prepare("INSERT INTO ArchivedStudentsExport (`Timestamp`, `ID` , Code , `status`, Who ) VALUES (?,?,?,?,?)");
  $Increase->execute(array($Timestamp , $ID, $Code , 1 ,$Who ));
  $Increase_count = $Increase->rowCount();
  if ($Increase_count > 0 ) {
    $Notification = " طلب استخراج من الأرشيف جديد ";
    NotificationSend("MA-1",$Notification,"ArchivedStudentsExportRequest"," طلبات الأرشيف ");
    $_SESSION['Emessage'] = 'تم ارسال الطلب بنجاح ';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }else {
    $_SESSION['Emessage'] =  'لم يتم ارسال الطلب برجاء المحاولة مرة اخرى ';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }
  
}
function ArchivedStudentsExportRequestAccept($ID)
{
  global $con ;


    $ExportRequest = $con->prepare("SELECT * FROM ArchivedStudentsExport WHERE ID=? ");
    $ExportRequest->execute(array($ID));
    $ExportRequest_count = $ExportRequest->rowCount();
    $ExportRequestfetch = $ExportRequest->fetch();

    if ($ExportRequest_count > 0) {
      $students = $con->prepare("SELECT * FROM ArchivedStudents WHERE Code=? ");
      $students->execute(array($ExportRequestfetch['Code']));
      $students_count = $students->rowCount();
      $studentsfetch = $students->fetch();
      if ($students_count > 0) {
        $ArchivedStudents = $con->prepare("INSERT INTO students SELECT * FROM ArchivedStudents WHERE ID = ?");
        $ArchivedStudents->execute(array($studentsfetch['ID']));
        $ArchivedStudents_rowCount = $ArchivedStudents->rowCount();

        $DELETEArchivedStudents = $con->prepare("DELETE FROM ArchivedStudents WHERE ID = ?");
        $DELETEArchivedStudents->execute(array($studentsfetch['ID']));
        $DELETEArchivedStudents_rowCount = $DELETEArchivedStudents->rowCount();

        $ArchivedHistory = $con->prepare("INSERT INTO history SELECT * FROM ArchivedHistory WHERE S_code = ?");
        $ArchivedHistory->execute(array($studentsfetch['Code']));
        $ArchivedHistory_rowCount = $ArchivedHistory->rowCount();

        $DELETEArchivedHistory = $con->prepare("DELETE FROM ArchivedHistory WHERE S_code = ?");
        $DELETEArchivedHistory->execute(array($studentsfetch['Code']));
        $DELETEArchivedHistory_rowCount = $DELETEArchivedHistory->rowCount();

        $Journal = $con->prepare("UPDATE  ArchivedStudentsExport SET  `status`=? WHERE ID = ?");
        $Journal->execute(array(2,$ID));
        $Journal_count = $Journal->rowCount();
        if ($Journal_count > 0) {
          $Notification = " تم قبول طلب الأستخراج الخاص بالطالب ".$studentsfetch['Code'];
          $CodeExplode = explode(' ',$studentsfetch['Code']);
          $StudentCodeExplode = $CodeExplode[0] ;
          NotificationSend($studentsfetch['Who'],$Notification,"search?Code=".$StudentCodeExplode," طلبات الأستخراج ");
          $Message = "تم استخراج الكود من الأرشيف";
          ActivitySend($studentsfetch['Code'],$Message,'Automated',2);
  
          $_SESSION['Emessage'] = ' تم قبول الطلب بنجاح ';
          header('Location: ' . $_SERVER['HTTP_REFERER']);
          exit;
        }else {
          $_SESSION['Emessage'] =  ' لم يتم قبول الطلب برجاء المراجعة اولا ثم المحاوله مره اخرى ';
          header('Location: ' . $_SERVER['HTTP_REFERER']);
          exit;
        }

      }else {
        $_SESSION['Emessage'] =  'هذا الطالب غير موجود بالأرشيف';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }
    }
  
}
function ArchivedStudentsExportRequestRefuse($ID)
{
  global $con ;

      $GetJournalSettlement = $con->prepare("SELECT * FROM ArchivedStudentsExport WHERE ID=? ");
      $GetJournalSettlement->execute(array($ID));
      $GetJournalSettlementfetch = $GetJournalSettlement->fetch();

      $Journal = $con->prepare("UPDATE  ArchivedStudentsExport SET  `status`=? WHERE ID = ?");
      $Journal->execute(array(3,$ID));
      $Journal_count = $Journal->rowCount();

      $students = $con->prepare("SELECT * FROM ArchivedStudents WHERE Code=? ");
      $students->execute(array($GetJournalSettlementfetch['Code']));
      $students_count = $students->rowCount();
      $studentsfetch = $students->fetch();
      
      if ($Journal_count > 0) {
        $Notification = " تم رفض طلب التسوية الخاص بالطالب ".$studentsfetch['Code'];
        NotificationSend($studentsfetch['Who'],$Notification,"ArchivedStudentsExportRequest"," طلبات التسوية ");
        $_SESSION['Emessage'] = ' تم رفض الطلب بنجاح ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }else {
        $_SESSION['Emessage'] =  ' لم يتم رفض الطلب برجاء المراجعة اولا ثم المحاوله مره اخرى ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }

  
}

if ($_SERVER ['REQUEST_METHOD']== 'POST') {
}elseif (isset($_GET["ExportRequest"])) {
  $Code         = $_GET["ExportRequest"] ;
  $Who         = $_GET["Who"] ;
  ArchivedStudentsExportRequest($Code,$Who);
}elseif (isset($_GET["Accept"])) {
  $ID           = $_GET["Accept"] ;
  ArchivedStudentsExportRequestAccept($ID);

}elseif (isset($_GET["Refuse"])) {
  $ID = $_GET["Refuse"] ;
  ArchivedStudentsExportRequestRefuse($ID);
}else {
     header('Location: index');  exit;  
}









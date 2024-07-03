<?php
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
require_once "connect.php" ;
require_once "ActivitySend.php" ;
require_once "NotificationSend.php" ;
function RenewalDate($Code,$Renewal_date)
{
  global $con ;

  $Active_stmt = $con->prepare("SELECT * FROM students WHERE Code=? LIMIT 1 ");
  $Active_stmt->execute(array($Code));
  $Active_count = $Active_stmt->rowCount();
  $Activefetch = $Active_stmt->fetch();
  if ($Active_count > 0 ) {
   
    $UpdateRenewal_date = $con->prepare("UPDATE students set Renewal_date=?  WHERE Code =?");
    $UpdateRenewal_date->execute(array($Renewal_date,$Code));
    $UpdateRenewal_daterowCount = $UpdateRenewal_date->rowCount();
      if ($UpdateRenewal_daterowCount > 0) {
        $_SESSION['Emessage'] = ' تم تحديث تاريخ التجديد ';
        header('Location: CustomerService');
        exit;
      }else {
        $_SESSION['Emessage'] = ' لم يتم تحديث تاريخ التجديد برجاء المراجعة اولا ثم المحاولة مره اخرى ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }
    
  }else {
    $_SESSION['Emessage'] = 'الطالب غير موجود';
    header('Location: CustomerService');
    exit;
  }
  
}
function DeletAll($ID)
{
  global $con ;
  $IDExplode = str_replace(",","','",$ID);
  $GetToDeleteJournal = $con->prepare("SELECT * FROM Journal  WHERE ID IN ('$IDExplode')");
  $GetToDeleteJournal->execute(array());
  $GetToDeleteJournal_count = $GetToDeleteJournal->rowCount();
  $GetToDeleteJournal_fetch = $GetToDeleteJournal->fetchAll();
  if ($GetToDeleteJournal_count > 0) {
    foreach ($GetToDeleteJournal_fetch as $key => $value) {
      $GetToDelete = $con->prepare("SELECT * FROM students WHERE Code=? ");
      $GetToDelete->execute(array($value['Code']));
      $GetToDelete_count = $GetToDelete->rowCount();
      $GetToDelete_fetch = $GetToDelete->fetch();
      if ($GetToDelete_count > 0) {
  
        $num = $GetToDelete_fetch['Remained']+$value['Subscription'];
        $Renewal_date = $GetToDelete_fetch['Renewal_date'];
        $Next_Date_of_Payment = date('Y-m-d', strtotime($Renewal_date. ' - 1 months'));
  
        $UpdateRemained = $con->prepare("UPDATE students SET  `Remained`=? , `Renewal_date`=? WHERE Code = ?");
        $UpdateRemained->execute(array($num, $Next_Date_of_Payment,$value['Code']));
        $UpdateRemained_count = $UpdateRemained->rowCount();
  
        $DeleteJournal = $con->prepare("DELETE FROM Journal WHERE ID IN ('$IDExplode')");
        $DeleteJournal->execute(array());
        $DeleteJournal_count = $DeleteJournal->rowCount();
  
      }
    }
    if ($UpdateRemained_count > 0 AND $DeleteJournal_count > 0 ) {
      $_SESSION['Emessage'] = 'تم حذف الدفع وتحديث رصيد الحلقات بنجاح ';
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit;
    }else {
      $_SESSION['Emessage'] =  ' لم يتم حذف الدفع برجاء المراجعة اولا ثم المحاوله مره اخرى ';
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit;
    }
  }else {
    $_SESSION['Emessage'] =  ' لم يتم حذف الدفع برجاء المراجعة اولا ثم المحاوله مره اخرى ';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }




  
}
function DeleteRemained($ID)
{
  global $con ;

  $GetToDeleteJournal = $con->prepare("SELECT * FROM Journal WHERE ID=? ");
  $GetToDeleteJournal->execute(array($ID));
  $GetToDeleteJournal_count = $GetToDeleteJournal->rowCount();
  $GetToDeleteJournal_fetch = $GetToDeleteJournal->fetch();
  if ($GetToDeleteJournal_count > 0) {
    $GetToDelete = $con->prepare("SELECT * FROM students WHERE Code=? ");
    $GetToDelete->execute(array($GetToDeleteJournal_fetch['Code']));
    $GetToDelete_count = $GetToDelete->rowCount();
    $GetToDelete_fetch = $GetToDelete->fetch();
    if ($GetToDelete_count > 0) {

      $num = $GetToDelete_fetch['Remained']+$GetToDeleteJournal_fetch['Subscription'];
      $Renewal_date = $GetToDelete_fetch['Renewal_date'];
      $Next_Date_of_Payment = date('Y-m-d', strtotime($Renewal_date. ' - 1 months'));

      $UpdateRemained = $con->prepare("UPDATE students SET  `Remained`=? , `Renewal_date`=? WHERE Code = ?");
      $UpdateRemained->execute(array($num, $Next_Date_of_Payment,$GetToDeleteJournal_fetch['Code']));
      $UpdateRemained_count = $UpdateRemained->rowCount();

      $DeleteJournal = $con->prepare("DELETE FROM Journal WHERE ID = ?");
      $DeleteJournal->execute(array($ID));
      $DeleteJournal_count = $DeleteJournal->rowCount();

      if ($UpdateRemained_count > 0 AND $DeleteJournal_count > 0 ) {
        $_SESSION['Emessage'] = 'تم حذف الدفع وتحديث رصيد الحلقات بنجاح ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }else {
        $_SESSION['Emessage'] =  ' لم يتم حذف الدفع برجاء المراجعة اولا ثم المحاوله مره اخرى ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }

    }else {
      $_SESSION['Emessage'] =  ' لم يتم حذف الدفع برجاء المراجعة اولا ثم المحاوله مره اخرى ';
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit;
    }
  }else {
    $_SESSION['Emessage'] =  ' لم يتم حذف الدفع برجاء المراجعة اولا ثم المحاوله مره اخرى ';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }




  
}
function DeleteBonus($ID)
{
  global $con ;


  $DeleteJournal = $con->prepare("DELETE FROM TeachersJournal WHERE ID = ?");
  $DeleteJournal->execute(array($ID));
  $DeleteJournal_count = $DeleteJournal->rowCount();

  if ($DeleteJournal_count > 0 AND $DeleteJournal_count > 0 ) {
    $_SESSION['Emessage'] = 'تم حذف ';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }else {
    $_SESSION['Emessage'] =  ' لم يتم حذف الدفع ';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }




  
}
function Settlement($Code,$Name,$Subscription,$status,$Who,$Note,$Expire,$Type)
{
  global $con ;

  $Timestamp = date('Y-m-d H:i:s');
  $ID = rand(0,1000).date("d").rand(9,99);

  $Active_stmt = $con->prepare("SELECT * FROM students WHERE Code=? LIMIT 1 ");
  $Active_stmt->execute(array($Code));
  $Active_count = $Active_stmt->rowCount();
  $Activefetch = $Active_stmt->fetch();
  if ($Active_count > 0 ) {
    $Increase = $con->prepare("INSERT INTO Journal (`Timestamp`, `ID` , Code , `Name` , `Renewal_VS_Trail` , Date_of_Payment , `Amount`,`Payment_Way`, `Note` , `Subscription`,`Type`,Who) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
    $Increase->execute(array($Timestamp , $ID, $Code , $Name  , $status , $Expire ,0, 'Settlement' ,$Note,$Subscription,$Type,$Who));
    $Increase_count = $Increase->rowCount();
    if ($Increase_count > 0 ) {
      $Notification = " طلب تسوية جديد مقدم من ".$Who;
      NotificationSend("MA-1",$Notification,"CustomerServiceSettlementRequest"," طلبات التسوية ");
      $_SESSION['Emessage'] = 'تم ارسال الطلب بنجاح ';
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit;
    }else {
      $_SESSION['Emessage'] =  'لم يتم ارسال الطلب برجاء المحاولة مرة اخرى ';
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit;
    }
  }else {
    $_SESSION['Emessage'] = 'كود الطالب غير موجود فى السيستم';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }
  
}
function SettlementAccept($ID,$Code,$Subscription,$status,$TypeUpdate)
{
  global $con ;


    $GetRemained = $con->prepare("SELECT * FROM students WHERE Code=? ");
    $GetRemained->execute(array($Code));
    $GetRemained_count = $GetRemained->rowCount();
    $GetRemainedfetch = $GetRemained->fetch();


    if ($GetRemained_count > 0) {
      if ($status == 1 ) {
        $num = $GetRemainedfetch['Remained']+$Subscription;
      }else {
        $num = $GetRemainedfetch['Remained']-$Subscription;
      }
      $Journal = $con->prepare("UPDATE  Journal SET  `Type`=? WHERE ID = ?");
      $Journal->execute(array($TypeUpdate,$ID));
      $Journal_count = $Journal->rowCount();
      
      $remained = $con->prepare("UPDATE  students SET  `Remained`=? WHERE Code = ?");
      $remained->execute(array($num,$Code));
      $remained_count = $remained->rowCount();

      if ($status == 1 ) {
        $Message = 'تم خصم '.$Subscription.'  حصص عن طريق طلب تسويه';
      }else {
        $Message = 'تم إضافة '.$Subscription.'  حصص عن طريق طلب تسويه';
      }
      
      if ($remained_count > 0) {
        $Notification = " تم قبول طلب التسوية الخاص بالطالب ".$Code;
        NotificationSend($GetRemainedfetch['Who'],$Notification,"CustomerServiceSettlementRequest"," طلبات التسوية ");
        ActivitySend($Code,$Message,'Automated',0);

        $_SESSION['Emessage'] = ' تم قبول الطلب بنجاح ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }else {
        $_SESSION['Emessage'] =  ' لم يتم قبول الطلب برجاء المراجعة اولا ثم المحاوله مره اخرى ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }
    }
  
}
function SettlementRefuse($ID)
{
  global $con ;

  $GetJournalSettlement = $con->prepare("SELECT * FROM Journal WHERE ID=? ");
  $GetJournalSettlement->execute(array($ID));
  $GetJournalSettlementfetch = $GetJournalSettlement->fetch();

      $Journal = $con->prepare("UPDATE  Journal SET  `Type`=? WHERE ID = ?");
      $Journal->execute(array(4,$ID));
      $Journal_count = $Journal->rowCount();
      
      if ($Journal_count > 0) {
        $Notification = " تم رفض طلب التسوية الخاص بالطالب ".$GetJournalSettlementfetch['Code'];
        NotificationSend($GetJournalSettlementfetch['Who'],$Notification,"CustomerServiceSettlementRequest"," طلبات التسوية ");
        $_SESSION['Emessage'] = ' تم رفض الطلب بنجاح ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }else {
        $_SESSION['Emessage'] =  ' لم يتم رفض الطلب برجاء المراجعة اولا ثم المحاوله مره اخرى ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }

  
}
function UnApproval($ID)
{

  global $con ;

  $UpdateJournal = $con->prepare("UPDATE  Journal SET `Type` = ? WHERE ID =?");
  $UpdateJournal->execute(array(1,$ID));
  $UpdateJournal_count = $UpdateJournal->rowCount();
  if ($UpdateJournal_count > 0) {
    $_SESSION['Emessage'] = ' تم الغاء التقفيل بنجاح ';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }else {
    $_SESSION['Emessage'] =  ' لم يتم الغاء التقفيل برجاء المراجعة اولا ثم المحاوله مره اخرى ';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }
}
function Approv($ID)
{

  global $con ;

  $UpdateJournal = $con->prepare("UPDATE  Journal SET `Type` = ? WHERE ID =?");
  $UpdateJournal->execute(array(5,$ID));
  $UpdateJournal_count = $UpdateJournal->rowCount();
  if ($UpdateJournal_count > 0) {
    $_SESSION['Emessage'] = ' تم التقفيل بنجاح ';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }else {
    $_SESSION['Emessage'] =  ' لم يتم التقفيل برجاء المراجعة اولا ثم المحاوله مره اخرى ';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }
}
function AddHoursManually($Code,$Month,$Hours,$Who)
{

  global $con ;
  $ID = rand(0,1000).date("d").rand(9,99);
  $AddHoursManually = $con->prepare("SELECT * FROM TeachersSendSallary WHERE Code = ? AND `Month` = ? ");
  $AddHoursManually->execute(array($Code,$Month));
  $AddHoursManuallyfetch = $AddHoursManually->rowCount();
  $fetch = $AddHoursManually->fetch();
  if ($AddHoursManuallyfetch > 0) {
    $ToAddHours = $Hours+$fetch["AddHoursManually"] ;
    $AddHours = $con->prepare("UPDATE TeachersSendSallary  SET `AddHoursManually` = ? , `WhoAddHours` = ? WHERE ID = ? ");
    $AddHours->execute(array($ToAddHours,$Who,$fetch["ID"]));
    $AddHours_count = $AddHours->rowCount();
    if ($AddHours_count > 0) {
      $_SESSION['Emessage'] = ' تم اضافة الساعات بنجاح ';
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit;
    }else {
      $_SESSION['Emessage'] =  ' لم يتم اضافة الساعات برجاء المراجعة اولا ثم المحاوله مره اخرى ';
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit;
    }
  }else {
    $ToAddHours = $Hours ;
    $AddHours = $con->prepare("INSERT INTO `TeachersSendSallary`( `ID`, `Code`, `Month`, `SendSallary`, `AddHoursManually`, `WhoSendSallary`, `WhoAddHours`) VALUES ( ?,?,?,?,?,?,?)");
    $AddHours->execute(array($ID,$Code,$Month,NULL,$ToAddHours,NULL,$Who));
    $AddHours_count = $AddHours->rowCount();
    if ($AddHours_count > 0) {
      $_SESSION['Emessage'] = ' تم اضافة الساعات بنجاح ';
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit;
    }else {
      $_SESSION['Emessage'] =   ' لم يتم اضافة الساعات برجاء المراجعة اولا ثم المحاوله مره اخرى ';
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit;
    }
  }

}
function UNAddHoursManually($Code,$Month)
{

  global $con ;
  $AddHoursManually = $con->prepare("SELECT * FROM TeachersSendSallary WHERE Code = ? AND `Month` = ? ");
  $AddHoursManually->execute(array($Code,$Month));
  $AddHoursManuallyfetch = $AddHoursManually->rowCount();
  $fetch = $AddHoursManually->fetch();
  if ($AddHoursManuallyfetch > 0) {
    $AddHours = $con->prepare("UPDATE TeachersSendSallary  SET `AddHoursManually` = ? , `WhoAddHours` = ? WHERE ID = ? ");
    $AddHours->execute(array(NULL,NULL,$fetch["ID"]));
    $AddHours_count = $AddHours->rowCount();
    if ($AddHours_count > 0) {
      $_SESSION['Emessage'] = ' تم الغاء اضافة الساعات بنجاح ';
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit;
    }else {
      $_SESSION['Emessage'] =  ' لم يتم الغاء اضافة الساعات برجاء المراجعة اولا ثم المحاوله مره اخرى ';
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit;
    }
  }else {
    $_SESSION['Emessage'] =   ' لم يتم الغاء اضافة الساعات برجاء المراجعة اولا ثم المحاوله مره اخرى ';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }

}
function SendSallary($Code,$Month,$Who)
{

  global $con ;
  $ID = rand(0,1000).date("d").rand(9,99);
  $WhoSendSallary = $con->prepare("SELECT * FROM TeachersSendSallary WHERE Code = ? AND `Month` = ? ");
  $WhoSendSallary->execute(array($Code,$Month));
  $WhoSendSallaryfetch = $WhoSendSallary->rowCount();
  $fetch = $WhoSendSallary->fetch();
  if ($WhoSendSallaryfetch > 0) {
    $SendSallary = $con->prepare("UPDATE TeachersSendSallary  SET `SendSallary` = ? , `WhoSendSallary` = ? WHERE ID = ? ");
    $SendSallary->execute(array("Yes",$Who,$fetch["ID"]));
    $SendSallary_count = $SendSallary->rowCount();
    if ($SendSallary_count > 0) {
      $_SESSION['Emessage'] = ' تم ارسال الراتب بنجاح ';
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit;
    }else {
      $_SESSION['Emessage'] =  ' لم يتم ارسال الراتب برجاء المراجعة اولا ثم المحاوله مره اخرى ';
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit;
    }
  }else {
    $SendSallary = $con->prepare("INSERT INTO `TeachersSendSallary`( `ID`, `Code`, `Month`, `SendSallary`, `AddHoursManually`, `WhoSendSallary`,`WhoAddHours`) VALUES ( ?,?,?,?,?,?,?)");
    $SendSallary->execute(array($ID,$Code,$Month,"Yes",0,$Who,NULL));
    $SendSallary_count = $SendSallary->rowCount();
    if ($SendSallary_count > 0) {
      $_SESSION['Emessage'] = ' تم ارسال الراتب بنجاح ';
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit;
    }else {
      $_SESSION['Emessage'] =  ' لم يتم ارسال الراتب برجاء المراجعة اولا ثم المحاوله مره اخرى ';
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit;
    }
  }

}
function UNSendSallary($Code,$Month)
{

  global $con ;
  $WhoSendSallary = $con->prepare("SELECT * FROM TeachersSendSallary WHERE Code = ? AND `Month` = ? ");
  $WhoSendSallary->execute(array($Code,$Month));
  $WhoSendSallaryfetch = $WhoSendSallary->rowCount();
  if ($WhoSendSallaryfetch > 0) {
    $SendSallary = $con->prepare("UPDATE TeachersSendSallary SET `SendSallary` = ? , `WhoSendSallary` = ? WHERE Code = ? ");
    $SendSallary->execute(array(NULL,NULL,$Code));
    $SendSallary_count = $SendSallary->rowCount();
    if ($SendSallary_count > 0) {
      $_SESSION['Emessage'] = ' تم الغاء ارسال الراتب بنجاح ';
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit;
    }else {
      $_SESSION['Emessage'] =  ' لم يتم الغاء ارسال الراتب برجاء المراجعة اولا ثم المحاوله مره اخرى ';
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit;
    }
  }else {
    $_SESSION['Emessage'] =  ' لم يتم الغاء ارسال الراتب برجاء المراجعة اولا ثم المحاوله مره اخرى ';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }

}
function ApprovAll($ID)
{

  global $con ;
  $IDExplode = str_replace(",","','",$ID);
  $UpdateJournal = $con->prepare("UPDATE  Journal SET `Type` = ? WHERE ID IN ('$IDExplode')");
  $UpdateJournal->execute(array(5));
  $UpdateJournal_count = $UpdateJournal->rowCount();
  if ($UpdateJournal_count > 0) {
    $_SESSION['Emessage'] = ' تم التقفيل بنجاح ';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }else {
    $_SESSION['Emessage'] =  ' لم يتم التقفيل برجاء المراجعة اولا ثم المحاوله مره اخرى ';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }
}
function Increase($Amount,$Code,$Name,$Way,$RenewalVSTrail,$Date,$Note,$Who,$Type)
{
  global $con ;

  $Timestamp = date('Y-m-d H:i:s');
  $ID = rand(0,1000).date("d").rand(9,99);

  $Active_stmt = $con->prepare("SELECT * FROM students WHERE Code=? LIMIT 1 ");
  $Active_stmt->execute(array($Code));
  $Active_count = $Active_stmt->rowCount();
  $Activefetch = $Active_stmt->fetch();
  $Subscription = $Activefetch['Days'];
  if ($Active_count > 0 ) {
    $GetRemained = $con->prepare("SELECT * FROM students WHERE Code=? ");
    $GetRemained->execute(array($Code));
    $GetRemained_count = $GetRemained->rowCount();
    $GetRemainedfetch = $GetRemained->fetch();
    $num = $GetRemainedfetch['Remained']-$Subscription;
    $Renewal_date = $GetRemainedfetch['Renewal_date'];
    $Next_Date_of_Payment = date('Y-m-d', strtotime($Renewal_date. ' + 1 months'));

    if ($GetRemained_count > 0) {
      $Increase = $con->prepare("INSERT INTO Journal (`Timestamp`, `ID` , Code , `Name` , `Renewal_VS_Trail` , Date_of_Payment , `Amount`,`Payment_Way`, `Note` , `Subscription`,`Type`,Who) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
      $Increase->execute(array($Timestamp , $ID, $Code , $Name  , $RenewalVSTrail , $Date ,$Amount, $Way ,$Note,$Subscription,$Type,$Who));
      $Increase_count = $Increase->rowCount();

      $remained = $con->prepare("UPDATE  students SET  `Remained`=? , `Renewal_date`=? WHERE Code = ?");
      $remained->execute(array($num, $Next_Date_of_Payment,$Code));
      $remained_count = $remained->rowCount();
      $Message = 'تم تسجيل دفع جديد بمبلغ '.$Amount ;
      ActivitySend($Code,$Message,$Who,0);
      if ($remained_count > 0) {
        $_SESSION['Emessage'] = ' تم تسجيل الدفع بنجاح ';
        header('Location: CustomerService');
        exit;
      }else {
        $_SESSION['Emessage'] =  ' لم يتم تسجيل الدفع برجاء المراجعة اولا ثم المحاوله مره اخرى ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }
    }


  }else {
    $_SESSION['Emessage'] = 'كود الطالب غير موجود فى السيستم';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }
  
}
function Bonus($Amount,$Code,$Way,$Date,$Note,$Who,$Type)
{
  global $con ;

  $Timestamp = date('Y-m-d H:i:s');
  $ID = rand(0,1000).date("d").rand(9,99);

      $Increase = $con->prepare("INSERT INTO TeachersJournal (`Timestamp`, `ID` , Code , `Type` , `Date` , `Amount`,`Payment_Way`, `Note` ,Who) VALUES (?,?,?,?,?,?,?,?,?)");
      $Increase->execute(array($Timestamp , $ID, $Code , $Type  , $Date ,$Amount, $Way ,$Note,$Who));
      $Increase_count = $Increase->rowCount();

      if ($Increase_count > 0) {
        $_SESSION['Emessage'] = ' تم تسجيل الدفع بنجاح ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }else {
        $_SESSION['Emessage'] =  ' لم يتم تسجيل الدفع برجاء المراجعة اولا ثم المحاوله مره اخرى ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }
    

}
function UpdateBonus($Code,$Type,$Date,$Amount,$Way,$Note,$ID)
{
  global $con ;

  $UpdateJournal = $con->prepare("UPDATE  TeachersJournal SET Code = ? , `Type` = ?  , `Date` = ?  , `Amount` = ? ,`Payment_Way` = ? , `Note` = ?  WHERE ID = ?");
  $UpdateJournal->execute(array($Code,$Type,$Date,$Amount,$Way,$Note,$ID));
  $UpdateJournal_count = $UpdateJournal->rowCount();
  if ($UpdateJournal_count > 0) {
    $_SESSION['Emessage'] = ' تم تعديل الدفع بنجاح ';
    header('Location: HR-SalaryBonusAndPenaltySheet');
    exit;
  }else {
    $_SESSION['Emessage'] =  ' لم يتم تعديل الدفع او لم يتم تغيير اى من البيانات  برجاء المراجعة اولا ثم المحاوله مره اخرى ';
    header('Location: HR-SalaryBonusAndPenaltySheet');
    exit;
  }

}
function UpdateJournal($ID,$Amount,$Code,$Name,$Way,$RenewalVSTrail,$Date,$Note)
{
  global $con ;

  $UpdateJournal = $con->prepare("UPDATE  Journal SET Code = ? , `Name` = ?  , `Renewal_VS_Trail` = ?  , Date_of_Payment = ?  , `Amount` = ? ,`Payment_Way` = ? , `Note` = ?  WHERE ID = ?");
  $UpdateJournal->execute(array($Code,$Name,$RenewalVSTrail,$Date,$Amount,$Way,$Note,$ID));
  $UpdateJournal_count = $UpdateJournal->rowCount();
  if ($UpdateJournal_count > 0) {
    $_SESSION['Emessage'] = ' تم تعديل الدفع بنجاح ';
    header('Location: CustomerServiceJournalView');
    exit;
  }else {
    $_SESSION['Emessage'] =  ' لم يتم تعديل الدفع او لم يتم تغيير اى من البيانات  برجاء المراجعة اولا ثم المحاوله مره اخرى ';
    header('Location: CustomerServiceJournalView');
    exit;
  }

}
if ($_SERVER ['REQUEST_METHOD']== 'POST') {
    // --------------
      if (isset($_POST["Amount"]) AND $_POST["Amount"] !== "") {
        $Amount = $_POST["Amount"];
      }else {
        $_SESSION['Emessage'] = ' لم يتم التسجيل يرجى ادخال المبلغ ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }
      if (isset($_POST["Code"]) AND $_POST["Code"] !== "") {
        $Code = $_POST["Code"];
      }else {
        $_SESSION['Emessage'] = ' لم يتم التسجيل يرجى ادخال الكود ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }
      if (isset($_POST["Name"]) AND $_POST["Name"] !== "") {
        $Name = $_POST["Name"];
      }else {
        $_SESSION['Emessage'] = ' لم يتم التسجيل يرجى ادخال الاسم ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }
      if (isset($_POST["Way"]) AND $_POST["Way"] !== "") {
        $Way = $_POST["Way"];
      }else {
        $_SESSION['Emessage'] = ' لم يتم التسجيل يرجى ادخال طريقة الدفع ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }
      if (isset($_POST["RenewalVSTrail"]) AND $_POST["RenewalVSTrail"] !== "") {
        $RenewalVSTrail = $_POST["RenewalVSTrail"];
      }else {
        $_SESSION['Emessage'] = ' لم يتم التسجيل يرجى اختيار تجريبى ام تجديد ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }
      if (isset($_POST["Date"]) AND $_POST["Date"] !== "") {
        $Date = $_POST["Date"];
      }else {
        $_SESSION['Emessage'] =' لم يتم التسجيل يرجى ادخال تاريخ الدفع ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }
      if (isset($_POST["Who"]) AND $_POST["Who"] !== "") {
        $Who = $_POST["Who"];
      }else {
        $Who = "Unknow";
      }
      if (isset($_POST["Note"]) AND $_POST["Note"] !== "") {
        $Note = $_POST["Note"];
      }else {
        $Note = "";
      }
      if (isset($_POST["Type"]) AND $_POST["Type"] !== "") {
        $Type = $_POST["Type"];
      }else {
        $Type = "Unknow";
      }
    // -----------
    Increase($Amount,$Code,$Name,$Way,$RenewalVSTrail,$Date,$Note,$Who,$Type);
}elseif (isset($_GET["CustomerServiceBonus"])) {
    // --------------
      if (isset($_GET["Amount"]) AND $_GET["Amount"] !== "") {
        $Amount = $_GET["Amount"];
      }else {
        $_SESSION['Emessage'] = ' لم يتم التسجيل يرجى ادخال المبلغ ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }
      if (isset($_GET["Code"]) AND $_GET["Code"] !== "") {
        $Code = $_GET["Code"];
      }else {
        $_SESSION['Emessage'] = ' لم يتم التسجيل يرجى ادخال الكود ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }
      if (isset($_GET["Type"]) AND $_GET["Type"] !== "") {
        $Type = $_GET["Type"];
      }else {
        $_SESSION['Emessage'] = ' لم يتم التسجيل يرجى ادخال الاسم ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }
      if (isset($_GET["Way"]) AND $_GET["Way"] !== "") {
        $Way = $_GET["Way"];
      }else {
        $_SESSION['Emessage'] = ' لم يتم التسجيل يرجى ادخال طريقة الدفع ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }
      if (isset($_GET["Date"]) AND $_GET["Date"] !== "") {
        $Date = $_GET["Date"];
      }else {
        $_SESSION['Emessage'] =' لم يتم التسجيل يرجى ادخال تاريخ الدفع ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }
      if (isset($_GET["Who"]) AND $_GET["Who"] !== "") {
        $Who = $_GET["Who"];
      }else {
        $Who = "Unknow";
      }
      if (isset($_GET["Note"]) AND $_GET["Note"] !== "") {
        $Note = $_GET["Note"];
      }else {
        $Note = "";
      }
    // -----------
    Bonus($Amount,$Code,$Way,$Date,$Note,$Who,$Type);
}
elseif (isset($_GET["DeletAll"])) {
    $ID = $_GET["DeletAll"] ;
    DeletAll($ID);
}
elseif (isset($_GET["Delete"])) {
    $ID = $_GET["Delete"] ;
    DeleteRemained($ID);
}
elseif (isset($_GET["DeleteBonus"])) {
    $ID = $_GET["DeleteBonus"] ;
    DeleteBonus($ID);
}
elseif (isset($_GET["UpdateBonus"])) {
  $ID = $_GET["UpdateBonus"] ;
    // --------------
      if (isset($_GET["Amount"]) AND $_GET["Amount"] !== "") {
        $Amount = $_GET["Amount"];
      }else {
        $_SESSION['Emessage'] = ' لم يتم التسجيل يرجى ادخال المبلغ ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }
      if (isset($_GET["Code"]) AND $_GET["Code"] !== "") {
        $Code = $_GET["Code"];
      }else {
        $_SESSION['Emessage'] = ' لم يتم التسجيل يرجى ادخال الكود ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }
      if (isset($_GET["Way"]) AND $_GET["Way"] !== "") {
        $Way = $_GET["Way"];
      }else {
        $_SESSION['Emessage'] = ' لم يتم التسجيل يرجى ادخال طريقة الدفع ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }
      if (isset($_GET["Type"]) AND $_GET["Type"] !== "") {
        $Type = $_GET["Type"];
      }else {
        $_SESSION['Emessage'] = ' لم يتم التسجيل يرجى اختيار تجريبى ام تجديد ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }
      if (isset($_GET["Date"]) AND $_GET["Date"] !== "") {
        $Date = $_GET["Date"];
      }else {
        $_SESSION['Emessage'] =' لم يتم التسجيل يرجى ادخال تاريخ الدفع ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }
      if (isset($_GET["Note"]) AND $_GET["Note"] !== "") {
        $Note = $_GET["Note"];
      }else {
        $Note = "";
      }

    // -----------
    UpdateBonus($Code,$Type,$Date,$Amount,$Way,$Note,$ID);
}
elseif (isset($_GET["Update"])) {
  $ID = $_GET["Update"] ;
    // --------------
      if (isset($_GET["Amount"]) AND $_GET["Amount"] !== "") {
        $Amount = $_GET["Amount"];
      }else {
        $_SESSION['Emessage'] = ' لم يتم التسجيل يرجى ادخال المبلغ ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }
      if (isset($_GET["Code"]) AND $_GET["Code"] !== "") {
        $Code = $_GET["Code"];
      }else {
        $_SESSION['Emessage'] = ' لم يتم التسجيل يرجى ادخال الكود ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }
      if (isset($_GET["Name"]) AND $_GET["Name"] !== "") {
        $Name = $_GET["Name"];
      }else {
        $_SESSION['Emessage'] = ' لم يتم التسجيل يرجى ادخال الاسم ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }
      if (isset($_GET["Way"]) AND $_GET["Way"] !== "") {
        $Way = $_GET["Way"];
      }else {
        $_SESSION['Emessage'] = ' لم يتم التسجيل يرجى ادخال طريقة الدفع ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }
      if (isset($_GET["RenewalVSTrail"]) AND $_GET["RenewalVSTrail"] !== "") {
        $RenewalVSTrail = $_GET["RenewalVSTrail"];
      }else {
        $_SESSION['Emessage'] = ' لم يتم التسجيل يرجى اختيار تجريبى ام تجديد ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }
      if (isset($_GET["Date"]) AND $_GET["Date"] !== "") {
        $Date = $_GET["Date"];
      }else {
        $_SESSION['Emessage'] =' لم يتم التسجيل يرجى ادخال تاريخ الدفع ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }
      if (isset($_GET["Note"]) AND $_GET["Note"] !== "") {
        $Note = $_GET["Note"];
      }else {
        $Note = "";
      }

    // -----------
    UpdateJournal($ID,$Amount,$Code,$Name,$Way,$RenewalVSTrail,$Date,$Note);
}
elseif (isset($_GET["RenewalDate"]) AND isset($_GET["Code"]) ) {
  $Date = $_GET["RenewalDate"] ;
  $Code = $_GET["Code"] ;
  RenewalDate($Code,$Date);
}elseif (isset($_GET["Settlement"])) {
  $Code         = $_GET["Settlement"] ;
  $Name         = $_GET["Name"] ;
  $Subscription = $_GET["Subscription"] ;
  $status       = $_GET["status"] ;
  $Who          = $_GET["Who"] ;
  $Note         = $_GET["Note"] ;
  $Expire         = $_GET["Expire"] ;
  $Type         = $_GET["Type"] ;
  Settlement($Code,$Name,$Subscription,$status,$Who,$Note,$Expire,$Type);
}elseif (isset($_GET["SettlementAccept"])) {
  $ID           = $_GET["SettlementAccept"] ;
  $Code         = $_GET["Code"] ;
  $Subscription = $_GET["Subscription"] ;
  $status       = $_GET["status"] ;
  $TypeUpdate       = $_GET["TypeUpdate"] ;
  SettlementAccept($ID,$Code,$Subscription,$status,$TypeUpdate);

}elseif (isset($_GET["SettlementRefuse"])) {
  $ID = $_GET["SettlementRefuse"] ;
  SettlementRefuse($ID);
}elseif (isset($_GET["UnApproval"])) {
  $ID = $_GET["UnApproval"] ;
  UnApproval($ID);
}elseif (isset($_GET["ApprovAll"])) {
  $ID = $_GET["ApprovAll"] ;
  ApprovAll($ID);
}
elseif (isset($_GET["Approv"])) {
  $ID = $_GET["Approv"] ;
  Approv($ID);
}
elseif (isset($_GET["AddHoursManually"])) {
  $Code = $_GET["AddHoursManually"] ;
  $Hours = $_GET["Hours"] ;
  $Month = $_GET["Month"] ;
  $Who = $_GET["WhoAddHours"] ;
  AddHoursManually($Code,$Month,$Hours,$Who);
}
elseif (isset($_GET["SendSallary"])) {
  $Code = $_GET["SendSallary"] ;
  $Month = $_GET["Month"] ;
  $Who = $_GET["WhoSendSallary"] ;
  SendSallary($Code,$Month,$Who);
}
elseif (isset($_GET["UNSendSallary"])) {
  $Code = $_GET["UNSendSallary"] ;
  $Month = $_GET["Month"] ;
  UNSendSallary($Code,$Month);
}
elseif (isset($_GET["UNAddHoursManually"])) {
  $Code = $_GET["UNAddHoursManually"] ;
  $Month = $_GET["Month"] ;
  UNAddHoursManually($Code,$Month);
}
elseif (isset($_GET["EditHourRate"])) {
  $Code = $_GET["EditHourRate"] ;
  $Rate = $_GET["Rate"] ;
  global $con ;

  $AddHoursManually = $con->prepare("SELECT * FROM teachers WHERE Code = ? ");
  $AddHoursManually->execute(array($Code));
  $AddHoursManuallyfetch = $AddHoursManually->rowCount();
  $fetch = $AddHoursManually->fetch();
  if ($AddHoursManuallyfetch > 0) {
    $HourRate = $con->prepare("UPDATE teachers SET HourRate = ? WHERE Code = ?");
    $HourRate->execute(array($Rate,$Code));
    $HourRate_count = $HourRate->rowCount();
    if ($HourRate_count > 0) {
      $_SESSION['Emessage'] = ' تم تعديل سعر الساعة بنجاح ';
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit;
    }else {
      $_SESSION['Emessage'] =  ' لم يتم تعديل سعر الساعة او لم يتم تغيير اى من البيانات  برجاء المراجعة اولا ثم المحاوله مره اخرى ';
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit;
    }
  }else {
    $HourRate = $con->prepare("INSERT INTO `teachers`( `Code`, `HourRate`,`Name`,`Password`) VALUES (?,?,?,?)");
    $HourRate->execute(array($Code,$Rate," غير معروف ","40bd001563085fc35165329ea1ff5c5ecbdbbeef"));
    $HourRate_count = $HourRate->rowCount();
    if ($HourRate_count > 0) {
      $_SESSION['Emessage'] = ' تم تعديل سعر الساعة بنجاح ';
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit;
    }else {
      $_SESSION['Emessage'] =  ' لم يتم تعديل سعر الساعة او لم يتم تغيير اى من البيانات  برجاء المراجعة اولا ثم المحاوله مره اخرى ';
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit;
    }
  }
}
else {
     header('Location: index');  exit;  
}









<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_GET['RequestID']) AND !isset($_GET['ID'])) {  header('Location: index');  exit;  } 
require_once "connect.php" ;
require_once "NotificationSend.php" ;
    function RescheduleRequest($RequestID,$ID,$Date,$Time) {
        global $con;
        $stmt_1 = $con->prepare("SELECT * FROM class WHERE ID=? LIMIT 1 ");
        $stmt_1->execute(array($ID));
        $T_1= $stmt_1->fetch() ;
        $count = $stmt_1->rowCount() ;
        $Timestamp = date('Y-m-d H:i:s') ;
        $classID = rand(0,1000).date("d").rand(9,99) ; ;
        $Teacher = $T_1['Teacher'];
        $Student = $T_1['Student'];
        $StudentName = $T_1["StudentName"] .' - '.'Rescheduled'.' - '.$classID;
        $Duration = $T_1['Duration'];
        $category = $T_1['category'];
        $one_time = $Date;
        $Saturday = "";
        $Sunday = "";
        $Monday = "";
        $Tuesday = "";
        $Wednesday = "";
        $Thursday = "";
        $Friday = ""; 
        $Saturday_time= NULL; 
        $Sunday_time= NULL;
        $Monday_time= NULL;
        $Tuesday_time= NULL;
        $Wednesday_time= NULL;
        $Thursday_time= NULL;
        $Friday_time= NULL;
        $Days = 0 ;
        $type= 1 ;
        $Duration= $T_1['Duration'];


        $c_stmt = $con->prepare("INSERT INTO class (`Timestamp`, ID , Teacher  , Student , StudentName , `one_time`, `Saturday`, `Sunday`, `Monday`, `Tuesday`, `Wednesday`, `Thursday`, `Friday` , `Saturday_time`, `Sunday_time`, `Monday_time`, `Tuesday_time`, `Wednesday_time`, `Thursday_time`, `Friday_time`,`type` , Duration , `Days`,`time` , category) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $c_stmt->execute(array( $Timestamp , $classID, $Teacher , $Student  , $StudentName  , $one_time, $Saturday, $Sunday, $Monday, $Tuesday, $Wednesday, $Thursday, $Friday  , $Saturday_time, $Sunday_time, $Monday_time, $Tuesday_time, $Wednesday_time, $Thursday_time, $Friday_time  , $type , $Duration ,$Days , $Time ,$category ));
        $c_stmt->rowCount();

        $stmt = $con->prepare("UPDATE  RescheduleRequest SET `status`=? , UpdatedAt =? , UpdatedBy=?  WHERE ID =? ");
        $stmt->execute(array(2,date('Y-m-d H:i:s'),$_SESSION['Name'], $RequestID ));
        $xcount = $stmt->rowCount();
        $Message = "تم قبول طلب الحصة التعويضية الخاص بالطالب ".$Student." يرجى تسجيل الحلقة ";
        NotificationSend($Teacher,$Message,"No","طلبات الحصص التعويضية");

    };
    function Refused($RequestID) {
        global $con;
        $stmt_1 = $con->prepare("SELECT * FROM RescheduleRequest WHERE ID=? LIMIT 1 ");
        $stmt_1->execute(array($RequestID));
        $T_1= $stmt_1->fetch() ;

        $stmt_2 = $con->prepare("SELECT * FROM class WHERE ID=? LIMIT 1 ");
        $stmt_2->execute(array($T_1['classID']));
        $T_2= $stmt_2->fetch() ;
        $Teacher = $T_2['Teacher'];
        $Student = $T_2['Student'];

        $stmt = $con->prepare("UPDATE  RescheduleRequest SET `status`=? , UpdatedAt =? , UpdatedBy=?   WHERE ID =? ");
        $stmt->execute(array(3,date('Y-m-d H:i:s'),$_SESSION['Name'], $RequestID ));
        $xcount = $stmt->rowCount();
        $Message = "تم رفض طلب الحصة التعويضية الخاص بالطالب ".$Student." يرجى التأكد من البيانات المدخلة ";
        NotificationSend($Teacher,$Message,"No","طلبات الحصص التعويضية");
    }

    if (isset($_GET['refused'])) {
        $RequestID = filter_var($_GET["RequestID"], FILTER_UNSAFE_RAW );
    
        Refused($RequestID);
    
        $_SESSION['Emessage'] = 'تم رفض الطلب بنجاح  ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit; 

    }else {
        $RequestID = filter_var($_GET["RequestID"], FILTER_UNSAFE_RAW );
        $ID = filter_var($_GET["ID"], FILTER_UNSAFE_RAW );
        $Date = filter_var($_GET["Date"], FILTER_UNSAFE_RAW );
        $Time = filter_var($_GET["Time"], FILTER_UNSAFE_RAW );
    
        RescheduleRequest($RequestID,$ID,$Date,$Time);

        $_SESSION['Emessage'] = 'تم قبول الطلب بنجاح  ';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
         

    }
   
?>

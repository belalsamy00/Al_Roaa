<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
include "connect.php" ;
if ($_SERVER ['REQUEST_METHOD'] == 'POST') { 
    
    function RescheduleRequest($classID,$Date,$Time,$MainDate) {
        global  $con ;
        $stmt_1 = $con->prepare("SELECT * FROM class WHERE ID=? LIMIT 1 ");
        $stmt_1->execute(array($classID));
        $T_1= $stmt_1->fetch() ;
        $count = $stmt_1->rowCount() ;
        $RequestID = rand(0,1000).date("d").rand(9,99) ; ;
        $Teacher = $T_1['Teacher'];
        $Student = $T_1['Student'];
        $Name = $T_1['StudentName'];
        $one_time = $Date;

        $c_stmt = $con->prepare("INSERT INTO RescheduleRequest ( ID ,classID , Teacher  , Student, `Name` , `for_one_time` ,`time`,`date`,`status`,MainDate) VALUES (?,?,?,?,?,?,?,?,?,?)");
        $c_stmt->execute(array( $RequestID ,$classID, $Teacher , $Student , $Name  , $one_time ,$Time,date('Y-m-d H:i:s'),1,$MainDate));
        $c_stmt->rowCount();

        return 0 ;

    }

    $classID = filter_var($_POST["ID"], FILTER_UNSAFE_RAW );
    $Date = filter_var($_POST["Date"], FILTER_UNSAFE_RAW );
    $Time = filter_var($_POST["Time"], FILTER_UNSAFE_RAW );
    $MainDate = filter_var($_POST["MainDate"], FILTER_UNSAFE_RAW );

    RescheduleRequest($classID ,$Date,$Time,$MainDate);

    $_SESSION['Emessage'] = 'تم تقديم الطلب بنجاح  ';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit; 
} else {
    if (isset($_GET['Student']) && isset($_GET['MainDate'])  && isset($_GET['classID'])) {
        $Permission = array() ;
        $Permission['Rescheduled'] = 0 ;
        $Permission['Permission'] = 'NotAllowed' ;
        $Permission['Massage'] = '' ;

        $stmt_class = $con->prepare("SELECT * FROM class WHERE ID=? LIMIT 1 ");
        $stmt_class->execute(array($_GET['classID']));
        $class= $stmt_class->fetch() ;
        $classDay = date('l', strtotime($_GET['MainDate'])) ;

        if ($_GET['MainDate'] >= date("Y-m-d",strtotime("-15 day"))) {
            if ($classDay !== $class[$classDay]) {
                $Permission['Massage'] = ' هذا الطالب جدوله ليس فى هذا اليوم ' ;
            }else {
                $H_stmt = $con->prepare("SELECT * FROM history WHERE  `date`=? AND  S_code =? AND S_name =? AND T_code=?");
                $H_stmt->execute(array($_GET['MainDate'],$class['Student'],$class['StudentName'],$class['Teacher']));
                $countv = $H_stmt->rowCount();
                if ($countv > 0) {
                    $Permission['Massage'] = ' الحلقة لهذا الطالب  فى هذا التاريخ مسجلة بالفعل ' ;
                }else {
                    $Permission['Permission'] = 'Allowed' ;
    
                    $Name = $class['StudentName'].' - Rescheduled';
                    $type = $con->prepare("SELECT * FROM class WHERE Teacher= ?  AND `one_time` >= ? AND Student =? AND StudentName LIKE ?  AND `type` =?  ");
                    $type->execute(array($class['Teacher'],date("Y-m-d") , $class['Student'] , "%$Name%",1) );
                    $type_count = $type->rowCount();
                    $H_type=$type->fetchall();
                    if ($type_count > 0) {
                        $Permission['Rescheduled'] = array();
                      foreach ($H_type as $key => $value) {
                        $Permission['Rescheduled'][$key] = '  هناك حصة تعويضية لهذا الطالب  متاحة للتسجيل يوم '.$value['one_time']  ;
                      }
                    }else {
                        $Permission['Rescheduled'] = $Permission ;
                    }
                }
    
            }
        }else {
            $Permission['Permission'] = 'NotAllowed' ;
            $Permission['Massage'] = ' لا يمكن اختيار تاريخ الحصة الاساسية قبل 15 يوم ' ;

        }


        echo json_encode($Permission);
    }
}
   
?>

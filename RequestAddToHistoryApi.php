<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_GET['Accept']) && !isset($_GET['Refuse'])) {  header('Location: index');  exit;  } 
require_once "connect.php" ;
require_once "NotificationSend.php" ;
require_once "functions/CS-functions.php" ;
    function Accept($AcceptID) {
        global $con;
        $stmt_1 = $con->prepare("SELECT * FROM add_to_history_request WHERE ID=? LIMIT 1 ");
        $stmt_1->execute(array($AcceptID));
        $Data= $stmt_1->fetch() ;

            $t_code =           $Data["T_code"];
            $code =             $Data["S_code"];
            $name =             $Data["S_name"];
            $stringName =       str_replace(" ","",$name);
            $name_explode=      explode('-',$stringName);
            $status =           $Data["status"];
            $date =             $Data["date"];
            $Duration =         $Data["Duration"];
            $nots =             $Data["nots"];
            $Form_Maker =       $Data['Form_Maker'] ; 
            $Timestamp =        date('Y-m-d H:i:s') ;

            if (0 > 0 ) {

            }else {
              $stmt = $con->prepare("SELECT * FROM history WHERE S_code=? AND S_name =? AND t_code =? AND `date` =?  ");
              $stmt->execute(array($code,$name,$t_code,$date));
              $stmtn =$stmt->fetch();
              $count = $stmt->rowCount();
              if ($count > 0) {
                $stmt = $con->prepare("UPDATE add_to_history_request SET `RequestStatus`=?  WHERE ID =? ");
                $stmt->execute(array(2, $AcceptID ));
                $xcount = $stmt->rowCount();

                if ($xcount > 0 ) {
                    $_SESSION['Emessage'] = 'تم قبول الطلب بنجاح  ';
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                    exit; 
                }else {
                    $_SESSION['Emessage'] = 'لم يتم قبول الطلب هناك خطأ غير مقصود برجاء المحاولة مرة اخرى   ';
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                    exit; 
                }
              }else {
                  $stmt11 = $con->prepare("INSERT INTO history (`T_code` , `S_code` , `S_name` ,`status` , `date`, Duration , `nots`,`Timestamp`,Form_Maker ) VALUES (?,?,?,?,?,?,?,?,?)");
                  $stmt11->execute(array( $t_code , $code  , $name  , $status , $date , $Duration , $nots , $Timestamp, $Form_Maker ));
                  $count11 = $stmt11->rowCount();
          
                  if ($count11 > 0) { 
                      if ($name_explode[0] != "oneTimeClass") { IncreaseRemained($code); }
                      UpdateLastClass($code,$date);

                      $stmt = $con->prepare("UPDATE add_to_history_request SET `RequestStatus`=?  WHERE ID =? ");
                      $stmt->execute(array(2, $AcceptID ));
                      $xcount = $stmt->rowCount();

                      if ($xcount > 0 ) {
                          $_SESSION['Emessage'] = 'تم قبول الطلب بنجاح  ';
                          header('Location: ' . $_SERVER['HTTP_REFERER']);
                          exit; 
                      }else {
                          $_SESSION['Emessage'] = 'لم يتم قبول الطلب هناك خطأ غير مقصود برجاء المحاولة مرة اخرى   ';
                          header('Location: ' . $_SERVER['HTTP_REFERER']);
                          exit; 
                      }
                  }

              }
            }



    };
    function Refuse($RefuseID) {
        global $con;
        $stmt_1 = $con->prepare("SELECT * FROM add_to_history_request WHERE ID=? LIMIT 1 ");
        $stmt_1->execute(array($RefuseID));
        $T_1= $stmt_1->fetch() ;

        $stmt = $con->prepare("UPDATE add_to_history_request SET `RequestStatus`=?  WHERE ID =? ");
        $stmt->execute(array(3, $RefuseID ));
        $xcount = $stmt->rowCount();
        if ($xcount > 0 ) {
            $_SESSION['Emessage'] = 'تم رفض الطلب بنجاح  ';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit; 
        }else {
            $_SESSION['Emessage'] = 'لم يتم رفض الطلب هناك خطأ غير مقصود برجاء المحاولة مرة اخرى   ';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit; 
        }
    }

    if (isset($_GET['Refuse'])) {
        $RefuseID = filter_var($_GET["Refuse"], FILTER_UNSAFE_RAW );
        Refuse($RefuseID);
    }else {
        $AcceptID = filter_var($_GET["Accept"], FILTER_UNSAFE_RAW );
        Accept($AcceptID);
    }
   
?>

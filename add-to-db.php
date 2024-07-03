<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['teacher']) AND !isset($_SESSION['Admin']) AND !isset($_SESSION['Suber_Admin']) AND !isset($_SESSION['manager'])) {  header('Location: index');  exit;  } 
else {
      include "assets/tem/header.php" ;
      include "functions/CS-functions.php" ; 
      require "functions/globalFunctions.php";
      require_once "NotificationSend.php" ;
        if ($_SERVER ['REQUEST_METHOD']== 'POST') { 

          if (isset($_POST['Cancel'])) {
            $stmt_1 = $con->prepare("SELECT *  FROM class WHERE  ID = ? ");
            $stmt_1->execute(array($_POST['Cancel']) );
            $T_1= $stmt_1->fetch() ;
            if (!empty($T_1['Cancel'])) {
              $array10 = unserialize ($T_1['Cancel']);
              if (is_array($array10)) { $array1 = $array10 ; } else { $array1 =[]; }
            }else{ $array1 =[];} 
              

            $date = filter_var($_POST["date"], FILTER_UNSAFE_RAW );
            array_push($array1,$date);
            $array = serialize ($array1);

             $stmt = $con->prepare("UPDATE class SET Cancel=?   WHERE ID =? ");
             $stmt->execute(array($array,$_POST['Cancel'] ));
          }elseif (isset($_POST['UNCancel'])) {
            $stmt_1 = $con->prepare("SELECT *  FROM class WHERE  ID = ? ");
            $stmt_1->execute(array($_POST['UNCancel']) );
            $T_1= $stmt_1->fetch() ;
            if (!empty($T_1['Cancel'])) { $array1 = unserialize ($T_1['Cancel']); }else{$array1 =[];}
            $date = filter_var($_POST["date"], FILTER_UNSAFE_RAW );
            $key = array_search($date, $array1);
            unset($array1[$key]);
            $array = serialize ($array1);
            $stmt = $con->prepare("UPDATE class SET Cancel=?   WHERE ID =? ");
            $stmt->execute(array($array,$_POST['UNCancel'] ));
          }else {
            SetActual($_POST["code"]); ?> 
           
            <div id="remove" class= "col-sm-8 col-lg-6 col-xl-4 text-center" style="margin-left: auto;margin-right: auto;margin-top: 66px;font-size: 20px;font-weight: bold;color: #0454ab;">
            <?php 
            $t_code = filter_var($_POST["t_code"], FILTER_UNSAFE_RAW );
            $code = filter_var($_POST["code"], FILTER_UNSAFE_RAW );
            if (!isset($_POST["category"])) {
                $category = "quran";
            }else {
              if (empty($_POST["category"])) {
                $category = "quran";
              }else {
                $category = filter_var($_POST["category"], FILTER_UNSAFE_RAW );
              }
              
            }
            
            $name = filter_var($_POST["name"], FILTER_UNSAFE_RAW );
            $stringName = str_replace(" ","",$name);
            $name_explode= explode('-',$stringName);
            
            $status = filter_var($_POST["status"], FILTER_UNSAFE_RAW );
            $date = filter_var($_POST["date"], FILTER_UNSAFE_RAW );
            $Duration = filter_var($_POST["Duration"], FILTER_UNSAFE_RAW );
            $nots = filter_var($_POST["nots"], FILTER_UNSAFE_RAW );
            if (isset($_SESSION['Name'])) { $Form_Maker = $_SESSION['Name'] ; }else { $Form_Maker = "Unknow" ; }
            $Timestamp = date('Y-m-d H:i:s') ;
            $sectionid = $_POST["code"]."-".$_POST["name"];

            if (0 > 0 ) {
            }else {
              $stmt = $con->prepare("SELECT * FROM history WHERE S_code=? AND S_name =? AND t_code =? AND `date` =?  ");
              $stmt->execute(array($code,$name,$t_code,$date));
              $stmtn =$stmt->fetch();
              $count = $stmt->rowCount();
              if ($count > 0) {
                $_SESSION['Emessage'] = 'لم تتم إضافة الحلقة لانها مضافة  من قبل';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit;
              }else {
                if (isset($_POST['add_to_history_request'])){
                  $tabel = "add_to_history_request" ;
                }else {
                  $tabel = "history" ;
                }
                $stmt11 = $con->prepare("INSERT INTO $tabel (`T_code` , `S_code` , `S_name` ,`status` , `date`, Duration , `nots`,`Timestamp`,Form_Maker ) VALUES (?,?,?,?,?,?,?,?,?)");
                $stmt11->execute(array( $t_code , $code  , $name  , $status , $date , $Duration , $nots , $Timestamp, $Form_Maker ));
                $count11 = $stmt11->rowCount();
                if (isset($_POST['add_to_history_request'])){
                  if ($count11 > 0) { 
                    ?>
                      <div id="spin"  style="margin-top: 20rem!important">
                      <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                      <span class="visually-hidden">Loading...</span>
                      </div>
                    <?php
                          $Notification = " طلب تسجيل حلقة فى الشهر الماضى مقدم من ".$Form_Maker;
                          NotificationSend("MA-1",$Notification,"RequestAddToHistory"," طلب تسجيل حلقة ");
                    $_SESSION['Emessage'] = 'تم تقديم طلب الحلقة بنجاح  ';
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                    exit;
                        } else {
                          $_SESSION['Emessage'] = 'لم تتم تقديم طلب الحلقة برجاء المحاولة مرة اخرى  ';
                          header('Location: ' . $_SERVER['HTTP_REFERER']);
                          exit;
                      }
                }else {
                  if ($count11 > 0) { 
                    ?>
                      <div id="spin"  style="margin-top: 20rem!important">
                      <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                      <span class="visually-hidden">Loading...</span>
                      </div>
                    <?php
                    if ($name_explode[0] != "oneTimeClass") {
                      IncreaseRemained($code);
                    }
                    UpdateLastClass($code,$date);
                    $_SESSION['Emessage'] = 'تم إضافة الحلقة بنجاح  ';
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                    exit;
                        } else {
                          $_SESSION['Emessage'] = 'لم تتم إضافة الحلقة برجاء المحاولة مرة اخرى  ';
                          header('Location: ' . $_SERVER['HTTP_REFERER']);
                          exit;
                      }
                }
              }
            }  ?>
            </div>
        </div>
        <?php
          }
             }else {
           header('location: Home');exit;
        }
    }
    include "assets/tem/header.php" ;
    ob_end_flush();
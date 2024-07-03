<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['Admin']) AND !isset($_SESSION['Suber_Admin']) AND !isset($_SESSION['manager']) AND !isset($_SESSION['Supervising_manager']) AND !isset($_SESSION['supervisor'])) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ;
require "functions/CS-functions.php";
require "functions/globalFunctions.php";
?>
<main id="main" class="main">
    <div class="container">
        <div class="pagetitle">
            <h1>Insert to Database</h1>
            <nav>
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index">Home</a></li>
                <li class="breadcrumb-item active">Insert to Database</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
            
        <?php if ($_SERVER ['REQUEST_METHOD']== 'POST') { ?> 
            <section>
                <div class="row">
                    <div  class= "col-lg-6 m-auto">
                        <div class="card">
                            <div class="card-body">
                                <?php
                                    if (isset($_GET['do'])) {$do = $_GET['do'];} else {$do = 'manage';}

                                    if ($do == 'manage' ) {

                                        if (!isset($_SESSION['teacher'])) {header('location: 404');exit;} 

                                    }elseif ($do == 'Add_Absence' ) {
                                        ?> 
                                        <?php 
                                        $S_code = filter_var($_POST["S_code"], FILTER_UNSAFE_RAW );
                                        $T_code = filter_var($_POST["T_code"], FILTER_UNSAFE_RAW );
                                        $S_name = filter_var($_POST["S_name"], FILTER_UNSAFE_RAW );
                                        $status = filter_var($_POST["status"], FILTER_UNSAFE_RAW );
                                        $Duration = filter_var($_POST["Duration"], FILTER_UNSAFE_RAW );
                                        $date = filter_var($_POST["date"], FILTER_UNSAFE_RAW ); 
                                        if (isset($_SESSION['Name'])) { $Form_Maker = $_SESSION['Name'] ; }else { $Form_Maker = "Unknow" ; }

                                        $Timestamp = date('Y-m-d H:i:s') ;
                                        if ( 0 != 0 ) {
                                        }else {
                                        $stmt = $con->prepare("INSERT INTO history (`T_code` , `S_code` , `S_name` ,`status` ,`Duration` , `date` , `nots`,`Timestamp` ) VALUES (?,?,?,?,?,?,?,?)");
                                        $stmt->execute(array( $T_code , $S_code  , $S_name  , $status , $Duration , $date , "سجلها المشرف" , $Timestamp ));
                                        $count = $stmt->rowCount();

                                        if ($count > 0) { ?>
                                        <div>
                                        <div class="text-center alert alert-success mt-2" role="alert">
                                        تم تسجيل الحصة
                                        </div>
                                        <a href="Add_Absence"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2">تسحيل حصة أخرى ؟ </a>
                                        <a href="index"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2">  الصفحة الرئيسئة</a>
                                        </div>
                                        <?php  } else {
                                        ?>
                                        <div>
                                        <div class="text-center alert alert-danger mt-2" role="alert">
                                        لم يتم تسجيل الحصة هناك مشكلة ما!
                                        </div>
                                        <a href="Add_Absence"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2">تسحيل حصة أخرى ؟ </a>
                                        <a href="index"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2">  الصفحة الرئيسئة</a>
                                        </div>
                                        <?php
                                        }

                                        }  ?>
                                        </div>
                                        <?php 
                                    }elseif ($do == 'Add_S' ) {
                                        $dbCode = ucfirst(filter_var($_POST["Code"], FILTER_UNSAFE_RAW ));
                                        $studentCode = ucfirst(filter_var($_POST["studentCode"], FILTER_UNSAFE_RAW ));
                                        $dbphone = filter_var($_POST["phone"], FILTER_UNSAFE_RAW );
                                        $dbsex = filter_var($_POST["sex"], FILTER_UNSAFE_RAW );
                                        $dbCountry = filter_var($_POST["Country"], FILTER_UNSAFE_RAW );
                                        $dbN_Students = filter_var($_POST["N_Students"], FILTER_UNSAFE_RAW );
                                        $dbDays = filter_var($_POST["Days"], FILTER_UNSAFE_RAW );
                                        $E_Cost = filter_var($_POST["E_Cost"], FILTER_UNSAFE_RAW );
                                        $S_Cost = filter_var($_POST["S_Cost"], FILTER_UNSAFE_RAW );
                                        $dbWho = filter_var($_POST["Who"], FILTER_UNSAFE_RAW );
                                        $ID = filter_var($_POST["ID"], FILTER_UNSAFE_RAW );
                                        
                                        if (isset($_SESSION['Name'])) { $Form_Maker = $_SESSION['Name'] ; }else { $Form_Maker = "Unknow" ; }

                                        $insert_s_stmt = $con->prepare("SELECT * FROM students WHERE Code = ? ");
                                        $insert_s_stmt->execute(array($dbCode));
                                        $insert_s_count = $insert_s_stmt->rowCount();

                                        if ($insert_s_count > 0) { ?>
                                        <div>
                                        <div class="text-center alert alert-success mt-2" role="alert">
                                        تم أضافة الطالب 
                                        </div>
                                        <a href="A-Student"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" >اضافة طالب اخر</a>
                                        <a href="index"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" >  الصفحة الرئيسئة</a>
                                        </div>
                                        <?php 
                                        }else {
                                        $stmt = $con->prepare("INSERT INTO students ( ID , Code  , phone  , sex , Country  , N_Students , `Days` , E_Cost , S_Cost, Who,Renewal_date ,First_class) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
                                        $stmt->execute(array( $ID , $dbCode , $dbphone    , $dbsex , $dbCountry  , $dbN_Students  , $dbDays  , $E_Cost , $S_Cost , $dbWho ,date('Y-m-d'),date('Y-m-d')));
                                            
                                        $count = $stmt->rowCount();
                                        if ($count > 0) { ?>
                                        <div>
                                        <div class="text-center alert alert-success mt-2" role="alert">
                                        تم أضافة الطالب 
                                        </div>
                                        <a href="A-Student"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2">اضافة طالب اخر</a>
                                        <a href="index"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2">  الصفحة الرئيسئة</a>
                                        </div>
                                        <?php  } else {
                                        ?>
                                        <div>
                                        <div class="text-center alert alert-danger mt-2" role="alert">
                                        لم تتم أضافة الطالب هناك مشكلة ما!
                                        </div>
                                        <a href="A-Student"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2">اضافة طالب اخر </a>
                                        <a href="index"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2">  الصفحة الرئيسئة</a>
                                        </div>
                                        <?php
                                        }
                                        }
                                    }elseif ($do == 'Add_c' ) {
                                        $ID = filter_var($_POST["ID"], FILTER_UNSAFE_RAW );
                                        $insert_s_stmt = $con->prepare("SELECT * FROM class WHERE ID = ? ");
                                        $insert_s_stmt->execute(array($ID));
                                        $insert_s_count = $insert_s_stmt->rowCount();
                                        if($insert_s_count > 0)
                                        {
                                        ?>
                                        <div>
                                        <div class="text-center alert alert-success mt-2" role="alert">
                                        تم اضافة الحدول بنجاح
                                        </div>
                                        <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button> 

                                        <a href="index"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2">  الصفحة الرئيسئة</a>
                                        </div>
                                        <?php
                                        }else {
                                        $Teacher = filter_var($_POST["Teacher"], FILTER_UNSAFE_RAW );
                                        $Student = filter_var($_POST["Student"], FILTER_UNSAFE_RAW );
                                        $Duration = filter_var($_POST["Duration"], FILTER_UNSAFE_RAW );
                                        $category = filter_var($_POST["category"], FILTER_UNSAFE_RAW );
                                        $Saturday = "";
                                        $Sunday = "";
                                        $Monday = "";
                                        $Tuesday = "";
                                        $Wednesday = "";
                                        $Thursday = "";
                                        $Friday = ""; 
                                        $Timestamp = date('Y-m-d H:i:s') ;
                                        if (isset($_POST["Days"])){$Days = filter_var($_POST["Days"], FILTER_UNSAFE_RAW );} else {$Days = 0 ;}
                                        if (isset($_POST["Saturday_time"]  ) && !empty( $_POST["Saturday_time"]) ){  $Saturday_time = TimeToInsert($_POST["Saturday_time"]);} else {$Saturday_time = NULL ;}
                                        if (isset($_POST["Sunday_time"])&& !empty( $_POST["Sunday_time"])){ $Sunday_time = TimeToInsert($_POST["Sunday_time"]);} else {$Sunday_time = NULL ;}
                                        if (isset($_POST["Monday_time"])&& !empty( $_POST["Monday_time"])){ $Monday_time =  TimeToInsert($_POST["Monday_time"]);} else {$Monday_time = NULL ;}
                                        if (isset($_POST["Tuesday_time"])&& !empty( $_POST["Tuesday_time"])){ $Tuesday_time =  TimeToInsert($_POST["Tuesday_time"]);} else {$Tuesday_time = NULL ;}
                                        if (isset($_POST["Wednesday_time"])&& !empty( $_POST["Wednesday_time"])){ $Wednesday_time =  TimeToInsert($_POST["Wednesday_time"]);} else {$Wednesday_time = NULL ;}
                                        if (isset($_POST["Thursday_time"])&& !empty( $_POST["Thursday_time"])){ $Thursday_time =  TimeToInsert($_POST["Thursday_time"]);} else {$Thursday_time = NULL ;}
                                        if (isset($_POST["Friday_time"])&& !empty( $_POST["Friday_time"])){ $Friday_time =  TimeToInsert($_POST["Friday_time"]);} else {$Friday_time = NULL ;}
                                        if (isset($_POST["time"])&& !empty( $_POST["time"])){ $time = TimeToInsert($_POST["time"]);} else {$time = NULL ;}
                                        if (isset($_POST["for_one_time"])){$type = 1 ;} else {$type = 2 ;}
                                        if (isset($_POST["Saturday"])) { $Saturday = filter_var($_POST["Saturday"], FILTER_UNSAFE_RAW );}
                                        if (isset($_POST["Sunday"])) { $Sunday = filter_var($_POST["Sunday"], FILTER_UNSAFE_RAW );}
                                        if (isset($_POST["Monday"])) { $Monday = filter_var($_POST["Monday"], FILTER_UNSAFE_RAW );}
                                        if (isset($_POST["Tuesday"])) { $Tuesday = filter_var($_POST["Tuesday"], FILTER_UNSAFE_RAW );}
                                        if (isset($_POST["Wednesday"])) { $Wednesday = filter_var($_POST["Wednesday"], FILTER_UNSAFE_RAW );}
                                        if (isset($_POST["Thursday"])) { $Thursday = filter_var($_POST["Thursday"], FILTER_UNSAFE_RAW );}
                                        if (isset($_POST["Friday"])) { $Friday = filter_var($_POST["Friday"], FILTER_UNSAFE_RAW );}
                                        if (isset($_SESSION['Name'])) { $Form_Maker = $_SESSION['Name'] ; }else { $Form_Maker = "Unknow" ; }
                                        if (isset($_POST["for_one_time"])) { $StudentName = filter_var($_POST["StudentName"] .' - '.$_POST["class_type"].' - '.$ID, FILTER_UNSAFE_RAW );}else {$StudentName = filter_var($_POST["StudentName"], FILTER_UNSAFE_RAW ); }
                                        if (isset($_POST["for_one_time"])) { $one_time = $_POST["for_one_time"];}else {$one_time = "";}
                                        $c_stmt = $con->prepare("INSERT INTO class (`Timestamp`, ID , Teacher  , Student , StudentName , `one_time`, `Saturday`, `Sunday`, `Monday`, `Tuesday`, `Wednesday`, `Thursday`, `Friday` , `Saturday_time`, `Sunday_time`, `Monday_time`, `Tuesday_time`, `Wednesday_time`, `Thursday_time`, `Friday_time`,`type` , Duration , `Days`,`time` , category) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                                        $c_stmt->execute(array( $Timestamp , $ID, $Teacher , $Student  , $StudentName  , $one_time, $Saturday, $Sunday, $Monday, $Tuesday, $Wednesday, $Thursday, $Friday  , $Saturday_time, $Sunday_time, $Monday_time, $Tuesday_time, $Wednesday_time, $Thursday_time, $Friday_time  , $type , $Duration ,$Days , $time ,$category ));
                                        $c_count = $c_stmt->rowCount();
                                        SetActive($Student);
                                        SetActual($Student);

                                        if ($c_count > 0  ) {
                                        ?>
                                        <div>
                                        <div class="text-center alert alert-success mt-2" role="alert">
                                        تم اضافة الجدول بنجاح
                                        </div>
                                        <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button> 

                                        <a href="index"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2">  الصفحة الرئيسئة</a>
                                        </div>
                                        <?php  } else {
                                        ?>
                                        <div>
                                        <div class="text-center alert alert-danger mt-2" role="alert">
                                        لم تتم أضافة الحصة هناك مشكلة ما!
                                        </div>
                                        <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button> 
                                        <a href="index"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2">  الصفحة الرئيسئة</a>
                                        </div>
                                        <?php
                                        }

                                        }

                                    }
                                    elseif ($do == 'EvaluationTeam' )  {
                                        if (isset($_POST["performance"])) {
                                        $Timestamp = date('Y-m-d H:i:s') ;
                                        $Code = filter_var($_POST["code"], FILTER_UNSAFE_RAW );
                                        $Date = filter_var($_POST["date"], FILTER_UNSAFE_RAW );
                                        $Who = filter_var($_POST["who"], FILTER_UNSAFE_RAW );
                                        $performance = filter_var($_POST["performance"], FILTER_UNSAFE_RAW );
                                        $report = filter_var($_POST["report"], FILTER_UNSAFE_RAW );
                                        if (isset($_POST["classID"])) {$classID = filter_var($_POST["classID"], FILTER_UNSAFE_RAW );}else { $classID = "0"; }
                                        $d_stmt = $con->prepare("INSERT INTO EvaluationTeam (`Timestamp`,`Code`, `Date`  , `Who` , `performance` , `report` , Dis , Mark , `Status`,`text`,`type`) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
                                        $d_stmt->execute(array( $Timestamp , $Code , $Date,  $Who , $performance , $report ,"performance","0","0",`performance`,1));
                                        $d2_count = $d_stmt->rowCount();
                                        if ($d2_count > 0  ) {
                                            ?>
                                            <div>
                                            <div class="text-center alert alert-success mt-2" role="alert">
                                            <?php echo " تم إضافة"." <span class=' fs-6 fw-bold'>".$performance."</span> "." بنجاح "?>
                                            </div>
                                            </div>
                                            <?php  } else {
                                            ?>
                                            <div>
                                            <div class="text-center alert alert-danger mt-2" role="alert">
                                            <?php echo "لم تتم إضافة"." <span class=' fs-6 fw-bold'>".$performance."</span> "."هناك مشكلة ما"?>
                                            </div>
                                            
                                            </div>
                                            <?php
                                        }}
                                        $counts = array_count_values(array_column($_POST["item"], 'id'));
                                            foreach ($_POST["item"] as $value) {
                                                if (!isset($value['id'])) {
                                                    continue;
                                                }else {
                                                    $Timestamp = date('Y-m-d H:i:s') ;
                                                    $stmt = $con->prepare("SELECT * FROM items WHERE ID=? ");
                                                    $stmt->execute(array($value['id']));
                                                    $item = $stmt->fetch();
                                                    $Code = filter_var($_POST["code"], FILTER_UNSAFE_RAW );
                                                    $Date = filter_var($_POST["date"], FILTER_UNSAFE_RAW );
                                                    $Who = filter_var($_POST["who"], FILTER_UNSAFE_RAW );
                                                    $Dis = $item['text'];
                                                    $Mark = $item['mark'];
                                                    $Status =$item['status'];
                                                    $Trust =$item['trust'];
                                                    $text =$value['dis'];
                                                    $d_stmt = $con->prepare("INSERT INTO EvaluationTeam (`Timestamp`,`Code`, `Date` , Dis , Mark , `Status` , `Who`,`text`, `performance` , `report`,`type`,Trust) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
                                                    $d_stmt->execute(array($Timestamp, $Code , $Date, $Dis , $Mark  , $Status , $Who , $text ,"0","0",2,$Trust));
                                                    $d_count = $d_stmt->rowCount();

                                                }
                                            }
                                            if ( isset($d_count) AND $d_count > 0  OR   isset($d2_count) AND $d2_count > 0 ) {
                                                if (isset($_POST["classID"])) {
                                                    $_SESSION['Emessage'] = 'تم إضافة التقييم بنجاح';
                                                    header('location: EvaluationAddTeacher?code='.$Code);
                                                    exit;
                                                }else { 
                                                    $_SESSION['Emessage'] = 'تم إضافة التقييم بنجاح';
                                                    header('location: Management');
                                                    exit; 
                                                }
                                            }else {
                                                if (isset($_POST["classID"])) {
                                                    $_SESSION['Emessage'] = 'لم تتم إضافة التقييم ';
                                                    header('location: EvaluationAddTeacher?code='.$Code);
                                                    exit;
                                                }else { 
                                                    $_SESSION['Emessage'] = 'لم تتم إضافة التقييم ';
                                                    header('location: Management');
                                                    exit; 
                                                }
                                            }
                                    }
                                    elseif ($do == 'evaluation' )  {
                                        if (isset($_POST["performance"])) {
                                        $Timestamp = date('Y-m-d H:i:s') ;
                                        $Code = filter_var($_POST["code"], FILTER_UNSAFE_RAW );
                                        $Date = filter_var($_POST["date"], FILTER_UNSAFE_RAW );
                                        $Who = filter_var($_POST["who"], FILTER_UNSAFE_RAW );
                                        $performance = filter_var($_POST["performance"], FILTER_UNSAFE_RAW );
                                        $report = filter_var($_POST["report"], FILTER_UNSAFE_RAW );
                                        if (isset($_POST["classID"])) {
                                            $classID = $_POST["classID"];
                                            $stmt = $con->prepare("SELECT * FROM evaluation WHERE classID=? AND `type` =? ");
                                            $stmt->execute(array($classID,1));
                                            $item = $stmt->rowCount();
                                            if ($item > 0) {
                                                $_SESSION['Emessage'] = 'تم إضافة التقييم بنجاح';
                                                header('Location: ' . $_SERVER['HTTP_REFERER']);
                                                exit;
                                            }
                                        }else { $classID = "0"; }
                                        $d_stmt = $con->prepare("INSERT INTO evaluation (`Timestamp`,`Code`, `Date`  , `Who` , `performance` , `report` , Dis , Mark , `Status`,`text`,`type`,classID,Approval) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
                                        $d_stmt->execute(array($Timestamp, $Code , $Date,  $Who , $performance , $report ,"performance","0","0",`performance`,1,$classID,1));
                                        $d2_count = $d_stmt->rowCount();
                                        if ($d2_count > 0  ) {
                                            ?>
                                            <div>
                                            <div class="text-center alert alert-success mt-2" role="alert">
                                            <?php echo " تم إضافة"." <span class=' fs-6 fw-bold'>".$performance."</span> "." بنجاح "?>
                                            </div>
                                            </div>
                                            <?php  } else {
                                            ?>
                                            <div>
                                            <div class="text-center alert alert-danger mt-2" role="alert">
                                            <?php echo "لم تتم إضافة"." <span class=' fs-6 fw-bold'>".$performance."</span> "."هناك مشكلة ما"?>
                                            </div>
                                            
                                            </div>
                                            <?php
                                        }}
                                        $counts = array_count_values(array_column($_POST["item"], 'id'));
                                            foreach ($_POST["item"] as $value) {
                                                if (!isset($value['id'])) {
                                                    continue;
                                                }else {
                                                    $stmt = $con->prepare("SELECT * FROM items WHERE ID=? ");
                                                    $stmt->execute(array($value['id']));
                                                    $item = $stmt->fetch();
                                                    $Code = filter_var($_POST["code"], FILTER_UNSAFE_RAW );
                                                    $Date = filter_var($_POST["date"], FILTER_UNSAFE_RAW );
                                                    $Who = filter_var($_POST["who"], FILTER_UNSAFE_RAW );
                                                    if (isset($_POST["classID"])) {$classID = filter_var($_POST["classID"], FILTER_UNSAFE_RAW );}else { $classID = "0"; }
                                                    $Dis = $item['text'];
                                                    $Mark = $item['mark'];
                                                    $Status =$item['status'];
                                                    $text =$value['dis'];
                                                    $d_stmt = $con->prepare("INSERT INTO evaluation (`Timestamp`,`Code`, `Date` , Dis , Mark , `Status` , `Who`,`text`, `performance` , `report`,`type`,classID,Approval) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
                                                    $d_stmt->execute(array($Timestamp, $Code , $Date, $Dis , $Mark  , $Status , $Who , $text ,"0","0",2,$classID,1));
                                                    $d_count = $d_stmt->rowCount();

                                                }
                                            }
                                            if ( isset($d_count) AND $d_count > 0  OR   isset($d2_count) AND $d2_count > 0 ) {
                                                if (isset($_POST["classID"])) {
                                                    $_SESSION['Emessage'] = 'تم إضافة التقييم بنجاح';
                                                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                                                    exit;
                                                }else { 
                                                    $_SESSION['Emessage'] = 'تم إضافة التقييم بنجاح';
                                                    header('location: Management');
                                                    exit; 
                                                }
                                            }else {
                                                if (isset($_POST["classID"])) {
                                                    $_SESSION['Emessage'] = 'لم تتم إضافة التقييم ';
                                                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                                                    exit;
                                                }else { 
                                                    $_SESSION['Emessage'] = 'لم تتم إضافة التقييم ';
                                                    header('location: Management');
                                                    exit; 
                                                }
                                            }
                                    }
                                    elseif ($do == 'Update_History' ) {
                                        $ID = filter_var($_POST["ID"], FILTER_UNSAFE_RAW );
                                        $Student = filter_var($_POST["Student"], FILTER_UNSAFE_RAW );
                                        $Teacher = filter_var($_POST["Teacher"], FILTER_UNSAFE_RAW );
                                        $StudentName = filter_var($_POST["StudentName"], FILTER_UNSAFE_RAW );
                                        $date = filter_var($_POST["date"], FILTER_UNSAFE_RAW );
                                        $Duration = filter_var($_POST["Duration"], FILTER_UNSAFE_RAW );
                                        $status = filter_var($_POST["status"], FILTER_UNSAFE_RAW );
                                        $stmt = $con->prepare("UPDATE  history SET  S_code=?, T_code=?, S_name=?, `date`=?, Duration=?, `status`=? WHERE ID =? ");
                                        $stmt->execute(array(  $Student , $Teacher, $StudentName, $date, $Duration, $status  , $ID  ));
                                        $y_count = $stmt->rowCount();


                                        if ($y_count > 0  ) {
                                            $_SESSION['Emessage'] = 'تم تعديل الحلقة بنجاح  ';
                                            header('Location: ' . $_SERVER['HTTP_REFERER']);
                                            exit; 
                                             } else {
                                                $_SESSION['Emessage'] = 'لم يتم تعديل الحلقة   ';
                                                header('Location: ' . $_SERVER['HTTP_REFERER']);
                                                exit; 
                                                 }

                                    }elseif ($do == 'Update_History_EAllCodes' ) {
                                        $OLDStudent = filter_var($_POST["OLDStudent"], FILTER_UNSAFE_RAW );
                                        $NEWStudent = filter_var($_POST["NEWStudent"], FILTER_UNSAFE_RAW );

                                        $stmt = $con->prepare("UPDATE  history SET  S_code=? WHERE S_code =? ");
                                        $stmt->execute(array(  $NEWStudent , $OLDStudent ));
                                        $y_count = $stmt->rowCount();

                                        if ($y_count > 0  ) {
                                            $_SESSION['Emessage'] = ' تم تعديل '.$y_count.' حلقة بنجاح ';
                                            header('Location: ' . $_SERVER['HTTP_REFERER']);
                                            exit; 
                                             } else {
                                                $_SESSION['Emessage'] = ' لم يتم تعديل اى حلقات ';
                                                header('Location: ' . $_SERVER['HTTP_REFERER']);
                                                exit; 
                                                 }

                                    }elseif ($do == 'Update_History_EAllNames' ) {
                                        $Student = filter_var($_POST["Student"], FILTER_UNSAFE_RAW );
                                        $Name = filter_var($_POST["Name"], FILTER_UNSAFE_RAW );

                                        $stmt = $con->prepare("UPDATE  history SET  S_name=? WHERE S_code =? ");
                                        $stmt->execute(array(  $Name , $Student ));
                                        $y_count = $stmt->rowCount();

                                        if ($y_count > 0  ) {
                                            $_SESSION['Emessage'] = ' تم تعديل '.$y_count.' حلقة بنجاح ';
                                            header('Location: ' . $_SERVER['HTTP_REFERER']);
                                            exit; 
                                             } else {
                                                $_SESSION['Emessage'] = ' لم يتم تعديل اى حلقات ';
                                                header('Location: ' . $_SERVER['HTTP_REFERER']);
                                                exit; 
                                                 }

                                    }elseif ($do == 'E_History' ) {
                                        $ID = filter_var($_POST["ID"], FILTER_UNSAFE_RAW );
                                        $Duration = filter_var($_POST["Duration"], FILTER_UNSAFE_RAW );

                                        $stmt = $con->prepare("UPDATE  history SET Duration=? WHERE ID =? ");
                                        $stmt->execute(array(  $Duration   , $ID  ));
                                        $y_count = $stmt->rowCount();

                                        if ($y_count > 0  ) {
                                            $_SESSION['Emessage'] = 'تم تعديل المدة بنجاح  ';
                                            header('Location: ' . $_SERVER['HTTP_REFERER']);
                                            exit; 
                                             } else {
                                                $_SESSION['Emessage'] = 'لم يتم تعديل المدة   ';
                                                header('Location: ' . $_SERVER['HTTP_REFERER']);
                                                exit; 
                                                 }

                                    }elseif ($do == 'Approval_EvaluationAll' ) {
                                            $stmt = $con->prepare("UPDATE  evaluation SET Approval=? WHERE Approval =? ");
                                            $stmt->execute(array( 0,1));
                                            $y_count = $stmt->rowCount();
                                            if ($y_count > 0  ) {
                                                $_SESSION['Emessage'] = 'تم إعتماد الحلقات بنجاح  ';
                                                header('Location: ' . $_SERVER['HTTP_REFERER']);
                                                exit; 
                                                }else{
                                                    $_SESSION['Emessage'] = 'لم يتم إعتماد الحلقات   ';
                                                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                                                    exit; 
                                                }

                                    }elseif ($do == 'Approval_Evaluation' ) {
                                            $ID = filter_var($_POST["classID"], FILTER_UNSAFE_RAW );
                                            $stmt = $con->prepare("UPDATE  evaluation SET Approval=? WHERE classID =? ");
                                            $stmt->execute(array( 0,$ID));
                                            $y_count = $stmt->rowCount();
                                            if ($y_count > 0  ) {
                                                $_SESSION['Emessage'] = 'تم إعتماد الحلقة بنجاح  ';
                                                header('Location: ' . $_SERVER['HTTP_REFERER']);
                                                exit; 
                                                }else{
                                                    $_SESSION['Emessage'] = 'لم يتم إعتماد الحلقة   ';
                                                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                                                    exit; 
                                                }

                                    }elseif ($do == 'Cancel_Evaluation' ) {
                                            $ID = filter_var($_POST["classID"], FILTER_UNSAFE_RAW );
                                            $stmt = $con->prepare("UPDATE  evaluation SET Cancel=? WHERE classID =? ");
                                            $stmt->execute(array( 1,$ID));
                                            $y_count = $stmt->rowCount();
                                            if ($y_count > 0  ) {
                                                $_SESSION['Emessage'] = 'تم إلغاء الحلقة بنجاح  ';
                                                header('Location: ' . $_SERVER['HTTP_REFERER']);
                                                exit; 
                                                }else{
                                                    $_SESSION['Emessage'] = 'لم يتم إلغاء الحلقة   ';
                                                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                                                    exit; 
                                                }

                                    }elseif ($do == 'Cancel_Approval_Evaluation' ) {
                                            $ID = filter_var($_POST["classID"], FILTER_UNSAFE_RAW );
                                            $stmt = $con->prepare("UPDATE  evaluation SET Approval=? WHERE classID =? ");
                                            $stmt->execute(array( 1,$ID));
                                            $y_count = $stmt->rowCount();
                                            if ($y_count > 0  ) {
                                                $_SESSION['Emessage'] = 'تم إعتماد الحلقة بنجاح  ';
                                                header('Location: ' . $_SERVER['HTTP_REFERER']);
                                                exit; 
                                                }else{
                                                    $_SESSION['Emessage'] = 'لم يتم إعتماد الحلقة   ';
                                                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                                                    exit; 
                                                }

                                    }elseif ($do == 'Cancel_Cancel_Evaluation' ) {
                                            $ID = filter_var($_POST["classID"], FILTER_UNSAFE_RAW );
                                            $stmt = $con->prepare("UPDATE  evaluation SET Cancel=? WHERE classID =? ");
                                            $stmt->execute(array( 0,$ID));
                                            $y_count = $stmt->rowCount();
                                            if ($y_count > 0  ) {
                                                $_SESSION['Emessage'] = 'تم إلغاء الحلقة بنجاح  ';
                                                header('Location: ' . $_SERVER['HTTP_REFERER']);
                                                exit; 
                                                }else{
                                                    $_SESSION['Emessage'] = 'لم يتم إلغاء الحلقة   ';
                                                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                                                    exit; 
                                                }

                                    }else {
                                        // header('location: 404');exit;
                                    }?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php }else {header('location: index');}  ?>
    </div>
</main><!-- End #main -->
<?php include "assets/tem/footer.php" ?>
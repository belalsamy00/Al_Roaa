<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['teacher'])) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ; 
 require "functions/globalFunctions.php";
  $teacher = $_SESSION['teacher'] ;
  $stmt_1 = $con->prepare("SELECT Saturday_time FROM class WHERE Teacher= ? AND (Saturday_time != ? OR Saturday_time = ? )AND `status` =? "); 
  $stmt_1->execute(array($teacher,"NULL",'00:00:00','Active') ); 
  $Saturday_time=$stmt_1->fetchAll();

  $stmt_2 = $con->prepare("SELECT Sunday_time FROM class WHERE Teacher= ? AND (Sunday_time != ? OR Sunday_time = ? ) AND `status` =?"); 
  $stmt_2->execute(array($teacher,"NULL",'00:00:00','Active') ); 
  $Sunday_time=$stmt_2->fetchAll();

  $stmt_3 = $con->prepare("SELECT Monday_time FROM class WHERE Teacher= ? AND (Monday_time != ? OR Monday_time = ? ) AND `status` =?"); 
  $stmt_3->execute(array($teacher,"NULL",'00:00:00','Active') ); 
  $Monday_time=$stmt_3->fetchAll();

  $stmt_4 = $con->prepare("SELECT Tuesday_time FROM class WHERE Teacher= ? AND (Tuesday_time != ? OR Tuesday_time = ? ) AND `status` =? "); 
  $stmt_4->execute(array($teacher,"NULL",'00:00:00','Active') ); 
  $Tuesday_time=$stmt_4->fetchAll();

  $stmt_5 = $con->prepare("SELECT Wednesday_time FROM class WHERE Teacher= ? AND (Wednesday_time != ? OR Wednesday_time = ? ) AND `status` =?"); 
  $stmt_5->execute(array($teacher,"NULL",'00:00:00','Active') ); 
  $Wednesday_time=$stmt_5->fetchAll();

  $stmt_6 = $con->prepare("SELECT Thursday_time FROM class WHERE Teacher= ? AND (Thursday_time != ? OR Thursday_time = ? ) AND `status` =? "); 
  $stmt_6->execute(array($teacher,"NULL",'00:00:00','Active') ); 
  $Thursday_time=$stmt_6->fetchAll();

  $stmt_7 = $con->prepare("SELECT Friday_time FROM class WHERE Teacher= ? AND (Friday_time != ? OR Friday_time = ? ) AND `status` =? "); 
  $stmt_7->execute(array($teacher,"NULL",'00:00:00','Active') ); 
  $Friday_time=$stmt_7->fetchAll();

  $week1 =array();
  $week2 =array();
  $week3 =array();
  $week4 =array();
  $week5 =array();
  $week6 =array();
  $week7 =array();
  $Day1 ='Saturday_time';
  $Day2 ='Sunday_time';
  $Day3 ='Monday_time';
  $Day4 ='Tuesday_time';
  $Day5 ='Wednesday_time';
  $Day6 ='Thursday_time';
  $Day7 ='Friday_time';
  foreach ($Saturday_time as $time) {
      if (!in_array($time['Saturday_time'], $week1))
      {
          $week1[] = $time['Saturday_time']; 
      }
  }
  foreach ($Sunday_time as $time) {
      if (!in_array($time['Sunday_time'], $week2))
      {
          $week2[] = $time['Sunday_time']; 
      }
  }
  foreach ($Monday_time as $time) {
      if (!in_array($time['Monday_time'], $week3))
      {
          $week3[] = $time['Monday_time']; 
      }
  }
  foreach ($Tuesday_time as $time) {
      if (!in_array($time['Tuesday_time'], $week4))
      {
          $week4[] = $time['Tuesday_time']; 
      }
  }
  foreach ($Wednesday_time as $time) {
      if (!in_array($time['Wednesday_time'], $week5))
      {
          $week5[] = $time['Wednesday_time']; 
      }
  }
  foreach ($Thursday_time as $time) {
      if (!in_array($time['Thursday_time'], $week6))
      {
          $week6[] = $time['Thursday_time']; 
      }
  }
  foreach ($Friday_time as $time) {
      if (!in_array($time['Friday_time'], $week7))
      {
          $week7[] = $time['Friday_time']; 
      }
  }
  sort($week1, 0);
  sort($week2, 0);
  sort($week3, 0);
  sort($week4, 0);
  sort($week5, 0);
  sort($week6, 0);
  sort($week7, 0);

?>

<main id="main" class="main">
  <div class="container">
    <div class="pagetitle">
      <h1>الجدول</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">الرئيسية</a></li>
          <li class="breadcrumb-item active">الجدول</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    
    <section class="section dashboard">

        <div class="row flex-row-reverse">

            <div class="col-lg-4 my-2">
                <div class="card h-100">
                    <div class="card-body rtl">
                        <h1 class="card-title text-center fs-4 fw-bold"> <i class="fa-solid fa-thumbtack"></i> &nbsp;السبت</h1> <hr>
                
                        <?php if (empty($week1)) { ?> 
                            <div>
                                <div class="alert alert-danger text-center" role="alert"  style=" width: 100%;" >
                                    لا توجد حلقات مسجلة
                                </div>
                            </div>
                        <?php }else { ?>

                            <!-- ----------------------------------- -->
                                <?php $week1_i = 0 ; foreach ($week1 as $key ) {
                                    $date_t_stmt = $con->prepare("SELECT * FROM class WHERE Teacher= ? AND $Day1 = ? AND `status` =?"); 
                                    $date_t_stmt->execute(array($teacher, $key,'Active') ); 
                                    $date_count = $date_t_stmt->rowCount(); 
                                    $date_t=$date_t_stmt->fetchAll(); 
                                    
                                    ?>
                                    <p class="card-details mb-0 ">
                                        
                                        <?php if ($date_count > 0) {foreach
                                            ($date_t as  $value) {
                                                $Active_stmt = $con->prepare("SELECT * FROM students WHERE Code=?  AND `status`=?  LIMIT 1 ");
                                                $Active_stmt->execute(array($value['Student'],"Active"));
                                                $Active_count = $Active_stmt->rowCount();
                                                if ($Active_count > 0 ) {
                                                $FStudentName = explode(' ',$value['Student']); echo "<span class='badge bg-primary w-100 fs-5 mb-2'> <span class='badge bg-primary w-50'> ".TimeToDisplay($key)." </span>"."</br>"."<span class='badge bg-light mb-2 card-details w-100'>"."  ". $FStudentName[0] ." ".$value['StudentName']."</span>" . "</span>" ;
                                                $week1_i++ ;
                                                }
                                            }
                                        }else {echo '' ;}?>
                                    </p>
                                <?php } ?>
                            <!-- ----------------------------------- -->
                            <?php if ($week1_i == 0) { ?> 
                                <div>
                                    <div class="alert alert-danger text-center" role="alert"  style=" width: 100%;" >
                                        لا توجد حلقات مسجلة
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 my-2">
                <div class="card h-100">
                    <div class="card-body rtl">
                        <h1 class="card-title text-center fs-4 fw-bold"> <i class="fa-solid fa-thumbtack"></i> &nbsp;الأحد</h1> <hr>
                
                        <?php if (empty($week2)) { ?> 
                        <div>
                            <div class="alert alert-danger text-center" role="alert"  style=" width: 100%;" >
                                لا توجد حلقات مسجلة
                            </div>
                        </div>
                        <?php }else { ?>

                            <!-- ----------------------------------- -->
                                <?php $week2_i = 0 ; foreach ($week2 as $key ) {
                                    $date_t_stmt = $con->prepare("SELECT * FROM class WHERE Teacher= ? AND $Day2 = ? AND `status` =?"); 
                                    $date_t_stmt->execute(array($teacher, $key,'Active') ); 
                                    $date_count = $date_t_stmt->rowCount(); 
                                    $date_t=$date_t_stmt->fetchAll(); 
                                    ?>
                                    <p class="card-details mb-0 ">
                                        <?php if ($date_count > 0) {
                                            foreach($date_t as  $value) {
                                                $Active_stmt = $con->prepare("SELECT * FROM students WHERE Code=?  AND `status`=?  LIMIT 1 ");
                                                $Active_stmt->execute(array($value['Student'],"Active"));
                                                $Active_count = $Active_stmt->rowCount();
                                                if ($Active_count > 0 ) {
                                                    $FStudentName = explode(' ',$value['Student']); echo "<span class='badge bg-primary w-100 fs-5 mb-2'> <span class='badge bg-primary w-50'> ".TimeToDisplay($key)." </span>"."</br>"."<span class='badge bg-light mb-2 card-details w-100'>"."  ". $FStudentName[0] ." ".$value['StudentName']."</span>" . "</span>" ;
                                                    $week2_i++ ;
                                                }
                                            }
                                        }else {echo '' ;}?>
                                    </p>
                                <?php } ?>
                            <!-- ----------------------------------- -->
                            <?php if ($week2_i == 0) { ?> 
                                <div>
                                    <div class="alert alert-danger text-center" role="alert"  style=" width: 100%;" >
                                        لا توجد حلقات مسجلة
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 my-2">
                <div class="card h-100">
                    <div class="card-body rtl">
                        <h1 class="card-title text-center fs-4 fw-bold"> <i class="fa-solid fa-thumbtack"></i> &nbsp;الأثنين</h1> <hr>
                
                        <?php if (empty($week3)) { ?> 
                            <div>
                                <div class="alert alert-danger text-center" role="alert"  style=" width: 100%;" >
                                    لا توجد حلقات مسجلة
                                </div>
                            </div>
                        <?php }else { ?>

                            <!-- ----------------------------------- -->
                                <?php $week3_i = 0 ; foreach ($week3 as $key ) {
                                    $date_t_stmt = $con->prepare("SELECT * FROM class WHERE Teacher= ? AND $Day3 = ? AND `status` =?"); 
                                    $date_t_stmt->execute(array($teacher, $key,'Active') ); 
                                    $date_count = $date_t_stmt->rowCount(); 
                                    $date_t=$date_t_stmt->fetchAll(); 
                                    
                                    ?>
                                    <p class="card-details mb-0 ">
                                        <?php if ($date_count > 0) {foreach
                                            ($date_t as  $value) {
                                                $Active_stmt = $con->prepare("SELECT * FROM students WHERE Code=?  AND `status`=?  LIMIT 1 ");
                                                $Active_stmt->execute(array($value['Student'],"Active"));
                                                $Active_count = $Active_stmt->rowCount();
                                                if ($Active_count > 0 ) {
                                                $FStudentName = explode(' ',$value['Student']); echo "<span class='badge bg-primary w-100 fs-5 mb-2'> <span class='badge bg-primary w-50'> ".TimeToDisplay($key)." </span>"."</br>"."<span class='badge bg-light mb-2 card-details w-100'>"."  ". $FStudentName[0] ." ".$value['StudentName']."</span>" . "</span>" ;
                                                $week3_i++ ;
                                                }
                                            }
                                        }else {echo '' ;}?>
                                    </p>
                                <?php } ?>
                            <!-- ----------------------------------- -->
                            <?php if ($week3_i == 0) { ?> 
                                <div>
                                    <div class="alert alert-danger text-center" role="alert"  style=" width: 100%;" >
                                        لا توجد حلقات مسجلة
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 my-2">
                <div class="card h-100">
                    <div class="card-body rtl">
                        <h1 class="card-title text-center fs-4 fw-bold"> <i class="fa-solid fa-thumbtack"></i> &nbsp;الثلاثاء</h1> <hr>
                
                        <?php if (empty($week4)) { ?> 
                            <div>
                                <div class="alert alert-danger text-center" role="alert"  style=" width: 100%;" >
                                    لا توجد حلقات مسجلة
                                </div>
                            </div>
                        <?php }else { ?>
                        
                            <!-- ----------------------------------- -->
                                <?php $week4_i = 0 ; foreach ($week4 as $key ) {
                                    $date_t_stmt = $con->prepare("SELECT * FROM class WHERE Teacher= ? AND $Day4 = ? AND `status` =?"); 
                                    $date_t_stmt->execute(array($teacher, $key,'Active') ); 
                                    $date_count = $date_t_stmt->rowCount(); 
                                    $date_t=$date_t_stmt->fetchAll(); 
                                    
                                    ?>
                                    <p class="card-details mb-0 ">
                                        <?php if ($date_count > 0) {
                                            foreach($date_t as  $value) {
                                                $Active_stmt = $con->prepare("SELECT * FROM students WHERE Code=?  AND `status`=?  LIMIT 1 ");
                                                $Active_stmt->execute(array($value['Student'],"Active"));
                                                $Active_count = $Active_stmt->rowCount();
                                                if ($Active_count > 0 ) {
                                                    $FStudentName = explode(' ',$value['Student']); echo "<span class='badge bg-primary w-100 fs-5 mb-2'> <span class='badge bg-primary w-50'> ".TimeToDisplay($key)." </span>"."</br>"."<span class='badge bg-light mb-2 card-details w-100'>"."  ". $FStudentName[0] ." ".$value['StudentName']."</span>" . "</span>" ;
                                                    $week4_i++  ;
                                                }
                                            }
                                        }else {echo '' ;}?>
                                    </p>
                                <?php } ?>
                            <!-- ----------------------------------- -->     
                            <?php if ($week4_i == 0) { ?> 
                                <div>
                                    <div class="alert alert-danger text-center" role="alert"  style=" width: 100%;" >
                                        لا توجد حلقات مسجلة
                                    </div>
                                </div>
                            <?php } ?> 
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 my-2">
                <div class="card h-100">
                    <div class="card-body rtl">
                        <h1 class="card-title text-center fs-4 fw-bold"> <i class="fa-solid fa-thumbtack"></i> &nbsp;الأربعاء</h1> <hr>
                
                        <?php if (empty($week5)) { ?> 
                            <div>
                                <div class="alert alert-danger text-center" role="alert"  style=" width: 100%;" >
                                    لا توجد حلقات مسجلة
                                </div>
                            </div>
                        <?php }else { ?>

                            <!-- ----------------------------------- -->
                                <?php $week5_i = 0 ; foreach ($week5 as $key ) {
                                    $date_t_stmt = $con->prepare("SELECT * FROM class WHERE Teacher= ? AND $Day5 = ? AND `status` =?"); 
                                    $date_t_stmt->execute(array($teacher, $key,'Active') ); 
                                    $date_count = $date_t_stmt->rowCount(); 
                                    $date_t=$date_t_stmt->fetchAll(); 
                                    ?>
                                    <p class="card-details mb-0 ">
                                        <?php if ($date_count > 0) {
                                            foreach($date_t as  $value) {
                                                $Active_stmt = $con->prepare("SELECT * FROM students WHERE Code=?  AND `status`=?  LIMIT 1 ");
                                                $Active_stmt->execute(array($value['Student'],"Active"));
                                                $Active_count = $Active_stmt->rowCount();
                                                if ($Active_count > 0 ) {
                                                    $FStudentName = explode(' ',$value['Student']); echo "<span class='badge bg-primary w-100 fs-5 mb-2'> <span class='badge bg-primary w-50'> ".TimeToDisplay($key)." </span>"."</br>"."<span class='badge bg-light mb-2 card-details w-100'>"."  ". $FStudentName[0] ." ".$value['StudentName']."</span>" . "</span>" ;
                                                    $week5_i++ ;
                                                }
                                            }
                                        }else {echo '' ;}?>
                                    </p>
                                <?php } ?>
                            <!-- ----------------------------------- -->     
                            <?php if ($week5_i == 0) { ?> 
                                <div>
                                    <div class="alert alert-danger text-center" role="alert"  style=" width: 100%;" >
                                        لا توجد حلقات مسجلة
                                    </div>
                                </div>
                            <?php } ?>   
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 my-2">
                <div class="card h-100">
                    <div class="card-body rtl">
                        <h1 class="card-title text-center fs-4 fw-bold"> <i class="fa-solid fa-thumbtack"></i> &nbsp;الخميس</h1> <hr>
                
                        <?php if (empty($week6)) { ?> 
                            <div>
                                <div class="alert alert-danger text-center" role="alert"  style=" width: 100%;" >
                                    لا توجد حلقات مسجلة
                                </div>
                            </div>
                        <?php }else { ?>

                            <!-- ----------------------------------- -->
                                <?php $week6_i = 0 ; foreach ($week6 as $key ) {
                                    $date_t_stmt = $con->prepare("SELECT * FROM class WHERE Teacher= ? AND $Day6 = ? AND `status` =?"); 
                                    $date_t_stmt->execute(array($teacher, $key,'Active') ); 
                                    $date_count = $date_t_stmt->rowCount(); 
                                    $date_t=$date_t_stmt->fetchAll(); 
                                    
                                    ?>
                                    <p class="card-details mb-0 ">
                                        <?php if ($date_count > 0) {
                                            foreach($date_t as  $value) {
                                                $Active_stmt = $con->prepare("SELECT * FROM students WHERE Code=?  AND `status`=?  LIMIT 1 ");
                                                $Active_stmt->execute(array($value['Student'],"Active"));
                                                $Active_count = $Active_stmt->rowCount();
                                                if ($Active_count > 0 ) {
                                                    $FStudentName = explode(' ',$value['Student']); echo "<span class='badge bg-primary w-100 fs-5 mb-2'> <span class='badge bg-primary w-50'> ".TimeToDisplay($key)." </span>"."</br>"."<span class='badge bg-light mb-2 card-details w-100'>"."  ". $FStudentName[0] ." ".$value['StudentName']."</span>" . "</span>" ;
                                                    $week6_i++ ;
                                                }
                                            }
                                        }else {echo '' ;}?>
                                    </p>
                                <?php } ?>
                            <!-- ----------------------------------- -->     
                            <?php if ($week6_i == 0) { ?> 
                                <div>
                                    <div class="alert alert-danger text-center" role="alert"  style=" width: 100%;" >
                                        لا توجد حلقات مسجلة
                                    </div>
                                </div>
                            <?php } ?>      
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 my-2">
                <div class="card h-100">
                    <div class="card-body rtl">
                        <h1 class="card-title text-center fs-4 fw-bold"> <i class="fa-solid fa-thumbtack"></i> &nbsp; الجمعة</h1> <hr>
                
                        <?php if (empty($week7)) { ?> 
                            <div>
                                <div class="alert alert-danger text-center" role="alert"  style=" width: 100%;" >
                                    لا توجد حلقات مسجلة
                                </div>
                            </div>
                        <?php }else { ?>

                            <!-- ----------------------------------- -->
                            <?php $week7_i = 0 ; foreach ($week7 as $key ) {
                                $date_t_stmt = $con->prepare("SELECT * FROM class WHERE Teacher= ? AND $Day7 = ? AND `status` =?"); 
                                $date_t_stmt->execute(array($teacher, $key,'Active') ); 
                                $date_count = $date_t_stmt->rowCount(); 
                                $date_t=$date_t_stmt->fetchAll(); 
                                
                                ?>
                                <p class="card-details mb-0 ">
                                    <?php if ($date_count > 0) {
                                        foreach($date_t as  $value) {
                                            $Active_stmt = $con->prepare("SELECT * FROM students WHERE Code=?  AND `status`=?  LIMIT 1 ");
                                            $Active_stmt->execute(array($value['Student'],"Active"));
                                            $Active_count = $Active_stmt->rowCount();
                                            if ($Active_count > 0 ) {
                                                $FStudentName = explode(' ',$value['Student']); echo "<span class='badge bg-primary w-100 fs-5 mb-2'> <span class='badge bg-primary w-50'> ".TimeToDisplay($key)." </span>"."</br>"."<span class='badge bg-light mb-2 card-details w-100'>"."  ". $FStudentName[0] ." ".$value['StudentName']."</span>" . "</span>" ;
                                                $week7_i++ ;
                                            }
                                        }
                                    }else {echo '' ;}?>
                                </p>
                            <?php } ?>
                            <!-- ----------------------------------- -->
                            <?php if ($week7_i == 0) { ?> 
                                <div>
                                    <div class="alert alert-danger text-center" role="alert"  style=" width: 100%;" >
                                        لا توجد حلقات مسجلة
                                    </div>
                                </div>
                            <?php } ?> 
                        <?php } ?>
                    </div>
                </div>
            </div>

        </div>

    </section>
  </div>
</main><!-- End #main -->
<?php include "assets/tem/footer.php" ?>
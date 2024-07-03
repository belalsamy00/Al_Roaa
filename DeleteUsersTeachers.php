<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['manager'])) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ; ?>
<main id="main" class="main">
  <div class="container">
    <div class="pagetitle">
      <h1>UsersTeachers</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Delete user</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    
    <section class="section">
      <div class="row">
        <div class="col-lg-6 m-auto">
    
          <div class="card">
            <div class="card-body rtl">
              <h5 class="card-title text-center"> حذف مستخدم </h5>
              <?php
                if (isset($_GET['ID'])) {
                    $id = filter_var($_GET["ID"], FILTER_UNSAFE_RAW );
                    $Dschedule_stmt = $con->prepare("SELECT * FROM teachers WHERE Code = ?  ");
                    $Dschedule_stmt->execute(array($id));
                    $Dschedule=$Dschedule_stmt->fetch();
                    $Dschedule_count = $Dschedule_stmt->rowCount(); 
                    if ($Dschedule_count > 0) {
                        
                        $stmt = $con->prepare("DELETE FROM teachers WHERE Code= ? ");
                        $stmt->execute(array($id));
                        $count = $stmt->rowCount();
                        if ($count > 0  ) {
                            
                            ?>
                                <div class="text-center alert alert-success" role="alert">
                                    تم حذف المستخدم  بنجاح
                                </div>
                                <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button> 
                                <a href="index"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2">  الصفحة الرئيسئة</a>
                        <?php  } else {
                            ?>
                        <div class="text-center alert alert-danger" role="alert">
                            لم يتم حذف بيانات المستخدم هناك مشكلة ما!
                        </div>
                        <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button> 
                        <a href="index"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2">  الصفحة الرئيسئة</a>
                        <?php
                        }
                    }else {
                    ?>
                        <div class=" text-center alert alert-danger" role="alert">
                            لم يتم حذف بيانات المستخدم هناك مشكلة ما!
                        </div>
                        <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button> 
                        <a href="index"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2">  الصفحة الرئيسئة</a>
                    <?php
                    }
                }else {?>
                        <div class="text-center alert alert-danger" role="alert">
                            لم يتم حذف بيانات المستخدم هناك مشكلة ما!
                        </div>
                        <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button> 
                        <a href="index"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2">  الصفحة الرئيسئة</a>
                    <?php
                }
                ?> 
            </div>
          </div>
    
        </div>
      </div>
    </section>
  </div>


</main><!-- End #main -->
<?php include "assets/tem/footer.php" ?>
<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if ( !isset($_SESSION['manager'])) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ;  ?>
<main id="main" class="main">
  <div class="container">
    <div class="pagetitle">
      <h1>Users Page</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Users</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    
    <section class="section">
      <div class="row">
        <div class="col-lg-6 m-auto">
    
          <div class="card">
            <div class="card-body">
            <h5 class="card-title text-center">  <span> Users </span></h5>
                  <?php if ($_SERVER ['REQUEST_METHOD'] =='POST') {
                    $Name = filter_var($_POST["Name"], FILTER_UNSAFE_RAW );
                    $Code = filter_var($_POST["Code"], FILTER_UNSAFE_RAW );
                    $oldPass = filter_var($_POST["oldPassword"], FILTER_UNSAFE_RAW );
                    $oldCode = filter_var($_POST["oldCode"], FILTER_UNSAFE_RAW );
                    $newPass = filter_var($_POST["newPassword"], FILTER_UNSAFE_RAW );
                    $trust = filter_var($_POST["trust"], FILTER_UNSAFE_RAW );
                    if (empty($newPass)) {$Password = $oldPass;}else{$Password = sha1($newPass);}
                    $stmt = $con->prepare("UPDATE  users SET `Name`=?   , Code=? , `Password`=?, `trust`=?  WHERE Code =? ");
                    $stmt->execute(array( $Name , $Code  , $Password, $trust , $oldCode  ));
                    $xcount = $stmt->rowCount();
                   
                    if ($xcount > 0  ) {
                        ?>
                            <div class="text-center mt-2 alert alert-success" role="alert">
                                تم تعديل بيانات المستخدم بنجاح
                            </div>
                            <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button> 
                            <a href="index"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2">  الصفحة الرئيسئة</a>
                    <?php  } else { ?>
                      <div class="text-center mt-2 alert alert-danger" role="alert">
                            لم يتم تعديل بيانات المستخدم هناك مشكلة ما!
                        </div>
                        <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button> 
                        <a href="index"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2">  الصفحة الرئيسئة</a>
                    <?php
                    }
                }else {
                if (isset($_GET['ID'])) {
                    $id = filter_var($_GET["ID"], FILTER_UNSAFE_RAW );
                    $update_stmt = $con->prepare("SELECT * FROM users WHERE Code = ?  ");
                    $update_stmt->execute(array($id));
                    $update=$update_stmt->fetch();
                    $update_count = $update_stmt->rowCount();
                    if ($update_count > 0 ) { ?>

                            <form name="myForm" novalidate   class="forms-sample needs-validation" action="" method="post"  enctype="multipart/form-data">
                            <input  class="form-control "  type="hidden" value="<?php echo $update['Password'] ?>" id="Password" name="oldPassword" autocomplete="new-password"  required>
                            <input  class="form-control "  type="hidden" value="<?php echo $update['Code'] ?>" id="oldCode" name="oldCode" autocomplete="new-password"  required>
                                <div class="form-group  ">


                                    <div class="input-group mb-3">
                                      <span class="input-group-text" id="basic-addon1">الأسم</span>
                                      <input autofocus class="form-control " value="<?php echo $update['Name'] ?>" type="text" id="Name" name="Name"   required  aria-describedby="basic-addon1">
                                      <div id="NameFeedback" class="invalid-feedback"> خطأ </div>
                                      <div id="NameFeedback" class="valid-feedback">جيد</div>
                                    </div>

                                    <div class="input-group mb-3">
                                      <span class="input-group-text" id="basic-addon1">UserName</span>
                                      <input class="form-control"  type="text" value="<?php echo $update['Code'] ?>" id="Code" name="Code" autocomplete="off"  required aria-describedby="basic-addon1">
                                      <div id="NameFeedback" class="invalid-feedback"> خطأ </div>
                                      <div id="NameFeedback" class="valid-feedback">جيد</div>
                                    </div>

                                    <div class="input-group mb-3">
                                      <span class="input-group-text" id="basic-addon1">Password</span>
                                      <input id="password-field" type="password" class="form-control rtl" name="newPassword" autocomplete="new-password"   aria-describedby="basic-addon1 basic-addon2">
                                      <span class="input-group-text" id="basic-addon2"> <span toggle="#password-field" class="bi bi-eye-fill toggle-password " ></span></span>
                                      <div id="NameFeedback" class="invalid-feedback"> خطأ </div>
                                      <div id="NameFeedback" class="valid-feedback">جيد</div>
                                    </div>

                                    <div class="input-group mb-3">
                                      <span class="input-group-text" id="basic-addon1">المهنة</span>
                                      <select name="trust"  class="form-control form-select form-select-lg" aria-label=".form-select-lg example"required='true'   aria-describedby="basic-addon1">
                                      <option <?php if ($update['trust'] == '1'  ) { echo "selected" ; } ?> value="1">Teacher</option>
                                      <option <?php if ($update['trust'] == '3'  ) { echo "selected" ; } ?> value="3">supervisor</option>
                                      <option <?php if ($update['trust'] == '4'  ) { echo "selected" ; } ?> value="4">Supervising Manager</option>
                                          <option <?php if ($update['trust'] == '5'  ) { echo "selected" ; } ?> value="5">Admin</option>
                                          <option <?php if ($update['trust'] == '6'  ) { echo "selected" ; } ?> value="6">Suber Admin</option>
                                          <option <?php if ($update['trust'] == '7'  ) { echo "selected" ; } ?> value="7">Manager</option>
                                          <option <?php if ($update['trust'] == '8'  ) { echo "selected" ; } ?> value="8">CustomerService</option>
                                      </select>
                                      <div id="NameFeedback" class="invalid-feedback"> خطأ </div>
                                      <div id="NameFeedback" class="valid-feedback">جيد</div>
                                    </div>

                                    <div class="form-group row">
                                        <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" type="submit">تعديل مستخدم</button>
                                    </div>
                                </div>
                            </form>
                            <div class="form-group row ">
                            <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button> 
                            </div>
                            <?php
                        }else {
                        ?>
                                <div class="text-center mt-2 alert alert-danger" role="alert">
                                    لم يتم العثور على المستخدم هناك مشكلة ما!
                                </div>
                            <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button> 
                          <a href="index"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2">  الصفحة الرئيسئة</a>
                        <?php
                        }
                        ?>
                        <?php
                    }else {
                      ?>
                      <div class="text-center mt-2 alert alert-danger" role="alert">
                          لم يتم العثور على المستخدم هناك مشكلة ما!
                      </div>
                  <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button> 
                  <a href="index"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2">  الصفحة الرئيسئة</a>
              <?php
                    }
                }
                ?> 
            </div>
          </div>
    
        </div>
    
      </div>
    </section>
  </div>


</main><!-- End #main -->
<script src="assets/js/jquery-22-05-2023.js"></script>
<?php include "assets/tem/footer.php" ?>
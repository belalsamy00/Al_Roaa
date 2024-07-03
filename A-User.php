<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if ( !isset($_SESSION['manager'])) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ; ?>
<main id="main" class="main">
  <div class="container">
    <div class="pagetitle">
      <h1>Add User</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">Home</a></li>
          <li class="breadcrumb-item active">Add User</li>
        </ol>
      </nav>
    </div>
    <!-- End Page Title -->
    
    <section class="section">
      <div class="row">
        <div class="col-lg-6 m-auto">
    
          <div class="card">
            <div class="card-body rtl">
                <?php if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
                $pagename = "Add" ;
                $Code = filter_var($_POST["Code"], FILTER_UNSAFE_RAW );
                $Name = filter_var($_POST["Name"], FILTER_UNSAFE_RAW );
                $Pass = filter_var($_POST["Password"], FILTER_UNSAFE_RAW );
                $Password = sha1($Pass);
                $trust = filter_var($_POST["trust"], FILTER_UNSAFE_RAW );
                $insert_s_stmt = $con->prepare("SELECT * FROM users WHERE Code = ? ");
                $insert_s_stmt->execute(array($Code));
                $insert_s_count = $insert_s_stmt->rowCount();
                if ($insert_s_count > 0) {  ?>
                <div class="text-center mt-2 alert alert-danger" role="alert">
                كود المستخدم موجود بالفعل
                </div>
                <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button> 
                <a href="index"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2">  الصفحة الرئيسئة</a>
                <?php 

                }else {
                  if ($trust == 1) {
                    $insert_s_stmt = $con->prepare("SELECT * FROM teachers WHERE Code = ? ");
                    $insert_s_stmt->execute(array($Code));
                    $insert_s_count = $insert_s_stmt->rowCount();
                    if ($insert_s_count > 0) {  ?>
                    <div class="text-center mt-2 alert alert-danger" role="alert">
                    كود المستخدم موجود بالفعل
                    </div>
                    <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button> 
                    <a href="index"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2">  الصفحة الرئيسئة</a>
                    <?php 
                    }else {
                      $stmt = $con->prepare("INSERT INTO teachers ( Code , `Name`  , `Password`  , HourRate  ) VALUES (?,?,?,?)");
                      $stmt->execute(array( $Code , $Name , $Password , 30 ));
                      $count = $stmt->rowCount();
                      if ($count > 0) { ?>
                      <div class="text-center mt-2 alert alert-success" role="alert">
                      تم أضافة المستخدم 
                      </div>
                      <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button> 
                      <a href="index"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2">  الصفحة الرئيسئة</a>
                      <?php  } else {
                      ?>
                      <div class="text-center mt-2 alert alert-danger" role="alert">
                      لم تتم أضافة الطالب هناك مشكلة ما!
                      </div>
                      <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button> 
                      <a href="index"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2">  الصفحة الرئيسئة</a>
                      <?php
                      }
                    }
                  }else {
                    $stmt = $con->prepare("INSERT INTO users ( Code , `Name`  , `Password`  , trust  ) VALUES (?,?,?,?)");
                    $stmt->execute(array( $Code , $Name , $Password , $trust ));
                    $count = $stmt->rowCount();
                    if ($count > 0) { ?>
                    <div class="text-center mt-2 alert alert-success" role="alert">
                    تم أضافة المستخدم 
                    </div>
                    <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button> 
                    <a href="index"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2">  الصفحة الرئيسئة</a>
                    <?php  } else {
                    ?>
                    <div class="text-center mt-2 alert alert-danger" role="alert">
                    لم تتم أضافة الطالب هناك مشكلة ما!
                    </div>
                    <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button> 
                    <a href="index"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2">  الصفحة الرئيسئة</a>
                    <?php
                    }
                  }
                }
                }else { ?>
                  <h5 class="card-title text-center mt-2 ">اضافة مستخدم جديد </h5>
                  <form name="myForm" novalidate   class="forms-sample needs-validation" action="" method="post"  enctype="multipart/form-data">
                    <div class="form-group  ">

                    <div class="form-group row">
                    <label for="Name">الأسم</label>
                    <input autofocus class="form-control "  type="text" id="Name" name="Name"   required>
                    <div id="NameFeedback" class="invalid-feedback">اكتب الأسم </div>
                    <div id="NameFeedback" class="valid-feedback">جيد</div>
                    </div>

                    <div class="form-group row">
                    <label for="Code">UserName</label>
                    <input  class="form-control"  type="text" id="Code" name="Code" autocomplete="off"  required>
                    <div id="CodeFeedback" class="invalid-feedback">اكتب الكود</div>
                    <div id="CodeFeedback" class="valid-feedback">جيد</div>
                    </div>

                    <div class="form-group row ltr">
                    <label for="Password">Password</label>
                    <input id="password-field" type="password" class="form-control rtl" name="Password" autocomplete="new-password"   >
                    <span toggle="#password-field" class="bi bi-eye-fill field-icon toggle-password w-10" ></span>
                    <div id="PasswordFeedback" class="invalid-feedback">اكتب  باسورد</div>
                    <div id="PasswordFeedback" class="valid-feedback">جيد</div>
                    </div>

                    <div class="form-group row ">
                    <label      for="trust">المهنة</label>
                    <select     id name="trust"  class="form-control form-select form-select-lg mb-3" aria-label=".form-select-lg example"required='true'>
                    <option     selected disabled value="">-</option>
                    <option     value="1">Teacher</option>
                    <option     value="3">supervisor</option>
                    <option     value="4">Supervising Manager</option>
                    <option     value="5">Admin</option>
                    <option     value="6">Super Admin</option>
                    <option     value="8">CustomerService</option>
                    <option     value="9">CustomerServiceManager</option>
                    <option     value="7">Manager</option>
                    </select>
                    <div id="trustFeedback" class="invalid-feedback">اختر</div>
                    <div id="trustFeedback" class="valid-feedback">جيد</div>
                    </div>

                    <div class="form-group row">
                    <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" type="submit">أضافة المستخدم</button>
                    </div>
                    </div>
                  </form>
                <?php } ?>
            </div>
          </div>
    
        </div>
    
      </div>
    </section>
  </div>


</main><!-- End #main -->
<script src="assets/js/jquery-22-05-2023.js"></script>
<?php include "assets/tem/footer.php" ?>
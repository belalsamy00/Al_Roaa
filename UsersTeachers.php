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
      <h1>Mange Teachers Page</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">Home</a></li>
          <li class="breadcrumb-item active">Teachers</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    
    <section class="section">
      <div class="row">
        <div class="col-lg-6 m-auto">
          <?php if ($_SERVER ['REQUEST_METHOD'] =='POST'){ 
          if (isset($_POST['All'])) { ?>
            <div class="card">
              <div class="card-body rtl ">
              <h5 class="card-title text-center"> إدارة المعلمين </h5>
                <?php
                $edite_s_stmt = $con->prepare("SELECT * FROM teachers  ORDER BY `Code` ASC ");
                $edite_s_stmt->execute();
                $edite=$edite_s_stmt->fetchAll();
                $edite_s_count = $edite_s_stmt->rowCount();
                if ($edite_s_count > 0) {
                foreach ($edite as $s ) {
                ?>
                <hr>
                <h5 class=" text-center"> <?php echo $s['Name'] ?></h5>
                <div class=" d-flex flex-column justify-content-between mt-2">
                <span class="w-100">
                <span class="w-25 m-0 btn btn-light"> <?php echo $s['Code'] ?></span>
                <span class='w-25 m-0 btn btn-light'>teacher</span>
                <a class="btn btn-outline  fs-6 fw-bold mt-2" href="UpdateUsersTeachers?ID=<?php echo $s['Code'] ?>"><lord-icon src="https://cdn.lordicon.com/hbigeisx.json" trigger="loop-on-hover" colors="primary:#121331"style="width:25px;height:25px"></lord-icon></i></a>

                <a class="btn btn-outline  fs-6 fw-bold mt-2"  onclick="return confirm('هل انت متأكد من الحذف ؟');" href="DeleteUsersTeachers?ID=<?php echo $s['Code'] ?>"><lord-icon src="https://cdn.lordicon.com/jmkrnisz.json"    trigger="loop-on-hover"  colors="primary:#121331"style="width:25px;height:25px"></lord-icon> </a>
                </span>

                </div>
                <?php
                } 
                ?>
                <div class=" mb-3  ">
                <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button>
                </div>
                <?php
                }else {
                header('location: UsersTeachers?false');exit;
                } ?>
              </div>
            </div>
            <?php }else { ?>
              <div class="card">
                <div class="card-body rtl">
                <h5 class="card-title text-center"> إدارة المعلمين </h5>
                <?php
                $Code = filter_var($_POST["Code"], FILTER_UNSAFE_RAW );
                $edite_s_stmt = $con->prepare("SELECT * FROM teachers WHERE Code = ?  ");
                $edite_s_stmt->execute(array($Code));
                $edite=$edite_s_stmt->fetchAll();
                $edite_s_count = $edite_s_stmt->rowCount();
                if ($edite_s_count > 0) {
                foreach ($edite as $s ) {
                ?>
                <div class="  p-1 ">
                <span class="card-details mt-2"> <?php echo $s['Name'].'<br>'; ?></span>
                <span class="card-details mb-0"> teacher </span>
                <a class="btn btn-outline-primary w-100 fs-6 fw-bold mt-2" href="UpdateUsersTeachers?ID=<?php echo $s['Code'] ?>">تعديل </a>

                <a class="btn btn-outline-primary w-100 fs-6 fw-bold mt-2" onclick="return confirm('هل انت متأكد من الحذف ؟');" href="DeleteUsersTeachers?ID=<?php echo $s['Code'] ?>">حذف </a>
                <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button>

                </div>
                <?php
                } 
                }else {
                  $_SESSION['message'] = 'false';
                  header('location: UsersTeachers');exit;
                } ?>
                </div>
              </div>
            <?php } ?>

            <?php }else { ?>
              <div class="card">
                <div class="card-body rtl">
                <h5 class="card-title text-center">  <span> teachers </span></h5>
                  <form novalidate   class="forms-sample needs-validation" action="" method="post">
                    <div class="form-group row mt-2">
                    <input autofocus class="form-control " placeholder="الكود"  type="text" id="Code" name="Code" required>
                    <div id="CodeFeedback" class="invalid-feedback">اكتب الكود  فقط</div>
                    <div id="CodeFeedback" class="valid-feedback">جيد</div>
                    </div>
                    <div class="form-group row">
                    <button class="btn btn-outline-primary w-100 fs-6 fw-bold mt-2"> ابحث عن المعلم </button>
                    </div>
                  </form>
                  <form novalidate   class="forms-sample needs-validation" action="" method="post">
                    <input  class="form-control "  type="hidden" id="Code" name="All" required>
                    <div class="form-group row">
                    <button class="btn btn-outline-primary w-100 fs-6 fw-bold mt-2">عرض جميع المعلمين</button>
                    </div>
                  </form>
                  <?php
              if (!empty($_SESSION['message'])) { ?>
                <div class="text-center alert alert-danger mt-2" role="alert">
                  <p>من فضلك تأكد من الكود</p>
                  <p>اكتب الكود فقط بدون الأسم</p>
                </div>
              <?php unset($_SESSION['message']); } ?>
                </div>
              </div>
            <?php } ?>
    
        </div>
    
      </div>
    </section>
  </div>


</main><!-- End #main -->
<?php include "assets/tem/footer.php" ?>
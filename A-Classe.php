<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['Admin']) AND !isset($_SESSION['Suber_Admin']) AND !isset($_SESSION['manager'])) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ; ?>

<main id="main" class="main">
  <div class="container">
    <div class="pagetitle">
      <h1>Add Classe</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">Home</a></li>
          <li class="breadcrumb-item active">Add Classe</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    
    <section class="section">
      <div class="row">
        <div class="col-lg-6 m-auto">
    
          <div class="card p-3">
            <div class="card-body rtl">
              <?php 
              if (isset($_GET['code'])) {
                $student = filter_var($_GET["code"], FILTER_UNSAFE_RAW );
                $Add_cs_stmt = $con->prepare("SELECT Code FROM students WHERE Code LIKE ?");
                $Add_cs_stmt->execute(array("%$student %"));
                $Sclass=$Add_cs_stmt->fetchAll();
                $Add_cs_count = $Add_cs_stmt->rowCount();
                if ($Add_cs_count > 0) {
                  
                  $b_stmt = $con->prepare("SELECT * FROM class  WHERE Student LIKE ? AND `type` = ? ");
                  $b_stmt->execute(array("%$student %",2));
                  $class_count = $b_stmt->rowCount();

                  $d_stmt = $con->prepare("SELECT SUM(Days) FROM class  WHERE Student LIKE ? AND `type` = ? ");
                  $d_stmt->execute(array("%$student %",2));
                  $N_class=$d_stmt->fetch();

                  $c_stmt = $con->prepare("SELECT * FROM students WHERE Code LIKE ?");
                  $c_stmt->execute(array("%$student %"));
                  $N_Students=$c_stmt->fetch();
                  $c_count = $c_stmt->rowCount();

                  $Add_ct_stmt = $con->prepare("SELECT Code FROM teachers");
                  $Add_ct_stmt->execute(array());
                  $Tclass=$Add_ct_stmt->fetchAll();
                  $Add_ct_count = $Add_ct_stmt->rowCount();
                  $ID = rand(0,1000).date("d").rand(9,99); 
                  if (isset($_GET["Trail"])) { ?> 
                    <form name="myForm" novalidate   class="forms-sample needs-validation" action="in-to-db?do=Add_c" method="post" id="sheetdb-form" enctype="multipart/form-data">
                      <div class="form-group  ">
                          <div  style="display: none;" class="form-group row ">
                              <input type="hidden" id="ID" value="<?php echo $ID ; ?>" name="ID" >
                          </div>

                          <div class="form-group row   ">
                              <label      for="Student">كود الطالب</label>
                              <select id="Student" name="Student" class="form-control form-select form-select-lg mb-3" aria-label=".form-select-lg example"required='true'>
                                  <?php 
                                  foreach ($Sclass as $key ) { ?>
                                  <option    selected  value="<?php echo $key['Code'] ?>"><?php echo $key['Code'] ?></option> <?php } ?>
                              </select> 
                              <div id="StudentFeedback" class="invalid-feedback">اختر الكود بشكل صحيح</div>
                              <div id="StudentFeedback" class="valid-feedback">جيد</div>
                          </div>

                          <div class="form-group row   ">
                              <label      for="Teacher">المعلم</label>
                              <select      id="Teacher" name="Teacher" class="form-control form-select form-select-lg mb-3" aria-label=".form-select-lg example"required='true'>
                                  <option     selected disabled value="">اختر معلم</option>
                                  <?php
                                  foreach ($Tclass as $key ) { ?>
                                  <option     value="<?php echo $key['Code'] ?>"><?php echo $key['Code'] ?></option>
                                  <?php } 
                                  ?>
                              </select>
                              <div id="TeacherFeedback" class="invalid-feedback">اختر</div>
                              <div id="TeacherFeedback" class="valid-feedback">جيد</div>
                          </div>

                          <div class="form-group row   ">
                              <label for="Duration">مدة الحصة</label>
                              <select     id="Duration"  name="Duration" class="form-control form-select form-select-lg mb-3" aria-label=".form-select-lg example"required='true'>
                                  <option     selected disabled value="">-</option>
                                  <option     value="15"   >15</option>
                                  <option     value="20"   >20</option>
                                  <option     value="30"   >30</option>
                                  <option     value="45"   >45</option>
                                  <option     value="60"   >60</option>
                                  <option     value="75"   >75</option>
                                  <option     value="90"   >90</option>
                                  <option     value="105"  >105</option>
                                  <option     value="120"  >120</option>
                                  <option     value="135"  >135</option>
                                  <option     value="150"  >150</option>
                                  <option     value="165"  >165</option>
                                  <option     value="180"  >180</option>
                              </select>
                              <div id="DurationFeedback" class="invalid-feedback">اختر</div>
                              <div id="DurationFeedback" class="valid-feedback">جيد</div>
                          </div> 

                          <div class="form-group row   ">
                                    <label for="category">تصنيف الجدول</label>
                                    <select     id="category"  name="category" class="form-control form-select form-select-lg mb-3" aria-label=".form-select-lg example"  required='true'>
                                    <option     selected disabled value="">-</option>
                                        <option     value="quran"   >قرآن</option>
                                        <option     value="quran_en"   >قرآن للآعاجم</option>
                                        <option     value="nour"   >نور البيان</option>
                                        <option     value="arabic"   >اللغة العربية</option>
                                        <option     value="religion"   >مواد شرعية</option>
                                    </select>
                                    <div id="categoryFeedback" class="invalid-feedback">اختر</div>
                                    <div id="categoryFeedback" class="valid-feedback">جيد</div>
                                </div>
                                
                          <div style=" display: none;" class="form-group row    ">
                          <label for="StudentName"> نوع الحصة</label>
                          <div class="form-check form-check-inline">
                              <input class="form-check-input" value="trail" type="radio" name="class_type" id="class_type"  required checked >
                              <label class="form-check-label" for="class_type">حصة تجريبية</label>
                          </div>
                          <div class="form-check form-check-inline">
                              <input class="form-check-input" value="Rescheduled" type="radio" name="class_type" id="class_type2"  required >
                              <label class="form-check-label" for="class_type2">حصة تعويضية</label>
                          </div>
                      </div>
                          
                          <div style=" display: none;" class="form-group row    ">
                              
                              <input class="form-control"  type="hidden" id="StudentName" name="StudentName" value="oneTimeClass"   required='true'>
                              <div id="T_codeFeedback" class="invalid-feedback">اسم الطالب فقط بدون رموز بحد اقصى 15 حرف</div>
                              <div id="T_codeFeedback" class="valid-feedback">جيد</div>
                          </div> 

                          <div class="form-group row    ">
                              <label for="time">الوقت</label>
                              <input class="form-control"  type="time" id="time" name="time"  required='true'>
                              <div id="timeFeedback" class="invalid-feedback">ادخل الوقت</div>
                              <div id="timeFeedback" class="valid-feedback">جيد</div>
                          </div> 
                          <div class="form-group row    ">
                              <label for="StudentName"> تاريخ الحصة</label>
                              <input class="form-control"  type="date" id="for_one_time" name="for_one_time"  maxlength="15"  required='true'>
                              <div id="for_one_timeFeedback" class="invalid-feedback">ادخل التاريخ</div>
                              <div id="for_one_timeFeedback" class="valid-feedback">جيد</div>
                          </div> 

                          <div class="form-group row   ">
                          <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" type="submit">أضافة الحصة</button>
                          </div>

                      </div>
                  </form>
                  <div class="form-group row   ">
                      <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button> 
                  </div>
                  <?php }else {
                    if ($class_count >= $N_Students['N_Students'] ) { ?>
                      <div class=" ">
                          <div class="alert alert-danger" role="alert">
                          <p>لا يمكن اضافة طالب جديد!!</p>
                          <p>  عدد الطلاب الموجود على الكود الرئيسى  <?php echo  $N_Students['N_Students'] ; ?></p>
                          <p>  عدد الاكواد الفرعية الموجوده بالفعل <?php echo  $class_count ; ?> </p>
                          </div>
                          <a href="Edite"style="width: 100%; color: white;margin-bottom: 5px;" class="button_primary"> يمكنك تعديل جدول الطالب من هنا </a>
                          <a href="Home"style="width: 100%; color: white;" class="button_primary">عد الى الصفحة الرئيسئة</a>
                      </div>
                      <div class=" ">
                      <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button> 
                      </div> 
                      <?php
                    }else {
                        $max = $N_Students['Days']-$N_class['SUM(Days)']; 
                        if ($max === 0 ) { ?>
                          <div class=" ">
                            <div class="alert alert-danger" role="alert">
                            <p>لا يمكن اضافة طالب جديد!!</p>
                            <p>  عدد الحصص المتاح فى الكود  <?php echo  $max ; ?> </p>
                          </div>
                          <a href="Edite"style="width: 100%; color: white;margin-bottom: 5px;" class="button_primary"> يمكنك تعديل جدول الطالب من هنا </a>
                          <a href="Home"style="width: 100%; color: white;" class="button_primary">عد الى الصفحة الرئيسئة</a>
                      </div>
                      <div class=" ">
                      <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button> 
                      </div> 
                        <?php }else { ?>

                            <form name="myForm" novalidate   class="forms-sample needs-validation" action="in-to-db?do=Add_c" method="post" id="sheetdb-form" enctype="multipart/form-data">

                                <div class="form-group  ">
                
                                    <div  style="display: none;" class="form-group row ">
                                        <input type="hidden" id="ID" value="<?php echo $ID ; ?>" name="ID" >
                                    </div> 
                
                                    <div class="form-group row   ">
                                        <label      for="Student">كود الطالب</label>
                                        <select id="Student" name="Student" class="form-control form-select form-select-lg mb-3" aria-label=".form-select-lg example"required='true'>
                                            <?php 
                                            foreach ($Sclass as $key ) { ?>
                                            <option    selected  value="<?php echo $key['Code'] ?>"><?php echo $key['Code'] ?></option> <?php } ?>
                                        </select>
                                        <div id="StudentFeedback" class="invalid-feedback">اختر الكود بشكل صحيح</div>
                                        <div id="StudentFeedback" class="valid-feedback">جيد</div>
                                    </div>
                    
                                    <div class="form-group row   ">
                                        <label      for="Teacher">المعلم</label>
                                        <select      id="Teacher" name="Teacher" class="form-control form-select form-select-lg mb-3" aria-label=".form-select-lg example"required='true'>
                                            <option     selected disabled value="">اختر معلم</option> 
                                            <?php foreach ($Tclass as $key ) { ?>
                                            <option     value="<?php echo $key['Code'] ?>"><?php echo $key['Code'] ?></option> <?php } ?>
                                        </select>
                                        <div id="sexFeedback" class="invalid-feedback">اختر</div>
                                        <div id="sexFeedback" class="valid-feedback">جيد</div>
                                    </div>
                    
                                    <div class="form-group row   ">
                                        <label for="Duration">مدة الحصة</label>
                                        <select     id="Duration"  name="Duration" class="form-control form-select form-select-lg mb-3" aria-label=".form-select-lg example"  required='true'>
                                        <option     selected disabled value="">-</option>
                                            <option     value="15"   >15</option>
                                            <option     value="20"   >20</option>
                                            <option     value="30"   >30</option>
                                            <option     value="45"   >45</option>
                                            <option     value="60"   >60</option>
                                            <option     value="75"   >75</option>
                                            <option     value="90"   >90</option>
                                            <option     value="105"  >105</option>
                                            <option     value="120"  >120</option>
                                            <option     value="135"  >135</option>
                                            <option     value="150"  >150</option>
                                            <option     value="165"  >165</option>
                                            <option     value="180"  >180</option>
                                        </select>
                                        <div id="DurationFeedback" class="invalid-feedback">اختر</div>
                                        <div id="DurationFeedback" class="valid-feedback">جيد</div>
                                    </div> 

                                    <div class="form-group row    ">
                                        <label      for="Days">عدد الأيام</label>
                                        <input class="form-control"  type="number" id="Days" name="Days" min="4" max="<?php echo $max ; ?>" step="4" required='true'>
                                        <div id="DaysFeedback" class="invalid-feedback">
                                            <p><?php echo $max ; ?> عدد الأيام المتاح فى الكود</p>
                                            <p> الارقام التى يمكن ادخالها 4,8,12,16,20,24,28 </p>
                                        </div>
                                        <div id="DaysFeedback" class="valid-feedback">جيد</div>
                                    </div>

                                    <div class="form-group row   ">
                                        <label for="category">تصنيف الجدول</label>
                                        <select     id="category"  name="category" class="form-control form-select form-select-lg mb-3" aria-label=".form-select-lg example"  required='true'>
                                        <option     selected disabled value="">-</option>
                                            <option     value="quran"   >قرآن</option>
                                            <option     value="quran_en"   >قرآن للآعاجم</option>
                                            <option     value="nour"   >نور البيان</option>
                                            <option     value="arabic"   >اللغة العربية</option>
                                            <option     value="religion"   >مواد شرعية</option>
                                        </select>
                                        <div id="categoryFeedback" class="invalid-feedback">اختر</div>
                                        <div id="categoryFeedback" class="valid-feedback">جيد</div>
                                    </div>  

                                    <div class="form-group row ">
                                        <label for="StudentName">اسم الطالب</label>
                                        <input class="form-control"  type="text" id="StudentName" name="StudentName"  pattern="[a-z a-z,A-Z A-Z]{2,15}"  required='true'>
                                        <div id="T_codeFeedback" class="invalid-feedback">اسم الطالب فقط بدون رموز بحد اقصى 15 حرف</div>
                                        <div id="T_codeFeedback" class="valid-feedback">جيد</div>
                                    </div> 
                    
                                    <div class="form-group row ">

                                        <strong>Choose Days:</strong>

                                            <div class="form-group d-inline-flex justify-content-around mt-3">
                                                <label class="form-check-label d-inline-flex align-items-center w-25 h-100" for="Saturday"><strong>Sa-day:</strong></label>
                                                <input class="form-check-input form-control w-25 h-100" name="Saturday" type="checkbox" value="Saturday" id="Saturday">
                                                <input class="form-check-input form-control w-50 h-100"  type="time" id="Saturday_time" name="Saturday_time" >
                                                <span class="btn btn-light" onclick="document.getElementById('Saturday_time').value = ''">clear </span>

                                            </div>

                                        <div class="form-group d-inline-flex justify-content-around mt-3">
                                            <label class="form-check-label d-inline-flex align-items-center w-25 h-100" for="Sunday"><strong>Su-day:</strong></label>
                                            <input class="form-check-input form-control w-25 h-100" name="Sunday" type="checkbox" value="Sunday" id="Sunday" >
                                            <input class="form-check-input form-control w-50 h-100"  type="time" id="Sunday_time" name="Sunday_time"  >
                                            <span class="btn btn-light" onclick="document.getElementById('Sunday_time').value = ''">clear </span>

                                        </div>

                                        <div class="form-group d-inline-flex justify-content-around mt-3">
                                            <label class="form-check-label d-inline-flex align-items-center w-25 h-100" for="Monday"><strong>Mo-day:</strong></label>
                                            <input class="form-check-input form-control w-25 h-100" name="Monday" type="checkbox" value="Monday" id="Monday" >
                                            <input class="form-check-input form-control w-50 h-100"  type="time" id="Monday_time" name="Monday_time"  >
                                            <span class="btn btn-light" onclick="document.getElementById('Monday_time').value = ''">clear </span>

                                        </div>

                                        <div class="form-group d-inline-flex justify-content-around mt-3">
                                            <label class="form-check-label d-inline-flex align-items-center w-25 h-100" for="Tuesday"><strong>Tu-day:</strong></label>
                                            <input class="form-check-input form-control w-25 h-100" name="Tuesday" type="checkbox" value="Tuesday" id="Tuesday" >
                                            <input class="form-check-input form-control w-50 h-100"  type="time" id="Tuesday_time" name="Tuesday_time"  >
                                            <span class="btn btn-light" onclick="document.getElementById('Tuesday_time').value = ''">clear </span>

                                        </div>

                                        <div class="form-group d-inline-flex justify-content-around mt-3">
                                            <label class="form-check-label d-inline-flex align-items-center w-25 h-100" for="Wednesday"><strong>We-sday:</strong></label>
                                            <input class="form-check-input form-control w-25 h-100" name="Wednesday" type="checkbox" value="Wednesday" id="Wednesday" >
                                            <input class="form-check-input form-control w-50 h-100"  type="time" id="Wednesday_time" name="Wednesday_time"  >
                                            <span class="btn btn-light" onclick="document.getElementById('Wednesday_time').value = ''">clear </span>

                                        </div>

                                        <div class="form-group d-inline-flex justify-content-around mt-3">
                                            <label class="form-check-label d-inline-flex align-items-center w-25 h-100" for="Thursday"><strong>Th-day:</strong></label>
                                            <input class="form-check-input form-control w-25 h-100" name="Thursday" type="checkbox" value="Thursday" id="Thursday" >
                                            <input class="form-check-input form-control w-50 h-100"  type="time" id="Thursday_time" name="Thursday_time"  >
                                            <span class="btn btn-light" onclick="document.getElementById('Thursday_time').value = ''">clear </span>

                                        </div>

                                        <div class="form-group  d-inline-flex justify-content-around mt-3">
                                            <label class="form-check-label d-inline-flex align-items-center w-25 h-100" for="Friday"><strong>Fr-day:</strong></label>
                                            <input class="form-check-input form-control w-25 h-100" name="Friday" type="checkbox" value="Friday" id="Friday" >
                                            <input class="form-check-input form-control w-50 h-100"  type="time" id="Friday_time" name="Friday_time"  >
                                            <span class="btn btn-light" onclick="document.getElementById('Friday_time').value = ''">clear </span>

                                        </div>

                                    </div>


                                    <div class="form-group row mt-2">
                                    <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" type="submit">أضافة الجدول</button>
                                    </div>
                    
                                </div>

                            </form>
            
                            <div class="form-group row   ">
                            <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button> 
                            </div> <?php
                        }
                    }
                  }
                }else {
                    $_SESSION['message'] = 'false';
                    header('location: Management#s');exit;
                }
              }else {
                header('location: 404');exit;
              }
              ?>
            </div>
          </div>
    
        </div>
    
      </div>
    </section>
  </div>


</main>
<!-- End #main -->
<script src="assets/js/jquery-22-05-2023.js"></script>
<?php include "assets/tem/footer.php" ?>
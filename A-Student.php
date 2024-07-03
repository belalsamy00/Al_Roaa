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
      <h1>Add Student Page</h1>
      <nav>
          <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">Home</a></li>
          <li class="breadcrumb-item active">Add Student</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <?php if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
          $pagename = "Add" ;
          $dbCode = filter_var($_POST["Code"], FILTER_UNSAFE_RAW );
          $dbphone = str_replace("+","",str_replace(" ","",filter_var($_POST["Phone_Number"], FILTER_UNSAFE_RAW )));
          $dbsex = filter_var($_POST["sex"], FILTER_UNSAFE_RAW );
          $dbCountry = filter_var($_POST["Country"], FILTER_UNSAFE_RAW );
          $dbN_Students = filter_var($_POST["N_Students"], FILTER_UNSAFE_RAW );
          $dbDays = filter_var($_POST["Days"], FILTER_UNSAFE_RAW );
          $E_Cost = filter_var($_POST["E_Cost"], FILTER_UNSAFE_RAW );
          $S_Cost = filter_var($_POST["S_Cost"], FILTER_UNSAFE_RAW );
          $dbWho = filter_var($_POST["Who"], FILTER_UNSAFE_RAW );
          $S_ID = rand(0,1000).date("d").rand(9,99); 
          $Fstudent = explode(' ',$dbCode);
          $student = $Fstudent[0];
          $insert_s_stmt = $con->prepare("SELECT * FROM students WHERE Code LIKE ? ");
          $insert_s_stmt->execute(array("%$student %"));
          $insert_s_count = $insert_s_stmt->rowCount();

          $ArchivedStudents_stmt = $con->prepare("SELECT * FROM ArchivedStudents WHERE Code LIKE ? ");
          $ArchivedStudents_stmt->execute(array("%$student %"));
          $ArchivedStudents_count = $ArchivedStudents_stmt->rowCount();
          ?>
          <div class="col-lg-4 m-auto">
            <div class="card">
              <div class="card-body  rtl">
                <p class="card-details mt-2 mb-0">  &nbsp; الكود:-   <?php echo $dbCode ?> </p>
                <p class="card-details mt-2 mb-0">  &nbsp; رقم الهاتف:-   <?php echo $dbphone ?> </p>
                <p class="card-details mt-2 mb-0">  &nbsp; الجنس:-  <?php echo $dbsex ?> </p>
                <p class="card-details mt-2 mb-0">  &nbsp; البلد:-   <?php echo $dbCountry ?> </p>
                <p class="card-details mt-2 mb-0">  &nbsp; عدد الطلاب:-   <?php echo $dbN_Students ?> </p>
                <p class="card-details mt-2 mb-0">  &nbsp; عدد الحصص الشهرية:-   <?php echo $dbDays ?> </p>
                <p class="card-details mt-2 mb-0">  &nbsp; الأشتراك بالجنية:-  <?php echo $E_Cost ?> </p>
                <p class="card-details mt-2 mb-0">  &nbsp; الأشتراك بالريال:-   <?php echo $S_Cost ?> </p>
                <p class="card-details mt-2 mb-0">  &nbsp; مسؤول التواصل:-  <?php echo $dbWho ?> </p>

                <?php if (preg_match("/^[aAbBHhjJrR]{1}[0-9]{1,3}\\s[a-zA-Z.-_&*%$#@]{1,20}/", $dbCode)) {
                  if ($insert_s_count > 0 ) {
                    ?><button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" disabled>لم تتم أضافة الكود موجود بالفعل</button>  <?php
                }elseif ($ArchivedStudents_count > 0) {
                  ?><button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" disabled>لم تتم أضافة الكود موجود فى الأرشيف </button>  <?php
                }else { ?>
                  <form name="dbForm"   action="in-to-db?do=Add_S" method="post"  enctype="multipart/form-data">
                      <div  style="display: none;" class="form-group row ">
                      <input type="hidden" id="Code" value="<?php echo $dbCode ; ?>" name="Code" >
                      </div>

                      <div  style="display: none;" class="form-group row ">
                      <input type="hidden" id="studentCode" value="<?php echo $student ; ?>" name="studentCode" >
                      </div>

                      <div   style="display: none;" class="form-group row ">
                      <input id="phone" type="hidden" value="<?php echo $dbphone ; ?>" name="phone">                   
                      </div>

                      <div  style="display: none;" class="form-group row ">
                      <input type="hidden" id="Country" value="<?php echo $dbCountry ; ?>" name="Country">
                      </div> 

                      <div  style="display: none;"class="form-group row ">
                      <input type="hidden" id="sex" value="<?php echo $dbsex ; ?>" name="sex">
                      </div>

                      <div  style="display: none;"  class="form-group row ">
                      <input type="hidden" id="N_Students" value="<?php echo $dbN_Students ; ?>" name="N_Students">
                      </div>

                      <div  style="display: none;" class="form-group row ">
                          <input  type="hidden" id="Days" value="<?php echo $dbDays ; ?>" name="Days">
                      </div> 
                      
                      <div  style="display: none;" class="form-group row ">
                          <input type="hidden" id="Cost" value="<?php echo $E_Cost ; ?>" name="E_Cost">
                      </div> 
                      <div  style="display: none;" class="form-group row ">
                          <input type="hidden" id="Cost" value="<?php echo $S_Cost ; ?>" name="S_Cost">
                      </div> 
                      
                      <div  style="display: none;" class="form-group row ">
                          <input type="hidden" id="Who" value="<?php echo $dbWho ; ?>"  name="Who" >
                      </div>
                      <div  style="display: none;" class="form-group row ">
                          <input type="hidden" id="ID" value="<?php echo $S_ID ; ?>"  name="ID" >
                      </div>
                      <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" >  أضافة الكود  </button>  
                  </form>
                <?php }  ?> 
                <?php }
                  else
                  {
                    ?><button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" disabled>  الكود غير مناسب <br> الطريقة الصحيحة للكود ( x000 xxxxxxx ) </button>  <?php
                  } ?>
                  <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button> 
              </div>
            </div>
          </div>
        <?php }else { ?>
          <div class="col-lg-4 m-auto">
            <div class="card">
                <div class="card-body rtl">
                  <h5 class="card-title text-center"> اضافة كود جديد</h5>
                  <form name="myForm"    class="forms-sample  fw-bold" action="" method="post"  enctype="multipart/form-data">
                    <div class="form-group  ">
                        <div class="form-group row">
                            <label for="Code">الكود والأسم</label>
                            <input autofocus class="form-control "  type="text" id="Code" name="Code" pattern="[a,A,b,B,H,h,j,J,r,R]{1}[0-9]{1,3}[a-zA-Z]{1 ,5}[^0-9]{1,20}"  required>
                            <div id="CodeFeedback" class="invalid-feedback">اكتب الكود والاسم</div>
                            <div id="CodeFeedback" class="valid-feedback">جيد</div>
                        </div>
                        <div class="form-group row ">
                            <label for="Phone_Number">رقم الهاتف</label>
                            <input id="Phone_Number"class="form-control" type="tel" style=" direction: ltr;" name="Phone_Number" pattern="[+]{1}[0-9 ]{1,4}[0-9 0-9]{8,12}" required='required'>                   
                            <div id="Phone-NumberFeedback" class="invalid-feedback">(+966 59 906 2179)(+20 122 987 6543) مثال</div>
                            <div id="Phone-NumberFeedback" class="valid-feedback">جيد</div>
                        </div> 
                        <div class="form-group row ">
                            <label for="Country">البلد</label>
                            <input class="form-control" type="text" id="Country" name="Country"  value=""  required='true'>
                            <div id="CountryFeedback" class="invalid-feedback">فارغ</div>
                            <div id="CountryFeedback" class="valid-feedback">جيد</div>
                        </div>
                        <div class="form-group row ">
                            <label      for="sex">الجنس</label>
                            <select      id="sex" name="sex" class="form-control form-select form-select-lg mb-3" aria-label=".form-select-lg example"required='true'>
                                <option     selected disabled value="">-</option>
                                <option     value="male"   >ذكر</option>
                                <option     value="female"   >انثى</option>
                                <option     value="Brothers"   >اخوه</option>
                            </select>
                            <div id="sexFeedback" class="invalid-feedback">اختر</div>
                            <div id="sexFeedback" class="valid-feedback">جيد</div>
                        </div>

                        <div class="form-group row ">
                            <label for="N_Students">عدد الطلاب</label>
                            <input class="form-control"  type="text" id="N_Students" name="N_Students"  pattern="([0-9]{1})"  required='true'>
                            <div id="N_StudentsFeedback" class="invalid-feedback">عدد الطلاب غير صحيح</div>
                            <div id="N_StudentsFeedback" class="valid-feedback">جيد</div>
                        </div> 
                        <div class="form-group row ">
                            <label      for="Days">عدد الأيام</label>
                            <input class="form-control"  type="text" id="Days" name="Days"  pattern="([0-9]{1,3})"  required='true'>
                            <div id="DaysFeedback" class="invalid-feedback">غير صحيح</div>
                            <div id="DaysFeedback" class="valid-feedback">جيد</div>
                        </div> 
                        <div class="form-group row ">
                            <label for="E_Cost">الأشتراك بالجنية</label>
                            <input class="form-control"  type="text" id="E_Cost" name="E_Cost"  pattern="([0-9]{1,4})"  required='true'>
                            <div id="E_CostFeedback" class="invalid-feedback">اختر</div>
                            <div id="E_CostFeedback" class="valid-feedback">جيد</div>
                        </div> 
                        <div class="form-group row ">
                            <label for="S_Cost">الأشتراك بالريال</label>
                            <input class="form-control"  type="text" id="S_Cost" name="S_Cost"  pattern="([0-9]{1,4})"  required='true'>
                            <div id="S_CostFeedback" class="invalid-feedback">اختر</div>
                            <div id="S_CostFeedback" class="valid-feedback">جيد</div>
                        </div> 
                        <div class="form-group row ">
                            <label      for="Who">المسؤول</label>
                            <select     id name="Who"  class="form-control form-select form-select-lg mb-3" aria-label=".form-select-lg example"required='true'>
                                <option     selected disabled value="">-</option>
                                <option     value="Belal">بلال</option>
                                <option     value="Hamza">حمزة</option>
                                <option     value="AbdelRahman">عبدالرحمن</option>
                                <option     value="Ramadan">رمضان</option>
                                <option     value="Bedo">بيدو</option>
                            </select>
                            <div id="WhoFeedback" class="invalid-feedback">اختر</div>
                            <div id="WhoFeedback" class="valid-feedback">جيد</div>
                        </div>
                        <div class="form-group row">
                            <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" type="submit">أضافة الكود</button>
                        </div>
                    </div>
                </form>
              </div>
            </div>
          </div>
        <?php }
        ?>
      </div>
    </section>
  </div>
</main><!-- End #main -->
<?php include "assets/tem/footer.php" ?>
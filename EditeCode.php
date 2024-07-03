<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['Suber_Admin']) AND !isset($_SESSION['manager'])) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ; 
require "functions/CS-functions.php";
require_once "NotificationSend.php" ;

        if (isset($_GET['do'])) {$do = $_GET['do'];} else {$do = '0';} ?>
<main id="main" class="main">
  <div class="container">
    <div class="pagetitle">
      <h1>Code Management Page</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">Home</a></li>
          <li class="breadcrumb-item active"> Management</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    
    <section class="section">
      <div class="row">
        <div class="col-lg-6 m-auto">
    
          <div class="card">
          <div class='card-body'> <?php            
              if ($do == '0' ) {header('location: 404');exit;}  
              elseif ($do == 'Edite' ) {
                  # start ... page========================================================================================================================
                  ?>
                      <h5 class="card-title text-center">  تعديل بيانات طالب   </h5> 
                          <?php if (isset($_GET["code"])){ 
                              $student = filter_var($_GET["code"], FILTER_UNSAFE_RAW );
                              $Edite_s_stmt = $con->prepare("SELECT * FROM students WHERE Code LIKE ?  ");
                              $Edite_s_stmt->execute(array("%$student %"));
                              $Edite=$Edite_s_stmt->fetchAll();
                              $Edite_s_count = $Edite_s_stmt->rowCount();
                              if ($Edite_s_count > 0) {
                                  foreach ($Edite as $s ) { if ($s['status'] == "Active") {
                                      ?>
                                      <div class="alert alert-success text-center" role="alert">
                                      <span style="display: block;"> <?php echo $s['Code'] ?> </span>
                                      </div>

                                      <a class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 border-0" href="EditeCode?do=Update&ID=<?php echo $s['ID'] ?>">تعديل بيانات الطالب </a>
                                      <a class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 border-0" onclick="return confirm('هل انت متأكد من الايقاف ؟');" href="EditeCode?do=Cancel&ID=<?php echo $s['ID'] ?>">  ايقاف الطالب </a>
                                      <a class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 border-0" onclick="return confirm('هل انت متأكد من الحذف ؟');" href="EditeCode?do=Delete&ID=<?php echo $s['ID'] ?>">حذف بيانات الطالب </a>
                                      <?php
                                  }else {
                                      ?>
                                      <div class="alert alert-success text-center" role="alert">
                                      <span style="display: block;"> <?php echo $s['Code'] ?> </span>
                                      </div>
                                      <a class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 border-0" href="EditeCode?do=Update&ID=<?php echo $s['ID'] ?>">تعديل بيانات الطالب </a>
                                      <a class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 border-0" onclick="return confirm('هل انت متأكد من التفعيل ؟');" href="EditeCode?do=Active&ID=<?php echo $s['ID'] ?>">  تفعيل الطالب </a>
                                      <a class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 border-0"  onclick="return confirm('هل انت متأكد من الحذف ؟');" href="EditeCode?do=Delete&ID=<?php echo $s['ID'] ?>">حذف بيانات الطالب </a>
                                      <?php
                                  }  } 
                                  ?>
                                  <?php
                              }else {
                                $_SESSION['message'] = 'false';
                              header('location: Management#s');exit;
                              }
                          }else {header('location: index');exit;} ?>
                  <?php
                  # end  ... page =========================================================================================================================
                  # end  ... page =========================================================================================================================
                  # end  ... page =========================================================================================================================
                  # end  ... page =========================================================================================================================
                  
              }  
              elseif ($do == 'Update' ) {
                  # start ... page========================================================================================================================
                  ?>
                  <h5 class="card-title text-center">  تعديل بيانات طالب   </h5> 
                      <?php if ($_SERVER ['REQUEST_METHOD'] =='POST') {
                          $ID = filter_var($_POST["ID"], FILTER_UNSAFE_RAW );
                          $Code = filter_var($_POST["Code"], FILTER_UNSAFE_RAW );
                          $OldCode = filter_var($_POST["OldCode"], FILTER_UNSAFE_RAW );
                          $phone = str_replace("+","",str_replace(" ","",filter_var($_POST["Phone_Number"], FILTER_UNSAFE_RAW )));
                          $Country = filter_var($_POST["Country"], FILTER_UNSAFE_RAW );
                          $sex = filter_var($_POST["sex"], FILTER_UNSAFE_RAW );
                          $N_Students = filter_var($_POST["N_Students"], FILTER_UNSAFE_RAW );
                          $Days = filter_var($_POST["Days"], FILTER_UNSAFE_RAW );
                          $E_Cost = filter_var($_POST["E_Cost"], FILTER_UNSAFE_RAW );
                          $S_Cost = filter_var($_POST["S_Cost"], FILTER_UNSAFE_RAW );
                          $Who = filter_var($_POST["Who"], FILTER_UNSAFE_RAW );
                          $OldWho = filter_var($_POST["OldWho"], FILTER_UNSAFE_RAW );

                          $stmt = $con->prepare("UPDATE  students SET `Code` =? , `phone` =? ,`Country` =?  ,`sex` =? ,`N_Students` =? ,`Days` =? ,`E_Cost` =? ,`S_Cost` =? ,`Who` =? WHERE ID =? ");
                          $stmt->execute(array( $Code , $phone , $Country, $sex, $N_Students, $Days , $E_Cost  , $S_Cost , $Who , $ID  ));
                          $xcount = $stmt->rowCount();

                          if ($Code !== $OldCode) {
                            $class = $con->prepare("UPDATE class SET Student=?  WHERE Student =? ");
                            $class->execute(array($Code,$OldCode));
                            $Studentclass = $class->rowCount();
  
                            $history = $con->prepare("UPDATE  history SET  S_code=? WHERE S_code =? ");
                            $history->execute(array(  $Code , $OldCode ));
                            $history_count = $history->rowCount();
  
                            $UpdateJournal = $con->prepare("UPDATE  Journal SET Code = ? WHERE Code = ?");
                            $UpdateJournal->execute(array($Code,$OldCode));
                            $UpdateJournal_count = $UpdateJournal->rowCount();
  
                            
                            $Activity = $con->prepare("UPDATE Activity SET `ForWho`=? WHERE ForWho = ?");
                            $Activity->execute(array($Code,$OldCode));
                            $Activity_count = $Activity->rowCount();
                          }
                          SetActual($Code);
                          if ($xcount > 0  ) {
                            $_SESSION['Emessage'] = ' تم تعديل الكود بنجاح'; ?>
                            <script>history.go(-2)</script>
                            <?php ;exit; 
                          } else {
                            $_SESSION['Emessage'] = ' لم يتم تعديل الكود حاول مرة اخرى '; ?>
                            <script>history.go(-2)</script>
                            <?php ;exit; 
                          }
                      }else {
                          if (isset($_GET['ID'])) {
                              $id = filter_var($_GET["ID"], FILTER_UNSAFE_RAW );
                              $update_stmt = $con->prepare("SELECT * FROM students WHERE ID = ?  ");
                              $update_stmt->execute(array($id));
                              $update=$update_stmt->fetch();
                              $update_count = $update_stmt->rowCount();
                              if ($update_count > 0 ) {
                                  ?>
                                          <form name="myForm" novalidate   class="forms-sample needs-validation fw-bold rtl" action="" method="post" id="sheetdb-form" enctype="multipart/form-data">
                                              <div class="form-group  ">

                                                  <div style="display: none;" class="form-group row">
                                                      <label for="ID">الكود</label>
                                                      <input class="form-control " value="<?php echo $update['ID']?>"  type="hidden" id="ID" name="ID">
                                                  </div>

                                                  <div class="form-group row">
                                                  <label for="Code">الكود والأسم</label>
                                                  <input autofocus class="form-control " value="<?php echo $update['Code']?>" type="text" id="Code" name="Code" pattern="[a,A,b,B,H,h,j,J,r,R]{1}[0-9]{1,3} [a-zA-Z]{1 ,5}[^0-9]{1,20}"  required>
                                                  <input  class="form-control " value="<?php echo $update['Code']?>" type="hidden" id="OldCode" name="OldCode"   required>
                                                  <input  class="form-control " value="<?php echo $update['Who']?>" type="hidden" id="OldWho" name="OldWho"   required>
                                                  <div id="CodeFeedback" class="invalid-feedback">اكتب الكود والاسم</div>
                                                  <div id="CodeFeedback" class="valid-feedback">جيد</div>
                                              </div>
                                              <div class="form-group row ">
                                                  <label for="Phone_Number">رقم الهاتف</label>
                                                  <input id="Phone_Number"class="form-control" type="tel" value="<?php echo "+".$update['phone']?>" style=" direction: ltr;" name="Phone_Number" pattern="[+]{1}[0-9 ]{1,4}[0-9 0-9]{8,12}" required='required'>                   
                                                  <div id="Phone-NumberFeedback" class="invalid-feedback">(+966 59 906 2179)(+20 122 987 6543) مثال</div>
                                                  <div id="Phone-NumberFeedback" class="valid-feedback">جيد</div>
                                              </div> 
                                              <div class="form-group row ">
                                                  <label for="Country">البلد</label>
                                                  <input class="form-control" type="text" id="Country" value="<?php echo $update['Country']?>" name="Country"  value=""  required='true'>
                                                  <div id="CountryFeedback" class="invalid-feedback">فارغ</div>
                                                  <div id="CountryFeedback" class="valid-feedback">جيد</div>
                                              </div>
                                              <div class="form-group row ">
                                                  <label      for="sex">الجنس</label>
                                                  <select      id="sex" name="sex" class="form-control form-select form-select-lg mb-3" aria-label=".form-select-lg example"required='true'>
                                                      <option  <?php if ($update['sex'] == 'male'  ) { echo "selected" ; } ?>   value="male"   >ذكر</option>
                                                      <option  <?php if ($update['sex'] == 'female'  ) { echo "selected" ; } ?>   value="female"   >انثى</option>
                                                      <option  <?php if ($update['sex'] == 'Brothers'  ) { echo "selected" ; } ?>   value="Brothers"   >اخوه</option>
                                                  </select>
                                                  <div id="sexFeedback" class="invalid-feedback">اختر</div>
                                                  <div id="sexFeedback" class="valid-feedback">جيد</div>
                                              </div>
                  
                                              <div class="form-group row ">
                                                  <label for="N_Students">عدد الطلاب</label>
                                                  <input class="form-control" value="<?php echo $update['N_Students']?>" type="text" id="N_Students" name="N_Students"  pattern="([0-9]{1})"  required='true'>
                                                  <div id="N_StudentsFeedback" class="invalid-feedback">عدد الطلاب غير صحيح</div>
                                                  <div id="N_StudentsFeedback" class="valid-feedback">جيد</div>
                                              </div> 
                                              <div class="form-group row ">
                                                  <label      for="Days">عدد الأيام</label>
                                                  <input class="form-control" value="<?php echo $update['Days']?>" type="text" id="Days" name="Days"  pattern="([0-9]{1,3})"  required='true'>
                                                  <div id="DaysFeedback" class="invalid-feedback">غير صحيح</div>
                                                  <div id="DaysFeedback" class="valid-feedback">جيد</div>
                                              </div> 
                                              <div class="form-group row ">
                                                  <label for="E_Cost">الأشتراك بالجنية</label>
                                                  <input class="form-control" value="<?php echo $update['E_Cost']?>" type="text" id="E_Cost" name="E_Cost"  pattern="([0-9]{1,4})"  required='true'>
                                                  <div id="E_CostFeedback" class="invalid-feedback">اختر</div>
                                                  <div id="E_CostFeedback" class="valid-feedback">جيد</div>
                                              </div> 
                                              <div class="form-group row ">
                                                  <label for="S_Cost">الأشتراك بالريال</label>
                                                  <input class="form-control" value="<?php echo $update['S_Cost']?>" type="text" id="S_Cost" name="S_Cost"  pattern="([0-9]{1,4})"  required='true'>
                                                  <div id="S_CostFeedback" class="invalid-feedback">اختر</div>
                                                  <div id="S_CostFeedback" class="valid-feedback">جيد</div>
                                              </div> 
                                              <div class="form-group row ">
                                                  <label      for="Who">المسؤول</label>
                                                  <select     id name="Who"  class="form-control form-select form-select-lg mb-3" aria-label=".form-select-lg example"required='true'>
                                                      <option  <?php if ($update['Who'] == 'Belal'  ) { echo "selected" ; } ?>   value="Belal">بلال</option>
                                                      <option  <?php if ($update['Who'] == 'Hamza'  ) { echo "selected" ; } ?>   value="Hamza">حمزة</option>
                                                      <option  <?php if ($update['Who'] == 'AbdelRahman'  ) { echo "selected" ; } ?>   value="AbdelRahman">عبدالرحمن</option>
                                                      <option  <?php if ($update['Who'] == 'Ramadan'  ) { echo "selected" ; } ?>   value="Ramadan">رمضان</option>
                                                      <option  <?php if ($update['Who'] == 'Bedo'  ) { echo "selected" ; } ?>   value="Bedo">بيدو</option>
                                                  </select>
                                                  <div id="WhoFeedback" class="invalid-feedback">اختر</div>
                                                  <div id="WhoFeedback" class="valid-feedback">جيد</div>
                                              </div>
  
                                                  <div class="form-group row  ">
                                                      <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 border-0" type="submit">تعديل الكود</button>
                                                  </div>
                                              </div>
                                          </form>
                                          <div class="form-group row ">
                                              <button onclick="history.go(-2)" class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 border-0"> عودة</button>
                                          </div>
                                  <?php
                              }else {
                                $_SESSION['Emessage'] = ' تأكد من الكود';
                                header('Location: ' . $_SERVER['HTTP_REFERER']);exit;
                              }
                          }else {
                            $_SESSION['Emessage'] = ' تأكد من الكود';
                            header('Location: ' . $_SERVER['HTTP_REFERER']);exit;
                          }
                      }
                  # end  ... page =========================================================================================================================
                  # end  ... page =========================================================================================================================
                  # end  ... page =========================================================================================================================
                  # end  ... page =========================================================================================================================
                  
              }  
              elseif ($do == 'Cancel' ) {
                  # start ... page========================================================================================================================
                  ?>
                  <h5 class="card-title text-center">   ايقاف طالب   </h5> 
                      <?php
                  if (isset($_GET['ID'])) {
                      $id = filter_var($_GET["ID"], FILTER_UNSAFE_RAW );
                      $update_stmt = $con->prepare("SELECT * FROM students WHERE ID = ?  ");
                      $update_stmt->execute(array($id));
                      $update=$update_stmt->fetch();

                      $stmt_2 = $con->prepare("SELECT * FROM class WHERE Student=? LIMIT 1 ");
                      $stmt_2->execute(array($update['Code']));
                      $T_2= $stmt_2->fetch() ;

                      $update_count = $update_stmt->rowCount();
                      if ($update_count > 0 ) {
                          $ID = filter_var($_GET["ID"], FILTER_UNSAFE_RAW );
                          $stmt = $con->prepare("UPDATE  students SET `status` ='Cancel'    WHERE ID =? ");
                          $stmt->execute(array($ID));
                          $xcount = $stmt->rowCount();
                         
                          SetActual($update['Code']);
                          if ($xcount > 0  ) {
                             $_SESSION['Emessage'] = 'تم ايقاف الكود بنجاح ';
                            header('Location: ' . $_SERVER['HTTP_REFERER']);exit;
                            } else { 
                                $_SESSION['Emessage'] = 'تأكد من الكود';
                                header('Location: ' . $_SERVER['HTTP_REFERER']);exit;

                          } }else {
                            $_SESSION['Emessage'] = 'تأكد من الكود';
                            header('Location: ' . $_SERVER['HTTP_REFERER']);exit;
                          }
                      }else {
                        $_SESSION['Emessage'] = 'تأكد من الكود';
                        header('Location: ' . $_SERVER['HTTP_REFERER']);exit;
                      } 

                  # end  ... page =========================================================================================================================
                  # end  ... page =========================================================================================================================
                  # end  ... page =========================================================================================================================
                  # end  ... page =========================================================================================================================
                  
              }  
              elseif ($do == 'Active' ) {
                  # start ... page========================================================================================================================
                  ?>
                  <h5 class="card-title text-center">  تفعيل  كود   </h5> 
                      <?php
                  if (isset($_GET['ID'])) {
                      $id = filter_var($_GET["ID"], FILTER_UNSAFE_RAW );
                      $update_stmt = $con->prepare("SELECT * FROM students WHERE ID = ?  ");
                      $update_stmt->execute(array($id));
                      $update=$update_stmt->fetch();
                      $update_count = $update_stmt->rowCount();
                      if ($update_count > 0 ) {
                          $ID = filter_var($_GET["ID"], FILTER_UNSAFE_RAW );
                          $stmt = $con->prepare("UPDATE  students SET `status` ='Active'    WHERE ID =? ");
                          $stmt->execute(array($ID));
                          $xcount = $stmt->rowCount();
                         
                          SetActual($update['Code']);
                          if ($xcount > 0  ) {
                            $_SESSION['Emessage'] = 'تم تفعيل الكود بنجاح ';
                            header('Location: ' . $_SERVER['HTTP_REFERER']);exit;
                         } else {
                            $_SESSION['Emessage'] = 'تأكد من الكود';
                            header('Location: ' . $_SERVER['HTTP_REFERER']);exit;
                          } }else {
                            $_SESSION['Emessage'] = 'تأكد من الكود';
                            header('Location: ' . $_SERVER['HTTP_REFERER']);exit;
                          }
                      }else {
                        $_SESSION['Emessage'] = 'تأكد من الكود';
                        header('Location: ' . $_SERVER['HTTP_REFERER']);exit;
                      }

                  # end  ... page =========================================================================================================================
                  # end  ... page =========================================================================================================================
                  # end  ... page =========================================================================================================================
                  # end  ... page =========================================================================================================================
                  
              }  
              elseif ($do == 'Delete' ) {
                  # start ... page========================================================================================================================
                  ?>
                  <h5 class="card-title text-center">  حذف  كود   </h5> 
                      <?php
                  if (isset($_GET['ID'])) {
                      $id = filter_var($_GET["ID"], FILTER_UNSAFE_RAW );
                      $update_stmt = $con->prepare("SELECT * FROM students WHERE ID = ?  ");
                      $update_stmt->execute(array($id));
                      $update=$update_stmt->fetch();
                      $update_count = $update_stmt->rowCount();
                      if ($update_count > 0 ) {
                          $ID = filter_var($_GET["ID"], FILTER_UNSAFE_RAW );
                          $stmt = $con->prepare("DELETE FROM  students WHERE ID =? ");
                          $stmt->execute(array($ID));
                          $xcount = $stmt->rowCount();
                          if ($xcount > 0  ) {
                            $_SESSION['Emessage'] = 'تم حذف الكود بنجاح ';
                            header('Location: ' . $_SERVER['HTTP_REFERER']);exit;
                          } else {
                            $_SESSION['Emessage'] = 'تأكد من الكود';
                            header('Location: ' . $_SERVER['HTTP_REFERER']);exit;
                          } }else {
                            $_SESSION['Emessage'] = 'تأكد من الكود';
                            header('Location: ' . $_SERVER['HTTP_REFERER']);exit;
                          }
                      }else {
                        $_SESSION['Emessage'] = 'تأكد من الكود';
                        header('Location: ' . $_SERVER['HTTP_REFERER']);exit;
                      } ?> 
                      <?php
                  # end  ... page =========================================================================================================================
                  # end  ... page =========================================================================================================================
                  # end  ... page =========================================================================================================================
                  # end  ... page =========================================================================================================================
                  
              }  ?>
          </div>
          </div>
    
        </div>

        <div class="toast-container position-fixed bottom-0 end-0 p-3" style=" z-index: 99999;">
          <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false">
            <div class="toast-header">
              <strong class="me-auto">أكاديمية الرؤى</strong>
              <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body rtl fs-6 fw-bold text-danger">
              <?php if (!empty($_SESSION['Emessage'])) {echo $_SESSION['Emessage'];}?>
            </div>
          </div>
        </div>

      </div>
    </section>
  </div>


</main><!-- End #main -->
<?php include "assets/tem/footer.php" ?>
<?php 
if (!empty($_SESSION['Emessage'])) { ?>
  <script>
    $(document).ready(function() {
        $(".toast").toast('show');
    });
</script>
<?php unset($_SESSION['Emessage']); } ?>
<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['Admin']) AND !isset($_SESSION['Suber_Admin']) AND !isset($_SESSION['manager'])) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ; 
require "functions/CS-functions.php";
require "functions/globalFunctions.php";
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
          <div class='card-body rtl'> <?php            
              if ($do == '0' ) {header('location: 404');exit;}  
              elseif ($do == 'Edite' ) {
                  # start ... page========================================================================================================================
                  ?>
                      
                          <?php if (isset($_GET["code"])){ 
                            $student = filter_var($_GET["code"], FILTER_UNSAFE_RAW );
                            $edite_s_stmt = $con->prepare("SELECT * FROM class WHERE Student LIKE ?  ");
                            $edite_s_stmt->execute(array("%$student %"));
                            $edite=$edite_s_stmt->fetchAll();
                            $edite_s_count = $edite_s_stmt->rowCount();
                            if ($edite_s_count > 0) {
                            foreach ($edite as $s ) { if ($s['type'] == 1) {
                            ?>
                            <h5 class="card-title text-center"><?php echo $s['StudentName'] ?></h5> 
                            <span> <?php echo $s['one_time'] ?> </span>
                            <?php if (isset($_SESSION['Suber_Admin'])|| isset($_SESSION['manager'])){ ?>
                            <a class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 border-0" onclick="return confirm('هل انت متأكد من الحذف ؟');" href="EditeClasse?do=Delete&ID=<?php echo $s['ID'] ?>">حذف بيانات الحصة </a>
                            <?php } ?>
                            <hr style="opacity: 1;"><?php
                            }else {
                            ?>
                            <h5 class="card-title text-center"><?php echo $s['StudentName'] ?></h5> 
                            <?php if ($s['status']== 'Pause') {echo '<p class="w-100 text-center text-danger fw-bolder">  هذا الطالب غير مفعل </p>';} ?>
                            <p class="card-details mb-0" > <?php if (!empty($s['Saturday'])) {echo $s['Saturday'].'<br>';} ?></p>
                            <p class="card-details mb-0" > <?php if (!empty($s['Sunday'])) {echo $s['Sunday'].'<br>';} ?></p>
                            <p class="card-details mb-0" > <?php if (!empty($s['Monday'])) {echo $s['Monday'].'<br>';} ?></p>
                            <p class="card-details mb-0" > <?php if (!empty($s['Tuesday'])) {echo $s['Tuesday'].'<br>';} ?></p>
                            <p class="card-details mb-0" > <?php if (!empty($s['Wednesday'])) {echo $s['Wednesday'].'<br>';} ?></p>
                            <p class="card-details mb-0" > <?php if (!empty($s['Thursday'])) {echo $s['Thursday'].'<br>';} ?></p>
                            <p class="card-details mb-0" > <?php if (!empty($s['Friday'])) {echo $s['Friday'].'<br>';} ?></p>
                            <a class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 border-0"  class="button_primary" href="EditeClasse?do=Update&ID=<?php echo $s['ID'] ?>&type=<?php echo $s['type'] ?>">تعديل  الجدول </a>
                            <?php if ($s['status'] == 'Active') { ?> <a class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 border-0" href="EditeClasse?do=Cancel&ID=<?php echo $s['ID'] ?>&type=<?php echo $s['type'] ?>"> ايقاف الطالب </a> <?php 
                            }else { ?>
                            <a class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 border-0" href="EditeClasse?do=Active&ID=<?php echo $s['ID'] ?>&type=<?php echo $s['type'] ?>">تفعيل الطالب</a>
                            <?php } ?>
                            <?php if (isset($_SESSION['Suber_Admin']) || isset($_SESSION['manager'])){ ?>
                            <a class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 border-0" onclick="return confirm('هل انت متأكد من الحذف ؟');" href="EditeClasse?do=Delete&ID=<?php echo $s['ID'] ?>">حذف الجدول  </a>
                            <?php } ?>
                            <hr style="opacity: 1;">

                            <?php
                            }  } 
                            ?>
                            <button onclick="history.back()" class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 border-0"> عودة</button>
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
                  <h5 class="card-title text-center">  تعديل تفاصيل جدول   </h5> 
                  <?php if ($_SERVER ['REQUEST_METHOD'] =='POST') {
                    $Teacher = filter_var($_POST["Teacher"], FILTER_UNSAFE_RAW );
                    $category = filter_var($_POST["category"], FILTER_UNSAFE_RAW );
                    $Student = filter_var($_POST["Student"], FILTER_UNSAFE_RAW );
                    $Duration = filter_var($_POST["Duration"], FILTER_UNSAFE_RAW );
                    $id = filter_var($_POST["id"], FILTER_UNSAFE_RAW );
                    $Saturday = "";
                    $Sunday = "";
                    $Monday = "";
                    $Tuesday = "";
                    $Wednesday = "";
                    $Thursday = "";
                    $Friday = "";
                    if (isset($_POST["Days"])){$Days = filter_var($_POST["Days"], FILTER_UNSAFE_RAW );} else {$Days = 0 ;}
                    if (isset($_POST["Saturday_time"]  ) && !empty( $_POST["Saturday_time"]) ){  $Saturday_time = TimeToInsert($_POST["Saturday_time"]);} else {$Saturday_time = NULL ;}
                    if (isset($_POST["Sunday_time"])&& !empty( $_POST["Sunday_time"])){ $Sunday_time = TimeToInsert($_POST["Sunday_time"]);} else {$Sunday_time = NULL ;}
                    if (isset($_POST["Monday_time"])&& !empty( $_POST["Monday_time"])){ $Monday_time = TimeToInsert($_POST["Monday_time"]);} else {$Monday_time = NULL ;}
                    if (isset($_POST["Tuesday_time"])&& !empty( $_POST["Tuesday_time"])){ $Tuesday_time = TimeToInsert($_POST["Tuesday_time"]);} else {$Tuesday_time = NULL ;}
                    if (isset($_POST["Wednesday_time"])&& !empty( $_POST["Wednesday_time"])){ $Wednesday_time = TimeToInsert($_POST["Wednesday_time"]);} else {$Wednesday_time = NULL ;}
                    if (isset($_POST["Thursday_time"])&& !empty( $_POST["Thursday_time"])){ $Thursday_time = TimeToInsert($_POST["Thursday_time"]);} else {$Thursday_time = NULL ;}
                    if (isset($_POST["Friday_time"])&& !empty( $_POST["Friday_time"])){ $Friday_time = TimeToInsert($_POST["Friday_time"]);} else {$Friday_time = NULL ;}
                    if (isset($_POST["time"])&& !empty( $_POST["time"])){ $time = TimeToInsert($_POST["time"]);} else {$time = NULL ;}
                    if (isset($_POST["Days"])){$Days = filter_var($_POST["Days"], FILTER_UNSAFE_RAW );} else {$Days = 0 ;}
                    if (isset($_POST["for_one_time"])){$type = 1 ;} else {$type = 2 ;}
                    if (isset($_POST["Saturday"])) { $Saturday = filter_var($_POST["Saturday"], FILTER_UNSAFE_RAW );}
                    if (isset($_POST["Sunday"])) { $Sunday = filter_var($_POST["Sunday"], FILTER_UNSAFE_RAW );}
                    if (isset($_POST["Monday"])) { $Monday = filter_var($_POST["Monday"], FILTER_UNSAFE_RAW );}
                    if (isset($_POST["Tuesday"])) { $Tuesday = filter_var($_POST["Tuesday"], FILTER_UNSAFE_RAW );}
                    if (isset($_POST["Wednesday"])) { $Wednesday = filter_var($_POST["Wednesday"], FILTER_UNSAFE_RAW );}
                    if (isset($_POST["Thursday"])) { $Thursday = filter_var($_POST["Thursday"], FILTER_UNSAFE_RAW );}
                    if (isset($_POST["Friday"])) { $Friday = filter_var($_POST["Friday"], FILTER_UNSAFE_RAW );}
                    if (isset($_POST["for_one_time"])) { $StudentName = filter_var($_POST["StudentName"] .' - '.$_POST["class_type"], FILTER_UNSAFE_RAW );}else {$StudentName = filter_var($_POST["StudentName"], FILTER_UNSAFE_RAW ); }
                    if (isset($_POST["for_one_time"])) { $one_time = $_POST["for_one_time"];}else {$one_time = "";}
                    $stmt = $con->prepare("UPDATE  class SET Teacher=?   , StudentName=? , `one_time`=?, `Saturday`=?, `Sunday`=?, `Monday`=?, `Tuesday`=?, `Wednesday`=?, `Thursday`=?, `Friday`=?,`Saturday_time`=?, `Sunday_time`=?, `Monday_time`=?, `Tuesday_time`=?, `Wednesday_time`=?, `Thursday_time`=?, `Friday_time`=?,`type`=? , Duration=? , `Days`=?,`time`=?, category = ? WHERE ID =? ");
                    $stmt->execute(array(  $Teacher   , $StudentName  , $one_time, $Saturday, $Sunday, $Monday, $Tuesday, $Wednesday, $Thursday, $Friday , $Saturday_time, $Sunday_time, $Monday_time, $Tuesday_time, $Wednesday_time, $Thursday_time, $Friday_time , $type , $Duration ,$Days , $time , $category ,$id  ));
                    $xcount = $stmt->rowCount();
                    SetActual($Student);

                    if ($xcount > 0  ) {
                        $_SESSION['Emessage'] = ' تم تعديل الجدول بنجاح'; ?>
                        <script>history.go(-2)</script>
                        <?php ;exit; 
                    } else { 
                            $_SESSION['Emessage'] = ' لم يتم تعديل الجدول حاول مرة اخرى '; ?>
                            <script>history.go(-2)</script>
                            <?php ;exit; 
                    }
                    
                }else {
                if (isset($_GET['ID'])) {
                    $id = filter_var($_GET["ID"], FILTER_UNSAFE_RAW );
                    $update_stmt = $con->prepare("SELECT * FROM class WHERE ID = ?  ");
                    $update_stmt->execute(array($id));
                    $update=$update_stmt->fetch();
                    $update_count = $update_stmt->rowCount();
                    $Add_ct_stmt = $con->prepare("SELECT Code FROM teachers");
                    $Add_ct_stmt->execute(array());
                    $Tclass=$Add_ct_stmt->fetchAll();
                    $Add_ct_count = $Add_ct_stmt->rowCount();
                    if ($update_count > 0 ) {

                            $d_stmt = $con->prepare("SELECT SUM(Days) FROM class  WHERE Student = ? AND `type` = ? AND ID !=? ");
                            $d_stmt->execute(array($update['Student'],2,$_GET['ID']));
                            $N_class=$d_stmt->fetch();

                            $c_stmt = $con->prepare("SELECT * FROM students WHERE Code = ?");
                            $c_stmt->execute(array($update['Student']));
                            $N_Students=$c_stmt->fetch();
                            $c_count = $c_stmt->rowCount();
                            $max = $N_Students['Days']-$N_class['SUM(Days)'];
                            ?>
                            <form name="myForm" novalidate   class="forms-sample needs-validation" action="" method="post"  enctype="multipart/form-data">
                                <div class="form-group  ">

                                    <div class="form-group row ">
                                        <input  value="<?php echo $update['ID'] ?>"  type="hidden"  name="id"    required='true'>
                                        <label  for="Student">كود الطالب</label>
                                        <select id="Student" name="Student" class="form-control form-select form-select-lg mb-3" aria-label=".form-select-lg example"required='true'>
                                            <option selected value="<?php echo $update['Student'] ?>"><?php echo $update['Student'] ?></option>
                                        </select> 
                                        <div id="sexFeedback" class="invalid-feedback">اختر الكود بشكل صحيح</div>
                                        <div id="sexFeedback" class="valid-feedback">جيد</div>
                                    </div>

                                    <div class="form-group row ">
                                        <label      for="Teacher">المعلم</label>
                                        <select      id="Teacher" name="Teacher" class="form-control form-select form-select-lg mb-3" aria-label=".form-select-lg example"required='true'>
                                            <?php
                                            foreach ($Tclass as $key ) { ?>
                                            <option <?php if ($key['Code'] == $update['Teacher'] ) { echo "selected" ; } ?> value="<?php echo $key['Code'] ?>"><?php echo $key['Code'] ?></option><?php } ?>
                                        </select>
                                        <div id="sexFeedback" class="invalid-feedback">اختر</div>
                                        <div id="sexFeedback" class="valid-feedback">جيد</div>
                                    </div>


                                    <div class="form-group row ">
                                        <label for="Duration">مدة الحصة</label>
                                        <select     id="Duration"  name="Duration" class="form-control form-select form-select-lg mb-3" aria-label=".form-select-lg example" required='true'>
                                            <option     selected disabled value="">-</option>
                                            <option     <?php if ($update['Duration'] == '15'  ) { echo "selected" ; } ?>     value="15"   >15</option>
                                            <option     <?php if ($update['Duration'] == '20'  ) { echo "selected" ; } ?>     value="20"   >20</option>
                                            <option     <?php if ($update['Duration'] == '30'  ) { echo "selected" ; } ?>     value="30"   >30</option>
                                            <option     <?php if ($update['Duration'] == '45'  ) { echo "selected" ; } ?>     value="45"   >45</option>
                                            <option     <?php if ($update['Duration'] == '60'  ) { echo "selected" ; } ?>     value="60"   >60</option>
                                            <option     <?php if ($update['Duration'] == '75'  ) { echo "selected" ; } ?>     value="75"   >75</option>
                                            <option     <?php if ($update['Duration'] == '90'  ) { echo "selected" ; } ?>     value="90"   >90</option>
                                            <option     <?php if ($update['Duration'] == '105' ) { echo "selected" ; } ?>     value="105"  >105</option>
                                            <option     <?php if ($update['Duration'] == '120' ) { echo "selected" ; } ?>     value="120"  >120</option>
                                            <option     <?php if ($update['Duration'] == '135' ) { echo "selected" ; } ?>     value="135"  >135</option>
                                            <option     <?php if ($update['Duration'] == '150' ) { echo "selected" ; } ?>     value="150"  >150</option>
                                            <option     <?php if ($update['Duration'] == '165' ) { echo "selected" ; } ?>     value="165"  >165</option>
                                            <option     <?php if ($update['Duration'] == '180' ) { echo "selected" ; } ?>     value="180"  >180</option>
                                        </select>
                                        <div id="DurationFeedback" class="invalid-feedback">اختر</div>
                                        <div id="DurationFeedback" class="valid-feedback">جيد</div>
                                    </div> 

                                    <div class="form-group row ">
                                        <label for="category">تصنيف الحصة</label>
                                        <select     id="category"  name="category" class="form-control form-select form-select-lg mb-3" aria-label=".form-select-lg example" required='true'>
                                            <option     selected disabled value="">-</option>
                                            <option     <?php if ($update['category'] == 'quran'  ) { echo "selected" ; } ?>     value="quran"   >قرآن</option>
                                            <option     <?php if ($update['category'] == 'quran_en'  ) { echo "selected" ; } ?>     value="quran_en"   >قرآن للآعاجم</option>
                                            <option     <?php if ($update['category'] == 'nour'  ) { echo "selected" ; } ?>     value="nour"   >نور البيان</option>
                                            <option     <?php if ($update['category'] == 'arabic'  ) { echo "selected" ; } ?>     value="arabic"   >اللغة العربية</option>
                                            <option     <?php if ($update['category'] == 'religion'  ) { echo "selected" ; } ?>     value="religion"   >مواد شرعية</option>

                                        </select>
                                        <div id="categoryFeedback" class="invalid-feedback">اختر</div>
                                        <div id="categoryFeedback" class="valid-feedback">جيد</div>
                                    </div> 

                                    <div class="form-group row  ">
                                        <label      for="Days">عدد الأيام</label>
                                        <input class="form-control"  value="<?php echo $update['Days'] ?>"  type="number" id="Days" name="Days" min="4" max="<?php echo $max ; ?>" step="4" required='true'>
                                        <div id="DaysFeedback" class="invalid-feedback">
                                        <p><?php echo $max ; ?> عدد الأيام المتاح فى الكود</p>
                                        <p> الارقام التى يمكن ادخالها 4,8,12,16,20,24,28 </p></div>
                                        <div id="DaysFeedback" class="valid-feedback">جيد</div>
                                    </div> 

                                    <div class="form-group row  ">
                                        <label for="StudentName">اسم الطالب</label>
                                        <input class="form-control" value="<?php echo $update['StudentName'] ?>"  type="text" id="StudentName" name="StudentName"  pattern="[a-z a-z,A-Z A-Z]{2,15}"  required='true'>
                                        <div id="T_codeFeedback" class="invalid-feedback">اسم الطالب فقط بدون مسافات بحد اقصى 15 حرف</div>
                                        <div id="T_codeFeedback" class="valid-feedback">جيد</div>
                                    </div> 

                                    <div class="form-group row ">
    
                                        <strong>Choose Days:</strong>

                                            <div class="form-group d-inline-flex justify-content-around mt-3">
                                                <label class="form-check-label d-inline-flex align-items-center w-25 h-100" for="Saturday"><strong class="w-100">Sa-day:</strong></label>
                                                <input class="form-check-input form-control w-25 h-100" <?php if ("Saturday" == $update['Saturday']) {echo 'checked' ;} ?> name="Saturday" type="checkbox" value="Saturday" id="Saturday">
                                                <input class="form-check-input form-control w-50 h-100" value="<?php echo TimeToDisplayIn24($update['Saturday_time']); ?>"  type="time" id="Saturday_time" name="Saturday_time" >
                                                <span class="btn btn-light" onclick="document.getElementById('Saturday_time').value = ''">clear </span>
                                                
                                            </div>

                                        <div class="form-group d-inline-flex justify-content-around mt-3">
                                            <label class="form-check-label d-inline-flex align-items-center w-25 h-100" for="Sunday"><strong class="w-100">Su-day:</strong></label>
                                            <input class="form-check-input form-control w-25 h-100" <?php if ("Sunday" == $update['Sunday']) {echo 'checked' ;} ?> name="Sunday" type="checkbox" value="Sunday" id="Sunday" >
                                            <input class="form-check-input form-control w-50 h-100" value="<?php echo TimeToDisplayIn24($update['Sunday_time']); ?>"  type="time" id="Sunday_time" name="Sunday_time"  >
                                            <span class="btn btn-light" onclick="document.getElementById('Sunday_time').value = ''">clear </span>

                                        </div>

                                        <div class="form-group d-inline-flex justify-content-around mt-3">
                                            <label class="form-check-label d-inline-flex align-items-center w-25 h-100" for="Monday"><strong class="w-100">Mo-day:</strong></label>
                                            <input class="form-check-input form-control w-25 h-100" <?php if ("Monday" == $update['Monday']) {echo 'checked' ;} ?> name="Monday" type="checkbox" value="Monday" id="Monday" >
                                            <input class="form-check-input form-control w-50 h-100" value="<?php echo TimeToDisplayIn24($update['Monday_time']); ?>" type="time" id="Monday_time" name="Monday_time"  >
                                            <span class="btn btn-light" onclick="document.getElementById('Monday_time').value = ''">clear </span>

                                        </div>

                                        <div class="form-group d-inline-flex justify-content-around mt-3 w-100">
                                            <label class="form-check-label d-inline-flex align-items-center w-25 h-100" for="Tuesday"><strong class="w-100">Tu-day:</strong></label>
                                            <input class="form-check-input form-control w-25 h-100" <?php if ("Tuesday" == $update['Tuesday']) {echo 'checked' ;} ?> name="Tuesday" type="checkbox" value="Tuesday" id="Tuesday" >
                                            <input class="form-check-input form-control w-50 h-100" value="<?php echo TimeToDisplayIn24($update['Tuesday_time']); ?>" type="time" id="Tuesday_time" name="Tuesday_time"  >
                                            <span class="btn btn-light" onclick="document.getElementById('Tuesday_time').value = ''">clear </span>

                                        </div>

                                        <div class="form-group d-inline-flex justify-content-around mt-3 w-100">
                                            <label class="form-check-label d-inline-flex align-items-center w-25 h-100" for="Wednesday"><strong class="w-100">We-day:</strong></label>
                                            <input class="form-check-input form-control w-25 h-100" <?php if ("Wednesday" == $update['Wednesday']) {echo 'checked' ;} ?> name="Wednesday" type="checkbox" value="Wednesday" id="Wednesday" >
                                            <input class="form-check-input form-control w-50 h-100" value="<?php echo TimeToDisplayIn24($update['Wednesday_time']); ?>" type="time" id="Wednesday_time" name="Wednesday_time"  >
                                            <span class="btn btn-light" onclick="document.getElementById('Wednesday_time').value = ''">clear </span>

                                        </div>

                                        <div class="form-group d-inline-flex justify-content-around mt-3">
                                            <label class="form-check-label d-inline-flex align-items-center w-25 h-100" for="Thursday"><strong class="w-100">Th-day:</strong></label>
                                            <input class="form-check-input form-control w-25 h-100" <?php if ("Thursday" == $update['Thursday']) {echo 'checked' ;} ?> name="Thursday" type="checkbox" value="Thursday" id="Thursday" >
                                            <input class="form-check-input form-control w-50 h-100" value="<?php echo TimeToDisplayIn24($update['Thursday_time']); ?>" type="time" id="Thursday_time" name="Thursday_time"  >
                                            <span class="btn btn-light" onclick="document.getElementById('Thursday_time').value = ''">clear </span>

                                        </div>

                                        <div class="form-group  d-inline-flex justify-content-around mt-3">
                                            <label class="form-check-label d-inline-flex align-items-center w-25 h-100" for="Friday"><strong class="w-100">Fr-day:</strong></label>
                                            <input class="form-check-input form-control w-25 h-100" <?php if ("Friday" == $update['Friday']) {echo 'checked' ;} ?> name="Friday" type="checkbox" value="Friday" id="Friday" >
                                            <input class="form-check-input form-control w-50 h-100" value="<?php echo TimeToDisplayIn24($update['Friday_time']); ?>" type="time" id="Friday_time" name="Friday_time"  >
                                            <span class="btn btn-light" onclick="document.getElementById('Friday_time').value = ''">clear </span>

                                        </div>

                                    </div>


                                    <div class="form-group row ">
                                        <button  class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 border-0">تعديل البيانات</button>
                                    </div>

                                </div>

                            </form>
                                <button onclick="history.back()" class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 border-0"> عودة</button>
                            <?php
                        }else {
                            $_SESSION['Emessage'] = ' تأكد من الكود';
                            header('Location: ' . $_SERVER['HTTP_REFERER']);exit;
                        }
                        ?>
                        <?php
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
                    $update_stmt = $con->prepare("SELECT * FROM class WHERE ID = ?  ");
                    $update_stmt->execute(array($id));
                    $update_count = $update_stmt->rowCount();
                    $Student_fetch =$update_stmt->fetch();
                    if ($update_count > 0 ) {
                        $stmt = $con->prepare("UPDATE  class SET `status`=?   WHERE ID =? ");
                        $stmt->execute(array('Pause',$id  ));
                        $xcount = $stmt->rowCount();


                        SetCancel($Student_fetch['Student']);
                        SetActual($Student_fetch['Student']);

                        if ($xcount > 0  ) {
                            $Message = "تم ايقاف الطالب".$Student_fetch['Student']." ( ".$Student_fetch['StudentName']." )";
                            NotificationSend($Student_fetch['Teacher'],$Message,"No","ايقاف الطلاب");
                            $_SESSION['Emessage'] = ' تم ايقاف الجدول بنجاح ';
                            header('Location: ' . $_SERVER['HTTP_REFERER']);exit;
                        } else { 
                            $_SESSION['Emessage'] = ' لم يتم ايقاف الجدول حاول مرة اخرى ';
                            header('Location: ' . $_SERVER['HTTP_REFERER']);exit;
                        }
                    }else {
                        $_SESSION['Emessage'] = ' تأكد من الكود';
                        header('Location: ' . $_SERVER['HTTP_REFERER']);exit;
                    }
                      }else {
                        $_SESSION['Emessage'] = ' تأكد من الكود';
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
                  <h5 class="card-title text-center">  تفعيل  طالب   </h5> 
                      <?php
                  if (isset($_GET['ID'])) {
                    $id = filter_var($_GET["ID"], FILTER_UNSAFE_RAW );
                    $update_stmt = $con->prepare("SELECT * FROM class WHERE ID = ?  ");
                    $update_stmt->execute(array($id));
                    $update_count = $update_stmt->rowCount();
                    $Student_fetch =$update_stmt->fetch();
                    if ($update_count > 0 ) {
                        $stmt = $con->prepare("UPDATE  class SET `status`=?   WHERE ID =? ");
                        $stmt->execute(array('Active',$id  ));
                        $xcount = $stmt->rowCount();

                        SetActive($Student_fetch['Student']);
                        SetActual($Student_fetch['Student']);

                        if ($xcount > 0  ) {
                            $_SESSION['Emessage'] = ' تم تفعيل الجدول بنجاح ';
                            header('Location: ' . $_SERVER['HTTP_REFERER']);exit;
                        } else { 
                            $_SESSION['Emessage'] = ' لم يتم تفعيل الجدول حاول مرة اخرى';
                            header('Location: ' . $_SERVER['HTTP_REFERER']);exit;
                        }
                    }else {
                        $_SESSION['Emessage'] = ' تأكد من الكود';
                        header('Location: ' . $_SERVER['HTTP_REFERER']);exit;
                    }
                      }else {
                        $_SESSION['Emessage'] = ' تأكد من الكود';
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
                    $Dschedule_stmt = $con->prepare("SELECT * FROM class WHERE ID = ?  ");
                    $Dschedule_stmt->execute(array($id));
                    $Dschedule=$Dschedule_stmt->fetch();
                    $Dschedule_count = $Dschedule_stmt->rowCount(); 
                    if ($Dschedule_count > 0) {
                        
                        $stmt = $con->prepare("DELETE FROM class WHERE ID= ? ");
                        $stmt->execute(array($id));
                        $count = $stmt->rowCount();

                        SetCancel($Dschedule['Student']);
                        SetActual($Dschedule['Student']);

                        if ($count > 0  ) {
                            $_SESSION['Emessage'] = ' تم حذف الجدول بنجاح ';
                            header('Location: ' . $_SERVER['HTTP_REFERER']);exit;
                        } else {
                            $_SESSION['Emessage'] = ' لم يتم حذف الجدول حاول مرة اخرى ';
                            header('Location: ' . $_SERVER['HTTP_REFERER']);exit;
                        }
                    }else {
                        $_SESSION['Emessage'] = ' تأكد من الكود';
                        header('Location: ' . $_SERVER['HTTP_REFERER']);exit;
                    }
                      }else {
                        $_SESSION['Emessage'] = ' تأكد من الكود';
                        header('Location: ' . $_SERVER['HTTP_REFERER']);exit;
                      } 

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
<script src="assets/js/jquery-22-05-2023.js"></script>
<?php include "assets/tem/footer.php" ?>
<?php 
if (!empty($_SESSION['Emessage'])) { ?>
  <script>
    $(document).ready(function() {
        $(".toast").toast('show');
    });
</script>
<?php unset($_SESSION['Emessage']); } ?>
<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['CustomerService']) AND !isset($_SESSION['manager']) AND !isset($_SESSION['CustomerServiceManager']) ) {  header('Location: index');  exit;  } 
if (!isset($_GET["code"]) ) {     $_SESSION['message'] = 'كود الطالب غير صحيح'; header('Location: ' . $_SERVER['HTTP_REFERER']); exit; } 
include "assets/tem/header.php" ; 
if (isset($_SESSION['Who']) AND in_array($_SESSION['Who'],array('AbdelRahman','Belal','Hamza','Ramadan','Bedo'))) {
  $Who = $_SESSION['Who'];
}else {
  $Who = "All";
}
if ($Who =="All") {
  $stmt = " ";
  $execute = array() ;
}else {
  $stmt = "WHERE `Who`= ?";
  $execute = array($Who) ;
}

if (isset($_GET["code"])){ 

  $student = filter_var($_GET["code"], FILTER_UNSAFE_RAW );

  $StudentsCount = $con->prepare("SELECT * FROM students WHERE Code LIKE ?  LIMIT 1 ");
  $StudentsCount->execute(array("%$student %"));
  $StudentsRowCount = $StudentsCount->rowCount();



  $Students = $con->prepare("SELECT * FROM students WHERE Code LIKE ?  LIMIT 1 ");
  $Students->execute(array("%$student %"));
  $StudentArry=$Students->fetch();
  $Students_count = $Students->rowCount();

  $H_stmt = $con->prepare("SELECT * FROM history WHERE S_code LIKE ? ORDER BY `Timestamp` ASC ");
  $H_stmt->execute(array($StudentArry['Code']));
  $count = $H_stmt->rowCount();
  $row = $H_stmt->fetchAll();

  $Remained = $con->prepare("SELECT * FROM students WHERE Code=? ");
  $Remained->execute(array($StudentArry['Code']));
  $Remained_count = $Remained->rowCount();
  $Remainedfetch = $Remained->fetch();
  if ($Remained_count > 0) {
    $Renewal_date = date('Y_m_d', strtotime($Remainedfetch['Renewal_date']));
    $Remained = $Remainedfetch['Remained'];
  }else {
    $Renewal_date = "?";
    $Remained = "?";
  }
  $CodeExplode = explode(' ',$StudentArry['Code']);
  $StudentCodeExplode = $CodeExplode[1] ;

  if ($StudentsRowCount > 0) { ?>
<main id="main" class="main">
  <div class="container">
    <div class="pagetitle">
    <nav>
        <ol class="breadcrumb">
          <li class="">        
            <button class="btn btn-primary fs-6 fw-bold text-white" onclick="window.location= 'CustomerService' ">
            <i class="bi bi-box-arrow-left"></i>
            <span>الخروج</span>
            </button.><!-- End Dashboard Iamge Icon -->
          </li>
        </ol>
      </nav>
      <h1> Renewal Date Page</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active"> Renewal Date </li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    
    <section class="section">
    <div class="row ">
        <div class="col-lg-6 m-auto">
          <div class="card ">
            <div id="" class="card-body overflow-auto rtl ">
              <div class="row ">
                <div class=" col-lg-6 mt-5">
                  <p class="card-details mb-0"   > <i class="fa-solid fa-user"></i> &nbsp; الكود  :- &nbsp;<small style=" font-size: 1.25em;"><?php echo $StudentArry['Code'] ; ?></small></p>
                  <p class="card-details mb-0"   > <i class="fa-sharp fa-solid fa-arrow-down-9-1"></i> &nbsp; عدد الطلاب :- &nbsp;<small style=" font-size: 1.25em;"><?php echo $StudentArry['N_Students'] ; ?></small></p>
                  <p class="card-details mb-0"   > <i class="fa-solid fa-calendar-days"></i> &nbsp; الباقة :- &nbsp;<small style=" font-size: 1.25em;"><?php echo $StudentArry['Days'] ; ?></small></p>
                  <p class="card-details mb-0"   > <i class="fa-solid fa-stopwatch"></i> &nbsp; حالة الكود :- &nbsp;<small style=" font-size: 1.25em;"><?php echo $StudentArry['status'] ; ?></small></p>
                  </div>
                  <div class=" col-lg-6 mt-5">
                  <p class="card-details mb-0"   > <i class="fa-solid fa-user"></i> &nbsp; الإشتراك بالمصرى  :- &nbsp;<small style=" font-size: 1.25em;"><?php echo $StudentArry['E_Cost'] ; ?></small></p>
                  <p class="card-details mb-0"   > <i class="fa-sharp fa-solid fa-arrow-down-9-1"></i> &nbsp;  الإشتراك بالريال :- &nbsp;<small style=" font-size: 1.25em;"><?php echo $StudentArry['S_Cost'] ; ?></small></p>
                  <p class="card-details mb-0"   > <i class="fa-solid fa-calendar-days"></i> &nbsp;  تاريخ التجديد :- &nbsp;<small style=" font-size: 1.25em;"><?php echo $Renewal_date ; ?></small></p>
                  <p class="card-details mb-0"   > <i class="fa-solid fa-stopwatch"></i> &nbsp;  الحصص المتبقية :- &nbsp;<small style=" font-size: 1.25em;<?php if ($Remained < 0) { echo ' color: green;';}elseif ($Remained > 0) { echo ' color: red;';}else {}?>"><?php echo $Remained ; ?></small></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6 m-auto">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title text-center"> طلب تسوية </h5>
              <form action="CustomerServiceJournalApi" method="get" id="Journal">
                
                <div class="mb-3">
                  <label for="Settlement" class="form-label">الكود</label>
                  <input class="form-control"  name="Settlement" id="Settlement" value="<?php echo $StudentArry['Code']?>" required='true' readonly >
                </div>
                
                <div class="mb-3">
                  <label for="Name" class="form-label">الأسم</label>
                  <input class="form-control"  name="Name" id="Name" value="<?php echo $StudentCodeExplode ?>" required='true' readonly >
                </div>

                <div class="mb-3">
                  <label for="Subscription" class="form-label"> عدد الحصص </label>
                  <input type="number" name="Subscription"   class="form-control" id="Subscription"  required='true'>
                  <div   class="form-text"> </div>
                </div>
                
                <div class="form-check form-check-inline mt-2" >
                  <input class="form-check-input" type="radio" name="Type" id="Type1" value="2" required='true' onchange="Check('Type1_checkbox')">
                  <label class="form-check-label" for="Type1">دائم</label>
                </div>
                <div class="form-check form-check-inline mt-2">
                  <input class="form-check-input" type="radio" name="Type" id="Type2" value="7"  required='true' onchange="Check('Type2_checkbox')">
                  <label class="form-check-label" for="Type2">مؤقت</label>
                </div>

                <div  id="Statusdiv" style="display: none;">

                  <div class="form-check form-check-inline mt-2">
                    <input class="form-check-input" type="radio" name="status" id="status1" value="-" required='true'>
                    <label class="form-check-label" for="status1">اضافة</label>
                  </div>
                  <div class="form-check form-check-inline mt-2">
                    <input class="form-check-input" type="radio" name="status" id="status2" value="+"  required='true'>
                    <label class="form-check-label" for="status2">خصم</label>
                  </div>
                </div>
                 

                <div class="mb-3" id="Expire" style="display: none;">
                  <label for="Note" class="form-label"> تاريخ الإنتهاء </label>
                  <input class="form-control" type="date" name="Expire" id="Expireinput" value='' >
                </div>

                <div class="mb-3">
                  <label for="Note" class="form-label"> التقرير </label>
                  <textarea  type="text" name="Note"   class="form-control" id="Note"  required='true'></textarea>
                  <div   class="form-text"> </div>
                </div>

                <div class="mb-3">
                  <input type="hidden" name="Who" value="<?php echo $Who ?>" class="form-control" id="Who"  required='true'>
                </div>
                
                <div  id="alert" class="d-none alert alert-danger" role="alert">
                  جميع الحقول مطلوبة
                </div>
                <div  id="Codealert" class="d-none alert alert-danger" role="alert">
                  كود الطالب غير صحيح
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
              </form>
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

<script>
  var Statusdiv = document.getElementById('Statusdiv');
  var status1 = document.getElementById('status1');
  var Expireinput = document.getElementById('Expireinput');
  var Expire = document.getElementById('Expire');
function Check(checkBox) {
  if (checkBox == 'Type1_checkbox' ){
    Statusdiv.style.display = "block";
    Expireinput.value = '<?php echo date("Y-m-d") ?>';

    Expire.style.display = "none";
    status1.checked = false;
  } else {
    Statusdiv.style.display = "none";
    Expireinput.value = '';

    Expire.style.display = "block";
    status1.checked = true
  }

}
</script>

<?php }else {
    $_SESSION['message'] = 'كود الطالب غير صحيح';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }
}

?>
<?php include "assets/tem/footer.php" ?>
<?php 
if (!empty($_SESSION['Emessage'])) { ?>
  <script>
    $(document).ready(function() {
        $(".toast").toast('show');
    });
</script>
<?php unset($_SESSION['Emessage']); } ?>
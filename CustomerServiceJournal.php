<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['CustomerService']) AND !isset($_SESSION['manager']) AND !isset($_SESSION['CustomerServiceManager']) ) {  header('Location: index');  exit;  } 
if (!isset($_GET["code"]) ) { $_SESSION['Emessage'] = 'كود الطالب غير صحيح'; header('Location: ' . $_SERVER['HTTP_REFERER']); exit; } 
include "assets/tem/header.php" ; 
if (isset($_GET['Who']) AND in_array($_GET['Who'],array('AbdelRahman','Belal','Hamza','Ramadan','Bedo','All'))) {
    $Who = $_GET['Who'];
}else {
  if (isset($_SESSION['Who']) AND in_array($_SESSION['Who'],array('AbdelRahman','Belal','Hamza','Ramadan','Bedo'))) {
    $Who = $_SESSION['Who'];
  }else {
    $Who = "All";
  }
}

 if (isset($_GET["code"])){ 

  $student = filter_var($_GET["code"], FILTER_UNSAFE_RAW );

  $StudentsCount = $con->prepare("SELECT * FROM students WHERE Code LIKE ?  LIMIT 1 ");
  $StudentsCount->execute(array("%$student %"));
  $StudentsRowCount = $StudentsCount->rowCount();

  if ($StudentsRowCount > 1) {
    $_SESSION['Emessage'] = ' هناك أكثر من طالب بنفس الكود رجاء مراجعة الدعم الفنى '; header('Location: ' . $_SERVER['HTTP_REFERER']); exit; 
  }


  if ($StudentsRowCount > 0) {
    $Students = $con->prepare("SELECT * FROM students WHERE Code LIKE ?  LIMIT 1 ");
  $Students->execute(array("%$student %"));
  $StudentArry=$Students->fetch();
  $Students_count = $Students->rowCount();

  if ($StudentArry['Who'] != $Who ) {
    $_SESSION['Emessage'] = ' هذا الطالب خاص بمسؤول تواصل آخر '; header('Location: ' . $_SERVER['HTTP_REFERER']); exit; 
  }
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

  if ( date('Y-m-d') >= date('Y-m-01') AND date('Y-m-d') <= date('Y-m-05')) {
    $Paymentmin = date("Y-m-01", strtotime("-1 month")) ;
    $Paymentmax = date("Y-m-d") ;
  }else {
    $Paymentmin = date("Y-m-01") ;
    $Paymentmax = date("Y-m-d") ;
  }  ?>
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
      <h1>Add Journal Page</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Add Journal</li>
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
                  <p class="card-details mb-0"   > <i class="fa-solid fa-user"></i> &nbsp; الكود  : &nbsp;<small style=" font-size: 1.25em;"><?php echo $StudentArry['Code'] ; ?></small></p>
                  <p class="card-details mb-0"   > <i class="fa-sharp fa-solid fa-arrow-down-9-1"></i> &nbsp; عدد الطلاب : &nbsp;<small style=" font-size: 1.25em;"><?php echo $StudentArry['N_Students'] ; ?></small></p>
                  <p class="card-details mb-0"   > <i class="fa-solid fa-calendar-days"></i> &nbsp; الباقة : &nbsp;<small style=" font-size: 1.25em;"><?php echo $StudentArry['Days'] ; ?></small></p>
                  <p class="card-details mb-0"   > <i class="fa-solid fa-stopwatch"></i> &nbsp; حالة الكود : &nbsp;<small style=" font-size: 1.25em;"><?php echo $StudentArry['status'] ; ?></small></p>
                  </div>
                  <div class=" col-lg-6 mt-5">
                  <p class="card-details mb-0"   > <i class="fa-solid fa-user"></i> &nbsp; الإشتراك بالمصرى  :- &nbsp;<small style=" font-size: 1.25em;"><?php echo $StudentArry['E_Cost'] ; ?></small></p>
                  <p class="card-details mb-0"   > <i class="fa-sharp fa-solid fa-arrow-down-9-1"></i> &nbsp;  الإشتراك بالريال : &nbsp;<small style=" font-size: 1.25em;"><?php echo $StudentArry['S_Cost'] ; ?></small></p>
                  <p class="card-details mb-0"   > <i class="fa-solid fa-calendar-days"></i> &nbsp;  تاريخ التجديد : &nbsp;<small style=" font-size: 1.25em;"><?php echo $Renewal_date ; ?></small></p>
                  <p class="card-details mb-0"   > <i class="fa-solid fa-stopwatch"></i> &nbsp;  الحصص المتبقية : &nbsp;<small style=" font-size: 1.25em;<?php if ($Remained < 0) { echo ' color: green;';}elseif ($Remained > 0) { echo ' color: red;';}else {}?>"><?php echo $Remained ; ?></small></p>
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
              <h5 class="card-title text-center"> Journal Book </h5>
              <form action="CustomerServiceJournalApi" method="post" id="Journal">
                
                <div class="mb-3">
                  <label for="Amount" class="form-label"> Amount  </label>
                  <input type="number" name="Amount" class="form-control" id="Amount" required='true' >
                  <div   class="form-text"> </div>
                </div>
                <div class="mb-3">
                  <label for="Code" class="form-label"> Code  </label>
                  <input type="text" name="Code" value="<?php echo $StudentArry['Code'] ; ?>" class="form-control" id="Code" readonly  required='true' >
                  <div   class="form-text"> </div>
                </div>

                <div class="mb-3">
                  <label for="Name" class="form-label"> Student-Name  </label>
                  <input type="text" name="Name" class="form-control" id="Name" value="<?php echo $StudentCodeExplode ; ?>" readonly  required='true'>
                  <div   class="form-text"> Just the first name </div>
                </div>

                <!-- -----Payment-Way----- -->
                  <div class="mb-3 ">
                    <label class="form-label"> Payment-Way  </label><br>
                  </div>
 
                  <div class="mb-3 form-check">
                    <input type="radio" name="Way" value="Wallet" class="form-check-input" id="Way0"required='true'>
                    <label class="form-check-label" for="Way0"> Wallet </label>
                  </div>
 
                  <div class="mb-3 form-check">
                    <input type="radio" name="Way" value="Instapay" class="form-check-input" id="Way1"required='true'>
                    <label class="form-check-label" for="Way1"> Instapay </label>
                  </div>

                  <div class="mb-3 form-check">
                    <input type="radio" name="Way" value="Bank" class="form-check-input" id="Way2"required='true'>
                    <label class="form-check-label" for="Way2"> Bank </label>
                  </div>

                  <div class="mb-3 form-check">
                    <input type="radio" name="Way" value="EasyKash" class="form-check-input" id="Way3"required='true'>
                    <label class="form-check-label" for="Way3"> EasyKash </label>
                  </div>

                  <div class="mb-3 form-check">
                    <input type="radio" name="Way" value="Westren" class="form-check-input" id="Way4"required='true'>
                    <label class="form-check-label" for="Way4"> Westren Union </label>
                  </div>

                  <div class="mb-3 form-check">
                    <input type="radio" name="Way" value="PayPal" class="form-check-input" id="Way5"required='true'>
                    <label class="form-check-label" for="Way5"> PayPal </label>
                  </div>

                  <div class="mb-3 form-check">
                    <input type="radio" name="Way" value="Cash" class="form-check-input" id="Way6"required='true'>
                    <label class="form-check-label" for="Way6"> Cash </label>
                  </div>

                <!-- --------- -->

                <!-- -----Renewal VS Trail----- -->
                  <div class="mb-3 ">
                    <label class="form-label"> Renewal VS Trail  </label><br>
                  </div>

                  <div class="mb-3 form-check">
                    <input type="radio" name="RenewalVSTrail" value="Renewal" class="form-check-input" id="RenewalVSTrail1"required='true'>
                    <label class="form-check-label" for="RenewalVSTrail1"> Renewal </label>
                  </div>

                  <div class="mb-3 form-check">
                    <input type="radio" name="RenewalVSTrail" value="Trail" class="form-check-input" id="RenewalVSTrail2"required='true'>
                    <label class="form-check-label" for="RenewalVSTrail2"> Trail </label>
                  </div>


                <!-- --------- -->

                <div class="mb-3">
                  <label for="Date" class="form-label"> Date of Payment  </label>
                  <input type="date" name="Date" class="form-control" id="Date"  required='true' min="<?php echo $Paymentmin ?>" max="<?php echo $Paymentmax ?>">
                  <div   class="form-text"> </div>
                </div>

                <div class="mb-3">
                  <label for="Note" class="form-label"> Note  </label>
                  <input type="text" name="Note" class="form-control" id="Note"  >
                  <div   class="form-text"> If you have </div>
                </div>

                <div class="mb-3">
                  <input type="hidden" name="Who" value="<?php echo $Who ?>" class="form-control" id="Who"  required='true'>
                </div>

                <div class="mb-3">
                  <input type="hidden" name="Type" value="1" class="form-control" id="Type"  required='true'>
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
  <?php }else {
    $_SESSION['Emessage'] = 'كود الطالب غير صحيح';
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
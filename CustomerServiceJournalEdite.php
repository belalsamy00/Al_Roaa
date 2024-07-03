<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['CustomerService']) AND !isset($_SESSION['manager']) AND !isset($_SESSION['CustomerServiceManager']) ) {  header('Location: index');  exit;  } 
if ( !isset($_GET["Update"])) {  header('Location: index');  exit;  } ;
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
$Students = $con->prepare("SELECT * FROM students $stmt");
$Students->execute($execute);
$Students_count = $Students->rowCount();
$StudentsArry = $Students->fetchAll();

$GetToUpdateJournal = $con->prepare("SELECT * FROM Journal WHERE ID=? ");
$GetToUpdateJournal->execute(array($_GET["Update"]));
$GetToUpdateJournal_count = $GetToUpdateJournal->rowCount();
$GetToUpdateJournal_fetch = $GetToUpdateJournal->fetch();
if ($GetToUpdateJournal_count > 0) { ?>
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
        <h1>Edite Journal</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="CustomerServiceJournalView">Journal</a></li>
            <li class="breadcrumb-item active">Edite</li>
          </ol>
        </nav>
      </div><!-- End Page Title -->
      
      <section class="section">
        <div class="row">

          <div class="col-lg-6 m-auto">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title text-center"> Journal Book </h5>
                <form action="CustomerServiceJournalApi" method="get" id="Journal">
                  
                  <div class="mb-3">
                    <label for="Amount" class="form-label"> Amount  </label>
                    <input type="number" name="Amount" value="<?php echo $GetToUpdateJournal_fetch['Amount'] ?>" class="form-control" id="Amount" required='true' >
                    <div   class="form-text"> </div>
                  </div>
                  
                  <div class="mb-3">
                    <label for="Code" class="form-label"> Code  </label>
                    <input type="text" name="Code" value="<?php echo $GetToUpdateJournal_fetch['Code'] ?>" class="form-control" id="Code" readonly required='true' >
                    <div   class="form-text"> </div>
                  </div>

                  <div class="mb-3">
                    <label for="Name" class="form-label"> Student-Name  </label>
                    <input type="text" name="Name" class="form-control" id="Name" value="<?php echo $GetToUpdateJournal_fetch['Name'] ?>" readonly required='true'>
                    <div   class="form-text"> Just the first name </div>
                  </div>

                  <!-- -----Payment-Way----- -->
                    <div class="mb-3 ">
                      <label class="form-label"> Payment-Way  </label><br>
                    </div>
  
                    <div class="mb-3 form-check">
                      <input type="radio" name="Way" value="Wallet" class="form-check-input" id="Way1"required='true'<?php if ("Wallet" == $GetToUpdateJournal_fetch['Payment_Way']) {echo 'checked' ;} ?>>
                      <label class="form-check-label" for="Way1"> Wallet </label>
                    </div>

                    <div class="mb-3 form-check">
                      <input type="radio" name="Way" value="Bank" class="form-check-input" id="Way2"required='true'<?php if ("Bank" == $GetToUpdateJournal_fetch['Payment_Way']) {echo 'checked' ;} ?>>
                      <label class="form-check-label" for="Way2"> Bank </label>
                    </div>

                    <div class="mb-3 form-check">
                      <input type="radio" name="Way" value="EasyKash" class="form-check-input" id="Way3"required='true'<?php if ("EasyKash" == $GetToUpdateJournal_fetch['Payment_Way']) {echo 'checked' ;} ?>>
                      <label class="form-check-label" for="Way3"> EasyKash </label>
                    </div>

                    <div class="mb-3 form-check">
                      <input type="radio" name="Way" value="Westren" class="form-check-input" id="Way4"required='true'<?php if ("Westren" == $GetToUpdateJournal_fetch['Payment_Way']) {echo 'checked' ;} ?>>
                      <label class="form-check-label" for="Way4"> Westren Union </label>
                    </div>

                    <div class="mb-3 form-check">
                      <input type="radio" name="Way" value="PayPal" class="form-check-input" id="Way5"required='true'<?php if ("PayPal" == $GetToUpdateJournal_fetch['Payment_Way']) {echo 'checked' ;} ?>>
                      <label class="form-check-label" for="Way5"> PayPal </label>
                    </div>

                    <div class="mb-3 form-check">
                      <input type="radio" name="Way" value="Cash" class="form-check-input" id="Way6"required='true'<?php if ("Cash" == $GetToUpdateJournal_fetch['Payment_Way']) {echo 'checked' ;} ?>>
                      <label class="form-check-label" for="Way6"> Cash </label>
                    </div>

                  <!-- --------- -->

                  <!-- -----Renewal VS Trail----- -->
                    <div class="mb-3 ">
                      <label class="form-label"> Renewal VS Trail  </label><br>
                    </div>

                    <div class="mb-3 form-check">
                      <input type="radio" name="RenewalVSTrail" value="Renewal" class="form-check-input" id="RenewalVSTrail1"required='true'<?php if ("Renewal" == $GetToUpdateJournal_fetch['Renewal_VS_Trail']) {echo 'checked' ;} ?>>
                      <label class="form-check-label" for="RenewalVSTrail1"> Renewal </label>
                    </div>

                    <div class="mb-3 form-check">
                      <input type="radio" name="RenewalVSTrail" value="Trail" class="form-check-input" id="RenewalVSTrail2"required='true'<?php if ("Trail" == $GetToUpdateJournal_fetch['Renewal_VS_Trail']) {echo 'checked' ;} ?>>
                      <label class="form-check-label" for="RenewalVSTrail2"> Trail </label>
                    </div>


                  <!-- --------- -->

                  <div class="mb-3">
                    <label for="Date" class="form-label"> Date of Payment  </label>
                    <input type="date" name="Date" class="form-control" id="Date" value="<?php echo $GetToUpdateJournal_fetch['Date_of_Payment'] ?>" required='true'>
                    <div   class="form-text"> </div>
                  </div>

                  <div class="mb-3">
                    <label for="Note" class="form-label"> Note  </label>
                    <input type="text" name="Note" class="form-control" id="Note" value="<?php echo $GetToUpdateJournal_fetch['Note'] ?>" >
                    <div   class="form-text"> If you have </div>
                  </div>

                  <div class="mb-3">
                    <input type="hidden" name="Who"  value="<?php echo $GetToUpdateJournal_fetch['Who'] ?>" class="form-control" id="Who"  required='true'>
                  </div>

                  <div class="mb-3">
                    <input type="hidden" name="Update"  value="<?php echo $GetToUpdateJournal_fetch['ID'] ?>" class="form-control" id="Who"  required='true'>
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
<?php }?>

<?php include "assets/tem/footer.php" ?>
<?php 
if (!empty($_SESSION['Emessage'])) { ?>
  <script>
    $(document).ready(function() {
        $(".toast").toast('show');
    });
</script>
<?php unset($_SESSION['Emessage']); } ?>
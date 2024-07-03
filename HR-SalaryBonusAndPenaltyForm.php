<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['CustomerService']) AND !isset($_SESSION['manager']) AND !isset($_SESSION['CustomerServiceManager']) ) {  header('Location: index');  exit;  } 
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

$Add_ct_stmt = $con->prepare("SELECT Code FROM teachers");
$Add_ct_stmt->execute(array());
$Tclass=$Add_ct_stmt->fetchAll();
$Add_ct_count = $Add_ct_stmt->rowCount();
?>
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
      <div class="row">
        <div class="col-lg-6 m-auto">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title text-center"> الخصم والأضافة للمعلمين </h5>
              <form action="CustomerServiceJournalApi" method="get" id="Journal">

              
                <input type="hidden" name="CustomerServiceBonus" value="CustomerServiceBonus">
                <div class="mb-3">
                  <label for="Amount" class="form-label"> المبلغ  </label>
                  <input type="number" name="Amount" class="form-control" id="Amount" required='true' >
                  <div   class="form-text"> </div>
                </div>
                <div class="mb-3">
                  <label for="Code" class="form-label"> الكود  </label>
                  <select      id="Code" name="Code" class="form-control form-select form-select-lg mb-3" required='true'>
                      <option selected disabled value="">اختر معلم</option> 
                      <?php foreach ($Tclass as $key ) { ?>
                      <option     value="<?php echo $key['Code'] ?>"><?php echo $key['Code'] ?></option> <?php } ?>
                  </select>
                </div>


                <!-- -----Payment-Way----- -->
                  <div class="mb-3 ">
                    <label class="form-label"> طريقة الدفع  </label><br>
                  </div>
 
                  <div class="mb-3 form-check">
                    <input type="radio" name="Way" value="Wallet" class="form-check-input" id="Way1"required='true'>
                    <label class="form-check-label" for="Way1"> Wallet </label>
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
                    <label class="form-label"> إضافة VS خصم  </label><br>
                  </div>

                  <div class="mb-3 form-check">
                    <input type="radio" name="Type" value="Bonus" class="form-check-input" id="Type1"required='true'>
                    <label class="form-check-label" for="Type1"> إضافة </label>
                  </div>

                  <div class="mb-3 form-check">
                    <input type="radio" name="Type" value="Penalty" class="form-check-input" id="Type2"required='true'>
                    <label class="form-check-label" for="Type2"> خصم </label>
                  </div>


                <!-- --------- -->


                <div class="mb-3">
                  <label for="Date" class="form-label"> التاريخ  </label>
                  <input type="date" name="Date" class="form-control" id="Date"  required='true'>
                  <div   class="form-text"> </div>
                </div>

                <!-- -----Renewal VS Trail----- -->
                <div class="mb-3 ">
                    <label class="form-label"> تفاصيل </label><br>
                  </div>

                  <div id="Bonus">

                    <div class="mb-3 form-check">
                      <input disabled type="radio" name="Note" value="هدية" class="form-check-input" id="Note1"required='true'>
                      <label  class="form-check-label" for="Note1"> هدية طالب </label>
                    </div>
  
                    <div class="mb-3 form-check">
                      <input disabled  type="radio" name="Note" value="بونص" class="form-check-input" id="Note2"required='true'>
                      <label class="form-check-label" for="Note2"> بونص </label>
                    </div>
                  </div>

                  <div id="Penalty">

                    <div class="mb-3 form-check">
                      <input disabled type="radio" name="Note" value="خصم" class="form-check-input" id="Note3"required='true'>
                      <label class="form-check-label" for="Note3"> خصم </label>
                    </div>
  
                    <div class="mb-3 form-check">
                      <input disabled type="radio" name="Note" value="سلفة" class="form-check-input" id="Note4"required='true'>
                      <label class="form-check-label" for="Note4"> سلفة </label>
                    </div>
                  </div>
                  <div class="mb-3 form-check">
                    <input   type="radio" name="Note"  class="form-check-input" id="Note5"required='true'>
                    <label class="form-check-label" for="Note5" > أخرى </label>
                  </div>




                <!-- --------- -->
                <div class="mb-3">
                  <input type="text" class="form-control" id="Note" >
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
  $('#Type1').click(function() {
    checked = $('Type1:checked').length;
    if(checked == 0  ) { 
      $('#Note1,#Note2').removeAttr('disabled');
      $('#Note3,#Note4').prop('disabled','disabled');
      $('#Note3,#Note4').prop('checked',false);
    };
  });
  $('#Type2').click(function() {
    checked = $('Type2:checked').length;
    if(checked == 0  ) { 
      $('#Note3,#Note4').removeAttr('disabled');
      $('#Note1,#Note2').prop('disabled','disabled');
      $('#Note1,#Note2').prop('checked',false);
    };
  });
  $('#Note5').click(function() {
    checked = $('Note5:checked').length;
    if(checked == 0  ) { $('#Note').prop('name','Note')};
    if(checked == 0  ) { $('#Note').prop('required',true)};
  });
  $('#Note1,#Note2,#Note3,#Note4').click(function() {
    var requiredAttr = $('#Note').attr('required');
    var nameAttr = $('#Note').attr('name');
    if(typeof requiredAttr !== 'undefined' && requiredAttr !== false ) { $('#Note').removeAttr('required')};
    if(typeof nameAttr !== 'undefined' && nameAttr !== false ) { $('#Note').removeAttr('name')};
  });
</script>
<?php include "assets/tem/footer.php" ?>
<?php 
if (!empty($_SESSION['Emessage'])) { ?>
  <script>
    $(document).ready(function() {
        $(".toast").toast('show');
    });
</script>
<?php unset($_SESSION['Emessage']); } ?>
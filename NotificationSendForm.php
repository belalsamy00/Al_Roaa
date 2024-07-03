<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if ( !isset($_SESSION['manager']) ) {  header('Location: index');  exit; } 
include "assets/tem/header.php" ; 
if (isset($_SESSION['Suber_Admin'])) {
  if (isset($_SESSION['manager'])) {
    $session_code = $_SESSION['manager'] ;
    $where = "WHERE trust=? OR trust=? OR trust=? OR trust=? OR trust=? OR trust=?";
    $trust =  array(5,6,4,3,7,8);
  }else {
    $session_code = $_SESSION['Suber_Admin'] ;
    $where = "WHERE trust=?  OR trust=?";
    $trust = array(5,3);
  }

}elseif (isset($_SESSION['manager'])) {
  $session_code = $_SESSION['manager'] ;
  $where = "WHERE trust=? OR trust=? OR trust=? OR trust=? OR trust=? OR trust=?";
  $trust =  array(5,6,4,3,7,8);
}elseif (isset($_SESSION['supervisor'])) {
  $session_code = $_SESSION['supervisor'] ;
  $where = "WHERE trust=?";
  $trust = array(0);
}elseif (isset($_SESSION['Supervising_manager'])) {
  $session_code = $_SESSION['Supervising_manager'] ;
  $where = "WHERE trust=? ";
  $trust = array(3);
}elseif (isset($_SESSION['Admin'])){
  $session_code = $_SESSION['Admin'] ;
  $where = "WHERE trust=?  ";
  $trust = array(0);
}else {
  $session_code = $_SESSION['Admin'] ;
  $where = "WHERE trust=?  ";
  $trust = array(0);
}
$get_cods_stmt = $con->prepare("SELECT * FROM users $where ");
$get_cods_stmt->execute($trust);
$cods = $get_cods_stmt->fetchAll();


$get_cods_stmt_2 = $con->prepare("SELECT * FROM teachers");
$get_cods_stmt_2->execute();
$cods_2 = $get_cods_stmt_2->fetchAll();

// ---------------------
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
      <h1>Send Notification</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Notification</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    
    <section class="section">
      <div class="row">
        <div class="col-lg-8 m-auto">
    
        <div class="card">
          <div class="card-body">
          <h5 class="card-title text-center"> أرسال إشعار </h5> 

          <form class="mt-2" method="POST" action="NotificationSend">

          <input class="form-control text-center  mt-2" type="text" name="Category" placeholder=" العنوان " required='true'>
          <input class="form-control text-center  mt-2" type="text" name="Message" placeholder=" نص الإشعار " required='true'>

          <select id="ForWho" name="ForWho[]" class="form-select form-select-lg mt-3" multiple aria-label="multiple size 10 select example" size="10" required='true' >
            <option value="general"> General </option>
            <option value="SuperAdmin"> Super Admins </option>
            <option value="Admin"> Admins </option>
            <option value="supervisor"> Supervisors </option>
            <option value="SupervisingManager"> Supervising Managers </option>
            <option value="CustomerService"> Customer Service </option>
            <option value="teacher"> Teachers </option>
            <?php foreach ($cods as $cod ) { 
              if ($cod['Code'] != "MA-1") { ?>
                <option value="<?php echo $cod['Code'] ?>"><?php echo $cod['Code']." / ".$cod['Name'] ?></option>
              <?php } ?>
            <?php } ?>
            <?php foreach ($cods_2 as $cod ) { ?>
              <option     value="<?php echo $cod['Code'] ?>"><?php echo $cod['Code']." / ".$cod['Name'] ?></option>
            <?php } ?>
          </select>
          <label for="ForWho" class="form-label"> عند اختيار مستخدم يمكنك اختيار اكثر من مستخدم فى نفس الوقت </label>

          <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 " type="submit"> Send </button>
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
<?php include "assets/tem/footer.php" ?>
<?php 
if (!empty($_SESSION['Emessage'])) { ?>
  <script>
    $(document).ready(function() {
        $(".toast").toast('show');
    });
</script>
<?php unset($_SESSION['Emessage']); } ?>
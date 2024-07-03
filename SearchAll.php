<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['Admin']) AND !isset($_SESSION['Suber_Admin']) AND !isset($_SESSION['CustomerService']) AND !isset($_SESSION['CustomerServiceManager']) AND !isset($_SESSION['manager'])) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ; 
if (isset($_SESSION['Who']) AND in_array($_SESSION['Who'],array('AbdelRahman','Belal','Hamza','Ramadan','Bedo'))) {
$Who = $_SESSION['Who'];
}else {
$Who = "All";
} 
if ($Who =="All") {
$stmt = "";
$execute = array() ;
}else {
$stmt = "WHERE `Who`= ?";
$execute = array($Who) ;
}
$Active_stmt = $con->prepare("SELECT * FROM students $stmt");
$Active_stmt->execute($execute);
$search_data=$Active_stmt->fetchAll();
$Active_count = $Active_stmt->rowCount(); 
?>
<main id="main" class="main">
  <div class="container">
    <h1>Search </h1>
    <nav>
    <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
    <li class="breadcrumb-item active">Search</li>
    </ol>
    </nav>
    </div><!-- End Page Title -->
    <section class="section">
      <div class="row">
        <div class="col-12 m-auto ">

          <div class="card">
            <div class="card-body rtl overflow-auto text-center">
              <h5 class="card-title text-center"> جميع الأكواد </h5>

              <table id="DataTable"   class="table table-border table-hover text-center  DataTable  " >
                <thead >
                  <tr >
                  <th class= text-center>الكود</th> 
                  <th class= text-center>الحاله</th>
                  <th class= text-center>عدد الطلاب</th>
                  <th class= text-center>عدد الايام</th>
                  <th class= text-center>عدد الايام الفعلى</th>
                  <th class= text-center>التكلفة مصرى</th>
                  <th class= text-center>التكلفة سعودى</th>
                  <th class= text-center>المسؤل</th>
                  <?php if (isset($_SESSION['manager']) OR isset($_SESSION['CustomerService'])) { 
                  $Count3_stmt = $con->prepare("SELECT COUNT(S_code) As Count3 ,`S_code` FROM history  GROUP BY `S_code` ");
                  $Count3_stmt->execute(array());
                  $Count3_stmt_count = $Count3_stmt->rowCount();
                  $Count3_stmt_fetch = $Count3_stmt->fetchAll();
                  ?>
                  <th class= text-center>الحصص المتبقية</th>
                  <th class= text-center>تاريخ التجديد</th>
                  <?php } ?>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach ($search_data as $data) { 
                  $CodeExplode = explode(' ',$data['Code']);
                  $StudentCodeExplode = $CodeExplode[0] ;


                  if (isset($_SESSION['manager']) OR isset($_SESSION['CustomerService'])) { 
                  if ( array_search($data['Code'],array_column($Count3_stmt_fetch, 'S_code')) != "" ) {
                  $keys = array_search($data['Code'],array_column($Count3_stmt_fetch, 'S_code'));
                  $count =  $Count3_stmt_fetch[$keys]['Count3'] ;

                  }else {
                  $count =  0;
                  }

                  $Remained = $con->prepare("SELECT * FROM students WHERE Code=? ");
                  $Remained->execute(array($data['Code']));
                  $Remained_count = $Remained->rowCount();
                  $Remainedfetch = $Remained->fetch();

                  if ($Remained_count > 0) {
                  $Renewal_date = date('Y_m_d', strtotime($Remainedfetch['Renewal_date']));
                  $Remained = $Remainedfetch['Remained'];
                  }else {
                  $Renewal_date = "?";
                  $Remained = "?";
                  }
                  } ?>
                  <tr onclick="window.location='<?php echo 'search?Code='.$StudentCodeExplode?>';" role="button">
                  <th ><?php echo $StudentCodeExplode ; ?></th>
                  <th ><?php echo $data['status'] ; ?></th>
                  <th ><?php echo $data['N_Students'] ; ?></th>
                  <th ><?php echo $data['Days'] ; ?></th>
                  <th ><?php echo $data['Actual_Days'] ; ?></th>
                  <th ><?php echo $data['E_Cost'] ; ?></th>
                  <th ><?php echo $data['S_Cost'] ; ?></th>
                  <th ><?php echo $data['Who'] ; ?></th>
                  <?php if (isset($_SESSION['manager']) OR isset($_SESSION['CustomerService'])) { ?>
                  <th style="<?php if ($Remained < 0) { echo ' color: green;';}elseif ($Remained > 0) { echo ' color: red;';}else {}?>" ><?php echo $Remained ; ?></th>
                  <th ><?php echo $Renewal_date ; ?></th>
                  <?php } ?>
                  </tr>
                  <?php } 
                  ?>
                </tbody>
              </table>

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
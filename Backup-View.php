<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['teacher'])) {header('location: index.php'); exit; } 
include "assets/tem/header.php" ; ?>
<main id="main" class="main">
  <div class="container">
    <div class="pagetitle">
      <h1>النسخ الأحتياطى</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">الرئيسية</a></li>
          <li class="breadcrumb-item active">النسخ الأحتياطية</li>
          <li class="breadcrumb-item active" id="date_li"></li>
        </ol>
      </nav>
      <nav>
        <ol class="breadcrumb">
        <li class="">        
        <a class="nav-link nav-profile d-flex flex-column align-items-center pe-0" href="Backup" >
        <lord-icon
        src="https://cdn.lordicon.com/ejxwvtlg.json"
        trigger="hover"
        style="width:90px;height:90px">
        </lord-icon>
        <span class="d-block rtl"> إنشاء نسخة أحتياطية جديدة </span>
        </a><!-- End Dashboard Iamge Icon -->
        </li>
        </ol>
    </nav>
    </div><!-- End Page Title -->
  
    <section class="section">
      <div class="row">
        <?php foreach(glob('BackupFiles/Excel/*.*') as $filename){
                $n_ex= explode('-',$filename);
                $n_ex[0] = str_replace("BackupFiles/Excel/","",$n_ex[0]);
                $n_ex[6] = str_replace("DB","All Database Tables",$n_ex[6]);
            ?> <span class="d-flex  justify-content-between mb-2"> <h3 class="w-75"> <?php echo $n_ex[0]."-".$n_ex[1]."-".$n_ex[2]." / ".$n_ex[3].":".$n_ex[4].":".$n_ex[5]." / ".$n_ex[6]; ?></h3>  <a class="btn btn-primary w-25 px-0" href="<?php echo $filename; ?>"> Download Excel </a></span> <br> <?php
        } ?>

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


</main>

<?php include "assets/tem/footer.php" ;?>
<?php 
if (!empty($_SESSION['Emessage'])) { ?>
  <script>
    $(document).ready(function() {
        $(".toast").toast('show');
    });
</script>
<?php unset($_SESSION['Emessage']); } ?>
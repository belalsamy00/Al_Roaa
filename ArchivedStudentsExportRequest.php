<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['CustomerService']) AND !isset($_SESSION['manager']) AND !isset($_SESSION['CustomerServiceManager']) ) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ; 

if (isset($_SESSION['Who']) AND in_array($_SESSION['Who'],array('AbdelRahman','Belal','Hamza','Ramadan','Bedo'))) {
  $Who = $_SESSION['Who'];
}else {
  $Who = "All";
} 
if ($Who =="All") {
  $stmt = "ORDER BY `Timestamp` DESC";
  $execute = array() ;
}else {
  $stmt = "WHERE `Who`= ? ORDER BY `Timestamp` DESC";
  $execute = array($Who) ;
}
$Journal = $con->prepare("SELECT * FROM ArchivedStudentsExport $stmt ");
$Journal->execute($execute);
$Journal_count = $Journal->rowCount();
$JournalArry = $Journal->fetchAll();

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
      <h1>Archived Students Export Page</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Archived Students Export</li>
        </ol>
      </nav>
    </div><!-- Center Page Title -->
    
    <section class="section dashboard">
      <div class="row">

        <div class="col-lg-12">
            <div class="card">
              <h5 class="card-title text-center">Archived Students Export</h5>
              <div class="card-body rtl overflow-auto text-center max-h">
                <table  class="table table-borderless text-center  ">
                  <thead >
                    <tr>
                      <th   class="fw-bold fs-5 text-center" > التاريخ </th>
                      <th   class="fw-bold fs-5 text-center" > الكود </th>
                      <th   class="fw-bold fs-5 text-center" > حالة الطلب </th>
                      <?php if (isset($_SESSION['manager']) OR isset($_SESSION['CustomerServiceManager']) ) { ?>
                      <th   class="fw-bold fs-5 text-center" > إجراء </th>
                      <?php } ?>
                    </tr>
                  </thead>
                  <tbody>
                    <?php  if ($Journal_count == 0) { ?>  <td  class="fw-bold fs-5" colspan="4"> لا توجد طلبات مسجلة </td>  <?php } ?>
                    <?php 
                    if ($Journal_count > 0) {
                      foreach ( $JournalArry as $value) { 
                        $CodeExplode = explode(' ',$value['Code']);
                        $StudentCodeExplode = $CodeExplode[0] ;
                        if ($value['status'] == 2 ) {
                          $href = "search?Code=".$StudentCodeExplode ;
                        }else {
                          $href = "ArchivedStudentsSearch?Code=".$StudentCodeExplode ;;
                        } 
                        ?>

                      <tr>
                      
                        <td class="fw-bold fs-5 text-center"> <?php echo date('Y-m-d', strtotime($value['Timestamp'])) ?> </td>
                        <td class="fw-bold fs-5 text-center"><a href="<?php echo $href ?>"><?php echo $StudentCodeExplode ?></a> </td>
                        <td class="fw-bold fs-5 text-center"> 
                          <?php
                            if ($value['status'] == 1 ) {
                              echo  "<span class= 'badge bg-warning'> جارى المراجعة </span>";
                            }elseif ($value['status'] == 2 ) {
                              echo  "<span class= 'badge bg-success'> مقبول  </span>";
                            }else {
                              echo  "<span class= 'badge bg-danger'>  مرفوض </span>";
                            } 
                          ?>
                        </td>
                        <?php if (isset($_SESSION['manager']) OR isset($_SESSION['CustomerServiceManager']) ) { ?>
                        <td class="fw-bold fs-5 text-center">
                          <div class="btn-group ltr">
                          <?php
                            if ($value['status'] == 1) { ?>
                              <a <?php echo "href='ArchivedStudentsExportRequestApi?Accept=".$value['ID']."'" ?> class="btn btn-success"> قبول </a>
                              <a <?php echo "href='ArchivedStudentsExportRequestApi?Refuse=".$value['ID']."'" ?> onclick="return confirm('هل انت متأكد من الرفض ؟');"  class="btn btn-danger"> رفض </a>
                            <?php }else {
                              echo "-" ;
                            }
                          ?>

                          </div>
                        </td>
                        <?php } ?>
                      </tr>
                      <?php }?>
                    <?php } ?>
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


</main><!-- Center #main -->
<?php include "assets/tem/footer.php" ?>
<?php 
if (!empty($_SESSION['Emessage'])) { ?>
  <script>
    $(document).ready(function() {
        $(".toast").toast('show');
    });
</script>
<?php unset($_SESSION['Emessage']); } ?>
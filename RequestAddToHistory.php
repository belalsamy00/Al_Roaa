<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['Admin']) AND !isset($_SESSION['Suber_Admin']) AND !isset($_SESSION['manager'])) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ; 

if (isset($_GET['All'])) {
  if (date("d") > 25 ) {
    $Start = date("Y-m-25",strtotime("-1 month"));
    $End = date("Y-m-26");
}else {
    $Start = date("Y-m-25",strtotime("-2 month"));
    $End = date("Y-m-26",strtotime("-1 month"));
}
$stmt = " WHERE Timestamp > ? AND  Timestamp < ?";
$execute = array($Start,$End);
}else {
  if (date("d") > 25 ) {
    $Start = date("Y-m-25");
}else {
    $Start = date("Y-m-25",strtotime("-1 month"));
}
$stmt = " WHERE Timestamp > ?";
$execute = array($Start);
}

$stmt_2 = $con->prepare("SELECT * FROM add_to_history_request $stmt ORDER BY `date` DESC ");
$stmt_2->execute($execute);
$T_2= $stmt_2->fetchAll() ;
$count_2 = $stmt_2->rowCount() ;

?>
<main id="main" class="main">
  <div class="container">
    <div class="pagetitle">
      <h1>Reschedule Request Page</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Reschedule Request</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    
    <section class="section dashboard">
      <div class="row ">

        <!-- Recent Sales -->
        <div class="col-sm-12">
          <div class="card recent-sales ">

          <h5 class="card-title text-center">  <a class="  <?php if (!isset($_GET['All'])) {echo "disabled btn btn-secondary";}else{echo " btn btn-dark";} ?>" href="RequestAddToHistory"> الشهر الحالى </a>   <a class="  <?php if (isset($_GET['All'])) {echo "disabled btn btn-secondary";}else{echo " btn btn-dark";} ?>" href="?All"> الشهر السابق </a> </h5>

          <div class="card-body rtl overflow-auto text-center">
            <table  class="table table-borderless text-center  ">
              <thead >
                <tr>
                <th  scope="col">تاريخ الطلب</th>
                <th scope="col">كود المعلم</th>
                <th scope="col">كود الطالب</th>
                <th scope="col"> تاريخ الحصة المطلوبة </th>
                <th scope="col"> مدة الحصة </th>
                <th scope="col"> حالة الحصة </th>
                <th scope="col"> إجراء </th>
                </tr>
              </thead>
              <tbody>
                <?php  if ($count_2 == 0) { ?>  <td  class="fw-bold fs-5" colspan="7"> لا توجد طلبات مسجلة </td>  <?php } ?>
                <?php 
                      if ($count_2 > 0) {
                        foreach ($T_2 as  $value) { ?>
                        
                        <tr>
                          <td> <?php echo $value['Timestamp'] ?> </td>
                          <td> <?php echo $value['T_code'] ?> </td>
                          <td> <?php echo $value['S_code'] ?> </td>
                          <td> <?php echo $value['date'] ?> </td>
                          <td> <?php echo $value['Duration'] ?> </td>
                          <td> <?php echo $value['status'] ?> </td>
                          <td>
                          <?php 
                          if ($value['RequestStatus'] == 1) {
                            ?>
                              <div class="btn-group ltr">
                                <a <?php echo " href='RequestAddToHistoryApi?Accept=".$value['ID']."'" ?> class="btn btn-success">قبول</a>
                                <a <?php echo " href='RequestAddToHistoryApi?Refuse=".$value['ID']."'" ?> class="btn btn-danger">رفض</a>
                              </div> 
                            <?php 
                          }elseif ($value['RequestStatus'] == 2 ) {
                            echo '<span class= "badge bg-success"> مقبول  </span>';
                          }elseif ($value['RequestStatus'] == 3 ) {
                            echo '<span class= "badge bg-danger">  مرفوض </span>';
                          }
                           ?>
                           </td>
                        </tr>
                        <?php 
   
                       }}
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
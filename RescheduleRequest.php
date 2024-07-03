<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['teacher'])) { header('Location: index');  exit; } 
include "assets/tem/header.php" ; 

if (isset($_GET['All'])) {
  if (date("d") > 25 ) {
    $Start = date("Y-m-25",strtotime("-1 month"));
    $End = date("Y-m-26");
}else {
    $Start = date("Y-m-25",strtotime("-2 month"));
    $End = date("Y-m-26",strtotime("-1 month"));
}
$stmt = " WHERE Teacher= ? AND for_one_time > ? AND  for_one_time < ?";
$execute = array($_SESSION['teacher'],$Start,$End);
}else {
  if (date("d") > 25 ) {
    $Start = date("Y-m-25");
}else {
    $Start = date("Y-m-25",strtotime("-1 month"));
}
$stmt = " WHERE Teacher= ? AND for_one_time > ?";
$execute = array($_SESSION['teacher'],$Start);
}

$stmt_1 = $con->prepare("SELECT * FROM class WHERE Teacher= ? AND `status` =? AND `type` =? ORDER BY `Time` ASC  ");
$stmt_1->execute(array($_SESSION['teacher'],'Active',2));
$T_1= $stmt_1->fetchAll() ;
$count = $stmt_1->rowCount() ;

if ($count > 0) {

  foreach ($T_1 as $key => $value) { 
    $search = $value['Student'] ;
    $Active_stmt = $con->prepare("SELECT * FROM students WHERE Code = ? AND `status` =? ");
    $Active_stmt->execute(array("$search",'Active'));
    $Active_count = $Active_stmt->rowCount();
    if ($Active_count == 0) {
      unset($T_1[$key]) ;
    }
  }
 }

$stmt_2 = $con->prepare("SELECT * FROM RescheduleRequest$stmt ORDER BY `date` DESC ");
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
        <div class="col-lg-12 mb-5 d-flex"> 
        <button type="button" class="btn btn-success fs-6 fw-bold  border-0 text-center m-auto d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#rescheduleRequest">طلب جديد  
        <lord-icon
            src="https://cdn.lordicon.com/ejxwvtlg.json"
            trigger="loop-on-hover"
            delay="1000"
            colors="outline:#198754,primary:#08a88a,secondary:#ebe6ef"
            style="width:50px;height:50px">
        </lord-icon>
        </button> 
        </div>

        <!-- Modal -->
        <div class="modal fade" id="rescheduleRequest" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="rescheduleRequestLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="rescheduleRequestLabel">طلب حصة تعويصية</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
              <form  action="RescheduleRequestApi"  method="post" class=" fs-6 fw-bold mt-2 w-100">
                  <div class="mb-3">
                    <label for="recipient-name" class="col-form-label">كود الطالب:</label>
                    <select onchange="Status(this.value)" id="Student"  name="ID"   class="form-control" aria-label=".form-select-lg example"  required='true' aria-describedby="button-addon1 button-addon2">
                      <option     selected disabled value="">اختر كود الطالب</option>
                      <?php 
                      if ($count > 0) {
                        foreach ($T_1 as  $value) { ?>
                          <option value="<?php echo $value['ID'] ?>"   ><?php $Fstudent = explode(' ',$value['Student']); $student = $Fstudent[0]; echo $student." - ".$value['StudentName'] ?></option>
                          <?php }
                       }
                      ?>
                    </select>                  
                  </div>
                  <div class="mb-3">
                    <label for="message-text" class="col-form-label">التاريخ المطلوب:</label>
                    <input onchange="Status(document.getElementById('Student').value)" id="Date" type="date" name="Date" class="form-control" required='true' min="<?php echo date("Y-m-d") ?>">
                  </div>
                  <div class="mb-3">
                    <label for="message-text" class="col-form-label"> تاريخ الحصة الأساسية:</label>
                    <input onchange="Status(document.getElementById('Student').value)" id="MainDate" type="date" name="MainDate" class="form-control" required='true'>
                  </div>
                  <div class="mb-3">
                    <label for="message-text" class="col-form-label">الوقت المطلوب:</label>
                    <input onchange="Status(document.getElementById('Student').value)" id="Time" type="Time" name="Time" class="form-control" required='true'>
                  </div>
                  <div class="modal-footer">
                    <button id="Submit" type="submit" class="btn btn-primary Request-btn" disabled style=" opacity: 0.4 !important; cursor: not-allowed; pointer-events: unset; ">إرسال</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                  </div>
                  <p id="Alert" style=" text-align: center; color: #ff305b; direction: rtl;" > * ادخل جميع البيانات اولا </p>
                </form>
              </div>

            </div>
          </div>
        </div>

        <!-- Recent Sales -->
        <div class="col-sm-12">
          <div class="card recent-sales ">

          <b class=" p-1 text-center "> طلب الحصة التعويضية يتم إرساله لكى تكون الحصة متاحه فى الجدول وتستطيع  تسجيلها فى اليوم الذى تريد </b>
          <b class=" p-1 text-center "> على سبيل المثال هناك حصة سوف تأخذ <strong>( غدا )</strong> <strong>  ( بدلا من امس )أو( بدلا من حصة الأسبوع القادم )للطالب </strong> </b>
          <b class=" p-1 text-center "> حضرتك تقدم الطلب لكى تكون الحصة متاحه فى جدولك غدا وتسطيع تسجيلها </b>
          <h5 class="card-title text-center">  <a class="  <?php if (!isset($_GET['All'])) {echo "disabled btn btn-secondary";}else{echo " btn btn-dark";} ?>" href="RescheduleRequest"> الشهر الحالى </a>   <a class="  <?php if (isset($_GET['All'])) {echo "disabled btn btn-secondary";}else{echo " btn btn-dark";} ?>" href="?All"> الشهر السابق </a> </h5>

          <div class="card-body rtl overflow-auto text-center">
            <table  class="table table-borderless text-center  ">
              <thead >
                <tr>
                <th  scope="col">تاريخ الطلب</th>
                <th scope="col">كود الطالب</th>
                <th scope="col"> التاريخ المطلوب</th>
                <th scope="col"> حالة الطلب</th>
                </tr>
              </thead>
              <tbody>
                <?php  if ($count_2 == 0) { ?>  <td  class="fw-bold fs-5" colspan="4"> لا توجد طلبات مسجلة </td>  <?php } ?>
                <?php 
                      if ($count_2 > 0) {
                        foreach ($T_2 as  $value) { ?>
                        <tr>
                          <td> <?php echo $value['date'] ?> </td>
                          <td> <?php echo $value['Student'] ?> </td>
                          <td> <?php echo $value['for_one_time'] ?> </td>
                          <td> <?php if ($value['status'] == 1) {
                            echo  "<span class= 'badge bg-warning'> جارى المراجعة </span>";
                          }elseif ($value['status'] == 2) {
                            echo  "<span class= 'badge bg-success'> مقبول  </span>";
                          }else {
                            echo  "<span class= 'badge bg-danger'>  مرفوض </span>";
                          }  ?> </td>
                        </tr>
                          <?php }
                       }
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
<script src="RescheduleRequest.js"></script>
<?php include "assets/tem/footer.php" ?>
<?php 
if (!empty($_SESSION['Emessage'])) { ?>
  <script>
    $(document).ready(function() {
        $(".toast").toast('show');
    });
</script>
<?php unset($_SESSION['Emessage']); } ?>
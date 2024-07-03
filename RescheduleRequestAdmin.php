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
$stmt = " WHERE for_one_time > ? AND  for_one_time < ?";
$execute = array($Start,$End);
}else {
  if (date("d") > 25 ) {
    $Start = date("Y-m-25");
}else {
    $Start = date("Y-m-25",strtotime("-1 month"));
}
$stmt = " WHERE for_one_time > ?";
$execute = array($Start);
}

$stmt_2 = $con->prepare("SELECT * FROM RescheduleRequest $stmt ORDER BY `date` DESC ");
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

          <h5 class="card-title text-center">  <a class="  <?php if (!isset($_GET['All'])) {echo "disabled btn btn-secondary";}else{echo " btn btn-dark";} ?>" href="RescheduleRequestAdmin"> الشهر الحالى </a>   <a class="  <?php if (isset($_GET['All'])) {echo "disabled btn btn-secondary";}else{echo " btn btn-dark";} ?>" href="?All"> الشهر السابق </a> </h5>

          <div class="card-body rtl overflow-auto text-center">
            <table  class="table table-borderless text-center  ">
              <thead >
                <tr>
                <th  scope="col">تاريخ الطلب</th>
                <th scope="col">كود المعلم</th>
                <th scope="col">كود الطالب</th>
                <th scope="col"> تاريخ الحصة الأساسية</th>
                <th scope="col"> حالة الحصة الأساسية</th>
                <th scope="col"> التاريخ المطلوب</th>
                <th scope="col"> حالة الطلب</th>
                <th scope="col"> إجراء </th>
                <th scope="col"> تاريخ الإجراء </th>
                <th scope="col">  الإجراء بواسطة </th>
                </tr>
              </thead>
              <tbody>
                <?php  if ($count_2 == 0) { ?>  <td  class="fw-bold fs-5" colspan="6"> لا توجد طلبات مسجلة </td>  <?php } ?>
                <?php 
                      if ($count_2 > 0) {
                        foreach ($T_2 as  $value) { ?>
                        
                        <tr>
                          <td> <?php echo $value['date'] ?> </td>
                          <td> <?php echo $value['Teacher'] ?> </td>
                          <td> <?php echo $value['Student'] ?> </td>
                          <td>  <a href="GetClasses?code=<?php echo $value['Teacher'] ?>&date=<?php echo $value['MainDate'] ?>"> <?php echo $value['MainDate'] ?></a> </td>
                          <td> <?php if ($value['status'] == 1) {
                                $H_stmt = $con->prepare("SELECT * FROM history WHERE  `date`=? AND  S_code =? AND S_name =? AND T_code=?");
                                $H_stmt->execute(array($value['MainDate'],$value['Student'],$value['Name'],$value['Teacher']));
                                $countv = $H_stmt->rowCount();
                                if ($countv > 0) {
                                  echo  "<span class= 'badge bg-danger'> مسجلة بالفعل </span>";
                                }else {
                                  echo  "<span class= 'badge bg-success'>  غير مسجلة </span>";
                                }
                          }else{ echo  "<span > - </span>";} ?> </td>
                          <td> <?php echo $value['for_one_time'] ?> </td>
                          <td> <?php if ($value['status'] == 1) {
                            echo  "<span class= 'badge bg-warning'> جارى المراجعة </span>";
                          }elseif ($value['status'] == 2) {
                            echo  "<span class= 'badge bg-success'> مقبول  </span>";
                          }else {
                            echo  "<span class= 'badge bg-danger'>  مرفوض </span>";
                          }  ?> </td>
                          <td> <?php if ($value['status'] == 1) {
                            ?>
                                <div class="btn-group ltr">
                                <a <?php echo " href='RescheduleRequestApiAdmin?ID=".$value['classID']."&RequestID=".$value['ID']."&Date=".$value['for_one_time']."&Time=".$value['time']."'" ?> class="btn btn-success">قبول</a>
                                <a <?php echo " href='RescheduleRequestApiAdmin?refused&RequestID=".$value['ID']."'" ?> class="btn btn-danger">رفض</a>
                                </div> 
                            <?php 
                          }elseif ($value['status'] == 2) {
                            echo  "-";
                          }else {
                            echo "-" ;
                          }  ?> </td>
                          <td> <?php echo $value['UpdatedAt'] ?> </td>
                          <td> <?php echo $value['UpdatedBy'] ?> </td>
                        </tr>
                        <?php 
                        if ($value['status'] == 1) {
                          $Name = $value['Name'].' - Rescheduled';
                          $type = $con->prepare("SELECT * FROM class WHERE Teacher= ?  AND `one_time` >= ? AND Student =? AND StudentName LIKE ?  AND `type` =?  ");
                          $type->execute(array($value['Teacher'],date("Y-m-d") , $value['Student'] , "%$Name%",1) );
                          $type_count = $type->rowCount();
                          $H_type=$type->fetchall();
                          if ($type_count != 0) {
                            foreach ($H_type as  $value) {
                              ?> <tr> <td  colspan="10" > هناك حصة تعويضية  متاحة للتسجيل يوم <?php echo $value['one_time'] ?> </td> </tr><?php
                            }
                            ?> <tr> <td  colspan="10" ><hr> </td> </tr><?php
                          }else {
                            ?> <tr> <td  colspan="10" ><hr> </td> </tr><?php
                          }
                        }else {
                          ?> <tr> <td  colspan="10" ><hr> </td> </tr><?php
                        }
                         
                            }
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
<?php include "assets/tem/footer.php" ?>
<?php 
if (!empty($_SESSION['Emessage'])) { ?>
  <script>
    $(document).ready(function() {
        $(".toast").toast('show');
    });
</script>
<?php unset($_SESSION['Emessage']); } ?>
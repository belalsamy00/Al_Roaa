<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['teacher'])) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ; 

if (isset($_GET['Start']) AND isset($_GET['End'])) {
  $Start = date("Y-m-d", strtotime($_GET['Start']));
  $End = date("Y-m-d", strtotime($_GET['End']));
}else {
  if (date("d") > 25 ) {
    $Start = date("Y-m-26");
}else {
    $Start = date("Y-m-26",strtotime("-1 month"));
}
if (date("d") > 25 ) {
  $End = date("Y-m-25",strtotime("+1 month"));
}else {
  $End = date("Y-m-25");
}
}
$teacher = $_SESSION['teacher'] ;
$row = array();

$H_stmt = $con->prepare("SELECT * FROM history WHERE  T_code=?   ORDER BY `date`  ASC ");
$H_stmt->execute(array( $teacher));
$count2 = $H_stmt->rowCount();
$H_stmt_2 = $H_stmt->fetchAll();

foreach ($H_stmt_2 as $key => $value) {
  $row[$key."row_2"]['S_code'] = $value['S_code'];
  $row[$key."row_2"]['S_name'] = $value['S_name'];
  $row[$key."row_2"]['date'] = date("Y-m-d", strtotime($value['date']));
  $row[$key."row_2"]['status'] = $value['status'];
  $row[$key."row_2"]['Duration'] = $value['Duration'];
  $row[$key."row_2"]['nots'] = $value['nots'];
}


  foreach ($row as $key => $value) {
    if ($value['date'] < $Start ) {
      unset($row[$key]);
    }
    if ($value['date'] > $End ) {
      unset($row[$key]);
    }
  }

$date = array_column($row, 'date');

array_multisort($date, SORT_DESC, $row);

$AArow =  array_sum(array_column($row, 'Duration')) / 60;
if (isset(array_count_values(array_column($row, 'status'))['حضور'])) {
  $Bcount = array_count_values(array_column($row, 'status'))['حضور'];
}else {
  $Bcount = 0 ;
}  if (isset(array_count_values(array_column($row, 'status'))['غياب'])) {
  $Ccount = array_count_values(array_column($row, 'status'))['غياب'];
}else {
  $Ccount = 0 ;
}
?>
<main id="main" class="main">
  <div class="container">
    <div class="pagetitle">
      <h1>الراتب</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">الرئيسئة</a></li>
          <li class="breadcrumb-item active">الراتب</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    
    <section class="section">         
      <div class="col-lg-12">
          <div class="card">
              <div class="card-body rtl">
              <h5 class="card-title text-center">   </h5> 
                  <table class="table  table-striped  table-bordered text-center table_class" >
                    <thead >
                        <tr >
                        <th scope="col">أجمالى ساعات العمل </th>
                        <th scope="col">عدد الحلقات الحضور </th>
                        <th scope="col">عدد الحلقات الغياب </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style=" font-size: 34px; font-weight: 800;" >
                            <td style=" padding: 8px;"><?php echo $AArow ;?></td>
                            <td style=" padding: 8px;"><?php echo $Bcount ; ?></td>
                            <td style=" padding: 8px;"><?php echo $Ccount ; ?></td>
                        </tr>
                    </tbody>
                </table>
              </div>
          </div>
      </div>


      <div id="myGroup" class="row">
  
  <?php
    if (empty($row)) {
    ?> 
        <div class="alert alert-danger text-center" role="alert"  >
            لا توجد حلقات مسجله
        </div>
    <?php
    }
    foreach($row as $class){ 
      $Fsectionid = explode(' ',$class['S_code']);
      $sectionid = $Fsectionid[0].rand(0,1000);
      ?>
      
      <div class="col-lg-3 m-auto">
        <div class="card">
          <div class="card-body rtl">
          <h5 class="card-title"> <i class="fa-solid fa-user"></i> <?php echo $Fsectionid[0] ; ?> <span>| <?php echo $class['date'] ; ?></span></h5>
              <div >
                <button class="btn btn-outline-primary fs-6 fw-bold w-100 border-0" type="button" data-bs-toggle="collapse" data-bs-target="<?php echo "#".$sectionid ?>" aria-expanded="false" aria-controls="<?php echo $sectionid  ?>"> عرض المزيد </button>
                <div class="collapse" id="<?php echo $sectionid ?>" data-bs-parent="#myGroup">
                <p class="card-details mt-2 mb-0"> <i class="ri-time-line"></i> &nbsp; ( كود الطالب ) &nbsp; <?php echo $Fsectionid[0] ; ?> </p>
                <p class="card-details mt-2 mb-0"> <i class="ri-time-line"></i> &nbsp; (أسم الطالب ) &nbsp;   <?php echo $class['S_name']  ; ?> </p>
                <p class="card-details mt-2 mb-0"> <i class="ri-time-line"></i> &nbsp; (مدة الحلقة ) &nbsp;  <?php echo $class['Duration']  ; ?> </p>
                <p class="card-details mt-2 mb-0"> <i class="ri-time-line"></i> &nbsp; (حالة الحلقة ) &nbsp;  <?php echo $class['status']  ; ?></p>
                <p class="card-details mt-2 mb-0"> <i class="ri-time-line"></i> &nbsp; (تقرير الحلقة ) &nbsp; <p > <?php echo $class['nots'] ; ?></p> </p>
                </div>
              </div>
          </div>
        </div>
      </div>
    <?php }?>
</div>
    
    </section>
  </div>


</main><!-- End #main -->
<?php include "assets/tem/footer.php" ?>
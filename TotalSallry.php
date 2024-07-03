<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if ( !isset($_GET['Code'])) {  header('Location: index');  exit;  } 
if (!isset($_SESSION['Admin']) AND !isset($_SESSION['Suber_Admin']) AND !isset($_SESSION['manager'])) {  header('Location: index');  exit;  } 
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
$teacher = $_GET['Code'] ;
$row = array();

$H_stmt = $con->prepare("SELECT * FROM history WHERE  T_code=?   ORDER BY `Timestamp`  ASC ");
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
              <div class="card-body ">
              <h5 class="card-title text-center">   </h5> 
                  <form id="form-2"class="mt-2 w-50 m-auto" method="GET" action="">
                    <div class="input-group mb-3">
                      <span class="input-group-text" id="basic-addon1">Code</span>
                      <input type="text" class="form-control" value="<?php echo $_GET['Code'] ;?>"  name="Code" aria-label="Code" aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group mb-3">
                      <span class="input-group-text" id="basic-addon1">From</span>
                      <input type="date" class="form-control" value="<?php echo $Start ;?>"  name="Start" aria-label="Start" aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group mb-3">
                      <span class="input-group-text" id="basic-addon1">To</span>
                      <input type="date" class="form-control" value="<?php echo $End ;?>" name="End" aria-label="End" aria-describedby="basic-addon1">
                    </div>
                    <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 " >عرض مدة محددة</button>
                  </form>
              </div>
          </div>
      </div>
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
    }?>
      
      <div class="col-sm-12">
        <div class="card recent-sales">
          <h5 class="card-title text-center">   </h5> 
          <div class="card-body rtl text-center overflow-auto">
            <table id="DataTable" class="table table-sm display table-borderless text-center DataTable ">
              <thead >
              <tr>
                <th  scope="col">Date</th>
              <th  scope="col">Code</th>
              <th  scope="col">Name</th>
              <th scope="col">status</th>
              <th scope="col">Duration</th>
              <th scope="col">note</th>
              </tr>
              </thead>
              <tbody>

              <?php 
              foreach ($row as $class ) { ?>
              <tr>
              <td class="fw-bold fs-5"><?php echo $class['date'] ?></td>
              <th class="fw-bold fs-5"><?php echo $class['S_code'] ?></th>
              <td class="fw-bold fs-5"> <?php echo $class['S_name'] ?></td>
              <td class="fw-bold fs-5"><?php echo $class['status'] ?></td>
              <td class="fw-bold fs-5"><?php echo $class['Duration'] ?></td>
              <td class="fw-bold fs-5"><?php echo $class['nots'] ?></td>
              </tr>
              <?php }
              ?>
              </tbody>
            </table>
              </div>
          </div>
        </div>
      </div>
</div>
    
    </section>
  </div>


</main><!-- End #main -->
<?php include "assets/tem/footer.php" ?>
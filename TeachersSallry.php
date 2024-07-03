<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if ( !isset($_SESSION['manager']) ) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ; 
if (isset($_GET['Start']) AND isset($_GET['End'])) {
  $current_month = $_GET['Start'];
  $Next_month = $_GET['End'];
}else {
  if (date("d") > 25 ) {
    $current_month = date("Y-m-26");
  }else {
    $current_month = date("Y-m-26",strtotime("-1 month"));
  }
  if (date("d") > 25 ) {
    $Next_month = date("Y-m-25",strtotime("+1 month"));
  }else {
    $Next_month = date("Y-m-25");
  }
}
$Total_sallry_stmt = $con->prepare("SELECT * FROM history WHERE `date` >= ? AND  `date` <= ? ORDER BY `T_code` ASC ");
$Total_sallry_stmt->execute(array($current_month,$Next_month));
$Arow = $Total_sallry_stmt->fetchAll();
foreach ($Arow as $key => $value) {
  $SallryData[$key]['Code'] = $value['T_code'];
  $SallryData[$key]['date'] = date("Y-m-d", strtotime($value['date']));
  $SallryData[$key]['Duration'] = $value['Duration'];
  if ($value['status'] == 'حضور' ) {
    $SallryData[$key]['status_1'] = 1;
    $SallryData[$key]['status_2'] = 0;
  }else {
    $SallryData[$key]['status_1'] = 0;
    $SallryData[$key]['status_2'] = 1;
  }

}

$sallry =array();
$Teachers_Code =array("T10"	,	"T11"	,	"T12"	,	"T13"	,	"T14"	,	"T15"	,	"T16"	,	"T17"	,	"T18"	,	"T19"	,	"T20"	,	"T21"	,	"T22"	,	"T23"	,	"T24"	,	'T25'	,	'T26'	,	'T27'	,	'T28'	,	'T29'	,	'T30'	,	'T31'	,	'T32'	,	'T33'	,	'T34'	,	'T35'	,	'T36'	,	'T37'	,	'T38'	,	'T39'	,	'T40'	,	'T41'	,	'T42'	,	'T43'	,	'T44'	,	'T45'	,	'T46'	,	'T47'	,	'T48'	,	'T49'	,	
'T50'	,	"T51"	,	"T52"	,	'T53'	,	'T54'	,	'T55'	,	'T56'	,	'T57'	,	'T58'	,	'T59'	,	'T60'	,	'T61'	,	'T62'	,	'T63'	,	'T64'	,	'T65'	,	'T66'	,	'T67'	,	'T68'	,	'T69'	,	'T70'	,	'T71'	,	'T72'	,	'T73'	,	'T74'	,	'T75'	,	'T76'	,	'T77'	,	'T78'	,	'T79'	,	'T80'	,	'T81'	,	'T82'	,	'T83'	,	'T84'	,	'T85'	,	'T86'	,	'T87'	,	'T88'	,	'T89'	,	'T90'	,	'T91'	,	'T92'	,	'T93'	,	'T94'	,	'T95'	,	'T96'	,	'T97'	,	'T98'	,	"T99");
foreach ($SallryData as $key => $value) {
  if (!array_key_exists( $value['Code'] , $sallry))
  {
      $sallry[$value['Code']]['Code'] = $value['Code']; 
      $sallry[$value['Code']]['Duration'] = $value['Duration']; 
      $sallry[$value['Code']]['status_1'] = $value['status_1']; 
      $sallry[$value['Code']]['status_2']= $value['status_2']; 
  }else {
    $sallry[$value['Code']]['Duration']+= $value['Duration']; 
    $sallry[$value['Code']]['status_1']+= $value['status_1']; 
    $sallry[$value['Code']]['status_2']+= $value['status_2']; 
  }
}
foreach ($Teachers_Code as $key => $value) {
  if (!array_key_exists($value , $sallry)) {
    $sallry[$value]['Code'] = $value ; 
    $sallry[$value]['Duration'] = 0; 
    $sallry[$value]['status_1'] = 0; 
    $sallry[$value]['status_2'] = 0; 
  }
}
?> 
<br>
<br>
<br>

<?php

  
  $AArow =  array_sum(array_column($sallry, 'Duration'))/60;
  $Bcount = array_sum(array_column($sallry, 'status_1'));
  $Ccount = array_sum(array_column($sallry, 'status_2'));

  ?>
<main id="main" class="main">
  <div class="container">
    <div class="pagetitle">
      <h1>Teachers Sallry</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Teachers Sallry</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    
    <section class="section">
      <div class="row">
        <div class="col-lg-6 m-auto">
          <div class="card">
              <div class="card-body ">
              <h5 class="card-title text-center">   </h5> 
                  <form id="form-2"class="mt-2 w-50 m-auto" method="GET" action="">
                    <div class="input-group mb-3">
                      <span class="input-group-text" id="basic-addon1">From</span>
                      <input type="date" class="form-control" value="<?php echo $current_month ;?>"  name="Start" aria-label="Start" aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group mb-3">
                      <span class="input-group-text" id="basic-addon1">To</span>
                      <input type="date" class="form-control" value="<?php echo $Next_month ;?>" name="End" aria-label="End" aria-describedby="basic-addon1">
                    </div>
                    <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 " >عرض مدة محددة</button>
                  </form>
              </div>
          </div>
        </div>
      </div>
      <div class="row">
          <div class="col-lg-6 m-auto">
            <div class="col-lg-12">
              <div class="card">
                  <div class="card-body rtl overflow-auto">
                  <h5 class="card-title text-center">   </h5> 
                      <table class="table  table-striped  table-bordered text-center table_class" >
                        <thead >
                            <tr >
                            <th  class="fw-bold fs-5 text-center" scope="col">أجمالى ساعات العمل </th>
                            <th  class="fw-bold fs-5 text-center" scope="col">عدد الحلقات الحضور </th>
                            <th  class="fw-bold fs-5 text-center" scope="col">عدد الحلقات الغياب </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style=" font-size: 34px; font-weight: 800;" >
                                <td  class="fw-bold fs-5 text-center" ><?php echo $AArow ;?></td>
                                <td  class="fw-bold fs-5 text-center" ><?php echo $Bcount ; ?></td>
                                <td  class="fw-bold fs-5 text-center" ><?php echo $Ccount ; ?></td>
                            </tr>
                        </tbody>
                    </table>
                  </div>
              </div>
          </div>
          <div class="col-lg-12">
            <div class="card recent-sales ">
  
            <h5 class="card-title   text-center">  ساعات عمل المعلمين</h5>
  
            <div class="card-body rtl text-center">
            <table id="DataTable" class="table table-borderless text-center DataTable">
            <thead >
            <tr>
            <th  class="fw-bold fs-5 text-end" scope="col">Code</th>
            <th class="fw-bold fs-5 text-end" scope="col">Total Hours</th>
            </tr>
            </thead>
            <tbody>
  
            <?php 
            foreach ($sallry as $key => $sallry_value) { ?>
            <tr>
            <td class="fw-bold fs-5 text-end" > <a href="<?php echo "TotalSallry?Code=".$sallry_value['Code'] ?>"><?php echo $sallry_value['Code'] ?></a></td>
            <td class="fw-bold fs-5 text-end" ><?php echo $sallry_value['Duration']/60 ?></td>
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
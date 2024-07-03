<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['Admin']) AND !isset($_SESSION['Suber_Admin']) AND !isset($_SESSION['manager']) AND !isset($_SESSION['Supervising_manager']) AND !isset($_SESSION['supervisor'])) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ;
 include "DayApi.php" ;
if (isset($_POST['Get-Classes'])) {header("location: GetClasses?code=".$_POST['code']."&date=".$_POST['date']);exit;} 
if (isset($_POST['Get-Schedule'])) {header("location: GetSchedule?code=".$_POST['code']);exit;}

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

// -------------------
if (isset($_SESSION['manager']) OR isset($_SESSION['Suber_Admin']) OR isset($_SESSION['Admin']) ) {
  $Acount = count($Meetings);
  if (isset(array_count_values(array_column($Meetings, 'Nots'))['1'])) {
      $Bcount = array_count_values(array_column($Meetings, 'Nots'))['1'];
  }else {
      $Bcount = 0 ;
  }
  if (isset(array_count_values(array_column($Meetings, 'Nots'))['0'])) {
      $Ccount = array_count_values(array_column($Meetings, 'Nots'))['0'];
  }else {
      $Ccount = 0 ;
  }
  if (isset(array_count_values(array_column($Meetings, 'Cancel'))['1'])) {
      $Dcount = array_count_values(array_column($Meetings, 'Cancel'))['1'];
  }else {
      $Dcount = 0 ;
  }
  if (isset(array_count_values(array_column($Meetings, 'Type'))['2'])) {
      $Ecount = array_count_values(array_column($Meetings, 'Type'))['2'];
  }else {
      $Ecount = 0 ;
  }
  if (isset(array_count_values(array_column($Meetings, 'Type'))['1'])) {
      $Fcount = array_count_values(array_column($Meetings, 'Type'))['1'];
  }else {
      $Fcount = 0 ;
  }

  
}

$Actions = array();
$Action_code_stmt = $con->prepare("SELECT  *  FROM EvaluationTeam WHERE  `Date`=? ORDER BY `Date`  ASC ");
$Action_code_stmt->execute(array(date("Y-m-d")));
$Action= $Action_code_stmt->fetchAll();
foreach ($Action as $key => $Act ) {
  if ($Act['type']==1)
  {
    $Actions[$key]['performance']= $Act['performance'];
    
  }else {
    if ($Act['Status'] == "+") {
      $performance =" بونص ".$Act['Mark'];
    }else {
      $performance =" خصم ".$Act['Mark'];
    }
    $Actions[$key]['performance']= $performance;
  }
  $Actions[$key]['Code'] = $Act['Code'];
  $Actions[$key]['Dis'] = $Act['Dis'];
  $Actions[$key]['Who'] = $Act['Who'];

}
?>
<main id="main" class="main">
  <div class="container" id="myGroup">
    <div class="pagetitle">
    <h1>Management Home Page</h1>
    <nav>
    <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="index">Home</a></li>
    </ol>
    </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">

      <div class="row ">
        <?php if (isset($_SESSION['manager']) OR isset($_SESSION['Suber_Admin']) ) {?>
          <div class="col-sm-12 my-2">
            <div class="card h-100">
              <div class="row">
                  
                  <h5 class="card-title text-center">   إدارة حلقات اليوم </h5>
                  <!-- Revenue Card -->
                  <div class="col-12">
                    <div class="">
                        <div class="card-body col-12 col-lg-6 my-2 m-auto">
                        <h5 class="card-title text-center">   </h5> 
                            <form id="form-2"class="mt-2 w-100 m-auto" method="GET" action="">
                              <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Day</span>
                                <input type="date" class="form-control" value="<?php echo $date_now ;?>"  name="date" aria-label="Start" aria-describedby="basic-addon1">
                                <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2  " >عرض يوم محدد </button>
                              </div>
                            </form>
                        </div>
                    </div>
                  </div>
                  <div class="col-sm-2 col-6">
                    <div class=" info-card sales-card">
                      <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <h5 class="card-title text-center"> إجمالى الحلقات </h5>
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                          <h6 class="text-center"><?php echo $Acount ; ?></h6>
                        </div>
                      </div>
                    </div>
                  </div><!-- End Revenue Card -->
                  <!-- Revenue Card -->
                  <div class="col-sm-2 col-6">
                    <div class=" info-card sales-card">
                      <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <h5 class="card-title text-center">   الأساسية </h5>
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                          <h6 class="text-center"><?php echo $Ecount ; ?></h6>
                        </div>
                      </div>
                    </div>
                  </div><!-- End Revenue Card -->
                  <!-- Revenue Card -->
                  <div class="col-sm-2 col-6">
                    <div class=" info-card sales-card">
                      <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <h5 class="card-title text-center">  التعويضية </h5>
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                          <h6 class="text-center"><?php echo $Fcount ; ?></h6>
                        </div>
                      </div>
                    </div>
                  </div><!-- End Revenue Card -->
                  <!-- Revenue Card -->
                  <div class="col-sm-2 col-6">
                    <div class=" info-card revenue-card">
                      <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <h5 class="card-title text-center">   المسجله </h5>
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                          <h6 class="text-center"><?php echo $Bcount ; ?></h6>
                        </div>
                      </div>
                    </div>
                  </div><!-- End Revenue Card -->
                  <!-- Revenue Card -->
                  <div class="col-sm-2 col-6">
                    <div class=" info-card customers-card">
                      <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <h5 class="card-title text-center">   الغير مسجله </h5>
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                          <h6 class="text-center"><?php echo $Ccount ; ?></h6>
                        </div>
                      </div>
                    </div>
                  </div><!-- End Revenue Card -->
                  <!-- Revenue Card -->
                  <div class="col-sm-2 col-6">
                    <div class=" info-card customers-card">
                      <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <h5 class="card-title text-center">   الملغاه </h5>
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                          <h6 class="text-center"><?php echo $Dcount ; ?></h6>
                        </div>
                      </div>
                    </div>
                  </div><!-- End Revenue Card -->

              </div>
            </div>
          </div>
        <?php } ?>

          <?php if (isset($_SESSION['manager']) OR isset($_SESSION['Suber_Admin']) OR isset($_SESSION['Admin']) OR isset($_SESSION['supervisor'])) {  
          include "EvaluationTeamTeachersDailyStatistics.php" ;
          } ?>

        <?php if (isset($_SESSION['manager']) OR isset($_SESSION['Suber_Admin'])) {  ?>
          <div class="col-lg-12  my-2">
            <div class="card h-100">

              <?php if (isset($_SESSION['manager'])) { ?>
                <h5 class="card-title text-center">تقيمات  اليوم</h5>
                <p class="text-muted  text-center">لقد ادخلت انت والفربق التقيمات الآتيه اليوم</p>
              <?php }else { ?>
                <h5 class="card-title text-center">تقيماتك خلال اليوم</h5>
                <p class="text-muted  text-center">لقد ادخلت التقيمات الآتيه اليوم</p>
              <?php }?>



              <div class="card-body max-h">
                <?php 
                if (empty($Actions)) {
                ?> 
                <p class="fw-bold fs-5 rtl"> لا توجد تقيمات مسجلة بعد </p> 
                <?php
                }
                foreach ($Actions as  $value) { 
                  if (isset($_SESSION['manager'])) { ?>
                    <p class="fw-bold fs-5 rtl col-12 overflow-auto"> <?php echo " "." <span class='badge bg-success overflow-auto col-12 col-lg-4'>".$value['Who']."</span> <span class='badge bg-primary overflow-auto col-12 col-lg-4'>".$value['Code']."</span> <span class='badge bg-secondary overflow-auto col-12'>".$value['Dis']." - ".$value['performance']."</span>" ?> </p>

                  <?php }else { ?>
                    <p class="fw-bold fs-5 rtl col-12 overflow-auto"> <?php echo " "."<span class='badge bg-primary overflow-auto col-12 col-lg-4'>".$value['Code']."</span> <span class='badge bg-secondary overflow-auto col-12 col-lg-6 my-2'>".$value['Dis']." - ".$value['performance']."</span>" ?> </p>

                  <?php }?>
                <?php }
                ?>
              </div>

            </div>
          </div>
        <?php } ?>

        <?php if (isset($_SESSION['manager'])){ ?>
            <div class="col-lg-6 my-2">
            <div class="card h-100">
                <div class="card-body ">
                <h5 class="card-title text-center">ساعات عمل المعلم</h5> 
                    <form id="form-2"class="mt-2  m-auto" method="GET" action="TotalSallry">
                      <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Code</span>
                        <input type="text" class="form-control" value=""  name="Code" aria-label="Code" aria-describedby="basic-addon1">
                      </div>
                      <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">From</span>
                        <input type="date" class="form-control" value=""  name="Start" aria-label="Start" aria-describedby="basic-addon1">
                      </div>
                      <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">To</span>
                        <input type="date" class="form-control" value="" name="End" aria-label="End" aria-describedby="basic-addon1">
                      </div>
                      <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 " >عرض مدة محددة</button>
                    </form>
                </div>
            </div>
          </div>
        <?php  } ?>

        <!-- تقيمك الشهرى -->
        <?php if (isset($_SESSION['Admin'])) {  
          include "EvaluationAdminsInclude.php" ;
          } ?>
        <?php if (isset($_SESSION['supervisor'])) {  
          include "EvaluationSupervisorInclude.php" ;
          } ?>


        <?php if (!isset($_SESSION['Admin']) AND !isset($_SESSION['supervisor']) ){ ?>        
          <div class="col-lg-6 my-2  ">

          <div class="card h-100">
          <div class="card-body">
          <h5 class="card-title text-center"> التقارير </h5> 

          <form class="mt-2" method="GET" action="EvaluationAddTeam">

          <select      id="code" name="code" class="form-control form-select form-select-lg  mt-2" aria-label=".form-select-lg example"required='true'>
          <option     selected disabled value="">اختر كود</option>
          <?php foreach ($cods as $cod ) { 
            if ($cod['Code'] != "MA-1") { ?>
          <option value="<?php echo $cod['Code'] ?>"><?php echo $cod['Code']." / ".$cod['Name'] ?></option>
          <?php } ?>
          <?php } ?>
          </select>

          <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 " type="submit"> تأكيد </button>
          </form>
          </div>
          </div>

          </div>
        <?php } ?>

        <?php if (isset($_SESSION['manager']) OR isset($_SESSION['Supervising_manager']) OR isset($_SESSION['supervisor'])){ ?>        
          <div class="col-lg-6 my-2  ">

          <div class="card h-100">
          <div class="card-body">
          <h5 class="card-title text-center"> تقييم حلقة </h5> 

          <form class="mt-2" method="GET" action="EvaluationAddTeacher">
          <?php if (isset($_SESSION['manager']) OR isset($_SESSION['Suber_Admin'])) { ?> 
            <input id="form-date" class="form-control text-center mt-2" type="date" name="date" required='true'>
          <?php } ?>
          <select      id="code" name="code" class="form-control form-select form-select-lg  mt-2" aria-label=".form-select-lg example"required='true'>
          <option     selected disabled value="">اختر كود</option>
          <?php foreach ($cods_2 as $cod ) { ?>
          <option     value="<?php echo $cod['Code'] ?>"><?php echo $cod['Code']." / ".$cod['Name'] ?></option>
          <?php } ?>
          </select>

          <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 " type="submit"> تأكيد </button>
          </form>
          </div>
          </div>

          </div>
        <?php } ?>

        <?php if (isset($_SESSION['manager']) OR isset($_SESSION['Suber_Admin'])OR isset($_SESSION['Admin'])  OR isset($_SESSION['Supervising_manager']) OR isset($_SESSION['supervisor'])){ ?>        
          <div class="col-lg-6 my-2  ">

          <div class="card h-100">
          <div class="card-body">
          <h5 class="card-title text-center"> الحصص المسجله </h5> 

          <form class="mt-2" method="GET" action="EvaluationAddTeachercontinue">
            <div class="input-group mb-3">
              <input type="hidden" class="form-control" value="1"  name="All" aria-label="Code" aria-describedby="basic-addon1">
            </div>
            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1">Date</span>
              <input type="date" class="form-control" name="date" aria-label="Start" aria-describedby="basic-addon1">
            </div>
          <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 " type="submit"> تأكيد </button>
          </form>
          </div>
          </div>

          </div>
        <?php } ?>





        <?php if (isset($_SESSION['manager']) OR isset($_SESSION['Suber_Admin']) OR isset($_SESSION['Admin'])) {  ?>

        <div class="col-lg-6 my-2 ">

          <div class="card h-100">
          <div class="card-body">
          <h5 class="card-title text-center"> المعلمين </h5> 
          <form id="form-2"class="mt-2" method="POST" action="">
          <input id="form-code" class="form-control text-center  mt-2" type="text" name="code" placeholder="كود المعلم" required='true'>
          <input id="form-date" class="form-control text-center mt-2" type="date" name="date" required='true'>
          <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 " type="submit" name="Get-Classes">ادارة حصص المعلم  </button>
          </form>
          </div>

          </div>
        </div>


        <?php  } ?>
        
        <div class="col-lg-6 my-2 ">

          <div class="card h-100">
          <div class="card-body">
          <h5 class="card-title text-center"> جداول المعلمين </h5> 
          <form   class="mt-2" method="POST" action="">
          <input  class="form-control text-center  mt-2" type="text" name="code" placeholder="كود المعلم" required='true'>
          <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 " name="Get-Schedule" >عرض جدول  المعلم</button>
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
  <script>
  function submit_form(textarea,form,back,Do,Cancel,spinner) {

  }
  function incrementValue()
  {
  var value = parseInt(document.getElementById('number').value, 10);
  value = isNaN(value) ? 0 : value;
  value++;
  document.getElementById('number').value = value + 4;
  }
  function decrementValue()
  {
  var value = parseInt(document.getElementById('number').value, 10);
  if (value >= 5) {
  value = isNaN(value) ? 0 : value;
  value--;
  document.getElementById('number').value = value - 4;
  }

  }
  </script>

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
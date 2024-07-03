<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['CustomerService']) AND !isset($_SESSION['manager']) AND !isset($_SESSION['CustomerServiceManager']) ) {  header('Location: index');  exit;  } 
if (isset($_POST['Journal'])) {header("location: CustomerServiceJournal?code=".$_POST['code']);exit;} 
if (isset($_POST['RenewalDate'])) {header("location: CustomerServiceRenewalDate?code=".$_POST['code']);exit;} 
if (isset($_POST['CustomerServiceSettlement'])) {header("location: CustomerServiceSettlement?code=".$_POST['code']);exit;} 
include "assets/tem/header.php" ; 
require_once "functions/CS-functions.php";
require_once "functions/Approval.php";
require_once "functions/GetCount.php";
require_once "functions/Temporary.php";
Approval();
Temporary();
if (isset($_POST['Journal'])) {header("location: CustomerServiceJournal?code=".$_POST['code']);exit;} 
if (isset($_POST['RenewalDate'])) {header("location: CustomerServiceRenewalDate?code=".$_POST['code']);exit;} 
if (isset($_POST['CustomerServiceSettlement'])) {header("location: CustomerServiceSettlement?code=".$_POST['code']);exit;} 
if (isset($_GET['Who']) AND in_array($_GET['Who'],array('AbdelRahman','Belal','Hamza','Ramadan','Bedo','All'))) {
  $_SESSION['Who'] = $_GET['Who'];
} 
if (isset($_SESSION['Who']) AND in_array($_SESSION['Who'],array('AbdelRahman','Belal','Hamza','Ramadan','Bedo'))) {
  $Who = $_SESSION['Who'];
}else {
  $Who = "All";
}

 if (GetStudentscount($Who) == 0) {
  $totalStudents = 0;
 }else {
  $totalStudents = number_format((count(GetRenewal($Who)) / GetStudentscount($Who)) * 100 , 0);
 }
 ?>
<main id="main" class="main">
  <div class="container">
    <div class="pagetitle">
      <h1> </h1>
      <nav>
        <ol class="breadcrumb">
          <li class="">        
            <a class="nav-link nav-profile d-flex flex-column align-items-center pe-2" href="SearchAll">
              <lord-icon
                src="https://cdn.lordicon.com/osbjlbsb.json"
                trigger="hover"
                style="width:90px;height:90px">
              </lord-icon>
              <span class="d-block "> جميع الطلاب </span>
            </a><!-- End Dashboard Iamge Icon -->
          </li>
          <li class="">        
            <a class="nav-link nav-profile d-flex flex-column align-items-center pe-2" href="ArchivedStudentsSearchAll">
              <lord-icon
                src="https://cdn.lordicon.com/yedgackm.json"
                trigger="hover"
                style="width:90px;height:90px">
              </lord-icon>
              <span class="d-block "> أرشيف الطلاب </span>
            </a><!-- End Dashboard Iamge Icon -->
          </li>
          <li class="">        
            <a class="nav-link nav-profile d-flex flex-column align-items-center pe-2" href="CustomerServiceJournalView">
              <lord-icon
                src="https://cdn.lordicon.com/nrzqxhfu.json"
                trigger="hover"
                style="width:90px;height:90px">
              </lord-icon>
              <span class="d-block "> الجورنال </span>
            </a><!-- End Dashboard Iamge Icon -->
          </li>
          <li class="">        
            <a class="nav-link nav-profile d-flex flex-column align-items-center pe-2" href="CustomerServiceSettlementRequest">
              <lord-icon
                src="https://cdn.lordicon.com/qtqvorle.json"
                trigger="hover"
                style="width:90px;height:90px">
              </lord-icon>
              <span class="d-block "> طلبات التسوية </span>
            </a><!-- End Dashboard Iamge Icon -->
          </li>
          <li class="">        
            <a class="nav-link nav-profile d-flex flex-column align-items-center pe-2" href="ArchivedStudentsExportRequest">
              <lord-icon
                src="https://cdn.lordicon.com/ujxzdfjx.json"
                trigger="hover"
                colors="primary:#121331,secondary:#66d7ee"
                style="width:90px;height:90px">
              </lord-icon>
              <span class="d-block "> طلبات الأرشيف </span>
            </a><!-- End Dashboard Iamge Icon -->
          </li>
        </ol>
      </nav>
    </div><!-- Center Page Title -->
    
    <section class="section dashboard">
      <div class="row">
      <?php if (isset($_SESSION['manager']) OR isset($_SESSION['CustomerServiceManager'])) { ?>
        <div class="col-lg-6 m-auto  my-2">
            <div class="card  h-100">
                <div class="card-body col-12 col-lg-6 m-auto">
                    <h5 class="card-title text-center"> </h5> 
                    <form id="form-2"class="mt-2 w-100 m-auto" method="GET" action="">
                    <select onchange="this.form.submit()" name="Who" class="form-select" aria-label="Default select example">
                      <option <?php if($Who == "Hamza" )      {echo "selected";} ?> value="Hamza">حمزة</option>
                      <option <?php if($Who == "Belal" )      {echo "selected";} ?> value="Belal">بلال</option>
                      <option <?php if($Who == "Ramadan" )    {echo "selected";} ?> value="Ramadan">رمضان</option>
                      <option <?php if($Who == "AbdelRahman" ){echo "selected";} ?> value="AbdelRahman">عبد الرحمن</option>
                      <option <?php if($Who == "Bedo" ){echo "selected";} ?> value="Bedo"> بيدو </option>
                      <option <?php if($Who == "All" )        {echo "selected";} ?> value="All">الجميع</option>
                    </select>
                    <?php if (isset($_GET['Start'])) { ?>
                      <input type="hidden" name="Start" value="<?php echo $_GET['Start']; ?>">
                    <?php } ?>
                    </form>
                  </div>
                </div>
              </div>
      <?php } ?>
      </div>
      <div class="row" id="myGroup">


        <div class="col-lg-6  my-2">
          <div class="card h-100 recent-sales">
            <h5 class="card-title text-center"> تفاصيل الطلاب </h5>
            <div class="card-body w-100 text-center">
              <div  id="radialBarChart2"></div>
              <div>
                <p class="fw-bold fs-5 text-end">  <?php echo " هناك "."<span class='text-danger'>".$totalStudents." % "."</span>"." من الطلاب حان موعد دفعهم "."<span class='text-danger'>". count(GetRenewal($Who))."</span>" . " طالب من أصل  "."<span class='text-danger'>". GetStudentsActivecount($Who)."</span>" ." طالب " ?> </p>
                <p class="fw-bold fs-5 text-end">  <?php echo  " إجمالى الطلاب "."<span class='text-danger'>". GetStudentscount($Who)."</span>" ." طالب "  ?> </p>
                <p class="fw-bold fs-5 text-end">  <?php echo   " الطلاب المفعلين " ."<span class='text-danger'>". GetStudentsActivecount($Who)."</span>" . " طالب " ?> </p>
                <p class="fw-bold fs-5 text-end">  <?php echo   " الطلاب الغير مفعلين " ."<span class='text-danger'>" . GetStudentsCancelcount($Who)."</span>" . " طالب " ?> </p>
              </div>
              <script>
                document.addEventListener("DOMContentLoaded", () => {
                new ApexCharts(document.querySelector("#radialBarChart2"), {
                chart: {
                height: 280,
                type: "radialBar",
                },

                series: [<?php echo $totalStudents; ?>],
                colors: ["#198754"],
                plotOptions: {
                radialBar: {
                hollow: {
                margin: 0,
                size: "70%",
                background: "#fff"
                },
                track: {
                dropShadow: {
                enabled: true,
                top: 2,
                left: 0,
                blur: 4,
                opacity: 0.15
                }
                },
                dataLabels: {
                name: {
                offsetY: 10,
                color: "#111",
                fontSize: "30px"
                },
                value: {
                color: "#111",
                fontSize: "30px",
                show: false
                }
                }
                }
                },
                fill: {
                type: "gradient",
                gradient: {
                shade: "dark",
                type: "vertical",
                gradientToColors: ["#198754"],
                stops: [0, 80]
                }
                },
                stroke: {
                lineCap: "round"
                },
                labels: [` <?php echo $totalStudents." % "; ?>`]
                }).render();
                });
              </script>
            </div>
          </div>
        </div>
        <div class="col-lg-6  my-2">

        <div id="s" class="card  h-100">
          <div class="card-body w-100">
            <h5 class="card-title text-center"> الطلاب </h5> 
            <form  class="mt-2" method="POST"  action="">
              <input class="form-control text-center  mt-2" type="text" name="code" placeholder="كود الطالب" title="ادخل كود الطالب" required='true'>
              <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 " type="submit" name="Journal">  أدخال دفع جديد  </button>
              <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 " type="submit" name="RenewalDate">  تغير موعد تجديد   </button>
              <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 " type="submit" name="CustomerServiceSettlement">  طلب تسوية الحساب   </button>
            </form>
            <?php 
            if (!empty($_SESSION['message'])) { ?>
            <div class="text-center alert alert-danger mt-2" role="alert">
              <p>من فضلك تأكد من الكود</p>
              <p>اكتب الكود فقط بدون الأسم</p>
            </div>
            <?php unset($_SESSION['message']); } ?>
          </div>
        </div>
        </div>

        <div class="col-lg-6  my-2">
          <!-- News & Updates Traffic -->
          <div class="card  h-100">

            <div class="card-body w-100">
              <h5 class="card-title"> Updates </h5>
              <div class="news rtl">

              <?php if (count(GetNotExceedingInDate($Who)) > 0) {  ?>
                <div class=" d-flex align-items-center mb-2 btn btn-outline-primary text-end border-0"onclick="window.location='CS-GetNotExceedingInDate';" role="button" >
                <div class="col-3 col-lg-2 d-flex align-items-center m-1"><span class="fs-1 fw-bold text-center  w-100"><?php echo count(GetNotExceedingInDate($Who)); ?></span></div>
                  <div class="col-9 col-lg-10 d-flex flex-column justify-items-center">
                    <h4 class="w-100"> طالب بحاجه الى تعديل تاريخ التجديد  </h4>
                    <p class="m-0"> المطلوب: إما تعديل موعد التجديد على السيستم او مراجعة عدد الحصص المسجلة عليهم </p>
                  </div>
                </div>
                <?php  } ?>

                <?php if (GetStudentsCancelcount($Who) > 0) {  ?>
                <div class=" d-flex align-items-center mb-2 btn btn-outline-primary text-end border-0"onclick="window.location='CS-GetStudentsCancel';" role="button">
                <div class="col-3 col-lg-2 d-flex align-items-center m-1"><span class="fs-1 fw-bold text-center  w-100"><?php echo   GetStudentsCancelcount($Who); ?></span></div>
                  <div class="col-9 col-lg-10 d-flex flex-column justify-items-center">
                    <h4 class="w-100"> طالب كانسل </h4>
                    <p class="m-0"> المطلوب: مراجعتهم لحذف بيانات المتغيبيين منذ زمن </p>
                  </div>
                </div>
                <?php  } ?>


                <div id="GetTrail" style="display: none" class=" align-items-center mb-2 btn btn-outline-primary text-end border-0"onclick="window.location='CS-GetTrail';" role="button">
                <div class="col-3 col-lg-2 d-flex align-items-center m-1"><span class="fs-1 fw-bold text-center  w-100 "id='GetTrailspan'></span></div>
                  <div class="col-9 col-lg-10 d-flex flex-column justify-items-center">
                    <h4 class="w-100"> طالب ليس لديهم عمليات دفع ( تجريبي )  </h4>
                    <p class="m-0"> المطلوب: متابعتهم وتحصيل الدفع </p>
                  </div>
                </div>
                <div id="GetTrailplaceholder" style=" ">
                <p class="card-text placeholder-glow  col-12">
                  <span class="placeholder bg-primary col-7"></span>
                  <span class="placeholder bg-primary col-4"></span>
                  <span class="placeholder bg-primary col-4"></span>
                  <span class="placeholder bg-primary col-6"></span>
                  <span class="placeholder bg-primary col-8"></span>
                </p>
                </div>


                <?php if (count(GetNotInRenewal($Who)) > 0) {  ?>
                <div class=" d-flex align-items-center mb-2 btn btn-outline-primary text-end border-0"onclick="window.location='CS-GetNotInRenewal';" role="button">
                <div class="col-3 col-lg-2 d-flex align-items-center m-1"><span class="fs-1 fw-bold text-center  w-100"><?php echo count(GetNotInRenewal($Who)); ?></span></div>
                  <div class="col-9 col-lg-10 d-flex flex-column justify-items-center">
                    <h4 class="w-100"> طالب ليس لديهم تاريخ تجديد </h4>
                    <p class="m-0"> المطلوب: تحديث تاريخ التجديد </p>
                  </div>
                </div>
                <?php  } ?>

                

              </div>
            </div><!-- End News & Updates -->
          </div><!-- End Right side columns -->
        </div>


      <div class="col-lg-6  my-2">
        <div class="col-lg-12" id="GetRenewal" style="display: none">
          <div class="card  h-100">
            <div class="col-12">
              <div class=" info-card revenue-card ">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <h5 class="card-title text-center">    طلاب حان موعد تجديدهم 	 </h5>
                  <a href="CS-GetRenewal"id='GetRenewalspan' class="bg-secondary text-white card-icon w-50 rounded-pill border  border-dark d-flex align-items-center justify-content-center rtl" >
                     
                  </a>
                  <h5 class="card-title text-center"> المطلوب: سرعة التواصل معهم لدفع الإشتراك </h5>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div id="GetRenewallaceholder" style=" ">
          <p class="card-text placeholder-glow  col-12">
            <span class="placeholder bg-primary col-7"></span>
            <span class="placeholder bg-primary col-4"></span>
            <span class="placeholder bg-primary col-4"></span>
            <span class="placeholder bg-primary col-6"></span>
            <span class="placeholder bg-primary col-8"></span>
          </p>
        </div>


        <div class="col-lg-12" id="GetExceedingOutDate" style="display: none">
          <div class="card  h-100">
            <div class="col-12">
              <div class=" info-card revenue-card ">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <h5 class="card-title text-center">طلاب تجاوزا عدد حصص الباقة الخاصة بهم ولم يحن موعد تجديدهم </h5>
                  <a href="CS-GetExceedingOutDate"id='GetExceedingOutDatespan' class="bg-secondary text-white card-icon w-50 rounded-pill border  border-dark d-flex align-items-center justify-content-center rtl" >
                     
                  </a>
                  <h5 class="card-title text-center"> المطلوب: إما تعديل موعد التجديد على السيستم او مراجعة عدد الحصص المسجلة عليهم </h5>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div id="GetExceedingOutDatelaceholder" style=" ">
          <p class="card-text placeholder-glow  col-12">
            <span class="placeholder bg-primary col-7"></span>
            <span class="placeholder bg-primary col-4"></span>
            <span class="placeholder bg-primary col-4"></span>
            <span class="placeholder bg-primary col-6"></span>
            <span class="placeholder bg-primary col-8"></span>
          </p>
        </div>


        <div class="col-lg-12" id="GetLastClasses" style="display: none">
          <div class="card  h-100">
            <div class="col-12">
              <div class=" info-card revenue-card ">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <h5 class="card-title text-center">طلاب لم يأخذوا حصص آخر اسبوعين </h5>
                  <a   href="CS-GetLastClasses" id='GetLastClassesspan' class="bg-secondary text-white card-icon w-50 rounded-pill border  border-dark d-flex align-items-center justify-content-center rtl" >
                     
                  </a>
                  <h5 class="card-title text-center"> المطلوب: التواصل معهم ومع المعلم لمتابعة سبب التوقف </h5>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div id="GetLastClasseslaceholder" style=" ">
          <p class="card-text placeholder-glow  col-12">
            <span class="placeholder bg-primary col-7"></span>
            <span class="placeholder bg-primary col-4"></span>
            <span class="placeholder bg-primary col-4"></span>
            <span class="placeholder bg-primary col-6"></span>
            <span class="placeholder bg-primary col-8"></span>
          </p>
        </div>
        
      </div>
        
        <div class="toast-container position-fixed bottom-0 end-0 p-3" style=" z-index: 99999;">
          <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false">
            <div class="toast-header">
              <strong class="me-auto">أكاديمية الرؤى</strong>
              <button class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
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
<?php 
      if (isset($_GET['Who']) AND in_array($_GET['Who'],array('AbdelRahman','Belal','Hamza','Ramadan','Bedo','All'))) {
        $_SESSION['Who'] = $_GET['Who'];
      } 
      if (isset($_SESSION['Who']) AND in_array($_SESSION['Who'],array('AbdelRahman','Belal','Hamza','Ramadan','Bedo'))) {
        $GETWho = $_SESSION['Who'];
      }else {
        $GETWho = "All";
      }
?>
<script>
        $(document).ready(function () {
        GetTrail();
        GetLastClasses();
        GetExceedingOutDate();
        GetRenewal();
      })

      function GetTrail() {
        $.ajax({
          type      : 'GET',
          url       : 'functions/GetTrail.php',
          data      : {
          Who : '<?php echo $GETWho  ?>',
          GetTrail : 'GetTrail'
          },
          cache  : false,
          success   : function(Data) {

          var Data = JSON.parse(Data)
          let content = Data


          if (content['Total'] > 0) {
          document.getElementById("GetTrailplaceholder").style.display = "none";
          document.getElementById("GetTrail").style.display = "flex";
          $('#GetTrailspan').html(content['Total'])



          }else{
          document.getElementById("GetTrailplaceholder").style.display = "none";
          }
          }
        });
      }
      function GetLastClasses() {
        $.ajax({
          type      : 'GET',
          url       : 'functions/CS-functions.php',
          data      : {
          Who : '<?php echo $GETWho  ?>',
          GetLastClasses : 'GetLastClasses'
          },
          cache  : false,
          success   : function(Data) {

          var Data = JSON.parse(Data)
          let content = Data


          if (content['Total'] > 0) {
          document.getElementById("GetLastClasseslaceholder").style.display = "none";
          document.getElementById("GetLastClasses").style.display = "block";
          $('#GetLastClassesspan').html(content['Total'])



          }else{
          document.getElementById("GetLastClasseslaceholder").style.display = "none";
          }
          }
        });
      }
      function GetExceedingOutDate() {
        $.ajax({
          type      : 'GET',
          url       : 'functions/CS-functions.php',
          data      : {
          Who : '<?php echo $GETWho  ?>',
          GetExceedingOutDate : 'GetExceedingOutDate'
          },
          cache  : false,
          success   : function(Data) {

          var Data = JSON.parse(Data)
          let content = Data


          if (content['Total'] > 0) {
          document.getElementById("GetExceedingOutDatelaceholder").style.display = "none";
          document.getElementById("GetExceedingOutDate").style.display = "block";
          $('#GetExceedingOutDatespan').html(content['Total'])



          }else{
          document.getElementById("GetExceedingOutDatelaceholder").style.display = "none";
          }
          }
        });
      }
      function GetRenewal() {
        $.ajax({
          type      : 'GET', 
          url       : 'functions/CS-functions.php',
          data      : {
          Who : '<?php echo $GETWho  ?>',
          GetRenewal : 'GetRenewal'
          },
          cache  : false,
          success   : function(Data) {

          var Data = JSON.parse(Data)
          let content = Data


          if (content['Total'] > 0) {
          document.getElementById("GetRenewallaceholder").style.display = "none";
          document.getElementById("GetRenewal").style.display = "block";
          $('#GetRenewalspan').html(content['Total'])



          }else{
          document.getElementById("GetRenewallaceholder").style.display = "none";
          }
          }
        });
      }
</script>
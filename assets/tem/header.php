<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>أكاديمة الرؤى</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <meta http-equiv="Cache-Control" content="no-cache" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="0" />
  <!-- Favicons -->
  <link href="assets/img/logo.png" rel="icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.13.4/b-2.3.6/b-html5-2.3.6/b-print-2.3.6/date-1.4.1/sc-2.1.1/datatables.min.css" rel="stylesheet"/>
  <script src="https://cdn.lordicon.com/fudrjiwc.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
  <link href="assets/css/style-01-11-2023.css" rel="stylesheet">
  

</head>
<?php
function myErrorHandler($errno, $errstr, $errfile, $errline) { 
  header('Location: Error?errno='.$errno.'&errstr='.$errstr.'&errfile='.$errfile.'&errline='.$errline);
  exit ;
 }

set_error_handler("myErrorHandler");
function myException($exception) {
  header('Location: Error?errno='.$exception->getCode().'&errstr='.$exception->getMessage().'&errfile='.$exception->getFile().'&errline='.$exception->getLine());
}
set_exception_handler("myException");
?>
<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top  d-flex  p-1 overflow-x-auto ">
    
    <div class="d-flex align-items-center justify-content-between">
      <a href="index" class="logo d-flex align-items-center">
        <img src="assets/img/logo.png" alt="">
        <span class="d-none d-lg-block">أكاديمية الرؤى</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <?php if (isset($_SESSION['manager']) OR isset($_SESSION['Suber_Admin']) OR isset($_SESSION['Admin']) OR isset($_SESSION['CustomerService']) OR isset($_SESSION['CustomerServiceManager'])) { ?> 
    <nav class="header-nav ms-auto d-flex justify-content-end  "> 
      <ul class="d-flex align-items-center ">

        <li class="nav-item dropdown pe-3">
        <a href="#" class="nav-link nav-profile d-flex flex-column align-items-center pe-0" type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        <lord-icon
          src="https://cdn.lordicon.com/osbjlbsb.json"
          trigger="hover"
          style="width:40px;height:40px">
        </lord-icon>
        <span class="d-block ">search</span>
      </a>
        </li><!-- End search Nav -->

      </ul>

    </nav><!-- End search Navigation -->
    <?php } ?>

    <nav class="header-nav ms-auto d-flex justify-content-end  "> 
        <ul class="d-flex align-items-center ">

        <li class="nav-item dropdown">

        <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown"  data-bs-auto-close="false" aria-expanded="false" >
          <i class="bi bi-bell"></i>
          <span  id="notifications_badge"></span>
        </a>

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications " style="height: 75vh!important; box-shadow: 0px 0 15px rgb(1 41 112 / 32%)!important;" >

          <div id="notifications_ul" class="overflow-auto" style="height: 93%!important;">

          </div>

          <li class="dropdown-header">
            <span> 
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
            </span> 
          </li>
        </ul>

      </li>

      </ul>


    </nav>

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
      
    <li class="fs-5 fw-bold text-center p-0 m-0"><?php if (isset($_SESSION['Name'])) {echo $_SESSION['Name'];}else {echo"Name";} ?></li>

    <li class="fs-6 fw-normal text-center p-0 m-0"><?php if (isset($_SESSION['postion'])) {echo $_SESSION['postion'];}else {echo"Unknow postion";} ?></li>

    <li class="nav-heading"><hr></li>

      <?php if (isset($_SESSION['manager']) OR isset($_SESSION['Suber_Admin']) OR isset($_SESSION['Admin']) OR isset($_SESSION['Supervising_manager']) OR isset($_SESSION['supervisor'])) { ?> 
          <li class="nav-item">
            <a class="nav-link " href="Management">
              <i class="bi bi-grid"></i>
              <span>الصفحة الرئيسية</span>
            </a>
          </li><!-- End Dashboard Nav -->
      <?php } ?>

      <?php if (isset($_SESSION['CustomerService']) OR isset($_SESSION['manager']) OR isset($_SESSION['CustomerServiceManager'])) { ?> 
        <li class="nav-item">
          <a class="nav-link " href="CustomerService">
            <i class="bi bi-grid"></i>
            <span>Customer Service Home</span>
          </a>
        </li><!-- End Dashboard Nav -->
      <?php } ?>

      <?php if (isset($_SESSION['teacher'])) { ?> 
        <?php if (isset($_SESSION['manager']) OR isset($_SESSION['Suber_Admin']) OR isset($_SESSION['Admin']) OR isset($_SESSION['Supervising_manager']) OR isset($_SESSION['supervisor'])) { ?> 
          <li class="nav-item">
            <a class="nav-link " href="Home">
              <i class="bi bi-grid"></i>
              <span> Teacher Home</span>
            </a>
          </li><!-- End Dashboard Nav -->
        <?php } else { ?> 
          <li class="nav-item">
            <a class="nav-link " href="Home">
              <i class="bi bi-grid"></i>
              <span> الصفحة الرئيسية </span>
            </a>
          </li><!-- End Dashboard Nav -->
        <?php } ?>
      <?php } ?>

      <?php if (isset($_SESSION['CustomerService']) AND $_SESSION['CustomerService'] == "Hamza") { ?> 
        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-menu-button-wide"></i><span>التقارير</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              <a href="HR-SalarySheet">
                <i class="bi bi-circle"></i><span> رواتب المعلمين </span>
              </a>
            </li>
          </ul>
        </li><!-- End Components Nav -->
      <?php } ?>

      <?php if (isset($_SESSION['manager'])) { ?> 
        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-menu-button-wide"></i><span>التقارير</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              <a href="HR-SalarySheet">
                <i class="bi bi-circle"></i><span> رواتب المعلمين </span>
              </a>
            </li>
            <li>
              <a href="TeachersSallry">
                <i class="bi bi-circle"></i><span>ساعات عمل المعلمين</span>
              </a>
            </li>
          </ul>
        </li><!-- End Components Nav -->
      <?php } ?>

      <?php if (isset($_SESSION['manager']) OR isset($_SESSION['Suber_Admin']) OR isset($_SESSION['Supervising_manager']) OR isset($_SESSION['supervisor']) ) {  ?>
        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-journal-text"></i><span> التقيمات </span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <?php if (isset($_SESSION['manager']) OR isset($_SESSION['Suber_Admin']) OR isset($_SESSION['Supervising_manager'])  ) {  ?>
              <li>
                <a href="EvaluationTeamAdmins">
                  <i class="bi bi-circle"></i><span> تقيمات مسؤولين السيستم  </span>
                </a>
              </li>
              <li>
                <a href="EvaluationTeamsupervisor">
                  <i class="bi bi-circle"></i><span> تقيمات المشرفين  </span>
                </a>
              </li>
            <?php } ?>

            <?php if (isset($_SESSION['manager'])  OR isset($_SESSION['Supervising_manager']) OR isset($_SESSION['supervisor'])  ) {  ?>
              <li>
                <a href="EvaluationTeamTeachers">
                  <i class="bi bi-circle"></i><span>  تقيمات المعلمين </span>
                </a>
              </li>
            <?php } ?>


            <?php if (isset($_SESSION['manager']) OR isset($_SESSION['Suber_Admin']) ) {  ?>
              <li>
                <a href="Items">
                  <i class="bi bi-circle"></i><span> إدارة بنود التقييم </span>
                </a>
              </li>
            <?php } ?>

          </ul>
        </li><!-- End Forms Nav -->
      <?php } ?>



      <?php if (isset($_SESSION['Admin']) OR isset($_SESSION['manager']) OR isset($_SESSION['Suber_Admin']) ) {  ?>
        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-layout-text-window-reverse"></i><span>الطلبات</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              <a href="RescheduleRequestAdmin">
                <i class="bi bi-circle"></i><span>  طلبات الحصص التعويضية </span>
              </a>
            </li>
            <?php if (isset($_SESSION['manager']) ) {  ?>
            <li>
              <a href="RequestAddToHistory">
                <i class="bi bi-circle"></i><span>  طلب إضافة حصة </span>
              </a>
            </li>
            <li>
              <a href="ArchivedStudentsExportRequest">
                <i class="bi bi-circle"></i><span>  طلب إستخراج من الأرشيف   </span>
              </a>
            </li>
          <?php } ?>
          </ul>
        </li><!-- End Tables Nav -->
      <?php } ?>

      <?php if (isset($_SESSION['manager'])) {  ?>
        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#charts-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-bar-chart"></i><span> المستخدمين</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="charts-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              <a href="A-User">
                <i class="bi bi-circle"></i><span> إضافة مستخدم </span>
              </a>
            </li>
            <li>
              <a href="Users">
                <i class="bi bi-circle"></i><span> إدارة المستخدمين </span>
              </a>
            </li>
            <li>
              <a href="UsersTeachers">
                <i class="bi bi-circle"></i><span> إدارة المعلمين </span>
              </a>
            </li>
          </ul>
        </li><!-- End Charts Nav -->
      <?php } ?>
      <?php if (isset($_SESSION['teacher'])) {  ?>
      <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#RescheduleRequest-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-layout-text-window-reverse"></i><span>الطلبات</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="RescheduleRequest-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              <a href="RescheduleRequest">
                <i class="bi bi-circle"></i><span>  طلبات الحصص التعويضية </span>
              </a>
            </li>
          </ul>
        </li><!-- End Tables Nav -->
        <?php } ?>
      <?php if (isset($_SESSION['manager'])) {  ?>
      <li class="nav-item">
          <a class="nav-link collapsed" data-bs-target="#Backup-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-layout-text-window-reverse"></i><span> النسخ الأحتياطى </span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="Backup-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
            <li>
              <a href="Backup-View">
                <i class="bi bi-circle"></i><span> عرض النسخ الأحتياطية </span>
              </a>
            </li>
            <li>
              <a href="Backup">
                <i class="bi bi-circle"></i><span> اخذ النسخة الأحتياطية </span>
              </a>
            </li>
          </ul>
        </li><!-- End Backup Nav -->
        <?php } ?>
      <li class="nav-heading"><hr></li>

      <?php if (isset($_SESSION['manager'])) {  ?>
        <li class="nav-item">
          <a class="nav-link collapsed" href="NotificationSendForm">
            <i class="bi bi-envelope"></i>
            <span> إرسال إشعار </span>
          </a>
        </li><!-- End Contact Page Nav -->
      <?php } ?>

      <?php if (isset($_SESSION['Admin']) OR isset($_SESSION['manager']) OR isset($_SESSION['Suber_Admin']) ){  ?>
        <li class="nav-item">
          <a class="nav-link collapsed" href="A-Student">
            <i class="bi bi-person-plus"></i>
            <span> إضافة كود جديد </span>
          </a>
        </li><!-- End Contact Page Nav -->
      <?php } ?>

      <?php if (isset($_SESSION['manager'])) {  ?>
        <li class="nav-item">
          <a class="nav-link collapsed position-relative" href="HR-GetNewTeachers">
            <span id="GetNewTeachersCountSpan">
              
            </span>
            <i class="bi bi-person-plus"></i>
            <span> معلمين جدد </span>
          </a>
        </li><!-- End Contact Page Nav -->
        <script>
          $(document).ready(function () {
            GetNewTeachersCount();
            setInterval(GetNewTeachersCount, 90000);
          })
          function GetNewTeachersCount() {
          $.ajax({ 
                  type      : 'POST', 
                  url       : 'functions/GetNewTeachers.php', 
                  data      : {
                    GetNewTeachersCount : 1
                  },
                  cache  : false,
                  success   : function(Data) {

                    var Data = JSON.parse(Data)
                    let content = Data
                    // console.log(content);
                    if (content[0].Total > 0) {
                      $('#GetNewTeachersCountSpan').html('')
                      $('#GetNewTeachersCountSpan').html(`<span class="position-absolute top-50 start-50 translate-middle badge rounded-pill bg-danger mx-3 ">${content[0].Total}</span>`)
                    }else{
                      $('#GetNewTeachersCountSpan').html('')
                    }
                  }
              });
          }
        </script>
      <?php } ?>

      <?php if (isset($_SESSION['manager'])) {  ?>
        <li class="nav-item">
          <a class="nav-link collapsed position-relative" href="CS-GetNewStudents">
            <span id="GetNewStudentsCountSpan">
              
            </span>
            <i class="bi bi-person-plus"></i>
            <span> طلاب جدد </span>
          </a>
        </li><!-- End Contact Page Nav -->
        <script>
          $(document).ready(function () {
            GetNewStudentsCount();
            setInterval(GetNewStudentsCount, 90000);
          })
          function GetNewStudentsCount() {
          $.ajax({
                  type      : 'POST', 
                  url       : 'functions/GetNewStudents.php', 
                  data      : {
                    GetNewStudentsCount : 1
                  },
                  cache  : false,
                  success   : function(Data) {

                    var Data = JSON.parse(Data)
                    let content = Data
                    // console.log(content);
                    if (content[0].Total > 0) {
                      $('#GetNewStudentsCountSpan').html('')
                      $('#GetNewStudentsCountSpan').html(`<span class="position-absolute top-50 start-50 translate-middle badge rounded-pill bg-danger mx-3">${content[0].Total}</span>`)
                    }else{
                      $('#GetNewStudentsCountSpan').html('')
                    }
                  }
              });
          }
        </script>
      <?php } ?>
      
      <?php if (isset($_SESSION['teacher'])) {  ?>
          <li class="nav-item">
            <a class="nav-link collapsed" href="salary">
              <i class="bi bi-currency-dollar"></i>
              <span> الراتب </span>
            </a>
          </li><!-- End Contact Page Nav -->

          <li class="nav-item">
            <a class="nav-link collapsed" href="Schedule">
              <i class="bi bi-table"></i>
              <span> الجدول  </span>
            </a>
          </li><!-- End Contact Page Nav -->
      <?php } ?>



      <li class="nav-heading"><hr></li>


      <li class="nav-item">
        <a class="nav-link collapsed" href="logout">
          <i class="bi bi-box-arrow-in-right"></i>
          <span> تسجيل الخروج </span>
        </a>
      </li><!-- End Login Page Nav -->

    </ul>

  </aside>
  <!-- End Sidebar-->

  <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel"> بحث </h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

        <form class="search-form d-flex flex-column align-items-center" method="GET" action="search" >

        <div class="input-group mb-3">
          <input type="text" id="CodeInput" name="Code" class="form-control" placeholder=" اكتب كود الطالب " autofocus  aria-describedby="button-addon2" >
          <button class="btn btn-primary" type="submit" id="button-addon2"> <i class="bi bi-search"></i> <span> بحث </span> </button>
        </div>

      </form>
      <button onclick="window.location= 'SearchAll' " class="btn btn-primary form-control"  > <i class="bi bi-search"></i> <span> عرض جميع الطلاب </span> </button>

        </div>

      </div>
    </div>
  </div>
  <script>
    const myModal = document.getElementById('staticBackdrop')
    const myInput = document.getElementById('CodeInput')
    myModal.addEventListener('shown.bs.modal', () => {
      myInput.focus()
    })
  </script>
  <?php include "connect.php"; ?>
<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_GET['Code'])) { header('Location: index'); exit;}
if (!isset($_SESSION['Admin']) AND !isset($_SESSION['Suber_Admin']) AND !isset($_SESSION['CustomerService']) AND !isset($_SESSION['CustomerServiceManager']) AND !isset($_SESSION['manager'])) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ;
require "functions/CS-functions.php";
 require "functions/globalFunctions.php";
    $search = $_GET['Code'] ;
    $ArchivedStudents_stmt = $con->prepare("SELECT * FROM ArchivedStudents WHERE Code LIKE ?");
    $ArchivedStudents_stmt->execute(array("%$search %"));
    $ArchivedStudents=$ArchivedStudents_stmt->fetch();
    $ArchivedStudents_count = $ArchivedStudents_stmt->rowCount();
     if ($ArchivedStudents_count > 0 ) { ?>
        <?php header('Location: ArchivedStudentsSearch?Code='.$search);
         exit;
     }
    $search = $_GET['Code'] ;
    $Active_stmt = $con->prepare("SELECT * FROM students WHERE Code LIKE ?");
    $Active_stmt->execute(array("%$search %"));
    $search_data=$Active_stmt->fetchAll();
    $Active_count = $Active_stmt->rowCount();
     if ($Active_count == 0 ) { ?>
        <main id="main" class="main">
            <div class="container">
                <section class="section dashboard">
                    <div class="rtl">
                        <div class="row">
                            <div class="col-lg-6 m-auto mh-100">
                                <div class="card">
                                    <h5 class="card-title text-center"></h5>
                                    <nav class="header-nav ms-auto d-flex justify-content-end  "> 
                                        <ul class="d-flex align-items-center ">

                                            <li class="nav-item dropdown pe-3">
                                            <a href="#" class="nav-link nav-profile d-flex flex-column align-items-center pe-0" type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                            <lord-icon
                                            src="https://cdn.lordicon.com/osbjlbsb.json"
                                            trigger="hover"
                                            style="width:40px;height:40px">
                                            </lord-icon>
                                            <span class="d-block "> ابحث مره أخرى </span>
                                        </a>
                                            </li><!-- End search Nav -->

                                        </ul>
                                    </nav><!-- End search Navigation -->
                                    <div class="card-body pt-3 ">
                                        <h1 class="rtl text-center"> من فضلك تأكد من الكود </h1>
                                        <h1 class="rtl text-center"> اكتب الكود فقط بدون الأسم </h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </main>
        <?php include "assets/tem/footer.php" ?>
        <?php exit;
     }
  if (isset($_GET['Start']) AND isset($_GET['End'])) {
    $current_month = date("Y-m-d", strtotime($_GET['Start']));
    $Next_month = date("Y-m-d", strtotime($_GET['End']));
  }else {
    $current_month = 0;
    $Next_month = date("Y-m-d");
  } 
?>
<main id="main" class="main">
    <div class="container">

    <?php if ($Active_count == 1) {
        foreach ($search_data as $key => $value) { ?>
        <div class="pagetitle">
            <nav>
                <ol class="breadcrumb">
                    <?php if (isset($_SESSION['CustomerService']) OR isset($_SESSION['CustomerServiceManager']) OR isset($_SESSION['manager']) ) { ?>
                        <li class="">        
                            <a class="nav-link nav-profile d-flex flex-column align-items-center pe-0" href="<?php echo "CustomerServiceJournal?code=".$_GET['Code'] ?>">
                            <lord-icon
                            src="https://cdn.lordicon.com/pimvysaa.json"
                            trigger="hover"
                            style="width:90px;height:90px">
                            </lord-icon>
                            <span class="d-block rtl">( تسجيل دفع )</span>
                            </a><!-- End Dashboard Iamge Icon -->
                        </li>
                        <li class="">        
                            <a class="nav-link nav-profile d-flex flex-column align-items-center pe-0" href="<?php echo "CustomerServiceRenewalDate?code=".$_GET['Code'] ?>">
                            <lord-icon
                            src="https://cdn.lordicon.com/alzqexpi.json"
                            trigger="hover"
                            style="width:90px;height:90px">
                            </lord-icon>
                            <span class="d-block rtl">( تعديل تاريخ )</span>
                            </a><!-- End Dashboard Iamge Icon -->
                        </li>
                        <li class="">        
                            <a class="nav-link nav-profile d-flex flex-column align-items-center pe-0" href="<?php echo "CustomerServiceSettlement?code=".$_GET['Code'] ?>">
                            <lord-icon
                            src="https://cdn.lordicon.com/qtqvorle.json"
                            trigger="hover"
                            style="width:90px;height:90px">
                            </lord-icon>
                            <span class="d-block rtl">( طلب تسوية )</span>
                            </a><!-- End Dashboard Iamge Icon -->
                        </li>
                    <?php } ?>
                    <?php if (isset($_SESSION['Suber_Admin']) OR isset($_SESSION['manager']) ) { ?>
                        <!-- ----------------------------------------------------------------------------------------------------------------- -->
                        <li class="">        
                            <a class="nav-link nav-profile d-flex flex-column align-items-center pe-0" href="EditeCode?do=Update&ID=<?php echo $value['ID'] ?>">
                            <lord-icon
                            src="https://cdn.lordicon.com/oncyjozz.json"
                            trigger="hover"
                            style="width:90px;height:90px">
                            </lord-icon>
                            <span class="d-block rtl">( تعديل الكود )</span>
                            </a><!-- End Dashboard Iamge Icon -->
                        </li>
                        <li class="">        
                            <a class="nav-link nav-profile d-flex flex-column align-items-center pe-0" href="EditeCode?do=Delete&ID=<?php echo $value['ID'] ?>"onclick="return confirm('هل انت متأكد من الحذف ؟');">
                            <lord-icon
                            src="https://cdn.lordicon.com/exkbusmy.json"
                            trigger="hover"
                            style="width:90px;height:90px">
                            </lord-icon>
                            <span class="d-block rtl">( حذف الكود )</span>
                            </a><!-- End Dashboard Iamge Icon -->
                        </li>

                        <?php if ($value['status'] == 'Cancel') { ?>
                        <li class="">        
                            <a class="nav-link nav-profile d-flex flex-column align-items-center pe-0" href="EditeCode?do=Active&ID=<?php echo $value['ID'] ?>">
                            <lord-icon
                            src="https://cdn.lordicon.com/sxobuwft.json"
                            trigger="hover"
                            style="width:90px;height:90px">
                            </lord-icon>
                            <span class="d-block rtl">( تفعيل الكود )</span>
                            </a><!-- End Dashboard Iamge Icon -->
                        </li>
                        <?php }else { ?>
                        <li class="">        
                            <a class="nav-link nav-profile d-flex flex-column align-items-center pe-0" href="EditeCode?do=Cancel&ID=<?php echo $value['ID'] ?>">
                            <lord-icon
                            src="https://cdn.lordicon.com/qxjdtzah.json"
                            trigger="hover"
                            style="width:90px;height:90px">
                            </lord-icon>
                            <span class="d-block rtl">( ايقاف الكود )</span>
                            </a><!-- End Dashboard Iamge Icon -->
                        </li>
                        <?php } ?>
                    <?php } ?>
                    <?php if (isset($_SESSION['Admin']) OR isset($_SESSION['Suber_Admin']) OR isset($_SESSION['manager']) ) { ?>

                        <li class="">        
                            <a class="nav-link nav-profile d-flex flex-column align-items-center pe-0" href="A-Classe?code=<?php echo $_GET['Code'] ?>">
                            <lord-icon
                            src="https://cdn.lordicon.com/ejxwvtlg.json"
                            trigger="hover"
                            style="width:90px;height:90px">
                            </lord-icon>
                            <span class="d-block rtl">( إضافة جدول )</span>
                            </a><!-- End Dashboard Iamge Icon -->
                        </li>
                        <li class="">        
                            <a class="nav-link nav-profile d-flex flex-column align-items-center pe-0" href="A-Classe?code=<?php echo $_GET['Code'] ?>&Trail">
                            <lord-icon
                            src="https://cdn.lordicon.com/kjtalhau.json"
                            trigger="hover"
                            style="width:90px;height:90px">
                            </lord-icon>
                            <span class="d-block rtl">( إضافة حصة تجريبية )</span>
                            </a><!-- End Dashboard Iamge Icon -->
                        </li>
                    <?php } ?>
                </ol>
            </nav>
            
            <h1>Search </h1>
            <nav>
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Search</li>
                </ol>
            </nav>
        </div>
    <?php } } ?>

    <?php if ($Active_count > 1) { ?>
        <h1 class="rtl"> انتبه من فضلك هناك طالبين بنفس الكود <?php echo $search ; ?> برجاء حذف كود منهم أو تغيير الكود</h1>
        
        <?php foreach ($search_data as $data) { 

        $H_stmt = $con->prepare("SELECT * FROM history WHERE S_code = ? AND `date` >= ? AND `date` <= ? ORDER BY `Timestamp` ASC ");
        $H_stmt->execute(array($data['Code'],$current_month,$Next_month));
        $count = $H_stmt->rowCount();
        $row = $H_stmt->fetchAll();

        $classes_stmt = $con->prepare("SELECT * FROM class WHERE Student=? And `type` = 2");
        $classes_stmt->execute(array($data['Code']));
        $classes_data=$classes_stmt->fetchAll();
        $classes_count=$classes_stmt->rowCount();

        $Remained = $con->prepare("SELECT * FROM students WHERE Code=? ");
        $Remained->execute(array($data['Code']));
        $Remained_count = $Remained->rowCount();
        $Remainedfetch = $Remained->fetch();

        if ($Remained_count > 0) {
            if (date('Y_m_d', strtotime($Remainedfetch['Renewal_date'])) =='1970_01_01') {
                $Renewal_date = 'Undefined';
            }else {
                $Renewal_date = date('Y_m_d', strtotime($Remainedfetch['Renewal_date']));
            }
        $Remained = $Remainedfetch['Remained'];
        }else {
        $Renewal_date = "?";
        $Remained = "?";
        }
        $Journal = $con->prepare("SELECT * FROM Journal WHERE Code = ?  AND `Type` IN ('1','5') ");
        $Journal->execute(array($data['Code']));
        $Journal_count = $Journal->rowCount();
        ?>
        <div class="row">
            <h5 class="card-title text-center"><?php echo $data['Code'] ; ?></h5>
            <div class="btn-group ltr w-100">
                <a  onclick="return confirm('هل انت متأكد من الحذف ؟');" href="EditeCode?do=Delete&ID=<?php echo $data['ID'] ?>"class="btn btn-danger fs-6 fw-bold text-nowrap  w-50" > حذف </a> 
                <a href="EditeCode?do=Update&ID=<?php echo $data['ID'] ?>" class="btn btn-primary fs-6 fw-bold text-nowrap  w-50" > تعديل </a> 
            </div>
            <div class="col-lg-6 m-auto h-100">
                <h5 class="card-title text-center"></h5>
                <div class="card card-body pt-3 ">
                    <p class="card-details mb-0"   > <i class="fa-solid fa-user"></i> &nbsp; الكود  :- &nbsp;<small style=" font-size: 1.25em;"><?php echo $data['Code'] ; ?></small></p>
                    <p class="card-details mb-0"   > <i class="fa-sharp fa-solid fa-arrow-down-9-1"></i> &nbsp; عدد الطلاب :- &nbsp;<small style=" font-size: 1.25em;"><?php echo $data['N_Students'] ; ?></small></p>
                    <p class="card-details mb-0"   > <i class="fa-solid fa-calendar-days"></i> &nbsp; الباقة :- &nbsp;<small style=" font-size: 1.25em;"><?php echo $data['Days'] ; ?></small></p>
                    <p class="card-details mb-0"   > <i class="fa-solid fa-stopwatch"></i> &nbsp; حالة الكود :- &nbsp;<small style=" font-size: 1.25em;"><?php echo $data['status'] ; ?></small></p>
                    <p class="card-details mb-0"   > <i class="fa-solid fa-stopwatch"></i> &nbsp; بداية التعامل :- &nbsp;<small style=" font-size: 1.25em;"><?php echo $data['First_class'] ; ?></small></p>
                </div>
            </div>

            <?php if (isset($_SESSION['manager'])  OR isset($_SESSION['CustomerServiceManager']) OR isset($_SESSION['CustomerService'])) { ?> 
                <div class="col-lg-6 m-auto h-100">
                    <h5 class="card-title text-center"></h5>
                    <div class="card card-body pt-3 ">
                        <p class="card-details mb-0"   > <i class="fa-solid fa-user"></i> &nbsp; الإشتراك بالمصرى  :- &nbsp;<small style=" font-size: 1.25em;"><?php echo $data['E_Cost'] ; ?></small></p>
                        <p class="card-details mb-0"   > <i class="fa-sharp fa-solid fa-arrow-down-9-1"></i> &nbsp;  الإشتراك بالريال :- &nbsp;<small style=" font-size: 1.25em;"><?php echo $data['S_Cost'] ; ?></small></p>
                        <p class="card-details mb-0"   > <i class="fa-solid fa-calendar-days"></i> &nbsp;  تاريخ التجديد :- &nbsp;<small style=" font-size: 1.25em;"><?php echo $Renewal_date ; ?></small></p>
                        <p class="card-details mb-0"   > <i class="fa-solid fa-stopwatch"></i> &nbsp;  الحصص المتبقية :- &nbsp;<small style=" font-size: 1.25em;<?php if ($Remained < 0) { echo ' color: green;';}elseif ($Remained > 0) { echo ' color: red;';}else {}?>"><?php echo $Remained ; ?></small></p>
                        <p class="card-details mb-0"   > <i class="fa-solid fa-stopwatch"></i> &nbsp; عدد مرات الدفع :- &nbsp;<small style=" font-size: 1.25em;"><?php echo $Journal_count ; ?></small></p>
                    </div>
                </div>
            <?php } ?>
            
        </div>


        <?php }  ?>
        <?php if ($Active_count > 1) { exit; } ?>
    <?php }  ?>

        <section class="section dashboard">
            <div class="rtl">

                <?php foreach ($search_data as $data) { 

                    $H_stmt = $con->prepare("SELECT * FROM history WHERE S_code = ? AND `date` >= ? AND `date` <= ? ORDER BY `Timestamp` ASC ");
                    $H_stmt->execute(array($data['Code'],$current_month,$Next_month));
                    $count = $H_stmt->rowCount();

                    if (isset($_GET['Start']) AND isset($_GET['End'])) {
                        $row1 = $H_stmt->fetchAll();
    
                        $H_stmt = $con->prepare("SELECT * FROM ArchivedHistory WHERE S_code = ? AND `date` >= ? AND `date` <= ? ORDER BY `Timestamp` ASC ");
                        $H_stmt->execute(array($data['Code'],$current_month,$Next_month));
                        $count = $H_stmt->rowCount();
                        $row2 = $H_stmt->fetchAll();
    
                        $row= array_merge($row1,$row2);
                    }else {
                        $row = $H_stmt->fetchAll();
                    }

                    $classes_stmt = $con->prepare("SELECT * FROM class WHERE Student=? And `type` = 2");
                    $classes_stmt->execute(array($data['Code']));
                    $classes_data=$classes_stmt->fetchAll();
                    $classes_count=$classes_stmt->rowCount();

                    $Remained = $con->prepare("SELECT * FROM students WHERE Code=? ");
                    $Remained->execute(array($data['Code']));
                    $Remained_count = $Remained->rowCount();
                    $Remainedfetch = $Remained->fetch();

                    if ($Remained_count > 0) {
                        if (date('Y_m_d', strtotime($Remainedfetch['Renewal_date'])) =='1970_01_01') {
                            $Renewal_date = 'Undefined';
                        }else {
                            $Renewal_date = date('Y_m_d', strtotime($Remainedfetch['Renewal_date']));
                        }
                    $Remained = $Remainedfetch['Remained'];
                    }else {
                    $Renewal_date = "?";
                    $Remained = "?";
                    }
                    $Journal = $con->prepare("SELECT * FROM Journal WHERE Code = ?  AND `Type` IN ('1','5') ");
                    $Journal->execute(array($data['Code']));
                    $Journal_count = $Journal->rowCount();

                    $Activity = $con->prepare("SELECT * FROM Activity WHERE ForWho = ? ORDER BY `Timestamp` DESC");
                    $Activity->execute(array($data['Code']));
                    $Activity_count = $Activity->rowCount();
                    $Activity_fetch = $Activity->fetchAll();
                    ?>
                    <div class="row">
                        <h5 class="card-title text-center"><?php echo $data['Code'] ; ?></h5>
                        <?php SetActual($data["Code"]); if (checkActual($data['Code']) == false) {
                           ?> <h5 class="card-title text-center text-danger"> خطأرقم : St-700 </h5> <?php
                           ?> <h5 class="card-title text-center text-danger"> هناك فرق بين الباقة وعدد الايام الفعلى فى الجداول </h5> <?php
                        } ; ?>
                        <div class="col-lg-6 m-auto mh-100">
                            <h5 class="card-title text-center"></h5>
                            <div class="card card-body pt-3 ">
                                <p class="card-details mb-0"   > <i class="fa-solid fa-user"></i> &nbsp; الكود  : &nbsp;<small style=" font-size: 1.25em;"><?php echo $data['Code'] ; ?></small></p>
                                <p class="card-details mb-0"   > <i class="fa-sharp fa-solid fa-arrow-down-9-1"></i> &nbsp; عدد الطلاب : &nbsp;<small style=" font-size: 1.25em;"><?php echo $data['N_Students'] ; ?></small></p>
                                <p class="card-details mb-0"   > <i class="fa-solid fa-calendar-days"></i> &nbsp; الباقة : &nbsp;<small style=" font-size: 1.25em;"><?php echo $data['Days'] ; ?></small></p>
                                <p class="card-details mb-0"   > <i class="fa-solid fa-stopwatch"></i> &nbsp; حالة الكود : &nbsp;<small style=" font-size: 1.25em;"><?php echo $data['status'] ; ?></small></p>
                                <p class="card-details mb-0"   > <i class="fa-solid fa-stopwatch"></i> &nbsp; بداية التعامل : &nbsp;<small style=" font-size: 1.25em;"><?php echo $data['First_class']  ; ?></small></p>
                            </div>
                        </div>

                        <?php if (isset($_SESSION['manager'])  OR isset($_SESSION['CustomerServiceManager']) OR isset($_SESSION['CustomerService'])) { ?> 
                            <div class="col-lg-6 m-auto mh-100">
                                <h5 class="card-title text-center"></h5>
                                <div class="card card-body pt-3 ">
                                    <p class="card-details mb-0"   > <i class="fa-solid fa-user"></i> &nbsp; الإشتراك بالمصرى  : &nbsp;<small style=" font-size: 1.25em;"><?php echo $data['E_Cost'] ; ?></small></p>
                                    <p class="card-details mb-0"   > <i class="fa-sharp fa-solid fa-arrow-down-9-1"></i> &nbsp;  الإشتراك بالريال : &nbsp;<small style=" font-size: 1.25em;"><?php echo $data['S_Cost'] ; ?></small></p>
                                    <p class="card-details mb-0"   > <i class="fa-solid fa-calendar-days"></i> &nbsp;  تاريخ التجديد : &nbsp;<small style=" font-size: 1.25em;"><?php echo $Renewal_date ; ?></small></p>
                                    <p class="card-details mb-0"   > <i class="fa-solid fa-stopwatch"></i> &nbsp;  الحصص المتبقية : &nbsp;<small style=" font-size: 1.25em;<?php if ($Remained < 0) { echo ' color: green;';}elseif ($Remained > 0) { echo ' color: red;';}else {}?>"><?php echo $Remained ; ?></small></p>
                                    <p class="card-details mb-0"   > <i class="fa-solid fa-stopwatch"></i> &nbsp; عدد مرات الدفع : &nbsp;<small style=" font-size: 1.25em;"><?php echo $Journal_count ; ?></small></p>
                                </div>
                            </div>
                        <?php } ?>
                        
                    </div>

                    <?php if ($classes_count > 0) { ?>
                    <div class="row">
                        <?php foreach ($classes_data as $class) { ?>
                            <div class="col-lg-6 m-auto">
                                <div class="card card-body pt-3 ">
                                <?php if (isset($_SESSION['Admin']) OR isset($_SESSION['Suber_Admin']) OR isset($_SESSION['manager']) ) { ?>
                                    <?php if ($Active_count == 1 ) { ?>
                                    <div class="btn-group ltr w-100">
                                    <a onclick="return confirm('هل انت متأكد من حذف ؟');" href="EditeClasse?do=Delete&ID=<?php echo $class['ID'] ?>" class="btn btn-danger fs-6 fw-bold text-nowrap  w-50" > حذف </a> 
                                    <?php if ($class['status'] == 'Pause') { ?>
                                    <a href="EditeClasse?do=Active&ID=<?php echo $class['ID'] ?>" class="btn btn-success fs-6 fw-bold text-nowrap  w-50" > تفعيل </a> 
                                    <?php }else { ?>
                                    <a href="EditeClasse?do=Cancel&ID=<?php echo $class['ID'] ?>" class="btn btn-dark fs-6 fw-bold text-nowrap  w-50" > ايقاف </a> 
                                    <?php } ?>
                                    <a href="EditeClasse?do=Update&ID=<?php echo $class['ID'] ?>" class="btn btn-primary fs-6 fw-bold text-nowrap  w-50" > تعديل </a> 
                                    </div>
                                    <?php }  ?>
                                <?php }  ?>
                                <?php if ($class['status']== 'Pause') {echo '<p class="w-100 text-center text-danger fw-bolder">  هذا الطالب غير مفعل </p>';} ?>
                                <p class="card-details mb-0"   > <i class="fa-solid fa-user"></i> &nbsp; اسم الطالب  : &nbsp;<small style=" font-size: 1.25em;"><?php echo $class['StudentName'] ; ?></small></p>
                                <p class="card-details mb-0"   > <i class="fa-solid fa-user"></i> &nbsp; كود المعلم : &nbsp;<small style=" font-size: 1.25em;"><?php echo $class['Teacher'] ; ?></small></p>
                                <p class="card-details mb-0"   > <i class="fa-solid fa-calendar-days"></i> &nbsp; عدد ايام الطالب : &nbsp;<small style=" font-size: 1.25em;"><?php echo $class['Days'] ; ?></small></p>
                                <p class="card-details mb-0"   > <i class="fa-solid fa-calendar-days"></i> &nbsp;  مدة الحصة : &nbsp;<small style=" font-size: 1.25em;"><?php echo $class['Duration'] ; ?></small></p>
                                <p class="card-details mb-0 ltr"   > 
                                <?php if (!empty($class['Saturday'])) {echo $class['Saturday'].'-';} ?>
                                <?php if (!empty($class['Saturday_time'])) {echo TimeToDisplay($class['Saturday_time']).'<br>';} ?>
                                <?php if (!empty($class['Sunday'])) {echo $class['Sunday'].'-';} ?>
                                <?php if (!empty($class['Sunday_time'])) {echo TimeToDisplay($class['Sunday_time']) .'<br>';} ?>
                                <?php if (!empty($class['Monday'])) {echo $class['Monday'].'-';} ?>
                                <?php if (!empty($class['Monday_time'])) {echo TimeToDisplay($class['Monday_time']) .'<br>';} ?>
                                <?php if (!empty($class['Tuesday'])) {echo $class['Tuesday'].'-';} ?>
                                <?php if (!empty($class['Tuesday_time'])) {echo TimeToDisplay($class['Tuesday_time']) .'<br>';} ?>
                                <?php if (!empty($class['Wednesday'])) {echo $class['Wednesday'].'-';} ?>
                                <?php if (!empty($class['Wednesday_time'])) {echo TimeToDisplay($class['Wednesday_time']) .'<br>';} ?>
                                <?php if (!empty($class['Thursday'])) {echo $class['Thursday'].'-';} ?>
                                <?php if (!empty($class['Thursday_time'])) {echo TimeToDisplay($class['Thursday_time']) .'<br>';} ?>
                                <?php if (!empty($class['Friday'])) {echo $class['Friday'].'-';} ?>
                                <?php if (!empty($class['Friday_time'])) {echo TimeToDisplay($class['Friday_time']) .'<br>';} ?>
                                </p>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <?php } ?>
                    
                    <?php if (isset($_SESSION['CustomerService']) OR isset($_SESSION['CustomerServiceManager']) OR isset($_SESSION['manager']) ) { ?>
                    <div  class="row ltr">
                        <div class="col-lg-12 m-auto">
                            <div class="card">

                                <div class="card-body">
                                <h5 class="card-title">Recent Activity</h5>
    
                                <div class="activity">
                                    <?php foreach ($Activity_fetch as $key => $value) { ?>

                                        <div class="activity-item d-flex">
                                        <div class="activite-label text-center w-25"> <?php echo $value['Timestamp'] ?> </div>
                                        <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                                        <div class="activity-content fw-bold text-dark rtl ">
                                            <?php echo $value['Message'] ?> 
                                        </div>
                                        </div>
                                    <?php } ?>
    
    
    
                                </div>
    
                                </div>
                            </div><!-- End Recent Activity -->
                        </div>
                    </div>
                    <?php } ?>

                    <div class="row">

                        <div class="col-lg-12 m-auto">
                            <h5 class="card-title text-center"></h5>
                            <div class="card card-body pt-3 overflow-auto">

                                <form class="row ltr row-cols-lg-auto g-3 align-items-center" method="GET" action="">
                                <input type="hidden" class="form-control" value="<?php echo $search ;?>"  name="Code" >
                                <div class="col-12">
                                    <label class="visually-hidden" for="inlineFormInputGroupUsername">Username</label>
                                    <div class="input-group">
                                    <div class="input-group-text">From</div>
                                    <input type="date" class="form-control" value="<?php echo $current_month ;?>"  name="Start" >
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="visually-hidden" for="inlineFormInputGroupUsername">Username</label>
                                    <div class="input-group">
                                    <div class="input-group-text">To</div>
                                    <input type="date" class="form-control" value="<?php echo $Next_month ;?>" name="End" >
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                                </form>
                                <br>
                                <p class="rtl text-center"> الحصص المعروضة بشكل افتراضى اخر اربع اشهر فقط اذا اردت المزيد <br> يرجى اختيار تاريخ البدايه الذى تريد العرض من عنده وتاريخ النهاية  </p>

                                <?php if (isset($_SESSION['manager']) ) { ?>
                                    <div class="btn-group col-12 col-lg-6 m-auto  mt-2 ltr">
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#AddClass">
                                            أضافة حصة
                                        </button>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#EAllCodes">
                                            تعديل جميع الأكواد
                                        </button>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#EAllNames">
                                            تعديل جميع الأسماء
                                        </button>
                                    </div>

                                    <br>
                                        <div class="modal fade" id="AddClass" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="AddClassLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="AddClassLabel">إضافة حصة</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>

                                                <form class="forms-sample" action="add-to-db" method="post">
                                                    <div class="modal-body">
                                                        <div class="form-group  ">
                                                            <div class="form-group row">
                                                                <label for="code"> code </label>
                                                                <input id="code" name="code" class="form-control mb-3" value="<?php echo $data['Code'] ; ?>" type="text" required='true'>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label for="name"> name </label>
                                                                <input id="name" name="name" class="form-control mb-3" value="<?php echo $data['Code'] ; ?>" type="text" required='true'>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label for="Duration"> Duration </label>
                                                                <input id="Duration" name="Duration" class="form-control mb-3" type="text" required='true'>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label for="t_code"> Teacher </label>
                                                                <input id="t_code" name="t_code" class="form-control mb-3" type="text" required='true'>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label for="status"> status </label>
                                                                <input id="status" name="status" class="form-control mb-3" value="حضور" type="text" required='true'>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label for="date"> date </label>
                                                                <input id="date" name="date" class="form-control mb-3" type="date" required='true'>
                                                            </div>

                                                                <input id="nots" name="nots" class="form-control mb-3" value="سجلها المشرف" type="hidden" required='true'>
                                                                <input id="id" name="id" class="form-control mb-3" value="<?php echo rand(0,1000).date("d").rand(9,99) ; ?>" type="hidden" required='true'>
                                                                <input id="category" name="category" class="form-control mb-3" value="سجلها المشرف" type="hidden" required='true'>

                                                            
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                                        <button type="submit" class="btn btn-primary">تسجيل</button>
                                                    </div>
                                                </form>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="EAllCodes" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="EAllCodesLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="EAllCodesLabel">تعديل الأكواد</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form class="forms-sample" action="in-to-db?do=Update_History_EAllCodes" method="post">
                                                    <div class="modal-body">
                                                        <div class="form-group  ">


                                                            <div class="form-group row">
                                                                <label for="OLDStudent">الكود القديم</label>
                                                                <input id="OLDStudent" name="OLDStudent" class="form-control mb-3" type="text" required='true'>
                                                            </div>

                                                            <div class="form-group row">
                                                                <label for="NEWStudent">الكود الجديد</label>
                                                                <input id="NEWStudent" name="NEWStudent" class="form-control mb-3" type="text" required='true'>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                                        <button type="submit" class="btn btn-primary">تحديث</button>
                                                    </div>
                                                </form>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="EAllNames" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="EAllNamesLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="EAllNamesLabel">تعديل الأسماء</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form class="forms-sample" action="in-to-db?do=Update_History_EAllNames" method="post">
                                                    <div class="modal-body">
                                                        <div class="form-group  ">

                                                            <div class="form-group row">
                                                                <label for="Student">كود الطالب</label>
                                                                <input id="Student" name="Student" class="form-control mb-3" type="text" required='true'>
                                                            </div>

                                                            <div class="form-group row">
                                                                <label for="Name">الأسم الجديد</label>
                                                                <input id="Name" name="Name" class="form-control mb-3" type="text" required='true'>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                                        <button type="submit" class="btn btn-primary">تحديث</button>
                                                    </div>
                                                </form>

                                                </div>
                                            </div>
                                        </div>
                                <?php } ?>
                                <table id="DataTable" class="table table-bordered text-center DataTable  ">
                                    <thead>
                                        <tr>
                                        <th class="text-nowrap" scope="col">التاريخ</th>
                                        <th class="text-nowrap" scope="col">الكود</th>
                                        <th class="text-nowrap" scope="col">المعلم</th>
                                        <th class="text-nowrap" scope="col">اسم الطالب</th>
                                        <th class="text-nowrap" scope="col"> المدة </th>
                                        <th class="text-nowrap" scope="col"> الحالة </th>
                                        <?php if (isset($_SESSION['manager']) ) { ?>
                                        <th class="text-nowrap" scope="col"> إجراء </th>
                                        </tr>
                                        <?php } ?>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($row as $key => $value) { 
                                        $ID = $value['ID'];
                                        $find = array("trail","Rescheduled","-");
                                        $replace = array("","","");
                                        $string = str_replace($find,$replace,$value['S_name']);
                                        $Name = preg_replace('/[0-9]+/','', $string);
                                        if (empty($Name)) { $Name = $value['S_code']; }
                                        $stringCode = $value['S_code'];
                                        $T_code_stmt = $con->prepare("SELECT `Name` FROM teachers WHERE Code = ? ");
                                        $T_code_stmt->execute(array($value['T_code']));
                                        $T_code = $T_code_stmt->fetch();
                                        $rowCount = $T_code_stmt->rowCount();
                                        if ($rowCount > 0) { $stringT_code =$T_code['Name']; }else { $stringT_code = 'Old_System'; }
                                        if (date("Y_m_d", strtotime($value['date'])) == "1970_01_01") { $date= "Old_System"; }else { $date = date("Y-m-d", strtotime($value['date'])); }
                                        if ($value['Duration'] > 0) { $Duration = $value['Duration']; }else { $Duration = 30; }
                                        if ($value['status'] == "") { $status= "Old_System"; }else { $status = $value['status']; }
                                        ?>
                                        <tr>
                                        <td class="fw-bold fs-5 text-nowrap"><?php echo $date ;?></td>
                                        <td class="fw-bold fs-5 text-nowrap"><?php echo $stringCode ?></td>
                                        <td class="fw-bold fs-5 text-nowrap"><?php echo $stringT_code ; ?></td>
                                        <td class="fw-bold fs-5 text-nowrap"><?php echo $Name ?></td>
                                        <td class="fw-bold fs-5 text-nowrap"><?php echo $Duration ?></td>
                                        <td class="fw-bold fs-5 text-nowrap"><?php echo $status?></td>

                                        <?php if (isset($_SESSION['manager']) ) { ?>
                                        <td class="fw-bold fs-5 text-nowrap">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#E<?php echo $ID ;?>">
                                                    تعديل
                                                </button>
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#D<?php echo $ID ;?>">
                                                    حذف
                                                </button>
                                                
                                            </div>
                                        </td>
                                        <?php } ?>

                                        </tr>

                                        <?php if (isset($_SESSION['manager']) ) { ?>
                                        <!-- E-Modal -->
                                        <div class="modal fade" id="E<?php echo $ID ;?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="E<?php echo $ID ;?>Label" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="E<?php echo $ID ;?>Label">تعديل الحصة</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form class="forms-sample" action="in-to-db?do=Update_History" method="post">
                                                    <div class="modal-body">
                                                        <div class="form-group  ">
                                                            <div  style="display: none;" class="form-group row ">
                                                                <input type="hidden" id="ID" value="<?php echo $ID ; ?>" name="ID" >
                                                            </div>

                                                            <div class="form-group row">
                                                                <label for="Student">كود الطالب</label>
                                                                <select id="Student" name="Student" class="form-control form-select form-select-lg mb-3" aria-label=".form-select-lg example"required='true'>
                                                                    <option selected  value="<?php echo $value['S_code'] ?>"><?php echo $value['S_code'] ?></option>
                                                                </select> 
                                                            </div>

                                                            <div class="form-group row">
                                                                <label for="Teacher">المعلم</label>
                                                                <select id="Teacher" name="Teacher" class="form-control form-select form-select-lg mb-3" aria-label=".form-select-lg example"required='true'>
                                                                    <option selected  value="<?php echo $value['T_code'] ?>"><?php echo $value['T_code'] ?></option>
                                                                </select>
                                                            </div>

                                                            <div class="form-group row">
                                                                <label for="Duration">اسم الطالب</label>
                                                                <input class="form-control"  type="text" id="StudentName" name="StudentName" value="<?php echo $value['S_name'] ?>"   required='true'>
                                                            </div> 

                                                            <div class="form-group row    ">
                                                                <label for="StudentName"> تاريخ الحصة</label>
                                                                <input class="form-control"  type="date" id="date" name="date" value="<?php echo $value['date'] ?>" required='true'>
                                                            </div> 

                                                            <div class="form-group row">
                                                                <label for="Duration">مدة الحصة</label>
                                                                <input class="form-control" type="text" name="Duration" value="<?php echo $value['Duration'] ?>"  required='true'>
                                                            </div> 

                                                            <div class="form-group row">
                                                                <label for="Duration">حالة الحصة</label>
                                                                <input class="form-control" type="text" name="status" value="<?php echo $value['status'] ?>"  required='true'>
                                                            </div> 
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                                        <button type="submit" class="btn btn-primary">تحديث</button>
                                                    </div>
                                                </form>

                                                </div>
                                            </div>
                                        </div>

                                        <!-- D-Modal -->
                                        <div class="modal fade" id="D<?php echo $ID ;?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="D<?php echo $ID ;?>Label" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="D<?php echo $ID ;?>Label">حذف الحصة</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    هل انت متأكد من حذف الحصة ؟
                                                </div>
                                                <form action="Delete" method="Get" >
                                                    <input type="hidden" name="ID" readonly   value="<?php echo $ID ; ?>"  required='true'> 
                                                    <input type="hidden" name="code" readonly   value="<?php echo $value['S_code'] ; ?>"  required='true'> 
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                                        <button type="submit" class="btn btn-danger">حذف</button>
                                                    </div>
                                                </form>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>

                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                <?php }  ?>

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
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
    $Active_stmt = $con->prepare("SELECT * FROM ArchivedStudents WHERE Code LIKE ?");
    $Active_stmt->execute(array("%$search %"));
    $search_data=$Active_stmt->fetchAll();
    $Active_count = $Active_stmt->rowCount(); 
    if (isset($_GET['Start']) AND isset($_GET['End'])) {
        $current_month = date("Y-m-d", strtotime($_GET['Start']));
        $Next_month = date("Y-m-d", strtotime($_GET['End']));
      }else {
        $current_month = 0;
        $Next_month = date("Y-m-d");
      } 

    if ($Active_count == 0 ) { ?>
    <main id="main" class="main">
        <div class="container">
            <section class="section dashboard">
                <div class="rtl">
                    <div class="row">
                        <div class="col-lg-6 m-auto mh-100">
                            <div class="card">
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
    } ?>
<main id="main" class="main">
    <div class="container">

    <?php if ($Active_count == 1) {
        foreach ($search_data as $key => $value) { ?>
        <div class="pagetitle">
            
            <h1>Archived Students </h1>
            <nav>
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active">Archived Students</li>
                </ol>
            </nav>
        </div>
    <?php } } ?>


        <section class="section dashboard">
            <div class="rtl">

                <?php foreach ($search_data as $data) { 

                    $H_stmt = $con->prepare("SELECT * FROM ArchivedHistory WHERE S_code = ? AND `date` >= ? AND `date` <= ? ORDER BY `Timestamp` ASC ");
                    $H_stmt->execute(array($data['Code'],$current_month,$Next_month));
                    $count = $H_stmt->rowCount();
                    $row = $H_stmt->fetchAll();


                    $Remained = $con->prepare("SELECT * FROM ArchivedStudents WHERE Code=? ");
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
                    $ArchivedStudentsExport = $con->prepare("SELECT * FROM ArchivedStudentsExport WHERE Code = ? AND `status` = ?");
                    $ArchivedStudentsExport->execute(array($data['Code'],1));
                    $ArchivedStudentsExport_count = $ArchivedStudentsExport->rowCount();
                    $ArchivedStudentsExportArry = $ArchivedStudentsExport->fetchAll();

                    ?>
                        <div class="card card-body pt-3 text-danger">
                            <h5 class="card-title text-center"><?php echo $data['Code'] ; ?></h5>
                            <h1 class="rtl text-center"> هذا الكود مؤرشف </h1>
                            <h5 class="rtl text-center"> اخر حصة حضرها بتاريخ <?php echo $data['Last_class'] ?> </h5>
                            <p class="rtl text-center"> اذا اراد الطالب الاستئناف قدم طلب لاستخراج الكود من الأررشيف </p>
                            <?php if (isset($_SESSION['manager'])  OR isset($_SESSION['CustomerServiceManager']) OR isset($_SESSION['CustomerService'])) { ?> 
                                <?php if ($ArchivedStudentsExport_count > 0) { ?>
                                    <button disabled class="btn btn-warning"> جارى مراجعة طلب الأستخراج </button>
                                <?php }else { ?>
                                    <a <?php echo "href='ArchivedStudentsExportRequestApi?ExportRequest=".urlencode($data['Code'])."&Who=".$_SESSION['Who']."'" ?> class="btn btn-primary" > قدم طلب للأستخراج </a>
                                <?php } ?>
                            <?php } ?>

                        </div>
                    <div class="row">
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
                                    <p class="card-details mb-0"   > <i class="fa-solid fa-stopwatch"></i> &nbsp;  الحصص المتبقية : &nbsp;<small style=" font-size: 1.25em;<?php if ($Remained < 0) { echo ' color: green;';}elseif ($Remained > 0) { echo ' color: red;';}else {}?>"><?php echo $Remained ; ?></small></p>
                                    <p class="card-details mb-0"   > <i class="fa-solid fa-stopwatch"></i> &nbsp; عدد مرات الدفع : &nbsp;<small style=" font-size: 1.25em;"><?php echo $Journal_count ; ?></small></p>
                                    <p class="card-details mb-0"   > <i class="fa-solid fa-calendar-days"></i> &nbsp;  تاريخ اخر حصة : &nbsp;<small style=" font-size: 1.25em;"><?php echo $data['Last_class'] ; ?></small></p>
                                </div>
                            </div>
                        <?php } ?>
                        
                    </div>

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

                                <table id="DataTable" class="table table-bordered text-center DataTable  ">
                                    <thead>
                                        <tr>
                                        <th class="text-nowrap" scope="col">التاريخ</th>
                                        <th class="text-nowrap" scope="col">الكود</th>
                                        <th class="text-nowrap" scope="col">المعلم</th>
                                        <th class="text-nowrap" scope="col">اسم الطالب</th>
                                        <th class="text-nowrap" scope="col"> المدة </th>
                                        <th class="text-nowrap" scope="col"> الحالة </th>
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
<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['CustomerService']) AND !isset($_SESSION['manager']) AND !isset($_SESSION['CustomerServiceManager']) ) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ; 
if (isset($_GET['Y'])) {
  $Y = $_GET['Y'] ;
}else {
  $_GET['Y'] = date("Y");
  $Y = $_GET['Y'] ;
}
if (isset($_GET['Start'])) {
  if ($_GET['Start'] == 1 ) {
    $this_month = date("$Y-01-01") ;
    $Next_month = date("$Y-m-t", strtotime(date("$Y-01-01"))) ;
  }
  elseif ($_GET['Start'] == 2) {
    $this_month = date("$Y-02-01") ;
    $Next_month = date("$Y-m-t", strtotime(date("$Y-02-01"))) ;
  }
  elseif ($_GET['Start'] == 3) {
    $this_month = date("$Y-03-01") ;
    $Next_month = date("$Y-m-t", strtotime(date("$Y-03-01"))) ;
  }
  elseif ($_GET['Start'] == 4) {
    $this_month = date("$Y-04-01") ;
    $Next_month = date("$Y-m-t", strtotime(date("$Y-04-01"))) ;
  }
  elseif ($_GET['Start'] == 5) {
    $this_month = date("$Y-05-01") ;
    $Next_month = date("$Y-m-t", strtotime(date("$Y-05-01"))) ;
  }
  elseif ($_GET['Start'] == 6) {
    $this_month = date("$Y-06-01") ;
    $Next_month = date("$Y-m-t", strtotime(date("$Y-06-01"))) ;
  }
  elseif ($_GET['Start'] == 7) {
    $this_month = date("$Y-07-01") ;
    $Next_month = date("$Y-m-t", strtotime(date("$Y-07-01"))) ;
  }
  elseif ($_GET['Start'] == 8) {
    $this_month = date("$Y-08-01") ;
    $Next_month = date("$Y-m-t", strtotime(date("$Y-08-01"))) ;
  }
  elseif ($_GET['Start'] == 9) {
    $this_month = date("$Y-09-01") ;
    $Next_month = date("$Y-m-t", strtotime(date("$Y-09-01"))) ;
  }
  elseif ($_GET['Start'] == 10) {
    $this_month = date("$Y-10-01") ;
    $Next_month = date("$Y-m-t", strtotime(date("$Y-10-01"))) ;
  }
  elseif ($_GET['Start'] == 11) {
    $this_month = date("$Y-11-01") ;
    $Next_month = date("$Y-m-t", strtotime(date("$Y-11-01"))) ;
  }
  elseif ($_GET['Start'] == 12) {
    $this_month = date("$Y-12-01") ;
    $Next_month = date("$Y-m-t", strtotime(date("$Y-12-01"))) ;
  }else {
    $_GET['Start'] = date("m");
    $this_month = date("$Y-m-01");
    $Next_month = date("$Y-m-t", strtotime(date("$Y-m-d")));
  }

}else {
  $_GET['Start'] = date("m");
  $this_month = date("$Y-m-01");
  $Next_month = date("$Y-m-t", strtotime(date("$Y-m-d")));
}

if (isset($_GET['Who']) AND in_array($_GET['Who'],array('AbdelRahman','Belal','Hamza','Ramadan','Bedo','All'))) {
  $_SESSION['Who'] = $_GET['Who'];
}
if (isset($_SESSION['Who']) AND in_array($_SESSION['Who'],array('AbdelRahman','Belal','Hamza','Ramadan','Bedo'))) {
  $Who = $_SESSION['Who'];
}else {
  $Who = "All";
}

if (isset($_GET['Code'])) {
  $search = $_GET['Code'] ;
  $stmt = "WHERE Code LIKE ?  AND `Type` IN ('1','5')";
  $execute = array("%$search %") ;
}else {
  if ($Who =="All") {
    $stmt = "WHERE  `Date_of_Payment`>=? AND `Date_of_Payment`<=?  AND `Type` IN ('1','5')";
    $execute = array($this_month,$Next_month) ;
  }else {
    $stmt = "WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=?  AND `Type` IN ('1','5')";
    $execute = array($Who,$this_month,$Next_month) ;
  }
}

$Journal = $con->prepare("SELECT * FROM Journal $stmt ORDER BY `Timestamp` ASC ");
$Journal->execute($execute);
$Journal_count = $Journal->rowCount();
$JournalArry = $Journal->fetchAll();

$WalletArry =[];
$InstapayArry =[];
$BankArry =[];
$EasyKashArry =[];
$WestrenArry =[];
$PayPalArry =[];
$CashArry =[];

foreach ($JournalArry as $key => $value) {
  if ($value['Payment_Way'] == 'Wallet') {
    $WalletArry[$key]['Amount'] = $value['Amount'];
  }
  elseif ($value['Payment_Way'] == 'Instapay') {
    $InstapayArry[$key]['Amount'] = $value['Amount'];
  }
  elseif ($value['Payment_Way'] == 'Bank') {
    $BankArry[$key]['Amount'] = $value['Amount'];
  }
  elseif ($value['Payment_Way'] == 'EasyKash') {
    $EasyKashArry[$key]['Amount'] = $value['Amount'];
  }
  elseif ($value['Payment_Way'] == 'Westren') {
    $WestrenArry[$key]['Amount'] = $value['Amount'];
  }
  elseif ($value['Payment_Way'] == 'PayPal') {
    $PayPalArry[$key]['Amount'] = $value['Amount'];
  }
  elseif ($value['Payment_Way'] == 'Cash') {
    $CashArry[$key]['Amount'] = $value['Amount'];
  }
}
$Total =  array_sum(array_column($JournalArry, 'Amount')) ;
$Wallet =  array_sum(array_column($WalletArry, 'Amount')) ;
$Instapay =  array_sum(array_column($InstapayArry, 'Amount')) ;
$Bank =  array_sum(array_column($BankArry, 'Amount')) ;
$EasyKash =  array_sum(array_column($EasyKashArry, 'Amount')) ;
$Westren =  array_sum(array_column($WestrenArry, 'Amount')) ;
$PayPal =  array_sum(array_column($PayPalArry, 'Amount')) ;
$Cash =  array_sum(array_column($CashArry, 'Amount')) ;
?>
<main id="main" class="main">
  <div class="container">
    <div class="pagetitle">
      <nav>
        <ol class="breadcrumb">
          <li class="">        
            <button class="btn btn-primary fs-6 fw-bold text-white" onclick="window.location= 'CustomerService' ">
            <i class="bi bi-box-arrow-left"></i>
            <span>الخروج</span>
            </button.><!-- End Dashboard Iamge Icon -->
          </li>
        </ol>
      </nav>
      <h1>Journal Page</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Journal</li>
          <li class="breadcrumb-item active"><?php echo $this_month ?></li>
          <li class="breadcrumb-item active"><?php echo $Next_month ?></li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    
    <section class="section dashboard">
      <div class="row">

        <?php if (!isset($_GET['Code'])) { ?>
          <div class="col-lg-12">
              <div class="card">
                  <div class="card-body col-12 col-lg-6 m-auto">
                    <h5 class="card-title pb-0 fs-4 fw-semibold text-center"> <?php echo " From :".$this_month." | "." To :".$Next_month ;?>  </h5> 
                        <form id="form-2"class="mt-2 w-100 m-auto" method="GET" action="">
                        <select  onchange="this.form.submit()" name="Start" class="form-select" aria-label="Default select example">
                          <option <?php if(isset($_GET['Start']) And $_GET['Start'] == "01" ) {echo "selected";} ?> value="01">يناير</option>
                          <option <?php if(isset($_GET['Start']) And $_GET['Start'] == "02" ) {echo "selected";} ?> value="02">فبراير</option>
                          <option <?php if(isset($_GET['Start']) And $_GET['Start'] == "03" ) {echo "selected";} ?> value="03">مارس</option>
                          <option <?php if(isset($_GET['Start']) And $_GET['Start'] == "04" ) {echo "selected";} ?> value="04">ابريل</option>
                          <option <?php if(isset($_GET['Start']) And $_GET['Start'] == "05" ) {echo "selected";} ?> value="05">مايو</option>
                          <option <?php if(isset($_GET['Start']) And $_GET['Start'] == "06" ) {echo "selected";} ?> value="06">يونيو</option>
                          <option <?php if(isset($_GET['Start']) And $_GET['Start'] == "07" ) {echo "selected";} ?> value="07">يوليو</option>
                          <option <?php if(isset($_GET['Start']) And $_GET['Start'] == "08" ) {echo "selected";} ?> value="08">اغسطس</option>
                          <option <?php if(isset($_GET['Start']) And $_GET['Start'] == "09" ) {echo "selected";} ?> value="09">سبتمبر</option>
                          <option <?php if(isset($_GET['Start']) And $_GET['Start'] == "10" ) {echo "selected";} ?> value="10">اكتوبر</option>
                          <option <?php if(isset($_GET['Start']) And $_GET['Start'] == "11" ) {echo "selected";} ?> value="11">نوفمبر</option>
                          <option <?php if(isset($_GET['Start']) And $_GET['Start'] == "12" ) {echo "selected";} ?> value="12">ديسمبر</option>
                        </select>

                        <select  onchange="this.form.submit()" name="Y" class="form-select mt-2" aria-label="Default select example">
                          <?php for ($i=2020; $i < date('Y', strtotime('+1 year')) ; $i++) { ?>
                          <option <?php if(isset($_GET['Y']) And $_GET['Y'] == $i ) {echo "selected";}?> value="<?php echo $i; ?>"> <?php echo $i; ?> </option>
                          <?php } ?>

                        </select>
                          <?php if (isset($_GET['Who'])) { ?>
                            <input type="hidden" name="Who" value="<?php echo $_GET['Who']; ?>">
                          <?php } ?>
                        </form>


                        <?php if (isset($_SESSION['manager']) OR isset($_SESSION['CustomerServiceManager'])) { ?>
                        <h5 class="card-title pb-0 fs-4 fw-semibold text-center"> </h5> 
                        <form id="form-2"class="mt-2 w-100 m-auto" method="GET" action="">
                        <select onchange="this.form.submit()" name="Who" class="form-select" aria-label="Default select example">
                          <option <?php if($Who == "Hamza" )      {echo "selected";} ?> value="Hamza">حمزة</option>
                          <option <?php if($Who == "Belal" )      {echo "selected";} ?> value="Belal">بلال</option>
                          <option <?php if($Who == "Ramadan" )    {echo "selected";} ?> value="Ramadan">رمضان</option>
                          <option <?php if($Who == "AbdelRahman" ){echo "selected";} ?> value="AbdelRahman">عبد الرحمن</option>
                          <option <?php if($Who == "Bedo" ){echo "selected";} ?> value="Bedo">  بيدو </option>
                          <option <?php if($Who == "All" )        {echo "selected";} ?> value="All">الجميع</option>
                        </select>
                        <?php if (isset($_GET['Start'])) { ?>
                          <input type="hidden" name="Start" value="<?php echo $_GET['Start']; ?>">
                        <?php } ?>
                        </form>
                        <?php } ?>
                  </div>
              </div>
          </div>
        <?php } ?>

      <div class="col-lg-12">
          <div class="card">
              <div class="card-body col-12 col-lg-6 m-auto">
                  <h5 class="card-title pb-0 fs-4 fw-semibold text-center"> عرض بيانات طالب محدد </h5> 
                  <form id="form-2"class="mt-2 w-100 m-auto" method="GET" action="">
                  <div class="input-group">
                    <input type="text" class="form-control" name="Code" placeholder=" اكتب كود الطالب فقط بدون الاسم " <?php if (isset($_GET['Code'])) { ?> value="<?php echo $_GET['Code']; ?>" <?php } ?> >
                    <button class="btn btn-outline-primary " type="submit"> عرض </button>
                    <?php if (isset($_GET['Code'])) { ?>
                    <a href="CustomerServiceJournalView" class="btn btn-outline-primary">إلغاء</a>
                    <?php } ?>
                  </div>
                  </form>
              </div>
          </div>
      </div>

      <div class="col-lg-12">
        <div class="card">
          <div class="row col-12 card-body flex-row-reverse m-auto mb-5">
          <h2 class=" text-center p-3"> <?php if (isset($_GET['Code'])) { echo $_GET['Code'] ; }else { echo $Who ; } ?>  </h2> 
            <!-- Revenue Card -->
            <div class="col-12 col-md-3">
              <div class=" info-card  sales-card">
                <div class="card-body border border-2 border-danger shadow-sm rounded-3 w-auto d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title pb-0 fs-4 fw-semibold text-danger text-center"> <img src="./assets/img/Total.png" class="w-100" alt=""> </h5>
                  <div class="d-flex align-items-center justify-content-center">
                    <h6 class=" fs-4 fw-semibold  fs-3 fw-semibold text-danger text-center"><?php echo $Total ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Revenue Card -->
            <div class="col-12 col-md-3">
              <div class=" info-card">
                <div class="card-body border border-2 shadow-sm rounded-3 w-auto d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title pb-0 fs-4 fw-semibold text-center">   <img src="./assets/img/instapay.png" class="w-100" alt="">	 </h5>
                  <div class=" d-flex align-items-center justify-content-center">
                    <h6 class=" fs-4 fw-semibold text-center"><?php echo $Instapay ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Revenue Card -->
            <div class="col-12 col-md-3">
              <div class=" info-card">
                <div class="card-body border border-2 shadow-sm rounded-3 w-auto d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title pb-0 fs-4 fw-semibold text-center">  <img src="./assets/img/bank.png" class="w-100" alt=""> </h5>
                  <div class=" d-flex align-items-center justify-content-center">
                    <h6 class=" fs-4 fw-semibold text-center"><?php echo $Bank ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Revenue Card -->
            <div class="col-12 col-md-3">
              <div class=" info-card">
                <div class="card-body border border-2 shadow-sm rounded-3 w-auto d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title pb-0 fs-4 fw-semibold text-center">   <img src="./assets/img/vodafone.png" class="w-100" alt="">	 </h5>
                  <div class=" d-flex align-items-center justify-content-center">
                    <h6 class=" fs-4 fw-semibold text-center"><?php echo $Wallet ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Revenue Card -->
            <div class="col-12 col-md-3">
              <div class=" info-card">
                <div class="card-body border border-2 shadow-sm rounded-3 w-auto d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title pb-0 fs-4 fw-semibold text-center">   <img src="./assets/img/easykash.png" class="w-100" alt=""> </h5>
                  <div class=" d-flex align-items-center justify-content-center">
                    <h6 class=" fs-4 fw-semibold text-center"><?php echo $EasyKash ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Revenue Card -->
            <div class="col-12 col-md-3">
              <div class=" info-card">
                <div class="card-body border border-2 shadow-sm rounded-3 w-auto d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title pb-0 fs-4 fw-semibold text-center"> <img src="./assets/img/paypal.png" class="w-100" alt=""> </h5>
                  <div class=" d-flex align-items-center justify-content-center">
                    <h6 class=" fs-4 fw-semibold text-center"><?php echo $PayPal ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Revenue Card -->
            <div class="col-12 col-md-3">
              <div class=" info-card">
                <div class="card-body border border-2 shadow-sm rounded-3 w-auto d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title pb-0 fs-4 fw-semibold text-center"> <img src="./assets/img/westernunion.png" class="w-100" alt=""> </h5>
                  <div class=" d-flex align-items-center justify-content-center">
                    <h6 class=" fs-4 fw-semibold text-center"><?php echo $Westren ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Revenue Card -->
            <div class="col-12 col-md-3">
              <div class=" info-card">
                <div class="card-body border border-2 shadow-sm rounded-3 w-auto d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title pb-0 fs-4 fw-semibold text-center"> <img src="./assets/img/cash.png" class="w-100" alt=""></h5>
                  <div class=" d-flex align-items-center justify-content-center">
                    <h6 class=" fs-4 fw-semibold text-center"><?php echo $Cash ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
    
              
          </div>
        </div>
      </div>

      <div class="col-12">
          <div class="card">
            <h5 class="card-title pb-0 fs-4 fw-semibold text-center"> Journal Book</h5>
            <div class="d-flex p-3">
              <a id="ApprovAll" class="btn btn-primary w-25 m-auto" href="" style="display: none;">تقفيل المحدد </a>
              <a id="DeletAll" onclick="return confirm('هل انت متأكد من حذف هذة الأكواد الحذف سيؤدى الى خصم عدد الحصص من رصيد الطالب وخصم شهر من تاريخ التجديد ');" class="btn btn-danger w-25 m-auto" href="" style="display: none;">حذف المحدد </a>
            </div>
            <div class="card-body  overflow-auto text-center ">
              <table  class="table table-border text-center w-100 DataTable rtl">
                <thead >
                  <tr>
                    <th   class="fw-bold fs-5 text-end" > Timestamp </th>
                    <th   class="fw-bold fs-5 text-end" > الكود </th>
                    <th   class="fw-bold fs-5 text-end" > تجديد#تجريبي </th>
                    <th   class="fw-bold fs-5 text-end" > التاريخ </th>
                    <th   class="fw-bold fs-5 text-end" > الأسم </th>
                    <th   class="fw-bold fs-5 text-end" > المبلغ </th>
                    <th   class="fw-bold fs-5 text-end" > الطريقة </th>
                    <th   class="fw-bold fs-5 text-end" > المسؤل </th>
                    <th   class="fw-bold fs-5 text-end" > الحصص_المضافة </th>
                    <?php if (isset($_SESSION['manager']) OR isset($_SESSION['CustomerServiceManager'])) { ?>
                    <th   class="fw-bold fs-5 text-center" > تحديد </th>
                    <th   class="fw-bold fs-5 text-center" > إجراء </th>
                    <?php  } ?>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  if ($Journal_count > 0) {
                    foreach ( $JournalArry as $value) { 
                      $StudentCodereplace = str_replace(" ","_",$value['Code']);
                      $CodeExplode = explode(' ',$value['Code']);
                      $StudentCodeExplode = $CodeExplode[0] ;
                      ?>
                    <?php if ($value['Type'] == 5 ) { ?>
                      <tr class="table-success" >
                          <td class="fw-bold fs-5 text-end"> <?php echo $value['Timestamp'] ?> </td>
                          <td class="fw-bold fs-5 text-end" ><a href="search?Code=<?php echo $StudentCodeExplode ?>"><?php echo $value['Code'] ?></a> </td>
                          <td class="fw-bold fs-5 text-end"> <?php echo $value['Renewal_VS_Trail'] ?> </td>
                          <td class="fw-bold fs-5 text-end"> <?php echo date('Y_m_d', strtotime($value['Date_of_Payment'])) ?> </td>
                          <td class="fw-bold fs-5 text-end"> <?php echo $value['Name'] ?> </td>
                          <td class="fw-bold fs-5 text-end"> <?php echo $value['Amount'] ?> </td>
                          <td class="fw-bold fs-5 text-end"> <?php echo $value['Payment_Way'] ?> </td>
                          <td class="fw-bold fs-5 text-end"> <?php echo $value['Who'] ?> </td>
                          <td class="fw-bold fs-5 text-center">   (  <?php echo "تــم الـــتـــقـــفـــيـــل" ?> )  </td>
                          <?php if (isset($_SESSION['manager']) OR isset($_SESSION['CustomerServiceManager'])) { ?>
                          <td class="fw-bold fs-5 text-end">
                          </td>
                          <td class="fw-bold fs-5 text-end">
                            <div class="btn-group ltr">
                            <a <?php echo " href='CustomerServiceJournalApi?UnApproval=".$value['ID']."'" ?> class="btn btn-primary"> الغاء التقفيل </a>
                                <a  onclick="return confirm('هل انت متأكد من حذف كود <?php echo $StudentCodeExplode ?>  الحذف سيؤدى الى خصم عدد الحصص من رصيد الطالب وخصم شهر من تاريخ التجديد ');" <?php echo " href='CustomerServiceJournalApi?Delete=".$value['ID']."'" ?> class="btn btn-danger">حذف</a>
                              <a <?php echo " href='CustomerServiceJournalEdite?Update=".$value['ID']."'" ?> class="btn btn-success">تعديل</a>
                            </div>
                          </td>
                          <?php  } ?>
                        </tr> 
                      <?php }else{?>
                        <tr class="table-warning" >
                          <td class="fw-bold fs-5 text-end"> <?php echo $value['Timestamp'] ?> </td>
                          <td class="fw-bold fs-5 text-end"><a href="search?Code=<?php echo $StudentCodeExplode ?>"><?php echo $value['Code']?></a> </td>
                          <td class="fw-bold fs-5 text-end"> <?php echo $value['Renewal_VS_Trail'] ?> </td>
                          <td class="fw-bold fs-5 text-end"> <?php echo date('Y_m_d', strtotime($value['Date_of_Payment'])) ?> </td>
                          <td class="fw-bold fs-5 text-end"> <?php echo $value['Name'] ?> </td>
                          <td class="fw-bold fs-5 text-end"> <?php echo $value['Amount'] ?> </td>
                          <td class="fw-bold fs-5 text-end"> <?php echo $value['Payment_Way'] ?> </td>
                          <td class="fw-bold fs-5 text-end"> <?php echo $value['Who'] ?> </td>
                          <td class="fw-bold fs-5 text-center"> <?php echo $value['Subscription'] ?> </td>
                          <?php if (isset($_SESSION['manager']) OR isset($_SESSION['CustomerServiceManager'])) { ?>
                          <td class="fw-bold fs-5 text-end">
                            <input type="checkbox" class="form-check-input form-control form-control-lg" id="<?php echo $value['ID']?>_checkbox"  onchange="Check('<?php echo $value['ID']?>_checkbox',<?php echo $value['ID']?>)">
                          </td>
                          <td class="fw-bold fs-5 text-end">
                            <div class="btn-group ltr">
                              <a <?php echo " href='CustomerServiceJournalApi?Approv=".$value['ID']."'" ?> class="btn btn-warning"> التقفيل يدويا </a>
                              <a  onclick="return confirm('هل انت متأكد من حذف كود <?php echo $StudentCodeExplode ?>  الحذف سيؤدى الى خصم عدد الحصص من رصيد الطالب وخصم شهر من تاريخ التجديد ');" <?php echo " href='CustomerServiceJournalApi?Delete=".$value['ID']."'" ?> class="btn btn-danger">حذف</a>
                              <a <?php echo " href='CustomerServiceJournalEdite?Update=".$value['ID']."'" ?> class="btn btn-success">تعديل</a>
                            </div>
                          </td>
                          <?php  } ?>
                        </tr>
                      <?php  } ?>
                    <?php }?>
                  <?php } ?>
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
<script>
var newArray = [];
var ApprovAll = document.getElementById('ApprovAll');
var DeletAll = document.getElementById('DeletAll');
function Check(checkBox,value) {
  var checkBox = document.getElementById(checkBox);
  checked = $("input[type=checkbox]:checked").length;
  if (checkBox.checked == true){
    ApprovAll.style.display = "block";
    DeletAll.style.display = "block";
    newArray.push(value);
  } else {
    let removeIndex = newArray.indexOf(value);
    if(removeIndex !== -1){
      newArray.splice(removeIndex,1);}

    if(checked == 0 ) {
      ApprovAll.style.display = "none";
      DeletAll.style.display = "none";
    }
  }
  let text = newArray.toString();
  ApprovAll.href= "CustomerServiceJournalApi?ApprovAll="+text
  DeletAll.href= "CustomerServiceJournalApi?DeletAll="+text
  console.log(text);

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
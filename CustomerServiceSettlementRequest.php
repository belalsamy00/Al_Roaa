<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['CustomerService']) AND !isset($_SESSION['manager']) AND !isset($_SESSION['CustomerServiceManager']) ) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ; 
if (isset($_GET['Start'])) {
  if ($_GET['Start'] == 1 ) {
    $this_month = date("Y-01-01") ;
    $Next_month = date("Y-01-t") ;
  }
  elseif ($_GET['Start'] == 2) {
    $this_month = date("Y-02-01") ;
    $Next_month = date("Y-m-t", strtotime(date("Y-02-01"))) ;
  }
  elseif ($_GET['Start'] == 3) {
    $this_month = date("Y-03-01") ;
    $Next_month = date("Y-m-t", strtotime(date("Y-03-01"))) ;
  }
  elseif ($_GET['Start'] == 4) {
    $this_month = date("Y-04-01") ;
    $Next_month = date("Y-m-t", strtotime(date("Y-04-01"))) ;
  }
  elseif ($_GET['Start'] == 5) {
    $this_month = date("Y-05-01") ;
    $Next_month = date("Y-m-t", strtotime(date("Y-05-01"))) ;
  }
  elseif ($_GET['Start'] == 6) {
    $this_month = date("Y-06-01") ;
    $Next_month = date("Y-m-t", strtotime(date("Y-06-01"))) ;
  }
  elseif ($_GET['Start'] == 7) {
    $this_month = date("Y-07-01") ;
    $Next_month = date("Y-m-t", strtotime(date("Y-07-01"))) ;
  }
  elseif ($_GET['Start'] == 8) {
    $this_month = date("Y-08-01") ;
    $Next_month = date("Y-m-t", strtotime(date("Y-08-01"))) ;
  }
  elseif ($_GET['Start'] == 9) {
    $this_month = date("Y-09-01") ;
    $Next_month = date("Y-m-t", strtotime(date("Y-09-01"))) ;
  }
  elseif ($_GET['Start'] == 10) {
    $this_month = date("Y-10-01") ;
    $Next_month = date("Y-m-t", strtotime(date("Y-10-01"))) ;
  }
  elseif ($_GET['Start'] == 11) {
    $this_month = date("Y-11-01") ;
    $Next_month = date("Y-m-t", strtotime(date("Y-11-01"))) ;
  }
  elseif ($_GET['Start'] == 12) {
    $this_month = date("Y-12-01") ;
    $Next_month = date("Y-m-t", strtotime(date("Y-12-01"))) ;
  }else {
    $this_month = date("Y-m-01");
    $Next_month = date("Y-m-t", strtotime(date("Y-m-d")));
  }

}else {
  $this_month = date("Y-m-01");
  $Next_month = date("Y-m-t", strtotime(date("Y-m-d")));
}

if (isset($_SESSION['Who']) AND in_array($_SESSION['Who'],array('AbdelRahman','Belal','Hamza','Ramadan','Bedo'))) {
  $Who = $_SESSION['Who'];
}else {
  $Who = "All";
} 
if ($Who =="All") {
  $stmt = "WHERE  `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND Type = ? OR  `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND Type = ?  OR   `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND Type = ? OR  `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND Type = ? OR  `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND Type = ?  OR   `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND Type = ? ORDER BY `Timestamp` DESC ";
  $execute = array($this_month,$Next_month,2,$this_month,$Next_month,3,$this_month,$Next_month,4,$this_month,$Next_month,7,$this_month,$Next_month,8,$this_month,$Next_month,9) ;
}else {
  $stmt = "WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND Type = ? OR `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND Type = ?  OR `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND Type = ? OR `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND Type = ? OR `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND Type = ?  OR `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND Type = ?ORDER BY `Timestamp` DESC ";
  $execute = array($Who,$this_month,$Next_month,2,$Who,$this_month,$Next_month,3,$Who,$this_month,$Next_month,4,$Who,$this_month,$Next_month,7,$Who,$this_month,$Next_month,8,$Who,$this_month,$Next_month,9) ;
}
$Journal = $con->prepare("SELECT * FROM Journal $stmt");
$Journal->execute($execute);
$Journal_count = $Journal->rowCount();
$JournalArry = $Journal->fetchAll();

$WalletArry =[];
$BankArry =[];
$EasyKashArry =[];
$WestrenArry =[];
$PayPalArry =[];
$CashArry =[];

foreach ($JournalArry as $key => $value) {
  if ($value['Payment_Way'] == 'Wallet') {
    $WalletArry[$key]['Amount'] = $value['Amount'];
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
    </div><!-- Center Page Title -->
    
    <section class="section dashboard">
      <div class="row">

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body col-12 col-lg-6 m-auto">
                <h5 class="card-title text-center"> <?php echo " From :".$this_month." | "." To :".$Next_month ;?>  </h5> 
                    <form id="form-2"class="mt-2 w-100 m-auto" method="GET" action="">
                    <select onchange="this.form.submit()" name="Start" class="form-select" aria-label="Default select example">
                      <option <?php if(!isset($_GET['Start']) ) {echo "selected";} ?>> أختر شهر </option>
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
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
              <h5 class="card-title text-center"> Journal Book</h5>
              <div class="card-body rtl overflow-auto text-center max-h">
                <table  class="table table-borderless text-center  ">
                  <thead >
                    <tr>
                      <th   class="fw-bold fs-5 text-center" > الكود </th>
                      <th   class="fw-bold fs-5 text-center" > التاريخ </th>
                      <th   class="fw-bold fs-5 text-center" > الأسم </th>
                      <th   class="fw-bold fs-5 text-center" > المسؤل </th>
                      <th   class="fw-bold fs-5 text-center" > المطلوب </th>
                      <th   class="fw-bold fs-5 text-center" > حالة الطلب </th>
                      <?php if (isset($_SESSION['manager']) OR isset($_SESSION['CustomerServiceManager']) ) { ?>
                      <th   class="fw-bold fs-5 text-center" > إجراء </th>
                      <?php } ?>
                    </tr>
                  </thead>
                  <tbody>
                    <?php  if ($Journal_count == 0) { ?>  <td  class="fw-bold fs-5" colspan="7"> لا توجد طلبات مسجلة </td>  <?php } ?>
                    <?php 
                    if ($Journal_count > 0) {
                      foreach ( $JournalArry as $value) { 
                        $CodeExplode = explode(' ',$value['Code']);
                        $StudentCodeExplode = $CodeExplode[0] ;
                        ?>

                      <tr>
                      
                        <td class="fw-bold fs-5 text-center"><a href="search?Code=<?php echo $StudentCodeExplode ?>"><?php echo $StudentCodeExplode ?></a> </td>
                        <td class="fw-bold fs-5 text-center"> <?php echo date('Y_m_d', strtotime($value['Date_of_Payment'])) ?> </td>
                        <td class="fw-bold fs-5 text-center"> <?php echo $value['Name'] ?> </td>
                        <td class="fw-bold fs-5 text-center"> <?php echo $value['Who'] ?> </td>
                        <td class="fw-bold fs-5 text-center"> 
                          
                          <?php
                          if ($value['Subscription'] == 1) {
                            $num = "حصة واحدة" ;
                          }elseif ($value['Subscription'] == 2) {
                            $num = " حصتان " ;
                          }elseif ($value['Subscription'] > 2 AND $value['Subscription'] < 11) {
                            $num = $value['Subscription']." "." حصص " ;
                          }elseif ($value['Subscription'] > 10) {
                            $num = $value['Subscription']." "." حصة " ;
                          }else {
                            $num = $value['Subscription']." "." حصة " ;
                          }
                          if ($value['Renewal_VS_Trail'] == "+" ) { $status = 1 ;echo "<span class= 'badge bg-danger'>"."خصم"." ".$num."</span>"; }else {$status = 2 ; echo "<span class= 'badge bg-success'>"."اضافة"." ".$num."</span>" ;}
                          if ($value['Type'] == 2 ) { $TypeUpdate = 3 ;}else {$TypeUpdate = 8 ;}?>
                        </td>

                        <td class="fw-bold fs-5 text-center"> 
                          <?php
                            if ($value['Type'] == 2 OR $value['Type'] == 7 ) {
                              echo  "<span class= 'badge bg-warning'> جارى المراجعة </span>";
                            }elseif ($value['Type'] == 3 OR $value['Type'] == 8 ) {
                              echo  "<span class= 'badge bg-success'> مقبول  </span>";
                            }else {
                              echo  "<span class= 'badge bg-danger'>  مرفوض </span>";
                            } 
                          ?>
                        </td>
                        <?php if (isset($_SESSION['manager']) OR isset($_SESSION['CustomerServiceManager']) ) { ?>
                        <td class="fw-bold fs-5 text-center">
                          <div class="btn-group ltr">
                          <?php
                            if ($value['Type'] == 2 OR $value['Type'] == 7 ) { ?>
                              <a <?php echo " href='CustomerServiceJournalApi?SettlementAccept=".$value['ID']."&Code=".urlencode($value['Code'])."&Subscription=".$value['Subscription']."&status=".$status."&TypeUpdate=".$TypeUpdate."'" ?> class="btn btn-success"> قبول </a>
                              <a  onclick="return confirm('هل انت متأكد من الرفض ؟');" <?php echo " href='CustomerServiceJournalApi?SettlementRefuse=".$value['ID']."'" ?> class="btn btn-danger"> رفض </a>
                            <?php }else {
                              echo "-" ;
                            }
                          ?>

                          </div>
                        </td>
                        <?php } ?>
                      </tr>
                      <tr >
                      <td class="fw-bold fs-5 text-center" colspan="7"> <?php echo $value['Note'] ?> <br>
                       <?php if ($value['Type'] == 7 ) { echo "<span class= 'badge bg-dark w-75'>"."هذا الطلب مؤقت ينتهى تأثيره فى يوم ".$value['Date_of_Payment']."</span>" ;}?> 
                       <?php if ($value['Type'] == 8 ) { echo "<span class= 'badge bg-dark w-75'>"."هذا الطلب مؤقت ينتهى تأثيره فى يوم ".$value['Date_of_Payment']."</span>" ;}?> 
                      <?php if ($value['Type'] == 9 ) { echo "<span class= 'badge bg-warning w-75'>"."هذا الطلب  انتهى تأثيره فى يوم ".$value['Date_of_Payment']."</span>" ;}?><hr></td>
                      </tr>
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
<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['CustomerService']) AND !isset($_SESSION['manager']) AND !isset($_SESSION['CustomerServiceManager']) ) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ; 
if (isset($_GET['ThisMonth'])) {
  $this_month = date("Y-m-01");
  $Next_month = date("Y-m-t");
  $this_month_T = date("Y-m-26",strtotime( '-1 month' ));
  $Next_month_T = date("Y-m-25");
}elseif (isset($_GET['LastThreeMonths'])) {
  $this_month = date("Y-m-01",strtotime( '-3 month' ));
  $Next_month = date("Y-m-t",strtotime( '-1 month' ));
  $this_month_T = date("Y-m-26",strtotime( '-4 month' ));
  $Next_month_T = date("Y-m-25",strtotime( '-1 month' ));
}elseif (isset($_GET['LastSixMonths'])) {
  $this_month = date("Y-m-01",strtotime( '-6 month' ));
  $Next_month = date("Y-m-t",strtotime( '-1 month' ));
  $this_month_T = date("Y-m-26",strtotime( '-7 month' ));
  $Next_month_T = date("Y-m-25",strtotime( '-1 month' ));
}elseif (isset($_GET['LastYear'])) {
  $this_month = date("Y-m-01",strtotime( '-12 month' ));
  $Next_month = date("Y-m-t",strtotime( '-1 month' ));
  $this_month_T = date("Y-m-26",strtotime( '-13 month' ));
  $Next_month_T = date("Y-m-25",strtotime( '-1 month' ));
}else {
  $this_month = date("Y-m-01");
  $Next_month = date("Y-m-t");
  $this_month_T = date("Y-m-26",strtotime( '-1 month' ));
  $Next_month_T = date("Y-m-26");
}


if (isset($_GET['Who']) AND in_array($_GET['Who'],array('AbdelRahman','Belal','Hamza','Ramadan','Bedo','All'))) {
  $_SESSION['Who'] = $_GET['Who'];
}
if (isset($_SESSION['Who']) AND in_array($_SESSION['Who'],array('AbdelRahman','Belal','Hamza','Ramadan','Bedo','All'))) {
  $Who = $_SESSION['Who'];
}else {
  $Who = "All";
}

if (isset($_GET['Code'])) {
  $search = $_GET['Code'] ;
  $stmt = "WHERE Code LIKE ?  AND `Type` IN ('1','5')";
  $execute = array("$search %") ;
}else {
  if ($Who =="All") {
    $stmt = "WHERE  `Date_of_Payment`>=? AND `Date_of_Payment`<=?  AND `Type` IN ('1','5')";
    $execute = array($this_month,$Next_month) ;
    $stmt2 = "WHERE history.T_code = teachers.Code AND `date`>=? AND `date`<=? ";
    $execute2 = array($this_month_T,$Next_month_T) ;
  }else {
    if ($Who == 'AbdelRahman') {
      $stmt = "WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=?  AND `Type` IN ('1','5')";
      $execute = array($Who,$this_month,$Next_month) ;

      $stmt2 = "WHERE history.T_code = teachers.Code AND  `S_code`LIKE ? AND `date`>=? AND `date`<=? OR history.T_code = teachers.Code AND  `S_code`LIKE ? AND `date`>=? AND `date`<=? ";
      $execute2 = array("A%",$this_month_T,$Next_month_T,"J%",$this_month_T,$Next_month_T) ;

    }elseif ($Who == 'Belal') {
      $stmt = "WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=?  AND `Type` IN ('1','5')";
      $execute = array($Who,$this_month,$Next_month) ;

      $stmt2 = "WHERE history.T_code = teachers.Code AND  `S_code`LIKE ? AND `date`>=? AND `date`<=? ";
      $execute2 = array("B%",$this_month_T,$Next_month_T) ;
    }elseif ($Who == 'Hamza') {
      $stmt = "WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=?  AND `Type` IN ('1','5')";
      $execute = array($Who,$this_month,$Next_month) ;

      $stmt2 = "WHERE history.T_code = teachers.Code AND  `S_code`LIKE ? AND `date`>=? AND `date`<=? ";
      $execute2 = array("H%",$this_month_T,$Next_month_T) ;
    }elseif ($Who == 'Ramadan') {
      $stmt = "WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=?  AND `Type` IN ('1','5')";
      $execute = array($Who,$this_month,$Next_month) ;

      $stmt2 = "WHERE history.T_code = teachers.Code AND  `S_code`LIKE ? AND `date`>=? AND `date`<=? ";
      $execute2 = array("R%",$this_month_T,$Next_month_T) ;
    }

  }
}

$Journal = $con->prepare("SELECT * FROM Journal $stmt ORDER BY `Timestamp` ASC ");
$Journal->execute($execute);
$Journal_count = $Journal->rowCount();
$JournalArry = $Journal->fetchAll();

$History = $con->prepare("SELECT T_code  AS `Code` , SUM(`Duration`)/60 AS `Duration` ,teachers.Code AS Hr_Code ,HourRate AS `Rate` FROM `history`, teachers    $stmt2 GROUP BY history.T_code,teachers.Code ");
$History->execute($execute2);
$History_count = $History->rowCount();
$Duration = $History->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);
$Total = 0 ;
$AllDuration = 0 ;
foreach ($Duration as $key => $value) {
  $Total += $value[0]['Duration']*$value[0]['Rate'];
  $AllDuration += $value[0]['Duration'];
}
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

$Wallet =   intval(array_sum(array_column($JournalArry, 'Amount'))) ;
$Bank =  intval($AllDuration) ;
$EasyKash =  intval($Wallet*(10/100)) ;
$PayPal =  intval($Wallet-$Total-$EasyKash );

$Total =  number_format($Total, 0, '`', ',') ;
$Wallet =  number_format($Wallet, 0, '`', ',') ;
$Bank =  number_format($Bank, 0, '`', ',') ;
$EasyKash =  number_format($EasyKash, 0, '`', ',') ;
$PayPal =  number_format($PayPal, 0, '`', ',') ;

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
      <h1>Earnings Page</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Earnings</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    
    <section class="section dashboard">
      <div class="row">


          <div class="col-lg-12">
              <div class="card">
                  <div class="card-body">
                  <h2 class="card-title text-end w-100 p-3"> الرسوم الادارية :- هى 6% تواصل و4% رسوم ثابته </h2> 
                  <h2 class="card-title text-end w-100 p-3"> رواتب المعلمين :- هو اجمالى عدد ساعات المعلمين مضروبا فى  سعر الساعه حسب سعر ساعة كل معلم </h2> 
                  <h2 class="card-title text-end w-100 p-3"> الربح :- هو اجمالى التحصيل مخصوما منه اجمالى الرواتب واجمالى الرسوم الأداريه </h2> 
                  <h2 class="card-title text-end w-100 p-3"> بداية التقرير بالنسبه للمعلمين <?php echo $this_month_T ." | ". $Next_month_T?> </h2> 
                  <h2 class="card-title text-end w-100 p-3"> بداية التقرير بالنسبه لمسؤولين التواصل <?php echo $this_month ." | ". $Next_month?> </h2> 
                  </div>
              </div>
          </div>



      <div class="col-lg-12">
        
        <div class="card">
          <div>
          <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item fw-bold <?php if($this_month == date("Y-m-01"))echo "disabled"?> " href="?ThisMonth">This Month</a></li>
                <li><a class="dropdown-item fw-bold <?php if(isset($_GET['LastThreeMonths']))echo "disabled"?> " href="?LastThreeMonths">Last Three Months</a></li>
                <li><a class="dropdown-item fw-bold <?php if(isset($_GET['LastSixMonths']))echo "disabled"?> " href="?LastSixMonths">Last Six Months</a></li>
                <li><a class="dropdown-item fw-bold <?php if(isset($_GET['LastYear']))echo "disabled"?> " href="?LastYear">Last Year</a></li>

                <li class="dropdown-header text-start">
                  <h6><hr></h6>
                </li>

                <li><a class="dropdown-item fw-bold <?php if($Who == "All")echo "disabled"?> " href="?Who=All">ALL</a></li>
                <li><a class="dropdown-item fw-bold <?php if($Who == "Hamza")echo "disabled"?> " href="?Who=Hamza">Hamza</a></li>
                <li><a class="dropdown-item fw-bold <?php if($Who == "Belal")echo "disabled"?> " href="?Who=Belal">Belal</a></li>
                <li><a class="dropdown-item fw-bold <?php if($Who == "Ramadan")echo "disabled"?> " href="?Who=Ramadan">Ramadan</a></li>
                <li><a class="dropdown-item fw-bold <?php if($Who == "AbdelRahman")echo "disabled"?> " href="?Who=AbdelRahman">AbdelRahman</a></li>
              </ul>
            </div>
          </div>
          <div class="row col-12 card-body flex-row-reverse m-auto mb-5">
            <!-- Revenue Card -->
            <div class="col-sm-2 col-6">
              <div class=" info-card sales-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center"> رواتب المعلمين	</h5>
                  <div class="card-icon w-100 rounded d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $Total ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Revenue Card -->
            <div class="col-sm-2 col-6">
              <div class=" info-card sales-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center">   اجمالى التحصيل	 </h5>
                  <div class="card-icon w-100 rounded d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $Wallet ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Revenue Card -->
            <div class="col-sm-2 col-6">
              <div class=" info-card sales-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center">   مدة الحصص </h5>
                  <div class="card-icon w-100 rounded d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $Bank ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Revenue Card -->
            <div class="col-sm-2 col-6">
              <div class=" info-card revenue-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center"> الرسوم الأداريه </h5>
                  <div class="card-icon w-100 rounded d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $EasyKash ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Revenue Card -->
            <div class="col-sm-2 col-6">
              <div class=" info-card customers-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center"> الربح </h5>
                  <div class="card-icon w-100 rounded d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $PayPal ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->

    
              
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
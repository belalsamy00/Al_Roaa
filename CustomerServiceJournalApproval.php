<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['CustomerService']) AND !isset($_SESSION['manager']) AND !isset($_SESSION['CustomerServiceManager']) ) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ; 
include "CustomerServicefunctions.php";
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


// Hamza -------------------------------------------------------
  $HamzaJournal = $con->prepare("SELECT * FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` IN ('1','6')");
  $HamzaJournal->execute(array("Hamza",$this_month,$Next_month));
  $HamzaJournal_count = $HamzaJournal->rowCount();
  $HamzaJournalArry = $HamzaJournal->fetchAll();

  $HamzaAvailable = $con->prepare("SELECT * FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ?");
  $HamzaAvailable->execute(array("Hamza",$this_month,$Next_month,1));
  $HamzaAvailable_count = $HamzaAvailable->rowCount();

  $HamzaAvailableAmount = $con->prepare("SELECT SUM(Amount) AS Available FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ?");
  $HamzaAvailableAmount->execute(array("Hamza",$this_month,$Next_month,1));
  $HamzaAvailableAmountfetch = $HamzaAvailableAmount->fetch()['Available'];
  if (empty($HamzaAvailableAmountfetch)) { $HamzaAvailableAmountfetch = 0 ;}

  $HamzaApproval = $con->prepare("SELECT * FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ?");
  $HamzaApproval->execute(array("Hamza",$this_month,$Next_month,6));
  $HamzaApproval_count = $HamzaApproval->rowCount();

  $HamzaWallet = $con->prepare("SELECT SUM(Amount) AS Wallet FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ? AND Payment_Way =? ");
  $HamzaWallet->execute(array("Hamza",$this_month,$Next_month,1,'Wallet'));
  $HamzaWalletAmount = $HamzaWallet->fetch()['Wallet'];
  if (empty($HamzaWalletAmount)) { $HamzaWalletAmount = 0 ;}

  $HamzaBank = $con->prepare("SELECT SUM(Amount) AS Bank FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ? AND Payment_Way =? ");
  $HamzaBank->execute(array("Hamza",$this_month,$Next_month,1,'Bank'));
  $HamzaBankAmount = $HamzaBank->fetch()['Bank'];
  if (empty($HamzaBankAmount)) { $HamzaBankAmount = 0 ;}

  $HamzaEasyKash = $con->prepare("SELECT SUM(Amount) AS EasyKash FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ? AND Payment_Way =? ");
  $HamzaEasyKash->execute(array("Hamza",$this_month,$Next_month,1,'EasyKash'));
  $HamzaEasyKashAmount = $HamzaEasyKash->fetch()['EasyKash'];
  if (empty($HamzaEasyKashAmount)) { $HamzaEasyKashAmount = 0 ;}

  $HamzaWestren = $con->prepare("SELECT SUM(Amount) AS Westren FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ? AND Payment_Way =? ");
  $HamzaWestren->execute(array("Hamza",$this_month,$Next_month,1,'Westren'));
  $HamzaWestrenAmount = $HamzaWestren->fetch()['Westren'];
  if (empty($HamzaWestrenAmount)) { $HamzaWestrenAmount = 0 ;}

  $HamzaPayPal = $con->prepare("SELECT SUM(Amount) AS PayPal FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ? AND Payment_Way =? ");
  $HamzaPayPal->execute(array("Hamza",$this_month,$Next_month,1,'PayPal'));
  $HamzaPayPalAmount = $HamzaPayPal->fetch()['PayPal'];
  if (empty($HamzaPayPalAmount)) { $HamzaPayPalAmount = 0 ;}

  $HamzaCash = $con->prepare("SELECT SUM(Amount) AS Cash FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ?  AND `Type` = ? AND Payment_Way =? ");
  $HamzaCash->execute(array("Hamza",$this_month,$Next_month,1,6,'Cash'));
  $HamzaCashAmount = $HamzaCash->fetch()['Cash'];
  if (empty($HamzaCashAmount)) { $HamzaCashAmount = 0 ;}

  $HamzaTotal =  array_sum(array_column($HamzaJournalArry, 'Amount')) ;

// Hamza -------------------------------------------------------

// Belal -------------------------------------------------------
  $BelalJournal = $con->prepare("SELECT * FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` IN ('1','6')");
  $BelalJournal->execute(array("Belal",$this_month,$Next_month));
  $BelalJournal_count = $BelalJournal->rowCount();
  $BelalJournalArry = $BelalJournal->fetchAll();

  $BelalAvailable = $con->prepare("SELECT * FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ?");
  $BelalAvailable->execute(array("Belal",$this_month,$Next_month,1));
  $BelalAvailable_count = $BelalAvailable->rowCount();

  $BelalAvailableAmount = $con->prepare("SELECT SUM(Amount) AS Available FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ?");
  $BelalAvailableAmount->execute(array("Belal",$this_month,$Next_month,1));
  $BelalAvailableAmountfetch = $BelalAvailableAmount->fetch()['Available'];
  if (empty($BelalAvailableAmountfetch)) { $BelalAvailableAmountfetch = 0 ;}

  $BelalApproval = $con->prepare("SELECT * FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ?");
  $BelalApproval->execute(array("Belal",$this_month,$Next_month,6));
  $BelalApproval_count = $BelalApproval->rowCount();

  $BelalWallet = $con->prepare("SELECT SUM(Amount) AS Wallet FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ? AND Payment_Way =? ");
  $BelalWallet->execute(array("Belal",$this_month,$Next_month,1,'Wallet'));
  $BelalWalletAmount = $BelalWallet->fetch()['Wallet'];
  if (empty($BelalWalletAmount)) { $BelalWalletAmount = 0 ;}

  $BelalBank = $con->prepare("SELECT SUM(Amount) AS Bank FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ? AND Payment_Way =? ");
  $BelalBank->execute(array("Belal",$this_month,$Next_month,1,'Bank'));
  $BelalBankAmount = $BelalBank->fetch()['Bank'];
  if (empty($BelalBankAmount)) { $BelalBankAmount = 0 ;}

  $BelalEasyKash = $con->prepare("SELECT SUM(Amount) AS EasyKash FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ? AND Payment_Way =? ");
  $BelalEasyKash->execute(array("Belal",$this_month,$Next_month,1,'EasyKash'));
  $BelalEasyKashAmount = $BelalEasyKash->fetch()['EasyKash'];
  if (empty($BelalEasyKashAmount)) { $BelalEasyKashAmount = 0 ;}

  $BelalWestren = $con->prepare("SELECT SUM(Amount) AS Westren FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ? AND Payment_Way =? ");
  $BelalWestren->execute(array("Belal",$this_month,$Next_month,1,'Westren'));
  $BelalWestrenAmount = $BelalWestren->fetch()['Westren'];
  if (empty($BelalWestrenAmount)) { $BelalWestrenAmount = 0 ;}

  $BelalPayPal = $con->prepare("SELECT SUM(Amount) AS PayPal FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ? AND Payment_Way =? ");
  $BelalPayPal->execute(array("Belal",$this_month,$Next_month,1,'PayPal'));
  $BelalPayPalAmount = $BelalPayPal->fetch()['PayPal'];
  if (empty($BelalPayPalAmount)) { $BelalPayPalAmount = 0 ;}

  $BelalCash = $con->prepare("SELECT SUM(Amount) AS Cash FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ?  AND `Type` = ? AND Payment_Way =? ");
  $BelalCash->execute(array("Belal",$this_month,$Next_month,1,6,'Cash'));
  $BelalCashAmount = $BelalCash->fetch()['Cash'];
  if (empty($BelalCashAmount)) { $BelalCashAmount = 0 ;}

  $BelalTotal =  array_sum(array_column($BelalJournalArry, 'Amount')) ;

// Belal -------------------------------------------------------

// Ramadan -------------------------------------------------------
  $RamadanJournal = $con->prepare("SELECT * FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` IN ('1','6')");
  $RamadanJournal->execute(array("Ramadan",$this_month,$Next_month));
  $RamadanJournal_count = $RamadanJournal->rowCount();
  $RamadanJournalArry = $RamadanJournal->fetchAll();

  $RamadanAvailable = $con->prepare("SELECT * FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ?");
  $RamadanAvailable->execute(array("Ramadan",$this_month,$Next_month,1));
  $RamadanAvailable_count = $RamadanAvailable->rowCount();

  $RamadanAvailableAmount = $con->prepare("SELECT SUM(Amount) AS Available FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ?");
  $RamadanAvailableAmount->execute(array("Ramadan",$this_month,$Next_month,1));
  $RamadanAvailableAmountfetch = $RamadanAvailableAmount->fetch()['Available'];
  if (empty($RamadanAvailableAmountfetch)) { $RamadanAvailableAmountfetch = 0 ;}

  $RamadanApproval = $con->prepare("SELECT * FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ?");
  $RamadanApproval->execute(array("Ramadan",$this_month,$Next_month,6));
  $RamadanApproval_count = $RamadanApproval->rowCount();

  $RamadanWallet = $con->prepare("SELECT SUM(Amount) AS Wallet FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ? AND Payment_Way =? ");
  $RamadanWallet->execute(array("Ramadan",$this_month,$Next_month,1,'Wallet'));
  $RamadanWalletAmount = $RamadanWallet->fetch()['Wallet'];
  if (empty($RamadanWalletAmount)) { $RamadanWalletAmount = 0 ;}

  $RamadanBank = $con->prepare("SELECT SUM(Amount) AS Bank FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ? AND Payment_Way =? ");
  $RamadanBank->execute(array("Ramadan",$this_month,$Next_month,1,'Bank'));
  $RamadanBankAmount = $RamadanBank->fetch()['Bank'];
  if (empty($RamadanBankAmount)) { $RamadanBankAmount = 0 ;}

  $RamadanEasyKash = $con->prepare("SELECT SUM(Amount) AS EasyKash FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ? AND Payment_Way =? ");
  $RamadanEasyKash->execute(array("Ramadan",$this_month,$Next_month,1,'EasyKash'));
  $RamadanEasyKashAmount = $RamadanEasyKash->fetch()['EasyKash'];
  if (empty($RamadanEasyKashAmount)) { $RamadanEasyKashAmount = 0 ;}

  $RamadanWestren = $con->prepare("SELECT SUM(Amount) AS Westren FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ? AND Payment_Way =? ");
  $RamadanWestren->execute(array("Ramadan",$this_month,$Next_month,1,'Westren'));
  $RamadanWestrenAmount = $RamadanWestren->fetch()['Westren'];
  if (empty($RamadanWestrenAmount)) { $RamadanWestrenAmount = 0 ;}

  $RamadanPayPal = $con->prepare("SELECT SUM(Amount) AS PayPal FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ? AND Payment_Way =? ");
  $RamadanPayPal->execute(array("Ramadan",$this_month,$Next_month,1,'PayPal'));
  $RamadanPayPalAmount = $RamadanPayPal->fetch()['PayPal'];
  if (empty($RamadanPayPalAmount)) { $RamadanPayPalAmount = 0 ;}

  $RamadanCash = $con->prepare("SELECT SUM(Amount) AS Cash FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ?  AND `Type` = ? AND Payment_Way =? ");
  $RamadanCash->execute(array("Ramadan",$this_month,$Next_month,1,6,'Cash'));
  $RamadanCashAmount = $RamadanCash->fetch()['Cash'];
  if (empty($RamadanCashAmount)) { $RamadanCashAmount = 0 ;}

  $RamadanTotal =  array_sum(array_column($RamadanJournalArry, 'Amount')) ;

// Ramadan -------------------------------------------------------

// AbdelRahman -------------------------------------------------------
  $AbdelRahmanJournal = $con->prepare("SELECT * FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` IN ('1','6')");
  $AbdelRahmanJournal->execute(array("AbdelRahman",$this_month,$Next_month));
  $AbdelRahmanJournal_count = $AbdelRahmanJournal->rowCount();
  $AbdelRahmanJournalArry = $AbdelRahmanJournal->fetchAll();

  $AbdelRahmanAvailable = $con->prepare("SELECT * FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ?");
  $AbdelRahmanAvailable->execute(array("AbdelRahman",$this_month,$Next_month,1));
  $AbdelRahmanAvailable_count = $AbdelRahmanAvailable->rowCount();

  $AbdelRahmanAvailableAmount = $con->prepare("SELECT SUM(Amount) AS Available FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ?");
  $AbdelRahmanAvailableAmount->execute(array("AbdelRahman",$this_month,$Next_month,1));
  $AbdelRahmanAvailableAmountfetch = $AbdelRahmanAvailableAmount->fetch()['Available'];
  if (empty($AbdelRahmanAvailableAmountfetch)) { $AbdelRahmanAvailableAmountfetch = 0 ;}

  $AbdelRahmanApproval = $con->prepare("SELECT * FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ?");
  $AbdelRahmanApproval->execute(array("AbdelRahman",$this_month,$Next_month,6));
  $AbdelRahmanApproval_count = $AbdelRahmanApproval->rowCount();

  $AbdelRahmanWallet = $con->prepare("SELECT SUM(Amount) AS Wallet FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ? AND Payment_Way =? ");
  $AbdelRahmanWallet->execute(array("AbdelRahman",$this_month,$Next_month,1,'Wallet'));
  $AbdelRahmanWalletAmount = $AbdelRahmanWallet->fetch()['Wallet'];
  if (empty($AbdelRahmanWalletAmount)) { $AbdelRahmanWalletAmount = 0 ;}

  $AbdelRahmanBank = $con->prepare("SELECT SUM(Amount) AS Bank FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ? AND Payment_Way =? ");
  $AbdelRahmanBank->execute(array("AbdelRahman",$this_month,$Next_month,1,'Bank'));
  $AbdelRahmanBankAmount = $AbdelRahmanBank->fetch()['Bank'];
  if (empty($AbdelRahmanBankAmount)) { $AbdelRahmanBankAmount = 0 ;}

  $AbdelRahmanEasyKash = $con->prepare("SELECT SUM(Amount) AS EasyKash FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ? AND Payment_Way =? ");
  $AbdelRahmanEasyKash->execute(array("AbdelRahman",$this_month,$Next_month,1,'EasyKash'));
  $AbdelRahmanEasyKashAmount = $AbdelRahmanEasyKash->fetch()['EasyKash'];
  if (empty($AbdelRahmanEasyKashAmount)) { $AbdelRahmanEasyKashAmount = 0 ;}

  $AbdelRahmanWestren = $con->prepare("SELECT SUM(Amount) AS Westren FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ? AND Payment_Way =? ");
  $AbdelRahmanWestren->execute(array("AbdelRahman",$this_month,$Next_month,1,'Westren'));
  $AbdelRahmanWestrenAmount = $AbdelRahmanWestren->fetch()['Westren'];
  if (empty($AbdelRahmanWestrenAmount)) { $AbdelRahmanWestrenAmount = 0 ;}

  $AbdelRahmanPayPal = $con->prepare("SELECT SUM(Amount) AS PayPal FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ? AND Payment_Way =? ");
  $AbdelRahmanPayPal->execute(array("AbdelRahman",$this_month,$Next_month,1,'PayPal'));
  $AbdelRahmanPayPalAmount = $AbdelRahmanPayPal->fetch()['PayPal'];
  if (empty($AbdelRahmanPayPalAmount)) { $AbdelRahmanPayPalAmount = 0 ;}

  $AbdelRahmanCash = $con->prepare("SELECT SUM(Amount) AS Cash FROM Journal WHERE `Who`= ? AND `Date_of_Payment`>=? AND `Date_of_Payment`<=? AND `Type` = ?  AND `Type` = ? AND Payment_Way =? ");
  $AbdelRahmanCash->execute(array("AbdelRahman",$this_month,$Next_month,1,6,'Cash'));
  $AbdelRahmanCashAmount = $AbdelRahmanCash->fetch()['Cash'];
  if (empty($AbdelRahmanCashAmount)) { $AbdelRahmanCashAmount = 0 ;}

  $AbdelRahmanTotal =  array_sum(array_column($AbdelRahmanJournalArry, 'Amount')) ;

// AbdelRahman -------------------------------------------------------

?>
<main id="main" class="main">
  <div class="container">
    <div class="pagetitle">
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

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body col-12 col-lg-6 m-auto">
                <h5 class="card-title text-center"> <?php echo " From :".$this_month." | "." To :".$Next_month ;?>  </h5> 
                    <form id="form-2"class="mt-2 w-100 m-auto" method="GET" action="">
                    <select name="Start" class="form-select" aria-label="Default select example">
                      <option selected> أختر شهر </option>
                      <option value="01">يناير</option>
                      <option value="02">فبراير</option>
                      <option value="03">مارس</option>
                      <option value="04">ابريل</option>
                      <option value="05">مايو</option>
                      <option value="06">يونيو</option>
                      <option value="07">يوليو</option>
                      <option value="08">اغسطس</option>
                      <option value="09">سبتمبر</option>
                      <option value="10">اكتوبر</option>
                      <option value="11">نوفمبر</option>
                      <option value="12">ديسمبر</option>
                    </select>
                      <button type="submit" class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 " >عرض</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Hamza ------------------------------------------------------- -->
          <div class="row col-sm-12 card card-body flex-row-reverse m-auto mb-5">
            <h1 class=" text-center p-3"> Hamza Total Income This Month ( <?php echo $HamzaTotal ; ?>  &#163;  )</h1>
            <!-- Revenue Card -->
            <div class="col-sm-2 col-6">
              <div class=" info-card sales-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center"> Need.To.Approve	</h5>
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $HamzaAvailableAmountfetch ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Revenue Card -->
            <div class="col-sm-2 col-6">
              <div class=" info-card sales-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center">   Wallet	 </h5>
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $HamzaWalletAmount ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Revenue Card -->
            <div class="col-sm-2 col-6">
              <div class=" info-card sales-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center">  Bank </h5>
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $HamzaBankAmount ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Revenue Card -->
            <div class="col-sm-2 col-6">
              <div class=" info-card revenue-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center">   EasyKash </h5>
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $HamzaEasyKashAmount ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Revenue Card -->
            <div class="col-sm-2 col-6">
              <div class=" info-card customers-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center"> PayPal </h5>
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $HamzaPayPalAmount ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Revenue Card -->
            <div class="col-sm-2 col-6">
              <div class=" info-card customers-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center"> Westren</h5>
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $HamzaWestrenAmount ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Revenue Card --> 
            <div class="col-lg-4 col-6">
              <div class=" info-card customers-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center">  العمليات المتاحة للتقفيل </h5>
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $HamzaAvailable_count ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->

            <!-- Revenue Card -->
            <div class="col-lg-4 col-6">
              <div class=" info-card customers-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center"> التقفيلات المسجله  هذا.الشهر </h5>
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $HamzaApproval_count ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            
            <!-- Revenue Card -->
            <div class="col-lg-4 col-12">
              <div class=" info-card customers-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center">( &#163; <?php echo $HamzaAvailableAmountfetch ; ?> ) تقفيل العمليات المتاحة بمبلغ</h5>
                  <a class="btn btn-outline-primary fs-6 fw-bold mt-2  w-50 <?php if ($HamzaAvailable_count == 0 ) {echo "disabled" ;} ?>" href="CustomerServiceJournalApi?Approval=Hamza&Cash=<?php echo $HamzaAvailableAmountfetch ?>&From=<?php echo $this_month ?>&To=<?php echo $Next_month ?>">تقفيل</a>
                </div>
              </div>
            </div><!-- End Revenue Card -->

          </div>
        <!-- Hamza ------------------------------------------------------- -->

        <!-- Belal ------------------------------------------------------- -->
          <div class="row col-sm-12 card card-body flex-row-reverse m-auto mb-5">
            <h1 class=" text-center p-3"> Belal Total Income This Month ( <?php echo $BelalTotal ; ?>  &#163;  )</h1>
            <!-- Revenue Card -->
            <div class="col-sm-2 col-6">
              <div class=" info-card sales-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center"> Need.To.Approve	</h5>
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $BelalAvailableAmountfetch ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Revenue Card -->
            <div class="col-sm-2 col-6">
              <div class=" info-card sales-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center">   Wallet	 </h5>
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $BelalWalletAmount ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Revenue Card -->
            <div class="col-sm-2 col-6">
              <div class=" info-card sales-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center">  Bank </h5>
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $BelalBankAmount ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Revenue Card -->
            <div class="col-sm-2 col-6">
              <div class=" info-card revenue-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center">   EasyKash </h5>
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $BelalEasyKashAmount ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Revenue Card -->
            <div class="col-sm-2 col-6">
              <div class=" info-card customers-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center"> PayPal </h5>
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $BelalPayPalAmount ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Revenue Card -->
            <div class="col-sm-2 col-6">
              <div class=" info-card customers-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center"> Westren</h5>
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $BelalWestrenAmount ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Revenue Card -->
            <div class="col-lg-4 col-6">
              <div class=" info-card customers-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center">  العمليات المتاحة للتقفيل </h5>
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $BelalAvailable_count ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->

            <!-- Revenue Card -->
            <div class="col-lg-4 col-6">
              <div class=" info-card customers-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center"> التقفيلات المسجله  هذا.الشهر </h5>
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $BelalApproval_count ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            
            <!-- Revenue Card -->
            <div class="col-lg-4 col-12">
              <div class=" info-card customers-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center">( &#163; <?php echo $BelalAvailableAmountfetch ; ?> ) تقفيل العمليات المتاحة بمبلغ</h5>
                  <a class="btn btn-outline-primary fs-6 fw-bold mt-2  w-50 <?php if ($BelalAvailable_count == 0 ) {echo "disabled" ;} ?>" href="CustomerServiceJournalApi?Approval=Belal&Cash=<?php echo $BelalAvailableAmountfetch ?>&From=<?php echo $this_month ?>&To=<?php echo $Next_month ?>">تقفيل</a>
                </div>
              </div>
            </div><!-- End Revenue Card -->

          </div>
        <!-- Belal ------------------------------------------------------- -->

        <!-- Ramadan ------------------------------------------------------- -->
          <div class="row col-sm-12 card card-body flex-row-reverse m-auto mb-5">
            <h1 class=" text-center p-3"> Ramadan Total Income This Month ( <?php echo $RamadanTotal ; ?>  &#163;  )</h1>
            <!-- Revenue Card -->
            <div class="col-sm-2 col-6">
              <div class=" info-card sales-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center"> Need.To.Approve	</h5>
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $RamadanAvailableAmountfetch ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Revenue Card -->
            <div class="col-sm-2 col-6">
              <div class=" info-card sales-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center">   Wallet	 </h5>
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $RamadanWalletAmount ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Revenue Card -->
            <div class="col-sm-2 col-6">
              <div class=" info-card sales-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center">  Bank </h5>
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $RamadanBankAmount ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Revenue Card -->
            <div class="col-sm-2 col-6">
              <div class=" info-card revenue-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center">   EasyKash </h5>
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $RamadanEasyKashAmount ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Revenue Card -->
            <div class="col-sm-2 col-6">
              <div class=" info-card customers-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center"> PayPal </h5>
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $RamadanPayPalAmount ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Revenue Card -->
            <div class="col-sm-2 col-6">
              <div class=" info-card customers-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center"> Westren</h5>
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $RamadanWestrenAmount ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Revenue Card -->
            <div class="col-lg-4 col-6">
              <div class=" info-card customers-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center">  العمليات المتاحة للتقفيل </h5>
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $RamadanAvailable_count ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->

            <!-- Revenue Card -->
            <div class="col-lg-4 col-6">
              <div class=" info-card customers-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center"> التقفيلات المسجله  هذا.الشهر </h5>
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $RamadanApproval_count ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            
            <!-- Revenue Card -->
            <div class="col-lg-4 col-12">
              <div class=" info-card customers-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center">( &#163; <?php echo $RamadanAvailableAmountfetch ; ?> ) تقفيل العمليات المتاحة بمبلغ</h5>
                  <a class="btn btn-outline-primary fs-6 fw-bold mt-2  w-50 <?php if ($RamadanAvailable_count == 0 ) {echo "disabled" ;} ?>" href="CustomerServiceJournalApi?Approval=Ramadan&Cash=<?php echo $RamadanAvailableAmountfetch ?>&From=<?php echo $this_month ?>&To=<?php echo $Next_month ?>">تقفيل</a>
                </div>
              </div>
            </div><!-- End Revenue Card -->

          </div>
        <!-- Ramadan ------------------------------------------------------- -->

        <!-- AbdelRahman ------------------------------------------------------- -->
          <div class="row col-sm-12 card card-body flex-row-reverse m-auto mb-5">
            <h1 class=" text-center p-3"> AbdelRahman Total Income This Month ( <?php echo $AbdelRahmanTotal ; ?>  &#163;  )</h1>
            <!-- Revenue Card -->
            <div class="col-sm-2 col-6">
              <div class=" info-card sales-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center"> Need.To.Approve	</h5>
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $AbdelRahmanAvailableAmountfetch ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Revenue Card -->
            <div class="col-sm-2 col-6">
              <div class=" info-card sales-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center">   Wallet	 </h5>
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $AbdelRahmanWalletAmount ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Revenue Card -->
            <div class="col-sm-2 col-6">
              <div class=" info-card sales-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center">  Bank </h5>
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $AbdelRahmanBankAmount ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Revenue Card -->
            <div class="col-sm-2 col-6">
              <div class=" info-card revenue-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center">   EasyKash </h5>
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $AbdelRahmanEasyKashAmount ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Revenue Card -->
            <div class="col-sm-2 col-6">
              <div class=" info-card customers-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center"> PayPal </h5>
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $AbdelRahmanPayPalAmount ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Revenue Card -->
            <div class="col-sm-2 col-6">
              <div class=" info-card customers-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center"> Westren</h5>
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $AbdelRahmanWestrenAmount ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            <!-- Revenue Card -->
            <div class="col-lg-4 col-6">
              <div class=" info-card customers-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center">  العمليات المتاحة للتقفيل </h5>
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $AbdelRahmanAvailable_count ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->

            <!-- Revenue Card -->
            <div class="col-lg-4 col-6">
              <div class=" info-card customers-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center"> التقفيلات المسجله  هذا.الشهر </h5>
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <h6 class="text-center"><?php echo $AbdelRahmanApproval_count ; ?></h6>
                  </div>
                </div>
              </div>
            </div><!-- End Revenue Card -->
            
            <!-- Revenue Card -->
            <div class="col-lg-4 col-12">
              <div class=" info-card customers-card">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center">( &#163; <?php echo $AbdelRahmanAvailableAmountfetch ; ?> ) تقفيل العمليات المتاحة بمبلغ</h5>
                  <a class="btn btn-outline-primary fs-6 fw-bold mt-2  w-50 <?php if ($AbdelRahmanAvailable_count == 0 ) {echo "disabled" ;} ?>" href="CustomerServiceJournalApi?Approval=AbdelRahman&Cash=<?php echo $AbdelRahmanAvailableAmountfetch ?>&From=<?php echo $this_month ?>&To=<?php echo $Next_month ?>">تقفيل</a>
                </div>
              </div>
            </div><!-- End Revenue Card -->

          </div>
        <!-- AbdelRahman ------------------------------------------------------- -->

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
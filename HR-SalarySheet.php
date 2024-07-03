<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['CustomerService']) AND !isset($_SESSION['manager']) ) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ; 
if (isset($_SESSION['manager'])) {
  
  if (isset($_GET['Y'])) {
    $Y = $_GET['Y'] ;
  }else {
    $_GET['Y'] = date("Y");
    $Y = $_GET['Y'] ;
  }
  
  if (isset($_GET['Start'])) {
    if ($_GET['Start'] == 1 ) {
      $LastY = $Y-1 ;
      $current_month = date("$LastY-12-26") ;
      $Next_month = date("$Y-01-25") ;
    }
    elseif ($_GET['Start'] == 2) {
      $current_month = date("$Y-01-26") ;
      $Next_month = date("$Y-02-25") ;
    }
    elseif ($_GET['Start'] == 3) {
      $current_month = date("$Y-02-26") ;
      $Next_month = date("$Y-03-25") ;
    }
    elseif ($_GET['Start'] == 4) {
      $current_month = date("$Y-03-26") ;
      $Next_month = date("$Y-04-25") ;
    }
    elseif ($_GET['Start'] == 5) {
      $current_month = date("$Y-04-26") ;
      $Next_month = date("$Y-05-25") ;
    }
    elseif ($_GET['Start'] == 6) {
      $current_month = date("$Y-05-26") ;
      $Next_month = date("$Y-06-25") ;
    }
    elseif ($_GET['Start'] == 7) {
      $current_month = date("$Y-06-26") ;
      $Next_month = date("$Y-07-25") ;
    }
    elseif ($_GET['Start'] == 8) {
      $current_month = date("$Y-07-26") ;
      $Next_month = date("$Y-08-25") ;
    }
    elseif ($_GET['Start'] == 9) {
      $current_month = date("$Y-08-26") ;
      $Next_month = date("$Y-09-25") ;
    }
    elseif ($_GET['Start'] == 10) {
      $current_month = date("$Y-09-26") ;
      $Next_month = date("$Y-10-25") ;
    }
    elseif ($_GET['Start'] == 11) {
      $current_month = date("$Y-10-26") ;
      $Next_month = date("$Y-11-25") ;
    }
    elseif ($_GET['Start'] == 12) {
      $current_month = date("$Y-11-26") ;
      $Next_month = date("$Y-12-25") ;
    }else {
      if (date("d") > 25 ) {
        $m_1 = sprintf("%02d", date("m")-1) ;
        $current_month = date("Y-$m_1-26");
        $Next_month = date("Y-m-25");
      }else {
        $m_1 = sprintf("%02d", date("m")-1) ;
        $m_2 = sprintf("%02d", date("m")-2) ;
        $current_month = date("Y-$m_2-26");
        $Next_month = date("Y-$m_1-25");
      }
    }
  
  }else {
  
    if (date("d") > 25 ) {
      $m_1 = sprintf("%02d", date("m")-1) ;
      $current_month = date("Y-$m_1-26");
      $Next_month = date("Y-m-25");
    }else {
      $m_1 = sprintf("%02d", date("m")-1) ;
      $m_2 = sprintf("%02d", date("m")-2) ;
      $current_month = date("Y-$m_2-26");
      $Next_month = date("Y-$m_1-25");
    }
  
  }
}else {
  $_GET['Y'] = date("Y");
  $Y = $_GET['Y'] ;
  if (date("d") > 25 ) {
    $m_1 = sprintf("%02d", date("m")-1) ;
    $current_month = date("Y-$m_1-26");
    $Next_month = date("Y-m-25");
  }else {
    $m_1 = sprintf("%02d", date("m")-1) ;
    $m_2 = sprintf("%02d", date("m")-2) ;
    $current_month = date("Y-$m_2-26");
    $Next_month = date("Y-$m_1-25",);
  }
}

$Next_month_Explode = explode('-',$Next_month);
$M = $Next_month_Explode[1] ;
if($M == "01" ) {$month = "يناير";} 
elseif($M == "02" ) {$month = "فبراير";}
elseif($M == "03" ) {$month = "مارس";}  
elseif($M == "04" ) {$month = "ابريل";} 
elseif($M == "05" ) {$month = "مايو";}  
elseif($M == "06" ) {$month = "يونيو";} 
elseif($M == "07" ) {$month = "يوليو";} 
elseif($M == "08" ) {$month = "اغسطس";} 
elseif($M == "09" ) {$month = "سبتمبر";}
elseif($M == "10" ) {$month = "اكتوبر";}
elseif($M == "11" ) {$month = "نوفمبر";}
elseif($M == "12" ) {$month = "ديسمبر";}
$Total_sallry_stmt = $con->prepare("SELECT * FROM history WHERE `date` >= ? AND  `date` <= ? ORDER BY `T_code` ASC ");
$Total_sallry_stmt->execute(array($current_month,$Next_month));
$Arow = $Total_sallry_stmt->fetchAll();
$SallryData = array();
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

$Add_ct_stmt = $con->prepare("SELECT * FROM teachers");
$Add_ct_stmt->execute(array());
$Teachers_Code=$Add_ct_stmt->fetchAll();
$Add_ct_count = $Add_ct_stmt->rowCount();

$TeachersSendSallary = $con->prepare("SELECT * FROM TeachersSendSallary WHERE Code !=?");
$TeachersSendSallary->execute(array('Approve'));
$fetchAllSendSallary=$TeachersSendSallary->fetchAll();
$rowCountSendSallary = $TeachersSendSallary->rowCount();

$ApproveSallary = $con->prepare("SELECT * FROM TeachersSendSallary WHERE Code =? AND `Month` =? AND SendSallary = ?");
$ApproveSallary->execute(array('Approve',date("$Y-$M-00"),"Yes"));
$Approve = $ApproveSallary->rowCount();

$TeachersJournal = $con->prepare("SELECT * FROM TeachersJournal WHERE `date` >= ? AND  `date` <= ?  ORDER BY `Timestamp` ASC ");
$TeachersJournal->execute(array($current_month,$Next_month));
$TeachersJournalArry = $TeachersJournal->fetchAll();

foreach ($SallryData as $key => $value) {
  if (!array_key_exists( $value['Code'] , $sallry))
  {
      $sallry[$value['Code']]['Code'] = $value['Code']; 
      $sallry[$value['Code']]['Duration'] = $value['Duration']; 
      $sallry[$value['Code']]['status_1'] = $value['status_1']; 
      $sallry[$value['Code']]['status_2']= $value['status_2']; 
      $sallry[$value['Code']]['Rate'] = 0; 
      $sallry[$value['Code']]['Bonus']= 0; 
      $sallry[$value['Code']]['Penalty']= 0; 
      $sallry[$value['Code']]['Advance']= 0; 
      $sallry[$value['Code']]['SendSallary']= ""; 
      $sallry[$value['Code']]['WhoSendSallary']= ""; 
      $sallry[$value['Code']]['WhoAddHours']= ""; 
      $sallry[$value['Code']]['AddHoursManually']= 0;
  }else {
    $sallry[$value['Code']]['Duration']+= $value['Duration']; 
    $sallry[$value['Code']]['status_1']+= $value['status_1']; 
    $sallry[$value['Code']]['status_2']+= $value['status_2']; 
    
  }
}
foreach ($Teachers_Code as $value) {
  if (!array_key_exists($value['Code'] , $sallry)) {
    $sallry[$value['Code']]['Code'] = $value['Code'] ; 
    $sallry[$value['Code']]['Duration'] = 0; 
    $sallry[$value['Code']]['status_1'] = 0; 
    $sallry[$value['Code']]['status_2'] = 0; 
    $sallry[$value['Code']]['Rate'] = $value['HourRate']; 
    $sallry[$value['Code']]['Bonus']= 0; 
    $sallry[$value['Code']]['Penalty']= 0; 
    $sallry[$value['Code']]['Advance']= 0; 
    $sallry[$value['Code']]['SendSallary']= ""; 
    $sallry[$value['Code']]['WhoSendSallary']= ""; 
    $sallry[$value['Code']]['WhoAddHours']= ""; 
    $sallry[$value['Code']]['AddHoursManually']= 0;
  }else {
    $sallry[$value['Code']]['Rate'] = $value['HourRate']; 
  }
}
foreach ($TeachersJournalArry as $key => $value) {
  if ($value['Type'] == 'Bonus') {
    if (!array_key_exists($value['Code'] , $sallry)) {
      $sallry[$value['Code']]['Code'] = $value['Code'] ; 
      $sallry[$value['Code']]['Duration'] = 0; 
      $sallry[$value['Code']]['status_1'] = 0; 
      $sallry[$value['Code']]['status_2'] = 0; 
      $sallry[$value['Code']]['Rate'] = $value['HourRate']; 
      $sallry[$value['Code']]['Bonus']= $value['Amount']; 
      $sallry[$value['Code']]['SendSallary']= ""; 
      $sallry[$value['Code']]['WhoSendSallary']= ""; 
      $sallry[$value['Code']]['WhoAddHours']= ""; 
      $sallry[$value['Code']]['AddHoursManually']= 0;
    }else {
      $sallry[$value['Code']]['Bonus'] += $value['Amount']; 
    }
  }else {
    if ($value['Note'] == 'سلفة') {
      if (!array_key_exists($value['Code'] , $sallry)) {
        $sallry[$value['Code']]['Code'] = $value['Code'] ; 
        $sallry[$value['Code']]['Duration'] = 0; 
        $sallry[$value['Code']]['status_1'] = 0; 
        $sallry[$value['Code']]['status_2'] = 0; 
        $sallry[$value['Code']]['Rate'] = $value['HourRate']; 
        $sallry[$value['Code']]['Advance']= $value['Amount']; 
        $sallry[$value['Code']]['SendSallary']= ""; 
        $sallry[$value['Code']]['WhoSendSallary']= ""; 
        $sallry[$value['Code']]['WhoAddHours']= ""; 
        $sallry[$value['Code']]['AddHoursManually']= 0;
      }else {
        $sallry[$value['Code']]['Advance'] += $value['Amount']; 
      }
    }else {
      if (!array_key_exists($value['Code'] , $sallry)) {
        $sallry[$value['Code']]['Code'] = $value['Code'] ; 
        $sallry[$value['Code']]['Duration'] = 0; 
        $sallry[$value['Code']]['status_1'] = 0; 
        $sallry[$value['Code']]['status_2'] = 0; 
        $sallry[$value['Code']]['Rate'] = $value['HourRate']; 
        $sallry[$value['Code']]['Penalty']= $value['Amount']; 
        $sallry[$value['Code']]['SendSallary']= ""; 
        $sallry[$value['Code']]['WhoSendSallary']= ""; 
        $sallry[$value['Code']]['WhoAddHours']= ""; 
        $sallry[$value['Code']]['AddHoursManually']= 0;
      }else {
        $sallry[$value['Code']]['Penalty'] += $value['Amount']; 
      }
    }
   
  }
}
foreach ($fetchAllSendSallary as $key => $value) {
  if ($value['Month'] == date("$Y-$M-00")) {
    if (!array_key_exists($value['Code'] , $sallry)) {
      $sallry[$value['Code']]['Code'] = $value['Code'] ; 
      $sallry[$value['Code']]['Duration'] += $value['AddHoursManually']; 
      $sallry[$value['Code']]['status_1'] = 0; 
      $sallry[$value['Code']]['status_2'] = 0; 
      $sallry[$value['Code']]['Rate'] = $value['HourRate']; 
      $sallry[$value['Code']]['Bonus']= $value['Amount']; 
      $sallry[$value['Code']]['SendSallary']= $value['SendSallary']; 
      $sallry[$value['Code']]['WhoSendSallary']= $value['WhoSendSallary']; 
      $sallry[$value['Code']]['AddHoursManually']= $value['AddHoursManually']; 
      $sallry[$value['Code']]['WhoAddHours']= $value['WhoAddHours']; 
    }else {
      $sallry[$value['Code']]['Duration'] += $value['AddHoursManually']; 
      $sallry[$value['Code']]['SendSallary'] = $value['SendSallary']; 
      $sallry[$value['Code']]['WhoSendSallary'] = $value['WhoSendSallary']; 
      $sallry[$value['Code']]['WhoAddHours'] = $value['WhoAddHours'];   
      $sallry[$value['Code']]['AddHoursManually'] += $value['AddHoursManually'];   
    }
  }
}
if (date('l', strtotime("+8 day", strtotime($Next_month))) == 'Friday') {
  $DayOFPay = date('Y-m-d', strtotime("+8 day", strtotime($Next_month)));
  $ARDayOFPay = "الجمعه" ;
}
elseif (date('l', strtotime("+8 day", strtotime($Next_month))) == 'Saturday') {
  $DayOFPay = date('Y-m-d', strtotime("+8 day", strtotime($Next_month)));
  $ARDayOFPay = "السبت" ;
}
elseif (date('l', strtotime("+8 day", strtotime($Next_month))) == 'Sunday') {
  $DayOFPay = date('Y-m-d', strtotime("+8 day", strtotime($Next_month)));
  $ARDayOFPay = "الأحد" ;
}
elseif (date('l', strtotime("+8 day", strtotime($Next_month))) == 'Monday') {
  $DayOFPay = date('Y-m-d', strtotime("+8 day", strtotime($Next_month)));
  $ARDayOFPay = "الأثنين" ;
}
elseif (date('l', strtotime("+8 day", strtotime($Next_month))) == 'Tuesday') {
  $DayOFPay = date('Y-m-d', strtotime("+8 day", strtotime($Next_month)));
  $ARDayOFPay = "الثلاثاء" ;
}
elseif (date('l', strtotime("+8 day", strtotime($Next_month))) == 'Wednesday') {
  $DayOFPay = date('Y-m-d', strtotime("+8 day", strtotime($Next_month)));
  $ARDayOFPay = "الأربعاء" ;
}else {
  $DayOFPay = date('Y-m-d', strtotime("+8 day", strtotime($Next_month)));
  $ARDayOFPay = "الخميس" ;
}


  
  $AArow =  array_sum(array_column($sallry, 'Duration'))/60;
  $Bcount = array_sum(array_column($sallry, 'status_1'));
  $Ccount = array_sum(array_column($sallry, 'status_2'));

  $AllTotals = 0 ;
  $AllMale = 0 ;
  $AllFemale = 0 ;
  $AllPenalty = 0 ;
  $AllAdvance = 0 ;
  $TotalSendSallary = 0 ;
  $HamzaSendSallary = 0 ;
  $MangodSendSallary = 0 ;
  $NotSendSallary = 0 ;

$Male = array("T40","T41","T42","T43","T44","T45","T46","T47","T48","T49","T50","T51","T52","T53","T54","T55","T56","T57","T58","T59","T60","T61","T62","T63","T64","T65","T66","T67","T68","T69");
$Female = array("T10","T11","T12","T13","T14","T15","T16","T17","T18","T19","T20","T21","T22","T23","T24","T25","T26","T27","T28","T29","T30","T31","T32","T33","T34","T35","T36","T37","T38","T39","T70","T71","T72","T73","T74","T75","T76","T77","T78","T79","T80","T81","T82","T83","T84","T85","T86","T87","T88","T89","T90","T91","T92","T93","T94","T95","T96","T97","T98","T99");
$EmptyCodes = array();
  foreach ($sallry as $key => $sallry_value) { 
    $Code = $sallry_value['Code'] ;
    $Rate = $sallry_value['Rate'] ;
    $WhoSendSallary = $sallry_value['WhoSendSallary'] ;
    $SendSallary = $sallry_value['SendSallary'] ;
    $Duration = $sallry_value['Duration']/60 ;
    $Bonus = $sallry_value['Bonus'] ;
    $Penalty  = $sallry_value['Penalty'] ;
    $Advance  = $sallry_value['Advance'] ;
    $STotal = $Duration*$sallry_value['Rate'] ;
    $Total = $STotal+$Bonus-$Penalty-$Advance ;
    $AllTotals += $Total ;
    if (in_array($Code, $Male) ) {
      $AllMale += $Total ;
      $AllPenalty += $Penalty ;
      $AllAdvance += $Advance ;
    }else {
      $AllFemale += $Total ;
      $AllPenalty += $Penalty ;
      $AllAdvance += $Advance ;
    }
    if ($SendSallary == "Yes") {
      $TotalSendSallary += $Total ;
      if ($WhoSendSallary == "Hamza") {
        $HamzaSendSallary += $Total ;
      }else {
        $MangodSendSallary += $Total ;
      }
    }else {
      $NotSendSallary += $Total ;
    }

     }
foreach ($Male as $value) {
  if(!array_key_exists($value , $sallry)){
    $EmptyCodes[$value] = $value ;
  }
}
foreach ($Female as $value) {
  if(!array_key_exists($value , $sallry)){
    $EmptyCodes[$value] = $value ;
  }
}
  ?>
<main id="main" class="main">
  <div class="container">

    <div class="pagetitle">
    <nav>
        <ol class="breadcrumb">
          <li class="">        
            <button class="btn btn-success fs-6 fw-bold text-white m-2" onclick="window.location= 'HR-SalaryBonusAndPenaltyForm' ">
            <i class="bi bi-journal-plus"></i>
            <span> إضافة بونص أو خصم </span>
            </button.><!-- End Dashboard Iamge Icon -->
          </li>
          <li class="">        
            <button class="btn btn-primary fs-6 fw-bold text-white m-2" onclick="window.location= 'HR-SalaryBonusAndPenaltySheet' ">
            <i class="bi bi-journal-plus"></i>
            <span> عرض شيت البونص و الخصم </span>
            </button.><!-- End Dashboard Iamge Icon -->
          </li>
        </ol>
      </nav>
      <h1>Teachers Sallry Sheet</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Teachers Sallry</li>
        </ol>
      </nav>
    </div>

    <section class="section">

      <div class="row">
        <?php if (isset($_SESSION['manager'])) { ?>
          <div class="col-lg-12">
              <div class="card">
                  <div class="card-body col-12 col-lg-6 m-auto">
                    <h5 class="card-title text-center"> <?php echo " مرتب شهر  :".$M." من :".$current_month." | "." الى :".$Next_month ;?>  </h5> 
                        <form id="form-2"class="mt-2 w-100 m-auto" method="GET" action="">
                        <select  onchange="this.form.submit()" name="Start" class="form-select" aria-label="Default select example">
                          <option <?php if(isset($M) And $M == "01" ) {echo "selected";}  ?> value="01">يناير</option>
                          <option <?php if(isset($M) And $M == "02" ) {echo "selected";} ?> value="02">فبراير</option>
                          <option <?php if(isset($M) And $M == "03" ) {echo "selected";}   ?> value="03">مارس</option>
                          <option <?php if(isset($M) And $M == "04" ) {echo "selected";}  ?> value="04">ابريل</option>
                          <option <?php if(isset($M) And $M == "05" ) {echo "selected";}   ?> value="05">مايو</option>
                          <option <?php if(isset($M) And $M == "06" ) {echo "selected";}  ?> value="06">يونيو</option>
                          <option <?php if(isset($M) And $M == "07" ) {echo "selected";}  ?> value="07">يوليو</option>
                          <option <?php if(isset($M) And $M == "08" ) {echo "selected";}  ?> value="08">اغسطس</option>
                          <option <?php if(isset($M) And $M == "09" ) {echo "selected";} ?> value="09">سبتمبر</option>
                          <option <?php if(isset($M) And $M == "10" ) {echo "selected";} ?> value="10">اكتوبر</option>
                          <option <?php if(isset($M) And $M == "11" ) {echo "selected";} ?> value="11">نوفمبر</option>
                          <option <?php if(isset($M) And $M == "12" ) {echo "selected";} ?> value="12">ديسمبر</option>
                        </select>
                        
                        <select  onchange="this.form.submit()" name="Y" class="form-select mt-2" aria-label="Default select example">
                          <?php for ($i=2020; $i < date('Y', strtotime('+1 year')) ; $i++) { ?>
                          <option <?php if(isset($_GET['Y']) And $_GET['Y'] == $i ) {echo "selected";}?> value="<?php echo $i; ?>"> <?php echo $i; ?> </option>
                          <?php } ?>
                        </select>
                        </form>

                  </div>
              </div>
          </div>
          <?php }else { ?>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body col-12 col-lg-6 m-auto">
                      <h5 class="card-title text-center"> <?php echo " مرتب شهر  :".$M." من :".$current_month." | "." الى :".$Next_month ;?>  </h5>   
                    </div>
                </div>
            </div>
        <?php } ?>
      </div>
      <?php if (isset($_SESSION['manager'])) { ?>
      <div class="row">
        <div class="col-12 m-auto">
          <div class=" col-12">
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
        </div>
      </div>

      <div class="row">
        <div class="col-12 m-auto">
          <div class=" col-12">
            <div class="card">
              <div class="card-body rtl overflow-auto">
                <h5 class="card-title text-center">   </h5> 
                <table class="table  table-striped  table-bordered text-center table_class" >
                  <thead >
                  <tr >
                  <th  class="fw-bold fs-5 text-center" scope="col"> الرواتب </th>
                  <th  class="fw-bold fs-5 text-center" scope="col"> المعلمين </th>
                  <th  class="fw-bold fs-5 text-center" scope="col"> المعلمات </th>
                  <th  class="fw-bold fs-5 text-center" scope="col"> الخصومات </th>
                  <th  class="fw-bold fs-5 text-center" scope="col"> السلف </th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr style=" font-size: 34px; font-weight: 800;" >
                  <td  class="fw-bold fs-5 text-center" ><?php echo $AllTotals ;?></td>
                  <td  class="fw-bold fs-5 text-center" ><?php echo $AllMale ; ?></td>
                  <td  class="fw-bold fs-5 text-center" ><?php echo $AllFemale ; ?></td>
                  <td  class="fw-bold fs-5 text-center" ><?php echo $AllPenalty ; ?></td>
                  <td  class="fw-bold fs-5 text-center" ><?php echo $AllAdvance ; ?></td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12 m-auto">
          <div class=" col-12">
            <div class="card">
              <div class="card-body rtl overflow-auto">
                <h5 class="card-title text-center">   </h5> 
                <table class="table  table-striped  table-bordered text-center table_class" >
                  <thead >
                  <tr >
                  <th  class="fw-bold fs-5 text-center" scope="col"> تم الأرسال </th>
                  <th  class="fw-bold fs-5 text-center" scope="col"> حمزة </th>
                  <th  class="fw-bold fs-5 text-center" scope="col"> منجود </th>
                  <th  class="fw-bold fs-5 text-center" scope="col"> لم يتم الأرسال </th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr style=" font-size: 34px; font-weight: 800;" >
                  <td  class="fw-bold fs-5 text-center" ><?php echo $TotalSendSallary ;?></td>
                  <td  class="fw-bold fs-5 text-center" ><?php echo $HamzaSendSallary ; ?></td>
                  <td  class="fw-bold fs-5 text-center" ><?php echo $MangodSendSallary ; ?></td>
                  <td  class="fw-bold fs-5 text-center" ><?php echo $NotSendSallary ; ?></td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php } ?>
      
      <div class="row">

        <div class="col-12 m-auto ">
          <div class="card">
            <div class="card-body overflow-auto text-center">
              <h5 class="card-title text-center"> جميع الأكواد </h5>
              <?php if (isset($_SESSION['manager'])) { ?> 
                  <?php if ($Approve > 0 ) { ?>
                  <form id="CancelApprove" action="CustomerServiceJournalApi" method="get">
                  <input type="hidden" value="Approve" name="UNSendSallary">
                  <input type="hidden" value="<?php echo date("$Y-$M-00") ?>" name="Month">
                  <button class=" btn btn-light fs-3 text-danger  mb-2" onclick=" if (confirm('هل انت متأكد من انك تريد الغاء التقفيل ؟ ') == true) {document.getElementById('CancelApprove').submit()} else { return false; }" > <i class="bi bi-x-circle-fill"></i> إلغاء تقفيل الشهر </button> 
                  </form>
                  <?php }else { ?>
                  <form id="sendApprove" action="CustomerServiceJournalApi" method="get">
                  <input type="hidden" value="Approve" name="SendSallary">
                  <input type="hidden" value="<?php echo $_SESSION['Name'] ?>" name="WhoSendSallary">
                  <input type="hidden" value="<?php echo date("$Y-$M-00") ?>" name="Month">
                  <button class=" btn btn-light fs-3 text-success mb-2" onclick=" if (confirm('هل انت متأكد من انك تريد التقفيل ؟ ') == true) {document.getElementById('sendApprove').submit()} else { return false; }" > <i class="bi bi-check-circle-fill"></i> تقفيل الشهر </button>
                  </form>
                  <style>
                  #SalaryTable_wrapper .dt-buttons{
                    display: none;
                  }
                </style>
                  <?php } ?>
                  <?php }?>
              <table id="SalaryTable"   class="table table-border table-hover text-center  DataTable stripe row-border order-column nowrap " >
                <thead >
                  <tr >
                  <th class= text-center> Teacher </th> 
                  <th class= text-center> Hours </th>
                  <th class= text-center> Hourly Rate </th>
                  <th class= text-center> Additions </th>
                  <th class= text-center> Penalty </th>
                  <th class= text-center> Total </th>
                  <th class= text-center> Advance </th>
                  <th class= text-center> Net Salary </th>
                  <th class= text-center></th> 
                  <th class= text-center></th>
                  <?php if (isset($_SESSION['manager'])) { ?>
                  <th class= text-center></th>
                  <?php } ?>
                  </tr>
                </thead>

                <tbody class="text-start ">
                  <?php foreach ($sallry as $key => $sallry_value) {  
                    $Code = $sallry_value['Code'] ;
                    $Rate = $sallry_value['Rate'] ;
                    $Bonus = $sallry_value['Bonus'] ;
                    $Penalty  = $sallry_value['Penalty'] ;
                    $Advance  = $sallry_value['Advance'] ;
                    $Duration = $sallry_value['Duration']/60 ;
                    $SubTotal = $Duration*$Rate ;
                    $Total    = ceil($SubTotal+$Bonus-$Penalty)  ;
                    $NetSalary = $Total-$Advance ;
                    if ($Total == 0) {
                     continue ;
                    }
                  ?>
                  <?php if (!isset($_SESSION['manager'])) { ?>
                  <?php if ($Code =="T10" OR $Code =="T22" OR $Code =="T23" OR $Code =="T40" OR $Code =="T41" OR $Code =="T53" ) { continue ;} ?>
                  <?php } ?>
                  <?php if ($sallry_value['AddHoursManually'] > 0) { ?>
                  <p class=" mb-0">
                  <form id="cancelAddHoursManually<?php echo $Code ?>" action="CustomerServiceJournalApi" method="get">
                  <input type="hidden" value="<?php echo $Code ?>" name="UNAddHoursManually">
                  <input type="hidden" value="<?php echo date("$Y-$M-00") ?>" name="Month">
                  <p class="m-0 rtl">
                  تم اضافة <?php echo $sallry_value['AddHoursManually'] ?> دقيقة بواسطة <?php echo $sallry_value['WhoAddHours'] ?> الى المعلم <?php echo $Code ?> - 
                  <?php if (isset($_SESSION['manager'])) { ?> <i onclick=" if (confirm('هل انت متأكد من حذف المدة المضافة الى <?php echo $Code ?> ؟ ') == true) {document.getElementById('cancelAddHoursManually<?php echo $Code ?>').submit()} else { return false; }" 
                  role="button" class="bi bi-x-circle-fill fs-3 text-danger"></i>
                   <?php }?>
                  </p>
                  </form>
                  </p>
                  <?php } ?>
                  <tr class="<?php  if ($sallry_value['SendSallary'] == "Yes") { echo "table-success" ;}else{ echo "" ;} ; ?>" >
                  <td class="fs-5">
                  <?php if (isset($_SESSION['manager'])) { ?>
                    <a class="text-dark" href="TotalSallry?Code=<?php echo $Code ?>&Start=<?php echo $current_month ?>&End=<?php echo $Next_month ?>"> <?php echo $Code ?> </a>
                  <?php }else { ?>
                    <?php echo $Code ; ?>
                  <?php } ?>
                  </td>
                  <?php if (isset($_SESSION['manager'])) { ?>
                  <td class="fs-5">
                  <a class="card-details mb-0" data-bs-toggle="modal" href="#AddHoursManually<?php echo $Code ?>" role="button" aria-expanded="false" aria-controls="AddHoursManually<?php echo $Code ?>">
                    <i class="bi bi-pencil-square"></i> <?php echo $Duration ?>
                  </a>
                  </td>
                  <?php }else { ?>
                    <td class="fs-5">
                    <?php echo $Duration ?>
                  </td>
                  <?php } ?>
                  <?php if (isset($_SESSION['manager'])) { ?>
                  <td class="fs-5">
                  <a class="card-details mb-0" data-bs-toggle="modal" href="#EditHourRate<?php echo $Code ?>" role="button" aria-expanded="false" aria-controls="EditHourRate<?php echo $Code ?>">
                    <i class="bi bi-pencil-square"></i> <?php echo $Rate ?>
                  </a>
                  </td>
                  <?php }else { ?>
                  <td class="fs-5">
                    <?php echo $Rate ?>
                  </td>
                  <?php } ?>
                  <td class="fs-5 card-details"><?php echo $Bonus ; ?></td>
                  <td class="fs-5 card-details"><?php echo $Penalty ; ?></td>
                  <td class="fs-5 card-details"><?php echo $Total ; ?></td>
                  <td class="fs-5 card-details"><?php echo $Advance ; ?></td>
                  <td class="fs-5 card-details"><?php echo $NetSalary ; ?></td>
                  <td>
                  <?php if ($sallry_value['SendSallary'] == "Yes") { ?>
                  <form id="Cancel<?php echo $Code ?>" action="CustomerServiceJournalApi" method="get">
                  <input type="hidden" value="<?php echo $Code ?>" name="UNSendSallary">
                  <input type="hidden" value="<?php echo date("$Y-$M-00") ?>" name="Month">
                  <?php if (isset($_SESSION['manager'])) { ?> 
                    <i onclick=" if (confirm('هل انت متأكد من انك لم ترسل الراتب الى <?php echo $Code ?> ؟ ') == true) {document.getElementById('Cancel<?php echo $Code ?>').submit()} else { return false; }" 
                    role="button" class="bi bi-x-circle-fill fs-3 text-danger"></i>  <?php }?>
                  </form>
                  <?php }else { ?>
                  <form id="send<?php echo $Code ?>" action="CustomerServiceJournalApi" method="get">
                  <input type="hidden" value="<?php echo $Code ?>" name="SendSallary">
                  <input type="hidden" value="<?php echo $_SESSION['Name'] ?>" name="WhoSendSallary">
                  <input type="hidden" value="<?php echo date("$Y-$M-00") ?>" name="Month">
                  <i onclick=" if (confirm('هل انت متأكد من انك ارسلت الراتب الى <?php echo $Code ?> ؟ ') == true) {document.getElementById('send<?php echo $Code ?>').submit()} else { return false; }" 
                  role="button" class="bi bi-send-fill fs-3 text-success"></i>
                  </form>
                  <?php } ?>
                  </td>
                  <td> <i onclick="CopyFunction('<?php echo $Code ?>','<?php echo $Duration ?>','<?php echo $Rate ?>','<?php echo $Bonus ?>','<?php echo $Penalty ?>','<?php echo $Advance ?>','<?php echo $Total ?>')" role="button" class="bi bi-clipboard-fill fs-3 text-dark"></i></td>
                  <?php if (isset($_SESSION['manager'])) { ?>
                  <td>
                  <?php if ($Code =="T10" OR $Code =="T22" OR $Code =="T23" 
                      OR $Code =="T40" OR $Code =="T41" OR $Code =="T53" ) { ?>
                      <i class="bi bi-1-square-fill fs-3 text-dark"></i>
                  <?php } ?>
                   </td>
                   <?php } ?>
                  </tr>

                <div class="modal fade" id="EditHourRate<?php echo $Code ?>" tabindex="-1" aria-labelledby="EditHourRate<?php echo $Code ?>Label" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h1 class="modal-title fs-5" id="EditHourRate<?php echo $Code ?>Label">Modal title</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <form action="CustomerServiceJournalApi" method="get">
                        <input type="hidden" class="form-control" name="EditHourRate" value="<?php echo $Code ?>" required>
                        <input type="number" class="form-control" name="Rate" value="<?php echo $Rate ?>" required>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary"> تعديل </button>
                          </div>
                        </form>
                      </div>

                    </div>
                  </div>
                </div>

                <div class="modal fade" id="AddHoursManually<?php echo $Code ?>" tabindex="-1" aria-labelledby="AddHoursManually<?php echo $Code ?>Label" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h1 class="modal-title fs-5" id="AddHoursManually<?php echo $Code ?>Label">Modal title</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <form action="CustomerServiceJournalApi" method="get">
                          <input type="hidden" value="<?php echo $Code ?>" name="AddHoursManually">
                          <input type="hidden" value="<?php echo $_SESSION['Name'] ?>" name="WhoAddHours">
                          <input type="hidden" value="<?php echo date("$Y-$M-00") ?>" name="Month">
                          <input type="number" class="form-control" name="Hours"  required>
                          <p> ادخل الرقم بالدقائق !!!</p>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary"> إضافة </button>
                          </div>
                        </form>
                      </div>

                    </div>
                  </div>
                </div>

                  <?php } ?>
                </tbody>
              </table>

            </div>
          </div>
        </div>
        <?php if (isset($_SESSION['manager'])) { ?>
      <div class="alert alert-secondary  text-center mt-3" role="alert">
        <h5 class="card-title text-center"> اكواد فارغة  </h5>
        <p>
          <?php foreach ($EmptyCodes as $key => $value) { ?>
          <?php echo $value." - "; ?>
          <?php } ?>
        </p>
      </div>
      <?php } ?>



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



      <div class="toast-container position-fixed bottom-0 end-0 p-3" style=" z-index: 999999;">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false">
          <div class="toast-header">
            <strong class="me-auto">أكاديمية الرؤى</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
          <div class="toast-body rtl fs-6 fw-bold text-danger">
            <?php if (!empty($_SESSION['Emessage'])) {echo $_SESSION['Emessage'];}else { echo " لقد تـــم النسخ " ;}?>
          </div>
        </div>
      </div>

    </section>

  </div>
</main><!-- End #main -->
<script>
  function CopyFunction(Code,Duration,Rate,Bonus,Penalty,Advance,Total) {
    var month = "<?php echo $month ?>";
    var copyText = `
    ${Code}
    السلام عليكم ورحمة الله وبركاته
    كيف الحال؟

    مرتب شهر ${month}

    عدد الساعـات    🔸 ${Duration}

    سعر الســاعة    🔸 ${Rate}

    إضافـــــــات   🔸 ${Bonus}

    خصومـــــــات   🔸 ${Penalty}

    سلـــــفـــة    🔸 ${Advance}

    الــمـــرتــب   👈 ${Total}

    إجمالى المتبقى  👈 ${Total-Advance}


    الموعد المتوقع لإرسال الراتب

    <?php echo $DayOFPay ?> / <?php echo $ARDayOFPay ?>

    برجاء إرسال رقم المحفظة الذي سوف نقوم بالإرسال عليه
    برجاء مراجعة الرقم بدقة


    بارك الله لكم ونفع بكم
    🌹جزاكم الله خيرًا 
    `
  navigator.clipboard.writeText(copyText);

  $(document).ready(function() {
        $(".toast").toast('show');
    });
    }
</script>
<?php include "assets/tem/footer.php" ?>
<?php if (!empty($_SESSION['Emessage'])) { ?>
  <script>
    $(document).ready(function() {
        $(".toast").toast('show');
    });
</script>
<?php unset($_SESSION['Emessage']); } ?>

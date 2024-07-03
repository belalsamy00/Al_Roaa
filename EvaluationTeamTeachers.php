<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['manager']) AND !isset($_SESSION['Supervising_manager']) AND !isset($_SESSION['supervisor'])) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ;
if (isset($_GET['Start'])) {
  if ($_GET['Start'] == 1 ) {
    $this_month = date("Y-12-26",strtotime("-1 year")) ;
    $Next_month = date("Y-01-25") ;
  }
  elseif ($_GET['Start'] == 2) {
    $this_month = date("Y-01-26") ;
    $Next_month = date("Y-02-25") ;
  }
  elseif ($_GET['Start'] == 3) {
    $this_month = date("Y-02-26") ;
    $Next_month = date("Y-03-25") ;
  }
  elseif ($_GET['Start'] == 4) {
    $this_month = date("Y-03-26") ;
    $Next_month = date("Y-04-25") ;
  }
  elseif ($_GET['Start'] == 5) {
    $this_month = date("Y-04-26") ;
    $Next_month = date("Y-05-25") ;
  }
  elseif ($_GET['Start'] == 6) {
    $this_month = date("Y-05-26") ;
    $Next_month = date("Y-06-25") ;
  }
  elseif ($_GET['Start'] == 7) {
    $this_month = date("Y-06-26") ;
    $Next_month = date("Y-07-25") ;
  }
  elseif ($_GET['Start'] == 8) {
    $this_month = date("Y-07-26") ;
    $Next_month = date("Y-08-25") ;
  }
  elseif ($_GET['Start'] == 9) {
    $this_month = date("Y-08-26") ;
    $Next_month = date("Y-09-25") ;
  }
  elseif ($_GET['Start'] == 10) {
    $this_month = date("Y-09-26") ;
    $Next_month = date("Y-10-25") ;
  }
  elseif ($_GET['Start'] == 11) {
    $this_month = date("Y-10-26") ;
    $Next_month = date("Y-11-25") ;
  }
  elseif ($_GET['Start'] == 12) {
    $this_month = date("Y-11-26") ;
    $Next_month = date("Y-12-25") ;
  }else {
    if (date("d") > 25 ) {
      $this_month = date("Y-m-26");
    }else {
      $this_month = date("Y-m-26",strtotime("-1 month"));
    }
    if (date("d") > 25 ) {
      $Next_month = date("Y-m-25",strtotime("+1 month"));
    }else {
      $Next_month = date("Y-m-25");
    }
  }

}else {
  if (date("d") > 25 ) {
    $this_month = date("Y-m-26");
  }else {
    $this_month = date("Y-m-26",strtotime("-1 month"));
  }
  if (date("d") > 25 ) {
    $Next_month = date("Y-m-25",strtotime("+1 month"));
  }else {
    $Next_month = date("Y-m-25");
  }
}
$AllTeacherSaturdaycount = $con->prepare("SELECT COUNT(Teacher) As COUNT FROM `class` WHERE `status` = ? AND `type` =? AND Saturday =? AND Teacher  IN ( 'T41','T42','T43','T44','T45','T46','T47','T48','T49','T50',
'T51','T52','T54','T55','T56','T57','T58','T59','T60','T61','T62','T63','T64','T65','T66','T67') ");
$AllTeacherSaturdaycount->execute(array('Active',2,'Saturday'));
$AllTeacherSaturday = $AllTeacherSaturdaycount->fetch();

$AllTeacherSundaycount = $con->prepare("SELECT COUNT(Teacher) As COUNT FROM `class` WHERE `status` = ? AND `type` =? AND Sunday =? AND Teacher  IN ( 'T41','T42','T43','T44','T45','T46','T47','T48','T49','T50',
'T51','T52','T54','T55','T56','T57','T58','T59','T60','T61','T62','T63','T64','T65','T66','T67') ");
$AllTeacherSundaycount->execute(array('Active',2,'Sunday'));
$AllTeacherSunday = $AllTeacherSundaycount->fetch();

$AllTeacherMondaycount = $con->prepare("SELECT COUNT(Teacher) As COUNT FROM `class` WHERE `status` = ? AND `type` =? AND Monday =? AND Teacher  IN ( 'T41','T42','T43','T44','T45','T46','T47','T48','T49','T50',
'T51','T52','T54','T55','T56','T57','T58','T59','T60','T61','T62','T63','T64','T65','T66','T67') ");
$AllTeacherMondaycount->execute(array('Active',2,'Monday'));
$AllTeacherMonday = $AllTeacherMondaycount->fetch();

$AllTeacherTuesdaycount = $con->prepare("SELECT COUNT(Teacher) As COUNT FROM `class` WHERE `status` = ? AND `type` =? AND Tuesday =? AND Teacher  IN ( 'T41','T42','T43','T44','T45','T46','T47','T48','T49','T50',
'T51','T52','T54','T55','T56','T57','T58','T59','T60','T61','T62','T63','T64','T65','T66','T67') ");
$AllTeacherTuesdaycount->execute(array('Active',2,'Tuesday'));
$AllTeacherTuesday = $AllTeacherTuesdaycount->fetch();

$AllTeacherWednesdaycount = $con->prepare("SELECT COUNT(Teacher) As COUNT FROM `class` WHERE `status` = ? AND `type` =? AND Wednesday =? AND Teacher  IN ( 'T41','T42','T43','T44','T45','T46','T47','T48','T49','T50',
'T51','T52','T54','T55','T56','T57','T58','T59','T60','T61','T62','T63','T64','T65','T66','T67') ");
$AllTeacherWednesdaycount->execute(array('Active',2,'Wednesday'));
$AllTeacherWednesday = $AllTeacherWednesdaycount->fetch();

$AllTeacherThursdaycount = $con->prepare("SELECT COUNT(Teacher) As COUNT FROM `class` WHERE `status` = ? AND `type` =? AND Thursday =? AND Teacher  IN ( 'T41','T42','T43','T44','T45','T46','T47','T48','T49','T50',
'T51','T52','T54','T55','T56','T57','T58','T59','T60','T61','T62','T63','T64','T65','T66','T67') ");
$AllTeacherThursdaycount->execute(array('Active',2,'Thursday'));
$AllTeacherThursday = $AllTeacherThursdaycount->fetch();

$AllTeacherFridaycount = $con->prepare("SELECT COUNT(Teacher) As COUNT FROM `class` WHERE `status` = ? AND `type` =? AND Friday =? AND Teacher  IN ( 'T41','T42','T43','T44','T45','T46','T47','T48','T49','T50',
'T51','T52','T54','T55','T56','T57','T58','T59','T60','T61','T62','T63','T64','T65','T66','T67') ");
$AllTeacherFridaycount->execute(array('Active',2,'Friday'));
$AllTeacherFriday = $AllTeacherFridaycount->fetch();

$AllTeacherClasscount = ($AllTeacherSaturday['COUNT']+$AllTeacherSunday['COUNT']+$AllTeacherMonday['COUNT']+$AllTeacherTuesday['COUNT']+$AllTeacherWednesday['COUNT']+$AllTeacherThursday['COUNT']+$AllTeacherFriday['COUNT'])*4;
$prsnt = intval(round((336/$AllTeacherClasscount)*100)) ;
$evaluation =array();

$TeacherDailyEvaluationCount = $con->prepare("SELECT * FROM evaluation WHERE `Date`=? AND classID != ? AND `type` = ?  ORDER BY `Date`  ASC");
$TeacherDailyEvaluationCount->execute(array(date("Y-m-d"),0,1));
$DailyEvaluationCount = $TeacherDailyEvaluationCount->rowCount();

  $users_stmt = $con->prepare("SELECT * FROM teachers WHERE  Code  IN ( 'T41','T42','T43','T44','T45','T46','T47','T48','T49','T50',
  'T51','T52','T54','T55','T56','T57','T58','T59','T60','T61','T62','T63','T64','T65','T66','T67') ORDER BY `Code` ASC  ");
  $users_stmt->execute();
  $users=$users_stmt->fetchAll();

  foreach ($users as $K => $user) {

  $plus_user_stmt = $con->prepare("SELECT  SUM(Mark) FROM evaluation WHERE Code=? AND `Status`=? AND `Date`>=? AND `Date`<=? AND `Approval` =? ");
  $plus_user_stmt->execute(array($user['Code'],"+",$this_month,$Next_month,0));
  $plus_user = $plus_user_stmt->fetch();

  $minus_user_stmt = $con->prepare("SELECT  SUM(Mark) FROM evaluation WHERE Code=? AND `Status`=? AND `Date`>=? AND`Date`<=? AND `Approval` =? ");
  $minus_user_stmt->execute(array($user['Code'],"-",$this_month,$Next_month,0));
  $minus_user = $minus_user_stmt->fetch();

  $evaluation_user_stmt = $con->prepare("SELECT * FROM evaluation WHERE Code=? AND `Date`>=? AND`Date`<=? AND `Approval` =? ");
  $evaluation_user_stmt->execute(array($user['Code'],$this_month,$Next_month,0));
  $evaluation_user_count = $evaluation_user_stmt->rowCount();

  $count = $con->prepare("SELECT * FROM evaluation WHERE Code=? AND `type`=? AND  `Date`>=? AND`Date`<=? AND `Approval` =? ");
  $count->execute(array($user['Code'],1,$this_month,$Next_month,0));
  $evaluation_count = $count->rowCount();

  $Saturdaycount = $con->prepare("SELECT COUNT(Teacher) As COUNT FROM `class` WHERE Teacher = ? AND `status` = ? AND `type` =? AND Saturday =?  ");
  $Saturdaycount->execute(array($user['Code'],'Active',2,'Saturday'));
  $Saturday = $Saturdaycount->fetch();

  $Sundaycount = $con->prepare("SELECT COUNT(Teacher) As COUNT FROM `class` WHERE Teacher = ? AND `status` = ? AND `type` =? AND Sunday =?  ");
  $Sundaycount->execute(array($user['Code'],'Active',2,'Sunday'));
  $Sunday = $Sundaycount->fetch();

  $Mondaycount = $con->prepare("SELECT COUNT(Teacher) As COUNT FROM `class` WHERE Teacher = ? AND `status` = ? AND `type` =? AND Monday =?  ");
  $Mondaycount->execute(array($user['Code'],'Active',2,'Monday'));
  $Monday = $Mondaycount->fetch();

  $Tuesdaycount = $con->prepare("SELECT COUNT(Teacher) As COUNT FROM `class` WHERE Teacher = ? AND `status` = ? AND `type` =? AND Tuesday =?  ");
  $Tuesdaycount->execute(array($user['Code'],'Active',2,'Tuesday'));
  $Tuesday = $Tuesdaycount->fetch();

  $Wednesdaycount = $con->prepare("SELECT COUNT(Teacher) As COUNT FROM `class` WHERE Teacher = ? AND `status` = ? AND `type` =? AND Wednesday =?  ");
  $Wednesdaycount->execute(array($user['Code'],'Active',2,'Wednesday'));
  $Wednesday = $Wednesdaycount->fetch();

  $Thursdaycount = $con->prepare("SELECT COUNT(Teacher) As COUNT FROM `class` WHERE Teacher = ? AND `status` = ? AND `type` =? AND Thursday =?  ");
  $Thursdaycount->execute(array($user['Code'],'Active',2,'Thursday'));
  $Thursday = $Thursdaycount->fetch();

  $Fridaycount = $con->prepare("SELECT COUNT(Teacher) As COUNT FROM `class` WHERE Teacher = ? AND `status` = ? AND `type` =? AND Friday =?  ");
  $Fridaycount->execute(array($user['Code'],'Active',2,'Friday'));
  $Friday = $Fridaycount->fetch();

  $TeacherClasscount = ($Saturday['COUNT']+$Sunday['COUNT']+$Monday['COUNT']+$Tuesday['COUNT']+$Wednesday['COUNT']+$Thursday['COUNT']+$Friday['COUNT'])*4;
  $Teacherprsnt = intval(round(($TeacherClasscount/$AllTeacherClasscount)*100)) ;
  $TeacherTarget = ceil(336*($Teacherprsnt/100))  ;
  
  $Amr_count = $con->prepare("SELECT * FROM evaluation WHERE Code=? AND `type`=? AND Who =? AND  `Date`>=? AND`Date`<=? AND Cancel = 0");
  $Amr_count->execute(array($user['Code'],1,"عمرو عبدالله",$this_month,$Next_month));
  $evaluation_Amr_count = $Amr_count->rowCount();

  $Bedo_count = $con->prepare("SELECT * FROM evaluation WHERE Code=? AND `type`=? AND Who =? AND  `Date`>=? AND`Date`<=? AND Cancel = 0");
  $Bedo_count->execute(array($user['Code'],1,"عبدالرحمن",$this_month,$Next_month));
  $evaluation_Bedo_count = $Bedo_count->rowCount();

  $Ahmed_count = $con->prepare("SELECT * FROM evaluation WHERE Code=? AND `type`=? AND Who =? AND  `Date`>=? AND`Date`<=? AND Cancel = 0");
  $Ahmed_count->execute(array($user['Code'],1,"أحمد منجود",$this_month,$Next_month));
  $evaluation_Ahmed_count = $Ahmed_count->rowCount();

  $evaluation_Targets = $con->prepare("SELECT `Target` FROM Targets WHERE Code=?");
  $evaluation_Targets->execute(array($user['Code']));
  $evaluation_Targets_fetch = $evaluation_Targets->fetch();
  $evaluation_Targets_count = $evaluation_Targets_fetch['Target'];

  $evaluation[$K]['Code'] = $user['Code']; 
  $evaluation[$K]['trust'] = 1; 
  $evaluation[$K]['Mark'] = 1000+$plus_user['SUM(Mark)']-$minus_user['SUM(Mark)'];
  $evaluation[$K]['Count'] = $evaluation_count; 
  $evaluation[$K]['evaluation_Targets'] = $TeacherTarget; 
  $evaluation[$K]['TeacherClasscount'] = $TeacherClasscount; 
  $evaluation[$K]['Amr'] = $evaluation_Amr_count; 
  $evaluation[$K]['Bedo'] = $evaluation_Bedo_count; 
  $evaluation[$K]['Ahmed'] = $evaluation_Ahmed_count; 
  }

  $AmrTarget = $con->prepare("SELECT `Target` FROM Targets WHERE Code=?");
  $AmrTarget->execute(array("عمرو عبدالله"));
  $AmrTarget_fetch = $AmrTarget->fetch();
  $AmrTarget = $AmrTarget_fetch['Target'];


  $BedoTarget = $con->prepare("SELECT `Target` FROM Targets WHERE Code=?");
  $BedoTarget->execute(array("عبدالرحمن"));
  $BedoTarget_fetch = $BedoTarget->fetch();
  $BedoTarget = $BedoTarget_fetch['Target'];

  $AhmedTarget = $con->prepare("SELECT `Target` FROM Targets WHERE Code=?");
  $AhmedTarget->execute(array("أحمد منجود"));
  $AhmedTarget_fetch = $AhmedTarget->fetch();
  $AhmedTarget = $AhmedTarget_fetch['Target'];


    $AmrActualTarget = array_sum(array_column($evaluation, 'Amr'));
    $BedoActualTarget = array_sum(array_column($evaluation, 'Bedo'));
    $AhmedActualTarget = array_sum(array_column($evaluation, 'Ahmed'));
    $Amrtotal = number_format(($AmrActualTarget / $AmrTarget) * 100 , 0);
    $Bedototal = number_format(($BedoActualTarget / $BedoTarget) * 100 , 0);
    $Ahmedtotal = number_format(($AhmedActualTarget / $AhmedTarget) * 100 , 0);
    if ($AmrTarget /2 < $AmrActualTarget) {
      $AmrTargetcolor1 =  '#198754';
      $AmrTargetcolor2 =  '#18b16adb';
    }else {
      $AmrTargetcolor1 =  '#a01414';
      $AmrTargetcolor2 =  '#dc3545bf';
    }
    if ($BedoTarget /2 < $BedoActualTarget) {
      $BedoTargetcolor1 =  '#198754';
      $BedoTargetcolor2 =  '#18b16adb';
    }else {
      $BedoTargetcolor1 =  '#a01414';
      $BedoTargetcolor2 =  '#dc3545bf';
    }
    if ($AhmedTarget /2 < $AhmedActualTarget) {
      $AhmedTargetcolor1 =  '#198754';
      $AhmedTargetcolor2 =  '#18b16adb';
    }else {
      $AhmedTargetcolor1 =  '#a01414';
      $AhmedTargetcolor2 =  '#dc3545bf';
    }

 ?>
<main id="main" class="main">
  <div class="container">
    <div class="pagetitle">
      <h1>Team Evaluation </h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Team Evaluation</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    
    <section class="section">
      <div class="row">
      <div class=" col-lg-12">
        <div class="card">
          <div class="card-body col-12 col-lg-6 m-auto">
          <h1 class=" card-title text-center rtl"> <?php echo $DailyEvaluationCount ; ?>  إجمالى التقيمات اليوم </h1>
          <a href="EvaluationTeamTeachersDaily" class="btn btn-primary fs-6 fw-bold w-100 m-auto " >عرض تقيمات اليوم</a>
          </div>
        </div>
      </div>
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
                    <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 " >عرض</button>
                  </form>
              </div>
          </div>
      </div>


      <div class="col-lg-4 m-auto">
        <div class=" card ">
          <div class=" recent-sales">
            <h5 class="card-title text-center"> عمرو </h5>
            <div class="card-body  text-center">
              <div  id="radialBarChart">
              </div>
              <script>
              document.addEventListener("DOMContentLoaded", () => {
              new ApexCharts(document.querySelector("#radialBarChart"), {
              chart: {
              height: 280,
              type: "radialBar",
              },

              series: [<?php echo $Amrtotal; ?>],
              colors: ["<?php echo $AmrTargetcolor1; ?>"],
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
              gradientToColors: ["<?php echo $AmrTargetcolor2; ?>"],
              stops: [0, 80]
              }
              },
              stroke: {
              lineCap: "round"
              },
              labels: [` <?php echo $AmrActualTarget; ?> / <?php echo $AmrTarget; ?>`]
              }).render();
              });
              </script>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 m-auto">
        <div class=" card ">
          <div class=" recent-sales">
            <h5 class="card-title text-center"> عبدالرحمن </h5>
            <div class="card-body  text-center">
              <div  id="radialBarChart2">
              </div>
              <script>
              document.addEventListener("DOMContentLoaded", () => {
              new ApexCharts(document.querySelector("#radialBarChart2"), {
              chart: {
              height: 280,
              type: "radialBar",
              },

              series: [<?php echo $Bedototal; ?>],
              colors: ["<?php echo $BedoTargetcolor1; ?>"],
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
              gradientToColors: ["<?php echo $BedoTargetcolor2; ?>"],
              stops: [0, 80]
              }
              },
              stroke: {
              lineCap: "round"
              },
              labels: [` <?php echo $BedoActualTarget; ?> / <?php echo $BedoTarget; ?>`]
              }).render();
              });
              </script>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 m-auto">
        <div class=" card ">
          <div class=" recent-sales">
            <h5 class="card-title text-center"> أحمد </h5>
            <div class="card-body  text-center">
              <div  id="radialBarChart3">
              </div>
              <script>
              document.addEventListener("DOMContentLoaded", () => {
              new ApexCharts(document.querySelector("#radialBarChart3"), {
              chart: {
              height: 280,
              type: "radialBar",
              },

              series: [<?php echo $Ahmedtotal; ?>],
              colors: ["<?php echo $AhmedTargetcolor1; ?>"],
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
              gradientToColors: ["<?php echo $AhmedTargetcolor2; ?>"],
              stops: [0, 80]
              }
              },
              stroke: {
              lineCap: "round"
              },
              labels: [` <?php echo $AhmedActualTarget; ?> / <?php echo $AhmedTarget; ?>`]
              }).render();
              });
              </script>
            </div>
          </div>
        </div>
      </div>


        <div class="col-sm-12">
        <div class="card recent-sales ">

        <h5 class="card-title   text-center">  جميع تقيمات المعلمين</h5>

        <div class="card-body overflow-auto rtl text-center">
           <p><span> عدد حصص المعلمين خلال الشهر </span> <?php echo $AllTeacherClasscount ?> </p>
           <p><span> عدد الحصص التى سيدخلها الأشراف </span> 336 </p>
           <p><span> نسبة الحصص التى سيدخلها الأشراف </span> <?php echo '%'. $prsnt ?> </p>
        </div>
        </div>
        </div>

        <div class="col-sm-12">
        <div class="card recent-sales ">

        <h5 class="card-title   text-center">  جميع تقيمات المعلمين</h5>
        <h6 class=" text-center"> !  اضعط على اى معلم لعرض تفاصيل التقييمات </h6>

        <div class="card-body overflow-auto rtl text-center">
        <table id="DataTable" class="table table-border table-hover text-center  ">
        <thead >
        <tr>
        <th  scope="col">المعلم</th>
        <th scope="col"> المطلوب دخوله </th>
        <th scope="col"> تم دخوله </th>
        <th scope="col"> الباقى للدخول </th>
        <th scope="col">الدرجة</th>
        <th scope="col">عمرو</th>
        <th scope="col">عبدالرحمن</th>
        <th scope="col">أحمد</th>
        </tr>
        </thead>
        <tbody>

        <?php 
        foreach ($evaluation as $key => $value) { ?>
        <tr onclick="window.location='<?php echo 'EvaluationTeamTeachersCode?Code='.$value['Code']?>';" role="button">
        <td class="fw-bold fs-5"><?php echo $value['Code'] ?></td>
        <td class="fw-bold fs-5"><?php echo $value['evaluation_Targets']?></td>
        <td class="fw-bold fs-5"><?php echo $value['Count'] ?></td>
        <td class="fw-bold fs-5  <?php echo $value['Count']-$value['evaluation_Targets'] < 0 ? 'text-danger' : ' text-success' ?>"><?php echo $value['Count']-$value['evaluation_Targets']?></td>
        <td class="fw-bold fs-5"><?php echo $value['Mark'] ?></td>
        <td class="fw-bold fs-5"><?php echo $value['Amr'] ?></td>
        <td class="fw-bold fs-5"><?php echo $value['Bedo'] ?></td>
        <td class="fw-bold fs-5"><?php echo $value['Ahmed'] ?></td>
        </tr>
        <?php }
        ?>
        </tbody>
        </table>
        </div>
        </div>
        </div>
      </div>
    </section>
  </div>


</main><!-- End #main -->
<?php include "assets/tem/footer.php" ?>
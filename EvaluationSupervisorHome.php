<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['CustomerService']) AND !isset($_SESSION['Admin']) AND !isset($_SESSION['Suber_Admin']) AND !isset($_SESSION['manager']) AND !isset($_SESSION['Supervising_manager']) AND !isset($_SESSION['supervisor'])) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ; 
if (isset($_SESSION['Suber_Admin'])) {
  if (isset($_SESSION['manager'])) {
    $session_code = "Belal" ;
  }else {
    $session_code = $_SESSION['Suber_Admin'] ;
  }
}elseif (isset($_SESSION['manager'])) {
  $session_code = $_SESSION['manager'] ;
}elseif (isset($_SESSION['supervisor'])) {
  $session_code = $_SESSION['supervisor'] ;
}
elseif (isset($_SESSION['Supervising_manager'])) {
  $session_code = $_SESSION['Supervising_manager'] ;
}
elseif (isset($_SESSION['CustomerService'])) {
  $session_code = $_SESSION['CustomerService'] ;
}
else{
  $session_code = $_SESSION['Admin'] ;
}


$this_month = date("Y-m-01");
$performance = array();
$Days_code_stmt = $con->prepare("SELECT  min(`Date`) AS `Date`  FROM EvaluationTeam WHERE Code=?  AND `Date`>=? GROUP BY `Date` ORDER BY `Date`  ASC ");
$Days_code_stmt->execute(array($session_code,$this_month));
$Days= $Days_code_stmt->fetchAll();

foreach ($Days as $key => $Day ) {
  $Days_code_stmt = $con->prepare("SELECT  * FROM EvaluationTeam WHERE Code=? AND `type`=? AND `Date`=? ");
  $Days_code_stmt->execute(array($session_code,"1",$Day['Date']));
  $Day_performance= $Days_code_stmt->fetch();

  $plus_code_stmt = $con->prepare("SELECT  SUM(Mark) FROM EvaluationTeam WHERE Code=? AND `Status`=? AND `Date`=? AND Trust = ?");
  $plus_code_stmt->execute(array($session_code,"+",$Day['Date'],3));
  $plus = $plus_code_stmt->fetch();

  $minus_code_stmt = $con->prepare("SELECT  SUM(Mark) FROM EvaluationTeam WHERE Code=? AND `Status`=? AND `Date`=? AND Trust = ?");
  $minus_code_stmt->execute(array($session_code,"-",$Day['Date'],3));
  $minus = $minus_code_stmt->fetch();

  $performance[$key]['Date']        = $Day['Date'];
  $performance[$key]['performance'] = $Day_performance['performance'] ?? "لا يوجد";
  $performance[$key]['minus']       = $minus['SUM(Mark)'];
  $performance[$key]['plus']        = $plus['SUM(Mark)'];

}

$plus_code_stmt = $con->prepare("SELECT  SUM(Mark) FROM EvaluationTeam WHERE Code=? AND `Status`=? AND `Date`>=?  AND Trust = ?");
$plus_code_stmt->execute(array($session_code,"+",$this_month,3));
$plus = $plus_code_stmt->fetch();

$minus_code_stmt = $con->prepare("SELECT  SUM(Mark) FROM EvaluationTeam WHERE Code=? AND `Status`=? AND `Date`>=?  AND Trust = ?");
$minus_code_stmt->execute(array($session_code,"-",$this_month,3));
$minus = $minus_code_stmt->fetch();

$evaluation_1 = 52 ;
$mark = $evaluation_1+$plus['SUM(Mark)']-$minus['SUM(Mark)'];
$total = number_format(($mark / $evaluation_1) * 100 , 0);
if ($mark > 52) {
  $color1 =  '#198754';
  $color2 =  '#18b16adb';
}else {
  $color1 =  '#a01414';
  $color2 =  '#dc3545bf';
}
// -------------------

// ---------------------
$last_month = date("Y-m-01",strtotime("-1 month"));
$this_month = date("Y-m-01");
$last_month_plus_code_stmt = $con->prepare("SELECT  SUM(Mark) FROM EvaluationTeam WHERE Code=? AND `Status`=? AND `Date`>=? AND `Date`< ?  AND Trust = ?");
$last_month_plus_code_stmt->execute(array($session_code,"+",$last_month,$this_month,3));
$last_month_plus = $last_month_plus_code_stmt->fetch();
$last_month_minus_code_stmt = $con->prepare("SELECT  SUM(Mark) FROM EvaluationTeam WHERE Code=? AND `Status`=? AND `Date`>=? AND `Date`< ?  AND Trust = ?");
$last_month_minus_code_stmt->execute(array($session_code,"-",$last_month,$this_month,3));
$last_month_minus = $last_month_minus_code_stmt->fetch();
$last_month_mark = $evaluation_1+$last_month_plus['SUM(Mark)']-$last_month_minus['SUM(Mark)'];
$last_month_total = number_format(($last_month_mark / $evaluation_1) * 100 , 0);

// -------------------

// ---------------------
$pre_last_month = date("Y-m-01",strtotime("-2 month"));
$last_month = date("Y-m-01",strtotime("-1 month"));
$pre_last_month_plus_code_stmt = $con->prepare("SELECT  SUM(Mark) FROM EvaluationTeam WHERE Code=? AND `Status`=? AND `Date`>=? AND `Date`< ?  AND Trust = ?");
$pre_last_month_plus_code_stmt->execute(array($session_code,"+",$pre_last_month,$last_month,3));
$pre_last_month_plus = $pre_last_month_plus_code_stmt->fetch();
$pre_last_month_minus_code_stmt = $con->prepare("SELECT  SUM(Mark) FROM EvaluationTeam WHERE Code=? AND `Status`=? AND `Date`>=? AND `Date`< ?  AND Trust = ?");
$pre_last_month_minus_code_stmt->execute(array($session_code,"-",$pre_last_month,$last_month,3));
$pre_last_month_minus = $pre_last_month_minus_code_stmt->fetch();
$pre_last_month_mark = $evaluation_1+$pre_last_month_plus['SUM(Mark)']-$pre_last_month_minus['SUM(Mark)'];
$pre_last_month_total = number_format(($pre_last_month_mark / $evaluation_1) * 100 , 0);

// -------------------
?>
<main id="main" class="main">
  <div class="container">
    <div class="pagetitle">
      <h1>Evaluation Page</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Evaluation</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    
    <section class="section dashboard">
        <div class="row ">

          <div class="col-lg-8">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title text-center">أدائك خلال الثلاث أشهر</h5>

                  <!-- Area Chart -->
                  <div id="areaChart"></div>

                  <script>
                    document.addEventListener("DOMContentLoaded", () => {

                      new ApexCharts(document.querySelector("#areaChart"), {
                        
                        
                        series: [
                          {
                            name: "نقاطك",
                            data: [<?php echo $pre_last_month_mark; ?>,<?php echo $last_month_mark; ?>, <?php echo $mark; ?>]
                          }
                        ],
                        chart: {
                          type: 'area',
                          height: 350,
                          zoom: {
                            enabled: false
                          }
                        },
                        annotations: {
                          yaxis: [{
                            y: 52,
                            borderColor: '#999',
                            label: {
                              show: true,
                              text: 'الدرجة الأساسية',
                              style: {
                                color: "#fff",
                                background: '#1446a0bf'
                              }
                            }
                          }]},
                        dataLabels: {
                          enabled: false
                        },
                        stroke: {
                          curve: 'smooth'
                        },

                        xaxis: {
                          categories: [
                            " الشهر قبل السابق ",
                            " الشهر السابق ",
                            " الشهر الحالى "
                          ]
                        },
                        legend: {
                          horizontalAlign: 'left'
                        }
                      }).render();
                    });
                  </script>
                  <!-- End Area Chart -->

                </div>
              </div>
          </div>


            <div class="col-sm-4">
              <div class="card">
                <div class="card-body ">
                <h5 class="card-title text-center">  تقيمك الشهرى | <?php echo $session_code;?> </h5>
                      <div  id="radialBarChart"></div>
                      <?php if ($mark > 52) { ?>
                        <div class="row ">
                          <div class="col-4">
                            <script src="https://cdn.lordicon.com/bhenfmcm.js"></script>
                            <lord-icon
                                src="https://cdn.lordicon.com/kejpvrvr.json"
                                trigger="loop"
                                delay="500"
                                colors="primary:#121331,secondary:#ffc738,tertiary:#f24c00,quaternary:#ebe6ef,quinary:#b26836"
                                style="width:125px;height: 125px;px">
                            </lord-icon>
                          </div>
                          <div class="alert alert-light col-8 text-center fs-6 fw-bold" role="alert">
                            <p> تهانيا لقد تجاوزت 52 نقطة </p>
                            <p> جزاكم الله خيرا اداء رائع </p>
                          </div>
                        </div>
                      <?php  } ?>
                      <script>
                        document.addEventListener("DOMContentLoaded", () => {
                          new ApexCharts(document.querySelector("#radialBarChart"), {
                              chart: {
                              height: 280,
                              type: "radialBar",
                              },

                              series: [<?php echo $total; ?>],
                              colors: ["<?php echo $color1; ?>"],
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
                              gradientToColors: ["<?php echo $color2; ?>"],
                              stops: [0, 80]
                              }
                              },
                              stroke: {
                              lineCap: "round"
                              },
                              labels: [` <?php echo $mark; ?> / <?php echo $evaluation_1; ?>`]
                        }).render();
                        });
                      </script>
                </div>
              </div>
            </div>

                <!-- Recent Sales -->
                <div class="col-sm-12">
                  <div class="card recent-sales ">

                  <h5 class="card-title text-center">  تقيمك الشهرى </h5>
                    <p class="text-muted  text-center"> لديك اول كل شهر 52 نقطه يتم الخصم منهم بسبب التقيمات السلبيه أو الأضافة لهم بسبب التقيمات الأبجابية </p>

                    <div class="card-body max-h rtl text-center">
                        <table  class="table table table-borderless text-center DataTable  ">
                          <thead >
                            <tr>
                              <th  scope="col">التاريخ</th>
                              <th scope="col">تقييم اليوم</th>
                              <th scope="col">عدد نقاط الخصم</th>
                              <th scope="col">عدد نقاط البونص</th>
                              <th scope="col">تفاصيل الخصم و والبونص</th>
                            </tr>
                          </thead>
                          <tbody>

                          <?php 
                                  if ($performance == 0) {
                                    ?> 
                                    <td  class="fw-bold fs-5" colspan="4"> لا توجد تقيمات مسجلة </td> 
                                    <?php
                                    }
                          foreach ($performance as  $value) { ?>
                            <tr>
                              <td> <?php echo $value['Date'] ?> </td>
                              <td><?php echo $value['performance'] ?></td>
                              <td><?php echo $value['minus'] ?></td>
                              <td><?php echo $value['plus'] ?></td>
                              <td><a href="EvaluationSupervisorDetails?Date=<?php echo $value['Date'] ?>"><script src="https://cdn.lordicon.com/bhenfmcm.js"></script> <lord-icon src="https://cdn.lordicon.com/dnmvmpfk.json" trigger="loop-on-hover"  colors="primary:#121331"style="width:25px;height:25px"></lord-icon></a></td>
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
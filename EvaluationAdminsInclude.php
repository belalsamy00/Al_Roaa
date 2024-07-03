<?php 

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
  $session_code = 'Amr' ;
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
  $plus_code_stmt->execute(array($session_code,"+",$Day['Date'],5));
  $plus = $plus_code_stmt->fetch();

  $minus_code_stmt = $con->prepare("SELECT  SUM(Mark) FROM EvaluationTeam WHERE Code=? AND `Status`=? AND `Date`=? AND Trust = ?");
  $minus_code_stmt->execute(array($session_code,"-",$Day['Date'],5));
  $minus = $minus_code_stmt->fetch();

  $performance[$key]['Date']        = $Day['Date'];
  $performance[$key]['performance'] = $Day_performance['performance'] ?? "لا يوجد";
  $performance[$key]['minus']       = $minus['SUM(Mark)'];
  $performance[$key]['plus']        = $plus['SUM(Mark)'];

}

$plus_code_stmt = $con->prepare("SELECT  SUM(Mark) FROM EvaluationTeam WHERE Code=? AND `Status`=? AND `Date`>=?  AND Trust = ?");
$plus_code_stmt->execute(array($session_code,"+",$this_month,5));
$plus = $plus_code_stmt->fetch();

$minus_code_stmt = $con->prepare("SELECT  SUM(Mark) FROM EvaluationTeam WHERE Code=? AND `Status`=? AND `Date`>=?  AND Trust = ?");
$minus_code_stmt->execute(array($session_code,"-",$this_month,5));
$minus = $minus_code_stmt->fetch();

$evaluation_1 = 1000 ;
$mark = $evaluation_1+$plus['SUM(Mark)']-$minus['SUM(Mark)'];
$total = number_format(($mark / $evaluation_1) * 100 , 0);
if ($mark > 1000) {
  $color1 =  '#198754';
  $color2 =  '#18b16adb';
}else {
  $color1 =  '#a01414';
  $color2 =  '#dc3545bf';
}
// -------------------


?>
            <div class="col-sm-6 my-2">
              <div class="card h-100">
                <div class="card-body ">
                  <h5 class="card-title text-center">  تقيمك لمهام السيستم | <?php echo $session_code;?> </h5>
                      <div  id="radialBarChart"></div>
                      <?php if ($mark > 1000) { ?>
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
                            <p> تهانيا لقد تجاوزت 1000 نقطة </p>
                            <p> جزاكم الله خيرا اداء رائع </p>
                          </div>
                        </div>
                      <?php  } ?>
                      <div> <a class="btn btn-outline-primary fs-6 fw-bold w-100 border-0 mb-2 m-auto"  href="EvaluationAdminsHome">تفاصيل التقيمات</a></div>
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
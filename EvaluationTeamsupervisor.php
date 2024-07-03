<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['Admin']) AND !isset($_SESSION['Suber_Admin']) AND !isset($_SESSION['manager']) AND !isset($_SESSION['Supervising_manager']) ) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ;
if (isset($_GET['Start']) AND isset($_GET['End'])) {
  $this_month = $_GET['Start'];
  $Next_month = $_GET['End'];
}else {
  $this_month = date("Y-m-01");
  $Next_month = date("Y-m-d");
}
$evaluation =array();
  $users_stmt = $con->prepare("SELECT * FROM users WHERE Code = ? OR Code = ? OR Code = ? ORDER BY `Code` ASC  ");
  $users_stmt->execute(array('a.mangod','Amr','Bedo'));
  $users=$users_stmt->fetchAll();

foreach ($users as $K => $user) {

$plus_user_stmt = $con->prepare("SELECT  SUM(Mark) FROM EvaluationTeam WHERE Code=? AND `Status`=? AND `Date`>=? AND `Date`<=? AND Trust = ?");
$plus_user_stmt->execute(array($user['Code'],"+",$this_month,$Next_month,3));
$plus_user = $plus_user_stmt->fetch();

$minus_user_stmt = $con->prepare("SELECT  SUM(Mark) FROM EvaluationTeam WHERE Code=? AND `Status`=? AND `Date`>=? AND `Date`<=? AND Trust = ?");
$minus_user_stmt->execute(array($user['Code'],"-",$this_month,$Next_month,3));
$minus_user = $minus_user_stmt->fetch();

$evaluation_user_stmt = $con->prepare("SELECT * FROM EvaluationTeam WHERE Code=? AND `Date`>=? AND `Date`<=? AND Trust = ?");
$evaluation_user_stmt->execute(array($user['Code'],$this_month,$Next_month,3));
$evaluation_user_count = $evaluation_user_stmt->rowCount();

$evaluation[$K]['Code'] = $user['Code']; 
$evaluation[$K]['trust'] = $user['trust']; 
$evaluation[$K]['Mark'] = 52+$plus_user['SUM(Mark)']-$minus_user['SUM(Mark)'];
$evaluation[$K]['Count'] = $evaluation_user_count; 
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
      <div class="col-lg-12">
          <div class="card">
              <div class="card-body ">
              <h5 class="card-title text-center">   </h5> 
                  <form id="form-2"class="mt-2 w-50 m-auto" method="GET" action="">
                    <div class="input-group mb-3">
                      <span class="input-group-text" id="basic-addon1">From</span>
                      <input type="date" class="form-control" value="<?php echo $this_month ;?>"  name="Start" aria-label="Start" aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group mb-3">
                      <span class="input-group-text" id="basic-addon1">To</span>
                      <input type="date" class="form-control" value="<?php echo $Next_month ;?>" name="End" aria-label="End" aria-describedby="basic-addon1">
                    </div>
                    <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 " >عرض مدة محددة</button>
                  </form>
              </div>
          </div>
      </div>
        <div class="col-sm-12">
        <div class="card recent-sales ">

        <h5 class="card-title   text-center">  جميع تقيمات الفريق</h5>

        <div class="card-body rtl text-center">
        <table id="DataTable" class="table table-borderless text-center DataTable ">
        <thead >
        <tr>
        <th  scope="col">Code</th>
        <th scope="col">Evaluation Count </th>
        <th scope="col">Marke</th>
        </tr>
        </thead>
        <tbody>

        <?php 
        foreach ($evaluation as $key => $value) { ?>
        <tr>
        <td class="fw-bold fs-5"> <a href="<?php echo "Evaluation?Code=".$value['Code']?>"><?php echo $value['Code'] ?></a></td>
        <td class="fw-bold fs-5"><?php echo $value['Count'] ?></td>
        <td class="fw-bold fs-5"><?php echo $value['Mark'] ?></td>
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
<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_GET['Date'])) {  header('Location: index');  exit;  } 
if (!isset($_SESSION['CustomerService']) AND!isset($_SESSION['Admin']) AND !isset($_SESSION['Suber_Admin']) AND !isset($_SESSION['manager'])AND !isset($_SESSION['Supervising_manager']) AND !isset($_SESSION['supervisor'])) {  header('Location: index');  exit;  } 
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
$this_month = $_GET['Date'];

$session_code_stmt = $con->prepare("SELECT  * FROM EvaluationTeam WHERE Code=?  AND `Date`=? AND `type`=2   AND Trust = 3");
$session_code_stmt->execute(array($session_code,$this_month));
$session_code_fetch = $session_code_stmt->fetchAll();
$session_code_count = $session_code_stmt->rowCount();

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
                <div class="col-sm-12">
                  <div class="card recent-sales ">

                    <h5 class="card-title   text-center"> تقيمك  <span>| <?php echo $this_month ?></span></h5>
                    <div class="card-body max-h rtl text-center">
                        <table  class="table table-borderless text-center  ">
                          <thead >
                            <tr>
                              <th  scope="col">التاريخ</th>
                              <th scope="col">الراسل</th>
                              <th scope="col">النوع</th>
                            </tr>
                          </thead>
                          <tbody>

                          <?php 
                                  if ($session_code_fetch == 0) {
                                    ?> 
                                    <td  class="fw-bold fs-5" colspan="3"> لا توجد تقيمات مسجلة </td> 
                                    <?php
                                    }
                          foreach ($session_code_fetch as  $value) { ?>
                            <tr>
                              <td> <?php echo $value['Date'] ?> </td>
                              <td><?php echo " أ ".$value['Who'] ?></td>
                              <td><span class="<?php if ($value['Status'] == "-") { echo "badge bg-danger"; }else { echo "badge bg-success"; } ?>"><?php if ($value['Status'] == "-") { echo "خصم"." ".$value['Mark']." "."نقط"; }else { echo "اضافة"." ".$value['Mark']." "."نقط" ;} ?></span></td>
                            </tr>
                            <tr>
                              <td colspan="3"><?php echo $value['Dis'] ?></td>
                            </tr>
                            <tr>
                              <td colspan="3"><hr></td>
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


</main>
<?php include "assets/tem/footer.php" ?>
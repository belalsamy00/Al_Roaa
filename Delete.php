<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['Admin']) AND !isset($_SESSION['Suber_Admin']) AND !isset($_SESSION['manager'])) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ; 
include "functions/CS-functions.php" ; ?>

<main id="main" class="main">
  <div class="container">
    <div class="pagetitle">
      <h1>Blank Page</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Blank</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    
    <section class="section">
      <div  class= "col-sm-8 col-lg-6 col-xl-4 text-center" style="margin-left: auto;margin-right: auto;margin-top: 66px;font-size: 20px;font-weight: bold;color: #0454ab;">
        <div >
    
          <div >
            <div >
            <?php 
            if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
              $id = filter_var($_GET["ID"], FILTER_UNSAFE_RAW );
              $code = filter_var($_GET["code"], FILTER_UNSAFE_RAW );
              $stmt = $con->prepare("SELECT * FROM history WHERE ID=?");
              $stmt->execute(array($id));
              $stmtn =$stmt->fetch();
              $count = $stmt->rowCount();
              if ($count > 0) {
              $name = $stmtn["S_name"];
              $stringName = str_replace(" ","",$name);
              $name_explode= explode('-',$stringName);
              }
              $Delete_cs_stmt = $con->prepare("DELETE FROM `history` WHERE  ID = ?  ");
              $Delete_cs_stmt->execute(array($id));
              $Delete_cs_count = $Delete_cs_stmt->rowCount();
              
               if ($Delete_cs_count > 0) { 
                ?>
                <div id="spin"  style="margin-top: 20rem!important">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
                </div>
              <?php
              if (isset($name_explode[0]) ) {
                if ($name_explode[0] != "oneTimeClass") {
                  DecreaseRemained($code);
                }
              }else {
                DecreaseRemained($code);
              }
              $_SESSION['Emessage'] = 'تم حذف الحلقة بنجاح  ';
              header('Location: ' . $_SERVER['HTTP_REFERER']);
              exit;
             } else {
              $_SESSION['Emessage'] = 'لم يتم حذف الحلقة برجاء المحاولة مرة أخرى  ';
              header('Location: ' . $_SERVER['HTTP_REFERER']);
              exit;
                }
            }else {
                header('location: 404');exit;
            }
            ?>
            </div>
          </div>
    
        </div>
      </div>
    </section>
  </div>


</main><!-- End #main -->
<?php include "assets/tem/footer.php" ?>
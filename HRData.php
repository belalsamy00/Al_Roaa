<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['Admin']) AND !isset($_SESSION['Suber_Admin']) AND !isset($_SESSION['manager'])) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ; 

if($_SERVER ['REQUEST_METHOD']== 'POST') {
  if (isset($_POST['Send'])) {
    $Code = $_POST['Code'];
    $Hr_data_code_stmt = $con->prepare("UPDATE Hr_data SET `Contract`=? WHERE Code=?");
    $Hr_data_code_stmt->execute(array(1,$Code));
    $Hr_data_rowCount = $Hr_data_code_stmt->rowCount();
    if ($Hr_data_rowCount > 0 ) {
      $_SESSION['Emessage'] = 'تم إرسال العقد بنجاح ';
      header('Location: '.$_SERVER['PHP_SELF']);
      exit;
    }else {
      $_SESSION['Emessage'] =  'لم يتم إرسال العقد حاول مرة أخرى ';
      header('Location: '.$_SERVER['PHP_SELF']);
      exit;
    }
  }
  elseif (isset($_POST['NewEm'])) {
    $ID = rand(0,1000).date("d").rand(9,99);
    $Code = $_POST['Code'];
    $Name = $_POST['Name'];
    $IDNumber = $_POST['IDNumber'];
    $HourRate = $_POST['HourRate'];
  
    $SELECTNewEm = $con->prepare("SELECT * FROM Hr_data WHERE `ID` = ? ");
    $SELECTNewEm->execute(array($Code));
    $SELECTNewEmfetch = $SELECTNewEm->fetch() ;
    $SELECTNewEmcount = $SELECTNewEm->rowCount() ;
    if ($SELECTNewEmcount > 0) {
      $_SESSION['Emessage'] =  'لم يتم أضافة الموظف كود الموظف موجود من قبل ';
      header('Location: '.$_SERVER['PHP_SELF']);
      exit;
    }else {
      $INSERTNewEm = $con->prepare("INSERT INTO Hr_data
      (ID,Code,IDNumber,`Name`,HourRate,`Contract`) VALUES (?,?,?,?,?,?)");
      $INSERTNewEm->execute(array($ID,$Code,$IDNumber,$Name,$HourRate,0));
      $INSERTNewEmCount = $INSERTNewEm->rowCount() ;
      if ($INSERTNewEmCount > 0 ) {
        $_SESSION['Emessage'] = 'تم أضافة الموظف بنجاح ';
        header('Location: '.$_SERVER['PHP_SELF']);
        exit;
      }else {
        $_SESSION['Emessage'] =  'لم يتم أضافة الموظف حاول مرة أخرى ';
        header('Location: '.$_SERVER['PHP_SELF']);
        exit;
      }
    }
  
  }
  elseif (isset($_POST['EditeEm'])) {
    $ID = $_POST['ID'];
    $Code = $_POST['Code'];
    $Name = $_POST['Name'];
    $IDNumber = $_POST['IDNumber'];
    $HourRate = $_POST['HourRate'];
  
    $SELECTEditeEm = $con->prepare("UPDATE Hr_data SET `Code`=?,`IDNumber`=?,`Name`=?,`HourRate`=?  WHERE ID=?");
    $SELECTEditeEm->execute(array($Code,$IDNumber,$Name,$HourRate,$ID));
    $SELECTEditeEmcount = $SELECTEditeEm->rowCount() ;
    if ($SELECTEditeEmcount > 0 ) {
      $_SESSION['Emessage'] = 'تم تعديل بيانات الموظف بنجاح ';
      header('Location: '.$_SERVER['PHP_SELF']);
      exit;
    }else {
      $_SESSION['Emessage'] =  'لم يتم تعديل بيانات الموظف حاول مرة أخرى ';
      header('Location: '.$_SERVER['PHP_SELF']);
      exit;
    }
  
  }
  else {
    $Code = $_POST['Code'];
    $Hr_data_code_stmt = $con->prepare("UPDATE Hr_data SET `Contract`=? WHERE Code=?");
    $Hr_data_code_stmt->execute(array(0,$Code));
    $Hr_data_rowCount = $Hr_data_code_stmt->rowCount();
    if ($Hr_data_rowCount > 0 ) {
      $_SESSION['Emessage'] = 'تم التراجع عن إرسال العقد بنجاح ';
      header('Location: '.$_SERVER['PHP_SELF']);
      exit;
    }else {
      $_SESSION['Emessage'] =  'لم يتم التراجع عن إرسال العقد حاول مرة أخرى ';
      header('Location: '.$_SERVER['PHP_SELF']);
      exit;
    }
  }
}
elseif (isset($_GET['9'])) {
  $IDNumber = $_GET['IDNumber'];
  $src = "assets/img/Contracts/".$IDNumber.".png";
  unlink($src);
  $Hr_data_code_stmt = $con->prepare("UPDATE Hr_data SET `Contract`=? , RenewalDate = ? , `Text`=? WHERE ID=?");
  $Hr_data_code_stmt->execute(array(0,"","",$_GET['id']));
  $Hr_data_rowCount = $Hr_data_code_stmt->rowCount();
  if ($Hr_data_rowCount > 0 ) {
    $_SESSION['Emessage'] = 'تم حذف العقد بنجاح ';
    header('Location: '.$_SERVER['PHP_SELF']);
    exit;
  }else {
    $_SESSION['Emessage'] =  'لم يتم حذف العقد حاول مرة أخرى ';
    header('Location: '.$_SERVER['PHP_SELF']);
    exit;
  }
}
elseif (isset($_GET['10'])) {
  $IDNumber = $_GET['IDNumber'];
  $src = "assets/img/Contracts/".$IDNumber.".png";
  if (unlink($src)) 
  $Hr_data_code_stmt = $con->prepare("DELETE FROM Hr_data WHERE ID=?");
  $Hr_data_code_stmt->execute(array($_GET['id']));
  $Hr_data_rowCount = $Hr_data_code_stmt->rowCount();
  if ($Hr_data_rowCount > 0 ) {
    $_SESSION['Emessage'] = 'تم حذف  بيانات الموظف  بنجاح ';
    header('Location: '.$_SERVER['PHP_SELF']);
    exit;
  }else {
    $_SESSION['Emessage'] =  'لم يتم حذف  بيانات الموظف  حاول مرة أخرى ';
    header('Location: '.$_SERVER['PHP_SELF']);
    exit;
  }
}

$stmt_2 = $con->prepare("SELECT * FROM Hr_data WHERE `Contract` != ? ORDER BY `Code` ASC ");
$stmt_2->execute(array(9));
$T_2= $stmt_2->fetchAll() ;
$count_2 = $stmt_2->rowCount() ;

?>
<main id="main" class="main">
  <div class="container">
    <div class="pagetitle">
      <h1>HR Data Page</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">HR Data</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    
    <section class="section">
      <div class="row ">

      <div class="col-lg-12 mb-5 d-flex"> 
      <button type="button" class="btn btn-success fs-6 fw-bold  border-0 text-center m-auto d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#NewEm">موظف جديد  
        <lord-icon
            src="https://cdn.lordicon.com/ejxwvtlg.json"
            trigger="loop-on-hover"
            delay="1000"
            colors="outline:#198754,primary:#08a88a,secondary:#ebe6ef"
            style="width:50px;height:50px">
        </lord-icon>
        </button> 
        </div>

        <!-- Modal -->
        <div class="modal fade" id="NewEm" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="NewEmLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="NewEmLabel"> </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
              <form  action=""  method="post" class=" fs-6 fw-bold mt-2 w-100">
                
                  <div class="mb-3">
                    <label for="message-text" class="col-form-label"> الكود :</label>
                    <input type="text" name="Code" class="form-control" required='true'>
                  </div>
                  <div class="mb-3">
                    <label for="message-text" class="col-form-label">الأسم :</label>
                    <input type="text" name="Name" class="form-control" required='true'>
                  </div>
                  <div class="mb-3">
                    <label for="message-text" class="col-form-label"> الرقم القومى :</label>
                    <input type="number" name="IDNumber" class="form-control" required='true'>
                  </div>
                  <div class="mb-3">
                    <label for="message-text" class="col-form-label"> الراتب :</label>
                    <input type="number" name="HourRate" class="form-control" required='true'>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" name="NewEm" class="btn btn-primary">إرسال</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                </div>
                </form>
              </div>

            </div>
          </div>
        </div>

        <!-- Recent Sales -->
        <div class="col-sm-12">
          <div class="card recent-sales ">

          <h5 class="card-title text-center"> قبل إرسال العقد للموظف تأكد من جميع بياناته</h5>

          <div class="card-body rtl overflow-auto text-center">
            <table  class="table table-borderless text-center  ">
              <thead >
                <tr>
                <th scope="col"> الكود  </th>
                <th scope="col"> الرقم القومى </th>
                <th scope="col"> الإسم </th>
                <th scope="col"> التجديد </th>
                <th scope="col"> الراتب </th>
                <th scope="col"> العقد </th>
                <th scope="col"> إجراء </th>
                </tr>
              </thead>
              <tbody>
                <?php if ($count_2 > 0) {foreach ($T_2 as  $value) { ?>
                  <tr>
                    <td> <a href="<?php echo "TotalSallry?Code=".$value['Code'] ?>"><?php echo $value['Code'] ?></a></td>
                    <td>  <?php echo $value['IDNumber'] ?>  </td>
                    <td>  <?php echo $value['Name'] ?>  </td>
                    <td>  <?php echo $value['RenewalDate'] ?>  </td>
                    <td>  <?php echo $value['HourRate'] ?>  </td>
                    <td class="text-center" >  <?php
                      if ($value['Contract'] == 0) { ?>
                        <form action="" method="post">
                          <input type="hidden" name="Send">
                          <input type="hidden" name="Code" value="<?php echo $value['Code'] ?>">
                          <input class="btn btn-secondary fs-6 fw-bold w-100" type="submit" value=" إرسال العقد ">
                        </form>
                      <?php }elseif ($value['Contract'] == 1) { ?>
                        <div class="btn-group ltr w-100">
                          <form action="" method="post" class="w-50 text-nowrap">
                            <input type="hidden" name="UnSend">
                            <input type="hidden" name="Code" value="<?php echo $value['Code'] ?>">
                            <button class="btn btn-danger text-center fs-6 fw-bold w-100" > حذف العقد </button>
                          </form> 
                          <button type="button" class="btn btn-light fs-6 fw-bold  text-nowrap w-50" disabled > <span> لم يوقع بعد </span> </button> 
                        </div>
                      <?php }else { ?>
                        <div class="btn-group ltr w-100">
                          <a onclick="return confirm('هل انت متأكد من حذف العقد الخاص ب الموظف <?php echo $value['Code'] ?> ؟');" href="?9&id=<?php echo $value['ID'] ?>&IDNumber=<?php echo $value['IDNumber'] ?>" class="btn btn-danger fs-6 fw-bold text-nowrap  w-50" > حذف العقد </a> 
                          <button type="button" class="btn btn-success fs-6 fw-bold text-nowrap  w-50" data-bs-toggle="modal" data-bs-target="#id<?php echo $value['IDNumber'] ?>"> عرض العقد </button> 
                        </div>
                      <?php } ?> </td>
                      <td>
                      <?php if ($value['Contract'] == 2) { ?>
                        <div class="btn-group ltr w-100">
                          <a onclick="return confirm('هل انت متأكد من حذف بيانات الموظف <?php echo $value['Code'] ?> ؟');" href="?10&id=<?php echo $value['ID'] ?>&IDNumber=<?php echo $value['IDNumber'] ?>" class="btn btn-danger fs-6 fw-bold text-nowrap  w-50" > حذف البيانات </a> 
                          <button type="button" class="btn btn-primary fs-6 fw-bold " data-bs-toggle="modal" data-bs-target="#NOEdite"> <span class="text-nowrap"> تعديل البيانات </span></button>
                        </div>
                      <?php }else  { ?>
                        <div class="btn-group ltr w-100">
                          <a onclick="return confirm('هل انت متأكد من حذف بيانات الموظف <?php echo $value['Code'] ?> ؟');" href="?10&id=<?php echo $value['ID'] ?>&IDNumber=<?php echo $value['IDNumber'] ?>" class="btn btn-danger fs-6 fw-bold text-nowrap  w-50" > حذف البيانات </a> 
                          <button type="button" class="btn btn-primary fs-6 fw-bold " data-bs-toggle="modal" data-bs-target="#Edite<?php echo $value['IDNumber'] ?>"> <span class="text-nowrap"> تعديل البيانات </span></button> 
                        </div>
                      <?php }  ?>
                      </td>
                  </tr>
                <?php  }} ?>
              </tbody>
            </table>
            <!-- ------------------------------------------------ -->
            <div class="modal fade" id="NOEdite" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="NOEdite" aria-hidden="true">
              <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <h5 class="card-title text-center"> لا يمكن تعديل البيانات هناك عقد مرتبط بهذة البيانات احذف العقد أولا</h5>
                  </div>

                </div>
              </div>
              </div>
              <!-- ------------------------------------------------ -->
            <?php foreach ($T_2 as  $value) { ?>
              <!-- ------------------------------------------------ -->
              <div class="modal fade" id="Edite<?php echo $value['IDNumber'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Edite<?php echo $value['IDNumber'] ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <form  action=""  method="post" class=" fs-6 fw-bold mt-2 w-100">
                        <div class="mb-3">
                          <label for="message-text" class="col-form-label"> الكود :</label>
                          <input type="text" name="Code" readonly  value="<?php echo $value['Code'] ?>" class="form-control" required='true'>
                          <input type="hidden" name="ID" readonly  value="<?php echo $value['ID'] ?>" class="form-control" required='true'>
                        </div>
                        <div class="mb-3">
                          <label for="message-text" class="col-form-label">الأسم :</label>
                          <input type="text" name="Name"  value="<?php echo $value['Name'] ?>" class="form-control" required='true' >
                        </div>
                        <div class="mb-3">
                          <label for="message-text" class="col-form-label"> الرقم القومى :</label>
                          <input type="number" name="IDNumber"  value="<?php echo $value['IDNumber'] ?>" class="form-control" required='true'>
                        </div>
                        <div class="mb-3">
                          <label for="message-text" class="col-form-label"> الراتب :</label>
                          <input type="number" name="HourRate"  value="<?php echo $value['HourRate'] ?>" class="form-control" required='true'>
                        </div>
                        <div class="modal-footer">
                          <button type="submit" name="EditeEm" class="btn btn-primary">تعديل</button>
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <!-- ------------------------------------------------ -->

            <?php } ?>

            <?php foreach ($T_2 as  $value) { ?>
            <?php if ($value['Contract'] == 2) { ?>
              <!-- ------------------------------------------------ -->
              <div class="modal fade" id="id<?php echo $value['IDNumber'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="id<?php echo $value['IDNumber'] ?>" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="bg-white p-3" style="background-image: url(assets/img/logo-op-sm.png); background-repeat: no-repeat; background-position: center; background-size: contain;">
                        <h5 class="card-title text-center">عقد توظيف معلم</h5>
                        <div class="rtl text-end">
                          <?php echo $value['Text'] ?>
                        </div>
                        <hr>
                        <div class="d-flex align-items-start">
                          <div class="text-center m-auto mt-0 overflow-hidden w-50" >
                            <p class="fs-6 fw-bold"> توقيع الطرف الأول "الأكاديمية"</p>
                            <img  src = "assets/img/Signature.png" class="w-50">
                          </div>
                          <div class="text-center m-auto mt-0 overflow-hidden w-50" >
                            <p class="fs-6 fw-bold"> توقيع الطرف الثاني "المعلم"</p>
                            <img id="Contractimg" src = "assets/img/Contracts/<?php echo $value['IDNumber'] ?>.png" class="w-50">
                          </div>
                        </div>
                        <hr>
                        <p class="fw-bold rtl"> يعد توقيعك على هذا العقد اقرار منك انك توافق على كل بنود العقد وعلى شروط وأحكام الأكاديمية. </p>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
              <!-- ------------------------------------------------ -->
            <?php } ?>
            <?php } ?>
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
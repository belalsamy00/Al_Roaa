<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if ( !isset($_GET['classID']) AND !isset($_GET['Code']) AND !isset($_GET['Edite']) AND !isset($_GET['Delete'])AND !isset($_GET['Deleteclass'])AND !isset($_GET['Updeted'])AND !isset($_GET['UpdetedTeam']) AND !isset($_GET['Deleted'])) {  header('Location: index');  exit;  } 
if (!isset($_SESSION['Admin']) AND !isset($_SESSION['Suber_Admin']) AND !isset($_SESSION['manager']) AND !isset($_SESSION['Supervising_manager']) AND !isset($_SESSION['supervisor'])) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ; 

if (isset($_GET['Start']) AND isset($_GET['End'])) {
  $this_month = $_GET['Start'];
  $Next_month = $_GET['End'];
}else {
  $this_month = date("Y-m-01");
  $Next_month = date("Y-m-d");
}

  if (isset($_GET['Code'])) {
    $GETCode = $_GET['Code'];
    if ($GETCode =="All") {
      $evaluation_user_stmt = $con->prepare("SELECT * FROM EvaluationTeam WHERE `Date`>=? AND `Date`<=? ORDER BY `Date`  ASC");
      $evaluation_user_stmt->execute(array($this_month,$Next_month));
      $evaluation_user_fetch = $evaluation_user_stmt->fetchAll();
    }else {
      $evaluation_user_stmt = $con->prepare("SELECT * FROM EvaluationTeam WHERE Code=? AND `Date`>=? AND `Date`<=? ORDER BY `Date`  ASC");
      $evaluation_user_stmt->execute(array($GETCode ,$this_month,$Next_month));
      $evaluation_user_fetch = $evaluation_user_stmt->fetchAll();
    }
  }else {
    $GETCode = "";
  }

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
    
    <section class="section">
      <div class="row">
        <?php if (isset($_GET['Delete'])) { 
          $ID = filter_var($_GET["Delete"], FILTER_UNSAFE_RAW );

          $stmt = $con->prepare("DELETE FROM EvaluationTeam WHERE ID= ? ");
          $stmt->execute(array($ID));
          $xcount = $stmt->rowCount();

          ?> 
          <div class="col-lg-12 ">
          <div class="card">
          <div class="card-body w-50 m-auto">
          <h5 class="card-title text-center"> تعديل تقرير </h5> 
          <?php
          if ($xcount > 0  ) {
          ?>
          <div class="text-center mt-2 alert alert-success" role="alert">
          تم حذف التقرير  بنجاح
          </div>
          <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button> 
          <a href="index"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2">  الصفحة الرئيسئة</a>
          <?php  } else { ?>
          <div class="text-center mt-2 alert alert-danger" role="alert">
          لم يتم حذف  التقرير هناك مشكلة ما!
          </div>
          <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button> 
          <a href="index"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2">  الصفحة الرئيسئة</a>
          <?php
          }
          ?>
          </div>
          </div>
          </div> 
        <?php }elseif (isset($_GET['Deleteclass'])) { 
          $ID = filter_var($_GET["Deleteclass"], FILTER_UNSAFE_RAW );

          $stmt = $con->prepare("DELETE FROM evaluation WHERE ID= ? ");
          $stmt->execute(array($ID));
          $xcount = $stmt->rowCount();

          ?> 
          <div class="col-lg-12 ">
          <div class="card">
          <div class="card-body w-50 m-auto">
          <h5 class="card-title text-center"> تعديل تقرير </h5> 
          <?php
          if ($xcount > 0  ) {
          ?>
          <div class="text-center mt-2 alert alert-success" role="alert">
          تم حذف التقرير  بنجاح
          </div>
          <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button> 
          <a href="index"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2">  الصفحة الرئيسئة</a>
          <?php  } else { ?>
          <div class="text-center mt-2 alert alert-danger" role="alert">
          لم يتم حذف  التقرير هناك مشكلة ما!
          </div>
          <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button> 
          <a href="index"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2">  الصفحة الرئيسئة</a>
          <?php
          }
          ?>
          </div>
          </div>
          </div> 
        <?php }elseif (isset($_GET['Edite'])) { 
          if (isset($_GET['classID'])) {
            $evaluation_user_stmt = $con->prepare("SELECT * FROM evaluation WHERE ID=? ");
            $evaluation_user_stmt->execute(array($_GET['Edite']));
            $evaluation_user_fetch = $evaluation_user_stmt->fetch();
            $date = 'hidden';
            $action = 'Updeted';
          }else {
            $evaluation_user_stmt = $con->prepare("SELECT * FROM EvaluationTeam WHERE ID=? ");
            $evaluation_user_stmt->execute(array($_GET['Edite']));
            $evaluation_user_fetch = $evaluation_user_stmt->fetch();
            $date = 'date';
            $action = 'UpdetedTeam';
          }
          ?>
          <div class="col-lg-12 ">
            <div class="card">
            <div class="card-body m-auto">
            <h5 class="card-title text-center"> تعديل تقرير </h5> 

            <form class="mt-2" method="POST" action="Evaluation?<?php echo $action ?>">

            <select      id="code" name="code" class="form-control form-select form-select-lg  mt-2" aria-label=".form-select-lg example"required='true'>
            <option    selected disabled value="<?php echo $evaluation_user_fetch['Code'] ?>"><?php echo $evaluation_user_fetch['Code']?></option>
            </select>

            <input class="form-control form-control-lg text-center mt-2" type="hidden" name="ID" value="<?php echo $evaluation_user_fetch['ID']?>" required='true'>

            <input class="form-control form-control-lg text-center mt-2" type="<?php echo $date ;?>" name="date" value="<?php echo $evaluation_user_fetch['Date']?>" required='true'>


            <input id="form-code" class="form-control form-control-lg text-center  mt-2" type="text" value="<?php echo $evaluation_user_fetch['Dis']?>" name="dis" placeholder=" الوصف" required='true'>

            <div class="input-group mb-3">
            <label class="input-group-text mt-2" for="number">الدرجات</label>
            <button class="btn btn-outline-primary  fs-6 fw-bold  mt-2 " type="button" id="button-addon1" onclick="incrementValue()">+</button>
            <input readonly class="form-control form-control-lg text-center  mt-2" name="mark" type="text" id="number" value="<?php echo $evaluation_user_fetch['Mark']?>" aria-label="Example text with button addon" aria-describedby="button-addon1 button-addon2">
            <button class="btn btn-outline-primary  fs-6 fw-bold  mt-2 " type="button" id="button-addon2" onclick="decrementValue()">-</button>
            </div>

            <div class="form-check form-check-inline mt-2">
            <input class="form-check-input" type="radio" name="status" id="inlineRadio1" value="+" <?php if($evaluation_user_fetch['Status'] == "+"){echo "checked";}?> >
            <label class="form-check-label" for="inlineRadio1">اضافة</label>
            </div>
            <div class="form-check form-check-inline mt-2">
            <input class="form-check-input" type="radio" name="status" id="inlineRadio2" value="-" <?php if($evaluation_user_fetch['Status'] == "-"){echo "checked";}?>>
            <label class="form-check-label" for="inlineRadio2">خصم</label>
            </div>

            <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 " type="submit" name="Get-All"> تعديل التقرير </button>
            
            </form>
            <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button> 
            </div>
            </div>
          </div> 
        <?php }elseif(isset($_GET['Updeted'])) { 
          $ID = filter_var($_POST["ID"], FILTER_UNSAFE_RAW );
          $Date = filter_var($_POST["date"], FILTER_UNSAFE_RAW );
          $Dis = filter_var($_POST["dis"], FILTER_UNSAFE_RAW );
          $Mark = filter_var($_POST["mark"], FILTER_UNSAFE_RAW );
          $Status = filter_var($_POST["status"], FILTER_UNSAFE_RAW );

          $stmt = $con->prepare("UPDATE evaluation SET `Date`=?   , Dis=? , `Mark`=?, `Status`=?  WHERE ID =? ");
          $stmt->execute(array( $Date , $Dis  , $Mark, $Status , $ID  ));
          $xcount = $stmt->rowCount();

          ?> 
          <div class="col-lg-12 ">
          <div class="card">
          <div class="card-body w-50 m-auto">
          <h5 class="card-title text-center"> تعديل تقرير </h5> 
          <?php
          if ($xcount > 0  ) {
          ?>
          <div class="text-center mt-2 alert alert-success" role="alert">
          تم تعديل التقرير بنجاح
          </div>
          <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button> 
          <a href="index"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2">  الصفحة الرئيسئة</a>
          <?php  } else { ?>
          <div class="text-center mt-2 alert alert-danger" role="alert">
          لم يتم تعديل التقرير هناك مشكلة ما!
          </div>
          <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button> 
          <a href="index"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2">  الصفحة الرئيسئة</a>
          <?php
          }
          ?>
          </div>
          </div>
          </div> 
        <?php }elseif(isset($_GET['UpdetedTeam'])) { 
          $ID = filter_var($_POST["ID"], FILTER_UNSAFE_RAW );
          $Date = filter_var($_POST["date"], FILTER_UNSAFE_RAW );
          $Dis = filter_var($_POST["dis"], FILTER_UNSAFE_RAW );
          $Mark = filter_var($_POST["mark"], FILTER_UNSAFE_RAW );
          $Status = filter_var($_POST["status"], FILTER_UNSAFE_RAW );

          $stmt = $con->prepare("UPDATE EvaluationTeam SET `Date`=?   , Dis=? , `Mark`=?, `Status`=?  WHERE ID =? ");
          $stmt->execute(array( $Date , $Dis  , $Mark, $Status , $ID  ));
          $xcount = $stmt->rowCount();

          ?> 
          <div class="col-lg-12 ">
          <div class="card">
          <div class="card-body w-50 m-auto">
          <h5 class="card-title text-center"> تعديل تقرير </h5> 
          <?php
          if ($xcount > 0  ) {
          ?>
          <div class="text-center mt-2 alert alert-success" role="alert">
          تم تعديل التقرير بنجاح
          </div>
          <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button> 
          <a href="index"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2">  الصفحة الرئيسئة</a>
          <?php  } else { ?>
          <div class="text-center mt-2 alert alert-danger" role="alert">
          لم يتم تعديل التقرير هناك مشكلة ما!
          </div>
          <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button> 
          <a href="index"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2">  الصفحة الرئيسئة</a>
          <?php
          }
          ?>
          </div>
          </div>
          </div> 
        <?php }elseif (isset($_GET['classID'])) {
                      $classID = $_GET['classID']."#".$_GET['date'];
                      $Edite_stmt = $con->prepare("SELECT * FROM evaluation WHERE classID=? ");
                      $Edite_stmt->execute(array($classID));
                      $Edite_stmt_fetch = $Edite_stmt->fetchAll();
                      ?>
                <div class="col-sm-12">
                  <div class="card">
                  <h5 class="card-title   text-center"> التقيمات | <?php echo $classID ?> </h5>
                  <div class="card-body overflow-auto rtl text-center">
                  <table class="table table-borderless text-center  ">
                  <thead >
                  <tr>
                  <th  scope="col">التاريخ</th>
                  <th scope="col">الراسل</th>
                  <th class="d-ph-none"  scope="col">الوصف</th>
                  <th scope="col">النوع</th>
                  <?php if (isset($_SESSION['manager']) OR isset($_SESSION['Supervising_manager'])) {  ?>
                  <th scope="col" colspan="2">تخصيص</th>
                  <?php } ?>
                  </tr>
                  </thead>
                  <tbody>
          
                  <?php 
                  if (empty($Edite_stmt_fetch)) {
                    ?> 
                    <td class="fw-bold fs-5 " colspan="5"> لا توجد تقيمات مسجلة </td> 
                    <?php
                    }
                  foreach ($Edite_stmt_fetch as $key => $value) { ?>
                  <tr>
                  <td class="fs-6 fw-bold"> <?php echo $value['Date'] ?> </td>
                  <td><?php echo " أ ".$value['Who'] ?></td>
                  <td class="d-ph-none"><?php echo $value['Dis'] ?></td>
                  <td><span class="
                  <?php
                  if ($value['type'] == "1") {
                    echo "fs-6 fw-bold";
                  }else {
                    if ($value['Status'] == "-") { echo "badge bg-danger"; }else { echo "badge bg-success"; }
                  }
                  ?>
                  ">
                  <?php
                  if ($value['type'] == "1") {
                    echo "الأداء - "." ".$value['performance'];
                  }else {
                    if ($value['Status'] == "-") { echo "خصم"." ".$value['Mark']." "."نقط"; }else { echo "اضافة"." ".$value['Mark']." "."نقط" ;} 
                  }
                  ?>
                  </span></td>
                  <?php if (isset($_SESSION['manager']) OR isset($_SESSION['Supervising_manager'])) {  ?>
                    <td><?php if ($value['type'] == "2") { ?><p><a class="btn btn-outline  fs-6 fw-bold mt-2" href="Evaluation?Edite=<?php echo $value['ID'] ?>&classID"><lord-icon src="https://cdn.lordicon.com/hbigeisx.json" trigger="loop-on-hover" colors="primary:#121331"style="width:25px;height:25px"></lord-icon></i></a></p><?php } ?></td>
          
                    <td><p><a class="btn btn-outline  fs-6 fw-bold mt-2"  onclick="return confirm('هل انت متأكد من الحذف ؟');" href="Evaluation?Deleteclass=<?php echo $value['ID'] ?>"><lord-icon src="https://cdn.lordicon.com/jmkrnisz.json"    trigger="loop-on-hover"  colors="primary:#121331"style="width:25px;height:25px"></lord-icon></a></p></td>
                  <?php } ?>
          
          
                  </tr>
                  <?php } ?>
                  </tbody>
                  </table>
                  </div>
          
                  </div>
                  </div>
                </div>
              <?php
                    
        }else { 

          ?>
          <div class="col-lg-12">
          <div class="card">
              <div class="card-body ">
              <h5 class="card-title text-center">   </h5> 
                  <form id="form-2"class="mt-2 w-50 m-auto" method="GET" action="">
                  <input type="hidden" class="form-control" value="<?php echo $GETCode ;?>"  name="Code" aria-label="Start" aria-describedby="basic-addon1">

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
                <div class="card">
                <h5 class="card-title   text-center"> التقيمات | <?php echo $GETCode  ?> </h5>
                <div class="card-body overflow-auto rtl text-center">
                <table class="table table-borderless text-center  ">
                <thead >
                <tr>
                <th  scope="col">التاريخ</th>
                <th scope="col">الراسل</th>
                <th class="d-none d-md-block " scope="col">التقرير</th>
                <th class="d-ph-none"  scope="col">الوصف</th>
                <th scope="col">النوع</th>
                <?php if (isset($_SESSION['manager']) OR isset($_SESSION['Supervising_manager']) ) {  ?>
                <th scope="col" colspan="2">تخصيص</th>
                <?php } ?>
                </tr>
                </thead>
                <tbody>

                <?php 
                foreach ($evaluation_user_fetch as $key => $value) { ?>
                <tr>
                <td class="fs-6 fw-bold"> <?php echo $value['Date'] ?> </td>
                <td><?php echo  $value['Who'] ?></td>
                <td class="d-none d-md-block "><?php if ($value['report'] == 0) {
                  echo "-"; 
                }else {
                  echo $value['report'] ;
                } ?></td>
                <td class="d-ph-none"><?php echo $value['Dis'] ?></td>
                <td><span class="
                <?php
                if ($value['type'] == "1") {
                  echo "fs-6 fw-bold";
                }else {
                  if ($value['Status'] == "-") { echo "badge bg-danger"; }else { echo "badge bg-success"; }
                }
                ?>
                ">
                <?php
                if ($value['type'] == "1") {
                  echo "التقييم اليومى"." ".$value['performance'];
                }else {
                  if ($value['Status'] == "-") { echo "خصم"." ".$value['Mark']." "."نقط"; }else { echo "اضافة"." ".$value['Mark']." "."نقط" ;} 
                }
                ?>
                </span></td>
                <?php if (isset($_SESSION['manager']) OR isset($_SESSION['Supervising_manager'])) {  ?>
                  <td><?php if ($value['type'] == "2") { ?><p><a class="btn btn-outline  fs-6 fw-bold mt-2" href="Evaluation?Edite=<?php echo $value['ID'] ?>"><lord-icon src="https://cdn.lordicon.com/hbigeisx.json" trigger="loop-on-hover" colors="primary:#121331"style="width:25px;height:25px"></lord-icon></i></a></p><?php } ?></td>

                  <td><p><a class="btn btn-outline  fs-6 fw-bold mt-2"  onclick="return confirm('هل انت متأكد من الحذف ؟');" href="Evaluation?Delete=<?php echo $value['ID'] ?>"><lord-icon src="https://cdn.lordicon.com/jmkrnisz.json"    trigger="loop-on-hover"  colors="primary:#121331"style="width:25px;height:25px"></lord-icon></a></p></td>
                <?php } ?>


                </tr>
                <?php } ?>
                </tbody>
                </table>
                </div>

                </div>
                </div>
              </div>

        <?php } ?>
    </section>
  </div>


</main><!-- End #main -->
<script>
  function submit_form(textarea,form,back,Do,Cancel,spinner) {

  }
  function incrementValue()
  {
  var value = parseInt(document.getElementById('number').value, 10);
  value = isNaN(value) ? 0 : value;
  value++;
  document.getElementById('number').value = value + 4;
  }
  function decrementValue()
  {
  var value = parseInt(document.getElementById('number').value, 10);
  if (value >= 5) {
  value = isNaN(value) ? 0 : value;
  value--;
  document.getElementById('number').value = value - 4;
  }

  }
  </script>
<?php include "assets/tem/footer.php" ?>
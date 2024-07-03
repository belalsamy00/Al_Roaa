<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if ( !isset($_GET['Code'])) {  header('Location: index');  exit;  } 
if (!isset($_SESSION['Admin']) AND !isset($_SESSION['Suber_Admin']) AND !isset($_SESSION['manager']) AND !isset($_SESSION['Supervising_manager']) AND !isset($_SESSION['supervisor'])) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ; 
if (isset($_GET['Start']) AND isset($_GET['End'])) {
  $this_month = $_GET['Start'];
  $Next_month = $_GET['End'];
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
  $GETCode = $_GET['Code'];
  $evaluation_user_stmt = $con->prepare("SELECT * FROM evaluation WHERE Code=? AND `Date`>=? AND `Date`<=? AND classID != ?  AND `Approval` =? ORDER BY `Date`  ASC");
  $evaluation_user_stmt->execute(array($GETCode,$this_month,$Next_month,0,0));
  $evaluation_user_fetch = $evaluation_user_stmt->fetchAll();


  $classID = array();
  $classEvaluation = array();
  $Evaluation = array();
  foreach ($evaluation_user_fetch as $key =>   $value) {
  if (!in_array($value['classID'], $classID))
  {
      $classID[$value['classID']][1] = $value['Code']; 
      $classID[$value['classID']][2] = $value['classID']; 
  }
  }

  foreach ($classID as $key => $classes) {
  $classes_stmt = $con->prepare("SELECT * FROM evaluation WHERE Code=? AND `classID`=? ");
  $classes_stmt->execute(array($classes[1] ,$classes[2]));
  $classes_fetch = $classes_stmt->fetchAll();


  $s_code_arry = explode('#',$classes[2]);

  $s_code_stmt = $con->prepare("SELECT * FROM class WHERE ID=?  ");
  $s_code_stmt->execute(array($s_code_arry[0]));
  $s_code_stmt_fetch = $s_code_stmt->fetch();


  $s_name_explode = explode('-',$s_code_stmt_fetch['StudentName']);
  if ($s_code_stmt_fetch['type'] == 2 ) {
      $sStudentName = $s_name_explode[0];
  }else {
      $Name = $s_name_explode[1]; 
      $find = array("oneTimeClass","trail","Rescheduled","-");
      $replace = array(" ","تجريبية","تعويضية"," ");
      $string = str_replace($find,$replace,$s_code_stmt_fetch['StudentName']);
      $sStudentName = preg_replace('/[0-9]+/', '', $string);
  }

  $s_code_explode = explode(' ',$s_code_stmt_fetch['Student']);
  $s_name_explode = explode(' ',$s_code_stmt_fetch['StudentName']);

  $plus_user_stmt = $con->prepare("SELECT  SUM(Mark) FROM evaluation WHERE Code=? AND `Status`=?  AND `classID`=? ");
  $plus_user_stmt->execute(array($classes[1],"+",$classes[2]));
  $plus_user = $plus_user_stmt->fetch();

  $minus_user_stmt = $con->prepare("SELECT  SUM(Mark) FROM evaluation WHERE Code=? AND `Status`=? AND `classID`=?");
  $minus_user_stmt->execute(array($classes[1],"-",$classes[2]));
  $minus_user = $minus_user_stmt->fetch();

  $Evaluation[$key]['id'] = $s_code_arry[0] ;
  $Evaluation[$key]['Code'] = $classes[1] ;
  $Evaluation[$key]['date'] = $s_code_arry[1] ;
  $Evaluation[$key]['plus'] = $plus_user['SUM(Mark)'] ;
  $Evaluation[$key]['minus'] = $minus_user['SUM(Mark)'];
  $Evaluation[$key]['s_code'] = $s_code_explode[0] ;
  $Evaluation[$key]['s_name'] = $sStudentName;

  foreach ($classes_fetch as $Evaluation_key =>  $fetch) {
    if ($fetch['type']==1)
    {
      $Evaluation[$key]['performance'] = $fetch['performance']; 
      $Evaluation[$key]['report'] = $fetch['report']; 
      $Evaluation[$key]['Who'] = $fetch['Who'];
      $Evaluation[$key]['Date'] = $fetch['Date'];
      
    }else {
      $Evaluation[$key]['text'][$Evaluation_key] = $fetch['text'];
      $Evaluation[$key]['Dis'][$Evaluation_key] = $fetch['Dis'];
      $Evaluation[$key]['Mark'][$Evaluation_key] = $fetch['Mark'];
      $Evaluation[$key]['Status'][$Evaluation_key] = $fetch['Status'];
    }
  }
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
      <div class="col-lg-12">
              <div class="card">
                  <div class="card-body col-12 col-lg-6 m-auto">
                  <h5 class="card-title text-center">   </h5> 
                      <form id="form-2"class="mt-2 w-100 m-auto" method="GET" action="">
                      <input type="hidden" class="form-control" value="1"  name="trust" aria-label="Start" aria-describedby="basic-addon1">
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
              <table class="table table-borderless text-center ">
              <thead >
              <tr>
              <th  scope="col">التاريخ</th>
              <th  scope="col">المشرف</th>
              <th  scope="col">المعلم</th>
              <th  scope="col">الكود</th>
              <th  scope="col">الاسم</th>
              <th  scope="col">الأداء</th>
              <th  scope="col">البونص</th>
              <th  scope="col">الخصم</th>
              <?php if (isset($_SESSION['manager']) OR isset($_SESSION['Supervising_manager']) ) {  ?>
              <th  scope="col">تخصيص</th>
              <?php } ?>

 
              </tr>
              </thead>
              <tbody>
  
              <?php 
              if (empty($Evaluation)) {
                ?> 
                <td class="fw-bold fs-5 " colspan="11"> لا توجد تقيمات مسجلة </td> 
                <?php
                }
              foreach ($Evaluation as $key => $value) { 
               ?>
              <tr>
              <td> <?php echo $value['Date'] ?> </td>
              <td> <?php echo $value['Who'] ?> </td>
              <td> <?php echo $value['Code'] ?> </td>
              <td> <?php echo $value['s_code'] ?> </td>
              <td> <?php echo $value['s_name'] ?> </td>
              <td> <?php echo $value['performance'] ?> </td>
              <td> <?php echo $value['plus'] ?> </td>
              <td> <?php echo $value['minus'] ?> </td>
              <?php if (isset($_SESSION['manager']) OR isset($_SESSION['Supervising_manager']) ) {  ?>
              <td> <a class="btn btn-outline  fs-6 fw-bold mt-2" href="Evaluation?classID=<?php echo $value['id'] ?>&date=<?php echo $value['date'] ?>"><lord-icon src="https://cdn.lordicon.com/hbigeisx.json" trigger="loop-on-hover" colors="primary:#121331"style="width:25px;height:25px"></lord-icon></i></a> </td>
              <?php } ?>
  
  
              </tr>
              <tr>
                <td colspan="9"> <?php echo $value['report'] ?> <br> <?php
                  if (!isset($value['Dis'])) {
                    echo " لا يوجد خصم او بونص ";
                  }else {
                    for ($i=1; $i < count($value['Dis'])+1 ; $i++) { 
                      if ($value['Status'][$i] == "-") { echo " { <p class= 'badge bg-danger'>"." ( ".$value['Dis'][$i]." ) "."خصم"." ".$value['Mark'][$i]." "."نقطه"." ( ".$value['text'][$i]." )</p> } "; }else{ echo " { <p class= 'badge bg-success'>"." ( ".$value['Dis'][$i]." ) "."اضافة"." ".$value['Mark'][$i]." "."نقطه"." ( ".$value['text'][$i]." )</p> } " ;}
                    }
                  } ?>
                </td>
              </tr>
              <tr>
                <td colspan="9"><hr></td>
              </tr>
              <?php } ?>
              </tbody>
              </table>
              </div>
  
              </div>
              </div>
            </div>
            </div>
          

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
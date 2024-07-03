<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['Admin']) AND !isset($_SESSION['Suber_Admin']) AND !isset($_SESSION['manager']) AND !isset($_SESSION['Supervising_manager']) AND !isset($_SESSION['supervisor'])) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ; 
if (isset($_GET['Start'])) {
  $this_month = $_GET['Start'];
  if (isset($_GET['End'])) {
    $Next_month = $_GET['End'];
  }else {
    $Next_month = $_GET['Start'];
  }
  }else {
    $this_month = date("Y-m-d");
    $Next_month = date("Y-m-d");
  }
  $GETCode = "All";
  $evaluation_user_stmt = $con->prepare("SELECT * FROM evaluation WHERE `Date`>=? AND `Date`<=? AND classID != ? ORDER BY `Who`,`Code`,`Timestamp`  ASC");
  $evaluation_user_stmt->execute(array($this_month,$Next_month,0));
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
  $Evaluation[$key]['classID'] = $classes[2];


  foreach ($classes_fetch as $Evaluation_key =>  $fetch) {
    if ($fetch['type']==1)
    {
      $Evaluation[$key]['Timestamp'] = $fetch['Timestamp']; 
      $Evaluation[$key]['performance'] = $fetch['performance']; 
      $Evaluation[$key]['report'] = $fetch['report']; 
      $Evaluation[$key]['Cancel'] = $fetch['Cancel'];
      $Evaluation[$key]['Who'] = $fetch['Who'];
      $Evaluation[$key]['Date'] = $fetch['Date'];
      $Evaluation[$key]['Approval'] = $fetch['Approval'];
    }else {
      $Evaluation[$key]['text'][$Evaluation_key] = $fetch['text'];
      $Evaluation[$key]['Dis'][$Evaluation_key] = $fetch['Dis'];
      $Evaluation[$key]['Mark'][$Evaluation_key] = $fetch['Mark'];
      $Evaluation[$key]['Status'][$Evaluation_key] = $fetch['Status'];
    }
  }
  }

  $Amr_count = $con->prepare("SELECT * FROM evaluation WHERE Who =? AND `Date`>=? AND `Date`<=? AND classID != ? AND `type`=? AND Cancel = 0 ORDER BY `Date`  ASC");
  $Amr_count->execute(array("عمرو عبدالله",$this_month,$Next_month,0,1));
  $evaluation_Amr_count = $Amr_count->rowCount();

  $Bedo_count = $con->prepare("SELECT * FROM evaluation WHERE Who =? AND `Date`>=? AND `Date`<=? AND classID != ? AND `type`=? AND Cancel = 0 ORDER BY `Date`  ASC");
  $Bedo_count->execute(array("عبدالرحمن",$this_month,$Next_month,0,1));
  $evaluation_Bedo_count = $Bedo_count->rowCount();

  $Ahmed_count = $con->prepare("SELECT * FROM evaluation WHERE Who =? AND `Date`>=? AND `Date`<=? AND classID != ? AND `type`=? AND Cancel = 0 ORDER BY `Date`  ASC");
  $Ahmed_count->execute(array("أحمد منجود",$this_month,$Next_month,0,1));
  $evaluation_Ahmed_count = $Ahmed_count->rowCount();

  $Other_count = $con->prepare("SELECT * FROM evaluation WHERE Who !=? AND Who !=? AND Who !=? AND `Date`>=? AND `Date`<=? AND classID != ? AND `type`=? AND Cancel = 0 ORDER BY `Date`  ASC");
  $Other_count->execute(array("أحمد منجود","عبدالرحمن","عمرو عبدالله",$this_month,$Next_month,0,1));
  $evaluation_Other_count = $Other_count->rowCount();

  $Cancel_count = $con->prepare("SELECT * FROM evaluation WHERE `Date`>=? AND `Date`<=? AND classID != ? AND `type`=? AND Cancel = 1 ORDER BY `Date`  ASC");
  $Cancel_count->execute(array($this_month,$Next_month,0,1));
  $evaluation_Cancel_count = $Cancel_count->rowCount();

  $TotalEvaluation = $evaluation_Amr_count+$evaluation_Bedo_count+$evaluation_Ahmed_count+$evaluation_Other_count+$evaluation_Cancel_count ;


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
          <div class="row">
            <h5 class="card-title text-center"> أحصائيات الفريق  </h5>
            <div class="col-5 col-lg-2 card-body p-0 text-center m-auto">
            <h1 class=" card-title  text-center  rtl"> <?php echo " ( ".$TotalEvaluation." ) " ; ?> الإجمالى </h1>
            </div>
            <div class="col-5 col-lg-2 card-body p-0 text-center m-auto">
            <h1 class=" card-title rtl"> <?php echo " ( ".$evaluation_Cancel_count." ) " ; ?> ملغية </h1>
            </div>
            <div class="col-5 col-lg-2 card-body p-0 text-center m-auto">
            <h1 class=" card-title rtl"> <?php echo " ( ".$evaluation_Other_count." ) " ; ?> مشرف آخر </h1>
            </div>
            <div class="col-5 col-lg-2 card-body p-0 text-center m-auto">
            <h1 class=" card-title rtl"> <?php echo " ( ".$evaluation_Amr_count." ) " ; ?> عمرو </h1>
            </div>
            <div class="col-5 col-lg-2 card-body p-0 text-center m-auto">
            <h1 class=" card-title rtl"> <?php echo " ( ".$evaluation_Bedo_count." ) " ; ?> عبدالرحمن </h1>
            </div>
            <div class="col-5 col-lg-2 card-body p-0 text-center m-auto">
            <h1 class=" card-title rtl"> <?php echo " ( ".$evaluation_Ahmed_count." ) " ; ?> أحمد </h1>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-12">
              <div class="card">
                  <div class="card-body  col-12 col-lg-6 m-auto">
                  <h5 class="card-title text-center">   </h5> 
                      <form id="form-2"class="mt-2 w-100 m-auto" method="GET" action="">
                      <input type="hidden" class="form-control" value="1"  name="trust" aria-label="Start" aria-describedby="basic-addon1">
                      <input type="hidden" class="form-control" value="<?php echo $GETCode ;?>"  name="Code" aria-label="Start" aria-describedby="basic-addon1">
                        <div class="input-group mb-3">
                          <span class="input-group-text" id="basic-addon1">From</span>
                          <input type="date" onchange="this.form.submit()" class="form-control" value="<?php echo $this_month ;?>"  name="Start" aria-label="Start" aria-describedby="basic-addon1">
                        </div>
                      </form>
                  </div>
              </div>
            </div>
            <div class="col-sm-12">
              <div class="card">
              <h5 class="card-title   text-center"> التقيمات | <?php echo $this_month  ?> </h5>
              <div class="card-body overflow-auto  text-center">
              <table class="table table-borderless text-center  rtl">
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
              <th  scope="col">
                <form action="in-to-db?do=Approval_EvaluationAll" method="post" class="ms-2">
                  <button  class=" border-0 bg-transparent fs-6 fw-bold mt-2" type="submit"> إعتماد </button>
                </form>
              </th>
              <?php } ?>

 
              </tr>
              </thead>
              <tbody>
  
              <?php 
              if (empty($Evaluation)) {
                ?> 
                <tr>
                  <td> </td>
                  <td> </td>
                  <td> </td>
                  <td> </td>
                  <td> </td>
                  <td> </td>
                  <td> </td>
                  <td> </td>
                  <td> </td>
                  <td> </td>
                </tr>
                <?php
                }
              foreach ($Evaluation as $key => $value) { 
               ?>
              <tr>
              <td> <?php echo $value['Timestamp'] ?> </td>
              <td> <?php echo $value['Who'] ?> </td>
              <td> <?php echo $value['Code'] ?> </td>
              <td> <?php echo $value['s_code'] ?> </td>
              <td> <?php echo $value['s_name'] ?> </td>
              <td> <?php echo $value['performance'] ?> </td>
              <td> <?php echo $value['plus'] ?> </td>
              <td> <?php echo $value['minus'] ?> </td>
              <?php if (isset($_SESSION['manager']) OR isset($_SESSION['Supervising_manager']) ) {  ?>
              <td> <a class="btn btn-outline  fs-6 fw-bold mt-2" href="Evaluation?classID=<?php echo $value['id'] ?>&date=<?php echo $value['date'] ?>"><lord-icon src="https://cdn.lordicon.com/hbigeisx.json" trigger="loop-on-hover" colors="primary:#121331"style="width:25px;height:25px"></lord-icon></i></a> </td>
              <td>
              <?php
              if ($value['Approval'] == 1) { ?>
              <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                <form action="in-to-db?do=Approval_Evaluation" method="post" class="ms-2">
                  <input type="hidden" value="<?php echo $value['classID'] ?>" name="classID">
                  <button  class="btn btn-outline-primary fs-6 fw-bold mt-2" type="submit"> إعتماد </button>
                </form>
              <?php if ($value['Cancel'] == 0) { ?>
                <form action="in-to-db?do=Cancel_Evaluation" method="post">
                  <input type="hidden" value="<?php echo $value['classID'] ?>" name="classID">
                  <button  class="btn btn-outline-danger fs-6 fw-bold mt-2" type="submit"> إلغاء </button>
                </form>
                <?php }else { ?>
                <form action="in-to-db?do=Cancel_Cancel_Evaluation" method="post">
                  <input type="hidden" value="<?php echo $value['classID'] ?>" name="classID">
                  <button  class="btn btn-outline-danger fs-6 fw-bold mt-2" type="submit"> تراجع عن الإلغاء </button>
                </form>
                <?php } ?>
              </div>

              <?php }else { ?>
                
              <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                <form action="in-to-db?do=Cancel_Approval_Evaluation" method="post" class="ms-2">
                  <input type="hidden" value="<?php echo $value['classID'] ?>" name="classID">
                  <button  class="btn btn-outline-success fs-6 fw-bold mt-2" type="submit"> تراجع عن الأعتماد </button>
                  </form>
              <?php if ($value['Cancel'] == 0) { ?>
                <form action="in-to-db?do=Cancel_Evaluation" method="post">
                  <input type="hidden" value="<?php echo $value['classID'] ?>" name="classID">
                  <button  class="btn btn-outline-danger fs-6 fw-bold mt-2" type="submit"> إلغاء </button>
                </form>
                <?php }else { ?>
                <form action="in-to-db?do=Cancel_Cancel_Evaluation" method="post">
                  <input type="hidden" value="<?php echo $value['classID'] ?>" name="classID">
                  <button  class="btn btn-outline-danger fs-6 fw-bold mt-2" type="submit"> تراجع عن الإلغاء </button>
                </form>
                <?php } ?>
              </div>
                <?php
                } ?>

             </td>
              <?php } ?>
  
  
              </tr>
              <tr>
              <td colspan="10"> <?php echo $value['report'] ?> <br> <?php
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
              <td colspan="10"><hr> </td>
              </tr>
              <?php } ?>
              </tbody>
              </table>
              </div>
  
              </div>
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
  <?php 
if (!empty($_SESSION['Emessage'])) { ?>
  <script>
    $(document).ready(function() {
        $(".toast").toast('show');
    });
</script>
<?php unset($_SESSION['Emessage']); } ?>
<?php include "assets/tem/footer.php" ?>
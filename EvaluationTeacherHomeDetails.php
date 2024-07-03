<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_GET['Date'])) {  header('Location: index');  exit;  } 
if (!isset($_SESSION['teacher'])) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ; 
$this_month = $_GET['Date'];
$session_code = $_SESSION['teacher'] ;
$evaluation_user_stmt = $con->prepare("SELECT * FROM evaluation WHERE Code=? AND `Date`=?  AND `Approval` =? ");
$evaluation_user_stmt->execute(array($session_code,$this_month,0));
$evaluation_user_fetch = $evaluation_user_stmt->fetchAll();

$classID = array();
$classEvaluation = array();
$Evaluation = array();

  foreach ($evaluation_user_fetch as  $value) {
    if (!in_array($value['classID'], $classID))
    {
        $classID[] = $value['classID']; 
    }
  }

  foreach ($classID as $key => $classes) {
    $classes_stmt = $con->prepare("SELECT * FROM evaluation WHERE Code=? AND `classID`=? ");
    $classes_stmt->execute(array($session_code,$classes));
    $classes_fetch = $classes_stmt->fetchAll();


    $s_code_arry = explode('#',$classes);

    $s_code_stmt = $con->prepare("SELECT * FROM class WHERE ID=?  ");
    $s_code_stmt->execute(array($s_code_arry[0]));
    $s_code_stmt_fetch = $s_code_stmt->fetch();

  
   
        $Name = $s_code_stmt_fetch['StudentName'] ;
        $find = array("oneTimeClass","trail","Rescheduled","-");
        $replace = array(" ","تجريبية","تعويضية"," ");
        $string = str_replace($find,$replace,$Name);
        $sStudentName = preg_replace('/[0-9]+/', '', $string);

    $s_code_explode = explode(' ',$s_code_stmt_fetch['Student']);
    $s_name_explode = explode(' ',$s_code_stmt_fetch['StudentName']);

    $plus_user_stmt = $con->prepare("SELECT  SUM(Mark) FROM evaluation WHERE Code=? AND `Status`=?  AND `classID`=? ");
    $plus_user_stmt->execute(array($session_code,"+",$classes));
    $plus_user = $plus_user_stmt->fetch();

    $minus_user_stmt = $con->prepare("SELECT  SUM(Mark) FROM evaluation WHERE Code=? AND `Status`=? AND `classID`=?");
    $minus_user_stmt->execute(array($session_code,"-",$classes));
    $minus_user = $minus_user_stmt->fetch();

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
        <div class="col-sm-12  m-auto">
          <div class="card">
            <h5 class="card-title   text-center"> التقيمات | <?php echo $session_code ?> </h5>
            <div class="card-body overflow-auto  rtl text-center">
              <div id="content text-center">
                <table    class="table table-borderless text-center  ">
                  <thead >
                    <tr>
                    <th  scope="col">التاريخ</th>
                    <th  scope="col">الكود</th>
                    <th  scope="col">الاسم</th>
                    <th  scope="col">الأداء</th>
                    <th  scope="col">البونص</th>
                    <th  scope="col">الخصم</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                    if (empty($Evaluation)) { ?> 
                    <tr><td class="fw-bold fs-5 " colspan="7"> لا توجد تقيمات مسجلة </td></tr>
                     
                    <?php
                    }
                    foreach ($Evaluation as $key => $value) { ?>
                    <tr>
                      <td> <?php echo $value['Date'] ?> </td>

                      <td> <?php echo $value['s_code'] ?> </td>

                      <td> <?php echo $value['s_name'] ?> </td>

                      <td> <?php echo $value['performance'] ?> </td>

                      <td> <?php echo $value['plus'] ?> </td>

                      <td> <?php echo $value['minus'] ?> </td>

                    </tr>
                    </tr>
                    <tr>
                    <td colspan="6"> <?php echo $value['report'] ?> <br> <?php
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
                      <td colspan="6"><hr></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
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
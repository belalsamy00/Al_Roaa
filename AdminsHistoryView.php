<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['CustomerService']) AND !isset($_SESSION['manager']) ) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ; 
if (isset($_GET['Start'])) {
  if ($_GET['Start'] == 1 ) {
    $this_month = date("Y-m-t", strtotime(date("Y-02-01"))) ;
    $Next_month = date("Y-m-t", strtotime(date("Y-02-01"))) ;
  }
  elseif ($_GET['Start'] == 2) {
    $this_month = date("Y-02-01") ;
    $Next_month = date("Y-m-t", strtotime(date("Y-02-01"))) ;
  }
  elseif ($_GET['Start'] == 3) {
    $this_month = date("Y-03-01") ;
    $Next_month = date("Y-m-t", strtotime(date("Y-03-01"))) ;
  }
  elseif ($_GET['Start'] == 4) {
    $this_month = date("Y-04-01") ;
    $Next_month = date("Y-m-t", strtotime(date("Y-04-01"))) ;
  }
  elseif ($_GET['Start'] == 5) {
    $this_month = date("Y-05-01") ;
    $Next_month = date("Y-m-t", strtotime(date("Y-05-01"))) ;
  }
  elseif ($_GET['Start'] == 6) {
    $this_month = date("Y-06-01") ;
    $Next_month = date("Y-m-t", strtotime(date("Y-06-01"))) ;
  }
  elseif ($_GET['Start'] == 7) {
    $this_month = date("Y-07-01") ;
    $Next_month = date("Y-m-t", strtotime(date("Y-07-01"))) ;
  }
  elseif ($_GET['Start'] == 8) {
    $this_month = date("Y-08-01") ;
    $Next_month = date("Y-m-t", strtotime(date("Y-08-01"))) ;
  }
  elseif ($_GET['Start'] == 9) {
    $this_month = date("Y-09-01") ;
    $Next_month = date("Y-m-t", strtotime(date("Y-09-01"))) ;
  }
  elseif ($_GET['Start'] == 10) {
    $this_month = date("Y-10-01") ;
    $Next_month = date("Y-m-t", strtotime(date("Y-10-01"))) ;
  }
  elseif ($_GET['Start'] == 11) {
    $this_month = date("Y-11-01") ;
    $Next_month = date("Y-m-t", strtotime(date("Y-11-01"))) ;
  }
  elseif ($_GET['Start'] == 12) {
    $this_month = date("Y-12-01") ;
    $Next_month = date("Y-m-t", strtotime(date("Y-12-01"))) ;
  }else {
    $this_month = date("Y-m-01");
    $Next_month = date("Y-m-t", strtotime(date("Y-m-d")));
  }

}else {
  $this_month = date("Y-m-01");
  $Next_month = date("Y-m-t", strtotime(date("Y-m-d")));
}



if (isset($_GET['Code'])) {
  $stmt = "";
  $execute = array($_GET['Code']) ;
}else {
  $stmt = "";
  $execute = array() ;
}

$count_History = $con->prepare("SELECT * FROM history $stmt ");
$count_History->execute($execute);
$count_History_count = $count_History->rowCount();

$max = ceil($count_History_count/1000);
if (isset($_GET['page'])) {
  if ($_GET['page'] > $max) {
    $page = $max ;
  }elseif ($_GET['page'] < 1) {
    $page = 1 ;
  }else {
    $page = $_GET['page'] ;
  }
}else {
  $page = 1 ;
}
$LIMITMIN = $page*1000-999;
$LIMITMAX = $page*1000;

$History = $con->prepare("SELECT * FROM history $stmt  LIMIT $LIMITMIN,$LIMITMAX ");
$History->execute($execute);
$History_count = $History->rowCount();
$HistoryArry = $History->fetchAll();
?>
<main id="main" class="main">
  <div class="container">
    <div class="pagetitle">
      <nav>
        <ol class="breadcrumb">
          <li class="">        
            <button class="btn btn-primary fs-6 fw-bold text-white" onclick="window.location= 'CustomerService' ">
            <i class="bi bi-box-arrow-left"></i>
            <span>الخروج</span>
            </button.><!-- End Dashboard Iamge Icon -->
          </li>
        </ol>
      </nav>
      <h1>Journal Page</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Journal</li>
          <li class="breadcrumb-item active"><?php echo $this_month ?></li>
          <li class="breadcrumb-item active"><?php echo $Next_month ?></li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    
    <section class="section dashboard">
      <div class="row">

        <?php if (!isset($_GET['Code'])) { ?>
          <div class="col-lg-12">
              <div class="card">
                  <div class="card-body col-12 col-lg-6 m-auto">
                    <h5 class="card-title text-center"> <?php echo " From :".$this_month." | "." To :".$Next_month ;?>  </h5> 
                        <form id="form-2"class="mt-2 w-100 m-auto" method="GET" action="">
                        <select  onchange="this.form.submit()" name="Start" class="form-select" aria-label="Default select example">
                          <option <?php if(!isset($_GET['Start']) ) {echo "selected";} ?>> أختر شهر </option>
                          <option <?php if(isset($_GET['Start']) And $_GET['Start'] == "01" ) {echo "selected";} ?> value="01">يناير</option>
                          <option <?php if(isset($_GET['Start']) And $_GET['Start'] == "02" ) {echo "selected";} ?> value="02">فبراير</option>
                          <option <?php if(isset($_GET['Start']) And $_GET['Start'] == "03" ) {echo "selected";} ?> value="03">مارس</option>
                          <option <?php if(isset($_GET['Start']) And $_GET['Start'] == "04" ) {echo "selected";} ?> value="04">ابريل</option>
                          <option <?php if(isset($_GET['Start']) And $_GET['Start'] == "05" ) {echo "selected";} ?> value="05">مايو</option>
                          <option <?php if(isset($_GET['Start']) And $_GET['Start'] == "06" ) {echo "selected";} ?> value="06">يونيو</option>
                          <option <?php if(isset($_GET['Start']) And $_GET['Start'] == "07" ) {echo "selected";} ?> value="07">يوليو</option>
                          <option <?php if(isset($_GET['Start']) And $_GET['Start'] == "08" ) {echo "selected";} ?> value="08">اغسطس</option>
                          <option <?php if(isset($_GET['Start']) And $_GET['Start'] == "09" ) {echo "selected";} ?> value="09">سبتمبر</option>
                          <option <?php if(isset($_GET['Start']) And $_GET['Start'] == "10" ) {echo "selected";} ?> value="10">اكتوبر</option>
                          <option <?php if(isset($_GET['Start']) And $_GET['Start'] == "11" ) {echo "selected";} ?> value="11">نوفمبر</option>
                          <option <?php if(isset($_GET['Start']) And $_GET['Start'] == "12" ) {echo "selected";} ?> value="12">ديسمبر</option>
                        </select>
                          <?php if (isset($_GET['Who'])) { ?>
                            <input type="hidden" name="Who" value="<?php echo $_GET['Who']; ?>">
                          <?php } ?>
                        </form>
                  </div>
              </div>
          </div>
        <?php } ?>

      <div class="col-lg-12">
          <div class="card">
              <div class="card-body col-12 col-lg-6 m-auto">
                  <h5 class="card-title text-center"> عرض بيانات طالب محدد </h5> 
                  <form id="form-2"class="mt-2 w-100 m-auto" method="GET" action="">
                  <div class="input-group">
                    <input type="text" class="form-control" name="Code" placeholder=" اكتب كود الطالب كامل " <?php if (isset($_GET['Code'])) { ?> value="<?php echo $_GET['Code']; ?>" <?php } ?> >
                    <button class="btn btn-outline-primary " type="submit"> عرض </button>
                    <?php if (isset($_GET['Code'])) { ?>
                    <a href="CustomerServiceJournalView" class="btn btn-outline-primary">إلغاء</a>
                    <?php } ?>
                  </div>
                  </form>
              </div>
          </div>
      </div>

      <div class="col-lg-12">
          <div class="card h-100">
            <h5 class="card-title text-center"><?php if (isset($_GET['Code'])) { echo $_GET['Code'] ; }else { echo "showing Rows From (". $LIMITMIN .") To (". $LIMITMAX .") Of (". $count_History_count .") Rows"; } ?>  </h5>
            <div class="card-body  overflow-auto text-center h-100">
              <nav aria-label="Page navigation example">
              <ul class="pagination">
                <li class="page-item <?php if($page == 1){echo "disabled";} ?>">
                  <a class="page-link" href="?page=1" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                  </a>
                </li>
                <li class="page-item  <?php if($page == 1){echo "disabled";} ?>"><a class="page-link" href="<?php echo "?page=".$page-1 ?>">Previous</a></li>
                <?php
                if ($page == 1 OR $page == 2 OR $page == 3 ) {
                  for ($i=1; $i <= 6 AND $i <= $max ; $i++) { 
                   if ($i == 6) { ?>
                   <li class="page-item  <?php if($page == $i){echo "disabled";} ?>"><span class="page-link">...</span></li>
                   <?php }else { ?>
                     <li class="page-item  <?php if($page == $i){echo "disabled";} ?>"><a class="page-link" href="<?php echo "?page=".$i ?>"><?php echo $i ?></a></li>
                   <?php } 
                  }
                }else {
                  for ($i=$page-2; $i <= $page+5 AND $i <= $max ; $i++) { 
                    if ($i == $page+5) { ?>
                    <li class="page-item  <?php if($page == $i){echo "disabled";} ?>"><span class="page-link">...</span></li>
                    <?php }else { ?>
                      <li class="page-item  <?php if($page == $i){echo "disabled";} ?>"><a class="page-link" href="<?php echo "?page=".$i ?>"><?php echo $i ?></a></li>
                    <?php } 
                   }
                }
                ?>
                <li class="page-item  <?php if($page == $max){echo "disabled";} ?>"><a class="page-link" href="<?php echo "?page=".$page+1 ?>">Next</a></li>
                <li class="page-item  <?php if($page == $max){echo "disabled";} ?>">
                  <a class="page-link" href="<?php echo "?page=".$max ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                  </a>
                </li>
              </ul>
              </nav>
            <table id="DataTable" class="table table-bordered text-center rtl">
                <thead>
                <tr>
                <th scope="col">التاريخ</th>
                <th scope="col">الكود</th>
                <th scope="col">المعلم</th>
                <th scope="col">اسم الطالب</th>
                <th scope="col"> المدة </th>
                <th scope="col"> الحالة </th>
                <?php if (isset($_SESSION['manager'])) { ?>
                  <th   class="fw-bold fs-5 text-center" > إجراء </th>
                <?php  } ?>
                </tr>
                </thead>
                <tbody>
                <?php 
                foreach ($HistoryArry as $key => $value) { 
                  if (date("Y-m-d", strtotime($value['date'])) < $this_month ) {
                    unset($HistoryArry[$key]);
                  }
                  if (date("Y-m-d", strtotime($value['date'])) > $Next_month ) {
                    unset($HistoryArry[$key]);
                  }
                $find = array("trail","Rescheduled","-"," ");
                $replace = array("","","","_");
                $string = str_replace($find,$replace,$value['S_name']);
                $Name = preg_replace('/[0-9]+/','', $string);
                if (empty($Name)) {
                  $Name = str_replace(" ","_",$value['S_code']);
                }
                if (empty($Name)) {
                  $Name = str_replace(" ","_",$value['S_code']);
                }
                $stringCode = str_replace(" ","_",$value['S_code']);


                $T_code_stmt = $con->prepare("SELECT `Name` FROM teachers WHERE Code = ? ");
                $T_code_stmt->execute(array($value['T_code']));
                $T_code = $T_code_stmt->fetch();
                $rowCount = $T_code_stmt->rowCount();
                if ($rowCount > 0) {
                  $stringT_code = str_replace(" ","_",$T_code['Name']);
                }else {
                  $stringT_code = 'Old_System';
                }
                if (date("Y_m_d", strtotime($value['date'])) == "1970_01_01") {
                  $date= "Old_System";
                }else {
                  $date = date("Y_m_d", strtotime($value['date']));
                }
                if ($value['Duration'] > 0) {
                  $Duration = $value['Duration'];

                }else {
                  $Duration = 30;
                }
                if ($value['status'] == "") {
                  $status= "Old_System";
                }else {
                  $status = $value['status'];
                }
                ?>
                <tr>
                <td class="fw-bold fs-5"><?php echo $date ;?></td>
                <td class="fw-bold fs-5"><?php echo $value['S_code'] ?></td>
                <td class="fw-bold fs-5"><?php echo $stringT_code ; ?></td>
                <td class="fw-bold fs-5"><?php echo $Name ?></td>
                <td class="fw-bold fs-5"><?php echo $Duration ?></td>
                <td class="fw-bold fs-5"><?php echo $status?></td>
                <?php if (isset($_SESSION['manager'])) { ?>
                  <td class="fw-bold fs-5 text-end">
                    <div class="btn-group ltr">
                    <a  onclick="return confirm('هل انت متأكد من حذف <?php echo $StudentCodeExplode ?> ؟');" <?php echo " href='CustomerServiceJournalApi?Delete=".$value['ID']."'" ?> class="btn btn-danger">حذف</a>
                    <a <?php echo " href='CustomerServiceJournalEdite?Update=".$value['ID']."'" ?> class="btn btn-success">تعديل</a>
                    </div>
                  </td>
                <?php  } ?>
                </tr>
                <?php }
                ?>
                </tbody>
                </table>
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
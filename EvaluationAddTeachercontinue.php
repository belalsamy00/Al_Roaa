<?php  
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['supervisor']) AND !isset($_SESSION['Supervising_manager']) AND !isset($_SESSION['manager'])) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ;
$sessionteacher=["T01","T02","T03","T04","T05","T06","T07","T08","T09","T10","T11","T12","T13","T14","T15","T16","T17","T18",
"T19","T20","T21","T22","T23","T24","T25","T26","T27","T28","T29","T30","T31","T32","T33","T34","T35","T36","T37","T38","T39",
"T40","T41","T42","T43","T44","T45","T46","T47","T48","T49","T50","T51","T52","T53","T54","T55","T56","T57","T58","T59",
"T60","T61","T62","T63","T64","T65","T66","T67","T68","T69","T70","T71","T72","T73","T74","T75","T76","T77","T78","T79",
"T80","T81","T82","T83","T84","T85","T86","T87","T88","T89","T90","T91","T92","T93","T94","T95","T96","T97","T98","	T99	"
];
if (isset($_GET['code']) AND in_array($_GET['code'],$sessionteacher)) { $teacher = filter_var($_GET["code"], FILTER_UNSAFE_RAW );} else { $teacher = 'T41'; }
if (isset($_GET["date"])) { $date_now = date("Y-m-d", strtotime($_GET['date'])) ; }else { $date_now = date("Y-m-d"); }

$stmt = $con->prepare("SELECT * FROM items WHERE trust=1 ");
$stmt->execute(array());
$items = $stmt->fetchAll();

$H_stmt = $con->prepare("SELECT *  FROM history WHERE  `date`=? AND T_code=? ");
$H_stmt->execute(array($date_now,$teacher));
$MeetingsrowCount = $H_stmt->rowCount();
$Meetings=$H_stmt->fetchAll();
?>
<script type="text/javascript">

    var counter = 3;
  $(function(){
    $(".item-copy").on('click', function(){
      var ele = $(this).closest('.item').clone(true);
      $(this).closest('.item').attr("id", 'item' + counter ).after(ele);
      $('#item' + counter).find('select').attr("name", 'item[' + counter + '][id]');
      $('#item' + counter).find('input[type=text]').attr("name", 'item[' + counter + '][dis]');
      counter++;
    })

  })

  $(function(){
    $(".item-delete").on('click', function(){
      if (counter > 3) {
        $(this).closest('.item').remove();
        counter--;
      }
    })

  })


  $(function(){
    $(".item2-copy").on('click', function(){
      var ele = $(this).closest('.item2').clone(true);
      $(this).closest('.item2').attr("id", 'item2' + counter ).after(ele);
      $('#item2' + counter).find('select').attr("name", 'item[' + counter + '][id]');
      $('#item2' + counter).find('input[type=text]').attr("name", 'item[' + counter + '][dis]');
      counter++;
    })

  })

  $(function(){
    $(".item2-delete").on('click', function(){
      if (counter > 3) {
        $(this).closest('.item2').remove();
        counter--;
      }
    })

  })
</script>

<main id="main" class="main">
    <div class="container">
        <div class="pagetitle">
            <h1>الرئيسية</h1>
            <nav>
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index">الرئيسية</a></li>
                <li class="breadcrumb-item active">تقييم الحلقات</li>
                <li class="breadcrumb-item active"> <?php if (!isset($teacher) ) {$teacher = " " ; } echo $teacher  ; ?></li>
                <li class="breadcrumb-item active"> <?php echo $date_now ; ?></li>
                </ol>
            </nav>
        </div>
    
        <section class="section">
            <div id="myGroup" class="row">
            <div class="col-lg-12">
          <div class="card">
              <div class="card-body ">
              <h5 class="card-title text-center">   </h5> 
                  <form id="form-2"class="mt-2 w-50 m-auto" method="GET" action="">
                    <div class="input-group mb-3">
                      <span class="input-group-text" id="basic-addon1">Code</span>
                      <input type="text" class="form-control" value="<?php echo $teacher ;?>"  name="code" aria-label="Code" aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group mb-3">
                      <span class="input-group-text" id="basic-addon1">From</span>
                      <input type="date" class="form-control" value="<?php echo $date_now ;?>"  name="date" aria-label="Start" aria-describedby="basic-addon1">
                    </div>
                    <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 " >عرض </button>
                  </form>
              </div>
          </div>
      </div>
                <?php
                if ($MeetingsrowCount == 0) {
                ?> 
                <div class="form-group row shadow p-3  bg-body rounded" style=" margin: auto;">
                    <div class="alert alert-primary" role="alert"  style=" width: 100%;" >
                        لا توجد حلقات هذا اليوم
                    </div>
                </div>
                <?php
                }
                foreach($Meetings as $Meeting){
                    ?>
                    <div  class="card card-body  col-lg-12 col-sm-12 d-lg-flex-row p-3">    
                        <div class=" col-lg-6"> <p class="card-details"   >   &nbsp; <?php   echo  $Meeting['S_code'] ?> /&nbsp; <?php echo $Meeting['S_name'] ?> /&nbsp; <?php echo "( ".$Meeting['Duration']." "."دقيقة"." )" ?> /&nbsp; <?php echo $Meeting['Timestamp'] ?> </p></div>
                        <div class=" col-lg-12">
                            <div class= "row d-flex align-items-center justify-content-around">
                                <?php
                                  if ($Meeting['status'] == "غياب") {
                                    ?>
                                    <button class="mb-2 btn btn-danger fs-6 fw-bold w-20-sm-100" disabled>
                                    <?php  echo "لقد  سجل المعلم هذة غياب" ;?>
                                    </button>
                                    <?php
                                  }else {
                                    ?>
                                    <button class="mb-2 btn btn-success fs-6 fw-bold w-20-sm-100" disabled>
                                    <?php  echo "لقد  سجل المعلم هذة حضور";?>
                                    </button>
                                    <?php
                                  } 
                                ?>

                            </div >

                        </div >
                    </div>
                <?php } ?> 
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
</main>
<script src="assets/js/action-28-07-2023.js"></script>
<?php include "assets/tem/footer.php" ;?>
<?php 
if (!empty($_SESSION['Emessage'])) { ?>
  <script>
    $(document).ready(function() {
        $(".toast").toast('show');
    });
</script>
<?php unset($_SESSION['Emessage']); } ?>




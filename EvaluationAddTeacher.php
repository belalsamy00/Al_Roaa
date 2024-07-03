<?php  
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['supervisor']) AND !isset($_SESSION['Supervising_manager']) AND !isset($_SESSION['manager'])) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ;
include "HomeApi.php" ;
if (isset($_GET["date"])) {
    $classIDdate = $_GET["date"] ;
}else {
    $classIDdate = date("Y-m-d") ;
}
$stmt = $con->prepare("SELECT * FROM items WHERE trust=1 ");
$stmt->execute(array());
$items = $stmt->fetchAll();

$users_stmt = $con->prepare("SELECT * FROM users WHERE trust = 3 OR trust = 5  OR trust = 7 ");
$users_stmt->execute(array());
$users_fetch = $users_stmt->fetchAll();

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
                <li class="breadcrumb-item active"> <?php echo $day_now ; ?></li>
                <li class="breadcrumb-item active"> <?php echo $date_now ; ?></li>
                </ol>
            </nav>
        </div>
    
        <section class="section">
            <div id="myGroup" class="row">
        
                <?php
                if (empty($Meetings)) {
                ?> 
                <div class="form-group row shadow p-3  bg-body rounded" style=" margin: auto;">
                    <div class="alert alert-primary" role="alert"  style=" width: 100%;" >
                        لا توجد حلقات هذا اليوم
                    </div>
                </div>
                <?php
                }
                foreach($Meetings as $Meeting){?>
                    <div  class="card card-body  col-lg-12 col-sm-12 d-lg-flex-row p-3">    
                        <div class=" col-lg-6"> <p class="card-details"   >   &nbsp; <?php  if (!isset($_GET['code']) ) { echo $Meeting['Teacher'] ." / " ; } echo  $Meeting['Student'] ?> /&nbsp; <?php echo $Meeting['Name'] ?> /&nbsp; <?php echo "( ".$Meeting['Duration']." "."دقيقة"." )" ?> /&nbsp; <?php echo $Meeting['Time'] ?> </p></div>
                        <div class=" col-lg-12">
                            <div class= "row d-flex align-items-center justify-content-around">
                                <?php
                                if ($Meeting['Nots'] > 0) { ?>
                                    <button class="mb-2 btn btn-success fs-6 fw-bold w-20-sm-100" disabled>
                                    <?php  echo "لقد  سجل المعلم هذة الحلقة" ;?>
                                    </button>
                                    <?php
                                }else { ?>
                                    <button class="mb-2 btn btn-primary fs-6 fw-bold w-20-sm-100" disabled>
                                    <?php  echo "لم   يسجل المعلم هذة الحلقة بعد" ;?>
                                    </button>
                                    <?php
                                } 
                                $H_stmt = $con->prepare("SELECT * FROM evaluation WHERE classID=?    LIMIT 1 ");
                                $H_stmt->execute(array($Meeting['MeetingID']."#".$date_now));
                                $count = $H_stmt->rowCount();
                                $H_nots=$H_stmt->fetch();
                                if ($count > 0) {
                                    if ($H_nots['Approval'] == 1) {
                                        ?>
                                        <button class="mb-2 btn btn-warning fs-6 fw-bold w-20-sm-100" disabled>
                                        <?php  echo "تم تقييم هذة الحلقة من "."أ"." / ".$H_nots['Who']." "."ولم يوافق عليها المسؤل بعد" ;?>
                                        </button>
                                        <?php
                                    }else {
                                        ?>
                                        <button class="mb-2 btn btn-primary fs-6 fw-bold w-20-sm-100" disabled>
                                        <?php  echo "تم تقييم هذة الحلقة من "."أ"." / ".$H_nots['Who']." "." ووافق عليها المسؤل بالفعل" ;?>
                                        </button>
                                        <?php
                                    }
                                }else {
                                    ?>
                                    <button class="mb-2 btn btn-outline-primary fs-6 fw-bold w-20-sm-100" type="button" data-bs-toggle="collapse" data-bs-target="<?php echo "#".$Meeting['Section'] ?>" aria-expanded="false" aria-controls="<?php echo$Meeting['Section'] ?>">تقييم الحصة  </button>
                                    <div class="collapse" id="<?php echo $Meeting['Section'] ?>" data-bs-parent="#myGroup" >
                                        <form class="mt-2" method="POST" action="in-to-db?do=evaluation">

                                            <input class="form-control form-control-lg text-center mt-2" type="hidden" name="date" value="<?php echo $date_now ;  ?>" required='true'>
                                            <input type="hidden" name="classID" value="<?php echo $Meeting['MeetingID']."#".$classIDdate ?>" required='true'>
                                            <input type="hidden" name="code"    value="<?php echo $_GET['code'] ?>" required='true'>
                                            
                                            <?php if (isset($_SESSION['manager']) OR isset($_SESSION['Suber_Admin'])) { ?> 
                                                <div class="input-group mb-3">
                                                    <label class="input-group-text input-group-text-primary mt-2" for="item"> المشرف </label>
                                                    <select id="who" name="who" class="form-control mt-2"  >
                                                        <option selected disabled value=""> اختر مشرف </option>
                                                        <?php foreach ($users_fetch as $users ) { ?>
                                                        <option value="<?php echo $users['Name'] ?>"><?php echo $users['Name']?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            <?php }else { ?>
                                                <input type="hidden" name="who" value="<?php echo $_SESSION['Name'] ?>" required='true'>
                                            <?php } ?>

                                            <div class="input-group mb-3 flex-nowrap flex-row-reverse justify-content-around">
                                            <div class="input-group mb-3 justify-content-center">
                                            <label class="input-group-text " for="excellent">متميز</label>
                                                <div class="input-group-text">
                                                <input class="form-check-input mt-0 " id="excellent" type="radio" name='performance' value="متميز" aria-label="Radio for following text input"required >
                                                </div>
                                            </div>

                                            <div class="input-group mb-3 justify-content-center">
                                            <label class="input-group-text " for="VGood">جيد جدا</label>
                                                <div class="input-group-text">
                                                <input class="form-check-input mt-0 " id="VGood" type="radio" name='performance' value="جيد جدا" aria-label="Radio for following text input" required>
                                                </div>
                                            </div>

                                            <div class="input-group mb-3 justify-content-center">
                                            <label class="input-group-text " for="Good">جيد</label>
                                                <div class="input-group-text">
                                                <input class="form-check-input mt-0 " id="Good" type="radio" name='performance' value="جيد" aria-label="Radio for following text input" required>
                                                </div>
                                            </div>

                                            <div class="input-group mb-3 justify-content-center">
                                            <label class="input-group-text " for="NBad">مقبول</label>
                                                <div class="input-group-text">
                                                <input class="form-check-input mt-0 " id="NBad" type="radio" name='performance' value="مقبول" aria-label="Radio for following text input" required>
                                                </div>
                                            </div>

                                            <div class="input-group mb-3 justify-content-center">
                                            <label class="input-group-text " for="Bad">ضعيف</label>
                                                <div class="input-group-text">
                                                <input class="form-check-input mt-0 " id="Bad" type="radio" name='performance' value="ضعيف" aria-label="Radio for following text input"required >
                                                </div>
                                            </div>
                                            </div>

                                            <div class="input-group">
                                            <span class="input-group-text">التقرير</span>
                                            <textarea class="form-control"  placeholder="اكتب تقرير تفصيلي " name="report"  aria-label="With textarea" required></textarea>
                                            </div>

                                            <div class="input-group justify-content-center mb-3">
                                            <a class="btn btn-success fs-6 fw-bold w-auto border-0 m-2" data-bs-toggle="collapse" href="#item_2" role="button" aria-expanded="false" aria-controls="item_2">هل تريد اضافة بونص</a>

                                            <a class="btn btn-danger fs-6 fw-bold w-auto border-0 m-2" data-bs-toggle="collapse" href="#item_1" role="button" aria-expanded="false" aria-controls="item_1">هل تريد اضافة خصم</a>
                                            </div>

                                            <div class="collapse" id="item_1">

                                            <div class=" item">
                                                <div class="input-group mb-3">
                                                    <label class="input-group-text input-group-text-danger mt-2" for="item">خصم</label>
                                                    <select id="item" name="item[1][id]" class="form-control form-select form-select-lg  mt-2"  >
                                                        <option     selected disabled value="">اختر بند</option>
                                                        <?php foreach ($items as $item ) { 
                                                        if ($item['status'] == "-") { ?>
                                                        <option value="<?php echo $item['ID'] ?>"><?php echo $item['text']?></option>
                                                        <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                                <div class="input-group mb-3">
                                                <label class="input-group-text  mt-2" for="item">الوصف</label>
                                                <input  type="text"id="item" name="item[1][dis]" class="form-control  mt-2"  >
                                                <button class="btn btn-outline-danger  fs-6 fw-bold  mt-2 item-copy" type="button" >بند آخر</button>
                                                <span class="input-group-text mt-2 item-delete " id="basic-addon2"> <lord-icon src="https://cdn.lordicon.com/jmkrnisz.json"    trigger="loop-on-hover"  colors="primary:#121331"style="width:25px;height:25px" ></lord-icon></span>
                                                </div>
                                                
                                            </div>
                                            </div>

                                            <div class="collapse" id="item_2" >
                                            <div class=" item2">
                                                <div class="input-group mb-3">
                                                <label class="input-group-text input-group-text-success mt-2" for="item2">بونص</label>
                                                <select id="item2" name="item[2][id]" class="form-control form-select form-select-lg  mt-2" >
                                                    <option     selected disabled value="">اختر بند</option>
                                                    <?php foreach ($items as $item ) { 
                                                    if ($item['status'] == "+") { ?>
                                                    <option value="<?php echo $item['ID'] ?>"><?php echo $item['text']?></option>
                                                    <?php } ?>
                                                    <?php } ?>
                                                </select>
                                                </div>

                                                <div class="input-group mb-3">
                                                <label class="input-group-text  mt-2" for="item">الوصف</label>
                                                <input class="form-control form-control-lg text-center  mt-2" value="" name="item[2][dis]" type="text"  >
                                                <button class="btn btn-outline-success  fs-6 fw-bold  mt-2 item2-copy" type="button" >بند آخر</button>
                                                <span class="input-group-text mt-2" id="basic-addon2"> <lord-icon src="https://cdn.lordicon.com/jmkrnisz.json"    trigger="loop-on-hover"  colors="primary:#121331"style="width:25px;height:25px" class="item2-delete "></lord-icon></span>
                                                    
                                                </div>
                                                
                                            </div>
                                            </div>
                                            <div id="evaluationspinner<?php echo $Meeting['MeetingID']?>"  class="d-flex justify-content-center" style="display: none!important; ">
                                                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                            </div>
                                            <button id="evaluationbutton<?php echo $Meeting['MeetingID']?>" onclick="submit_evaluationbutton(<?php echo $Meeting['MeetingID']?>)"class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 " type="submit"> ارسال تقرير </button>
                                        </form>
                                    </div>
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
<script>
    function submit_evaluationbutton(MeetingID) {
        document.getElementById('evaluationbutton'+MeetingID).style.setProperty ("display" ,"none", "important");
        document.getElementById('evaluationspinner'+MeetingID).style.setProperty ("display" ,"flex", "important");
    }
</script>
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




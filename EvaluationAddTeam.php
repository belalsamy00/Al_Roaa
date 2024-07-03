<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if ( !isset($_GET['code'])) {  header('Location: index');  exit;  } 
if (!isset($_SESSION['Admin']) AND !isset($_SESSION['Suber_Admin']) AND !isset($_SESSION['manager']) AND !isset($_SESSION['Supervising_manager']) AND !isset($_SESSION['supervisor'])) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ; 
$stmt = $con->prepare
("SELECT trust FROM users WHERE Code=?  LIMIT 1");
$stmt->execute(array($_GET['code']));
$row = $stmt->fetch();

if ($_GET['code'] == "AD2" OR $_GET['code'] == "Test-06") {
  $stmt = "WHERE trust=? OR  trust=?" ;
  $execute = array(8,6) ;
}elseif ($_GET['code'] == "Belal") {
  $stmt = "WHERE trust=? " ;
  $execute = array(8) ;
}elseif ($_GET['code'] == "Amr" OR $_GET['code'] == "a.mangod") {
  $stmt = "WHERE trust=? OR  trust=?" ;
  $execute = array(3,5) ;
}else{
  $stmt = "WHERE trust=? " ;
  $execute = array($row['trust']) ;
}

$stmt = $con->prepare("SELECT * FROM items $stmt ");
$stmt->execute($execute);
$items = $stmt->fetchAll();

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
      <h1>Add Evaluation Page</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Add Evaluation</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    
    <section class="section">
      <div class="row">
      <div class="col-lg-8 m-auto">

      <div class="card">
      <div class="card-body">
      <h5 class="card-title text-center"> التقارير </h5> 
      <form class="mt-2" method="POST" action="in-to-db?do=EvaluationTeam">

      <input class="form-control form-control-lg text-center mt-2" type="<?php if (isset($_SESSION['manager'])) { echo "date" ;}else {echo "hidden"  ;}  ?>" name="date" value="<?php if (date('H:i:s') > '11:59:59' AND date('H:i:s') < '02:59:59' ) { echo date("Y-m-d",strtotime("-1 day"));}else {echo date("Y-m-d");}  ?>" required='true'>

      <input class="form-control form-control-lg text-center mt-2" type="hidden" name="who" value="<?php echo $_SESSION['Name'] ?>" required='true'>

      <div class="input-group mb-3">
      <label class="input-group-text mt-2" for="code">الكود</label>
      <input readonly type="text" id="code" name="code" class="form-control  form-lg  mt-2" value="<?php echo $_GET['code'] ?>" required='true'>
      </div>


      <div class="input-group mb-3 flex-nowrap flex-row-reverse justify-content-around">
        <div class="input-group mb-3 justify-content-center">
        <label class="input-group-text " for="excellent">متميز</label>
          <div class="input-group-text">
            <input class="form-check-input mt-0 " id="excellent" type="radio" name='performance' value="متميز" aria-label="Radio for following text input" >
          </div>
        </div>

        <div class="input-group mb-3 justify-content-center">
        <label class="input-group-text " for="VGood">جيد جدا</label>
          <div class="input-group-text">
            <input class="form-check-input mt-0 " id="VGood" type="radio" name='performance' value="جيد جدا" aria-label="Radio for following text input" >
          </div>
        </div>

        <div class="input-group mb-3 justify-content-center">
        <label class="input-group-text " for="Good">جيد</label>
          <div class="input-group-text">
            <input class="form-check-input mt-0 " id="Good" type="radio" name='performance' value="جيد" aria-label="Radio for following text input" >
          </div>
        </div>

        <div class="input-group mb-3 justify-content-center">
        <label class="input-group-text " for="NBad">مقبول</label>
          <div class="input-group-text">
            <input class="form-check-input mt-0 " id="NBad" type="radio" name='performance' value="مقبول" aria-label="Radio for following text input" >
          </div>
        </div>

        <div class="input-group mb-3 justify-content-center">
        <label class="input-group-text " for="Bad">ضعيف</label>
          <div class="input-group-text">
            <input class="form-check-input mt-0 " id="Bad" type="radio" name='performance' value="ضعيف" aria-label="Radio for following text input" >
          </div>
        </div>
      </div>

      <div class="input-group">
        <span class="input-group-text">التقرير</span>
        <textarea class="form-control"  placeholder="اكتب تقرير تفصيلي " name="report"  aria-label="With textarea" ></textarea>
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
            <input  type="text"id="item" name="item[1][dis]" class="form-control form-select form-select-lg  mt-2"  >
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


      <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 " type="submit"> ارسال تقرير </button>
      </form>
      </div>
      </div>

      </div>
    
      </div>
    </section>
  </div>


</main><!-- End #main -->
<?php include "assets/tem/footer.php" ?>
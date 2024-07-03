<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['CustomerService']) AND !isset($_SESSION['manager']) AND !isset($_SESSION['CustomerServiceManager']) ) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ; 
require_once "functions/CS-functions.php";
if (isset($_GET['Who']) AND in_array($_GET['Who'],array('AbdelRahman','Belal','Hamza','Ramadan','Bedo','All'))) {
  $Who = $_GET['Who'];
}else {
if (isset($_SESSION['Who']) AND in_array($_SESSION['Who'],array('AbdelRahman','Belal','Hamza','Ramadan','Bedo'))) {
  $Who = $_SESSION['Who'];
}else {
  $Who = "All";
}
}

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
    </div><!-- End Page Title -->
    
    <section class="section dashboard">
      <div class="row" id="myGroup">
      <?php if (isset($_SESSION['manager']) OR isset($_SESSION['CustomerServiceManager'])) { ?>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body col-12 col-lg-6 m-auto">
                    <h5 class="card-title text-center"> </h5> 
                    <form id="form-2"class="mt-2 w-100 m-auto" method="GET" action="">
                    <select onchange="this.form.submit()" name="Who" class="form-select" aria-label="Default select example">
                      <option <?php if($Who == "Hamza" )      {echo "selected";} ?> value="Hamza">حمزة</option>
                      <option <?php if($Who == "Belal" )      {echo "selected";} ?> value="Belal">بلال</option>
                      <option <?php if($Who == "Ramadan" )    {echo "selected";} ?> value="Ramadan">رمضان</option>
                      <option <?php if($Who == "AbdelRahman" ){echo "selected";} ?> value="AbdelRahman">عبد الرحمن</option>
                      <option <?php if($Who == "Bedo" ){echo "selected";} ?> value="Bedo"> بيدو </option>
                      <option <?php if($Who == "All" )        {echo "selected";} ?> value="All">الجميع</option>
                    </select>
                    <?php if (isset($_GET['Start'])) { ?>
                      <input type="hidden" name="Start" value="<?php echo $_GET['Start']; ?>">
                    <?php } ?>
                    </form>
                  </div>
                </div>
              </div>
      <?php } ?>

      <div class="col-lg-12">
          <div class="card">

            <div class="col-12">
              <div class=" info-card revenue-card ">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center">    طلاب حان موعد تجديدهم 	 </h5>
                  <button  data-bs-target="#GetRenewal" aria-expanded="false" aria-controls="GetRenewal" class="bg-secondary text-white card-icon w-50 rounded-pill border  border-dark d-flex align-items-center justify-content-center " >
                    <h6 class="text-center text-white rtl"><?php echo  count(GetRenewal($Who))."  "."طالب" ; ?></h6>
                  </button>
                  <h5 class="card-title text-center"> المطلوب: سرعة التواصل معهم لدفع الإشتراك </h5>
                </div>
              </div>
            </div>

            <div class="card-body overflow-auto text-center"  id="GetRenewal" data-bs-parent="#myGroup">
              <table  class="table table-border table-hover text-center DataTable">
                <thead >
                  <tr>
                    <th   class="fw-bold fs-5 text-start text-nowrap" >كود الطالب</th>
                    <th    class="fw-bold fs-5 text-center text-nowrap" > حصص متبقية </th>
                    <th   class="fw-bold fs-5 text-center text-nowrap" > تاريخ التجديد </th>
                    <th   class="fw-bold fs-5 text-center text-nowrap" >  الحصص اخر اسبوعين </th>
                    <th   class="fw-bold fs-5 text-center text-nowrap" > تم التواصل </th>
                    <?php if (isset($_SESSION['manager']) AND $_SESSION['manager'] == "Belal") { ?>
                    <th   class="fw-bold fs-5 text-center text-nowrap" >  حذف </th>
                    <?php  } ?>
                  </tr>
                </thead>
                <tbody>
                  <?php  if (count(GetRenewal($Who)) == 0) { ?>  <td  class="fw-bold fs-5" colspan="3"> لا توجد طلبات مسجلة </td>  <?php } ?>
                  <?php 
                  if (count(GetRenewal($Who)) > 0) {

                    foreach ( GetRenewal($Who) as $value) {
                      $CodeExplode = explode(' ',$value['Code']);
                      $StudentCodeExplode = $CodeExplode[0] ; ?>
                    <tr>
                      <td onclick="window.location='<?php echo 'search?Code='.$StudentCodeExplode?>';" role="button" class="fw-bold fs-5 text-start text-nowrap"> <?php echo $value['Code'] ?> </td>
                      <td onclick="window.location='<?php echo 'search?Code='.$StudentCodeExplode?>';" role="button" class="fw-bold fs-5 text-center text-nowrap"> <?php echo $value['Remained'] ?> </td>
                      <td onclick="window.location='<?php echo 'search?Code='.$StudentCodeExplode?>';" role="button" class="fw-bold fs-5 text-center text-nowrap"> <?php echo $value['Renewal_date'] ?> </td>
                      <td onclick="window.location='<?php echo 'search?Code='.$StudentCodeExplode?>';" role="button" class="fw-bold fs-5 text-center text-nowrap"> <?php echo $value['LastClasses'] ?> </td>

                      <?php if ($value['Status'] == 1) { ?>
                        <td  class="fw-bold fs-5 text-center"> <input type="checkbox" checked disabled class="form-check-input form-control form-control-lg"> </td>
                      <?php  }else { ?>
                        <td onclick="document.getElementById('<?php echo $value['Code'] ?>').disabled = true,myAjax('<?php echo $value['Code'] ?>','<?php echo $Who ?>')" class="fw-bold fs-5 text-center">
                        <input type="checkbox" id='<?php echo $value['Code'] ?>' class="form-check-input form-control form-control-lg"> </td>
                      <?php  } ?>
                      <?php if (isset($_SESSION['manager']) AND $_SESSION['manager'] == "Belal") { ?>
                      <td  class="fw-bold fs-5 text-center"> <button  class="btn btn-outline-danger fs-6 fw-bold w-100 mt-2 " onclick="window.location='functions/DeleteFromRemained.php?ID=<?php echo $value['ID'] ?>'" >Delete</button> </td>
                      <?php  } ?>
                    </tr>
                    <?php } ?>
                  <?php } ?>
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

<script>
  function myAjax(Code,Who) {
      $.ajax({
           type: "POST",
           url: 'ActivitySend.php',
           data:{
            myAjax: 0 ,
            Code: Code ,
            Message:'  تم التواصل من أجل التجديد من قبل '+Who ,
            Who: Who ,
            Status: 1 
          },
           success:function(html) {
             alert(html);
           }

      });
 }
</script>
</main><!-- Center #main -->
<?php include "assets/tem/footer.php" ?>
<?php 
if (!empty($_SESSION['Emessage'])) { ?>
  <script>
    $(document).ready(function() {
        $(".toast").toast('show');
    });
</script>
<?php unset($_SESSION['Emessage']); } ?>
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
                      <option <?php if($Who == "AbdelRahman" ){echo "selected";} ?> value="AbdelRahman"> بيدو </option>
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
                <h5 class="card-title text-center">طلاب لم يأخذوا حصص آخر اسبوعين </h5>
                  <button type="button" data-bs-toggle="collapse" data-bs-target="#GetLastClasses" aria-expanded="false" aria-controls="GetLastClasses" class="bg-secondary text-white card-icon w-50 rounded-pill border  border-dark d-flex align-items-center justify-content-center " >
                    <h6 class="text-center text-white rtl"><?php echo  count(GetLastClasses($Who))."  "."طالب" ; ?></h6>
                  </button>
                  <h5 class="card-title text-center"> المطلوب: التواصل معهم ومع المعلم لمتابعة سبب التوقف </h5>
                </div>
              </div>
            </div>

            <div class="card-body overflow-auto text-center"  id="GetLastClasses" data-bs-parent="#myGroup">
              <table  class="table table-border table-hover text-center DataTable">
                <thead >
                  <tr>
                    <th   class="fw-bold fs-5 text-start text-nowrap" >كود الطالب</th>
                    <th    class="fw-bold fs-5 text-center text-nowrap" > حصص متبقية </th>
                    <th   class="fw-bold fs-5 text-center text-nowrap" > تاريخ اخر حصة </th>
                    <th   class="fw-bold fs-5 text-center text-nowrap" > تاريخ التجديد </th>
                  </tr>
                </thead>
                <tbody>
                  <?php  if (count(GetLastClasses($Who)) == 0) { ?>  <td  class="fw-bold fs-5" colspan="3"> لا توجد طلبات مسجلة </td>  <?php } ?>
                  <?php 
                  if (count(GetLastClasses($Who)) > 0) {

                    foreach ( GetLastClasses($Who,1) as $value) {
                      $CodeExplode = explode(' ',$value['Code']);
                      $StudentCodeExplode = $CodeExplode[0] ;
                       ?>
                    <tr onclick="window.location='<?php echo 'search?Code='.$StudentCodeExplode?>';" role="button">
                      <td class="fw-bold fs-5 text- text-start text-nowrap"> <?php echo $value['Code'] ?> </td>
                      <td class="fw-bold fs-5 text-center text-nowrap"> <?php echo $value['Remained'] ?> </td>
                      <td class="fw-bold fs-5 text-center text-nowrap"> <?php echo $value['Last_class'] ?> </td>
                      <td class="fw-bold fs-5 text-center text-nowrap"> <?php echo $value['Renewal_date'] ?> </td>
                    </tr>
                    <?php }?>
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
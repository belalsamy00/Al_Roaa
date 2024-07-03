<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['CustomerService']) AND !isset($_SESSION['manager']) AND !isset($_SESSION['CustomerServiceManager']) ) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ; 
require_once "functions/GetNewTeachers.php";
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

      <div class="col-lg-12">
          <div class="card">

            <div class="col-12">
              <div class=" info-card revenue-card ">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                  <h5 class="card-title text-center"> معلمين جدد </h5>
                  <button  data-bs-target="#GetNewTeachers" aria-expanded="false" aria-controls="GetNewTeachers" class="bg-secondary text-white card-icon w-50 rounded-pill border  border-dark d-flex align-items-center justify-content-center " >
                    <h6 class="text-center text-white rtl" id="countGetNewTeachers"></h6>
                  </button>
                  <h5 class="card-title text-center"> </h5>
                </div>
              </div>
            </div>

            <div class="card-body overflow-auto text-center"  id="GetNewTeachers" data-bs-parent="#myGroup">
              <table  class="table table-border table-hover table-striped-columns ">
                <thead >
                  <tr>
                    <th class="fw-bold fs-5 text-end text-nowrap" > Timestamp </th>
                    <th class="fw-bold fs-5 text-end text-nowrap" > الاسم </th>
                    <th class="fw-bold fs-5 text-end text-nowrap" > السن </th>
                    <th class="fw-bold fs-5 text-end text-nowrap" > الجنس </th>
                    <th class="fw-bold fs-5 text-end text-nowrap" > جميع التفاصيل </th>
                    <th class="fw-bold fs-5 text-end text-nowrap" > تم التواصل </th>
                    
                  </tr>
                </thead>
                <tbody id='tbody'>

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
        $(document).ready(function () {
        GetNewTeachers();
        setInterval(GetNewTeachers, 5000);
      })
  
        function GetNewTeachers() {
        $.ajax({ 
                type      : 'POST', 
                url       : 'functions/GetNewTeachers.php', 
                data      : {
                  GetNewTeachers : 1
                },
                cache  : false,
                success   : function(Data) {

                  var Data = JSON.parse(Data)
                  let content = Data
                  console.log(content);
                  if (content.length > 0) {
                    $('#tbody').html('')
                    $('#countGetNewTeachers').html(content[0].Total)
                    for (let i = 1; i < content.length; i++) {
                      let IDen = content[i].ID ;
                      let Timestamp = content[i].Timestamp ;
                      let Address = content[i].Address ;
                      let Email = content[i].Email ;
                      let Name = content[i].Name ;
                      let Phone = content[i].Phone ;
                      let Sex = content[i].Sex ;
                      let Age = content[i].Age ;
                      let Qualifications = content[i].Qualifications ;
                      let AnotherJob = content[i].AnotherJob ;
                      let HafsLicense = content[i].HafsLicense ;
                      let AnotherLicense = content[i].AnotherLicense ;
                      let MaritalStatus = content[i].MaritalStatus ;
                      let Documents = content[i].Documents ;
                      
                      if (content[i].Status == 0) {
                        var Statusinput = `
                        <td onclick="myAjax('${IDen}')" class="fw-bold fs-5 text-center" role="button"> <i class="bi bi-square"></i> </td>
                        `
                      }else{
                        var Statusinput = `
                        <td  class="fw-bold fs-5 text-center"> <i class="bi bi-check-circle"></i> </td>
                        `
                      }
                      let tr =`
                      <tr>
                        <td class="fw-bold fs-5 text-end text-nowrap"> ${Timestamp} </td>
                        <td class="fw-bold fs-5 text-end text-nowrap"> ${Name} </td>
                        <td class="fw-bold fs-5 text-end text-nowrap"> ${Age} </td>
                        <td class="fw-bold fs-5 text-end text-nowrap"> ${Sex} </td>
                        <td class="fw-bold fs-5 text-end text-nowrap"> 
                        <form action="https://application.alroaaacademy.site/GetNewTeachersApplication.php" target="_blank">
                          <input type="hidden" name="ID" value='${IDen}'>
                          <button class='btn btn-light w-100 fs-6 fw-bold' type="submit"> عرض </button>
                        </form>
                         </td>
                        ${Statusinput}
                      </tr>
                    `;
                    $('#tbody').append(tr)

                      ;}}
                }
            });
      }

  function myAjax(ID) {
      $.ajax({
           type: "POST",
           url: 'functions/GetNewTeachers.php',
           data:{
            ID: ID,
            myAjax:1
          },
            success:function(html) {
            alert(html);
            GetNewTeachers();
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
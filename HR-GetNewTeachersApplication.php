<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['CustomerService']) AND !isset($_SESSION['manager']) AND !isset($_SESSION['CustomerServiceManager']) ) {  header('Location: index');  exit;  } 
if (!isset($_GET['ID']) ) {  header('Location: index');  exit;  } 
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
$GetNewTeachers = $con->prepare("SELECT *  FROM  NewTeachers WHERE ID =? ");
$GetNewTeachers->execute(array($_GET['ID']));
$RenewalCount = $GetNewTeachers->rowCount();
$NewTeachers = $GetNewTeachers->fetch();
 ?>
<main id="main" class="main">
  <div class="container">
  <div class="pagetitle">
      <nav>
        <ol class="breadcrumb">
          <li class="">        
            <button class="btn btn-primary fs-6 fw-bold text-white" onclick="history.back()">
            <i class="bi bi-box-arrow-left"></i>
            <span> عودة </span>
            </button.><!-- End Dashboard Iamge Icon -->
          </li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    
    <section class="section dashboard">
      <div class="row" id="myGroup">

      <div class="col-lg-6 m-auto">
          <div class="card rtl">


            <div class="card-body overflow-auto text-center"  id="GetNewTeachers" data-bs-parent="#myGroup">

              <div class=" mt-2 d-flex justify-content-start">
                <strong> تاريخ الطلب :- </strong>
                <p class="px-2"> <?php echo $NewTeachers['Timestamp'] ?> </p>
              </div>

              <div class=" mt-2 d-flex justify-content-start">
                <strong> الأسم :- </strong>
                <p class="px-2"> <?php echo $NewTeachers['Name'] ?> </p>
              </div>

              <div class=" mt-2 d-flex justify-content-start">
                <strong> السن :- </strong>
                <p class="px-2"> <?php echo $NewTeachers['Age'] ?> </p>
              </div>

              <div class=" mt-2 d-flex justify-content-start">
                <strong> النوع :- </strong>
                <p class="px-2"> <?php echo $NewTeachers['Sex'] ?> </p>
              </div>

              <div class=" mt-2 d-flex justify-content-start">
                <strong> الحالة الأجتماعية :- </strong>
                <p class="px-2"> <?php echo $NewTeachers['MaritalStatus'] ?> </p>
              </div>

              <div class=" mt-2 d-flex justify-content-start">
                <strong> المؤهلات :- </strong>
                <p class="px-2"> <?php echo $NewTeachers['Qualifications'] ?> </p>
              </div>

              <div class=" mt-2 d-flex justify-content-start">
                <strong> إجازة فى حفص عن عاصم ؟ :- </strong>
                <p class="px-2"> <?php echo $NewTeachers['HafsLicense'] ?> </p>
              </div>

              <div class=" mt-2 d-flex justify-content-start">
                <strong> إجازات اخرى ؟ :- </strong>
                <p class="px-2"> <?php echo $NewTeachers['AnotherLicense'] ?> </p>
              </div>

              <div class=" mt-2 d-flex justify-content-start">
                <strong> متفرغ ؟ :- </strong>
                <p class="px-2"> <?php echo $NewTeachers['AnotherJob'] ?> </p>
              </div>

              <div class=" mt-2 d-flex justify-content-start">
                <strong> الهاتف :- </strong>
                <p class="px-2"> <?php echo $NewTeachers['Phone'] ?> </p>
              </div>

              <div class=" mt-2 d-flex justify-content-start">
                <strong> الإيميل :- </strong>
                <p class="px-2"> <?php echo $NewTeachers['Email'] ?> </p>
              </div>

              <div class=" mt-2 d-flex justify-content-start">
                <strong> العنوان :- </strong>
                <p class="px-2"> <?php echo $NewTeachers['Address'] ?> </p>
              </div>
              <a href="https://application.alroaaacademy.site/Docs.php?id=<?php echo $NewTeachers['ID'] ?>" target="_blank" rel="noopener noreferrer">look </a>
              <iframe src="https://application.alroaaacademy.site/Docs.php?id=<?php echo $NewTeachers['ID'] ?>" frameborder="0"></iframe>
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
                  // console.log(content);
                  if (content.length > 0) {
                    $('#tbody').html('')
                    $('#countGetNewTeachers').html(content[0].Total)
                    for (let i = 1; i < content.length; i++) {
                      let ID = content[i].ID ;
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
                        <td onclick="myAjax('${ID}')" class="fw-bold fs-5 text-center" role="button"> <i class="bi bi-square"></i> </td>
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
                        <td class="fw-bold fs-5 text-end text-nowrap"> ${MaritalStatus} </td>
                        <td class="fw-bold fs-5 text-end text-nowrap"> ${Qualifications} </td>
                        <td class="fw-bold fs-5 text-end text-nowrap"> ${AnotherJob} </td>
                        <td class="fw-bold fs-5 text-end text-nowrap"> ${HafsLicense} </td>
                        <td class="fw-bold fs-5 text-end text-nowrap"> ${AnotherLicense} </td>
                        <td class="fw-bold fs-5 text-end text-nowrap"> ${Phone} </td>
                        <td class="fw-bold fs-5 text-end text-nowrap"> ${Email} </td>
                        <td class="fw-bold fs-5 text-end text-nowrap"> ${Address} </td>
                        <td class="fw-bold fs-5 text-end text-nowrap"> ${Documents} </td>
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
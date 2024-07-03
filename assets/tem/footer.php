   <!-- ======= loader ======= -->
   <style>
    .loader-contener {
      position: fixed;
      right: 0;
      left: 0;
      top: 0;
      bottom: 0;
      z-index: 9999999999;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      background-color: #f6f9ff;
    }
    .loader-perant {
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .loader-child-2 {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content:center;
      color: #012970;
      font-size: 20px;
      font-weight: 600;
      direction: rtl;
      text-wrap: nowrap;
    }
    .loader-child {
      min-height: fit-content;
      display: flex;
      align-items: center;
      justify-content: center;
      width: 200px;
      height: 200px;
      background-color: #fff;
      color: #012970;
      font-size: large;
      font-weight: 900;
      position: relative;
      border-radius: 50%;
      background-image: url(assets/img/logo.png);
      background-size: cover;
      margin-bottom: 10px;
      animation: float 4s ease-in-out infinite;
    }
    .loader-child::after {
      content: "";
      width: 200px;
      height: 200px;
      position: absolute;
      left: 0;
      top: 0;
      z-index: -5;
      border-radius: 50%;
      border: 3px solid #012970;
      border-color: #f26e0c3d #f26e0c3d #f26e0c3d #f26e0c;
      box-sizing: border-box;
      transition: transform 0.5s;
    }

    .loader-child::after {
      animation-name: loader-rotate;
      animation-duration: 2.5s;
      animation-iteration-count: infinite;
      animation-timing-function: linear;
    }

    @keyframes loader-rotate {
      from {
        transform: rotate(360deg);
      }

      to {
        transform: rotate(0deg);
      }
    }

    @keyframes float {
      0% {
        box-shadow: 0px 0 15px rgb(1 41 112 / 32%);
        transform: translatey(0px);
      }
      50% {
        box-shadow: 0px 0 15px rgb(1 41 112 / 32%);
        transform: translatey(-20px);
      }
      100% {
        box-shadow: 0px 0 15px rgb(1 41 112 / 32%);
        transform: translatey(0px);
      }
    }


  </style>
  <div id="loader-contener" class="loader-contener">
    <div class="loader-perant">
      <div class="loader-child"></div>
    </div>
    <div class="loader-perant">
      <div class="loader-child-2">
        <p> من فضلك انتظر جارى التحميل ... </p>
        <p> صلى على رسول الله </p>
      </div>
    </div>
  </div>
  <script>
    $('#loader-contener').hide();
    $('form').on("submit", function (e) {
      $('#loader-contener').show();
      console.log(e);
    }); 
    $('a').on('click', function (e) {
      if ($(this).attr('href') != '' && $(this).attr('href') != '#' && !$(this).attr('data-bs-toggle') ) {
        $('#loader-contener').show();
        console.log(e);
      }
    });
  </script>
<!-- ======= Footer ======= -->
  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>El Roaa Academy</span></strong>. All Rights Reserved 
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.13.4/b-2.3.6/b-html5-2.3.6/b-print-2.3.6/date-1.4.1/sc-2.1.1/datatables.min.js"></script>


  <!-- Template Main JS File -->
  <script src="assets/js/main-03-28-2024.js"></script>
  <script src="assets/js/refreshAt-22-09-2023.js"></script>
  <!-- ---------- ShowNotification -------------- -->
    <?php 
      if (isset($_SESSION['Suber_Admin'])) {
        if (isset($_SESSION['manager'])) {
          $Who = $_SESSION['Suber_Admin'].',general,manager,SuperAdmin,Admin,supervisor,SupervisingManager,CustomerService,teacher,T40';
          $WhoSeen = $_SESSION['Suber_Admin'].',T40';
        }elseif (isset($_SESSION['CustomerService'])) {
          $Who = $_SESSION['Suber_Admin'].',general,SuperAdmin,SupervisingManager,CustomerService,teacher,T50';
          $WhoSeen = $_SESSION['Suber_Admin'].',T50';
        }else {
          $Who = $_SESSION['Suber_Admin'].',general,SuperAdmin';
          $WhoSeen = $_SESSION['Suber_Admin'];
        }
      
      }elseif (isset($_SESSION['manager'])) {
        $Who = $_SESSION['manager'].',general,manager';
        $WhoSeen = $_SESSION['manager'];
      }elseif (isset($_SESSION['supervisor'])) {
        $Who = $_SESSION['supervisor'].',general,supervisor';
        $WhoSeen = $_SESSION['supervisor'];
      }elseif (isset($_SESSION['Supervising_manager'])) {
        $Who = $_SESSION['Supervising_manager'].',general,Supervising_manager';
        $WhoSeen = $_SESSION['Supervising_manager'];
      }elseif (isset($_SESSION['Admin'])){
        $Who = $_SESSION['Admin'].',general,Admin';
        $WhoSeen = $_SESSION['Admin'];
      }elseif (isset($_SESSION['teacher'])) {
        $Who = $_SESSION['teacher'].',general,teacher';
        $WhoSeen = $_SESSION['teacher'];
      }else {
        $Who = 'general';
        $WhoSeen = 'T0';
      }

    ?>
    <script>
      $(document).ready(function () {
        ShowNotification();
        // setInterval(ShowNotification, 90000);
      })
      function Seen(ID) {
        $('#loader-contener').show();
        $.ajax({ 
                type      : 'GET',
                url       : 'Notification.php', 
                data      : {
                  ID : ID,
                  WhoSeen : '<?php echo $WhoSeen  ?>'
                },
                cache  : false,
                success   : function() {
                  ShowNotification();
                }
            });
        $('#loader-contener').hide();
      }


      function ShowNotification() {
        $.ajax({
                type      : 'GET',
                url       : 'Notification.php',
                data      : {
                  Who : '<?php echo $Who  ?>'
                },
                cache  : false,
                success   : function(Data) {

                  var Data = JSON.parse(Data)
                  let content = Data
                  let seenAll = `` ;
                  if (content.length > 0) {
                    $('#notifications_ul').html('')
                    
                    for (let i = 1; i < content.length; i++) {
                        let ID = content[i].ID ;
                        let Timestamp = content[i].Timestamp ;
                        let Message = content[i].Message ;
                        let Seen = content[i].Seen ;
                        let Url = content[i].Url ;
                        let Category = content[i].Category ;
                        if (Seen == 0 ) {
                          Seentext = 'background: #f6f9ff;'
                          border = 'warning'
                        }else{
                          Seentext = ''
                          border = 'success'
                        }
                        if (Seen == 1 ) {
                          Seenbtn = ` `
                          Urlbtn = ` `
                        }else{
                          seenAll += ID+' '
                          Seenbtn = `<button  class="btn btn-outline-primary btn-sm" onclick="Seen(${ID})"> تمييز كمقرؤة </button>`
                          if (Url == 'No' ) {
                            Urlbtn = ` `
                          }else{
                            Urlbtn = ` <a class="btn btn-outline-primary btn-sm" href="${Url}"onclick="Seen(${ID})"> Go </a> `
                          }
                        }
                        
                      let  notification_li =`
                        <li class="notification-item"  style="${Seentext}">
                          <h4 class="w-25 text-center pe-1 me-1 fs-6">${Category}</h4>
                          <div class="w-75 p-3  border-start  border-${border}">
                            <p class="rtl fw-bold text-dark">${Message}</p>
                            <p>${Timestamp}</p>
                            ${Seenbtn}
                            
                            ${Urlbtn}
                          </div>
                        </li>

                        <li>
                          <hr class="dropdown-divider">
                        </li>
                      `;
                      $('#notifications_ul').append(notification_li)
                    }
                    if (seenAll != '') {
                      let SeenAllbtn = `<li onclick="SeenAll('${seenAll}')" class="d-flex justify-content-center mb-2 link-danger" style='cursor: pointer;'>
                        تمييز الكل كمقرؤة  
                      <li>
                          <hr class="dropdown-divider">
                        </li>
                      </li>
                      `
                      $('#notifications_ul').prepend(SeenAllbtn)
                    }
                    if (content[0].Total > 0) {
                      $('#notifications_badge').html(`
                      <span class="badge bg-danger badge-number">${content[0].Total}</span>
                    `)
                    }else{
                      $('#notifications_badge').html(`
                      <span> </span>
                    `)
                    }
                  }else{
                    $('#notifications_ul').html('')
                    let  notification_li =`
                      <li class="notification-item" >
                        
                        <div class="w-100">
                          <p class="rtl fw-bold text-dark text-center"> لاتوجد إشعارات </p>
                        </div>
                      </li>

                      <li>
                        <hr class="dropdown-divider">
                      </li>
                    `;
                    $('#notifications_ul').append(notification_li)

                    $('#notifications_badge').html(`
                      <span></span>
                    `)
                  }
                }
            });
      }


      function SeenAll(element) {
        const myArray = element.toString().split(" ");
        for (const id of myArray) {
          Seen(id)
        }
        
      }
     
    </script>
  <!-- ---------- ShowNotification -------------- -->
  </body>

</html>
<?php ob_end_flush(); ?>
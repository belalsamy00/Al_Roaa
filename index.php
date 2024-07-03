<?php 
    ob_start();
    if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
    include "connect.php" ;
    if (isset($_SESSION['teacher']))             {header('location: Home');exit;} 
    if (isset($_SESSION['Student']))             {header('location: Student\\Home');exit;} 
    if (isset($_SESSION['supervisor']))          {header('location: Management');exit;} 
    if (isset($_SESSION['Supervising_manager'])) {header('location: Management');exit;} 
    if (isset($_SESSION['Admin']))               {header('location: Management');exit;} 
    if (isset($_SESSION['Suber_Admin']))         {header('location: Management');exit;} 
    if (isset($_SESSION['manager']))             {header('location: Management');exit;} 
    if (isset($_SESSION['CustomerService']))             {header('location: CustomerService');exit;} 
    if (isset($_SESSION['CustomerServiceManager']))             {header('location: CustomerService');exit;} 
    if (isset($_COOKIE['login-user']) && isset($_COOKIE['login-token'])) {
      $username = $_COOKIE["login-user"];
      $hashedpass =  $_COOKIE["login-token"] ;
      $stmt = $con->prepare("SELECT Code ,`Name`, `Password`,trust FROM users WHERE Code=? AND `Password`=?  LIMIT 1 ");
      $stmt->execute(array($username,$hashedpass));
      $row = $stmt->fetch();
      $count = $stmt->rowCount();
      if ($count == 0) {
        $stmt = $con->prepare("SELECT Code ,`Name`, `Password` FROM teachers WHERE Code=? AND `Password`=?  LIMIT 1 ");
        $stmt->execute(array($username,$hashedpass));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $count = $stmt->rowCount();
        if ($count > 0) {
          $row['trust'] = 1;
        }
      }
    }elseif (isset($_POST['user']) && isset($_POST['pass'])) {
      $username = filter_var($_POST["user"], FILTER_UNSAFE_RAW );
      $password = filter_var($_POST["pass"], FILTER_UNSAFE_RAW );
      $hashedpass= sha1($password);
      $stmt = $con->prepare("SELECT Code ,`Name`, `Password`,trust FROM users WHERE Code=? AND `Password`=?  LIMIT 1 ");
      $stmt->execute(array($username,$hashedpass));
      $row = $stmt->fetch();
      $count = $stmt->rowCount();
      if ($count == 0) {
        $stmt = $con->prepare("SELECT Code ,`Name`, `Password` FROM teachers WHERE Code=? AND `Password`=?  LIMIT 1 ");
        $stmt->execute(array($username,$hashedpass));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $count = $stmt->rowCount();
        if ($count > 0) {
          $row['trust'] = 1;
        }
      }
    }
            
    if (isset($count)) {
      if ($count > 0) {
  
        if(isset($_POST["remember_me"]) || $_POST["remember_me"]=='on')
          {
          $hour = time() + 3600 * 24 * 30 * 12;
          setcookie('login-user', $username, $hour);
          setcookie('login-token', $hashedpass, $hour);
          }
          if ($row['trust']== 1) {
              $_SESSION['teacher'] = $row['Code'];
              $_SESSION['Name'] = $row['Name'];
              $_SESSION['postion'] = "Teacher";
              header('location: Home');
              exit;
          }elseif ($row['trust']== 2) {
              $_SESSION['Student'] = $row['Code'];
              $_SESSION['Name'] = $row['Name'];
              $_SESSION['postion'] = "Student";
              header('location: Student');
              exit;
          }elseif ($row['trust']==3) {
            if ($row['Code'] == "Bedo") {
              $_SESSION['supervisor'] = $row['Code'];
              $_SESSION['CustomerService'] = $row['Code'];
              $_SESSION['Who'] = "Bedo";
              $_SESSION['Name'] = $row['Name'];
              $_SESSION['postion'] = "supervisor";
              header('location: Management');
              exit;
            }else {
              $_SESSION['supervisor'] = $row['Code'];
              $_SESSION['Name'] = $row['Name'];
              $_SESSION['postion'] = "supervisor";
              header('location: Management');
              exit;
            }
          }elseif ($row['trust']== 4) {
              $_SESSION['Supervising_manager'] = $row['Code'];
              $_SESSION['Name'] = $row['Name'];
              $_SESSION['postion'] = "Supervising manager";
              header('location: Management');
              exit;
          }elseif ($row['trust']== 5) {
              $_SESSION['Admin'] = $row['Code'];
              $_SESSION['supervisor'] = $row['Code'];
              $_SESSION['Name'] = $row['Name'];
              $_SESSION['postion'] = "Admin";
              header('location: Management');
              exit;
          }elseif ($row['trust']== 6) {
            if ($row['Code'] == "AD2") {
              $_SESSION['Suber_Admin'] = $row['Code'];
              $_SESSION['Supervising_manager'] = $row['Code'];
              $_SESSION['CustomerService'] = $row['Code'];
              $_SESSION['CustomerServiceManager'] = $row['Code'];
              $_SESSION['teacher'] = "T50";
              $_SESSION['Name'] = $row['Name'];
              $_SESSION['Who'] = "Ramadan";
              $_SESSION['postion'] = "Super Admin";
              header('location: Management');
              exit;
            }else {
              $_SESSION['Suber_Admin'] = $row['Code'];
              $_SESSION['Name'] = $row['Name'];
              $_SESSION['postion'] = "Super Admin";
              header('location: Management');
              exit;
            }
          }
          elseif ($row['trust']== 7 ) {
            if ($row['Code'] == "Belal") {
              $_SESSION['manager'] = $row['Code'];
              $_SESSION['Suber_Admin'] = $row['Code'];
              $_SESSION['Supervising_manager'] = $row['Code'];
              $_SESSION['CustomerService'] = $row['Code'];
              $_SESSION['teacher'] = "T40";
              $_SESSION['Name'] = $row['Name'];
              $_SESSION['Who'] = "Belal";
              $_SESSION['postion'] = "Manager";
              header('location: Management');
              exit;
            }else {
              $_SESSION['manager'] = $row['Code'];
              $_SESSION['Name'] = $row['Name'];
              $_SESSION['postion'] = "Manager";
              header('location: Management');
              exit;
            }
  
          }
          elseif ($row['trust']== 8) {
            $_SESSION['CustomerService'] = $row['Code'];
            $_SESSION['Name'] = $row['Name'];
            $_SESSION['Who'] = $row['Name'];
            $_SESSION['postion'] = "CustomerService";
            header('location: CustomerService');
            exit;
  
          }
          elseif ($row['trust']== 9) {
            $_SESSION['CustomerServiceManager'] = $row['Code'];
            $_SESSION['Name'] = $row['Name'];
            $_SESSION['Who'] = $row['All'];
            $_SESSION['postion'] = "CustomerServiceManager";
            header('location: CustomerService');
            exit;
  
          }
      }else {
          $_SESSION['login-message'] = 'false';
      }
    }
    ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>El Roaa / Login </title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/logo.png" rel="icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.4.slim.js" integrity="sha256-dWvV84T6BhzO4vG6gWhsWVKVoa4lVmLnpBOZh/CAHU4=" crossorigin="anonymous"></script>

  <!-- Template Main CSS File -->
  <link href="assets/css/style-01-11-2023.css" rel="stylesheet">

</head>

<body>

  <main class=" pagebackground ">
    <div class="  container" >


      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="loginform col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">



              <div class="card mb-3">

                <div class="card-body">
                <div class="d-flex justify-content-center py-4">
                <a href="index.html" class="logo d-flex align-items-center w-auto">
                  <img src="assets/img/logo.png" alt="">
                  <span class="d-none d-lg-block">أكاديمية الرؤى</span>

                </a>
              </div><!-- End Logo -->

                  <div class="pt-4 pb-2 text-center">
                  <h2> أكبر أكاديمية لتعليم القرآن الكريم في الوطن العربي </h2>
                  <h3> قال رسول الله ﷺ ( خَيْرُكُمْ مَن تَعَلَّمَ القُرْآنَ وعَلَّمَهُ) </h3>
                    <h5 class="card-title text-center pb-0 fs-4">تسجيل الدخول</h5>
                  </div>

                  <form  action=""  method= 'POST' class="row g-3 needs-validation" novalidate>

                    <div class="col-12">
                      <label for="yourUsername" class="form-label">اسم المستخدم</label>
                      <div class="input-group has-validation">
                        <input type="text" name="user" class="form-control" id="yourUsername" required>
                        <div class="invalid-feedback">Please enter your username.</div>
                      </div>
                    </div>

                    <div class="col-12 ltr">
                        <label for="password-field">Password</label>
                        <input id="password-field" type="password" class="form-control rtl" name="pass" autocomplete="new-password" required>
                        <span toggle="#password-field" class="bi bi-eye-fill field-icon toggle-password w-10" ></span>
                        <div class="invalid-feedback">Please enter your password!</div>
                    </div>

                    <div class="col-12">
                      <label for="remember_me" class="btn btn-light w-100"> البقاء متصلا <input type="checkbox" name="remember_me" id="remember_me" checked> </label>
                    </div>
                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">دخول</button>
                    </div>
                  </form>
                  <?php
                    if (!empty($_SESSION['login-message']) ){
                    ?> <div class= "alert alert-danger Validate" style="width: 90%!important; margin-top: 6px!important; margin: auto;" > <?php
                    echo "خطأ فى أسم المستخدم أو كلمة السر" ;
                    ?> </div> <?php
                    unset($_SESSION['login-message']); }?>

                </div>
              </div>

            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
 
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/jquery-22-05-2023.js"></script>

</body>

</html>
<?php  ob_end_flush();?>
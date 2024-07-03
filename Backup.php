<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['teacher'])) {header('location: index.php'); exit; } 
include "assets/tem/header.php" ; ?>
<main id="main" class="main">
  <div class="container">
    <div class="pagetitle">
      <h1>النسخ الأحتياطى</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">الرئيسية</a></li>
          <li class="breadcrumb-item active">نسخة أحتياطية جيدة</li>
          <li class="breadcrumb-item active" id="date_li"></li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
  
    <section class="section">
      <div class="row">

        <button class="btn btn-light fs-6 fw-bold  mt-2 m-auto" type="button" id="ExcelAll" disabled>
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Loading...
        </button>

        <button class="btn btn-light fs-6 fw-bold  mt-2 m-auto" type="button" id="ExcelHistory" disabled>
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Loading...
        </button>

        <button class="btn btn-light fs-6 fw-bold  mt-2 m-auto" type="button" id="SQLAll" disabled>
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Loading...
        </button>

        <button class="btn btn-light fs-6 fw-bold  mt-2 m-auto" type="button" id="SQLHistory" disabled>
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Loading...
        </button>

        <script>
          function ExcelAll() {
            $.ajax({
              type: "GET",
              url: "Backup-Excel-All.php",
              data: {},
              cache: false,
              success: function (Data) {
                    try {
                      var Data = JSON.parse(Data)
                      let content = Data;
                      console.log(content[1].Text);
                      $('#ExcelAll').html(content[1].Text)
                      if (content[0].Status == 0) {document.getElementById('ExcelAll').style.setProperty ("color" ,"red", "important");}
                    } catch (e) {
                      console.log("راجع الدعم الفنى"+" All Database Excel "+"لم يتم إنشاء ملف ال ");
                      $('#ExcelAll').html("راجع الدعم الفنى"+" All Database Excel "+"لم يتم إنشاء ملف ال ")
                      document.getElementById('ExcelAll').style.setProperty ("color" ,"red", "important"); 
                    }
              },
            });
          }
          function ExcelHistory() {
            $.ajax({
              type: "GET",
              url: "Backup-Excel-History.php",
              data: {},
              cache: false,
              success: function (Data) {
                    try {
                      var Data = JSON.parse(Data)
                      let content = Data;
                      console.log(content[1].Text);
                      $('#ExcelHistory').html(content[1].Text)
                      if (content[0].Status == 0) {document.getElementById('ExcelHistory').style.setProperty ("color" ,"red", "important");}
                    } catch (e) {
                        console.log("راجع الدعم الفنى"+" History Excel "+"لم يتم إنشاء ملف ال ");
                        $('#ExcelHistory').html("راجع الدعم الفنى"+" History Excel "+"لم يتم إنشاء ملف ال ")
                        document.getElementById('ExcelHistory').style.setProperty ("color" ,"red", "important"); 
                    }
              },
            });
          }
          function SQLAll() {
            $.ajax({
              type: "GET",
              url: "Backup-SQL-All.php",
              data: {},
              cache: false,
              success: function (Data) {
                    try {
                      var Data = JSON.parse(Data)
                      let content = Data;
                      console.log(content[1].Text);
                      $('#SQLAll').html(content[1].Text)
                      if (content[0].Status == 0) {document.getElementById('SQLAll').style.setProperty ("color" ,"red", "important");}
                    } catch (e) {
                        console.log("راجع الدعم الفنى"+" All Database SQL "+"لم يتم إنشاء ملف ال ");
                        $('#SQLAll').html("راجع الدعم الفنى"+" All Database SQL "+"لم يتم إنشاء ملف ال ")
                        document.getElementById('SQLAll').style.setProperty ("color" ,"red", "important"); 
                    }
                
              },
            });
          }
          function SQLHistory() {
            $.ajax({
              type: "GET",
              url: "Backup-SQL-History.php",
              data: {},
              cache: false,
              success: function (Data) {
                    try {
                      var Data = JSON.parse(Data)
                      let content = Data;
                      console.log(content[1].Text);
                      $('#SQLHistory').html(content[1].Text)
                      if (content[0].Status == 0) {document.getElementById('SQLHistory').style.setProperty ("color" ,"red", "important");}
                    } catch (e) {
                        console.log("راجع الدعم الفنى"+" History SQL "+"لم يتم إنشاء ملف ال ");
                        $('#SQLHistory').html("راجع الدعم الفنى"+" History SQL "+"لم يتم إنشاء ملف ال ")
                        document.getElementById('SQLHistory').style.setProperty ("color" ,"red", "important"); 
                    }
              },
            });
          }
          ExcelAll();
          ExcelHistory();
          SQLAll();
          SQLHistory();
        </script>

        <button onclick="history.back()" class="btn btn-primary fs-6 fw-bold  mt-2 w-25 m-auto"> عودة</button>
      </div>
    </section>
  </div>


</main>

<?php include "assets/tem/footer.php" ;?>




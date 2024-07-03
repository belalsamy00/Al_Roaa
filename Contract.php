<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['Admin']) AND !isset($_SESSION['Suber_Admin']) AND !isset($_SESSION['manager']) AND !isset($_SESSION['Supervising_manager']) AND !isset($_SESSION['CustomerService']) AND !isset($_SESSION['supervisor']) AND !isset($_SESSION['teacher'])) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ;
if (isset($_SESSION['teacher'])) {
  $session_code = $_SESSION['teacher'] ;
} else {
  $session_code = " " ;
}
$Hr_data_code_stmt = $con->prepare("SELECT * FROM Hr_data WHERE Code=? AND `Contract` != ?");
$Hr_data_code_stmt->execute(array($session_code,9));
$Hr_data = $Hr_data_code_stmt->fetch();
$Hr_data_rowCount = $Hr_data_code_stmt->rowCount();

// $session_code = $_SESSION['teacher'] ;
// $_UPDATE = $con->prepare("UPDATE  Hr_data SET `Contract`=? WHERE Code=?");
// $_UPDATE->execute(array(1,$session_code));
// $Hr_data_UPDATE = $_UPDATE->fetch();
// $Hr_data_rowCount_UPDATE = $_UPDATE->rowCount();

if($_SERVER ['REQUEST_METHOD']== 'POST') {
  $img = $_POST['data'];
  $IDNumber = $_POST['IDNumber'];
  $Text = $_POST['Text'];
  $RenewalDate = date('Y-m-d', strtotime('+1 year'));
  $img = str_replace('data:image/png;base64,', '', $img);
  $img = str_replace(' ', '+', $img);
  $fileData = base64_decode($img);
  $fileName = 'assets/img/Contracts/'.$IDNumber.'.png';
  file_put_contents($fileName, $fileData);

  $session_code = $_SESSION['teacher'] ;
  $Hr_data_code_stmt = $con->prepare("UPDATE  Hr_data SET `Contract`=?, `Text`=? ,RenewalDate=? WHERE Code=?");
  $Hr_data_code_stmt->execute(array(2,$Text,$RenewalDate,$session_code));
  $Hr_data_rowCount = $Hr_data_code_stmt->rowCount();
  if ($Hr_data_rowCount > 0 ) {
    $_SESSION['Emessage'] = 'تم إرسال العقد بنجاح ';
    header('Location: Contract');
    exit;
  }else {
    $_SESSION['Emessage'] =  'لم يتم إرسال العقد حاول مرة أخرى ';
    header('Location: Contract');
    exit;
  }
}else {
?>
  <main id="main" class="main p-0">
    <div class="container">
      <div class="pagetitle">
        <h1>Contract Page</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item active">Contract</li>
          </ol>
        </nav>
      </div><!-- End Page Title -->

      <section class="section w-100">

      <?php if ($Hr_data_rowCount  != 0 ) { 
        if ($Hr_data['Contract'] == 1) { ?>
          <div class="col-lg-9 m-auto">
            <div class="card">
              <div class="card-body">

                <!-- ------------------------------------------------ -->
                <div class="bg-white p-3" style="background-image: url(assets/img/logo-op-sm.png); background-repeat: no-repeat; background-position: center; background-size: contain;">
                  <h5 class="card-title text-center">عقد توظيف معلم</h5>
                  <div class="rtl" id ="Text">
                    <img src="assets/img/logo.png" alt="Profile" class="rounded-circle w-25">
                    <p class="fs-6 fw-bold"> قد تم الاتفاق في يوم <span class="text-danger"> <?php echo date("Y-m-d") ?> </span>  </p>
                    <p class="fs-6 fw-bold"> بين: </p>
                    <p class="fs-6 fw-bold"> الطرف الأول: أكاديمية الرؤي لتعليم القرآن الكريم، ويمثلها في هذا العقد <span class="text-danger">  السيد/ محمد عبدالله محمد حسن منجود </span> ، بصفته المدير العام ويشار إليه فيما بعد بـ (الأكاديمية). </p>
                    <p class="fs-6 fw-bold"> الطرف الثاني: <span class="text-danger"> السيد/ة: <?php echo $Hr_data['Name']." "."," ?> بطاقة رقم قومي: <?php echo $Hr_data['IDNumber']." ".","  ?> </span>  ويشار إليه فيما بعد بـ (المعلم). </p>
                    <p class="fs-6 fw-bold"> بعد أن أقر الطرفان بأهليتهما للتعاقد والتصرف قانونا، اتفقا على أن يعمل الطرف الثاني لدى الطرف الأول بالشروط الآتية: </p>
                    <p class="badge bg-primary fs-6 fw-bold"> (البند الأول) </p>
                    <p class="fs-6 fw-bold"> يعمل الطرف الثاني (المعلم) لدى الطرف الأول (الأكاديمية) و تحت إشرافه بوظيفة معلم قرآن كريم اون لاين بأكاديمية الرؤي لتعليم القرآن الكريم . </p>
                    <p class="badge bg-primary fs-6 fw-bold"> (البند الثاني) </p>
                    <p class="fs-6 fw-bold"> مدة هذا العقد سنة ميلادية واحدة تبدأ من <span class="text-danger"> <?php echo date("Y-m-d") ?> </span>  ويتجدد تلقائيا لمدة أخرى مماثلة إذا لم تقم إدارة الأكاديمية بإخطار المعلم قبل انتهاء المدة الجارية بشهرين على الأقل برغبتها في عدم تجديد العقد. </p>
                    <p class="badge bg-primary fs-6 fw-bold"> (البند الثالث) </p>
                    <p class="fs-6 fw-bold"> يحق للمعلم تقديم استقالته بعد مرور سنة على الأقل من مدة العقد وليس قبل ذلك. </p>
                    <p class="fs-6 fw-bold"> و يجب أن يقدم إشعارًا كتابيًا مسبقًا قبل شهرين على الأقل إلى الأكاديمية ويحق لإدارة الأكاديمية رفض الاستقالة في حالة احتياج العمل إلى المعلم.  </p>
                    <p class="fs-6 fw-bold"> وفى حالة تم قبول الاستقالة فيجب على الأكاديمية خلال فترة الشهرين نقل حصص المعلم إلى معلم آخر. وفي حالة مخالفته لهذا النص يعتبراً مقصرًا لإضراره بالأكاديمية أمام عملائها، ويلتزم بدفع مبلغ ٢۰۰۰ جنيه تعويضًا للأكاديمية </p>
                    <p class="badge bg-primary fs-6 fw-bold"> (البند الرابع) </p>
                    <p class="fs-6 fw-bold"> يجب على الطرف الثاني عند انتهاء خدمته لأي سبب من الأسباب بما في ذلك الاستقالة ،أن لا يحاول التماس او التواصل مع أي عميل من الأكاديمية (الطلاب أو أولياء أمور الطلاب). وفي حالة المخالفة يعتبر مسئولًا عن الأضرار الناجمة عن هذا الفعل. </p>
                    <p class="badge bg-primary fs-6 fw-bold"> (البند الخامس) </p>
                    <p class="fs-6 fw-bold"> ١- يتقاضى المعلم من الأكاديمية <span class="text-danger"> <?php echo $Hr_data['HourRate'] ?> </span>  جنيهًا في الساعة الواحدة للطلاب العرب. وله علاوة بعد ثلاثة شهور من العمل ليصل سعر الساعة إلى <span class="text-danger"> <?php echo $Hr_data['HourRate']+5 ?> </span>  جنيهًا في الساعة.  </p>
                    <p class="fs-6 fw-bold"> ٢- في حال تكليف الطرف الأول للطرف الثاني بتدريس بخلاف المتفق على سعر الساعة فيها يقوم الطرفين بالاتفاق على السعر العادل للساعة. </p>
                    <p class="fs-6 fw-bold"> ٣- تلتزم الأكاديمية بإرسال الراتب للمعلم خلال أول عشرة أيام من كل شهر ميلادي. ويتم إرسال الراتب على محفظة الهاتف المحمول أو الحساب البنكي في حال رغبة المعلم. </p>
                    <p class="fs-6 fw-bold"> ٤- يلتزم المعلم في حالة إقدامه على تغيير بيانات المحفظة الخاصة به بإرسال إشعار للأكاديمية قبلها بعشرة أيام. </p>
                    <p class="badge bg-primary fs-6 fw-bold"> (البند السادس) </p>
                    <p class="fs-6 fw-bold"> يتعهد المعلم بأن يؤدي العمل المنوط به بنفسه بدقة وأمانة وأن يتواجد في الموعد المحدد للعمل وينتظر الطلاب، وأن يحافظ على مواعيد العمل ويتبع النظم واللوائح المنظمة لسير العمل. </p>
                    <p class="badge bg-primary fs-6 fw-bold"> (البند السابع) </p>
                    <p class="fs-6 fw-bold"> يقر المعلم أنه على دراية تامة بطبيعة عمله كمعلم قرآن اون لاين والمتطلبات اللازمة للقيام بهذا العمل ولابد من توافر هذه المتطلبات (جهاز حاسب آلي بكاميرا، مايك صوت جيد، اتصال جيد بالإنترنت، وسائل بديلة في حالة انقطاع التيار الكهربائي، توافر مناخ جيد وهادئ لأداء الحلقات) </p>
                    <p class="badge bg-primary fs-6 fw-bold"> (البند الثامن) </p>
                    <p class="fs-6 fw-bold"> يقر المعلم بصحة المستندات ومسوغات التعيين المقدمة منه ويعتبر مسئولًا عنها مسؤولية شخصية. </p>
                    <p class="badge bg-primary fs-6 fw-bold"> (البند التاسع) </p>
                    <p class="fs-6 fw-bold"> تم توقيع هذا العقد إلكترونيا ويحق للطرف الثانى الأطلاع عليه اى وقت او الأحتفاظ بنسخة منه. </p>
                  </div>
                  <hr>
                  <div class="d-flex align-items-start">
                    <div class="text-center m-auto mt-0 overflow-hidden w-100" >

                      <p class="fs-6 fw-bold"> توقيع الطرف الثاني "المعلم"</p>

                      <div id="canvasdiv">

                        <canvas id="canvas" class="  border border-dark"></canvas>

                        <div>
                        <button class="btn btn-outline-primary" onclick="javascript:save();return false;">حفظ</button>
                        <button class="btn btn-outline-primary" onclick="javascript:clearArea();return false;">مسح</button>
                        </div>

                        <div class="d-none">
                        Line width : <select id="selWidth">
                        <option value="4">4</option>
                        </select>
                        Color : <select id="selColor">
                        <option value="black" selected="selected">black</option>
                        </select>
                        </div>

                      </div>
                      <img id="canvasimg" class="m-auto">

                    </div>
                  </div>
                  <hr>
                  <p class="fw-bold rtl"> يعد توقيعك على هذا العقد اقرار منك انك توافق على كل بنود العقد وعلى شروط وأحكام الأكاديمية. </p>
                </div>
                <!-- ------------------------------------------------ -->



                <div  id="canvassend" style="display: none;">
                <form id="submit"action="" method="post">
                  <input id="Textinput"  type="hidden" name="Text" value=''>
                  <input id="canvasinput" type="hidden" name="data" value="">
                  <input   type="hidden" name="IDNumber" value="<?php echo $Hr_data['IDNumber'] ?>">
                  <button class="btn btn-outline-primary " type="submit" >إرسال العقد</button>
                  <button class="btn btn-outline-primary" onclick="javascript:Retry();return false;">إعادة التوقيع</button>
                </form>
                </div>

              </div>
            </div>
          </div>
        <?php }elseif ($Hr_data['Contract'] == 2) { ?>
          <div class="col-lg-9 m-auto">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title text-center"> </h5>
              <!-- ------------------------------------------------ -->
                <div class="bg-white p-3" style="background-image: url(assets/img/logo-op-sm.png); background-repeat: no-repeat; background-position: center; background-size: contain;">
                  <h5 class="card-title text-center">عقد توظيف معلم</h5>
                  <?php echo $Hr_data['Text'] ?>
                  <hr>
                  <div class="d-flex align-items-start">
                    <div class="text-center m-auto mt-0 overflow-hidden w-50" >
                      <p class="fs-6 fw-bold"> توقيع الطرف الأول "الأكاديمية"</p>
                      <img  src = "assets/img/Signature.png" class="w-50">
                    </div>
                    <div class="text-center m-auto mt-0 overflow-hidden w-50" >
                      <p class="fs-6 fw-bold"> توقيع الطرف الثاني "المعلم"</p>
                      <img id="Contractimg" src = "assets/img/Contracts/<?php echo $Hr_data['IDNumber'] ?>.png" class="w-50">
                    </div>
                  </div>
                  <hr>
                  <p class="fw-bold rtl"> يعد توقيعك على هذا العقد اقرار منك انك توافق على كل بنود العقد وعلى شروط وأحكام الأكاديمية. </p>
                </div>
              <!-- ------------------------------------------------ -->
            </div>
          </div>
        </div>
        <?php }else { ?>
          <div class="col-lg-9 m-auto">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title text-center"> </h5>
                <div class="alert alert-primary text-center" role="alert">
                  لايوجد عقد متاح لحضرتك
                </div>
              </div>
            </div>
          </div>
        <?php } ?>

      <?php }else { ?>
        <div class="col-lg-9 m-auto">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title text-center"> </h5>
              <div class="alert alert-primary text-center" role="alert">
                لايوجد عقد لحضرتك
              </div>
            </div>
          </div>
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

      </section>
    </div>


  </main><!-- End #main -->
  <script src="https://github.com/niklasvh/html2canvas/releases/download/0.5.0-alpha1/html2canvas.js"></script>
  <script src="assets/js/html2canvas.js"></script>
  <?php if ($Hr_data_rowCount > 0 AND $Hr_data['Contract'] == 1){ ?>
  <script>
    const canvas = document.getElementById('canvas');
    const context = canvas.getContext('2d');
    let isDrawing = false;
    let x = 0;
    let y = 0;
    var offsetX;
    var offsetY;

    function startup() {
      canvas.addEventListener('touchstart', handleStart);
      canvas.addEventListener('touchend', handleEnd);
      canvas.addEventListener('touchcancel', handleCancel);
      canvas.addEventListener('touchmove', handleMove);
      canvas.addEventListener('mousedown', (e) => {
        x = e.offsetX;
        y = e.offsetY;
        isDrawing = true;
      });

      canvas.addEventListener('mousemove', (e) => {
        if (isDrawing) {
          drawLine(context, x, y, e.offsetX, e.offsetY);
          x = e.offsetX;
          y = e.offsetY;
        }
      });

      canvas.addEventListener('mouseup', (e) => {
        if (isDrawing) {
          drawLine(context, x, y, e.offsetX, e.offsetY);
          x = 0;
          y = 0;
          isDrawing = false;
        }
      });
    }

    document.addEventListener("DOMContentLoaded", startup);

    const ongoingTouches = [];

    function handleStart(evt) {
      evt.preventDefault();
      const touches = evt.changedTouches;
      offsetX = canvas.getBoundingClientRect().left;
      offsetY = canvas.getBoundingClientRect().top;
      for (let i = 0; i < touches.length; i++) {
        ongoingTouches.push(copyTouch(touches[i]));
      }
    }

    function handleMove(evt) {
      evt.preventDefault();
      const touches = evt.changedTouches;
      for (let i = 0; i < touches.length; i++) {
        const color = document.getElementById('selColor').value;
        const idx = ongoingTouchIndexById(touches[i].identifier);
        if (idx >= 0) {
          context.beginPath();
          context.moveTo(ongoingTouches[idx].clientX - offsetX, ongoingTouches[idx].clientY - offsetY);
          context.lineTo(touches[i].clientX - offsetX, touches[i].clientY - offsetY);
          context.lineWidth = document.getElementById('selWidth').value;
          context.strokeStyle = color;
          context.lineJoin = "round";
          context.closePath();
          context.stroke();
          ongoingTouches.splice(idx, 1, copyTouch(touches[i]));  // swap in the new touch record
        }
      }
    }

    function handleEnd(evt) {
      evt.preventDefault();
      const touches = evt.changedTouches;
      for (let i = 0; i < touches.length; i++) {
        const color = document.getElementById('selColor').value;
        let idx = ongoingTouchIndexById(touches[i].identifier);
        if (idx >= 0) {
          context.lineWidth = document.getElementById('selWidth').value;
          context.fillStyle = color;
          ongoingTouches.splice(idx, 1);  // remove it; we're done
        }
      }
    }

    function handleCancel(evt) {
      evt.preventDefault();
      const touches = evt.changedTouches;
      for (let i = 0; i < touches.length; i++) {
        let idx = ongoingTouchIndexById(touches[i].identifier);
        ongoingTouches.splice(idx, 1);  // remove it; we're done
      }
    }

    function copyTouch({ identifier, clientX, clientY }) {
      return { identifier, clientX, clientY };
    }

    function ongoingTouchIndexById(idToFind) {
      for (let i = 0; i < ongoingTouches.length; i++) {
        const id = ongoingTouches[i].identifier;
        if (id === idToFind) {
          return i;
        }
      }
      return -1;    // not found
    }

    function drawLine(context, x1, y1, x2, y2) {
      context.beginPath();
      context.strokeStyle = document.getElementById('selColor').value;
      context.lineWidth = document.getElementById('selWidth').value;
      context.lineJoin = "round";
      context.moveTo(x1, y1);
      context.lineTo(x2, y2);
      context.closePath();
      context.stroke();
    }

    function clearArea() {
        context.setTransform(1, 0, 0, 1, 0, 0);
        context.clearRect(0, 0, context.canvas.width, context.canvas.height);
    }
  </script>
  <script >
    function save() {
    var canvas = document.getElementById('canvas');
    var dataURL = canvas.toDataURL();
    document.getElementById("canvasimg").style.display = "block";
    document.getElementById("canvasimg").src = dataURL;
    document.getElementById("canvasinput").value = dataURL;
    document.getElementById("canvasdiv").style.display = "none";
    document.getElementById("canvassend").style.display = "block";
    }

    function Retry() {
      document.getElementById("canvasimg").style.display = "none";
    document.getElementById("canvasdiv").style.display = "block";
    document.getElementById("canvassend").style.display = "none";

    }

    var Text = document.getElementById("Text");
    document.getElementById("Textinput").value = Text.outerHTML;

  </script>
<?php } ?>
<?php } ?>


</div>
<?php include "assets/tem/footer.php" ?>
<?php 
if (!empty($_SESSION['Emessage'])) { ?>
  <script>
    $(document).ready(function() {
        $(".toast").toast('show');
    });
</script>
<?php unset($_SESSION['Emessage']); } ?>
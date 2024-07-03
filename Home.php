<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['teacher'])) {header('location: index.php'); exit; } 
include "assets/tem/header.php" ; 
function ArabicMonth() {
  if (date("d") > 25 ) {
      $your_date = date("Y-m-26",strtotime("+1 month"));
    }else {
      $your_date = date("Y-m-26");
    }
  $months = array("Jan" => "يناير", "Feb" => "فبراير", "Mar" => "مارس", "Apr" => "أبريل", "May" => "مايو", "Jun" => "يونيو", "Jul" => "يوليو", "Aug" => "أغسطس", "Sep" => "سبتمبر", "Oct" => "أكتوبر", "Nov" => "نوفمبر", "Dec" => "ديسمبر");
  $en_month = date("M", strtotime($your_date));
  foreach ($months as $en => $ar) {
      if ($en == $en_month) { $ar_month = $ar; }
  }

  $find = array ("Sat", "Sun", "Mon", "Tue", "Wed" , "Thu", "Fri");
  $replace = array ("السبت", "الأحد", "الإثنين", "الثلاثاء", "الأربعاء", "الخميس", "الجمعة");
  $ar_day_format = date('D'); // The Current Day
  $ar_day = str_replace($find, $replace, $ar_day_format);

  header('Content-Type: text/html; charset=utf-8');
  $standard = array("0","1","2","3","4","5","6","7","8","9");
  $eastern_arabic_symbols = array("٠","١","٢","٣","٤","٥","٦","٧","٨","٩");
  $arabic_date = $ar_month;

  return $arabic_date;
}
$sessionteacher=[
  "T40", 
  "T41",
  "T42",
  "T43",
  "T44",
  "T45",
  "T46",
  "T47",
  "T48",
  "T49",
  "T50",
  "T51",
  "T52",
  "T53",
  "T54",
  "T55",
  "T56",
  "T57",
  "T58",
  "T59",
  "T60",
  "T61",
  "T62",
  "T63",
  "T64",
  "T65",
  "T66",
  "T67"
];
if (in_array($_SESSION['teacher'],$sessionteacher)){

  if (date("d") > 25 ) {
    $this_month = date("Y-m-26");
  }else {
    $this_month = date("Y-m-26",strtotime("-1 month"));
  }
  $session_code = $_SESSION['teacher'] ;
  $plus_code_stmt = $con->prepare("SELECT  SUM(Mark) FROM evaluation WHERE Code=? AND `Status`=? AND `Date`>=?  AND `Approval` =? ");
  $plus_code_stmt->execute(array($session_code,"+",$this_month,0));
  $plus = $plus_code_stmt->fetch();
  
  $minus_code_stmt = $con->prepare("SELECT  SUM(Mark) FROM evaluation WHERE Code=? AND `Status`=? AND `Date`>=?  AND `Approval` =? ");
  $minus_code_stmt->execute(array($session_code,"-",$this_month,0));
  $minus = $minus_code_stmt->fetch();
  
  $evaluation_1 = 1000 ;
  $mark = $evaluation_1+$plus['SUM(Mark)']-$minus['SUM(Mark)'];
  $total = number_format(($mark / $evaluation_1) * 100 , 0);
  if ($mark > 1000) {
    $color1 =  '#198754';
    $color2 =  '#18b16adb';
  }else {
    $color1 =  '#a01414';
    $color2 =  '#dc3545bf';
  }
}
if (isset($_COOKIE['country']) && isset($_COOKIE['city'])) {
  $country = $_COOKIE['country'];
  $city = $_COOKIE['city'];
}else {
  $country = 'Egypt' ;
  $city = 'Alexandria' ;
  $hour = time() + 3600 * 24 * 30 * 12;
  setcookie('country', $country, $hour);
  setcookie('city', $city, $hour);
}

if (isset($_GET['country']) && isset($_GET['city']) ) {
  $country = $_GET['country'];
  $city = $_GET['city'];
  setcookie('country', $country);
  setcookie('city', $city);
}
if (isset($_COOKIE['method'])) {
  $method = $_COOKIE['method'];
}else {
  $method = 5 ;
  $hour = time() + 3600 * 24 * 30 * 12;
  setcookie('method', $method, $hour);
}

if (isset($_GET['method']) ) {
  $method = $_GET['method'];
  setcookie('method', $method);
}

?>
<main id="main" class="main">
  <div class="container">
    <div class="pagetitle">
      <h1>الرئيسية</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">الرئيسية</a></li>
          <li class="breadcrumb-item active">تسجيل الحلقات</li>
          <li class="breadcrumb-item active" id="date_li"></li>
        </ol>
      </nav>
    </div><!-- End Page Title -->





    <section class="section Home">

    <div  class="row">
      <div class="col-sm-12 mt-3">
        <div class=" card h-100">
          <div class=" recent-sales">

          <!-- Button trigger modal -->


          <!-- Modal -->
          <div class="modal fade" id="method" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="methodLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="methodLabel"> اعداد المواقيت </h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <ul class="nav nav-tabs">

                    <li class="nav-item dropdown">
                    <a href="#" class="btn btn-outline-primary"aria-expanded="false" role="button" data-bs-toggle="dropdown"><b>طريقة حساب المواقيت</b></a>
                    <ul class="dropdown-menu" id="Time">

                    </ul>
                    </li>
                    <li class="nav-item dropdown">
                      <a class="btn btn-outline-primary" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                      <b> اختر الموقع </b>
                      </a>
                      
                      <div class="collapse" id="collapseExample">
                        <ul class="nav nav-tabs">
                        
                          <li class="nav-item dropdown">
                          <a href="#" class="nav-link dropdown-toggle"aria-expanded="false" role="button" data-bs-toggle="dropdown"><b>Africa</b></a>
                          <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?city=Algiers&country=Algeria">Algiers, Algeria</a></li>
                            <li><a class="dropdown-item" href="?city=Capetown&country=South Africa">Capetown, South Africa</a></li>
                            <li><a class="dropdown-item" href="?city=Casablanca&country=Morocco">Casablanca, Morocco</a></li>
                            <li><a class="dropdown-item" href="?city=Fez&country=Morocco">Fez, Morocco</a></li>
                            <li><a class="dropdown-item" href="?city=Johannesburg&country=South Africa">Johannesburg, South Africa</a></li>
                            <li><a class="dropdown-item" href="?city=Nigeria&country=Lagos">Lagos, Nigeria</a></li>
                            <li><a class="dropdown-item" href="?city=Marrakech&country=Morocco">Marrakech, Morocco</a></li>
                            <li><a class="dropdown-item" href="?city=Rabat&country=Morocco">Rabat, Morocco</a></li>
                            <li><a class="dropdown-item" href="?city=Tunis&country=Tunisia">Tunis, Tunisia</a></li>
                          </ul>
                          </li>
                        
                          <li class="nav-item dropdown">
                          <a href="#" class="nav-link dropdown-toggle"aria-expanded="false" role="button" data-bs-toggle="dropdown"><b>Asia</b></a>
                          <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?city=Astana&country=Kazakhstan">Astana, Kazakhstan</a></li>
                            <li><a class="dropdown-item" href="?city=Beijing&country=China">Beijing, China</a></li>
                            <li><a class="dropdown-item" href="?city=Chennai&country=India">Chennai, India</a></li>
                            <li><a class="dropdown-item" href="?city=Colombo&country=Sri Lanka">Colombo, Sri Lanka</a></li>
                            <li><a class="dropdown-item" href="?city=Dhaka&country=Bangladesh">Dhaka, Bangladesh</a></li>
                            <li><a class="dropdown-item" href="?city=Hong Kong&country=China">Hong Kong, China</a></li>
                            <li><a class="dropdown-item" href="?city=Islamabad&country=Pakistan">Islamabad, Pakistan</a></li>
                            <li><a class="dropdown-item" href="?city=Jakarta&country=Indonesia">Jakarta, Indonesia</a></li>
                            <li><a class="dropdown-item" href="?city=Kabul&country=Afghanistan">Kabul, Afghanistan</a></li>
                            <li><a class="dropdown-item" href="?city=Karachi&country=Pakistan">Karachi, Pakistan</a></li>
                            <li><a class="dropdown-item" href="?city=Lahore&country=Pakistan">Lahore, Pakistan</a></li>
                            <li><a class="dropdown-item" href="?city=Makhachkala&country=Dagestan">Makhachkala, Dagestan</a></li>
                            <li><a class="dropdown-item" href="?city=Mumbai&country=India">Mumbai, India</a></li>
                            <li><a class="dropdown-item" href="?city=New Delhi&country=India">New Dellhi, India</a></li>
                            <li><a class="dropdown-item" href="?city=Samarkand&country=Uzbekistan">Samarkand, Uzbekistan</a></li>
                            <li><a class="dropdown-item" href="?city=Seoul&country=South Korea">Seoul, South Korea</a></li>
                            <li><a class="dropdown-item" href="?city=Shanghai&country=China">Shanghai, China</a></li>
                            <li><a class="dropdown-item" href="?city=Singapore&country=Asia">Singapore</a></li>
                            <li><a class="dropdown-item" href="?city=Taipei&country=Taiwan">Taipei, Taiwan</a></li>
                            <li><a class="dropdown-item" href="?city=Tashkent&country=Uzbekistan">Tashkent, Uzbekistan</a></li>
                            <li><a class="dropdown-item" href="?city=Tokyo&country=Japan">Tokyo, Japan</a></li>
                            <li><a class="dropdown-item" href="?city=Ulaanbaatar&country=Mongolia">Ulaanbaatar, Mongolia</a></li>
                          </ul>
                          </li>
                        
                          <li class="nav-item dropdown">
                          <a href="#" class="nav-link dropdown-toggle"aria-expanded="false" role="button" data-bs-toggle="dropdown"><b>Australia</b></a>
                          <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?city=Adelaide&country=Australia">Adelaide, Australia</a></li>
                            <li><a class="dropdown-item" href="?city=Auckland&country=New Zealand">Auckland, New Zealand</a></li>
                            <li><a class="dropdown-item" href="?city=Brisbane&country=Australia">Brisbane, Australia</a></li>
                            <li><a class="dropdown-item" href="?city=Darwin&country=Australia">Darwin, Australia</a></li>
                            <li><a class="dropdown-item" href="?city=Perth&country=Australia">Perth, Australia</a></li>
                            <li><a class="dropdown-item" href="?city=Sydney&country=Australia">Sydney, Australia</a></li>
                            <li><a class="dropdown-item" href="?city=Tasmania&country=Australia">Tasmania, Australia</a></li>
                          </ul>
                          </li>
                        
                          <li class="nav-item dropdown">
                          <a href="#" class="nav-link dropdown-toggle"aria-expanded="false" role="button" data-bs-toggle="dropdown"><b>Europe</b></a>
                          <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?city=Amsterdam&country=Netherlands">Amsterdam, Netherlands</a></li>
                            <li><a class="dropdown-item" href="?city=Belfast&country=Northern Ireland">Belfast, Northern Ireland</a></li>
                            <li><a class="dropdown-item" href="?city=Berlin&country=Germany">Berlin, Germany</a></li>
                            <li><a class="dropdown-item" href="?city=Birmingham&country=UK">Birmingham, UK</a></li>
                            <li><a class="dropdown-item" href="?city=Brussels&country=Belgium">Brussels, Belgium</a></li>
                            <li><a class="dropdown-item" href="?city=Bucharest&country=Romania">Bucharest, Romania</a></li>
                            <li><a class="dropdown-item" href="?city=Budapest&country=Hungary">Budapest, Hungary</a></li>
                            <li><a class="dropdown-item" href="?city=Cordoba&country=Spain">Cordoba, Spain</a></li>
                            <li><a class="dropdown-item" href="?city=Dublin&country=Ireland">Dublin, Ireland</a></li>
                            <li><a class="dropdown-item" href="?city=Edinburgh&country=Scotland, UK">Edinburgh, UK</a></li>
                            <li><a class="dropdown-item" href="?city=Frankfurt&country=Germany">Frankfurt, Germany</a></li>
                            <li><a class="dropdown-item" href="?city=Glasgow&country=Scotland, UK">Glasgow, UK</a></li>
                            <li><a class="dropdown-item" href="?city=Helsinki&country=Finland">Helsinki, Finland</a></li>
                            <li><a class="dropdown-item" href="?city=Lisbon&country=Portugal">Lisbon, Portugal</a></li>
                            <li><a class="dropdown-item" href="?city=London&country=UK">London, UK</a></li>
                            <li><a class="dropdown-item" href="?city=Madrid&country=Spain">Madrid, Spain</a></li>
                            <li><a class="dropdown-item" href="?city=Manchester&country=UK">Manchester, UK</a></li>
                            <li><a class="dropdown-item" href="?city=Milan&country=Italy">Milan, Italy</a></li>
                            <li><a class="dropdown-item" href="?city=Moscow&country=Russia">Moscow, Russia</a></li>
                            <li><a class="dropdown-item" href="?city=Munich&country=Germany">Munich, Germany</a></li>
                            <li><a class="dropdown-item" href="?city=Naples&country=Italy">Naples, Italy</a></li>
                            <li><a class="dropdown-item" href="?city=Oslo&country=Norway">Oslo, Norway</a></li>
                            <li><a class="dropdown-item" href="?city=Paris&country=France">Paris, France</a></li>
                            <li><a class="dropdown-item" href="?city=Prague&country=Czech Republic">Prague, Czech Republic</a></li>
                            <li><a class="dropdown-item" href="?city=Pristina&country=Kosovo">Pristina, Kosovo</a></li>
                            <li><a class="dropdown-item" href="?city=Rome&country=Italy">Rome, Italy</a></li>
                            <li><a class="dropdown-item" href="?city=Sarajevo&country=Bosnia and Herzegovina">Sarajevo, Bosnia and Herzegovina</a></li>
                            <li><a class="dropdown-item" href="?city=Sofia&country=Bulgaria">Sofia, Bulgaria</a></li>
                            <li><a class="dropdown-item" href="?city=Stockholm&country=Sweden">Stockholm, Sweden</a></li>
                            <li><a class="dropdown-item" href="?city=Tirana&country=Albania">Tirana, Albania</a></li>
                            <li><a class="dropdown-item" href="?city=Valencia&country=Spain">Valencia, Spain</a></li>
                            <li><a class="dropdown-item" href="?city=Vienna&country=Austria">Vienna, Austria</a></li>
                            <li><a class="dropdown-item" href="?city=Zurich&country=Switzerland">Zurich, Switzerland</a></li>
                          </ul>
                          </li>
                        
                          <li class="nav-item dropdown">
                          <a href="#" class="nav-link dropdown-toggle"aria-expanded="false" role="button" data-bs-toggle="dropdown"><b>Middle East</b></a>
                          <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?city=Makkah&country=Saudi Arabia">Makkah, Saudi Arabia</a></li>
                            <li><a class="dropdown-item" href="?city=Madinah&country=Saudi Arabia">Madinah, Saudi Arabia</a></li>
                            <li><a class="dropdown-item" href="?city=Riyadh&country=Saudi Arabia">Riyadh, Saudi Arabia</a></li>
                            <li><a class="dropdown-item" href="?city=Dubai&country=United Arab Emirates">Dubai, UAE</a></li>
                            <li><a class="dropdown-item" href="?city=Abu Dhabi&country=United Arab Emirates">Abu Dhabi, UAE</a></li>
                            <li><a class="dropdown-item" href="?city=Sharjah&country=United Arab Emirates">Sharjah, UAE</a></li>
                            <li><a class="dropdown-item" href="?city=Ajman&country=United Arab Emirates">Ajman, UAE</a></li>
                            <li><a class="dropdown-item" href="?city=Ras Al Khaimah&country=United Arab Emirates">Ras Al Khaimah, UAE</a></li>
                            <li><a class="dropdown-item" href="?city=Umm Al Quwain&country=United Arab Emirates">Umm Al Quwain, UAE</a></li>
                            <li><a class="dropdown-item" href="?city=Muscat&country=Oman">Muscat, Oman</a></li>
                            <li><a class="dropdown-item" href="?city=Damascus&country=Syria">Damascus, Syria</a></li>
                            <li><a class="dropdown-item" href="?city=Aleppo&country=Syria">Aleppo, Syria</a></li>
                            <li><a class="dropdown-item" href="?city=Baghdad&country=Iraq">Baghdad, Iraq</a></li>
                            <li><a class="dropdown-item" href="?city=Mosul&country=Iraq">Mosul, Iraq</a></li>
                            <li><a class="dropdown-item" href="?city=Tehran&country=Iran">Tehran, Iran</a></li>
                            <li><a class="dropdown-item" href="?city=Isfahan&country=Iran">Isfahan, Iran</a></li>
                            <li><a class="dropdown-item" href="?city=Istanbul&country=Turkey">Istanbul, Turkey</a></li>
                            <li><a class="dropdown-item" href="?city=Konya&country=Turkey">Konya, Turkey</a></li>
                            <li><a class="dropdown-item" href="?city=Cairo&country=Egypt">Cairo, Egypt</a></li>
                            <li><a class="dropdown-item" href="?city=Alexandria&country=Egypt">Alexandria, Egypt</a></li>
                            <li><a class="dropdown-item" href="?city=Aden&country=Yemen">Aden, Yemen</a></li>
                            <li><a class="dropdown-item" href="?city=Sanaa&country=Yemen">Sanaa, Yemen</a></li>
                            <li><a class="dropdown-item" href="?city=Jerusalem&country=Palestine">Jerusalem, Palestine</a></li>
                          </ul>
                          </li>
                        
                          <li class="nav-item dropdown">
                          <a href="#" class="nav-link dropdown-toggle"aria-expanded="false" role="button" data-bs-toggle="dropdown"><b>North America</b></a>
                          <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?city=Chicago&country=IL, USA">Chicago, IL, USA</a></li>
                            <li><a class="dropdown-item" href="?city=Denver&country=CO, USA">Denver, CO, USA</a></li>
                            <li><a class="dropdown-item" href="?city=Edmonton&country=Canada">Edmonton, Canada</a></li>
                            <li><a class="dropdown-item" href="?city=Halifax&country=Canada">Halifax, Canada</a></li>
                            <li><a class="dropdown-item" href="?city=Havana&country=Cuba">Havana, Cuba</a></li>
                            <li><a class="dropdown-item" href="?city=Honolulu&country=Hawaii">Honolulu, Hawaii</a></li>
                            <li><a class="dropdown-item" href="?city=Houston&country=TX, USA">Houston, TX, USA</a></li>
                            <li><a class="dropdown-item" href="?city=Los Angeles&country=CA, USA">Los Angeles, CA, USA</a></li>
                            <li><a class="dropdown-item" href="?city=Montreal&country=Canada">Montreal, Canada</a></li>
                            <li><a class="dropdown-item" href="?city=New York&country=NY, USA">New York, NY, USA</a></li>
                            <li><a class="dropdown-item" href="?city=Regina&country=Canada">Regina, Canada</a></li>
                            <li><a class="dropdown-item" href="?city=Toronto&country=Canada">Toronto, Canada</a></li>
                            <li><a class="dropdown-item" href="?city=Vancouver&country=Canada">Vancouver, Canada</a></li>
                          </ul>
                          </li>
                        
                          <li class="nav-item dropdown">
                          <a href="#" class="nav-link dropdown-toggle"aria-expanded="false" role="button" data-bs-toggle="dropdown"><b>South America</b></a>
                          <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?city=Buenos Aires&country=Argentina">Buenos Aires, Argentina</a></li>
                            <li><a class="dropdown-item" href="?city=Caracas&country=Venezuela">Caracas, Venezuela</a></li>
                            <li><a class="dropdown-item" href="?city=Lima&country=Peru">Lima, Peru</a></li>
                            <li><a class="dropdown-item" href="?city=Mexico City&country=Mexico">Mexico City, Mexico</a></li>
                            <li><a class="dropdown-item" href="?city=Santiago&country=Chile">Santiago, Chile</a></li>
                            <li><a class="dropdown-item" href="?city=Sao Paulo&country=Brazil">Sao Paulo, Brazil</a></li>
                          </ul>
                          </li>
                        
                        </ul>
                      </div>
                    </li>


                  </ul>
                  <p> الموقع الحالى | <?php echo $country ?> - <?php echo $city ?></p>
                  <p> طريقة الحساب الحالية | <?php echo " رقم ".$method ?></p>

                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>




            <h5 class="card-title text-center" style="font-size: 31px; background-color: #012970; color: #ffffff; border-radius: 10px;">
              <button style="font-size: 30px;color: #ffffff;" type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#method"><i class="ri-settings-2-line"></i></button>
              <span style="font-size: 31px; background-color: #012970; color: #ffffff; border-radius: 10px;"> مواقيت الصلاة </span>
            </h5>
            <div class="card-body  text-center">
            <div id='PrayerTimes' class="row rtl">
              <div class="Loading justify-content-center" >
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
                </div>
              </div>
            </div>
              <div class="rtl"> 
              <h5 class="card-title text-center"> ﴿إِنَّ الصَّلَاةَ كَانَتْ عَلَى الْمُؤْمِنِينَ كِتَابًا مَوْقُوتًا﴾ </h5>
              <p> هذة المواعيد استرشادية تقريبية يرجى عدم الاعتماد عليها وتحرى الدقة </p>
              </div>
            </div>
          </div>
        </div>
      </div>


      <?php if (in_array($_SESSION['teacher'],$sessionteacher)){ ?>
        <div class="col-lg-6 mt-3 m-auto">
          <div class=" card  h-100 ">
            <div class=" recent-sales">
              <h5 class="card-title text-center">  تقيمك لشهر  <?php echo ArabicMonth();?> | <?php echo $mark." "." نقطة ";?> </h5>
              <div class="card-body  text-center">
                <div> 
                  <p> نظام التقييم حاليا هو نظام تجريبي ولا يترتب عليه شئ </p>
                  <a class="btn btn-outline-primary fs-6 fw-bold w-50 border-0 mb-2 w-100"  href="EvaluationTeachersHome">الإطلاع على التقييم </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php }?>
    </div>

      <div id="myGroup" class="row mt-3">



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


</main><!-- End #main -->
<script>
Classes();
PrayerTimes();
methods();
var Interval = setInterval(IntervalClasses, 90000);

function IntervalClasses() { if($("input,textarea").is(":focus")){ }else{Classes();} }
function PrayerTimes() {
  $.ajax({ 
    type      : 'GET', 
    url       : 'https://api.aladhan.com/v1/timingsByCity/<?php echo date("d-m-Y") ?>',
    data      : {
    city : '<?php echo $city ?>',
    country : '<?php echo $country ?>',
    method : '<?php echo $method ?>',
    // Imsak,Fajr,Sunrise,Dhuhr,Asr,Maghrib,Sunset,Isha,Midnight
    tune: "0,0,0,0,0,0,0,0,0"
    },
    cache  : false,
    success   : function(PrayerTimes) {
      
    let content = PrayerTimes.data
    console.log(content)
    if ( PrayerTimes.status == "OK") {
      $('#PrayerTimes').html('')
      let Fajr = PrayerTimes.data.timings.Fajr
      let Sunrise = PrayerTimes.data.timings.Sunrise
      let Dhuhr = PrayerTimes.data.timings.Dhuhr
      let Asr = PrayerTimes.data.timings.Asr
      let Maghrib = PrayerTimes.data.timings.Maghrib
      let Isha = PrayerTimes.data.timings.Isha
      let hijri_weekday = PrayerTimes.data.date.hijri.weekday.ar
      let hijri_month = PrayerTimes.data.date.hijri.month.ar
      let hijri_year = PrayerTimes.data.date.hijri.year
      let hijri_day = PrayerTimes.data.date.hijri.day
      let gregorian_year = PrayerTimes.data.date.gregorian.year
          

          var  PrayerTimes =`
                <h5 class="card-title text-center"> ${hijri_weekday} ${hijri_day} من ${hijri_month} ${hijri_year} </h5>
                <div class="row justify-content-center m-auto">

                <p class="col-lg-4 col-sm-6 text-end card-details mb-0 d-flex btn btn-outline-primary disabled ms-2 my-2"> <i class="ri-vip-diamond-line"></i> <span class="w-50"> &nbsp; الفجر:- &nbsp; </span> <span class="w-50"> ${Fajr} </span> </p>
                <p class="col-lg-4 col-sm-6 text-end card-details mb-0 d-flex btn btn-outline-primary disabled ms-2 my-2"> <i class="ri-vip-diamond-line"></i> <span class="w-50"> &nbsp; الشروق:- &nbsp; </span> <span class="w-50"> ${Sunrise} </span> </p>
                <p class="col-lg-4 col-sm-6 text-end card-details mb-0 d-flex btn btn-outline-primary disabled ms-2 my-2"> <i class="ri-vip-diamond-line"></i> <span class="w-50"> &nbsp; الظهر:- &nbsp; </span> <span class="w-50"> ${Dhuhr} </span> </p>
                <p class="col-lg-4 col-sm-6 text-end card-details mb-0 d-flex btn btn-outline-primary disabled ms-2 my-2"> <i class="ri-vip-diamond-line"></i> <span class="w-50"> &nbsp; العصر:- &nbsp; </span> <span class="w-50"> ${Asr} </span> </p>
                <p class="col-lg-4 col-sm-6 text-end card-details mb-0 d-flex btn btn-outline-primary disabled ms-2 my-2"> <i class="ri-vip-diamond-line"></i> <span class="w-50"> &nbsp; المغرب:- &nbsp; </span> <span class="w-50"> ${Maghrib} </span> </p>
                <p class="col-lg-4 col-sm-6 text-end card-details mb-0 d-flex btn btn-outline-primary disabled ms-2 my-2"> <i class="ri-vip-diamond-line"></i> <span class="w-50"> &nbsp; العشاء:- &nbsp; </span> <span class="w-50"> ${Isha} </span> </p>
                
                </div>
          `;

        $('#PrayerTimes').append(PrayerTimes)

    }else{
      $('#PrayerTimes').html('')
      let  PrayerTimes =`
          <div class="alert alert-danger" role="alert"  style=" width: 100%;" >
              خطأ فى تحميل البيانات
          </div>
      `;
      $('#PrayerTimes').append(PrayerTimes)

    }

    }
  });
}
function methods() {
  $.ajax({ 
    type      : 'GET', 
    url       : 'https://api.aladhan.com/v1/methods',
    data      : {
    },
    cache  : false,
    success   : function(methods) {
      var Data = JSON.stringify(methods)
      var Data = JSON.parse(Data)
      let content = Data.data

      for (const key of Object.keys(content)) {
          const idval = content[key].id;
          const nameval = content[key].name;
          var  Timeappend =`
            <li><a class="dropdown-item" href="?method=${idval}"> ${idval} رقم | ${nameval}</a></li>
                `;

              $('#Time').append(Timeappend)

      }
     
     
    }
  });
}
function Classes() {
  $('#loader-contener').show();
  $.ajax({ 
    type      : 'GET', 
    url       : 'ClassesApi.php',
    data      : {
    NeededCode : '<?php echo $_SESSION['teacher'] ?>'
    },
    cache  : false,
    success   : function(Data) {

    var Data = JSON.parse(Data)
    let content = Data
    console.log(content);

    if (content[0].Total > 0) {
      $('#myGroup').html('')
      for (let i = 1; i < content.length; i++) {
        let Time = content[i].Time
        let Lastabsence = content[i].Lastabsence
        let absence = content[i].absence
        let ID = content[i].ID
        let Teacher = content[i].Teacher
        let Name = content[i].Name
        let Student = content[i].Student
        let Section = content[i].Section
        let Duration = content[i].Duration
        let Nots = content[i].Nots
        let Admin = content[i].Admin
        let Nots_id = content[i].Nots_id
        let StudentName = content[i].StudentName
        let Code = content[i].Code
        let MeetingID = content[i].MeetingID
        let Type = content[i].Type
        let category = content[i].category
        let Cancel = content[i].Cancel
        let status= `حضور`
        let date = content[i].Meetingdate
        if (Admin > 0) { var btn =  "سجل مشرف الحلقة" ; }else{ var btn =  "تم  تسجيل الحلقة" ; }
        if (Nots > 0) {
          var  Meeting =`
          <div class="col-lg-4 m-auto">
          <div class="card">
            <img src="assets/img/${category}.png" class="card-img-top" alt="...">
          <div class="card-body rtl">
          <p class="card-details mt-2 mb-0"> <i class="ri-time-line"></i>  &nbsp; الموعد:- &nbsp;${Time}</p>
          <p class="card-details mb-0"> <i class="ri-time-line"></i>  &nbsp; الكود:- &nbsp;${Student}</p>
          <p class="card-details mb-0"> <i class="ri-time-line"></i>  &nbsp; الاسم:- &nbsp; ${Name} </p>
          <p class="card-details mb-0"> <i class="ri-time-line"></i>  &nbsp; المدة:- &nbsp; ${Duration} </p>
          <button class="btn btn-success fs-6 fw-bold w-100" disabled > ${btn} </button>
          </div>
          </div>
          </div>
          `;
        }else if( Cancel > 0 ){
          var  Meeting =`
          <div class="col-lg-4 m-auto">
          <div class="card">
            <img src="assets/img/${category}.png" class="card-img-top" alt="...">
          <div class="card-body rtl">
          <p class="card-details mt-2 mb-0"> <i class="ri-time-line"></i>  &nbsp; الموعد:- &nbsp;${Time}</p>
          <p class="card-details mb-0"> <i class="ri-time-line"></i>  &nbsp; الكود:- &nbsp;${Student}</p>
          <p class="card-details mb-0"> <i class="ri-time-line"></i>  &nbsp; الاسم:- &nbsp; ${Name} </p>
          <p class="card-details mb-0"> <i class="ri-time-line"></i>  &nbsp; المدة:- &nbsp; ${Duration} </p>
          <button class="btn btn-success fs-6 fw-bold w-100" disabled >  تم الغاء الحلقة  </button>
          </div>
          </div>
          </div>
          `;
        }else{
          var  Meeting =`
          <div class="col-lg-4 m-auto">
          <div class="card">
          <img id="img" src="assets/img/${category}.png" class="card-img-top" alt="...">
          <div id="img" class="card-body rtl">
          <p class="card-details mt-2 mb-0"> <i class="ri-time-line"></i>  &nbsp; الموعد:- &nbsp;${Time}</p>
          <p class="card-details mb-0"> <i class="ri-time-line"></i>  &nbsp; الكود:- &nbsp;${Student}</p>
          <p class="card-details mb-0"> <i class="ri-time-line"></i>  &nbsp; الاسم:- &nbsp; ${Name} </p>
          <p class="card-details mb-0"> <i class="ri-time-line"></i>  &nbsp; المدة:- &nbsp; ${Duration} </p>

          <div id="Aa${Section}">
          <button class="btn btn-outline-primary fs-6 fw-bold w-100" type="button" data-bs-toggle="collapse" data-bs-target="#${Section}" aria-expanded="false" aria-controls="${Section}">
          تسجيل الحضور
          </button>
          <div class="collapse" id="${Section}" data-bs-parent="#myGroup">
            <textarea  name="nots" id="nots${Section}" value='' rows="1" cols="25" class="form-control" required placeholder="اكتب تقرير الحلقه"></textarea>
            <div id="invalid${Section}" class="invalid-feedback"> اكتب تقرير الحلقة. </div>
            <button type="submit" class="btn btn-outline-primary w-100 fs-6 fw-bold mt-2" onclick="Send('${Section}','${Code}','${category}','${StudentName}','${Duration}','${Teacher}','${date}','${ID}','${status}')">تأكيد</button>
          </div>
          </div>
          <div id="Cc${Section}"  class="Loading justify-content-center" style="display: none; ">
          <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
          <span class="visually-hidden">Loading...</span>
          </div>
          </div>
          <button type="button"id="Dd${Section}" onclick="Cancel('${Section}','${MeetingID}','${date}')" class="btn btn-outline-primary w-100 fs-6 fw-bold mt-2">الغاء الحلقة</button>



          </div>
          </div>
          </div>
          `;
        }

        $('#myGroup').append(Meeting)
        $('#loader-contener').hide();
      }



    }else{
      $('#myGroup').html('')
      let  Meeting =`
      <div class="form-group row shadow p-3  bg-body rounded" style=" margin: auto;">
          <div class="alert alert-danger" role="alert"  style=" width: 100%;" >
              لا توجد حلقات اليوم
          </div>
      </div>
      `;
      $('#myGroup').append(Meeting)

    }
    }
  });
}

function Send(Section,code,category,name,Duration,t_code,date,id,status) {
  var nots = document.getElementById('nots'+Section)
  var invalid = document.getElementById('invalid'+Section)
  if (nots.value.length != 0) {
    clearInterval(Interval);
    document.getElementById('Aa'+Section).style.setProperty ("display" ,"none", "important");
    document.getElementById(Section).style.setProperty ("display" ,"none", "important");
    document.getElementById('Dd'+Section).style.setProperty ("display" ,"none", "important");  
    document.getElementById('Cc'+Section).style.setProperty ("display" ,"flex", "important"); 
    $('#loader-contener').show(); 
    $.ajax({ 
      type      : 'post', 
      url       : 'add-to-db.php', 
      data      : {
      code : code,
      category : category,
      name : name,
      Duration : Duration,
      t_code : t_code,
      status : status,
      date : date,
      nots : nots.value,
      id : id
      },
      cache  : false,
      success   : function(Data) {
      Classes();
      var Interval = setInterval(IntervalClasses, 90000);
      }
    })

  }else{
    invalid.style.setProperty ("display" ,"block", "important");
  }
}
function Cancel(Section,Cancel,date) {
  if (confirm('هل انت متأكد من الألغاء ؟ \n قم بألغاء الحصة اذا أجل الطالب الموعد او اعتذرت انت للطالب') == true) {
      clearInterval(Interval);
      document.getElementById('Aa'+Section).style.setProperty ("display" ,"none", "important");
      document.getElementById('Dd'+Section).style.setProperty ("display" ,"none", "important");
      document.getElementById('Cc'+Section).style.setProperty ("display" ,"flex", "important");
      $('#loader-contener').show();
      $.ajax({ 
        type      : 'post', 
        url       : 'add-to-db.php', 
        data      : {
        Cancel : Cancel,
        date : date
        },
        cache  : false,
        success   : function(Data) {
        Classes();
        var Interval = setInterval(IntervalClasses, 90000);
        }
      })
  } else {
      return false;
  }
} 

var span = document.getElementById('date_li');



function time() {
  var d = new Date();
  var s = d.getSeconds();
  var m = d.getMinutes();
  var h = d.getHours();
  var timeContent = ("0" + h).substr(-2) + ":" + ("0" + m).substr(-2) + ":" + ("0" + s).substr(-2);
  var todayDate = new Date().toLocaleDateString('en-CA');
  span.textContent  = todayDate+" "+timeContent ;
}

setInterval(time, 1000);
</script>
<?php include "assets/tem/footer.php" ;?>
<?php 
if (!empty($_SESSION['Emessage'])) { ?>
  <script>
    $(document).ready(function() {
        $(".toast").toast('show');
    });
</script>
<?php unset($_SESSION['Emessage']); } ?>




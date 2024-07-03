<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['Admin']) AND !isset($_SESSION['Suber_Admin']) AND !isset($_SESSION['manager'])) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ;
include "HomeApi.php" ;
if (isset($_GET['All'])) {
    $Acount = count($Meetings);
    if (isset(array_count_values(array_column($Meetings, 'Nots'))['1'])) {
        $Bcount = array_count_values(array_column($Meetings, 'Nots'))['1'];
    }else {
        $Bcount = 0 ;
    }
    if (isset(array_count_values(array_column($Meetings, 'Nots'))['0'])) {
        $Ccount = array_count_values(array_column($Meetings, 'Nots'))['0'];
    }else {
        $Ccount = 0 ;
    }
    if (isset(array_count_values(array_column($Meetings, 'Cancel'))['1'])) {
        $Dcount = array_count_values(array_column($Meetings, 'Cancel'))['1'];
    }else {
        $Dcount = 0 ;
    }
    if (isset(array_count_values(array_column($Meetings, 'Type'))['2'])) {
        $Ecount = array_count_values(array_column($Meetings, 'Type'))['2'];
    }else {
        $Ecount = 0 ;
    }
    if (isset(array_count_values(array_column($Meetings, 'Type'))['1'])) {
        $Fcount = array_count_values(array_column($Meetings, 'Type'))['1'];
    }else {
        $Fcount = 0 ;
    }
 

}
?>
<main id="main" class="main">
  <div class="container">
    <div class="pagetitle">
      <h1>الرئيسية</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index">الرئيسية</a></li>
          <li class="breadcrumb-item active">ادارة الحلقات</li>
          <li class="breadcrumb-item active"> <?php if (!isset($teacher) ) {$teacher = " " ; } echo $teacher  ; ?></li>
          <li class="breadcrumb-item active"> <?php echo $day_now ; ?></li>
          <li class="breadcrumb-item active"> <?php
          if (date("d") > 25 ) {
            $current_month = date("Y-m-26");
            $pre_current_month = date("Y-m-26",strtotime("-1 month"));
            $Next_month = date("Y-m-25",strtotime("+1 month"));
          }else {
            $current_month = date("Y-m-26",strtotime("-1 month"));
            $pre_current_month = date("Y-m-26",strtotime("-2 month"));
            $Next_month = date("Y-m-25");
          }
          if ($date_now < $current_month) {
            if ($date_now >= $pre_current_month) {
              if (date("d") < 28 && date("d") >= 26 ) {
                echo $date_now ;
                $Allow = 1;
              }else {
                echo $date_now ;
                echo "<br/>";
                ?> 
                  <div class=" d-flex flex-column justify-content-center align-items-center">
                    <h2 class=" text-danger"> لا يمكن تسجيل حصص تم تقفيل هذا الشهر </h2>  
                    <h2> يمكنك تقديم طلب للتسجيل </h2> 
                  </div>
                <?php
              }
            }else {
              echo $date_now ;
              echo "<br/>";
              ?> 
                <div class=" d-flex flex-column justify-content-center align-items-center">
                  <h2 class=" text-danger"> لا يمكن تسجيل حصص تم تقفيل هذا الشهر </h2>  
                  <h2> يمكنك تقديم طلب للتسجيل </h2> 
                </div>
              <?php
            }
          }elseif ($date_now <= $Next_month && $date_now >= $current_month) {
            echo $date_now ;
            $Allow = 1;
          }else {
            echo $date_now ;
            echo "<br/>";
            ?> 
              <div class=" d-flex flex-column justify-content-center align-items-center">
                <h2 class=" text-danger"> لا يمكن تسجيل حصص هذا الشهر لم يأتى بعد </h2>  
                <h2> يمكنك تقديم طلب للتسجيل </h2> 
              </div>
            <?php
          }
    ?></li>
          
        </ol>
      </nav>
    </div><!-- End Page Title -->
  
    <section class="section dashboard">
      <div id="myGroup" class="row">
        <?php if (isset($_GET['All'])) {?>
            <div class="row card card-body flex-row-reverse m-auto mb-5">
          <h5 class="card-title text-center">   إدارة حلقات اليوم </h5>
          <!-- Revenue Card -->
          <div class="col-sm-2 col-6">
            <div class=" info-card sales-card">
              <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <h5 class="card-title text-center"> إجمالى الحلقات </h5>
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <h6 class="text-center"><?php echo $Acount ; ?></h6>
                </div>
              </div>
            </div>
          </div><!-- End Revenue Card -->
          <!-- Revenue Card -->
          <div class="col-sm-2 col-6">
            <div class=" info-card sales-card">
              <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <h5 class="card-title text-center">   الأساسية </h5>
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <h6 class="text-center"><?php echo $Ecount ; ?></h6>
                </div>
              </div>
            </div>
          </div><!-- End Revenue Card -->
          <!-- Revenue Card -->
          <div class="col-sm-2 col-6">
            <div class=" info-card sales-card">
              <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <h5 class="card-title text-center">  التعويضية </h5>
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <h6 class="text-center"><?php echo $Fcount ; ?></h6>
                </div>
              </div>
            </div>
          </div><!-- End Revenue Card -->
          <!-- Revenue Card -->
          <div class="col-sm-2 col-6">
            <div class=" info-card revenue-card">
              <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <h5 class="card-title text-center">   المسجله </h5>
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <h6 class="text-center"><?php echo $Bcount ; ?></h6>
                </div>
              </div>
            </div>
          </div><!-- End Revenue Card -->
          <!-- Revenue Card -->
          <div class="col-sm-2 col-6">
            <div class=" info-card customers-card">
              <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <h5 class="card-title text-center">   الغير مسجله </h5>
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <h6 class="text-center"><?php echo $Ccount ; ?></h6>
                </div>
              </div>
            </div>
          </div><!-- End Revenue Card -->
          <!-- Revenue Card -->
          <div class="col-sm-2 col-6">
            <div class=" info-card customers-card">
              <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <h5 class="card-title text-center">   الملغاه </h5>
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <h6 class="text-center"><?php echo $Dcount ; ?></h6>
                </div>
              </div>
            </div>
          </div><!-- End Revenue Card -->

            
          </div>
      <?php } ?>
        <?php
          if (empty($Meetings)) {
          ?> 
          <div class="form-group row shadow p-3  bg-body rounded" style=" margin: auto;">
              <div class="alert alert-primary" role="alert"  style=" width: 100%;" >
                  لا توجد حلقات اليوم
              </div>
          </div>
          <?php
          
          }
          foreach($Meetings as $Meeting){ ?>
            <div  class="card card-body  col-lg-12 col-sm-12 d-lg-flex-row p-3">    
                    <div class=" col-lg-6"> <p class="card-details"   >   &nbsp; <?php  if (!isset($_GET['code']) ) { echo $Meeting['Teacher'] ." / " ; } echo  $Meeting['Student'] ?> /&nbsp; <?php echo $Meeting['Name'] ?> /&nbsp; <?php echo "( ".$Meeting['Duration']." "."دقيقة"." )" ?> /&nbsp; <?php echo $Meeting['Time'] ?> </p></div>
                    <div class=" col-lg-12">
                        <div class= "row d-flex align-items-center justify-content-around">
                        <?php
                        if ($Meeting['Nots'] > 0) { 
                            ?>
                            <button class="mb-2 btn btn-success fs-6 fw-bold w-20-sm-100" disabled>
                            <?php if ($Meeting['Admin'] == 1 ) { echo "سجل مشرف الحلقة" ; }else { echo "تم  تسجيل الحلقة" ; } ?>
                            </button>
                            <?php
                            $Durat = $con->prepare("SELECT * FROM history WHERE ID= ?   ");
                            $Durat->execute(array($Meeting['Nots_id']) );
                            $Duration_count = $Durat->rowCount();
                            $Duratione_fetch = $Durat->fetch();
                            if ($Duration_count > 0) {
                              $Duratione = $Duratione_fetch['Duration'];
                            }else{
                              $Duratione = "Unknow";
                            }
                            ?>

                            <button class="mb-2 btn btn-outline-primary fs-6 fw-bold w-20-sm-100" type="button" data-bs-toggle="collapse" data-bs-target="<?php echo "#".$Meeting['Section'] ?>" aria-expanded="false" aria-controls="<?php echo$Meeting['Section'] ?>"> تعديل مدة الحصة  </button>
                            <input  id="D_History<?php echo $Meeting['Section'] ; ?>" onclick="D_History(<?php echo 'D_History_form'.$Meeting['Section'].','.'D_History'.$Meeting['Section'].','.'spin'.$Meeting['Section'] ; ?>)" type="submit" value=" الغاء تسجيل الحصة" class="mb-2 btn btn-secondary fs-6 fw-bold w-20-sm-100" >

                            <div class="collapse" id="<?php echo $Meeting['Section'] ?>" data-bs-parent="#myGroup" >

                            <form  id="form_group<?php echo $Meeting['Section'] ; ?>"  action="in-to-db?do=E_History" method="post" style="">
                              <span class="form-control text-center  mt-2">
                                <p>
                                <span class="text-center  text-danger "> انتبه !! </span> تعديل مدة الحصة يؤثر على هذة الحصة المسجله فقط 
                                <p>
                                <span> مدة الحصة  لهذة الحصة المسجلة هى <?php echo $Duratione." دقيقة " ?> </span>
                                </p>
                                </p>
                              </span>


                              <input type="hidden" name="ID" readonly   value="<?php echo $Meeting['Nots_id'] ; ?>"  required='true'>

                                <div class="input-group mb-3">
                                  <select  name="Duration"   id="form_date<?php echo $Meeting['Section'] ; ?>" class="form-control form-control-lg text-center  mt-2" aria-label=".form-select-lg example"  required='true' aria-describedby="button-addon1 button-addon2">
                                  <option     selected disabled value="">اختر مدة الحصة</option>
                                  <option     value="15"   >15</option>
                                  <option     value="20"   >20</option>
                                  <option     value="30"   >30</option>
                                  <option     value="45"   >45</option>
                                  <option     value="60"   >60</option>
                                  <option     value="75"   >75</option>
                                  <option     value="90"   >90</option>
                                  <option     value="105"  >105</option>
                                  <option     value="120"  >120</option>
                                  <option     value="135"  >135</option>
                                  <option     value="150"  >150</option>
                                  <option     value="165"  >165</option>
                                  <option     value="180"  >180</option>
                                  </select>
                                  <button class="btn btn-outline-primary  fs-6 fw-bold  mt-2 "  id="form_submit<?php echo $Meeting['Section'] ; ?>"  type="submit"> تعديل مدة الحصة  </button>
                                </div>

                            </form>

                            </div>

                            <form  id="D_History_form<?php echo $Meeting['Section'] ; ?>" action="Delete" method="Get"  style=" display: none!important;" >
                                 <input type="hidden" name="ID" readonly   value="<?php echo $Meeting['Nots_id'] ; ?>"  required='true'> 
                                 <input type="hidden" name="code" readonly   value="<?php echo $Meeting['Code'] ; ?>"  required='true'> 
                            </form>

                            <div id="spin<?php echo $Meeting['Section'] ; ?>"  style=" width: 45%;display: none!important; ">
                            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                            </div>

                            </div>
                            <?php
                        }else {
                        if ($Meeting['Cancel'] == 1) { ?> <button class="mb-2 btn btn-danger fs-6 fw-bold w-20-sm-100 " disabled  id="Cancel_button<?php echo $Meeting['Section'] ?>" > تم الألغاء </button>
                            <form target="form" class="Cancel_form w-20-sm-100 p-0" id="Cancel_form<?php echo $Meeting['Section']?>"  action="add-to-db" method="post"  style=" display: inline-block!important;" >
                                <input type="hidden" name="UNCancel" readonly   value="<?php echo $Meeting['MeetingID'] ; ?>"  required='true'> 
                                <input type="hidden" name="date" readonly   value="<?php echo $date_now ; ?>"  required='true'> 
                                <input class="mb-2 btn btn-secondary fs-6 fw-bold w-100"  type="submit" id="Cancel_UNCancel<?php echo $Meeting['Section'] ; ?>" onclick="Cancel_UNCancel(<?php echo 'Cancel_button'.$Meeting['Section'].','.'Cancel_form'.$Meeting['Section'].','.'Cancel_spinner'.$Meeting['Section'].','.'Cancel_UNCancel'.$Meeting['Section'] ; ?>)" value="ازالة الالغاء"  required='true'> 
                            </form>
                            <div id="Cancel_spinner<?php echo $Meeting['Section'] ; ?>"  class="d-flex justify-content-center" style=" width: 20%; display: none!important; ">
                                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            <?php
                        }else { 
                          if ($Meeting['history_request'] == 1) {
                            ?>
                            <button class="mb-2 btn btn-success fs-6 fw-bold w-20-sm-100" disabled>
                            <?php if ($Meeting['history_request_status'] == 1 ) {
                                echo "جار مراجعة طلب التسجيل " ; 
                               }elseif ($Meeting['history_request_status'] == 2) {
                                echo "تم قبول طلب التسجيل " ;
                               }elseif ($Meeting['history_request_status'] == 3) {
                                echo "تم رفض طلب التسجيل ";
                               }else {
                                echo "تم تقديم طلب التسجيل " ; 
                                 } ?>
                            </button>
                            <?php
                          }else {
                            ?>
                            <form  name="TForm" id="form<?php echo $Meeting['Section'] ; ?>" style=" width: 20%; display: none; padding: 0;"  class="Add forms-sample" action="add-to-db" method="post"  enctype="multipart/form-data">
                              <?php if (!isset( $Allow)) { ?> <input type="hidden" name="add_to_history_request" readonly   value="add_to_history_request"  required='true'> <?php } ?>
                              <input type="hidden" name="code" readonly   value="<?php echo $Meeting['Code'] ; ?>"  required='true'> 
                              <input type="hidden" name="category" readonly   value="<?php echo $Meeting['category'] ; ?>"  required='true'> 
                              <input type="hidden" name="name" readonly   value="<?php echo $Meeting['StudentName'] ; ?>"  required='true'> 
                              <input type="hidden" name="Duration" readonly   value="<?php echo $Meeting['Duration'] ; ?>"  required='true'> 
                              <input type="hidden" name="t_code" readonly value="<?php echo $Meeting['Teacher'] ; ?>" class="form-control" required='true'>
                              <input type="hidden" name="status" readonly value="حضور" class="form-control" required='true'>
                              <input type="hidden" name="date" readonly   value="<?php echo $date_now ; ?>" class="form-control" required='true'>
                              <input type="hidden"  name="nots" readonly    value="سجلها المشرف" class="form-control mr-2" required='true'>
                              <input type="hidden"  name="id" readonly    value="<?php echo $Meeting['ID'] ; ?>" class="form-control mr-2" required='true'>
                              <input  class="mb-2 btn btn-outline-primary fs-6 fw-bold w-100"  type="submit"onclick="submit_form(<?php echo 'spinner'.$Meeting['Section'].','.'form'.$Meeting['Section'] ; ?>)"  value="تأكيد"  >
                            </form>
                            <div id="spinner<?php echo $Meeting['Section'] ; ?>"  class="d-flex justify-content-center" style=" width: 20%; display: none!important; ">
                            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                            </div>
                            </div>
                            <?php if (!isset( $Allow)) {
                              ?> <input  class="mb-2 btn btn-outline-primary fs-6 fw-bold w-20-sm-100 Do" id="action<?php echo $Meeting['Section'] ; ?>" onclick="myDIV(),action(<?php echo 'spinner'.$Meeting['Section'].','.'action'.$Meeting['Section'].','.'form'.$Meeting['Section'] ; ?>)"   value="تقديم طلب لتسجيل الحضور" type="submit" style="" > <?php }else {
                              ?> <input  class="mb-2 btn btn-outline-primary fs-6 fw-bold w-20-sm-100 Do" id="action<?php echo $Meeting['Section'] ; ?>" onclick="myDIV(),action(<?php echo 'spinner'.$Meeting['Section'].','.'action'.$Meeting['Section'].','.'form'.$Meeting['Section'] ; ?>)"   value="تسجيل الحضور" type="submit" style="" >
                            <?php } ?>
                            
                              <?php if ($Meeting['Lastabsence'] == 1) { ?>
                                <input class="mb-2 btn btn-danger fs-6 fw-bold w-20-sm-100 Do"  disabled value=" لا يمكن تسجيل غياب " type="submit"style="" >
                              <?php }else { ?>
                                <form  name="TForm" id="form_Add_Absence<?php echo $Meeting['Section'] ; ?>" style=" width: 20%; display: none; padding: 0;"  class="Add forms-sample" action="add-to-db" method="post"  enctype="multipart/form-data">
                                <?php if (!isset( $Allow)) { ?> <input type="hidden" name="add_to_history_request" readonly   value="add_to_history_request"  required='true'> <?php } ?>
                                  <input type="hidden" name="code" readonly   value="<?php echo $Meeting['Code'] ; ?>"  required='true'> 
                                  <input type="hidden" name="category" readonly   value="<?php echo $Meeting['category'] ; ?>"  required='true'> 
                                  <input type="hidden" name="name" readonly   value="<?php echo $Meeting['StudentName'] ; ?>"  required='true'> 
                                  <input type="hidden" name="Duration" readonly   value="<?php echo $Meeting['Duration'] ; ?>"  required='true'> 
                                  <input type="hidden" name="t_code" readonly value="<?php echo $Meeting['Teacher'] ; ?>" class="form-control" required='true'>
                                  <input type="hidden" name="status" readonly value="غياب" class="form-control" required='true'>
                                  <input type="hidden" name="date" readonly   value="<?php echo $date_now ; ?>" class="form-control" required='true'>
                                  <input type="hidden"  name="nots" readonly    value="سجلها المشرف" class="form-control mr-2" required='true'>
                                  <input type="hidden"  name="id" readonly    value="<?php echo $Meeting['ID'] ; ?>" class="form-control mr-2" required='true'>
                                  <input class="mb-2 btn btn-outline-primary fs-6 fw-bold w-100" type="submit"onclick="Add_Absence(<?php echo 'spinner_Add_Absence'.$Meeting['Section'].','.'form_Add_Absence'.$Meeting['Section'] ; ?>)"  value="تأكيد"  >
                               </form>
                                <div id="spinner_Add_Absence<?php echo $Meeting['Section'] ; ?>"  class="d-flex justify-content-center" style=" width: 20%; display: none!important; ">
                                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                                <span class="visually-hidden">Loading...</span>
                                </div>
                                </div>
                                <?php if (!isset( $Allow)) {
                                  ?> <input class="mb-2 btn btn-secondary fs-6 fw-bold w-20-sm-100 Do" id="action_Add_Absence<?php echo $Meeting['Section'] ; ?>" onclick="myDIV(),action_Add_Absence(<?php echo 'spinner_Add_Absence'.$Meeting['Section'].','.'action_Add_Absence'.$Meeting['Section'].','.'form_Add_Absence'.$Meeting['Section'] ; ?>)"   value="تقديم طلب لتسجيل الغياب" type="submit"style="" > <?php }else {
                                  ?> <input class="mb-2 btn btn-secondary fs-6 fw-bold w-20-sm-100 Do" id="action_Add_Absence<?php echo $Meeting['Section'] ; ?>" onclick="myDIV(),action_Add_Absence(<?php echo 'spinner_Add_Absence'.$Meeting['Section'].','.'action_Add_Absence'.$Meeting['Section'].','.'form_Add_Absence'.$Meeting['Section'] ; ?>)"   value="تسجيل الغياب" type="submit"style="" >
                                <?php } ?>
                                
                              <?php } ?>

                            <?php
                          }
                           
                        } 
                        } 
                        if ($Meeting['Nots'] > 0) { ?>
                            </div>
                        <?php }else {
                         if ($Meeting['Type'] == 1) { 
                        ?>
                            <input  id="Dschedule<?php echo $Meeting['Section'] ; ?>" onclick="Dschedule(<?php echo 'Dschedule_form'.$Meeting['Section'].','.'Dschedule'.$Meeting['Section'].','.'spin'.$Meeting['Section'] ; ?>)" type="submit" value=" حذف الحصة" class="mb-2 btn btn-outline-primary fs-6 fw-bold w-20-sm-100" >
                            <form target="form"  id="Dschedule_form<?php echo $Meeting['Section'] ; ?>" action="EditeClasse" method="GET"  style=" display: none!important;" >
                                 <input type="hidden" name="do" readonly   value="Delete"  required='true'> 
                                 <input type="hidden" name="ID" readonly   value="<?php echo $Meeting['MeetingID'] ; ?>"  required='true'> 
                            </form>
                            <div id="spin<?php echo $Meeting['Section'] ; ?>"  style=" width: 45%;display: none!important; ">
                            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                            </div>
                            </div>
                           
                        </div>
                        <?php }else {
                            $Name = $Meeting['StudentName'].' - Rescheduled';
                            $type = $con->prepare("SELECT * FROM class WHERE Teacher= ?  AND `one_time` >= ? AND Student =? AND StudentName LIKE ?  AND `type` =? ORDER BY `$time_day_now` ASC ");
                            $type->execute(array($Meeting['Teacher'],date("Y-m-d") , $Meeting['Code'] , "%$Name%",1) );
                            $type_count = $type->rowCount();
                            $H_type=$type->fetchall();
                            if ($type_count != 0) { ?>
                                <button class="mb-2 btn btn-outline-primary fs-6 fw-bold w-20-sm-100" type="button" data-bs-toggle="collapse" data-bs-target="<?php echo "#".$Meeting['Section'] ?>" aria-expanded="false" aria-controls="<?php echo$Meeting['Section'] ?>">نسخ تعويضية </button>

                                <?php if ($Meeting['Cancel'] == 1 ){}else { ?>                                       
                                    <input class="mb-2 btn btn-secondary fs-6 fw-bold w-20-sm-100" title="قم بألغاء الحصة اذا أجل الطالب الموعد او اعتذرت انت للطالب" id="Cancel<?php echo $Meeting['Section'] ; ?>" onclick="Cancel(<?php echo 'Cancel_form'.$Meeting['Section'].','.'Cancel'.$Meeting['Section'].','.'Cancel_spinner'.$Meeting['Section'] ; ?>)" value="الغاء الحصة" readonly > 
                                    <form target="form"  id="Cancel_form<?php echo $Meeting['Section'] ; ?>" action="add-to-db" method="post"  style=" padding: 0; width: 100%;" >
                                        <input type="hidden" name="Cancel" readonly   value="<?php echo $Meeting['MeetingID'] ; ?>"  required='true'> 
                                        <input type="hidden" name="date" readonly   value="<?php echo $date_now ; ?>"  required='true'> 
                                    </form>
                                    <div id="Cancel_spinner<?php echo $Meeting['Section'] ; ?>"  class="d-flex justify-content-center" style=" width: 20%; display: none!important; ">
                                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                                <span class="visually-hidden">Loading...</span>
                                </div>
                                </div>
                                <?php } ?>
                                </div >
                                <div class= "row ">
                                <?php foreach($H_type as  $value) { ?> <p > هناك حصة تعويضية  متاحة للتسجيل يوم <?php echo $value['one_time'] ?> </p> <?php } ?>
                                </div>
                                
                                <?php
                            }else {
                                ?>
                                <button class="mb-2 btn btn-outline-primary fs-6 fw-bold w-20-sm-100" type="button" data-bs-toggle="collapse" data-bs-target="<?php echo "#".$Meeting['Section'] ?>" aria-expanded="false" aria-controls="<?php echo$Meeting['Section'] ?>">نسخ تعويضية </button>

                                <?php if ($Meeting['Cancel'] == 1  ){}else { ?>                                       
                                    <input class="mb-2 btn btn-secondary fs-6 fw-bold w-20-sm-100"  title="قم بألغاء الحصة اذا أجل الطالب الموعد او اعتذرت انت للطالب" id="Cancel<?php echo $Meeting['Section'] ; ?>" onclick="Cancel(<?php echo 'Cancel_form'.$Meeting['Section'].','.'Cancel'.$Meeting['Section'].','.'Cancel_spinner'.$Meeting['Section'] ; ?>)" value="الغاء الحصة" readonly > 
                                    <form target="form"  id="Cancel_form<?php echo $Meeting['Section'] ; ?>" action="add-to-db" method="post"  style=" padding: 0; width: 100%;" >
                                        <input type="hidden" name="Cancel" readonly   value="<?php echo $Meeting['MeetingID'] ; ?>"  required='true'> 
                                        <input type="hidden" name="date" readonly   value="<?php echo $date_now ; ?>"  required='true'> 
                                    </form>
                                    <div id="Cancel_spinner<?php echo $Meeting['Section'] ; ?>"  class="d-flex justify-content-center" style=" width: 20%; display: none!important; ">
                                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                                <span class="visually-hidden">Loading...</span>
                                </div>
                                </div>
                                <?php } ?>
                                </div>
                            <?php } ?>

                            <div class="collapse" id="<?php echo $Meeting['Section'] ?>" data-bs-parent="#myGroup" >
                            <form target="form" id="form_group<?php echo $Meeting['Section'] ; ?>"  action="in-to-db?do=Add_c" method="post" style="">
                            <input type="hidden" name="Student" readonly   value="<?php echo $Meeting['Code'] ; ?>"  required='true'> 
                            <input type="hidden" name="StudentName" readonly   value="<?php echo $Meeting['StudentName'] ; ?>"  required='true'> 
                            <input type="hidden" name="Duration" readonly   value="<?php echo $Meeting['Duration'] ; ?>"  required='true'> 
                            <input type="hidden" name="Teacher" readonly value="<?php echo $Meeting['Teacher'] ; ?>" class="form-control" required='true'>
                            <input type="hidden" name="category" readonly value="<?php echo $Meeting['category'] ; ?>" class="form-control" required='true'>
                            <input type="hidden" name="class_type" readonly value="Rescheduled" class="form-control" required='true'>
                            <input style=" margin-top: 10px;" id="form_date<?php echo $Meeting['Section'] ; ?>" type="date" name="for_one_time"     value="" class="form-control" required='true'>
                            <input style=" margin-top: 10px;" id="form_time<?php echo $Meeting['Section'] ; ?>" type="time"  name="time"    value="<?php echo $cod['Time']  ?>" class="form-control" required='true'>
                            <input type="hidden"  name="ID" readonly    value="<?php echo  rand(0,1000).date("d").rand(9,99) ; ?>" class="form-control mr-2" required='true'>
                            <input class="mb-2 btn btn-outline-primary fs-6 fw-bold w-20-sm-100" id="form_submit<?php echo $Meeting['Section'] ; ?>" onclick="form_2(<?php echo 'form_date'.$Meeting['Section'].','.'form_group'.$Meeting['Section'].','.'spin'.$Meeting['Section'] ; ?>)" type="submit"   value="نسخ  تعويضيه" >
                            </form>
                            <div id="spin<?php echo $Meeting['Section'] ; ?>"  class="d-flex justify-content-center" style=" width: 20%;display: none!important; ">
                            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                            </div>
                            </div>
                            </div>
                        <?php }
                    } ?>
                    </div >
                </div>
        <?php } ?> 
          <iframe name="form" src="" frameborder="0" style=" display: none;"></iframe>

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
<script src="assets/js/action-28-07-2023.js"></script>
<?php include "assets/tem/footer.php" ;?>
<?php 
if (!empty($_SESSION['Emessage'])) { ?>
  <script>
    $(document).ready(function() {
        $(".toast").toast('show');
    });
</script>
<?php unset($_SESSION['Emessage']); } ?>
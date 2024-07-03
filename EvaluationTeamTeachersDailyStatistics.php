<?php 
  $FromThis = date("Y-m-d");
  $ToThis = date("Y-m-d");


  $Amr_count = $con->prepare("SELECT * FROM evaluation WHERE Who =? AND `Date`>=? AND `Date`<=? AND classID != ? AND `type`=? AND Cancel = 0 ORDER BY `Date`  ASC");
  $Amr_count->execute(array("عمرو عبدالله",$FromThis,$ToThis,0,1));
  $evaluation_Amr_count = $Amr_count->rowCount();

  $Bedo_count = $con->prepare("SELECT * FROM evaluation WHERE Who =? AND `Date`>=? AND `Date`<=? AND classID != ? AND `type`=? AND Cancel = 0 ORDER BY `Date`  ASC");
  $Bedo_count->execute(array("عبدالرحمن",$FromThis,$ToThis,0,1));
  $evaluation_Bedo_count = $Bedo_count->rowCount();

  $Ahmed_count = $con->prepare("SELECT * FROM evaluation WHERE Who =? AND `Date`>=? AND `Date`<=? AND classID != ? AND `type`=? AND Cancel = 0 ORDER BY `Date`  ASC");
  $Ahmed_count->execute(array("أحمد منجود",$FromThis,$ToThis,0,1));
  $evaluation_Ahmed_count = $Ahmed_count->rowCount();

  $Other_count = $con->prepare("SELECT * FROM evaluation WHERE Who !=? AND Who !=? AND Who !=? AND `Date`>=? AND `Date`<=? AND classID != ? AND `type`=? AND Cancel = 0 ORDER BY `Date`  ASC");
  $Other_count->execute(array("أحمد منجود","عبدالرحمن","عمرو عبدالله",$FromThis,$ToThis,0,1));
  $evaluation_Other_count = $Other_count->rowCount();

  $Cancel_count = $con->prepare("SELECT * FROM evaluation WHERE `Date`>=? AND `Date`<=? AND classID != ? AND `type`=? AND Cancel = 1 ORDER BY `Date`  ASC");
  $Cancel_count->execute(array($FromThis,$ToThis,0,1));
  $evaluation_Cancel_count = $Cancel_count->rowCount();

  $TotalEvaluation = $evaluation_Amr_count+$evaluation_Bedo_count+$evaluation_Ahmed_count+$evaluation_Other_count+$evaluation_Cancel_count ;
?>
<div class="col-lg-12 my-2">
  <div class="card h-100">
    <div class="row">
      <h5 class="card-title text-center"> أحصائيات فريق الأشراف <?php echo " ( ".$FromThis." ) " ; ?> </h5>
      <div class="col-5 col-lg-2 card-body p-0 text-center m-auto">
      <h1 class=" card-title  text-center  rtl"> <?php echo " ( ".$TotalEvaluation." ) " ; ?> الإجمالى </h1>
      </div>
      <div class="col-5 col-lg-2 card-body p-0 text-center m-auto">
      <h1 class=" card-title rtl"> <?php echo " ( ".$evaluation_Cancel_count." ) " ; ?> ملغية </h1>
      </div>
      <div class="col-5 col-lg-2 card-body p-0 text-center m-auto">
      <h1 class=" card-title rtl"> <?php echo " ( ".$evaluation_Other_count." ) " ; ?> مشرف آخر </h1>
      </div>
      <div class="col-5 col-lg-2 card-body p-0 text-center m-auto">
      <h1 class=" card-title rtl"> <?php echo " ( ".$evaluation_Amr_count." ) " ; ?> عمرو </h1>
      </div>
      <div class="col-5 col-lg-2 card-body p-0 text-center m-auto">
      <h1 class=" card-title rtl"> <?php echo " ( ".$evaluation_Bedo_count." ) " ; ?> عبدالرحمن </h1>
      </div>
      <div class="col-5 col-lg-2 card-body p-0 text-center m-auto">
      <h1 class=" card-title rtl"> <?php echo " ( ".$evaluation_Ahmed_count." ) " ; ?> أحمد </h1>
      </div>
    </div>
  </div>
</div>

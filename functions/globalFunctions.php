  <?php 
  // Winter OR Summer
  $season = "Summer";
  function TimeToDisplay($time){
    if ($time != NULL) {
      global $season ;
        if ($season == "Winter") {
            $t = strtotime($time) - (60*60);
            return date('h:i:s a', $t);
        }else {
            $t = strtotime($time);
            return date('h:i:s a', $t);
        }
    }
  }
  function TimeToDisplayIn24($time){
    if ($time != NULL) {
      global $season ;
        if ($season == "Winter") {
            $t = strtotime($time) - (60*60);
            return date('H:i:s', $t);
        }else {
            $t = strtotime($time);
            return date('H:i:s', $t);
        }
    }
  }
  function TimeToInsert($time){
    if ($time != NULL) {
      global $season ;
        if ($season == "Winter") {
            $t = strtotime($time) + (60*60);
            return date('H:i:s', $t);
        }else {
            $t = strtotime($time);
            return date('H:i:s', $t);
        }
    }
  }
  function checkActual($student)
{
  include "connect.php" ;
  $Actual_stmt = $con->prepare("SELECT * FROM students WHERE `Code`= ? ");
  $Actual_stmt->execute(array($student) );
  $Actual_count = $Actual_stmt->rowCount();
  $Actual_Days=$Actual_stmt->fetch();

  if ($Actual_Days['Days'] === $Actual_Days['Actual_Days']) {
    return true;
  }else {
    return false;
  }
}
  ?>
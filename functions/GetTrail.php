<?php


if (isset($_GET['GetTrail'])) {
  require_once "../connect.php" ;
}else {
  require_once "connect.php" ;
}
function GetTrail($Who)
{
  global $con;
  if ($Who =="All") {
    $stmt = "";
    $execute = array("Active" ) ;
  }else {
    $stmt = "AND Who= ?";
    $execute = array("Active",$Who ) ;
  }

  $Trail_stmt = $con->prepare("SELECT students.Code   FROM  students   WHERE  students.Code NOT  IN(SELECT `Code` FROM Journal  ) AND students.status =? $stmt GROUP BY students.Code ");
  $Trail_stmt->execute($execute);
  $GetTrail = $Trail_stmt->rowCount();
  $GetTrailfetch = $Trail_stmt->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);
    
  $Trail = [];
  foreach ($GetTrailfetch as $key => $value) {
  
      $Trail[$key]['Code'] = $key ;
    
  }
  return $Trail  ;
  
}
if (isset($_GET['GetTrail'])) {
$GetTrail['Total'] = count(GetTrail($_GET['Who']));
echo json_encode($GetTrail);
}
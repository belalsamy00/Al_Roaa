<?php

$row = array();
$day_15 = date("Y-m-d",strtotime("-15 day")) ;
$Count3_stmt = $con->prepare("SELECT * FROM history  ORDER BY `Timestamp` DESC  LIMIT 7000 ");
$Count3_stmt->execute(array());
$Count3_stmt_count = $Count3_stmt->rowCount();
$Count3_stmt_fetch = $Count3_stmt->fetchAll();
foreach ($Count3_stmt_fetch as $key => $value) {
  $row[$key."row_2"]['S_code'] = $value['S_code'];
  $row[$key."row_2"]['date'] = date("Y-m-d", strtotime($value['date']));
}

foreach ($row as $key => $value) {
  if ($value['date'] <= date("Y-m-01") ) {
    unset($row[$key]);
  }
}
function GetLastClassesForCode($Code)
{
  global $row;

  if (isset(array_count_values(array_column($row, 'S_code'))[$Code])) {
    $count = array_count_values(array_column($row, 'S_code'))[$Code];
  }else {
    $count = 0;
  }
  return $count ;
}
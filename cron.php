<?php
include "connect.php"; 
$d_stmt = $con->prepare("INSERT  INTO teachers (Code) VALUES (?)");
$d_stmt->execute(array('A'.date('h:i:s')));
$d_count = $d_stmt->rowCount();
echo $d_count ;
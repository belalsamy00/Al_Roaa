<?php
 header('Access-Control-Allow-Origin: *'); 
if (isset($_GET['Who'])) {
    $Who = $_GET['Who'];
function GetNotification($Who)
{
  include "connect.php" ;

  $WhoExplode = str_replace(",","','",$Who);
  $GetNotification = $con->prepare("SELECT * FROM `Notification` WHERE ForWho IN ('$WhoExplode')  ORDER BY `Timestamp` DESC LIMIT 20 ");
  $GetNotification->execute(array());
  $GetNotification_count = $GetNotification->rowCount();
  $Data = $GetNotification->fetchAll(PDO::FETCH_ASSOC);
  $GetNotification_count_count = [];
  if ($GetNotification_count > 0 ) {
    $Who_str_replace = str_replace("'","",$WhoExplode);
    $WhoExplodearray = explode(',',$Who_str_replace);
    foreach ($Data as $key => $value) {
        $arrayofSeen = unserialize ($value['Seen'] ??"");
        if (is_array($arrayofSeen)) {
            if (!empty(array_intersect($WhoExplodearray, $arrayofSeen))) {
                $Data[$key]['Seen'] = 1;
            }else {
                $Data[$key]['Seen'] = 0;
                $GetNotification_count_count[$key] = 1;
            }
        }else {
            $Data[$key]['Seen'] = 0;
            $GetNotification_count_count[$key] = 1;
        }
    }



    array_multisort(array_column($Data, 'Seen'), SORT_ASC,array_column($Data, 'Timestamp'), SORT_DESC, $Data);

    array_unshift($Data,(object)[
        'Total' =>count($GetNotification_count_count)
    ]);
  }

  
  return json_encode($Data) ;
  
};
echo GetNotification($Who);
}

if (isset($_GET['ID'])) {
  include "connect.php" ;
    $ID = $_GET['ID'];
    $WhoSeen = $_GET['WhoSeen'];
    $WhoSeen = explode(',',$WhoSeen);


  $GetNotificationToUPDATE = $con->prepare("SELECT *  FROM `Notification` WHERE  ID = ? ");
  $GetNotificationToUPDATE->execute(array($ID));
  $GetNotificationToUPDATE_fetch= $GetNotificationToUPDATE->fetch() ;

  if (!empty($GetNotificationToUPDATE_fetch['Seen'])) {
    $Seenarray = unserialize ($GetNotificationToUPDATE_fetch['Seen']);
    if (is_array($Seenarray)) { $SeenarrayToUPDATE = $Seenarray ; } else { $SeenarrayToUPDATE =[]; }
  }else{ $SeenarrayToUPDATE =[];}

  $array_merge = array_merge($SeenarrayToUPDATE,$WhoSeen);
  $SeenSerializeToUPDATE = serialize ($array_merge);


  $UPDATENotification = $con->prepare("UPDATE  `Notification` SET  Seen=?  WHERE ID =? ");
  $UPDATENotification->execute(array($SeenSerializeToUPDATE , $ID ));
  $UPDATENotification_count = $UPDATENotification->rowCount();

  if ($UPDATENotification_count > 0 ) {
    $Data = "مقرؤة";
  }
echo json_encode($Data);
}

?>
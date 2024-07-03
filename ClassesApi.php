<?php 
 require "connect.php";
 require "functions/globalFunctions.php";
    if (isset($_GET["NeededDate"])) { $date_now = date("Y-m-d", strtotime($_GET['NeededDate'])); }else { $date_now = date("Y-m-d"); }
    if (isset($_GET["NeededCode"])) { $teacher = $_GET["NeededCode"]; }else { $teacher = 'All'; }
    if ($teacher == 'All') {
        $date_now_explode= explode('-',$date_now);
        $d = $date_now_explode[2];
        $m = $date_now_explode[1];
        $y = $date_now_explode[0];
        $day_now = date("l", mktime(0,0,0,$m,$d,$y));
        $time_day_now = $day_now."_time";
        $prepare = " `one_time` = ? AND class.status =? OR `".$day_now."` = ? AND class.status =? " ;
        $execute = array($date_now,'Active',$day_now,'Active');
    }else {
        $date_now_explode= explode('-',$date_now);
        $d = $date_now_explode[2];
        $m = $date_now_explode[1];
        $y = $date_now_explode[0];
        $day_now = date("l", mktime(0,0,0,$m,$d,$y));
        $time_day_now = $day_now."_time";
        $prepare = " Teacher= ? AND `one_time` = ? AND class.status =? OR Teacher= ? AND `".$day_now."` = ? AND class.status =? " ;
        $execute = array($teacher,$date_now,'Active',$teacher,$day_now,'Active');
    }


    $SelectClasses = $con->prepare("SELECT class.ID, Teacher , Student ,StudentName , one_time  ,  Cancel , `Time` As `Time1`  ,Duration , `type`, class.status , category , `$day_now` , `$time_day_now` As `Time2` 
    FROM class WHERE  $prepare ORDER BY `Time2`,`Time` ASC  ");
    $SelectClasses->execute($execute);
    $SelectClassesFetchAll= $SelectClasses->fetchAll(PDO::FETCH_ASSOC);
    $SelectClassesRowCount = $SelectClasses->rowCount() ;

    foreach ($SelectClassesFetchAll as $key => $value) {
       if ($value['type'] == 2 ) {
        $SelectClassesFetchAll[$key]['Time'] = date('h:i:s a ', strtotime($value['Time2']));
       }else {
        $SelectClassesFetchAll[$key]['Time'] = date('h:i:s a ', strtotime($value['Time1']));
       }
    }
    $Times = array_column($SelectClassesFetchAll, 'Time');
    array_multisort($Times, SORT_ASC, $SelectClassesFetchAll);
    $Meetings = [] ;
    $H_stmt = $con->prepare("SELECT S_code ,nots , ID , S_code , S_name , T_code , `status` FROM history WHERE  `date`=?  ");
    $H_stmt->execute(array($date_now));
    $countv = $H_stmt->rowCount();
    $H_nots=$H_stmt->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);


    $absence = $con->prepare("SELECT S_code,S_name,`status`,`date` FROM history WHERE  `date` > ? ");
    $absence->execute(array(date("Y-m-d",strtotime("-31 day"))));
    $absence_count = $absence->rowCount();
    $absence_fetch = $absence->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);

    foreach ($absence_fetch as $key => $value) {
        for ($i=0; $i <count($value) ; $i++) { 
            if($value[$i]['S_name'] == ''){
                $absence_fetch[$key][$i]['S_name'] = 'OLD';
            }else {
                $FStudentName = explode('-',$value[$i]['S_name']);
                $absence_fetch[$key][$i]['S_name'] = $FStudentName[0] ;
            }
        }
       
    }

    foreach ($absence_fetch as $key => $value) {
        if (empty($value)) {
            unset($absence_fetch[$key]);
        }
       
    }
    foreach ($absence_fetch as $key => $value) {
        for ($i=0; $i <count($value) ; $i++) { 
            $Name = $value[$i]['S_name'] ;
            $string = str_replace(' ','',$Name);
            if(isset($absence_fetch[$key][$string])){
                if($absence_fetch[$key][$string]['date'] < $value[$i]['date'] ){
                    $absence_fetch[$key][$string]['date'] = $value[$i]['date'];
                    $absence_fetch[$key][$string]['status'] = $value[$i]['status'];
                    unset($absence_fetch[$key][$i]);
                }else {
                    unset($absence_fetch[$key][$i]);
                }
            }else {
                $absence_fetch[$key][$string]['date'] = $value[$i]['date'];
                $absence_fetch[$key][$string]['status'] = $value[$i]['status'];
                unset($absence_fetch[$key][$i]);
            }
        }
       
    }

    foreach($SelectClassesFetchAll as $key => $cod){
        $Active_stmt = $con->prepare("SELECT * FROM students WHERE Code=?  AND `status`=?  LIMIT 1 ");
        $Active_stmt->execute(array($cod['Student'],"Active"));
        $Active_count = $Active_stmt->rowCount();
         
        if ($Active_count > 0 ) {

            $nots_id = 0 ;
            $nots_count = 0 ; 
            $nots_admin = 0 ;
            $nots_absence = 0 ;
            $Lastabsence = 0 ;
    
            if (isset($H_nots[$cod['Student']])) {
                
                for ($i=0; $i < count($H_nots[$cod['Student']]) ; $i++) { 
                    if ($H_nots[$cod['Student']][$i]['S_name'] == $cod['StudentName'] ) {
                        if ($H_nots[$cod['Student']][$i]['T_code'] == $cod['Teacher']) {
                            $nots_id = $H_nots[$cod['Student']][$i]['ID'];
                            $nots_count += 1 ;
                            if ($H_nots[$cod['Student']][$i]['nots'] == 'سجلها المشرف') {
                                $nots_admin += 1 ;
                            }
                            if ($H_nots[$cod['Student']][$i]['status'] == 'غياب') {
                                $nots_absence += 1 ;
                            }
                        }
                    }
                }
                
            }



              

            $FStudentName = explode('-',$cod['StudentName']);
            if ($cod['type'] == 2 ) {

                $sStudentName = $FStudentName[0];
                $Name = $FStudentName[0]; 
                $stringName = str_replace(" ","",$Name);
            }else {

                $Name = $FStudentName[0]; 
                $stringName = str_replace(" ","",$Name);
                $find = array("oneTimeClass","trail","Rescheduled","-");
                $replace = array(" ","تجريبية","تعويضية"," ");
                $string = str_replace($find,$replace,$cod['StudentName']);
                $sStudentName = preg_replace('/[0-9]+/', '', $string);
            }
            $Fsectionid = explode(' ',$cod['Student']);
            $sectionid = $Fsectionid[0].rand(0,1000);
            $sid = $Fsectionid[0]."-".$cod['StudentName'];

            if (isset($absence_fetch[$cod['Student']][$stringName])) {
                if ($absence_fetch[$cod['Student']][$stringName]['status'] == 'غياب') {
                    $Lastabsence += 1 ;
                }
                
            }
                        $Meetings[$key]['Time'] = TimeToDisplay($cod['Time']);
                        $Meetings[$key]['Lastabsence'] = $Lastabsence;
                        $Meetings[$key]['absence'] = $nots_absence;
                        $Meetings[$key]['ID'] = rand(0,1000).date("d").rand(9,99) ;
                        $Meetings[$key]['Teacher'] = ucfirst($cod['Teacher']);
                        $Meetings[$key]['Name'] = $sStudentName ;
                        $Meetings[$key]['Student'] = $Fsectionid[0] ;
                        $Meetings[$key]['Section'] = $sectionid ;
                        $Meetings[$key]['Duration'] = $cod['Duration'] ;
                        $Meetings[$key]['Nots'] = $nots_count ;
                        $Meetings[$key]['Admin'] = $nots_admin ;
                        $Meetings[$key]['Nots_id'] = $nots_id ;
                        $Meetings[$key]['StudentName'] = $cod['StudentName'] ;
                        $Meetings[$key]['Code'] = $cod['Student'] ;
                        $Meetings[$key]['MeetingID'] = $cod['ID'] ;
                        $Meetings[$key]['Type'] = $cod['type'] ;
                        $Meetings[$key]['Meetingdate'] = $date_now ;
                        $category = array('quran','quran_en','nour','arabic','religion','trail','rescheduled' );
                        if (in_array($cod['category'],$category)) {
                            $Meetings[$key]['category'] = $cod['category'];
                        }else {
                            $Meetings[$key]['category'] = "quran";
                        }
            
                        if (!empty($cod['Cancel'])) {
                            $arrayofcancels = unserialize ($cod['Cancel']);
                            if (is_array($arrayofcancels)) {
                                if (in_array($date_now,$arrayofcancels)) {
                                    $Meetings[$key]['Cancel'] = 1; 
                                }else {
                                    $Meetings[$key]['Cancel'] = 0;
                                }
                            }else {
                                $Meetings[$key]['Cancel'] = 0;
                            }
                        }else {
                            $Meetings[$key]['Cancel'] = 0;
                        }
        }
        
    }
    array_unshift($Meetings,(object)[
        'Total' =>count($Meetings)
    ]);
    echo json_encode($Meetings)
?>

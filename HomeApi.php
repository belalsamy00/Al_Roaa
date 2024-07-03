<?php 
 require "functions/globalFunctions.php";
if (isset($_GET['code'])) { 
    $teacher = filter_var($_GET["code"], FILTER_UNSAFE_RAW );
    if (isset($_GET["date"])) { $date_now = filter_var($_GET["date"], FILTER_UNSAFE_RAW ); }else { $date_now = date("Y-m-d"); }
    $date_now_explode= explode('-',$date_now);
    $d = $date_now_explode[2];
    $m = $date_now_explode[1];
    $y = $date_now_explode[0];
    $day_now = date("l", mktime(0,0,0,$m,$d,$y));
    $time_day_now = $day_now."_time";
    $stm_prepare = "Teacher= ? AND `one_time` = ? AND " ;
    $stm2_prepare = "Teacher= ? AND `".$day_now."` = ? AND " ;
    $stm1_execute = array($teacher,$date_now,'Active');
    $stm2_execute = array($teacher,$day_now,'Active') ;
}elseif (isset($_GET['All'])) {
        $date_now = filter_var($_GET["date"], FILTER_UNSAFE_RAW );
        $date_now_explode= explode('-',$date_now);
        $d = $date_now_explode[2];
        $m = $date_now_explode[1];
        $y = $date_now_explode[0];
        $day_now = date("l", mktime(0,0,0,$m,$d,$y));
        $time_day_now = $day_now."_time";
        $stm_prepare = " `one_time` = ? AND " ;
        $stm2_prepare = " `".$day_now."` = ? AND " ;
        $stm1_execute = array($date_now,'Active');
        $stm2_execute = array($day_now,'Active') ;
} else {
        if (isset($_SESSION['teacher'])){$teacher = $_SESSION['teacher'] ;}else { $teacher = "" ;}
        $date_now = date("Y-m-d");
        $day_now = date("l");
        $time_day_now = date("l")."_time";
        $stm_prepare = "Teacher= ? AND  `one_time` = ? AND " ;
        $stm1_execute = array($teacher,$date_now,'Active');
        $stm2_prepare = "Teacher= ? AND `".$day_now."` = ? AND " ;
        $stm2_execute = array($teacher,$day_now,'Active') ;
}
    $stmt_1 = $con->prepare("SELECT ID, Teacher , Student ,StudentName , one_time  ,  Cancel , `Time`  ,Duration , `type`, `status` , category
    FROM class WHERE  $stm_prepare  `status` =? ORDER BY `Time` ASC  ");
    $stmt_1->execute($stm1_execute);
    $stmt_2 = $con->prepare("SELECT ID, Teacher , Student ,StudentName , `$day_now` , `$time_day_now` As `Time`, Cancel , Duration , `type`, `status` , category
    FROM class WHERE $stm2_prepare  `status` =?  ORDER BY `Time` ASC  ");
    $stmt_2->execute($stm2_execute);
    $count = $stmt_1->rowCount() + $stmt_2->rowCount();
    $T_1= $stmt_1->fetchAll() ;
    $T_2= $stmt_2->fetchAll();
    $T_D= array_merge($T_1,$T_2);
    $Times = array_column($T_D, 'Time');
    array_multisort($Times, SORT_ASC, $T_D);

    $Meetings = [] ;
    $H_stmt = $con->prepare("SELECT S_code ,nots , ID , S_code , S_name , T_code , `status` FROM history WHERE  `date`=?  ");
    $H_stmt->execute(array($date_now));
    $countv = $H_stmt->rowCount();
    $H_nots=$H_stmt->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);

    $add_to_history_request = $con->prepare("SELECT S_code ,nots , ID , S_code , S_name , T_code , `status`,`RequestStatus` FROM add_to_history_request WHERE  `date`=?  ");
    $add_to_history_request->execute(array($date_now));
    $add_to_history_request_  = $add_to_history_request->rowCount();
    $add_to_history_request_fetch =$add_to_history_request->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_GROUP);

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
    foreach($T_D as $key => $cod){

        $Active_stmt = $con->prepare("SELECT * FROM students WHERE Code=?  AND `status`=?  LIMIT 1 ");
        $Active_stmt->execute(array($cod['Student'],"Active"));
        $Active_count = $Active_stmt->rowCount();
         
        if ($Active_count > 0 ) {

            $nots_id = 0 ;
            $nots_count = 0 ; 
            $nots_admin = 0 ;
            $nots_absence = 0 ;
            $Lastabsence = 0 ;
            $history_request_count = 0 ; 
            $history_request_status = 0 ;

    
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
    
            if (isset($add_to_history_request_fetch[$cod['Student']])) {
                
                for ($i=0; $i < count($add_to_history_request_fetch[$cod['Student']]) ; $i++) { 
                    if ($add_to_history_request_fetch[$cod['Student']][$i]['S_name'] == $cod['StudentName'] ) {
                        if ($add_to_history_request_fetch[$cod['Student']][$i]['T_code'] == $cod['Teacher']) {
                            $history_request_status += $add_to_history_request_fetch[$cod['Student']][$i]['RequestStatus'] ;
                            $history_request_id = $add_to_history_request_fetch[$cod['Student']][$i]['ID'];
                            $history_request_count += 1 ;
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

            $Meetings[$key]['Lastabsence'] =  $Lastabsence;
            $Meetings[$key]['absence'] = $nots_absence;
            $Meetings[$key]['ID'] = rand(0,1000).date("d").rand(9,99) ;
            $Meetings[$key]['Teacher'] = ucfirst($cod['Teacher']);
            $Meetings[$key]['Name'] = $sStudentName ;
            $Meetings[$key]['Time'] = TimeToDisplay($cod['Time']);
            $Meetings[$key]['Student'] = $Fsectionid[0] ;
            $Meetings[$key]['Section'] = $sectionid ;
            $Meetings[$key]['Duration'] = $cod['Duration'] ;
            $Meetings[$key]['Nots'] = $nots_count ;
            $Meetings[$key]['history_request'] = $history_request_count ;
            $Meetings[$key]['history_request_status'] = $history_request_status ;
            $Meetings[$key]['Admin'] = $nots_admin ;
            $Meetings[$key]['Nots_id'] = $nots_id ;
            $Meetings[$key]['StudentName'] = $cod['StudentName'] ;
            $Meetings[$key]['Code'] = $cod['Student'] ;
            $Meetings[$key]['MeetingID'] = $cod['ID'] ;
            $Meetings[$key]['Type'] = $cod['type'] ;
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
?>
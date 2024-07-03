<?php 
    if (isset($_GET["date"])) { $date_now = filter_var($_GET["date"], FILTER_UNSAFE_RAW ); }else { $date_now = date("Y-m-d"); }
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
    $nots = [] ;
    $Meetings = [] ;
    $H_stmt = $con->prepare("SELECT nots , ID , S_code , S_name , T_code  FROM history WHERE  `date`=?  ");
    $H_stmt->execute(array($date_now));
    $countv = $H_stmt->rowCount();
    $H_nots=$H_stmt->fetchAll();
    $H_nots = array_values($H_nots);
    foreach ($H_nots as $key => $H) {
        $nots[$key]['S_code'] = $H['S_code'] ;
        $nots[$key]['S_name'] = $H['S_name'] ;
        $nots[$key]['T_code'] = $H['T_code'] ;
        $nots[$key]['nots'] = $H['nots'] ;
        $nots[$key]['ID'] = $H['ID'] ;
    }

    $nots_id = 0 ;
    $nots_count = 0 ; 
    $nots_admin = 0 ;
    foreach($T_D as $key => $cod){

        $Active_stmt = $con->prepare("SELECT * FROM students WHERE Code=?  AND `status`=?  LIMIT 1 ");
        $Active_stmt->execute(array($cod['Student'],"Active"));
        $Active_count = $Active_stmt->rowCount();
        if ($Active_count > 0 ) {

          

            $FStudentName = explode('-',$cod['StudentName']);
            if ($cod['type'] == 2 ) {
                $sStudentName = $FStudentName[0];
            }else {
                $Name = $FStudentName[1]; 
                $find = array("oneTimeClass","trail","Rescheduled","-");
                $replace = array(" ","تجريبية","تعويضية"," ");
                $string = str_replace($find,$replace,$cod['StudentName']);
                $sStudentName = preg_replace('/[0-9]+/', '', $string);
            }
            $Fsectionid = explode(' ',$cod['Student']);
            $sectionid = $Fsectionid[0].rand(0,1000);
            $sid = $Fsectionid[0]."-".$cod['StudentName'];


            $Meetings[$key]['ID'] = rand(0,1000).date("d").rand(9,99) ;
            $Meetings[$key]['Teacher'] = ucfirst($cod['Teacher']);
            $Meetings[$key]['Name'] = $sStudentName ;
            $Meetings[$key]['Time'] = date('h:i:s a ', strtotime($cod['Time'] ?? "" )) ;
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
            if ( in_array($Meetings[$key]['Code'],array_column($nots, 'S_code')) ) {
                $keys =array_keys(array_column($nots, 'S_code'),$Meetings[$key]['Code']);

                
                for ($i=0; $i < count($keys) ; $i++) { 
                    if ($nots[$keys[$i]]['S_name'] == $Meetings[$key]['StudentName'] AND  $nots[ $keys[$i]]['T_code'] == $Meetings[$key]['Teacher'] ) {
                        $Meetings[$key]['Nots'] = 1 ;
                        $Meetings[$key]['Admin'] = 0 ;
                        $Meetings[$key]['Nots_id'] = $nots[$keys[$i]]['ID'] ;
                        break;
                    }else {
                        $Meetings[$key]['Nots'] = 0 ;
                        $Meetings[$key]['Admin'] = 0 ;
                        $Meetings[$key]['Nots_id'] =  0;
                    }
                }
            }
        }
    }
?>

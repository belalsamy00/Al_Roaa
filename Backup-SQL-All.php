<?php
function backupDatabaseAllTables($dbhost,$dbusername,$dbpassword,$dbname,$tables = array(
"students",
"ArchivedStudents",
"class",
"history",
"items",
"evaluation",
"EvaluationTeam",
"RescheduleRequest",
"Activity",
"Journal",
"NewStudents",
"Notification",
"Targets",
"teachers",
"users",
"add_to_history_request",
"ArchivedStudentsExport",
"Messages",
"NewTeachers",
"TeachersJournal",
"TeachersSendSallary",
)){
    $db = new mysqli($dbhost, $dbusername, $dbpassword, $dbname); 

    if($tables == '*') { 
        $tables = array();
        $result = $db->query("SHOW TABLES");
        while($row = $result->fetch_row()) { 
            $tables[] = $row[0];
        }
    } else { 
        $tables = is_array($tables)?$tables:explode(',',$tables);
    }
    $rand = rand(0,100).rand(0,100).rand(0,100);
    $return = '';
    $return .= '<?php'."\n\n";
    $return .= '$rand ='.'"'.$rand.'"'.";\n\n";
    $return .= 'if (!isset($_GET["pass"]) OR $_GET["pass"] !=$rand) {echo "برجاء إدخال كلمة المرور للملف رقم "."$rand";  exit; }'."\n\n";
    $return .= 'include "../connect.php";'."\n\n";

    foreach($tables as $table){
        $result = $db->query("SELECT * FROM $table");
        $numColumns = $result->field_count;

        /* $return .= "DROP TABLE $table;"; */
        $result2 = $db->query("SHOW CREATE TABLE $table");
        $row2 = $result2->fetch_row();

        $stringrow = str_replace("CREATE TABLE","CREATE TABLE IF NOT EXISTS",$row2[1]);
        $return .= "\n\n".'$'.$table.'query = $con->prepare("'.$stringrow.'")'.";\n\n";
        $return .= "\n\n".'$'.$table.'query->execute(array())'.";\n\n";
        $return .= "\n\n".'$'.$table.'query->closeCursor()'.";\n\n";

        $return .= "\n\n".'$'.$table.'row = $con->prepare('."\n \"";
        for($i = 0; $i < $numColumns; $i++) { 
            while($row = $result->fetch_row()) { 
                $return .= "INSERT IGNORE INTO $table  VALUES"."('";
                for($j=0; $j < $numColumns; $j++) { 
                    $row[$j] = addslashes($row[$j] ?? '');
                    $row[$j] = $row[$j];
                    $return .= $row[$j] ;
                        if ($j < ($numColumns-1) AND $row[$j+1] == NULL ) {
                            if ($row[$j] == NULL) { 
                                if ($j < ($numColumns-1)) { 
                                $return.= ",NULL";
                            }
                            }else {
                                if ($j < ($numColumns-1)) { 
                                $return.= "',NULL";
                            }
                            }
                        }else {

                            if ($row[$j] == NULL) { 
                                if ($j < ($numColumns-1)) { 
                                $return.= ",'";
                            }
                            }else {
                                if ($j < ($numColumns-1)) { 
                                $return.= "','";
                            }
                            }
                            
                        }
                }
                if ($return[strlen($return)-1] == "L") {
                    $return .= ");\n";
                }else {
                    $return .= "');\n";
                }
                
            } 
        }
        $return .= "\");\n";
        $return .= "\n\n".'$'.$table.'row->execute(array())'.";\n\n";
        $return .= "\n\n".'$'.$table.'row->closeCursor()'.";\n\n";
        $return .= "\n\n".'$'.$table.'rowCount = $'.$table.'row->rowCount()'.";\n\n";

        $return .= "\n\n".' echo "تم استعادة الأسطر المحذوفة من جدول  "."'.$table.'"."<br>";'."\n\n";

        $return .= "\n\n\n";
    }

    $handle = fopen('BackupFiles/'.date('Y-m-d - H-i-s')." - ".'DB-backup'." - ".$rand.'.php','w+');
    $File = 'BackupFiles/'.date('Y-m-d - H-i-s')." - ".'DB-backup'." - ".$rand.'.php';
    fwrite($handle,$return);
    fclose($handle);

        $Data =[];
        if(file_exists($File)){
            if(filesize($File)) {
                array_unshift($Data,(object)[
                    'Text' => " بنجاح "." All Database SQL "." تم إنشاء النسخة الأحتياطية من ملف  ال "
                ]);
                array_unshift($Data,(object)[
                    'Status' => 1
                ]);
                echo json_encode($Data);
            }else {
                array_unshift($Data,(object)[
                    'Text' => "لاكنه فارغ الدعم الفنى"." All Database SQL "." تم إنشاء ملف ال "
                ]);
                array_unshift($Data,(object)[
                    'Status' => 0
                ]);
                echo json_encode($Data);
            }
        }else {
                array_unshift($Data,(object)[
                    'Text' => "راجع الدعم الفنى"." All Database SQL "."لم يتم إنشاء ملف ال "
                ]);
                array_unshift($Data,(object)[
                    'Status' => 0
                ]);
                echo json_encode($Data);
        } ;
}

backupDatabaseAllTables('localhost','u950733909_AdminBelal','AdminBelal2024','u950733909_alroaaacademy');
// backupDatabaseAllTables('localhost','root','Root','alroaaacademy');
?>


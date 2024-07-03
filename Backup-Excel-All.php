<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['manager'])) {  header('Location: index');  exit;  }  ?>
<?php if(file_exists('connect.php')) include 'connect.php'; ?> 
<?php
    $tables =  array(
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
        );
        $html = '' ;
        foreach($tables as $table){
            $html .= "<h3>".$table."</h3>";
            $tablequery = $con->prepare("SELECT * FROM $table");
            $tablequery->execute(array());
            $tablerowCount = $tablequery->rowCount();
            $tablefetchAll = $tablequery->fetchAll(PDO::FETCH_ASSOC);
            $html .= '<table border="1"><tr>';
            foreach ($tablefetchAll[0] as $key => $value) {
                $html .= " <th>".$key."</th> ";

            }
            $html .='</tr>';
            foreach ($tablefetchAll as $key) {
                $html .='<tr>';
                foreach ($key as $t => $value) {
                $html .= " <td>".$value."</td> ";
                }
                $html .='</tr>';
            }
            $html .= '</table>';
            $html .= '</br>';
        }
        $rand = rand(0,100).rand(0,100).rand(0,100);
        $handle = fopen('BackupFiles/Excel/'.date('Y-m-d - H-i-s')." - ".'DB-backup'."-".'.xls','w+');
        $File = 'BackupFiles/Excel/'.date('Y-m-d - H-i-s')." - ".'DB-backup'."-".'.xls';
        fwrite($handle,$html);
        fclose($handle);
        $Data =[];
        if(file_exists($File)){
            if(filesize($File)) {
                array_unshift($Data,(object)[
                    'Text' => " بنجاح "." All Database Excel "." تم إنشاء النسخة الأحتياطية من ملف  ال "
                ]);
                array_unshift($Data,(object)[
                    'Status' => 1
                ]);
                echo json_encode($Data);
            }else {
                array_unshift($Data,(object)[
                    'Text' => "لاكنه فارغ الدعم الفنى"." All Database Excel "." تم إنشاء ملف ال "
                ]);
                array_unshift($Data,(object)[
                    'Status' => 0
                ]);
                echo json_encode($Data);
            }
        }else {
                array_unshift($Data,(object)[
                    'Text' => "راجع الدعم الفنى"." All Database Excel "."لم يتم إنشاء ملف ال "
                ]);
                array_unshift($Data,(object)[
                    'Status' => 0
                ]);
                echo json_encode($Data);
        } ;
        ?>
<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['CustomerService']) AND  !isset($_SESSION['Admin']) AND !isset($_SESSION['Suber_Admin']) AND !isset($_SESSION['manager'])) {  header('Location: index');  exit;  }  ?>
<?php
    $filename = "History_1";    
    $file_ending = "xls";
    header("Content-Type: application/xls");    
    header("Content-Disposition: attachment; filename=$filename.$file_ending");  
    header("Pragma: no-cache"); 
    header("Expires: 0");
?>
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
<meta name="keywords" content="" />
<meta name="description" content="Quran School" />
<meta name="author" content="" />
    <?php if(file_exists('../connect.php')) include '../connect.php'; ?>
    <?php if(file_exists('connect.php')) include 'connect.php'; ?>

</head>
<?php 
    if (isset($_GET['Code'])) {
        $Code = $_GET['Code'] ;
        $result = $con->prepare("SELECT * FROM history Where S_code LIKE ? ORDER BY `Timestamp` ASC ");
        $result->execute(array("%$Code %"));
    }else {
        $result = $con->prepare("SELECT * FROM history ORDER BY `Timestamp`");
        $result->execute(array());
    }
    
    $rows=$result->fetchAll();
    $update_count = $result->rowCount();  
    if ($update_count > 0) {   
        echo '<table border="1">';
        //make the column headers what you want in whatever order you want
        echo '<tr>
        <th>Timestamp</th>
        <th>ID</th>
        <th>المعلم</th>
        <th>الكود</th>
        <th>اسم الطالب</th>
        <th>الحالة</th>
        <th>التاريخ</th>
        <th>ملاحظات</th>
        <th>المدة</th>
        </tr>';
        //loop the query data to the table in same order as the headers
        foreach ($rows as $row){
            $find = array("trail","Rescheduled","-");
            $replace = array(" "," "," ");
            $string = str_replace($find,$replace,$row['S_name']);
            $Name = preg_replace('/[0-9]+/', '', $string);
            echo "<tr>
            <td>".$row['Timestamp']."</td>
            <td>".$row['ID']."</td>
            <td>".$row['T_code']."</td>
            <td>".$row['S_code']."</td>
            <td>".$Name."</td>
            <td>".$row['status']."</td>
            <td>".$row['date']."</td>
            <td>".$row['nots']."</td>
            <td>".$row['Duration']."</td>
            </tr>";
        }
        echo '</table>';
    }else {
        
        echo "<SCRIPT> //not showing me this
        alert('لا توجد حصص لتحميلها')
        window.location.replace('Home');
        </SCRIPT>";

        exit;
    }

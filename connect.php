<?php
$dsn = 'mysql:host=localhost;port=3306;dbname=alroaaacademy';
$user = 'root';
$pass = 'Root';
// $dsn = 'mysql:host=localhost;port=3306;dbname=u950733909_alroaaacademy';
// $user = 'u950733909_AdminBelal';
// $pass = 'AdminBelal2024';
// $dsn = 'mysql:host=localhost;port=3306;dbname=u901138566_elroaa';
// $user = 'u901138566_admin';
// $pass = '-_B.s95-h';
// $dsn = 'mysql:host=localhost;port=3306;dbname=irrvkpmy_alroaaacademy';
// $user = 'irrvkpmy_alroaaacademyApp';
// $pass = 'ASGFOoo-z1Q=';
$option = array(
    PDO::MYSQL_ATTR_INIT_COMMAND =>'SET NAMES utf8 ,  time_zone = "+02:00";',
);
try {
    $con = new PDO($dsn,$user,$pass,$option);
    $con->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
}
catch (PDOEXCEPTION $e) {
    echo 'failed to connect' . $e->getMessage();
}
if (!isset($con)) { ?>

<div class="fixed-bottom">
<div class="alert alert-danger" role="alert">
    تم قطع الأتصال انتظر خمس دقائق ثم أعد تحديث الصفحة <?php echo 'failed to connect' . $e->getMessage(); ?>
</div>
</div>
    
<?php exit ; }
// SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));
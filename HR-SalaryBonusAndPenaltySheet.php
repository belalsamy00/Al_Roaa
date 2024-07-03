<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
if (!isset($_SESSION['CustomerService']) and !isset($_SESSION['manager']) and !isset($_SESSION['CustomerServiceManager'])) {
  header('Location: index');
  exit;
}
include "assets/tem/header.php";
if (isset($_GET['Y'])) {
  $Y = $_GET['Y'];
} else {
  $_GET['Y'] = date("Y");
  $Y = $_GET['Y'];
}
if (isset($_GET['Start'])) {
  if ($_GET['Start'] == 1) {
    $this_month = date("$Y-01-01");
    $Next_month = date("$Y-m-t", strtotime(date("$Y-01-01")));
  } elseif ($_GET['Start'] == 2) {
    $this_month = date("$Y-02-01");
    $Next_month = date("$Y-m-t", strtotime(date("$Y-02-01")));
  } elseif ($_GET['Start'] == 3) {
    $this_month = date("$Y-03-01");
    $Next_month = date("$Y-m-t", strtotime(date("$Y-03-01")));
  } elseif ($_GET['Start'] == 4) {
    $this_month = date("$Y-04-01");
    $Next_month = date("$Y-m-t", strtotime(date("$Y-04-01")));
  } elseif ($_GET['Start'] == 5) {
    $this_month = date("$Y-05-01");
    $Next_month = date("$Y-m-t", strtotime(date("$Y-05-01")));
  } elseif ($_GET['Start'] == 6) {
    $this_month = date("$Y-06-01");
    $Next_month = date("$Y-m-t", strtotime(date("$Y-06-01")));
  } elseif ($_GET['Start'] == 7) {
    $this_month = date("$Y-07-01");
    $Next_month = date("$Y-m-t", strtotime(date("$Y-07-01")));
  } elseif ($_GET['Start'] == 8) {
    $this_month = date("$Y-08-01");
    $Next_month = date("$Y-m-t", strtotime(date("$Y-08-01")));
  } elseif ($_GET['Start'] == 9) {
    $this_month = date("$Y-09-01");
    $Next_month = date("$Y-m-t", strtotime(date("$Y-09-01")));
  } elseif ($_GET['Start'] == 10) {
    $this_month = date("$Y-10-01");
    $Next_month = date("$Y-m-t", strtotime(date("$Y-10-01")));
  } elseif ($_GET['Start'] == 11) {
    $this_month = date("$Y-11-01");
    $Next_month = date("$Y-m-t", strtotime(date("$Y-11-01")));
  } elseif ($_GET['Start'] == 12) {
    $this_month = date("$Y-12-01");
    $Next_month = date("$Y-m-t", strtotime(date("$Y-12-01")));
  } else {
    $_GET['Start'] = date("m");
    $this_month = date("$Y-m-01");
    $Next_month = date("$Y-m-t", strtotime(date("$Y-m-d")));
  }
} else {
  $_GET['Start'] = date("m");
  $this_month = date("$Y-m-01");
  $Next_month = date("$Y-m-t", strtotime(date("$Y-m-d")));
}

if (isset($_GET['Who']) and in_array($_GET['Who'], array('AbdelRahman', 'Belal', 'Hamza', 'Ramadan', 'Bedo', 'All'))) {
  $_SESSION['Who'] = $_GET['Who'];
}
if (isset($_SESSION['Who']) and in_array($_SESSION['Who'], array('AbdelRahman', 'Belal', 'Hamza', 'Ramadan', 'Bedo'))) {
  $Who = $_SESSION['Who'];
} else {
  $Who = "All";
}

if (isset($_GET['Code'])) {
  $search = $_GET['Code'];
  $stmt = "WHERE Code LIKE ? ";
  $execute = array("%$search %");
} else {
  if ($Who == "All") {
    $stmt = "WHERE  `Date`>=? AND `Date`<=? ";
    $execute = array($this_month, $Next_month);
  } else {
    $stmt = "WHERE `Who`= ? AND `Date`>=? AND `Date`<=? ";
    $execute = array($Who, $this_month, $Next_month);
  }
}

$Journal = $con->prepare("SELECT * FROM TeachersJournal $stmt ORDER BY `Timestamp` ASC ");
$Journal->execute($execute);
$Journal_count = $Journal->rowCount();
$JournalArry = $Journal->fetchAll();


?>
<main id="main" class="main">
  <div class="container">
    <div class="pagetitle">
      <nav>
        <ol class="breadcrumb">
          <li class="">
            <button class="btn btn-success fs-6 fw-bold text-white" onclick="window.location= 'HR-SalaryBonusAndPenaltyForm' ">
              <i class="bi bi-journal-plus"></i>
              <span> إضافة بونص أو خصم </span>
              </button.><!-- End Dashboard Iamge Icon -->
          </li>
        </ol>
      </nav>
      <h1>جدول الأضافات والخصومات للمعلمين</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Journal</li>
          <li class="breadcrumb-item active"><?php echo $this_month ?></li>
          <li class="breadcrumb-item active"><?php echo $Next_month ?></li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <?php if (!isset($_GET['Code'])) { ?>
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body col-12 col-lg-6 m-auto">
                <h5 class="card-title text-center"> <?php echo " From :" . $this_month . " | " . " To :" . $Next_month; ?> </h5>
                <form id="form-2" class="mt-2 w-100 m-auto" method="GET" action="">
                  <select onchange="this.form.submit()" name="Start" class="form-select" aria-label="Default select example">
                    <option <?php if (isset($_GET['Start']) and $_GET['Start'] == "01") {
                              echo "selected";
                            } ?> value="01">يناير</option>
                    <option <?php if (isset($_GET['Start']) and $_GET['Start'] == "02") {
                              echo "selected";
                            } ?> value="02">فبراير</option>
                    <option <?php if (isset($_GET['Start']) and $_GET['Start'] == "03") {
                              echo "selected";
                            } ?> value="03">مارس</option>
                    <option <?php if (isset($_GET['Start']) and $_GET['Start'] == "04") {
                              echo "selected";
                            } ?> value="04">ابريل</option>
                    <option <?php if (isset($_GET['Start']) and $_GET['Start'] == "05") {
                              echo "selected";
                            } ?> value="05">مايو</option>
                    <option <?php if (isset($_GET['Start']) and $_GET['Start'] == "06") {
                              echo "selected";
                            } ?> value="06">يونيو</option>
                    <option <?php if (isset($_GET['Start']) and $_GET['Start'] == "07") {
                              echo "selected";
                            } ?> value="07">يوليو</option>
                    <option <?php if (isset($_GET['Start']) and $_GET['Start'] == "08") {
                              echo "selected";
                            } ?> value="08">اغسطس</option>
                    <option <?php if (isset($_GET['Start']) and $_GET['Start'] == "09") {
                              echo "selected";
                            } ?> value="09">سبتمبر</option>
                    <option <?php if (isset($_GET['Start']) and $_GET['Start'] == "10") {
                              echo "selected";
                            } ?> value="10">اكتوبر</option>
                    <option <?php if (isset($_GET['Start']) and $_GET['Start'] == "11") {
                              echo "selected";
                            } ?> value="11">نوفمبر</option>
                    <option <?php if (isset($_GET['Start']) and $_GET['Start'] == "12") {
                              echo "selected";
                            } ?> value="12">ديسمبر</option>
                  </select>

                  <select onchange="this.form.submit()" name="Y" class="form-select mt-2" aria-label="Default select example">
                    <?php for ($i = 2020; $i < date('Y', strtotime('+1 year')); $i++) { ?>
                      <option <?php if (isset($_GET['Y']) and $_GET['Y'] == $i) {
                                echo "selected";
                              } ?> value="<?php echo $i; ?>"> <?php echo $i; ?> </option>
                    <?php } ?>

                  </select>
                  <?php if (isset($_GET['Who'])) { ?>
                    <input type="hidden" name="Who" value="<?php echo $_GET['Who']; ?>">
                  <?php } ?>
                </form>


                <?php if (isset($_SESSION['manager']) or isset($_SESSION['CustomerServiceManager'])) { ?>
                  <h5 class="card-title text-center"> </h5>
                  <form id="form-2" class="mt-2 w-100 m-auto" method="GET" action="">
                    <select onchange="this.form.submit()" name="Who" class="form-select" aria-label="Default select example">
                      <option <?php if ($Who == "Hamza") {
                                echo "selected";
                              } ?> value="Hamza">حمزة</option>
                      <option <?php if ($Who == "Belal") {
                                echo "selected";
                              } ?> value="Belal">بلال</option>
                      <option <?php if ($Who == "Ramadan") {
                                echo "selected";
                              } ?> value="Ramadan">رمضان</option>
                      <option <?php if ($Who == "AbdelRahman") {
                                echo "selected";
                              } ?> value="AbdelRahman">عبد الرحمن</option>
                      <option <?php if ($Who == "Bedo") {
                                echo "selected";
                              } ?> value="Bedo"> بيدو </option>
                      <option <?php if ($Who == "All") {
                                echo "selected";
                              } ?> value="All">الجميع</option>
                    </select>
                    <?php if (isset($_GET['Start'])) { ?>
                      <input type="hidden" name="Start" value="<?php echo $_GET['Start']; ?>">
                    <?php } ?>
                  </form>
                <?php } ?>
              </div>
            </div>
          </div>
        <?php } ?>



        <div class="col-12">
          <div class="card h-100">
            <h5 class="card-title text-center"> جدول الأضافات والخصومات للمعلمين</h5>
            <div class="card-body  overflow-auto text-center ">
              <table class="table table-border text-center w-100 rtl">
                <thead>
                  <tr>
                    <th class="fw-bold fs-5 text-end"> Timestamp </th>
                    <th class="fw-bold fs-5 text-end"> الكود </th>
                    <th class="fw-bold fs-5 text-end"> الحالة </th>
                    <th class="fw-bold fs-5 text-end"> النوع </th>
                    <th class="fw-bold fs-5 text-end"> المبلغ </th>
                    <th class="fw-bold fs-5 text-end"> الطريقة </th>
                    <th class="fw-bold fs-5 text-end"> المسؤل </th>
                    <?php if (isset($_SESSION['manager']) or isset($_SESSION['CustomerServiceManager'])) { ?>
                      <th class="fw-bold fs-5 text-center"> إجراء </th>
                    <?php  } ?>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if ($Journal_count > 0) {
                    foreach ($JournalArry as $value) {
                  ?>
                      <tr class="<?php if ($value['Type'] == 'Bonus') {
                                    echo "table-success";
                                  } else {
                                    echo "table-danger";
                                  } ?>">
                        <td class="fw-bold fs-5 text-end"> <?php echo $value['Date'] ?> </td>
                        <td class="fw-bold fs-5 text-end"> <?php echo $value['Code'] ?> </td>
                        <td class="fw-bold fs-5 text-end"> <?php if ($value['Type'] == 'Bonus') {
                                                              echo "إضافة";
                                                            } else {
                                                              echo "خصم";
                                                            } ?> </td>
                        <td class="fw-bold fs-5 text-end"> <?php echo $value['Note'] ?> </td>
                        <td class="fw-bold fs-5 text-end"> <?php echo $value['Amount'] ?> </td>
                        <td class="fw-bold fs-5 text-end"> <?php echo $value['Payment_Way'] ?> </td>
                        <td class="fw-bold fs-5 text-end"> <?php echo $value['Who'] ?> </td>
                        <?php if (isset($_SESSION['manager']) or isset($_SESSION['CustomerServiceManager'])) { ?>
                          <td class="fw-bold fs-5 text-end">
                            <div class="btn-group ltr">
                              <a onclick="return confirm('هل انت متأكد من الحذف');" <?php echo " href='CustomerServiceJournalApi?DeleteBonus=" . $value['ID'] . "'" ?> class="btn btn-danger">حذف</a>
                              <a <?php echo " href='HR-SalaryBonusAndPenaltyEdite?Update=" . $value['ID'] . "'" ?> class="btn btn-success">تعديل</a>
                              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal<?php echo $value['ID'] ?>">
                                نسخ
                              </button>
                            </div>
                          </td>
                        <?php  } ?>
                      </tr>
                    <?php  } ?>
                  <?php } ?>
                </tbody>
              </table>
              <?php
              if ($Journal_count > 0) {
                foreach ($JournalArry as $value) {
              ?>

                  <div class="modal fade" id="modal<?php echo $value['ID'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal<?php echo $value['ID'] ?>Label" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h1 class="modal-title fs-5" id="modal<?php echo $value['ID'] ?>Label">Modal title</h1>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <div class="">
                            <div class="">
                              <form action="CustomerServiceJournalApi" method="get" id="Journal">

                                <div class="d-none">
                                  <input type="hidden" name="Amount" value="<?php echo $value['Amount'] ?>" class="form-control" id="Amount" required='true'>

                                </div>

                                <div class="d-none">
                                  <input type="hidden" name="Code" value="<?php echo $value['Code'] ?>" class="form-control" id="Amount" required='true'>

                                </div>

                                <!-- -----Payment-Way----- -->
                                <div class="d-none ">
                                </div>

                                <div class="d-none form-check">
                                  <input type="radio" name="Way" value="Wallet" class="form-check-input" id="Way1" required='true' <?php if ("Wallet" == $value['Payment_Way']) {
                                                                                                                                      echo 'checked';
                                                                                                                                    } ?>>
                                </div>

                                <div class="d-none form-check">
                                  <input type="radio" name="Way" value="Bank" class="form-check-input" id="Way2" required='true' <?php if ("Bank" == $value['Payment_Way']) {
                                                                                                                                    echo 'checked';
                                                                                                                                  } ?>>
                                </div>

                                <div class="d-none form-check">
                                  <input type="radio" name="Way" value="EasyKash" class="form-check-input" id="Way3" required='true' <?php if ("EasyKash" == $value['Payment_Way']) {
                                                                                                                                        echo 'checked';
                                                                                                                                      } ?>>
                                </div>

                                <div class="d-none form-check">
                                  <input type="radio" name="Way" value="Westren" class="form-check-input" id="Way4" required='true' <?php if ("Westren" == $value['Payment_Way']) {
                                                                                                                                      echo 'checked';
                                                                                                                                    } ?>>
                                </div>

                                <div class="d-none form-check">
                                  <input type="radio" name="Way" value="PayPal" class="form-check-input" id="Way5" required='true' <?php if ("PayPal" == $value['Payment_Way']) {
                                                                                                                                      echo 'checked';
                                                                                                                                    } ?>>
                                </div>

                                <div class="d-none form-check">
                                  <input type="radio" name="Way" value="Cash" class="form-check-input" id="Way6" required='true' <?php if ("Cash" == $value['Payment_Way']) {
                                                                                                                                    echo 'checked';
                                                                                                                                  } ?>>
                                </div>

                                <!-- --------- -->

                                <!-- -----Renewal VS Trail----- -->
                                <div class="d-none ">
                                </div>

                                <div class="d-none form-check">
                                  <input type="radio" name="Type" value="Bonus" class="form-check-input" id="Type1" required='true' <?php if ("Bonus" == $value['Type']) {
                                                                                                                                      echo 'checked';
                                                                                                                                    } ?>>
                                </div>

                                <div class="d-none form-check">
                                  <input type="radio" name="Type" value="Penalty" class="form-check-input" id="Type2" required='true' <?php if ("Penalty" == $value['Type']) {
                                                                                                                                        echo 'checked';
                                                                                                                                      } ?>>
                                </div>


                                <!-- --------- -->

                                <div class="mb-3">
                                  <label for="Date" class="form-label"> نسخ الى  </label>
                                  <input type="date" name="Date" class="form-control" id="Date" value="<?php echo $value['Date'] ?>" required='true'>

                                </div>

                                <div class="d-none">
                                  <input type="hidden" name="Note" class="form-control" id="Note" value="<?php echo $value['Note'] ?>">

                                </div>

                                <div class="d-none">
                                  <input type="hidden" name="Who" value="<?php echo $value['Who'] ?>" class="form-control" id="Who" required='true'>
                                </div>

                                <div class="d-none">
                                  <input type="hidden" name="CustomerServiceBonus" value="CustomerServiceBonus" class="form-control" id="CustomerServiceBonus" required='true'>
                                </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-primary">submit</button>
                            </div>
                            </form>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
            </div>
        <?php }
              } ?>
          </div>
        </div>
      </div>

      <div class="toast-container position-fixed bottom-0 end-0 p-3" style=" z-index: 99999;">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false">
          <div class="toast-header">
            <strong class="me-auto">أكاديمية الرؤى</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
          <div class="toast-body rtl fs-6 fw-bold text-danger">
            <?php if (!empty($_SESSION['Emessage'])) {
              echo $_SESSION['Emessage'];
            } ?>
          </div>
        </div>
      </div>

  </div>

  </section>
  </div>

</main><!-- End #main -->
<?php include "assets/tem/footer.php" ?>
<?php
if (!empty($_SESSION['Emessage'])) { ?>
  <script>
    $(document).ready(function() {
      $(".toast").toast('show');
    });
  </script>
<?php unset($_SESSION['Emessage']);
} ?>
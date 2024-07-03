<?php 
ob_start(); 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 
if (!isset($_SESSION['Admin']) AND !isset($_SESSION['Suber_Admin']) AND !isset($_SESSION['manager']) ) {  header('Location: index');  exit;  } 
include "assets/tem/header.php" ; ?>
<main id="main" class="main">
  <div class="container">

    <div class="pagetitle">
      <h1>Evaluation Items Page</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Evaluation Items</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    
    <section class="section">
      <?php if (!isset($_GET['Add']) AND !isset($_GET['Insert']) AND !isset($_GET['Edite']) AND !isset($_GET['Update']) AND !isset($_GET['Delete']) ) { ?>
        <div class="row">
          <div class="col-lg-6 m-auto  ">
            <div class="card">
              <div class="card-body">
                <a href="Items?Add" class="btn btn-outline-primary fs-6 fw-bold w-100  mt-2">إضافة بند جديد</a>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-6 m-auto  ">

          </div>
          </div>
        </div>
      <?php } ?>
      <div class="row">

        <?php if (isset($_GET['Add'])) { ?>
          <div class="col-lg-6 m-auto  ">

            <div class="card">
            <div class="card-body">
            <h5 class="card-title text-center"> إضافة بند جديد </h5> 

            <form class="mt-2" method="POST" action="Items?Insert">

            <div class="input-group mb-3">
            <label class="input-group-text mt-2" for="trust">لمن</label>
            <select name="trust" class="form-control form-select form-select-lg  mt-2" aria-label=".form-select-lg example"required='true'>
            <option     selected disabled value="">اختر</option>
            <option value="1" >Teachers</option>
            <option value="3" >Supervisors</option>
            <option value="4" >Supervising Manager</option>
            <option value="5" >Admins</option>
            <option value="6" >Super Admins</option>
            <option value="8" >Customer Service</option>
            </select>
            </div>

            <div class="input-group mb-3">
            <label class="input-group-text mt-2" for="text">نص البند</label>
            <input  class="form-control form-control-lg text-center  mt-2" name="text" type="text" id="text"  aria-label="Example text with button addon" aria-describedby="button-addon1 button-addon2">
            </div>
            
            <div class="input-group mb-3">
            <label class="input-group-text mt-2" for="number">الدرجات</label>
            <button class="btn btn-outline-primary  fs-6 fw-bold  mt-2 " type="button" id="button-addon1" onclick="incrementValue()">+</button>
            <input readonly class="form-control form-control-lg text-center  mt-2" name="mark" type="text" id="number" value="0" aria-label="Example text with button addon" aria-describedby="button-addon1 button-addon2">
            <button class="btn btn-outline-primary  fs-6 fw-bold  mt-2 " type="button" id="button-addon2" onclick="decrementValue()">-</button>
            </div>

            <div class="form-check form-check-inline mt-2">
            <input class="form-check-input" type="radio" name="status" id="inlineRadio1" value="+">
            <label class="form-check-label" for="inlineRadio1">اضافة</label>
            </div>
            <div class="form-check form-check-inline mt-2">
            <input class="form-check-input" type="radio" name="status" id="inlineRadio2" value="-">
            <label class="form-check-label" for="inlineRadio2">خصم</label>
            </div>

            <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 " type="submit" name="Get-All"> إنشاء البند </button>
            </form>
            <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button> 
            </div>
            </div>

          </div>
        <?php }elseif (isset($_GET['Insert'])) { 
          $text = filter_var($_POST["text"], FILTER_UNSAFE_RAW );
          $trust = filter_var($_POST["trust"], FILTER_UNSAFE_RAW );
          $Mark = filter_var($_POST["mark"], FILTER_UNSAFE_RAW );
          $Status = filter_var($_POST["status"], FILTER_UNSAFE_RAW );

          $d_stmt = $con->prepare("INSERT INTO items (`text`, `trust`, `mark` , `status`) VALUES (?,?,?,?)");
          $d_stmt->execute(array(  $text, $trust , $Mark  , $Status ));
          $xcount = $d_stmt->rowCount();

          ?> 
          <div class="col-lg-6 m-auto">
          <div class="card">
          <div class="card-body">
          <h5 class="card-title text-center"> إضافة بند جديد </h5> 
          <?php
          if ($xcount > 0  ) {
            $_SESSION['message'] = 'تم إضافة البند بنجاح';
            header('location: Items');
            exit;
          } else { 
            $_SESSION['message'] = 'لم يتم إضافة البند ';
            header('location: Items');
            exit;
          }
          ?>
          </div>
          </div>
          </div> 
        <?php }elseif (isset($_GET['Edite'])) { 
          $stmt = $con->prepare("SELECT * FROM items WHERE ID=? ");
          $stmt->execute(array($_GET['Edite']));
          $item = $stmt->fetch();
          ?>
          <div class="col-lg-6 m-auto">

            <div class="card">
            <div class="card-body">
            <h5 class="card-title text-center"> تعديل بند  </h5> 

            <form class="mt-2" method="POST" action="Items?Update">

            <input  name="ID" type="hidden" id="ID" value="<?php echo $item['ID']?>" >

            <div class="input-group mb-3">
            <label class="input-group-text mt-2" for="trust">لمن</label>
            <select name="trust" class="form-control form-select form-select-lg  mt-2" aria-label=".form-select-lg example"required='true'>
            <option     selected disabled value="">اختر</option>
            <option value="1" <?php if($item['trust'] == "1"){echo "selected";}?> >Teachers</option>
            <option value="3" <?php if($item['trust'] == "3"){echo "selected";}?> >Supervisors</option>
            <option value="4" <?php if($item['trust'] == "4"){echo "selected";}?> >Supervising Manager</option>
            <option value="5" <?php if($item['trust'] == "5"){echo "selected";}?> >Admins</option>
            <option value="6" <?php if($item['trust'] == "6"){echo "selected";}?> >Super Admins</option>
            <option value="6" <?php if($item['trust'] == "8"){echo "selected";}?> >Customer Service</option>
            </select>
            </div>

            <div class="input-group mb-3">
            <label class="input-group-text mt-2" for="text">نص البند</label>
            <input  class="form-control form-control-lg text-center  mt-2" name="text" type="text" id="text" value="<?php echo $item['text']?>" aria-label="Example text with button addon" aria-describedby="button-addon1 button-addon2">
            </div>
            
            <div class="input-group mb-3">
            <label class="input-group-text mt-2" for="number">الدرجات</label>
            <button class="btn btn-outline-primary  fs-6 fw-bold  mt-2 " type="button" id="button-addon1" onclick="incrementValue()">+</button>
            <input readonly class="form-control form-control-lg text-center  mt-2" name="mark" type="text" id="number" value="<?php echo $item['mark']?>" aria-label="Example text with button addon" aria-describedby="button-addon1 button-addon2">
            <button class="btn btn-outline-primary  fs-6 fw-bold  mt-2 " type="button" id="button-addon2" onclick="decrementValue()">-</button>
            </div>

            <div class="form-check form-check-inline mt-2">
            <input class="form-check-input" type="radio" name="status" id="inlineRadio1" value="+" <?php if($item['status'] == "+"){echo "checked";}?>>
            <label class="form-check-label" for="inlineRadio1">اضافة</label>
            </div>
            <div class="form-check form-check-inline mt-2">
            <input class="form-check-input" type="radio" name="status" id="inlineRadio2" value="-" <?php if($item['status'] == "-"){echo "checked";}?> >
            <label class="form-check-label" for="inlineRadio2">خصم</label>
            </div>

            <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2 " type="submit" name="Get-All"> تعديل البند </button>
            </form>
            <button class="btn btn-outline-primary fs-6 fw-bold w-100 mt-2" onclick="history.back()" >عودة</button> 
            </div>
            </div>

          </div>
        <?php }elseif (isset($_GET['Update'])) {  
          $ID = filter_var($_POST["ID"], FILTER_UNSAFE_RAW );
          $text = filter_var($_POST["text"], FILTER_UNSAFE_RAW );
          $trust = filter_var($_POST["trust"], FILTER_UNSAFE_RAW );
          $Mark = filter_var($_POST["mark"], FILTER_UNSAFE_RAW );
          $Status = filter_var($_POST["status"], FILTER_UNSAFE_RAW );

          $stmt = $con->prepare("UPDATE  items SET `text`=?   , trust=? , `mark`=?, `status`=?  WHERE ID =? ");
          $stmt->execute(array( $text , $trust  , $Mark, $Status , $ID  ));
          $xcount = $stmt->rowCount();

          ?> 
          <div class="col-lg-6 m-auto">
          <div class="card">
          <div class="card-body">
          <h5 class="card-title text-center"> تعديل بند  </h5> 
          <?php
          if ($xcount > 0  ) {
            $_SESSION['message'] = 'تم تعديل البند بنجاح';
            header('location: Items');
            exit;
           } else { 
            $_SESSION['message'] = 'لم يتم تعديل البند ';
            header('location: Items');
            exit;
          }
          ?>
          </div>
          </div>
          </div> 
        <?php }elseif (isset($_GET['Delete'])) { 
          $ID = filter_var($_GET["Delete"], FILTER_UNSAFE_RAW );
          $stmt = $con->prepare("DELETE FROM items WHERE ID= ? ");
          $stmt->execute(array($ID));
          $xcount = $stmt->rowCount();

          ?> 
          <div class="col-lg-6 m-auto">
          <div class="card">
          <div class="card-body">
          <h5 class="card-title text-center"> حذف بند </h5> 
          <?php
          if ($xcount > 0  ) {
            $_SESSION['message'] = 'تم حذف البند بنجاح';
            header('location: Items');
            exit;
          } else { 
            $_SESSION['message'] = 'لم يتم حذف البند ';
            header('location: Items');
            exit;
          }
          ?>
          </div>
          </div>
          </div> 
        <?php }else { 
          $evaluation_user_stmt = $con->prepare("SELECT * FROM items ");
          $evaluation_user_stmt->execute(array());
          $evaluation_user_fetch = $evaluation_user_stmt->fetchAll();
          ?>
          <div class="col-lg-6 m-auto">
            <div class="card">
            <h5 class="card-title   text-center"> بنود التقييم </h5>
            <div class="card-body overflow-auto rtl text-center">
            <table class="table table-borderless text-center  ">
              <thead >
              <tr>
              <th  scope="col">نص التقييم</th>
              <th scope="col">لمن</th>
              <th scope="col">النوع</th>
              <th scope="col">تخصيص</th>
              </tr>
              </thead>
              <tbody>

              <?php 
              if (empty($evaluation_user_fetch)) {
                ?> 
                <tr><td class="fw-bold fs-5 " colspan="5"><span class="btn btn-danger fs-6 fw-bold w-100  mt-2"> لا توجد بنود مسجلة حتى الآن </span></td> </tr>
                <?php
                }
                ?> 
                <tr><td class="fw-bold fs-5 " colspan="5"><span class="btn btn-danger fs-6 fw-bold w-100  mt-2">Teachers</span></td> </tr>
                <?php
              foreach ($evaluation_user_fetch as $key => $value) { 
                if ($value['trust'] == "1"){
                ?>
                <tr>
                <td> <?php echo $value['text'] ?> </td>
                <td> Teachers </td>
                <td><span class="<?php if ($value['status'] == "-") { echo "badge bg-danger"; }else { echo "badge bg-success"; } ?>"><?php if ($value['status'] == "-") { echo "خصم"." ".$value['mark']." "."نقط"; }else { echo "اضافة"." ".$value['mark']." "."نقطة" ;} ?></span></td>
                <td>
                <p><a class="btn btn-outline  fs-6 fw-bold mt-2" href="Items?Edite=<?php echo $value['ID'] ?>"><lord-icon src="https://cdn.lordicon.com/hbigeisx.json" trigger="loop-on-hover" colors="primary:#121331"style="width:25px;height:25px"></lord-icon></i></a>&nbsp;<a class="btn btn-outline  fs-6 fw-bold mt-2"  onclick="return confirm('هل انت متأكد من الحذف ؟');" href="Items?Delete=<?php echo $value['ID'] ?>"><lord-icon src="https://cdn.lordicon.com/jmkrnisz.json"    trigger="loop-on-hover"  colors="primary:#121331"style="width:25px;height:25px"></lord-icon></a></p>
                </td>
                </tr>
              <?php }} ?>
              <tr><td class="fw-bold fs-5 " colspan="5"><span class="btn btn-danger fs-6 fw-bold w-100  mt-2">Supervisors</span></td> </tr>
              <?php foreach ($evaluation_user_fetch as $key => $value) { 
                if ($value['trust'] == "3"){
                ?>
                <tr>
                <td> <?php echo $value['text'] ?> </td>
                <td> Supervisors </td>
                <td><span class="<?php if ($value['status'] == "-") { echo "badge bg-danger"; }else { echo "badge bg-success"; } ?>"><?php if ($value['status'] == "-") { echo "خصم"." ".$value['mark']." "."نقط"; }else { echo "اضافة"." ".$value['mark']." "."نقطة" ;} ?></span></td>
                <td>
                <p><a class="btn btn-outline  fs-6 fw-bold mt-2" href="Items?Edite=<?php echo $value['ID'] ?>"><lord-icon src="https://cdn.lordicon.com/hbigeisx.json" trigger="loop-on-hover" colors="primary:#121331"style="width:25px;height:25px"></lord-icon></i></a>&nbsp;<a class="btn btn-outline  fs-6 fw-bold mt-2"  onclick="return confirm('هل انت متأكد من الحذف ؟');" href="Items?Delete=<?php echo $value['ID'] ?>"><lord-icon src="https://cdn.lordicon.com/jmkrnisz.json"    trigger="loop-on-hover"  colors="primary:#121331"style="width:25px;height:25px"></lord-icon></a></p>
                </td>
                </tr>
              <?php }} ?>
              <tr><td class="fw-bold fs-5 " colspan="5"><span class="btn btn-danger fs-6 fw-bold w-100  mt-2">Supervising Manager</span></td> </tr>
              <?php foreach ($evaluation_user_fetch as $key => $value) { 
                if ($value['trust'] == "4"){
                ?>
                <tr>
                <td> <?php echo $value['text'] ?> </td>
                <td> Supervising Manager </td>
                <td><span class="<?php if ($value['status'] == "-") { echo "badge bg-danger"; }else { echo "badge bg-success"; } ?>"><?php if ($value['status'] == "-") { echo "خصم"." ".$value['mark']." "."نقط"; }else { echo "اضافة"." ".$value['mark']." "."نقطة" ;} ?></span></td>
                <td>
                <p><a class="btn btn-outline  fs-6 fw-bold mt-2" href="Items?Edite=<?php echo $value['ID'] ?>"><lord-icon src="https://cdn.lordicon.com/hbigeisx.json" trigger="loop-on-hover" colors="primary:#121331"style="width:25px;height:25px"></lord-icon></i></a>&nbsp;<a class="btn btn-outline  fs-6 fw-bold mt-2"  onclick="return confirm('هل انت متأكد من الحذف ؟');" href="Items?Delete=<?php echo $value['ID'] ?>"><lord-icon src="https://cdn.lordicon.com/jmkrnisz.json"    trigger="loop-on-hover"  colors="primary:#121331"style="width:25px;height:25px"></lord-icon></a></p>
                </td>
                </tr>
              <?php }} ?>
              <tr><td class="fw-bold fs-5 " colspan="5"><span class="btn btn-danger fs-6 fw-bold w-100  mt-2"> Admins</span></td> </tr>
              <?php foreach ($evaluation_user_fetch as $key => $value) { 
                if ($value['trust'] == "5"){
                ?>
                <tr>
                <td> <?php echo $value['text'] ?> </td>
                <td> Admins </td>
                <td><span class="<?php if ($value['status'] == "-") { echo "badge bg-danger"; }else { echo "badge bg-success"; } ?>"><?php if ($value['status'] == "-") { echo "خصم"." ".$value['mark']." "."نقط"; }else { echo "اضافة"." ".$value['mark']." "."نقطة" ;} ?></span></td>
                <td>
                <p><a class="btn btn-outline  fs-6 fw-bold mt-2" href="Items?Edite=<?php echo $value['ID'] ?>"><lord-icon src="https://cdn.lordicon.com/hbigeisx.json" trigger="loop-on-hover" colors="primary:#121331"style="width:25px;height:25px"></lord-icon></i></a>&nbsp;<a class="btn btn-outline  fs-6 fw-bold mt-2"  onclick="return confirm('هل انت متأكد من الحذف ؟');" href="Items?Delete=<?php echo $value['ID'] ?>"><lord-icon src="https://cdn.lordicon.com/jmkrnisz.json"    trigger="loop-on-hover"  colors="primary:#121331"style="width:25px;height:25px"></lord-icon></a></p>
                </td>
                </tr>
              <?php }} ?>
              <tr><td class="fw-bold fs-5 " colspan="5"><span class="btn btn-danger fs-6 fw-bold w-100  mt-2"> Super Admins</span></td> </tr>
              <?php foreach ($evaluation_user_fetch as $key => $value) { 
                if ($value['trust'] == "6"){
                ?>
                <tr>
                <td> <?php echo $value['text'] ?> </td>
                <td> Super Admins </td>
                <td><span class="<?php if ($value['status'] == "-") { echo "badge bg-danger"; }else { echo "badge bg-success"; } ?>"><?php if ($value['status'] == "-") { echo "خصم"." ".$value['mark']." "."نقط"; }else { echo "اضافة"." ".$value['mark']." "."نقطة" ;} ?></span></td>
                <td>
                <p><a class="btn btn-outline  fs-6 fw-bold mt-2" href="Items?Edite=<?php echo $value['ID'] ?>"><lord-icon src="https://cdn.lordicon.com/hbigeisx.json" trigger="loop-on-hover" colors="primary:#121331"style="width:25px;height:25px"></lord-icon></i></a>&nbsp;<a class="btn btn-outline  fs-6 fw-bold mt-2"  onclick="return confirm('هل انت متأكد من الحذف ؟');" href="Items?Delete=<?php echo $value['ID'] ?>"><lord-icon src="https://cdn.lordicon.com/jmkrnisz.json"    trigger="loop-on-hover"  colors="primary:#121331"style="width:25px;height:25px"></lord-icon></a></p>
                </td>
                </tr>
              <?php }} ?>
              <tr><td class="fw-bold fs-5 " colspan="5"><span class="btn btn-danger fs-6 fw-bold w-100  mt-2"> Customer Service</span></td> </tr>
              <?php foreach ($evaluation_user_fetch as $key => $value) { 
                if ($value['trust'] == "8"){
                ?>
                <tr>
                <td> <?php echo $value['text'] ?> </td>
                <td>Customer Service</td>
                <td><span class="<?php if ($value['status'] == "-") { echo "badge bg-danger"; }else { echo "badge bg-success"; } ?>"><?php if ($value['status'] == "-") { echo "خصم"." ".$value['mark']." "."نقط"; }else { echo "اضافة"." ".$value['mark']." "."نقطة" ;} ?></span></td>
                <td>
                <p><a class="btn btn-outline  fs-6 fw-bold mt-2" href="Items?Edite=<?php echo $value['ID'] ?>"><lord-icon src="https://cdn.lordicon.com/hbigeisx.json" trigger="loop-on-hover" colors="primary:#121331"style="width:25px;height:25px"></lord-icon></i></a>&nbsp;<a class="btn btn-outline  fs-6 fw-bold mt-2"  onclick="return confirm('هل انت متأكد من الحذف ؟');" href="Items?Delete=<?php echo $value['ID'] ?>"><lord-icon src="https://cdn.lordicon.com/jmkrnisz.json"    trigger="loop-on-hover"  colors="primary:#121331"style="width:25px;height:25px"></lord-icon></a></p>
                </td>
                </tr>
              <?php }} ?>
              </tbody>
            </table>
            </div>

            </div>
            </div>
          </div>
        <?php } ?>
        <div class="toast-container position-fixed bottom-0 end-0 p-3" style=" z-index: 99999;">
          <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false">
            <div class="toast-header">
              <strong class="me-auto">أكاديمية الرؤى</strong>
              <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body rtl fs-6 fw-bold">
            <?php if (!empty($_SESSION['message'])) {echo $_SESSION['message'];}?>
            </div>
          </div>
        </div>
      </div>

    </section>

  </div>



</main><!-- End #main -->

<script>
  function submit_form(textarea,form,back,Do,Cancel,spinner) {

  }
  function incrementValue()
  {
  var value = parseInt(document.getElementById('number').value, 10);
  value = isNaN(value) ? 0 : value;
  value++;
  document.getElementById('number').value = value + 1;
  }
  function decrementValue()
  {
  var value = parseInt(document.getElementById('number').value, 10);
  if (value >= 5) {
  value = isNaN(value) ? 0 : value;
  value--;
  document.getElementById('number').value = value - 1;
  }

  }
  </script>
<?php include "assets/tem/footer.php" ;
if (!empty($_SESSION['message'])) { ?>
  <script>
    $(document).ready(function() {
        $(".toast").toast('show');
    });
</script>
<?php unset($_SESSION['message']); } ?>


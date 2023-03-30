<?php include 'db/db.php'; ?>
<?php include 'theme/head.php'; ?>

<?php
if (isset($_POST['submit'])) {
  $username = $_POST['uname'];
  $password = $_POST['upassword'];
  $hash = sha1($password);



  $all = $db->query("SELECT id,uname,upassword FROM user WHERE uname = '$username' AND upassword = '$hash' LIMIT 1");
  $data = $all->fetch(PDO::FETCH_ASSOC);

  if ($all->rowCount() > 0) {
    $id = $data['id'];
    $user = $data['uname'];

    $_SESSION['id'] = $id;
    $_SESSION['user'] = $user;

    if ($user == 'admin') {
      header('location: admin/dashboard.php');
    } elseif ($user == 'editor') {
      header('location: dashboard.php');
    } elseif ($user == 'user') {
      header('location: dashboard.php');
    } else {
      header('location: index.php');
    }
  } else {
    $error = 'incorrect login details';
  }
}


if (isset($error)) { ?>
  <br><br><br>
  <div class="alert alert-danger alert-dismissable">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong><?php echo $error; ?>.</strong>
  </div>
<?php
}
?>

<div class="w3-main">

  <div class="w3-bar w3-top w3-large w3-mobile" style="z-index:4; background-color:#294257; color:white;">
    <div class="w3-row">
      <div class="w3-col" style="width:30%">
        <button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey" onclick="w3_open();"><i class="fa fa-bars"></i></button>
        <h3><span class="w3-bar-item w3-hide-small w3-left">HR Digitization</span></h3>
      </div>
      <div class="w3-col" style="width:70%">
        <span class="w3-bar-item w3-right w3-hide-small">Active Talents Monitoring ER Application</span>
        <span class="w3-bar-item w3-right w3-hide-large">ER Application</span>
      </div>
    </div>
  </div>


  <div class="w3-container w3-quarter w3-margin-top w3-card-4 w3-display-middle w3-mobile">
    <form class="w3-mobile" method="post">

      <div class="w3-center w3-mobile" style='padding-bottom:20px'>
        <img src="/theme/default_avatar.jpg" style="width:120px; padding-top:30px; padding-bottom:20px;">
        <h3 style="font-size:23px; padding:0; margin:0; font-weight: 800;">User Login</h3>
      </div>

      <div class="form-group w3-mobile">
        <label class="w3-label">Name</label>
        <input class="w3-input" type="text" name="uname" required>
      </div>

      <div class="form-group w3-mobile">
        <label class="w3-label">Password</label>
        <input class="w3-input" type="password" name="upassword" required>
      </div>

      <div class="form-group w3-mobile">
        <input type="submit" value="Login" class="w3-btn w3-section w3-teal w3-ripple w3-mobile" name="submit" style="width:100%">
      </div>

    </form>
  </div>

</div>

<?php include 'theme/foot.php'; ?>
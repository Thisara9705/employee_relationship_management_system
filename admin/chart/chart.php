<?php
   // include("../Connection.php");
?>

 <?php
$server = "localhost";
$user = "u778501372_mas_er";
$pass = "#1Pp>]c3w71k";
$db = "u778501372_mas_er";

$conn = mysqli_connect($server,$user,$pass,$db);
//if($conn){
//	echo "Success!";
//}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
body {
  font-family: Arial, Helvetica, sans-serif;
}
</style>

    <title>Document</title>

    <link rel="stylesheet" href="style.css">

</head>
<body style="background-color:#0d0d0d">
  <!--  <h1>Test</h1>
    <h3>test</h3>

    <div class="flip-card">
        <div class="flip-card-inner">
            <div class="flip-card-front">
                <h2>hhh</h2>
            </div>
            <div class="flip-card-back">
                <h1>A</h1>
                <p>B</p>
                <p>C</p>
            </div>
        </div>
    </div>  -->

    <?php
     // $sql = "SELECT * FROM cards WHERE attendance = 'Present'";
     $sql = "SELECT * FROM attendance WHERE Attendance = 'Present'";
      $resultset = mysqli_query($conn,$sql);
      while($record = mysqli_fetch_assoc($resultset)){
        ?>

        <div class="flip-card">
          <div class="flip-card-inner">
              <div class="flip-card-front">
                  <h4><?php echo $record['name']; ?></h4>
              </div>

        <div class="flip-card-back">
            <h5><?php echo $record['description']; ?></h5>
    <!--        <h5><?php echo $record['address']; ?></h5>  -->
        </div>

          </div>
        </div>

  <?php    }?>

  <!--##########################################################################################-->

  <?php
      //$sql1 = "SELECT * FROM cards WHERE attendance = 'Absent'";    
      $sql1 = "SELECT * FROM attendance WHERE Attendance = 'Absent'";
      $resultset1 = mysqli_query($conn,$sql1);
      while($record1 = mysqli_fetch_assoc($resultset1)){
        ?>

        <div class="flip-card1">
          <div class="flip-card-inner1">
              <div class="flip-card-front1">
                  <h4><?php echo $record1['name']; ?></h4>
              </div>

        <div class="flip-card-back1">
            <h5><?php echo $record1['description']; ?></h5>
    <!--        <h5><?php echo $record1['address']; ?></h5>  -->
        </div>

          </div>
        </div>

  <?php    }?>

</body>
</html>
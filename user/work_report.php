<?php include '../db/db.php'; ?>
<?php include 'theme/head.php'; ?>
<?php 
    $data = $db->query("SELECT MAX(Updated_time) FROM attendance")->fetch();
    $update = $data[0];
?>
<div class="w3-bar w3-top w3-large w3-mobile" style="z-index:4; background-color:#294257; color:white;">
  <div class="w3-row">
    <div class="w3-col" style="width:45%">
      <h4><span class="w3-bar-item w3-hide-large w3-hide-medium w3-left">HR Digitization</span></h4>
      <h4><span class="w3-bar-item w3-hide-large w3-hide-small w3-left">Synergy HR Digitization</span></h4>
      <h3><span class="w3-bar-item w3-hide-small w3-hide-medium w3-left">Synergy HR Digitization</span></h3>
    </div>
    <div class="w3-col" style="width:55%">
        <div class="w3-row">
            <span class="w3-bar-item w3-right w3-hide-small w3-hide-medium">Last Update : <?php echo $update; ?></span>
        </div>
    </div>
  </div>
</div>  
<div class="w3-main" style="margin-top:43px;">

<!-- Header -->
    <header class="w3-container" style="padding-top:22px">
        <h3><a href="index.php" style="color:black;"><i class="fa fa-angle-left" style="font-weight:1000;"></i></a>&nbsp; &nbsp;<b><i class="fa fa-calendar-check-o"></i> Work Reporting Date</b></h3>
    </header>

<p class="text-center" style="margin-top: 3%"></p>

<div class="w3-row-padding w3-margin-bottom">
    <?php
    $today = date('d.m.Y',strtotime("-1 days"));

    for($i=1; $i<=366; $i++){
        $repeat = strtotime("+1 day",strtotime($today));
        $today = date('Y-m-d',$repeat);
        
        $date = $today;
        $all = $db->query("SELECT a.PN,a.Reason,a.Date_To,a.a_date,COUNT(a.PN) AS count FROM emp e INNER JOIN team t ON e.Team=t.Team INNER JOIN attendance a ON e.PN=a.PN  WHERE Reason IN ('C1','Symptoms','Positive') AND Date_To='$date' AND DATE(a_date) = CURDATE() AND DATE(c_date) = CURDATE() AND DATE(Entered_date) = CURDATE()");
        $fetch = $all->fetchAll(PDO::FETCH_OBJ);
        foreach($fetch as $data){ 
    ?>
        <div class="w3-quarter" style="margin-bottom:10px;">
            <a href="details22.php?date=<?php echo $today ?>" style="text-decoration:none; color:white;">
            <div class="w3-container w3-red  w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><h4><?php echo $today ?></h4></div>
                <div class="w3-right">
                <h4>
                <?php echo $data->count ?>
                </h4>
                </div>
            </div>
            </a>
        </div>
    <?php
    }
    }
    ?>    
</div> 


<?php include 'theme/foot.php'; ?>
<?php include '../db/db.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:250px;margin-top:43px;">

<!-- Header -->
    <header class="w3-container" style="padding-top:22px">
        <h3><b><i class="fa fa-bar-chart"></i> Old Absent Reports</b></h3>
    </header>

<p class="text-center" style="margin-top: 3%"></p>

<div class="w3-row-padding w3-margin-bottom">
    <?php
    $today = date('Y-m-d');

    for($i=1; $i<=366; $i++){
        $repeat = strtotime("-1 day",strtotime($today));
        $today = date('Y-m-d',$repeat);
    ?>
        <div class="w3-quarter" style="margin-bottom:10px;">
            <div class="w3-container w3-red  w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><h4><?php echo $today ?></h4></div>
                <div class="w3-right">
                <a href="details19.php?date=<?php echo $today ?>" style="text-decoration:none; color:white;"><i class="fa fa-angle-right w3-xxxlarge"></i></a>
                </div>
            </div>
        </div>
    <?php
    }
    ?>    
</div> 
>

<?php include 'theme/foot.php'; ?>
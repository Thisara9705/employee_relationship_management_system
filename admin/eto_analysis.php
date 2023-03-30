<?php include '../db/db.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:250px;margin-top:43px;">

<!-- Header -->
    <header class="w3-container" style="padding-top:22px">
        <h3><b><i class="fa fa-bar-chart"></i>  ETO Analysis</b></h3>
    </header>

<p class="text-center" style="margin-top: 3%"></p>

<div class="w3-row-padding w3-margin-bottom">
    <?php
    $start = new DateTime;
    $start->setDate($start->format('Y'), $start->format('n'), 1); 
    $start->setTime(0, 0, 0); 
    $start->sub(new DateInterval('P12M'));
    $interval = new DateInterval('P1M');
    $recurrences = 12;
    
    foreach (new DatePeriod($start, $interval, $recurrences, true) as $date) {


    ?>
        <div class="w3-quarter" style="margin-bottom:10px;">
            <a href="eto_vsl.php?date=<?php echo $date->format('F, Y') ?>" style="text-decoration:none; color:white;">
            <div class="w3-container w3-red  w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><h4><?php echo $date->format('F, Y') ?></h4></div>
                <div class="w3-right">
                <i class="fa fa-angle-right w3-xxxlarge"></i>
                </div>
            </div>
            </a>
        </div>
    <?php
    }
    ?>    
</div> 

<?php include 'theme/foot.php'; ?>
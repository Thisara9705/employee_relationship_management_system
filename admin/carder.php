<?php include '../db/db.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:250px;margin-top:43px;">

<!-- Header -->
    <header class="w3-container" style="padding-top:22px">
        <h3><b><i class="fa fa-bar-chart"></i> Carder Repoart</b></h3>
    </header>

<p class="text-center" style="margin-top: 3%"></p>

<div class="w3-row-padding w3-margin-bottom">
        <div class="w3-half" style="margin-bottom:10px;">
            <a href="area.php" style="text-decoration:none; color:white;">
            <div class="w3-container w3-red w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><h4>Area Vice</h4></div>
                <div class="w3-right">
                <i class="fa fa-angle-right w3-xxxlarge"></i>
                </div>
            </div>
            </a>
        </div>
        <div class="w3-half" style="margin-bottom:10px;">
            <a href="team.php" style="text-decoration:none; color:white;">
            <div class="w3-container w3-red  w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><h4>Team Vice</h4></div>
                <div class="w3-right">
                <i class="fa fa-angle-right w3-xxxlarge"></i>
                </div>
            </div>
            </a>
        </div>
</div> 

<?php include 'theme/foot.php'; ?>
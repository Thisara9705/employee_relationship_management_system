<?php include '../db/db.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:250px;margin-top:43px;">

<!-- Header -->
    <header class="w3-container" style="padding-top:22px">
        <h3><b><i class="fa fa-building"></i>  Employees Details</b></h3>
    </header>

<p class="text-center" style="margin-top: 3%"></p>

<div class="w3-row-padding w3-margin-bottom">
    

        <?php

            $area = $_GET['area'];
            $shift = $_GET['shift'];
            $all = $db->query("SELECT e.Team,t.Area,a.Attendance,t.Shift,COUNT(DISTINCT a.PN) attendance_count FROM emp e INNER JOIN team t ON e.Team=t.Team INNER JOIN attendance a ON e.PN=a.PN WHERE Area='$area' AND Shift='$shift' AND DATE(Entered_date) = CURDATE() GROUP BY Team ;");
            $fetch = $all->fetchAll(PDO::FETCH_OBJ);
            foreach($fetch as $data){ 

        ?>
        <div class=" w3-third" style="margin-bottom:20px;">
        <div class="w3-container w3-red w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><i class="fa fa-clone w3-xxxlarge"></i></div>
        <div class="w3-right">
            <h3>
            <a href="details17.php?team=<?php echo $data->Team ?>" style="text-decoration:none; color:white;"><?php echo $data->attendance_count ?></i></a>
            </h3>
            </div>
                <div class="w3-clear"></div>
                <h4><?php echo $data->Team ?></h4>
            </div>
        </div>
        <?php
        }
        ?>

</div>
<?php include 'theme/foot.php'; ?>
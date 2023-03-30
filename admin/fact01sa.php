<?php include '../db/db.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:250px;margin-top:43px;">

<!-- Header -->
    <header class="w3-container" style="padding-top:22px">
        <h3><b><i class="fa fa-building"></i>  Factory 01 Shift A</b></h3>
    </header>

<p class="text-center" style="margin-top: 3%"></p>

<div class="w3-row-padding w3-margin-bottom">
    

        <?php

            $all = $db->query("SELECT e.Team,t.Area,a.Attendance,t.Shift,COUNT(DISTINCT a.PN) attendance_count FROM emp e INNER JOIN team t ON e.Team=t.Team INNER JOIN attendance a ON e.PN=a.PN WHERE Area='A-Chanuka' AND Attendance='absent' AND Shift='A' AND DATE(a_date) = CURDATE()  AND DATE(Entered_date) = CURDATE() GROUP BY Team;");
            $fetch = $all->fetchAll(PDO::FETCH_OBJ);
            foreach($fetch as $data){ 

        ?>
        <div class=" w3-third" style="margin-bottom:20px;">
        <a href="details.php?team=<?php echo $data->Team ?>" style="text-decoration:none; color:white;">    
        <div class="w3-container w3-red w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><i class="fa fa-clone w3-xxxlarge"></i></div>
        <div class="w3-right">
            <h3>
            <?php echo $data->attendance_count ?>
            </h3>
            </div>
                <div class="w3-clear"></div>
                <h4><?php echo $data->Team ?></h4>
            </div>
        </a>    
        </div>
        <?php
        }
        ?>

</div>
<?php include 'theme/foot.php'; ?>
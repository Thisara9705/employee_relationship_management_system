<?php include '../db/db.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:250px;margin-top:43px;">

<!-- Header -->
    <header class="w3-container" style="padding-top:22px">
        <h3><b><i class="fa fa-calendar"></i> Service Duration</b></h3>
    </header>

<p class="text-center" style="margin-top: 3%"></p>

<div class="w3-row-padding w3-margin-bottom">
    

        <?php

            $all = $db->query("SELECT e.Join_date,a.Attendance,COUNT(DISTINCT a.PN) attendance_count FROM emp e INNER JOIN team t ON e.Team=t.Team INNER JOIN attendance a ON e.PN=a.PN WHERE Attendance='absent' AND Join_date > now() - INTERVAL 3 MONTH AND DATE(a_date) = CURDATE() AND DATE(Entered_date) = CURDATE();");
            $fetch = $all->fetchAll(PDO::FETCH_OBJ);
            foreach($fetch as $data){ 

        ?>
        <div class=" w3-third">
        <a href="details6.php" style="text-decoration:none; color:white;">
        <div class="w3-container w3-red w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><i class="fa fa-clone w3-xxxlarge"></i></div>
        <div class="w3-right">
            <h3>
           <?php echo $data->attendance_count ?>
            </h3>
            </div>
                <div class="w3-clear"></div>
                <h4>Below Three Months</h4>
            </div>
        </a>    
        </div>
        <?php
        }
        ?>
        <?php

            $all = $db->query("SELECT e.Join_date,a.Attendance,COUNT(DISTINCT a.PN) attendance_count FROM emp e INNER JOIN team t ON e.Team=t.Team INNER JOIN attendance a ON e.PN=a.PN WHERE Attendance='absent' AND  Join_date > now() - INTERVAL 6 MONTH AND  Join_date < now() - INTERVAL 3 MONTH AND DATE(a_date) = CURDATE();");
            $fetch = $all->fetchAll(PDO::FETCH_OBJ);
            foreach($fetch as $data){ 

            ?>
            <div class=" w3-third">
            <a href="details7.php" style="text-decoration:none; color:white;">
            <div class="w3-container w3-red w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><i class="fa fa-clone w3-xxxlarge"></i></div>
            <div class="w3-right">
            <h3>
            <?php echo $data->attendance_count ?>
            </h3>
            </div>
                <div class="w3-clear"></div>
                <h4>Between 3 and 6 Months</h4>
            </div>
            </a>
            </div>
        <?php
        }
        ?>
        <?php

            $all = $db->query("SELECT e.Join_date,a.Attendance,COUNT(DISTINCT a.PN) attendance_count FROM emp e INNER JOIN team t ON e.Team=t.Team INNER JOIN attendance a ON e.PN=a.PN WHERE Attendance='absent' AND  Join_date > now() - INTERVAL 12 MONTH AND  Join_date < now() - INTERVAL 6 MONTH AND DATE(a_date) = CURDATE();");
            $fetch = $all->fetchAll(PDO::FETCH_OBJ);
            foreach($fetch as $data){ 

            ?>
            <div class=" w3-third">
            <a href="details8.php" style="text-decoration:none; color:white;">    
            <div class="w3-container w3-red w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><i class="fa fa-clone w3-xxxlarge"></i></div>
            <div class="w3-right">
            <h3>
            <?php echo $data->attendance_count ?>
            </h3>
            </div>
                <div class="w3-clear"></div>
                <h4>Between 6 and 12 Months</h4>
            </div>
            </a>
            </div>
        <?php
        }
        ?>


</div> 

<div class="w3-row-padding w3-margin-bottom">
    

        <?php

            $all = $db->query("SELECT e.Join_date,a.Attendance,COUNT(DISTINCT a.PN) attendance_count FROM emp e INNER JOIN team t ON e.Team=t.Team INNER JOIN attendance a ON e.PN=a.PN WHERE Attendance='absent' AND  Join_date > now() - INTERVAL 24 MONTH AND  Join_date < now() - INTERVAL 12 MONTH AND DATE(a_date) = CURDATE();");
            $fetch = $all->fetchAll(PDO::FETCH_OBJ);
            foreach($fetch as $data){ 

        ?>
        <div class=" w3-third">
        <a href="details10.php" style="text-decoration:none; color:white;">
        <div class="w3-container w3-red w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><i class="fa fa-clone w3-xxxlarge"></i></div>
        <div class="w3-right">
            <h3>
            <?php echo $data->attendance_count ?>
            </h3>
            </div>
                <div class="w3-clear"></div>
                <h4>Between 12 and 24 Months</h4>
            </div>
        </a>    
        </div>
        <?php
        }
        ?>
        <?php

            $all = $db->query("SELECT e.Join_date,a.Attendance,COUNT(DISTINCT a.PN) attendance_count FROM emp e INNER JOIN team t ON e.Team=t.Team INNER JOIN attendance a ON e.PN=a.PN WHERE Attendance='absent' AND  Join_date > now() - INTERVAL 60 MONTH AND  Join_date < now() - INTERVAL 24 MONTH AND DATE(a_date) = CURDATE();");
            $fetch = $all->fetchAll(PDO::FETCH_OBJ);
            foreach($fetch as $data){ 

            ?>
            <div class=" w3-third">
            <a href="details11.php" style="text-decoration:none; color:white;">
            <div class="w3-container w3-red w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><i class="fa fa-clone w3-xxxlarge"></i></div>
            <div class="w3-right">
            <h3>
            <?php echo $data->attendance_count ?>
            </h3>
            </div>
                <div class="w3-clear"></div>
                <h4>Between 24 and 60 Months</h4>
            </div>
            </a>
            </div>
        <?php
        }
        ?>
        <?php

            $all = $db->query("SELECT e.Join_date,a.Attendance,COUNT(DISTINCT a.PN) attendance_count FROM emp e INNER JOIN team t ON e.Team=t.Team INNER JOIN attendance a ON e.PN=a.PN WHERE Attendance='absent' AND Join_date < now() - INTERVAL 60 MONTH AND DATE(a_date) = CURDATE();");
            $fetch = $all->fetchAll(PDO::FETCH_OBJ);
            foreach($fetch as $data){ 

            ?>
            <div class=" w3-third">
            <a href="details12.php" style="text-decoration:none; color:white;">    
            <div class="w3-container w3-red w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><i class="fa fa-clone w3-xxxlarge"></i></div>
            <div class="w3-right">
            <h3>
            <?php echo $data->attendance_count ?>
            </h3>
            </div>
                <div class="w3-clear"></div>
                <h4>Above 60 Months</h4>
            </div>
            </a>
            </div>
        <?php
        }
        ?>


</div> 
<?php include 'theme/foot.php'; ?>
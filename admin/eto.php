<?php include '../db/db.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:250px;margin-top:43px;">

<!-- Header -->
    <header class="w3-container" style="padding-top:22px">
        <h3><b><i class="fa fa-exclamation-triangle"></i> Possible ETO Count</b></h3>
    </header>

<p class="text-center" style="margin-top: 3%"></p>

<div class="w3-row-padding w3-margin-bottom">
    

        <?php

            $all = $db->query("SELECT a.Attendance,COUNT(DISTINCT a.PN) attendance_count FROM emp e INNER JOIN team t ON e.Team=t.Team INNER JOIN attendance a ON e.PN=a.PN WHERE Attendance='absent' AND a_date > CURDATE() - INTERVAL 2 DAY AND  a_date < now() - INTERVAL 1 DAY;");
            $fetch = $all->fetchAll(PDO::FETCH_OBJ);
            foreach($fetch as $data){ 

        ?>
        <div class=" w3-third">
        <a href="details13.php?day1=2&day2=1" style="text-decoration:none; color:white;">    
        <div class="w3-container w3-red w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><i class="fa fa-clone w3-xxxlarge"></i></div>
        <div class="w3-right">
            <h3>
            <?php echo $data->attendance_count ?>
            </h3>
            </div>
                <div class="w3-clear"></div>
                <h4>2 Days Absent List</h4>
            </div>
        </a>    
        </div>
        <?php
        }
        ?>
        <?php

            $all = $db->query("SELECT a.Attendance,COUNT(DISTINCT a.PN) attendance_count FROM emp e INNER JOIN team t ON e.Team=t.Team INNER JOIN attendance a ON e.PN=a.PN WHERE Attendance='absent' AND a_date > CURDATE() - INTERVAL 3 DAY AND  a_date < now() - INTERVAL 2 DAY;");
            $fetch = $all->fetchAll(PDO::FETCH_OBJ);
            foreach($fetch as $data){ 

            ?>
            <div class=" w3-third">
            <a href="details13.php?day1=3&day2=2" style="text-decoration:none; color:white;">    
            <div class="w3-container w3-red w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><i class="fa fa-clone w3-xxxlarge"></i></div>
            <div class="w3-right">
            <h3>
            <?php echo $data->attendance_count ?>
            </h3>
            </div>
                <div class="w3-clear"></div>
                <h4>3 Days Absent List</h4>
            </div>
            </a>
            </div>
        <?php
        }
        ?>
        <?php

            $all = $db->query("SELECT a.Attendance,COUNT(DISTINCT a.PN) attendance_count FROM emp e INNER JOIN team t ON e.Team=t.Team INNER JOIN attendance a ON e.PN=a.PN WHERE Attendance='absent' AND a_date > CURDATE() - INTERVAL 4 DAY AND  a_date < now() - INTERVAL 3 DAY;");
            $fetch = $all->fetchAll(PDO::FETCH_OBJ);
            foreach($fetch as $data){ 

            ?>
            <div class=" w3-third">
            <a href="details13.php?day1=4&day2=3" style="text-decoration:none; color:white;">    
            <div class="w3-container w3-red w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><i class="fa fa-clone w3-xxxlarge"></i></div>
            <div class="w3-right">
            <h3>
            <?php echo $data->attendance_count ?>
            </h3>
            </div>
                <div class="w3-clear"></div>
                <h4>4 Days Absents</h4>
            </div>
            </a>
            </div>
        <?php
        }
        ?>


</div> 

<div class="w3-row-padding w3-margin-bottom">
    

        <?php

            $all = $db->query("SELECT a.Attendance,COUNT(DISTINCT a.PN) attendance_count FROM emp e INNER JOIN team t ON e.Team=t.Team INNER JOIN attendance a ON e.PN=a.PN WHERE Attendance='absent' AND a_date > CURDATE() - INTERVAL 5 DAY AND  a_date < now() - INTERVAL 4 DAY;;");
            $fetch = $all->fetchAll(PDO::FETCH_OBJ);
            foreach($fetch as $data){ 

        ?>
        <div class=" w3-third">
        <a href="details13.php?day1=5&day2=4" style="text-decoration:none; color:white;">    
        <div class="w3-container w3-red w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><i class="fa fa-clone w3-xxxlarge"></i></div>
        <div class="w3-right">
            <h3>
            <?php echo $data->attendance_count ?>
            </h3>
            </div>
                <div class="w3-clear"></div>
                <h4>5 Days Absent List</h4>
            </div>
        </a>    
        </div>
        <?php
        }
        ?>
        <?php

            $all = $db->query("SELECT a.Attendance,COUNT(DISTINCT a.PN) attendance_count FROM emp e INNER JOIN team t ON e.Team=t.Team INNER JOIN attendance a ON e.PN=a.PN WHERE Attendance='absent' AND a_date > CURDATE() - INTERVAL 6 DAY AND  a_date < now() - INTERVAL 5 DAY;");
            $fetch = $all->fetchAll(PDO::FETCH_OBJ);
            foreach($fetch as $data){ 

            ?>
            <div class=" w3-third">
            <a href="details13.php?day1=6&day2=5" style="text-decoration:none; color:white;">    
            <div class="w3-container w3-red w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><i class="fa fa-clone w3-xxxlarge"></i></div>
            <div class="w3-right">
            <h3>
            <?php echo $data->attendance_count ?>
            </h3>
            </div>
                <div class="w3-clear"></div>
                <h4>6 Days Absent List</h4>
            </div>
            </a>
            </div>
        <?php
        }
        ?>
        <?php

            $all = $db->query("SELECT a.Attendance,COUNT(DISTINCT a.PN) attendance_count FROM emp e INNER JOIN team t ON e.Team=t.Team INNER JOIN attendance a ON e.PN=a.PN WHERE Attendance='absent' AND a_date > CURDATE() - INTERVAL 7 DAY AND  a_date < now() - INTERVAL 6 DAY;");
            $fetch = $all->fetchAll(PDO::FETCH_OBJ);
            foreach($fetch as $data){ 

            ?>
            <div class=" w3-third">
            <a href="details13.php?day1=7&day2=6" style="text-decoration:none; color:white;">    
            <div class="w3-container w3-red w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><i class="fa fa-clone w3-xxxlarge"></i></div>
            <div class="w3-right">
            <h3>
            <?php echo $data->attendance_count ?>
            </h3>
            </div>
                <div class="w3-clear"></div>
                <h4>7 Days Absents</h4>
            </div>
            </a>
            </div>
        <?php
        }
        ?>


</div>

<div class="w3-row-padding w3-margin-bottom">
    

        <?php

            $all = $db->query("SELECT a.Attendance,COUNT(DISTINCT a.PN) attendance_count FROM emp e INNER JOIN team t ON e.Team=t.Team INNER JOIN attendance a ON e.PN=a.PN WHERE Attendance='absent' AND a_date > CURDATE() - INTERVAL 8 DAY AND  a_date < now() - INTERVAL 7 DAY;;");
            $fetch = $all->fetchAll(PDO::FETCH_OBJ);
            foreach($fetch as $data){ 

        ?>
        <div class=" w3-third">
        <a href="details13.php?day1=8&day2=7" style="text-decoration:none; color:white;">    
        <div class="w3-container w3-red w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><i class="fa fa-clone w3-xxxlarge"></i></div>
        <div class="w3-right">
            <h3>
            <?php echo $data->attendance_count ?>
            </h3>
            </div>
                <div class="w3-clear"></div>
                <h4>8 Days Absent List</h4>
            </div>
        </a>    
        </div>
        <?php
        }
        ?>
        <?php

            $all = $db->query("SELECT a.Attendance,COUNT(DISTINCT a.PN) attendance_count FROM emp e INNER JOIN team t ON e.Team=t.Team INNER JOIN attendance a ON e.PN=a.PN WHERE Attendance='absent' AND a_date > CURDATE() - INTERVAL 9 DAY AND  a_date < now() - INTERVAL 8 DAY;");
            $fetch = $all->fetchAll(PDO::FETCH_OBJ);
            foreach($fetch as $data){ 

            ?>
            <div class=" w3-third">
            <a href="details13.php?day1=9&day2=8" style="text-decoration:none; color:white;">    
            <div class="w3-container w3-red w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><i class="fa fa-clone w3-xxxlarge"></i></div>
            <div class="w3-right">
            <h3>
            <?php echo $data->attendance_count ?>
            </h3>
            </div>
                <div class="w3-clear"></div>
                <h4>9 Days Absent List</h4>
            </div>
            </a>
            </div>
        <?php
        }
        ?>
        <?php

            $all = $db->query("SELECT a.Attendance,COUNT(DISTINCT a.PN) attendance_count FROM emp e INNER JOIN team t ON e.Team=t.Team INNER JOIN attendance a ON e.PN=a.PN WHERE Attendance='absent' AND a_date > CURDATE() - INTERVAL 10 DAY AND  a_date < now() - INTERVAL 9 DAY;");
            $fetch = $all->fetchAll(PDO::FETCH_OBJ);
            foreach($fetch as $data){ 

            ?>
            <div class=" w3-third">
            <a href="details13.php?day1=10&day2=9" style="text-decoration:none; color:white;">    
            <div class="w3-container w3-red w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><i class="fa fa-clone w3-xxxlarge"></i></div>
            <div class="w3-right">
            <h3>
            <?php echo $data->attendance_count ?>
            </h3>
            </div>
                <div class="w3-clear"></div>
                <h4>10 Days Absents</h4>
            </div>
            </a>
            </div>
        <?php
        }
        ?>


</div>

<div class="w3-row-padding w3-margin-bottom">
    

        <?php

            $all = $db->query("SELECT a.Attendance,COUNT(DISTINCT a.PN) attendance_count FROM emp e INNER JOIN team t ON e.Team=t.Team INNER JOIN attendance a ON e.PN=a.PN WHERE Attendance='absent' AND a_date > CURDATE() - INTERVAL 11 DAY AND  a_date < now() - INTERVAL 10 DAY;;");
            $fetch = $all->fetchAll(PDO::FETCH_OBJ);
            foreach($fetch as $data){ 

        ?>
        <div class=" w3-third">
        <a href="details13.php?day1=11&day2=10" style="text-decoration:none; color:white;">    
        <div class="w3-container w3-red w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><i class="fa fa-clone w3-xxxlarge"></i></div>
        <div class="w3-right">
            <h3>
            <?php echo $data->attendance_count ?>
            </h3>
            </div>
                <div class="w3-clear"></div>
                <h4>11 Days Absent List</h4>
            </div>
        </a>    
        </div>
        <?php
        }
        ?>
        <?php

            $all = $db->query("SELECT a.Attendance,COUNT(DISTINCT a.PN) attendance_count FROM emp e INNER JOIN team t ON e.Team=t.Team INNER JOIN attendance a ON e.PN=a.PN WHERE Attendance='absent' AND a_date > CURDATE() - INTERVAL 12 DAY AND  a_date < now() - INTERVAL 11 DAY;");
            $fetch = $all->fetchAll(PDO::FETCH_OBJ);
            foreach($fetch as $data){ 

            ?>
            <div class=" w3-third">
            <a href="details13.php?day1=12&day2=11" style="text-decoration:none; color:white;">    
            <div class="w3-container w3-red w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><i class="fa fa-clone w3-xxxlarge"></i></div>
            <div class="w3-right">
            <h3>
            <?php echo $data->attendance_count ?>
            </h3>
            </div>
                <div class="w3-clear"></div>
                <h4>12 Days Absent List</h4>
            </div>
            </a>
            </div>
        <?php
        }
        ?>
        <?php

            $all = $db->query("SELECT a.Attendance,COUNT(DISTINCT a.PN) attendance_count FROM emp e INNER JOIN team t ON e.Team=t.Team INNER JOIN attendance a ON e.PN=a.PN WHERE Attendance='absent' AND a_date > CURDATE() - INTERVAL 13 DAY AND  a_date < now() - INTERVAL 12 DAY;");
            $fetch = $all->fetchAll(PDO::FETCH_OBJ);
            foreach($fetch as $data){ 

            ?>
            <div class=" w3-third">
            <a href="details13.php?day1=13&day2=12" style="text-decoration:none; color:white;">    
            <div class="w3-container w3-red w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><i class="fa fa-clone w3-xxxlarge"></i></div>
            <div class="w3-right">
            <h3>
            <?php echo $data->attendance_count ?>
            </h3>
            </div>
                <div class="w3-clear"></div>
                <h4>13 Days Absents</h4>
            </div>
            </a>
            </div>
        <?php
        }
        ?>


</div>
<?php include 'theme/foot.php'; ?>
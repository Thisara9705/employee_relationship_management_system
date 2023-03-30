<?php include 'db/db.php'; ?>
<?php include 'theme/head.php'; ?>

<!-- !PAGE CONTENT! -->
<div class="w3-main">

    <div class="w3-bar w3-top w3-large" style="z-index:4; background-color:#294257; color:white;">
        <div class="w3-row">
            <div class="w3-col" style="width:85%">
                <span class="w3-bar-item w3-left" style="font-size:16px;">Active Talents Monitoring ER App</span>
            </div>
            <div class="w3-col" style="width:15%">
                <span class="w3-bar-item w3-right"><a href="dashboard.php" style="color:white;"><i class="fa fa-home"></i></a></span>
            </div>
        </div>  
    </div>

    <header class="w3-container" style="padding-top:45px">
        <h4><b>Departments</b></h4>
    </header>

    <p class="text-center" style="margin-top: 3%"></p>

    <div class="w3-row-padding w3-margin-bottom">

        <?php

            $ar = $_GET['area'];
            $all = $db->query("SELECT DISTINCT e.Team,t.Area,a.Attendance,t.Shift FROM emp e INNER JOIN team t ON e.Team=t.Team INNER JOIN attendance a ON a.PN = e.PN WHERE Area='$ar' AND Attendance='absent' AND Reason='Not Entered Yet' AND DATE(a_date) = CURDATE() AND DATE(Entered_date) = CURDATE();");
            $fetch = $all->fetchAll(PDO::FETCH_OBJ);
            foreach($fetch as $data){ 

        ?>
        <div class="w3-quarter" style="margin-bottom:10px;">
            <div class="w3-container w3-blue-grey w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><h4><?php echo $data->Team ?></h4></div>
                <div class="w3-right">
                <a href="members.php?team=<?php echo $data->Team ?>" style="text-decoration:none; color:white;"><i class="fa fa-angle-right w3-xxlarge"></i></a>
                </div>
            </div>
        </div>
        <?php 
        }
        ?>

    </div>
</div>    


<?php include 'theme/foot.php'; ?>
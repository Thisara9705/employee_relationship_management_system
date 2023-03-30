<?php include 'db/db.php'; ?>
<?php include 'theme/head.php'; ?>
<?php
        
        $all = $db->query("SELECT * FROM attendance WHERE DATE(a_date) = CURDATE()-1;");
        $data = $all->fetch(PDO::FETCH_ASSOC);

        if ($all->rowCount() > 0) {

            $team = $_GET['team'];
            $all = $db->query("SELECT DISTINCT e.Team,e.Name,a.Attendance,a.Reason,a.Sub_reason,a.PN,e.EPF,a.Date_From,a.Date_To FROM emp e INNER JOIN attendance a ON e.PN=a.PN  WHERE Team='$team' AND Attendance='absent' AND DATE(Date_To) > CURDATE() AND DATE(a_date) = CURDATE()-1 AND DATE(Entered_date) = CURDATE()-1;");
            $fetch = $all->fetchAll(PDO::FETCH_OBJ);
            foreach($fetch as $data){ 
    
    
            $attendance = $data->Attendance;
            $reason = $data->Reason;
            $sub = $data->Sub_reason;
            $pn = $data->PN;
            $datef = $data->Date_From;
            $datet = $data->Date_To;
            $team = $data->Team;
    
            $all = $db->query("UPDATE attendance SET Attendance='$attendance',Reason='$reason', Sub_reason='$sub', Date_From='$datef', Date_To='$datet' WHERE PN='$pn' AND DATE(a_date) = CURDATE()"); 
    
            }
        }else{
            
            $team = $_GET['team'];
            $all = $db->query("SELECT DISTINCT e.Team,e.Name,a.Attendance,a.Reason,a.Sub_reason,a.PN,e.EPF,a.Date_From,a.Date_To FROM emp e INNER JOIN attendance a ON e.PN=a.PN  WHERE Team='$team' AND Attendance='absent' AND DATE(Date_To) > CURDATE() AND DATE(a_date) = CURDATE()-2 AND DATE(Entered_date) = CURDATE()-2;");
            $fetch = $all->fetchAll(PDO::FETCH_OBJ);
            foreach($fetch as $data){ 
    
    
            $attendance = $data->Attendance;
            $reason = $data->Reason;
            $sub = $data->Sub_reason;
            $pn = $data->PN;
            $datef = $data->Date_From;
            $datet = $data->Date_To;
            $team = $data->Team;
    
            $all = $db->query("UPDATE attendance SET Attendance='$attendance',Reason='$reason', Sub_reason='$sub', Date_From='$datef', Date_To='$datet' WHERE PN='$pn' AND DATE(a_date) = CURDATE()"); 
    
            }
        }
?>

<!-- !PAGE CONTENT! -->
<div class="w3-main">

    <div class="w3-bar w3-top w3-large" style="z-index:4; background-color:#294257; color:white;">
        <div class="w3-row">
            <div class="w3-col" style="width:85%">
                <span class="w3-bar-item w3-left" style="font-size:16px;"><a href="module.php?area=<?php echo $_GET['area'] ?>&fact=<?php echo $_GET['fact'] ?>&shift=<?php echo $_GET['shift'] ?>&S=<?php echo $_GET['S'] ?>" style="color:white;"><i class="fa fa-angle-left" style="font-weight:1000;"></i></a>&nbsp; &nbsp;Active Talents Monitoring ER App</span>
            </div>
            <div class="w3-col" style="width:15%">
                <span class="w3-bar-item w3-right"><a href="dashboard.php" style="color:white;"><i class="fa fa-home"></i></a></span>
            </div>
        </div>  
    </div>

    <header class="w3-container" style="padding-top:45px">
        <div class="w3-left"><!--<a href="module.php"><button class="w3-button w3-black"> <i class="fa fa-angle-left w3-xxlarge"></i> </button></a>--><h3><b><?php echo $_GET['team'] ?> / Employees</b></h3></div>
        <!--<div class="w3-right">
            <a href="index.php"><button class="w3-button w3-black w3-hover-blue-gray"><i class="fa fa-home w3-xxlarge"></i></button></a>
        </div>-->
    </header>

    <p class="text-center" style="margin-top: 3%"></p>

    <div class="w3-row-padding w3-margin-bottom">

        <?php

            $team = $_GET['team'];
            $all = $db->query("SELECT DISTINCT e.Team,e.Name,a.Attendance,a.Reason,a.PN,e.EPF,a.Date_From,a.Date_To FROM emp e INNER JOIN attendance a ON e.PN=a.PN  WHERE Team='$team' AND Attendance='absent' AND Reason='Not Entered Yet' AND DATE(a_date) = CURDATE() AND DATE(Entered_date) = CURDATE();");
            $fetch = $all->fetchAll(PDO::FETCH_OBJ);
            foreach($fetch as $data){ 

        ?>
        <div class="w3-quarter" style="margin-bottom:10px; text-decoration:none; color:white;">
            <div class="w3-container w3-dark-gray w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><h4><?php echo $data->PN ?> -<?php echo $data->EPF ?> - <?php echo $data->Name ?></h4></div>
                <div class="w3-right">
                
                    <a href="add_reason.php?pn=<?php echo $data->PN ?>&epf=<?php echo $data->EPF ?>&team=<?php echo $data->Team ?>&name=<?php echo $data->Name ?>&datef=<?php echo $data->Date_From ?>&datet=<?php echo $data->Date_To ?>&area=<?php echo $_GET['area'] ?>&fact=<?php echo $_GET['fact'] ?>&shift=<?php echo $_GET['shift'] ?>&S=<?php echo $_GET['S'] ?>" style="text-decoration:none; color:white;"><i class="fa fa-angle-right w3-xxlarge"></i></a>
                    </div>
                </div>
            </div>
        
        <?php 
        }
        ?>
    </div>
    </div>
</div>    


<?php include 'theme/foot.php'; ?>
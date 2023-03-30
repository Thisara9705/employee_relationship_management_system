<?php include '../db/db.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php include 'session.php'; ?>


<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:250px;margin-top:43px;">

<p class="text-center w3-hide-large" style="padding-top:30px;"></p>
<!-- Header -->
    <header class="w3-container w3-hide-small" style="padding-top:22px;">
        <h3><b><i class="fa fa-dashboard"></i> Admin Dashboard</b></h3>
    </header>

<p class="text-center" style="margin-top: 3%"></p>

<?php

$message = '';

    if(isset($_POST["upload"])){

        $step=$_POST['step'];

        if ($step==2) {
            if($_FILES['product_file']['name']){

                $filename = explode(".", $_FILES['product_file']['name']);
            
                if(end($filename) == "csv"){
            
                $handle = fopen($_FILES['product_file']['tmp_name'], "r");
                while($data = fgetcsv($handle)){
            
                    $pdate = $data[0];
                    $date = date("Y-m-d", strtotime($pdate)); 
                    $pn = $data[1];
                    $att = $data[2];
                    $datef = date('Y-m-d');
                    $datet = date('Y-m-d');
                    
                    $all = $db->query("UPDATE attendance SET a_date ='$date', Attendance='$att',Date_From='$datef',Date_To='$datet' WHERE PN ='$pn' AND DATE(a_date) = CURDATE()");  
                }
                fclose($handle);
                header("location: dashboard.php?updation=1");
            
                }
                else{
                $message = '<label class="text-danger">Please Select CSV File only</label>';
                }
                }
                else{
                $message = '<label class="text-danger">Please Select File</label>';
                }
        } else {

            if($_FILES['product_file']['name']){

                $filename = explode(".", $_FILES['product_file']['name']);
            
                if(end($filename) == "csv"){
            
                $handle = fopen($_FILES['product_file']['tmp_name'], "r");
                while($data = fgetcsv($handle)){
            
                    $pdate = $data[0];
                    $date = date("Y-m-d", strtotime($pdate)); 
                    $pn = $data[1];
                    $att = $data[2];
                    $datef = date('Y-m-d');
                    $datet = date('Y-m-d');
                    
                    $all = $db->query("INSERT IGNORE INTO attendance (a_date,PN,Attendance,Date_From,Date_To) VALUES ('$date','$pn','$att','$datef','$datet')");  
                }
                fclose($handle);
                header("location: dashboard.php?updation=1");
            
                }
                else{
                $message = '<label class="text-danger">Please Select CSV File only</label>';
                }
                }
                else{
                $message = '<label class="text-danger">Please Select File</label>';
                }
        }
        

    
    }

    if(isset($_GET["updation"])){?>
        <div class="alert alert-success alert-dismissable" style="padding-top:50px;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Attendence Updation is Done <i class="fa fa-check"></i></strong>
        </div>
    <?php
    }
?>

<div class="w3-row-padding w3-margin-bottom">
    <form method="post" enctype='multipart/form-data' style="margin:10px;">
        <h5><label>Please Select File(Only CSV Formate)</label>
        <input type="file" name="product_file" /></h5>
        <div class="form-group">
            <select class="form-control" id="exampleFormControlSelect1" name="step" style="width:110px">
                <option>Select</option>
                <option value="1">1st time</option>
                <option value="2">2nd time</option>
            </select>
        </div>
        <input type="submit" name="upload" class="w3-button w3-teal w3-hover-blue-gray" value="Upload" />
    </form>
</div>

<div class="w3-row-padding w3-margin-bottom">

        <?php

        $all = $db->query("SELECT e.Team,t.Area,a.Attendance,a.a_date,t.Shift,COUNT(DISTINCT a.PN) a_count FROM emp e INNER JOIN team t ON e.Team=t.Team INNER JOIN attendance a ON e.PN=a.PN WHERE Attendance='absent' AND DATE(a_date) = CURDATE();");
        $fetch = $all->fetchAll(PDO::FETCH_OBJ);
        foreach($fetch as $data){ 

        ?>

        <div class=" w3-half">
        <a href="details20.php" style="text-decoration:none; color:white;">        
        <div class="w3-container w3-deep-purple w3-margin-16 w3-padding-16 w3-animate-zoom" >
                <div class="w3-left"><i class="fa fa-users w3-xxxlarge"></i></div>
        <div class="w3-right">
            <h3>
                <?php echo $data->a_count ?>
            </h3>
            </div>
                <div class="w3-clear"></div>
                <h4>Total Absent Heads</h4>
            </div>
        </a>    
        </div>
        <?php 
        }
        ?>


        <div class=" w3-quarter">
            <a href="absent_reports.php" style="text-decoration:none; color:white;">
            <div class="w3-container w3-2017-lapis-blue w3-margin-16 w3-padding-16 w3-animate-zoom" >
                <div class="w3-left"><i class="fa fa-users w3-xxxlarge"></i></div>
            <div class="w3-right">
            <h3>
            <i class="fa fa-angle-right w3-xlarge"></i>
            </h3>
            </div>
                <div class="w3-clear"></div>
                <h4>Old Reports</h4>
            </div>
            </a>
        </div>

        <div class=" w3-quarter">
            <a href="work_report.php" style="text-decoration:none; color:white;">
            <div class="w3-container w3-2019-eden w3-margin-16 w3-padding-16 w3-animate-zoom" >
                <div class="w3-left"><i class="fa fa-calendar-check-o w3-xxxlarge"></i></div>
            <div class="w3-right">
            <h3>
            <i class="fa fa-angle-right w3-xlarge"></i>
            </h3>
            </div>
                <div class="w3-clear"></div>
                <h4>Work Reporting Date</h4>
            </div>
            </a>
        </div>

</div>

<div class="w3-row-padding w3-margin-bottom">
    
        <?php

        $all = $db->query("SELECT e.Team,t.Area,a.Attendance,a.a_date,t.Shift,COUNT(DISTINCT a.PN) a_count FROM emp e INNER JOIN team t ON e.Team=t.Team INNER JOIN attendance a ON e.PN=a.PN WHERE Area='A-Chanuka' AND Attendance='absent' AND Shift='A' AND DATE(a_date) = CURDATE() AND DATE(Entered_date) = CURDATE();");
        $fetch = $all->fetchAll(PDO::FETCH_OBJ);
        foreach($fetch as $data){ 

        ?>

        <div class=" w3-third">
        <a href="details4.php?area=A-Chanuka&shift=A" style="text-decoration:none; color:white;">
        <div class="w3-container w3-red w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><i class="fa fa-building w3-xxxlarge"></i></div>
        <div class="w3-right">
            <h3>
            <?php echo $data->a_count ?>
            </h3>
            </div>
                <div class="w3-clear"></div>
                <h4>Factory 01 Shift A Total Absent Count</h4>
            </div>
        </a>        
        </div>
        <?php
        }
        ?>

        <?php

        $all = $db->query("SELECT e.Team,t.Area,a.Attendance,a.a_date,t.Shift,COUNT(DISTINCT a.PN) a_count FROM emp e INNER JOIN team t ON e.Team=t.Team INNER JOIN attendance a ON e.PN=a.PN WHERE Area='A-Thilina' AND Attendance='absent' AND Shift='A' AND DATE(a_date) = CURDATE() AND DATE(Entered_date) = CURDATE();");
        $fetch = $all->fetchAll(PDO::FETCH_OBJ);
        foreach($fetch as $data){ 

        ?>
    <div class="w3-third">
        <a href="details4.php?area=A-Thilina&shift=A" style="text-decoration:none; color:white;">
        <div class="w3-container w3-blue w3-padding-16 w3-animate-zoom">
            <div class="w3-left"><i class="fa fa-building w3-xxxlarge"></i></div>
            <div class="w3-right">
                <h3>
                <?php echo $data->a_count ?>
                </h3>
            </div>
            <div class="w3-clear"></div>
            <h4>Factory 02 Shift A Total Absent Count</h4>
        </div>
        </a>
    </div>
    <?php
        }
    ?>

    <?php

        $all = $db->query("SELECT e.Team,t.Area,a.Attendance,a.a_date,t.Shift,COUNT(DISTINCT a.PN) a_count FROM emp e INNER JOIN team t ON e.Team=t.Team INNER JOIN attendance a ON e.PN=a.PN WHERE Area='B-Chanuka' AND Attendance='absent' AND Shift='B' AND DATE(a_date) = CURDATE() AND DATE(Entered_date) = CURDATE();");
        $fetch = $all->fetchAll(PDO::FETCH_OBJ);
        foreach($fetch as $data){ 

    ?>
    <div class="w3-third">
        <a href="details4.php?area=B-Chanuka&shift=B" style="text-decoration:none; color:white;">
            <div class="w3-container w3-teal w3-padding-16 w3-animate-zoom">
                <div class="w3-left"><i class="fa fa-building w3-xxxlarge"></i></div>
                <div class="w3-right">
                    <h3>
                    <?php echo $data->a_count ?>
                    </h3>
                </div>
                <div class="w3-clear"></div>
                <h4><h4>Factory 01 Shift B Total Absent Count</h4></h4>
        </div>
        </a>
    </div>
    <?php
        }
    ?>
</div>   
<div class="w3-row-padding w3-margin-bottom">
        <?php

        $all = $db->query("SELECT e.Team,t.Area,a.Attendance,a.a_date,t.Shift,COUNT(DISTINCT a.PN) a_count FROM emp e INNER JOIN team t ON e.Team=t.Team INNER JOIN attendance a ON e.PN=a.PN WHERE Area='B-Thilina' AND Attendance='absent' AND Shift='B' AND DATE(a_date) = CURDATE() AND DATE(Entered_date) = CURDATE();");
        $fetch = $all->fetchAll(PDO::FETCH_OBJ);
        foreach($fetch as $data){ 

        ?>     
        <div class="w3-third">
            <a href="details4.php?area=B-Thilina&shift=B" style="text-decoration:none; color:white;">
                <div class="w3-container w3-orange w3-text-white w3-padding-16 w3-animate-zoom">
                    <div class="w3-left"><i class="fa fa-building w3-xxxlarge"></i></div>
                    <div class="w3-right">
                        <h3>
                        <?php echo $data->a_count ?>
                        </h3>
                    </div>
                    <div class="w3-clear"></div>
                    <h4><h4>Factory 02 Shift B Total Absent Count</h4> </h4>
                </div>
            </a>    
                </div>
        <?php
            }
        ?>                

        <?php

        $all = $db->query("SELECT e.Team,t.Area,a.Attendance,a.a_date,t.Shift,COUNT(DISTINCT a.PN) a_count FROM emp e INNER JOIN team t ON e.Team=t.Team INNER JOIN attendance a ON e.PN=a.PN WHERE Area='Departments' AND Attendance='absent' AND DATE(a_date) = CURDATE() AND DATE(Entered_date) = CURDATE();");
        $fetch = $all->fetchAll(PDO::FETCH_OBJ);
        foreach($fetch as $data){ 

        ?> 
        <div class="w3-third">
            <a href="details5.php?area=Departments" style="text-decoration:none; color:white;">
            <div class="w3-container w3-yellow  w3-text-white w3-padding-16 w3-animate-zoom " >
                    <div class="w3-left"><i class="fa fa-bookmark w3-xxxlarge"></i></div>
            <div class="w3-right">
                <h3>
                <?php echo $data->a_count ?>
                </h3>
                </div>
                    <div class="w3-clear"></div>
                    <h4>Departments Total Absent Count</h4>
                </div>
            </a>    
            </div>
        <?php
            }
        ?>

        <?php

        $all = $db->query("SELECT e.Team,t.Area,a.Attendance,a.a_date,t.Shift,COUNT(DISTINCT a.PN) a_count FROM emp e INNER JOIN team t ON e.Team=t.Team INNER JOIN attendance a ON e.PN=a.PN WHERE Area='O.Directs' AND Attendance='absent' AND DATE(a_date) = CURDATE() AND DATE(Entered_date) = CURDATE();");
        $fetch = $all->fetchAll(PDO::FETCH_OBJ);
        foreach($fetch as $data){ 

        ?>
        <div class="w3-third" >
            <a href="details5.php?area=O.Directs" style="text-decoration:none; color:white;">
            <div class="w3-container w3-pink w3-text-white w3-padding-16 w3-animate-zoom">
                    <div class="w3-left"><i class="fa fa-bookmark w3-xxxlarge"></i></div>
                        <div class="w3-right">
                        <h3>
                        <?php echo $data->a_count ?>
                        </h3>
                    </div>
                    <div class="w3-clear"></div>
                <h4>Other Directs Total Absent Count</h4>
            </div>
            </a>
        </div>
        <?php
            }
        ?>


</div>
<?php include 'theme/foot.php'; ?>


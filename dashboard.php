<?php include 'db/db.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'session.php'; ?>

<?php

$message = '';

    if(isset($_POST["upload"])){

        $step = $_POST['step'];

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
                header("location: index.php?updation=1");
            
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
                    $all = $db->query("INSERT IGNORE INTO attendance (a_date,PN,Attendance,Date_From,Date_To,Updated_time) VALUES ('$date','$pn','$att','$datef','$datet')");  
                }
                fclose($handle);
                header("location: index.php?updation=1");
            
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


<!-- !PAGE CONTENT! -->
<div class="w3-main">

<!-- Header -->
    <div class="w3-bar w3-top w3-large" style="z-index:4; background-color:#294257; color:white;">
        <div class="w3-row">
            <div class="w3-col" style="width:85%">
                <span class="w3-bar-item w3-left" style="font-size:16px;">Active Talents Monitoring ER App</span>
            </div>
            <div class="w3-col" style="width:15%">
                <span class="w3-bar-item w3-right"><a href="index.php" style="color:white;"><i class="fa fa-home"></i></a></span>
            </div>
        </div>  
    </div>

    <p class="text-center" style="margin-top: 3%"></p>

    <div class="w3-row-padding w3-margin-bottom" style="padding-top:45px;">
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

        <header class="w3-container" style="padding-top:5px">
            <h4><b>Shift A</b></h4>
        </header>

        <div class="w3-quarter" style="margin-bottom:10px;">
            <div class="w3-container w3-red w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><h4>Factory 01</h4></div>
                <div class="w3-right">
                <a href="module.php?area=A-Chanuka&fact=Factory 01&shift=Shift A&S=A" style="text-decoration:none; color:white;"><i class="fa fa-angle-right w3-xxlarge"></i></a>
                </div>
                <div class="w3-clear"></div>     
            </div>
        </div>

        <div class="w3-quarter" style="margin-bottom:10px;">
            <div class="w3-container w3-blue w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><h4>Factory 02</h4></div>
                <div class="w3-right">
                <a href="module.php?area=A-Thilina&fact=Factory 02&shift=Shift A&S=A" style="text-decoration:none; color:white;"><i class="fa fa-angle-right w3-xxlarge"></i></a>
                </div>
                <div class="w3-clear"></div>     
            </div>
        </div>


        <header class="w3-container" style="padding-top:5px">
            <h4><b>Shift B</b></h4>
        </header>

        <div class="w3-quarter" style="margin-bottom:10px;">
            <div class="w3-container w3-teal w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><h4>Factory 01</h4></div>
                <div class="w3-right">
                <a href="module.php?area=B-Chanuka&fact=Factory 01&shift=Shift B&S=B" style="text-decoration:none; color:white;"><i class="fa fa-angle-right w3-xxlarge"></i></a>
                </div>
                <div class="w3-clear"></div>     
            </div>
        </div>

        <div class="w3-quarter" style="margin-bottom:10px;">
            <div class="w3-container w3-brown w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><h4>Factory 02</h4></div>
                <div class="w3-right">
                <a href="module.php?area=B-Thilina&fact=Factory 02&shift=Shift B&S=B" style="text-decoration:none; color:white;"><i class="fa fa-angle-right w3-xxlarge"></i></a>
                </div>
                <div class="w3-clear"></div>     
            </div>
        </div>

    </div>

    <div class="w3-row-padding w3-margin-bottom">

        <header class="w3-container" style="padding-top:5px">
            <h4><b>Other Directs</b></h4>
        </header>

        <div class="w3-quarter" style="margin-bottom:10px;">
            <div class="w3-container w3-indigo w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><h4>Other Directs</h4></div>
                <div class="w3-right">
                <a href="o.directs.php?area=O.Directs" style="text-decoration:none; color:white;"><i class="fa fa-angle-right w3-xxlarge"></i></a>
                </div>
                <div class="w3-clear"></div>     
            </div>
        </div>
        
        <header class="w3-container" style="padding-top:5px">
            <h4><b>Departments</b></h4>
        </header>

        <div class="w3-quarter" style="margin-bottom:10px;">
            <div class="w3-container w3-2020-magenta-purple w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><h4>Departments</h4></div>
                <div class="w3-right">
                <a href="departments.php?area=Departments" style="text-decoration:none; color:white;"><i class="fa fa-angle-right w3-xxlarge"></i></a>
                </div>
                <div class="w3-clear"></div>     
            </div>
        </div>


    </div>
</div>    


<?php include 'theme/foot.php'; ?>
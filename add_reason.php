<?php include 'db/db.php'; ?>
<?php include 'theme/head.php'; ?>
<?php 
    if(isset($_POST["submit"])){

        $attendance = $_POST['attendance'];
        $reason = $_POST['reason'];
        $sub = $_POST['sub_reason'];
        $pn = $_POST['pn'];
        $datef = $_POST['datef'];
        $datet = $_POST['datet'];
        $team = $_GET['team'];
        $update = date('Y-m-d H:i:s');

        $all = $db->query("UPDATE attendance SET Attendance='$attendance',Reason='$reason', Sub_reason='$sub', Date_From='$datef', Date_To='$datet' , Updated_time='$update'  WHERE PN='$pn' AND DATE(a_date) = CURDATE()");  
        header("location:members.php?team=$team");
    }
?>

<!-- !PAGE CONTENT! -->
<div class="w3-main">

    <div class="w3-bar w3-top w3-large" style="z-index:4; background-color:#294257; color:white;">
        <div class="w3-row">
            <div class="w3-col" style="width:85%">
                <span class="w3-bar-item w3-left" style="font-size:16px;"><a href="members.php?team=<?php echo $_GET['team'] ?>&area=<?php echo $_GET['area'] ?>&fact=<?php echo $_GET['fact'] ?>&shift=<?php echo $_GET['shift'] ?>&S=<?php echo $_GET['S'] ?>" style="color:white;"><i class="fa fa-angle-left" style="font-weight:1000;"></i></a>&nbsp; &nbsp;Active Talents Monitoring ER App</span>
            </div>
            <div class="w3-col" style="width:15%">
                <span class="w3-bar-item w3-right"><a href="dashboard.php" style="color:white;"><i class="fa fa-home"></i></a></span>
            </div>
        </div>  
    </div>

<!-- Header -->
    <header class="w3-container" style="padding-top:22px">
        <div class="w3-left"><!--<a href="module.php"><button class="w3-button w3-black"> <i class="fa fa-angle-left w3-xxlarge"></i> </button></a>--><h3><b>Add Absent Reason</b></h3></div>
        <!--<div class="w3-right">
            <a href="index.php"><button class="w3-button w3-black w3-hover-blue-gray"><i class="fa fa-home w3-xxlarge"></i></button></a>
        </div>-->
    </header>

    <p class="text-center" style="margin-top: 3%"></p>

    <div class="w3-row-padding w3-margin-bottom w3-half">
    <?php 

        $pn = $_GET['pn'];
        $team = $_GET['team'];
        $all = $db->query("SELECT e.Team,e.Name,a.Attendance,a.Reason,a.PN,a.Date_From,a.Date_To FROM emp e INNER JOIN attendance a ON e.PN=a.PN  WHERE Team='$team' AND Attendance='absent' AND a.PN ='$pn' AND DATE(a_date) = CURDATE()-1 AND DATE(Entered_date) = CURDATE()-1;");
        $data = $all->fetch(PDO::FETCH_ASSOC);

        if($all->rowCount() > 0){
            $datef=$data['Date_From'];
            $datet=$data['Date_To'];
            $reason=$data['Reason'];
        }else{
            $datef=$_GET['datef'];
            $datet=$_GET['datet'];
        }
    ?>
        <form method="post">
            <input type="hidden" name="pn" value="<?php echo $_GET['pn'] ?>" >
            <div class="form-group">
                <label class="control-label">Name</label>
                <input type="text" name="name" class="form-control" readonly="on" value="<?php echo $_GET['name'] ?>" required>
            </div>
            <div class="form-group">
                <label class="control-label">EPF</label>
                <input type="text" name="epf" class="form-control" readonly="on" value="<?php echo $_GET['epf'] ?>" required>
            </div>
            <div class="form-group">
                <label for="exampleFormControlSelect1">Absent Reason</label>
                <select class="form-control" id="exampleFormControlSelect1"  name="attendance">
                    <option>Select The Reason</option>
                    <option value="present">Present</option>
                    <option value="absent">Absent</option>
                </select>
            </div>
            <div class="form-group">
                <label for="exampleFormControlSelect1">Absent Reason</label>
                <select class="form-control" id="exampleFormControlSelect1"  name="reason">
                    <option>Select The Reason</option>
                    <option value="Leaves">Leaves</option>
                    <option value="Absents">Absents</option>
                    <option value="Forecasted Absents">Forecasted Absents</option>
                    <option value="Symptoms">Symptoms</option>
                    <option value="Positive">Positive</option>
                    <option value="C1">C1</option>
                    <option value="Maternity Leave">Maternity Leave</option>
                    <option value="Pregnant Leave">Pregnant Leave</option>
                    <option value="Special Leave">Special Leave</option>
                </select>
            </div>

            <div class="form-group">
                <label for="exampleFormControlSelect1">Sub Reason</label>
                <select class="form-control" id="exampleFormControlSelect1" name="sub_reason">
                    <option value="No Sub Reason">Select The Reason</option>
                    <option value="Covid Contact">Covid Contact</option>
                    <option value="Covid Positive">Covid Positive</option>
                    <option value="Covid Symptoms">Covid Symptoms</option>
                    <option value="Covid Fear">Covid Fear</option>
                    <option value="Gastric">Gastric</option>
                    <option value="Sick">Sick</option>
                    <option value="Fever">Fever</option>
                    <option value="Dengue Fever">Dengue Fever</option>
                    <option value="Accident">Accident</option>
                    <option value="Operation">Operation</option>
                    <option value="Hospitalized">Hospitalized</option>
                    <option value="Clinic">Clinic</option>
                    <option value="Maternity Clinic">Maternity Clinic</option>
                    <option value="Pregnancy illness">Pregnancy illness</option>
                    <option value="Chicken Pox">Chicken Pox</option>
                    <option value="Mumps">Mumps</option>
                    <option value="Sore Eyes">Sore Eyes</option>
                    <option value="Child Sick">Child Sick</option>
                    <option value="Family member Sick">Family member Sick</option>
                    <option value="Child Hospitalized">Child Hospitalized</option>
                    <option value="Family member Hospitalized">Family member Hospitalized</option>
                    <option value="Child Case Issue">Child Case Issue</option>
                    <option value="Family Issue">Family Issue</option>
                    <option value="Peer-Peer Issue">Peer-Peer Issue</option>
                    <option value="Work Pressure Issue">Work Pressure Issue</option>
                    <option value="Target Related Issue">Target Related Issue</option>
                    <option value="Wedding">Wedding</option>
                    <option value="Funeral">Funeral</option>
                    <option value="Flood">Flood</option>
                    <option value="Gone Village">Gone Village</option>
                    <option value="Disciplinary">Disciplinary</option>
                    <option value="Exam/Class/Interview">Exam/Class/Interview</option>
                    <option value="Election">Election</option>
                    <option value="Personal Reason">Personal Reason</option>
                    <option value="Pregnant Leave">Pregnant Leave</option>
                    <option value="Maternity Leave">Maternity Leave</option>
                    <option value="After Maternity Leave">After Maternity Leave</option>
                    <option value="Special Leave">Special Leave</option>
                    <option value="Resign">Resign</option>
                    <option value="VOP">VOP</option>
                    <option value="Transfer">Transfer</option>
                    <option value="Duty Leave">Duty Leave</option>
                    <option value="ETO Possible">ETO Possible</option>
                </select>
            </div>

            <div class="form-group date" data-provide="datepicker">
            
                <label class="control-label">From :</label>
                <input type="text" name="datef" value="<?php echo $datef; ?>" class="form-control" required>
            </div>

            <div class="form-group date" data-provide="datepicker">
                <label class="control-label">To :</label>
                <input type="text" name="datet" value="<?php echo $datet; ?>" class="form-control" required>
            </div>
            <input type="submit" name="submit" value="update" class="w3-button w3-blue-gray">
        </form><!---->


    </div>
</div>    


<?php include 'theme/foot.php'; ?>
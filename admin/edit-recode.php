<?php include '../db/db.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>

<?php 
    if(!$_GET['id'] OR empty($_GET['id']) OR $_GET['id'] == '')
    {
        header('location: details21.php');

    }else{
        
        $id = (int)$_GET['id'];
        $all = $db->query("SELECT * FROM `attendance` WHERE aid = '$id'");
        $data = $all->fetch(PDO::FETCH_ASSOC);

        $pn = $data['PN'];
        $datef= $data['Date_From'];
        $datet= $data['Date_To'];

    }

?>
    <!-- !PAGE CONTENT! -->
    <div class="w3-main" style="margin-left:300px;margin-top:43px;">

    
    <div class="w3-container" style="padding-top:22px">
    <div class="w3-row">
    
        <div class="col-md-6">

        <?php
        if(isset($_POST['submit']))
        {
            $pn = $_POST['pn'];
            $name = $_POST['name'];
            $epf = $_POST['epf'];
            $attendance = $_POST['attendance'];
            $reason = $_POST['reason'];
            $sub = $_POST['sub_reason'];
            $datet = $_POST['datet'];
            $datef = $_POST['datef'];

            $id = $_GET['id'];

            $update_query = $db->query("UPDATE attendance SET Attendance = '$attendance',Reason = '$reason', Sub_reason = '$sub', Date_To = '$datet', Date_From = '$datef' WHERE aid = '$id' ");

            if($update_query){
                header("location: details21.php?updation1=1");  
                
            ?>
                
        <?php
            }else{ ?>
            <div class="alert alert-danger alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Error updating Absent data. Please try again <i class="fa fa-times"></i></strong>
            </div>
            <?php
        }

        }

        ?>

        <h2>Edit Absent Details</h2>
        <form method="post">
            <input type="hidden" name="pn" value="<?php echo $pn; ?>" >
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
                <select class="form-control" id="exampleFormControlSelect1" name="attendance">
                    <option>Select The Reason</option>
                    <option value="Present">Present</option>
                    <option value="Absent">Absent</option>
                </select>
            </div>
            <div class="form-group">
                <label for="exampleFormControlSelect1">Absent Reason</label>
                <select class="form-control" id="exampleFormControlSelect1" name="reason">
                    <option>Select The Reason</option>
                    <option value="Leaves">Leaves</option>
                    <option value="Absents">Absents</option>
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
                    <option>Select The Reason</option>
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
        </form>
    </div>
    </div>
    </div>
    </div>


    <?php include 'theme/foot.php'; ?>
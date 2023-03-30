<?php include '../db/db.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>

<div class="w3-main" style="margin-left:250px; margin-top:43px;">

    
    <div class="w3-container" style="padding-top:22px">
    <div class="w3-row">
    
        <div class="col-md-6">

        <?php
        if(isset($_POST['submit'])){
            
            $id = $_GET['id'];
            $PN = $_GET['pn'];
            $datet = $_POST['datet'];
            $date = $_GET['date'];

            $update_query = $db->query("UPDATE attendance SET Date_To='$datet' WHERE aid='$id'");

            if($update_query){
                header("location: details22.php?date=$date");  
                
            ?>
                
        <?php
            }else{ ?>
            <div class="alert alert-danger alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Error updating transport data. Please try again <i class="fa fa-times"></i></strong>
            </div>
            <?php
        }

        }

        ?>

        <h4>Edit Work Report Date</h4>
        <form method="post">
            <div class="form-group">
                <label class="control-label">Name</label>
                <input type="text" name="name" class="form-control" readonly="on" value="<?php echo $_GET['name'] ?>" required>
            </div>
            <div class="form-group">
                <label class="control-label">EPF</label>
                <input type="text" name="epf" class="form-control" readonly="on" value="<?php echo $_GET['epf'] ?>" required>
            </div>
            <div class="form-group date" data-provide="datepicker">
                <label class="control-label">Work Reporting Date</label>
                <input type="text" name="datet" value="<?php echo $_GET['date'] ?>" class="form-control" required>
            </div>

            <input type="submit" name="submit" value="update" class="w3-button w3-blue-gray">
        </form>
    </div>
    </div>
    </div>
    </div>


    <?php include 'theme/foot.php'; ?>
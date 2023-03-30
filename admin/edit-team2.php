<?php include '../db/db.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>

<?php 
    if(!$_GET['id'] OR empty($_GET['id']) OR $_GET['id'] == '')
    {
        header('location: add_acarder.php');

    }else{
        
        $id = (int)$_GET['id'];
        $data  = $db->query("SELECT * FROM Team WHERE id = '$id' LIMIT 1")->fetch();

        $team = data[1];
        $vsl = data[2];
        $er = data[3];
        $area = data[4];
        $shift = data[5];
        $ica = data[6];
        $ac = data[7];
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
            $team = $_POST['team'];
            $vsl = $_POST['vsl'];
            $er = $_POST['er'];
            $area = $_POST['area'];
            $shift = $_POST['shift'];
            $ica = $_POST['ica'];
            $ac = $_POST['ac'];

            $id = $_GET['id'];

            $update_query = $db->query("UPDATE Team SET Team = '$team', VSL = '$vsl', ER = '$er', Area = '$area', Shift = '$shift', ICA = '$ica', AC = '$ac' WHERE id = '$id' ");

            if($update_query){?>
            <div class="alert alert-success alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Team details successfully updated <i class="fa fa-check"></i></strong>
            </div>
        <?php
            }else{ ?>
            <div class="alert alert-danger alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Error updating team data. Please try again <i class="fa fa-times"></i></strong>
            </div>
            <?php
        }

        }

        ?>




            <h2>Edit Team</h2>
            <form method="post">
            <div class="form-group">
                    <label class="control-label">Team</label>
                    <input type="text" name="team" class="form-control" value="<?php echo $team; ?>" readonly="on" required>
                </div>


                <div class="form-group">
                    <label class="control-label">VSL</label>
                    <input type="text" name="vsl" class="form-control" value="<?php echo $vsl; ?>" required>
                </div>

                <div class="form-group">
                    <label class="control-label">ER</label>
                    <input type="text" name="er" class="form-control" value="<?php echo $er; ?>" required>
                </div>

                <div class="form-group">
                    <label class="control-label">Area</label>
                    <input type="text" name="er" class="form-control" value="<?php echo $area; ?>" required>
                </div>

                <div class="form-group">
                    <label class="control-label">Shift</label>
                    <input type="text" name="er" class="form-control" value="<?php echo $shift; ?>" required>
                </div>

                <div class="form-group">
                    <label class="control-label">Internal Carder Allocation</label>
                    <input type="text" name="ica" class="form-control" value="<?php echo $ica; ?>" required>
                </div>

                <div class="form-group">
                    <label class="control-label">Actual Carder</label>
                    <input type="text" name="ac" class="form-control" value="<?php echo $ac; ?>" required>
                </div>

                <button name="submit" type="submit" name="submit" class="btn btn-sn btn-default">Update</button>
                <a class="btn btn-danger btn-md" onclick="return confirm('Continue delete News ?')" href="delete-team2.php?id=<?php echo $id ?>"><i class="fa fa-trash"></i> Delete</a>
            </form>
    </div>
    </div>
    </div>
    </div>


    <?php include 'theme/foot.php'; ?>
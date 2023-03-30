<?php include '../db/db.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php ob_start();?>
<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:250px;margin-top:43px;">
<!-- Header -->
<header class="w3-container" style="padding-top:22px">
    <h3><b><i class="fa fa-calendar"></i> Employee Cadre Report</b></h3>
</header>


<div class="w3-row-padding w3-margin-bottom">
    <form method="post" enctype='multipart/form-data' style="margin:10px;">
        <div class="w3-row">
                <div class="form-group date w3-col m2 l2" data-provide="datepicker">
                    <label class="control-label">Date :</label>
                    <input type="text" name="datef"  class="form-control" required>
                </div>
        </div>
        <div class="w3-row">
              <input type="submit" name="submit" value="Submit" class="w3-button w3-blue-gray">
        </div>
    </form>
</div>

<div class="w3-row-padding w3-margin-bottom">
<div class="table-responsive">
<table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>ID</th>
                    <th>Date</th>
                    <th>PN</th>
                    <th>EPF</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Team</th>
                    <th>Classification</th>
                    <th>Join Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(isset($_POST["submit"])){
                    $datef=$_POST['datef'];
                    $count = 1;
                    $all = $db->query("SELECT * FROM emp WHERE DATE(Entered_date)='$datef' ");
                    $fetch = $all->fetchAll(PDO::FETCH_OBJ);
                    foreach($fetch as $data){ 
    
                ?>
              
            <tr>
                <td><?php echo $count  ?></td>
                <td><?php echo $data->ID  ?></td>
                <td><?php echo $data->Entered_date ?></td>
                <td><?php echo $data->PN ?></td>
                <td><?php echo $data->EPF ?></td>
                <td><?php echo $data->Name ?></td>
                <td><?php echo $data->Gender ?></td>
                <td><?php echo $data->Team ?></td>
                <td><?php echo $data->Classification ?></td>
                <td><?php echo $data->Join_Date ?></td>                
            </tr>    
            <?php
            $count = 1+$count; 
                }
             } 
            ?>
            </tbody>
    </table>
    </div>    
</div>
<?php include 'theme/foot.php'; ?>
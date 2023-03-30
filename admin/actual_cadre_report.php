<?php include '../db/db.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php ob_start();?>
<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:250px;margin-top:43px;">
<!-- Header -->
<header class="w3-container" style="padding-top:22px">
    <h3><b><i class="fa fa-calendar"></i> Actual Cadre Report</b></h3>
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
                    <th>Team</th>
                    <th>VSL</th>
                    <th>ER</th>
                    <th>Area</th>
                    <th>Shift</th>
                    <th>Internal Cadre Allocation</th>
                    <th>Actual Cadre</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(isset($_POST["submit"])){
                    $datef=$_POST['datef'];
                    $count = 1;
                    $all = $db->query("SELECT * FROM team WHERE DATE(c_date)='$datef' ");
                    $fetch = $all->fetchAll(PDO::FETCH_OBJ);
                    foreach($fetch as $data){ 
    
                ?>
              
            <tr>
                <td><?php echo $count  ?></td>
                <td><?php echo $data->id  ?></td>
                <td><?php echo $data->c_date ?></td>
                <td><?php echo $data->Team ?></td>
                <td><?php echo $data->VSL ?></td>
                <td><?php echo $data->ER ?></td>
                <td><?php echo $data->Area ?></td>
                <td><?php echo $data->Shift ?></td>
                <td><?php echo $data->ICA ?></td>
                <td><?php echo $data->AC ?></td>                
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
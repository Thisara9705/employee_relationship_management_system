<?php include '../db/db.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php ob_start();?>
<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:250px;margin-top:43px;">
<!-- Header -->
<header class="w3-container" style="padding-top:22px">
    <h3><b><i class="fa fa-calendar"></i> Attendance Report</b></h3>
</header>
<br>
<div class="w3-row-padding w3-margin-bottom"><button class="btn btn-primary btn-md" id='export'>Export excel</button></div>

<div class="w3-row-padding w3-margin-bottom">
    <form method="post" enctype='multipart/form-data' style="margin:10px;">
        <div class="w3-row">
          <div class="w3-col m6 l4">
              <div class="w3-row">
                <div class="form-group date w3-col m6 l6" data-provide="datepicker">
                    <label class="control-label">From :</label>
                    <input type="text" name="datef" value="<?php echo $datef; ?>" class="form-control" required>
                </div>
                <div class="form-group date w3-col m6 l6" data-provide="datepicker">
                    <label class="control-label">To :</label>
                    <input type="text" name="datet" value="<?php echo $datet; ?>" class="form-control" required>
                </div>
                <input type="submit" name="submit" value="Submit" class="w3-button w3-blue-gray">
              </div>
          </div>
        </div>
    </form>
</div>

<div class="w3-row-padding w3-margin-bottom">
<div class="table-responsive">
<table class="table table-hover table-striped" id="table3">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>ID</th>
                    <th>Date</th>
                    <th>PN</th>
                    <th>Attendance</th>
                    <th>Reason</th>
                    <th>Sub Reason</th>
                    <th>Date From</th>
                    <th>Date To</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(isset($_POST["submit"])){
                    $datef=$_POST['datef'];
                    $datet=$_POST['datet'];
                    $count = 1;
                    $all = $db->query("SELECT * FROM attendance WHERE DATE(a_date) BETWEEN '$datef' AND '$datet' ");
                    $fetch = $all->fetchAll(PDO::FETCH_OBJ);
                    foreach($fetch as $data){ 
    
                ?>
              
            <tr>
                <td><?php echo $count  ?></td>
                <td><?php echo $data->aid  ?></td>
                <td><?php echo $data->a_date ?></td>
                <td><?php echo $data->PN ?></td>
                <td><?php echo $data->Attendance ?></td>
                <td><?php echo $data->Reason ?></td>
                <td><?php echo $data->Sub_reason ?></td>
                <td><?php echo $data->Date_From ?></td>
                <td><?php echo $data->Date_To ?></td>
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
<script>
    var table2excel = new Table2Excel();
    document.getElementById('export').addEventListener('click', function(){
    table2excel.export(document.querySelectorAll('#table3'));
    });
</script>
<?php include 'theme/foot.php'; ?>
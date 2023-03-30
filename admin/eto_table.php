<?php include '../db/db.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>

<div class="w3-main" style="margin-left:250px;margin-top:30px;">

    <div class="w3-container" style="padding-top:22px">
    <div class="w3-row">
        <h2><i class="fa fa-bar-chart"></i> ETO List Table</h2><br>
        <br>
        <a class="btn btn-primary btn-lg"  href="add_eto.php"><i class="fa fa-plus"></i> Add ETO Recode</a>
        <br><br><br>
        <button class="btn btn-primary btn-md" id='export'>Export excel</button><br><br>
    <div class="table-responsive">
        <table class="table table-hover table-striped" id="table">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>PN</th>
                    <th>EPF</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Team</th>
                    <th>Classification</th>
                    <th>DOJ</th>
                    <th>Grading</th>
                    <th>VSL</th>
                    <th>VOP/Resign</th>
                    <th>Reason</th>
                    <th>Comment</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php

                $count = 1;
                $date1 = $_GET['day1'];
                $date2 = $_GET['day2'];
                $all = $db->query("SELECT * FROM eto");
                $fetch = $all->fetchAll(PDO::FETCH_OBJ);
                foreach($fetch as $data){ 

            ?>
            <tr>
                <td><?php echo $count ?></td>
                <td><?php echo $data->PN ?></td>
                <td><?php echo $data->EPF ?></td>
                <td><?php echo $data->Name ?></td>
                <td><?php echo $data->Team ?></td>
                <td><?php echo $data->Reason ?></td>
            </tr>    
            <?php
            $count++; 
            }
            ?>
            </tbody>
    </table>
    </div>
    </div>
    </div>
</div>
<script>
    var table2excel = new Table2Excel();
    document.getElementById('export').addEventListener('click', function(){
    table2excel.export(document.querySelectorAll('#table'));
    });
</script>

<?php include 'theme/foot.php'; ?>


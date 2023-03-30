<?php include '../db/db.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>

<div class="w3-main" style="margin-left:250px;margin-top:30px;">

    <div class="w3-container" style="padding-top:22px">
    <div class="w3-row">
        <h2>Details Of Absent Employees</h2><br>
        <button class="btn btn-primary btn-md" id='export'>Export excel</button><br><br>

    <div class="table-responsive">
        <table class="table table-hover table-striped" id="table2">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Date</th>
                    <th>PN</th>
                    <th>EPF</th>
                    <th>Name</th>
                    <th>Team</th>
                    <th>Absent Reason</th>
                    <th>Sub Reason</th>
                </tr>
            </thead>
            <tbody>
            <?php

                $count = 1;
                $a_date = $_GET['date'];
                $all = $db->query("SELECT DISTINCT e.Team,e.Name,a.Attendance,a.Reason,a.Sub_reason,a.Sub_reason,a.a_date,a.PN,e.EPF FROM emp e INNER JOIN team t ON e.Team=t.Team INNER JOIN attendance a ON e.PN=a.PN  WHERE Attendance='absent' AND a_date='$a_date' AND DATE(Entered_date) = '$a_date'");
                $fetch = $all->fetchAll(PDO::FETCH_OBJ);
                foreach($fetch as $data){ 

            ?>
            <tr>
                <td><?php echo $count ?></td>
                <td><?php echo $data->a_date ?></td>
                <td><?php echo $data->PN ?></td>
                <td><?php echo $data->EPF ?></td>
                <td><?php echo $data->Name ?></td>
                <td><?php echo $data->Team ?></td>
                <td><?php echo $data->Reason ?></td>
                <td><?php echo $data->Sub_reason ?></td> 
            </tr>    
            <?php
            $count = 1+$count; 
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
    table2excel.export(document.querySelectorAll('#table2'));
    });
</script

<?php include 'theme/foot.php'; ?>


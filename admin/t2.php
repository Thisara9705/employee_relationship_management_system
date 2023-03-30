<?php include '../db/db.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>

<div class="w3-main" style="margin-left:250px;margin-top:30px;">

    <div class="w3-container" style="padding-top:22px">
    <div class="w3-row">
        <h2>Details Of Absent Employees</h2><br>
        <button class="btn btn-primary btn-md" id='export'>Export excel</button><br><br>

    <div class="table-responsive">
        <table class="table table-hover table-striped" id="table">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Date</th>
                    <th>PN</th>
                    <th>EPF</th>
                    <th>Name</th>
                    <th>Team</th>
                    <th>Absent Reason</th>
                </tr>
            </thead>
            <tbody>
            <?php

                $count = 1;
                $all = $db->query("SELECT DISTINCT a.aid,e.Team,e.Name,a.Attendance,a.Reason,a.Sub_reason,a.a_date,a.PN,e.EPF FROM emp e INNER JOIN team t ON e.Team=t.Team INNER JOIN attendance a ON e.PN=a.PN  WHERE Team='Cutting - SY' AND Reason='Absents' AND DATE(a_date) = CURDATE() AND DATE(Entered_date) = CURDATE()");
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
    table2excel.export(document.querySelectorAll('#table'));
    });
</script>


<?php include 'theme/foot.php'; ?>


<?php include '../db/db.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>

<div class="w3-main" style="margin-left:250px;margin-top:30px;">

    <div class="w3-container" style="padding-top:22px">
    <div class="w3-row">
        <h2>ETO Possible List</h2><br>

    <div class="table-responsive">
        <table class="table table-hover table-striped" id="table">
            <thead>
                <tr>
                    <th>S/N</th>
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
                $date1 = $_GET['day1'];
                $date2 = $_GET['day2'];
                $all = $db->query("SELECT a.PN,e.EPF,e.Name,t.Team,a.Reason FROM emp e INNER JOIN team t ON e.Team=t.Team INNER JOIN attendance a ON e.PN=a.PN WHERE Attendance='absent' AND Sub_reason='ETO Possible' AND DATE(a_date) = CURDATE() AND DATE(c_date) = CURDATE() AND DATE(Entered_date) = CURDATE()");
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


<?php include 'theme/foot.php'; ?>


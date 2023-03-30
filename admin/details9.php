<?php include '../db/db.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>

<div class="w3-main" style="margin-left:250px;margin-top:30px;">

    <div class="w3-container" style="padding-top:22px">
    <div class="w3-row">
        <h2>Details Of Absent Employees</h2><br>

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
                    <th>Sub Reason</th>
                </tr>
            </thead>
            <tbody>
            <?php

                $all = $db->query("SELECT * FROM attendance WHERE DATE(a_date) = CURDATE()-1 LIMIT 2;");
                $data = $all->fetch(PDO::FETCH_ASSOC);

                if ($all->rowCount() > 0) {

                $count = 1;
                $all = $db->query("SELECT DISTINCT e.Team,e.Name,a.Attendance,a.Reason,a.Sub_reason,a.PN,e.EPF FROM emp e INNER JOIN attendance a ON e.PN=a.PN  WHERE  DATE(a_date) = CURDATE()-1 AND Team!='Input' AND DATE(Entered_date) = CURDATE()");
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
                <td><?php echo $data->Sub_reason ?></td>
            </tr>    
            <?php
            $count = 1+$count; 
                }
            }else{
            $count = 1;
                $all = $db->query("SELECT DISTINCT e.Team,e.Name,a.Attendance,a.Reason,a.Sub_reason,a.PN,e.EPF FROM emp e INNER JOIN attendance a ON e.PN=a.PN  WHERE  DATE(a_date) = CURDATE()-2 AND Team<>'Pending' AND DATE(Entered_date) = CURDATE()");
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
                <td><?php echo $data->Sub_reason ?></td>
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
    </div>
</div>


<?php include 'theme/foot.php'; ?>


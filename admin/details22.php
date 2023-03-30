<?php include '../db/db.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>

<div class="w3-main" style="margin-left:250px;margin-top:30px;">

    <div class="w3-container" style="padding-top:22px">
    <div class="w3-row">
        <h3><a href="work_report.php" style="color:black;"><i class="fa fa-angle-left" style="font-weight:1000;"></i></a>&nbsp; &nbsp;<?php echo $_GET['date'] ?> Work Reporting Members Details</h3><br>

    <div class="table-responsive">
        <table class="table table-hover table-striped" id="table2">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>PN</th>
                    <th>EPF</th>
                    <th>Name</th>
                    <th>Team</th>
                    <th>Absent Reason</th>
                    <th>Work Reporting Date</th>
                </tr>
            </thead>
            <tbody>
            <?php

                $count = 1;
                $date = $_GET['date'];
                $all = $db->query("SELECT DISTINCT e.Team,e.Name,a.Attendance,a.Reason,a.Sub_reason,a.Sub_reason,a.Date_To,a.a_date,a.PN,a.aid,e.EPF FROM emp e INNER JOIN team t ON e.Team=t.Team INNER JOIN attendance a ON e.PN=a.PN  WHERE Reason IN ('C1','Symptoms','Positive') AND Date_To='$date' AND DATE(a_date) = CURDATE() AND DATE(c_date) = CURDATE() AND DATE(Entered_date) = CURDATE()");
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
                <td><a href="edit-workreport.php?id=<?php echo $data->aid ?>&pn=<?php echo $data->PN ?>&name=<?php echo $data->Name ?>&epf=<?php echo $data->EPF ?>&date=<?php echo $data->Date_To ?>" style="color:black; text-decoration:none;"><?php echo $data->Date_To ?></td> 
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


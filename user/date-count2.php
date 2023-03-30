<?php include '../db/db.php'; ?>
<?php include 'theme/head.php'; ?>

<div class="w3-main" style="margin-top:30px;">

    <div class="w3-container" style="padding-top:22px">
    <div class="w3-row">
        <h2>Details Of Employees</h2><br>
        <button class="btn btn-primary btn-md" id='export'>Export excel</button><br><br>

    <div class="table-responsive">
        <table class="table table-hover table-striped" id="table">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>PN</th>
                    <th>EPF</th>
                    <th>Name</th>
                    <th>Team</th>
                </tr>
            </thead>
            <tbody>
            <?php

                $count = 1;
                $date = $_GET['date'];
                $vsl = $_GET['VSL'];
                $all = $db->query("SELECT e.Team,e.Name,a.PN,e.EPF,t.Area,a.Date_To,a.Reason FROM emp e INNER JOIN team t ON e.Team=t.Team INNER JOIN attendance a ON e.PN=a.PN  WHERE  Reason IN ('C1','Symptoms','Positive') AND Date_To='$date' AND Area='$vsl' AND DATE(a_date) = CURDATE() AND DATE(Entered_date) = CURDATE()");
                $fetch = $all->fetchAll(PDO::FETCH_OBJ);
                foreach($fetch as $data){ 

            ?>
            <tr>
                <td><?php echo $count ?></td>
                <td><?php echo $data->PN ?></td>
                <td><?php echo $data->EPF ?></td>
                <td><?php echo $data->Name ?></td>
                <td><?php echo $data->Team ?></td>
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


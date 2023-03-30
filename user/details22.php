<?php include '../db/db.php'; ?>
<?php include 'theme/head.php'; ?>
<?php 
    $data = $db->query("SELECT MAX(Updated_time) FROM attendance")->fetch();
    $update = $data[0];
?>
<div class="w3-bar w3-top w3-large w3-mobile" style="z-index:4; background-color:#294257; color:white;">
  <div class="w3-row">
    <div class="w3-col" style="width:45%">
      <h4><span class="w3-bar-item w3-hide-large w3-hide-medium w3-left">HR Digitization</span></h4>
      <h4><span class="w3-bar-item w3-hide-large w3-hide-small w3-left">Synergy HR Digitization</span></h4>
      <h3><span class="w3-bar-item w3-hide-small w3-hide-medium w3-left">Synergy HR Digitization</span></h3>
    </div>
    <div class="w3-col" style="width:55%">
        <div class="w3-row">
            <span class="w3-bar-item w3-right w3-hide-small w3-hide-medium">Last Update : <?php echo $update; ?></span>
        </div>
    </div>
  </div>
</div>  

<div class="w3-main" style="margin-top:30px;">

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
                <td><?php echo $data->Date_To ?></td> 
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
</script>

<?php include 'theme/foot.php'; ?>


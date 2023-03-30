<?php include '../db/db.php'; ?>
<?php include 'theme/head.php'; ?>
<?php error_reporting(0); ?>

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
        <div class="w3-row">
            <a class="btn btn-primary btn-md w3-right" href="index.php" style="margin:5px;" > Area Wise Cadre Report</a>
        </div>
    </div>
  </div>
</div>   
<div class="w3-main w3-hide-small w3-hide-medium" style="margin-top:55px;">

    <div class="w3-container" style="padding-top:22px">
    <div class="w3-row">

    <div class="table-responsive" style="overflow: auto; height: 690px;">
        <table class="table table-hover table-striped table w3-tiny display" id="table2"  style='font-weight: 900; margin-top:0px; width: 100%;'>
            <thead style="position: sticky; top: 0; z-index: 1;">
                <tr style="background:#f3f3f3;">
                    <th style="width:10%">Area</th>
                    <th>Internal Cadre Allocation</th>
                    <th>Actual Cadre</th>
                    <th>ML/PL/SL</th>
                    <th>ML Impacted to the Cadre</th>
                    <th>Actual Cadre After ML</th>
                    <th>Possible ETO</th>
                    <th>Shortage</th>
                    <th>Leaves</th>
                    <th>Absents</th>
                    <th>Normal Absent</th>
                    <th>Covid</th>
                    <th class='w3-yellow'>Symptoms</th>
                    <th class='w3-red'>Positive</th>
                    <th class='w3-light-green'>C1</th>
                    <th class='w3-text-red'>Total Absent Heads</th>
                    <th class='w3-text-green'>Total Present Heads</th>
                  
                    <th>Total Absent %</th>
                    <th>Total Absent % with ML</th>
                    <th>Total Absent % with Covid</th>
                
                   
                </tr>
            </thead>
            <tbody>

<!--======================================Kasun-B-Shift==============================================-->

<?php
                $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Style='Flees'");
                $fetch = $all->fetchAll(PDO::FETCH_OBJ);
                foreach($fetch as $data){
                    $id = $data->id;

                    $all = $db->query("SELECT t.Team,t.ICA,t.AC,t.Shift,pc.ml_count,pc.mli_count,
                    (t.AC-pc.ml_count) AS AAC,
                    pc.eto_count,
                    ((t.AC-pc.ml_count)-t.ICA-pc.eto_count) AS Shortage,
                    pc.leave_count,pc.absent_count,(pc.leave_count+pc.absent_count) AS normal_absent,
                    pc.symptom_count,pc.positive_count,
                    pc.c1_count,(pc.symptom_count+pc.positive_count+c1_count) AS covid,
                    (pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count)) AS total_absent,
                    (AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))) AS total_present,
                    (((pc.leave_count+pc.absent_count)/(t.AC-pc.ml_count))*100) AS ab,
                    ((((pc.leave_count+pc.absent_count)+pc.mli_count)/((t.AC-pc.ml_count)+pc.mli_count))*100) AS ab2
                    
                    FROM team t INNER JOIN 
                    (SELECT e.Team,e.Classification,t.Area,a.Attendance,a.Reason,t.Shift,
                    COUNT(case when Reason IN ('Maternity Leave','Special Leave','Pregnant Leave') then 1 else null end) ml_count,
                    COUNT(case Reason when 'Maternity Leave' then 1 else null end) mli_count, 
                    COUNT(case Sub_reason when 'ETO Possible' then 1 else null end) eto_count,
                    COUNT(case Reason when 'Leaves' then 1 else null end) leave_count,
                    COUNT(case Reason when 'Absents' then 1 else null end) absent_count,
                    COUNT(case Reason when 'Symptoms' then 1 else null end) symptom_count,
                    COUNT(case Reason when 'Positive' then 1 else null end) positive_count,
                    COUNT(case Reason when 'C1' then 1 else null end) c1_count
                    
                    FROM emp e INNER JOIN 
                    team t ON e.Team=t.Team INNER JOIN 
                    attendance a ON e.PN=a.PN 
                    WHERE  DATE(a_date) = CURDATE()  AND t.id='$id' AND DATE(Entered_date) = CURDATE()) 
                    pc ON pc.Team=t.Team WHERE DATE(c_date) = CURDATE()  ;");
                    $fetch = $all->fetchAll(PDO::FETCH_OBJ);
                    foreach($fetch as $data){

            ?> 
            <tr>

                <td><?php echo $data->Team ?></td>
                <td><?php echo $data->ICA ?></td>
                <td><?php echo $data->AC ?></td>
                <td><?php echo $data->ml_count ?></td>
                <td><?php echo $data->mli_count ?></td>
                <td><?php echo $data->AAC ?></td>
                <td><?php echo $data->eto_count ?></td>
                <td><a href="https://ts.hrsynergy.online//index.php?team=<?php echo $data->Team ?>" style="color:black; text-decoration:none;"><?php echo $data->Shortage ?></td>
                <td><?php echo $data->leave_count ?></td>
                <td><?php echo $data->absent_count ?></td>
                <td><?php echo $data->normal_absent ?></td>
                <td><?php echo $data->covid ?></td>
                <td class='w3-yellow'><?php echo $data->symptom_count ?></td>
                <td class='w3-red'><?php echo $data->positive_count ?></td>
                <td class='w3-light-green'><?php echo $data->c1_count ?></td>
                <td class='w3-text-red'><?php echo $data->total_absent ?></td>
                <td class='w3-text-green'><?php echo $data->total_present ?></td>
               
                <td><?php echo $data->ab ?>%</td>
                <td><?php echo $data->ab2 ?>%</td>
                <td><?php echo $data->ab3 ?>%</td><!---->

            </tr>    
            <?php
                }
            }
            ?>
            <?php
                $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Style='Flees'");
                $fetch = $all->fetchAll(PDO::FETCH_OBJ);
                $total_ica=0;
                $total_ac=0;
                $total_ml=0;
                $total_mli=0;
                $total_aac=0;
                $total_eto=0;
                $total_shortage=0;
                $total_leave=0;
                $total_absent=0;
                $total_normal_absent=0;
                $total_symptom=0;
                $total_positive=0;
                $total_c1=0;
                $total_covid=0;
                $total_absent_head=0;
                $total_present_head=0;
                $total_percentage=0;
               
                foreach($fetch as $data){
                    $id = $data->id;

                    $all = $db->query(
                    "SELECT 
                        SUM(t.ICA) t_ica,
                        SUM(t.AC) t_ac,
                        SUM(pc.ml_count) ml_count,
                        SUM(pc.mli_count) mli_count,
                        SUM(t.AC-pc.ml_count) total_aac,
                        SUM(pc.eto_count) eto_count,
                        SUM((t.AC-pc.ml_count)-t.ICA-pc.eto_count) total_shortage,
                        SUM(pc.leave_count) total_leave,
                        SUM(pc.absent_count) total_absent,
                        SUM(pc.leave_count+pc.absent_count) total_normal_absent,
                        SUM(pc.symptom_count) total_symptom,
                        SUM(pc.positive_count) total_positive,
                        SUM(pc.c1_count) total_c1,
                        SUM(pc.symptom_count+pc.positive_count+c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage
                      
                        /**/
                        FROM team t INNER JOIN 
                            (SELECT e.Team,e.Classification,t.Area,a.Attendance,a.Reason,t.Shift,
                                COUNT(case when Reason IN ('Maternity Leave','Special Leave','Pregnant Leave') then 1 else null end) ml_count, 
                                COUNT(case Reason when 'Maternity Leave' then 1 else null end) mli_count, 
                    COUNT(case Sub_reason when 'ETO Possible' then 1 else null end) eto_count,
                                COUNT(case Reason when 'Leaves' then 1 else null end) leave_count,
                                COUNT(case Reason when 'Absents' then 1 else null end) absent_count,
                                COUNT(case Reason when 'Symptoms' then 1 else null end) symptom_count,
                                COUNT(case Reason when 'Positive' then 1 else null end) positive_count,
                                COUNT(case Reason when 'C1' then 1 else null end) c1_count
                   
                            FROM emp e 
                            INNER JOIN team t ON e.Team=t.Team 
                            INNER JOIN attendance a ON e.PN=a.PN 
                            WHERE 
                            DATE(a_date) = CURDATE()  AND t.id='$id' AND DATE(Entered_date) = CURDATE()
                            ) 
                        pc ON pc.Team=t.Team WHERE DATE(c_date) = CURDATE()  ;"
                    );
                    $fetch = $all->fetchAll(PDO::FETCH_OBJ);
                    foreach($fetch as $data){
                        $total_ica+=$data->t_ica;
                        $total_ac+=$data->t_ac;
                        $total_ml+=$data->ml_count;
                        $total_mli+=$data->mli_count;
                         $total_aac+=$data->total_aac;
                        $total_eto+=$data->eto_count;
                        $total_shortage+=$data->total_shortage;
                        $total_leave+=$data->total_leave;
                        $total_absent+=$data->total_absent;
                        $total_normal_absent+=$data->total_normal_absent;
                        $total_covid+=$data->total_covid;
                        $total_symptom+=$data->total_symptom;
                        $total_positive+=$data->total_positive;
                        $total_c1+=$data->total_c1;
                        $total_absent_head+=$data->total_absent_head;
                        $total_present_head+=$data->total_present_head;
                       
                    }
                }    
                $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2); 
            ?> 
           
            </tbody>
    </table>
    </div>
    </div>
    </div>
</div>



<div class="w3-main w3-hide-large" style="margin-top:30px;">

    <div class="w3-container" style="padding-top:22px">
    <div class="w3-row">

    <div class="table-responsive" style="overflow: auto; height: 690px;">
    <table class="table table-hover table-striped table w3-tiny display" id="table2"  style='font-weight: 900; margin-top:0px; width: 100%;'>
            <thead style="position: sticky; top: 0; z-index: 1;">
                <tr style="background:#f3f3f3;">
                    <th style="width:10%">Area</th>
                    <th>Internal Cadre Allocation</th>
                    <th>Actual Cadre</th>
                    <th>ML/PL/SL</th>
                    <th>ML Impacted to the Cadre</th>
                    <th>Actual Cadre After ML</th>
                    <th>Possible ETO</th>
                    <th>Shortage</th>
                    <th>Leaves</th>
                    <th>Absents</th>
                    <th>Normal Absent</th>
                    <th>Covid</th>
                    <th class='w3-yellow'>Symptoms</th>
                    <th class='w3-red'>Positive</th>
                    <th class='w3-light-green'>C1</th>
                    <th class='w3-text-red'>Total Absent Heads</th>
                    <th class='w3-text-green'>Total Present Heads</th>
                    
                    <th>Total Absent %</th>
                    <th>Total Absent % with ML</th>
                    <th>Total Absent % with Covid</th>
                </tr>
            </thead>
            <tbody>



<!--======================================Kasun-B-Shift==============================================-->

<?php
                $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Style='Flees'");
                $fetch = $all->fetchAll(PDO::FETCH_OBJ);
                foreach($fetch as $data){
                    $id = $data->id;

                    $all = $db->query("SELECT t.Team,t.ICA,t.AC,t.Shift,pc.ml_count,pc.mli_count,
                    (t.AC-pc.ml_count) AS AAC,
                    pc.eto_count,
                    ((t.AC-pc.ml_count)-t.ICA-pc.eto_count) AS Shortage,
                    pc.leave_count,pc.absent_count,(pc.leave_count+pc.absent_count) AS normal_absent,
                    pc.symptom_count,pc.positive_count,
                    pc.c1_count,(pc.symptom_count+pc.positive_count+c1_count) AS covid,
                    (pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count)) AS total_absent,
                    (AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))) AS total_present,
                    (((pc.leave_count+pc.absent_count)/(t.AC-pc.ml_count))*100) AS ab,
                    ((((pc.leave_count+pc.absent_count)+pc.mli_count)/((t.AC-pc.ml_count)+pc.mli_count))*100) AS ab2
                    
                    FROM team t INNER JOIN 
                    (SELECT e.Team,e.Classification,t.Area,a.Attendance,a.Reason,t.Shift,
                    COUNT(case when Reason IN ('Maternity Leave','Special Leave','Pregnant Leave') then 1 else null end) ml_count,
                    COUNT(case Reason when 'Maternity Leave' then 1 else null end) mli_count, 
                    COUNT(case Sub_reason when 'ETO Possible' then 1 else null end) eto_count,
                    COUNT(case Reason when 'Leaves' then 1 else null end) leave_count,
                    COUNT(case Reason when 'Absents' then 1 else null end) absent_count,
                    COUNT(case Reason when 'Symptoms' then 1 else null end) symptom_count,
                    COUNT(case Reason when 'Positive' then 1 else null end) positive_count,
                    COUNT(case Reason when 'C1' then 1 else null end) c1_count
                   
                    FROM emp e INNER JOIN 
                    team t ON e.Team=t.Team INNER JOIN 
                    attendance a ON e.PN=a.PN 
                    WHERE  DATE(a_date) = CURDATE()  AND t.id='$id' AND DATE(Entered_date) = CURDATE()) 
                    pc ON pc.Team=t.Team WHERE DATE(c_date) = CURDATE()  ;");
                    $fetch = $all->fetchAll(PDO::FETCH_OBJ);
                    foreach($fetch as $data){

            ?> 
            <tr>

                <td><?php echo $data->Team ?></td>
                <td><?php echo $data->ICA ?></td>
                <td><?php echo $data->AC ?></td>
                <td><?php echo $data->ml_count ?></td>
                <td><?php echo $data->mli_count ?></td>
                <td><?php echo $data->AAC ?></td>
                <td><?php echo $data->eto_count ?></td>
                <td><a href="https://ts.hrsynergy.online//index.php?team=<?php echo $data->Team ?>" style="color:black; text-decoration:none;"><?php echo $data->Shortage ?></td>
                <td><?php echo $data->leave_count ?></td>
                <td><?php echo $data->absent_count ?></td>
                <td><?php echo $data->normal_absent ?></td>
                <td><?php echo $data->covid ?></td>
                <td class='w3-yellow'><?php echo $data->symptom_count ?></td>
                <td class='w3-red'><?php echo $data->positive_count ?></td>
                <td class='w3-light-green'><?php echo $data->c1_count ?></td>
                <td class='w3-text-red'><?php echo $data->total_absent ?></td>
                <td class='w3-text-green'><?php echo $data->total_present ?></td>
                
                <td><?php echo $data->ab ?>%</td>
                <td><?php echo $data->ab2 ?>%</td>
                <td><?php echo $data->ab3 ?>%</td><!---->

            </tr>    
            <?php
                }
            }
            ?>
            <?php
//                $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND VSL='Kasun'");

                $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Style='Flees'");
                $fetch = $all->fetchAll(PDO::FETCH_OBJ);
                $total_ica=0;
                $total_ac=0;
                $total_ml=0;
                $total_mli=0;
                $total_aac=0;
                $total_eto=0;
                $total_shortage=0;
                $total_leave=0;
                $total_absent=0;
                $total_normal_absent=0;
                $total_symptom=0;
                $total_positive=0;
                $total_c1=0;
                $total_covid=0;
                $total_absent_head=0;
                $total_present_head=0;
                $total_percentage=0;
                $total_datecount=0;
                $total_datecount1=0;
                $total_datecount2=0;
                $total_datecount3=0;
                $total_datecount4=0;
                foreach($fetch as $data){
                    $id = $data->id;

                    $all = $db->query(
                    "SELECT 
                        SUM(t.ICA) t_ica,
                        SUM(t.AC) t_ac,
                        SUM(pc.ml_count) ml_count,
                        SUM(pc.mli_count) mli_count,
                        SUM(t.AC-pc.ml_count) total_aac,
                        SUM(pc.eto_count) eto_count,
                        SUM((t.AC-pc.ml_count)-t.ICA-pc.eto_count) total_shortage,
                        SUM(pc.leave_count) total_leave,
                        SUM(pc.absent_count) total_absent,
                        SUM(pc.leave_count+pc.absent_count) total_normal_absent,
                        SUM(pc.symptom_count) total_symptom,
                        SUM(pc.positive_count) total_positive,
                        SUM(pc.c1_count) total_c1,
                        SUM(pc.symptom_count+pc.positive_count+c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage
                       
                        /**/
                        FROM team t INNER JOIN 
                            (SELECT e.Team,e.Classification,t.Area,a.Attendance,a.Reason,t.Shift,
                                COUNT(case when Reason IN ('Maternity Leave','Special Leave','Pregnant Leave') then 1 else null end) ml_count, 
                                COUNT(case Reason when 'Maternity Leave' then 1 else null end) mli_count, 
                    COUNT(case Sub_reason when 'ETO Possible' then 1 else null end) eto_count,
                                COUNT(case Reason when 'Leaves' then 1 else null end) leave_count,
                                COUNT(case Reason when 'Absents' then 1 else null end) absent_count,
                                COUNT(case Reason when 'Symptoms' then 1 else null end) symptom_count,
                                COUNT(case Reason when 'Positive' then 1 else null end) positive_count,
                                COUNT(case Reason when 'C1' then 1 else null end) c1_count
                     
                            FROM emp e 
                            INNER JOIN team t ON e.Team=t.Team 
                            INNER JOIN attendance a ON e.PN=a.PN 
                            WHERE 
                            DATE(a_date) = CURDATE()  AND t.id='$id' AND DATE(Entered_date) = CURDATE()
                            ) 
                        pc ON pc.Team=t.Team WHERE DATE(c_date) = CURDATE()  ;"
                    );
                    $fetch = $all->fetchAll(PDO::FETCH_OBJ);
                    foreach($fetch as $data){
                        $total_ica+=$data->t_ica;
                        $total_ac+=$data->t_ac;
                        $total_ml+=$data->ml_count;
                        $total_mli+=$data->mli_count;
                         $total_aac+=$data->total_aac;
                        $total_eto+=$data->eto_count;
                        $total_shortage+=$data->total_shortage;
                        $total_leave+=$data->total_leave;
                        $total_absent+=$data->total_absent;
                        $total_normal_absent+=$data->total_normal_absent;
                        $total_covid+=$data->total_covid;
                        $total_symptom+=$data->total_symptom;
                        $total_positive+=$data->total_positive;
                        $total_c1+=$data->total_c1;
                        $total_absent_head+=$data->total_absent_head;
                        $total_present_head+=$data->total_present_head;
                        
                    }
                }    
                $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2); 
            ?> 
           
            </tbody>
    </table>
    </div>
    </div>
    </div>
</div>



<?php include 'theme/foot.php'; ?>


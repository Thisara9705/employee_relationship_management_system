<?php include '../db/db.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php error_reporting(0); ?>

<div class="w3-main" style="margin-left:250px;margin-top:30px;">

    <div class="w3-container" style="padding-top:22px">
    <div class="w3-row">
        <h2><?php echo $_GET['date']; ?> Carder Report</h2><br>
        <button class="btn btn-primary btn-md" id='export'>Export excel</button><br><br>

    <div class="table-responsive">
        <table class="table table-hover table-striped" id="table3"  style='font-weight: 900; font-size:7px;'>
            <thead>
                <tr>
                    <th>Area</th>
                    <th>Internal Carder Allocation</th>
                    <th>Actual Carder</th>
                    <th>ML/PL/SL</th>
                    <th>ML Impacted to the Carder</th>
                    <th>Actual Carder After ML</th>
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
<!--======================================Prasanna-A-Shift==============================================-->

            <?php
                $a_date = $_GET['date'];
                $all = $db->query("SELECT id FROM team WHERE c_date='$a_date' AND VSL='Prasanna'");
                $fetch = $all->fetchAll(PDO::FETCH_OBJ);
                foreach($fetch as $data){
                    $id = $data->id;
                    $a_date = $_GET['date'];
                    

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
                    ((((pc.leave_count+pc.absent_count)+pc.mli_count)/((t.AC-pc.ml_count)+pc.mli_count))*100) AS ab2,
                    (((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) AS ab3 
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
                    WHERE  a_date='$a_date' AND t.id='$id' AND DATE(Entered_date) = '$a_date') 
                    pc ON pc.Team=t.Team WHERE c_date='$a_date' ;");
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
                <td><?php echo $data->Shortage ?></td>
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
                $a_date = $_GET['date'];
                $all = $db->query("SELECT id FROM team WHERE c_date='$a_date' AND VSL='Prasanna'");
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
                    $a_date = $_GET['date'];

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
                            a_date='$a_date' AND t.id='$id' AND DATE(Entered_date) = '$a_date'
                            ) 
                        pc ON pc.Team=t.Team WHERE c_date='$a_date' ;"
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
                        $total_percentage+=$data->total_percentage;
                        
                    }
                }
                $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);     
            ?> 
            <tr class='w3-light-blue'>

                <td><?php echo 'Naveen A Shift' ?></td>
                <td><?php echo $total_ica ?></td>
                <td><?php echo $total_ac ?></td>
                <td><?php echo $total_ml ?></td>
                <td><?php echo $total_mli ?></td>
                <td><?php echo $total_aac ?></td>
                <td><?php echo $total_eto ?></td>
                <td><?php echo $total_shortage ?></td>
                <td><?php echo $total_leave ?></td>
                <td><?php echo $total_absent ?></td>
                <td><?php echo $total_normal_absent ?></td>
                <td><?php echo $total_covid ?></td>
                <td class='w3-yellow'><?php echo $total_symptom ?></td>
                <td class='w3-red'><?php echo $total_positive ?></td>
                <td class='w3-light-green'><?php echo $total_c1 ?></td>
                <td class='w3-text-red'><?php echo $total_absent_head ?></td>
                <td class='w3-text-green'><?php echo $total_present_head ?></td>
                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>

            </tr>  
            
<!--======================================Viraj-A-Shift==============================================-->
<?php
                $a_date = $_GET['date'];
                $all = $db->query("SELECT id FROM team WHERE c_date='$a_date' AND VSL='Viraj'");
                $fetch = $all->fetchAll(PDO::FETCH_OBJ);
                foreach($fetch as $data){
                    $id = $data->id;
                    $a_date = $_GET['date'];

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
                    ((((pc.leave_count+pc.absent_count)+pc.mli_count)/((t.AC-pc.ml_count)+pc.mli_count))*100) AS ab2,
                    (((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) AS ab3 
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
                    WHERE  a_date='$a_date' AND t.id='$id' AND DATE(Entered_date) = '$a_date') 
                    pc ON pc.Team=t.Team WHERE c_date='$a_date' ;");
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
                <td><?php echo $data->Shortage ?></td>
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
                $a_date = $_GET['date'];
                $all = $db->query("SELECT id FROM team WHERE c_date='$a_date' AND VSL='Viraj'");
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
                
                $c=1;
                foreach($fetch as $data){
                    $id = $data->id;
                    $a_date = $_GET['date'];
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
                            a_date='$a_date' AND t.id='$id' AND DATE(Entered_date) = '$a_date'
                            ) 
                        pc ON pc.Team=t.Team WHERE c_date='$a_date' ;"
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
                        $total_percentage=($total_percentage+$data->total_percentage)/$c;
                        
                        $c++;
                 
                 $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);    }
                }    
            ?> 
            <tr class='w3-light-blue'>

                <td><?php echo 'Miyuranga A Shift' ?></td>
                <td><?php echo $total_ica ?></td>
                <td><?php echo $total_ac ?></td>
                <td><?php echo $total_ml ?></td>
                <td><?php echo $total_mli ?></td>
                <td><?php echo $total_aac ?></td>
                <td><?php echo $total_eto ?></td>
                <td><?php echo $total_shortage ?></td>
                <td><?php echo $total_leave ?></td>
                <td><?php echo $total_absent ?></td>
                <td><?php echo $total_normal_absent ?></td>
                <td><?php echo $total_covid ?></td>
                <td class='w3-yellow'><?php echo $total_symptom ?></td>
                <td class='w3-red'><?php echo $total_positive ?></td>
                <td class='w3-light-green'><?php echo $total_c1 ?></td>
                <td class='w3-text-red'><?php echo $total_absent_head ?></td>
                <td class='w3-text-green'><?php echo $total_present_head ?></td>
                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>

            </tr>  

<!--======================================Thilina-A-Shift==============================================-->
            
            <?php
                $a_date = $_GET['date'];
                $all = $db->query("SELECT id FROM team WHERE c_date='$a_date' AND Area='A-Thilina'");
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
                
                $c=1;
                foreach($fetch as $data){
                    $id = $data->id;
                    $a_date = $_GET['date'];
                    

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
                                COUNT(case Reason when 'Leaves' then 1 else null end) mli_count,
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
                            a_date='$a_date' AND t.id='$id' AND DATE(Entered_date) = '$a_date'
                            ) 
                        pc ON pc.Team=t.Team WHERE c_date='$a_date' ;"
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
                        $total_percentage=($total_percentage+$data->total_percentage)/$c;
                        
                        $c++; 
                 
                 $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);    }
                }
            ?> 
            <tr class='w3-blue'>

                <td><?php echo 'Prasanna A Shift' ?></td>
                <td><?php echo $total_ica ?></td>
                <td><?php echo $total_ac ?></td>
                <td><?php echo $total_ml ?></td>
                <td><?php echo $total_mli ?></td>
                <td><?php echo $total_aac ?></td>
                <td><?php echo $total_eto ?></td>
                <td><?php echo $total_shortage ?></td>
                <td><?php echo $total_leave ?></td>
                <td><?php echo $total_absent ?></td>
                <td><?php echo $total_normal_absent ?></td>
                <td><?php echo $total_covid ?></td>
                <td class='w3-yellow'><?php echo $total_symptom ?></td>
                <td class='w3-red'><?php echo $total_positive ?></td>
                <td class='w3-light-green'><?php echo $total_c1 ?></td>
                <td class='w3-text-red'><?php echo $total_absent_head ?></td>
                <td class='w3-text-green'><?php echo $total_present_head ?></td>
                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>

            </tr>
<!--======================================Sanjeewa-A-Shift==============================================-->
<?php
                $a_date = $_GET['date'];
                $all = $db->query("SELECT id FROM team WHERE c_date='$a_date' AND VSL='Sanjeewa'");
                $fetch = $all->fetchAll(PDO::FETCH_OBJ);
                foreach($fetch as $data){
                    $id = $data->id;
                    $a_date = $_GET['date'];

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
                    ((((pc.leave_count+pc.absent_count)+pc.mli_count)/((t.AC-pc.ml_count)+pc.mli_count))*100) AS ab2,
                    (((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) AS ab3 
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
                    WHERE  a_date='$a_date' AND t.id='$id' AND DATE(Entered_date) = '$a_date') 
                    pc ON pc.Team=t.Team WHERE c_date='$a_date' ;");
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
                <td><?php echo $data->Shortage ?></td>
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
                $a_date = $_GET['date'];
                $all = $db->query("SELECT id FROM team WHERE c_date='$a_date' AND VSL='Sanjeewa'");
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
                    $a_date = $_GET['date'];

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
                            a_date='$a_date' AND t.id='$id' AND DATE(Entered_date) = '$a_date'
                            ) 
                        pc ON pc.Team=t.Team WHERE c_date='$a_date' ;"
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
                        $total_percentage+=$data->total_percentage;
                        
                    }
                }
                $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);     
            ?> 
            <tr class='w3-light-blue'>

                <td><?php echo 'VSL A Shift' ?></td>
                <td><?php echo $total_ica ?></td>
                <td><?php echo $total_ac ?></td>
                <td><?php echo $total_ml ?></td>
                <td><?php echo $total_mli ?></td>
                <td><?php echo $total_aac ?></td>
                <td><?php echo $total_eto ?></td>
                <td><?php echo $total_shortage ?></td>
                <td><?php echo $total_leave ?></td>
                <td><?php echo $total_absent ?></td>
                <td><?php echo $total_normal_absent ?></td>
                <td><?php echo $total_covid ?></td>
                <td class='w3-yellow'><?php echo $total_symptom ?></td>
                <td class='w3-red'><?php echo $total_positive ?></td>
                <td class='w3-light-green'><?php echo $total_c1 ?></td>
                <td class='w3-text-red'><?php echo $total_absent_head ?></td>
                <td class='w3-text-green'><?php echo $total_present_head ?></td>
                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>

            </tr>    
            
<!--======================================Naveen-A-Shift==============================================-->
<?php
                $a_date = $_GET['date'];
                $all = $db->query("SELECT id FROM team WHERE c_date='$a_date' AND VSL='Naveen'");
                $fetch = $all->fetchAll(PDO::FETCH_OBJ);
                foreach($fetch as $data){
                    $id = $data->id;
                    $a_date = $_GET['date'];

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
                    ((((pc.leave_count+pc.absent_count)+pc.mli_count)/((t.AC-pc.ml_count)+pc.mli_count))*100) AS ab2,
                    (((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) AS ab3 
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
                    WHERE  a_date='$a_date' AND t.id='$id' AND DATE(Entered_date) = '$a_date') 
                    pc ON pc.Team=t.Team WHERE c_date='$a_date' ;");
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
                <td><?php echo $data->Shortage ?></td>
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
                $a_date = $_GET['date'];
                $all = $db->query("SELECT id FROM team WHERE c_date='$a_date' AND VSL='Naveen'");
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
                    $a_date = $_GET['date'];

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
                            a_date='$a_date' AND t.id='$id' AND DATE(Entered_date) = '$a_date'
                            ) 
                        pc ON pc.Team=t.Team WHERE c_date='$a_date' ;"
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
                        $total_percentage+=$data->total_percentage;
                        
                    }
                }
                $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);     
            ?> 
            <tr class='w3-light-blue'>

                <td><?php echo 'Saman A Shift' ?></td>
                <td><?php echo $total_ica ?></td>
                <td><?php echo $total_ac ?></td>
                <td><?php echo $total_ml ?></td>
                <td><?php echo $total_mli ?></td>
                <td><?php echo $total_aac ?></td>
                <td><?php echo $total_eto ?></td>
                <td><?php echo $total_shortage ?></td>
                <td><?php echo $total_leave ?></td>
                <td><?php echo $total_absent ?></td>
                <td><?php echo $total_normal_absent ?></td>
                <td><?php echo $total_covid ?></td>
                <td class='w3-yellow'><?php echo $total_symptom ?></td>
                <td class='w3-red'><?php echo $total_positive ?></td>
                <td class='w3-light-green'><?php echo $total_c1 ?></td>
                <td class='w3-text-red'><?php echo $total_absent_head ?></td>
                <td class='w3-text-green'><?php echo $total_present_head ?></td>
                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>

            </tr>  

<!--======================================Channuka-A-Shift==============================================-->
            
<?php
                $a_date = $_GET['date'];
                $all = $db->query("SELECT id FROM team WHERE c_date='$a_date' AND Area='A-Chanuka'");
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
                    $a_date = $_GET['date'];

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
                                COUNT(case Reason when 'Leaves' then 1 else null end) mli_count,
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
                            a_date='$a_date' AND t.id='$id' AND DATE(Entered_date) = '$a_date'
                            ) 
                        pc ON pc.Team=t.Team WHERE c_date='$a_date' ;"
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
                        $total_percentage+=$data->total_percentage;
                        
                    }
                }
                $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);     
            ?> 
            <tr class='w3-blue'>

                <td><?php echo 'Chanuka A Shift' ?></td>
                <td><?php echo $total_ica ?></td>
                <td><?php echo $total_ac ?></td>
                <td><?php echo $total_ml ?></td>
                <td><?php echo $total_mli ?></td>
                <td><?php echo $total_aac ?></td>
                <td><?php echo $total_eto ?></td>
                <td><?php echo $total_shortage ?></td>
                <td><?php echo $total_leave ?></td>
                <td><?php echo $total_absent ?></td>
                <td><?php echo $total_normal_absent ?></td>
                <td><?php echo $total_covid ?></td>
                <td class='w3-yellow'><?php echo $total_symptom ?></td>
                <td class='w3-red'><?php echo $total_positive ?></td>
                <td class='w3-light-green'><?php echo $total_c1 ?></td>
                <td class='w3-text-red'><?php echo $total_absent_head ?></td>
                <td class='w3-text-green'><?php echo $total_present_head ?></td>
                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>

            </tr>





<!--======================================Kasun-B-Shift==============================================-->

<?php
                $a_date = $_GET['date'];
                $all = $db->query("SELECT id FROM team WHERE c_date='$a_date' AND VSL='Kasun'");
                $fetch = $all->fetchAll(PDO::FETCH_OBJ);
                foreach($fetch as $data){
                    $id = $data->id;
                    $a_date = $_GET['date'];

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
                    ((((pc.leave_count+pc.absent_count)+pc.mli_count)/((t.AC-pc.ml_count)+pc.mli_count))*100) AS ab2,
                    (((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) AS ab3 
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
                    WHERE  a_date='$a_date' AND t.id='$id' AND DATE(Entered_date) = '$a_date') 
                    pc ON pc.Team=t.Team WHERE c_date='$a_date' ;");
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
                <td><?php echo $data->Shortage ?></td>
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
                $a_date = $_GET['date'];
                $all = $db->query("SELECT id FROM team WHERE c_date='$a_date' AND VSL='Kasun'");
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
                    $a_date = $_GET['date'];

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
                            a_date='$a_date' AND t.id='$id' AND DATE(Entered_date) = '$a_date'
                            ) 
                        pc ON pc.Team=t.Team WHERE c_date='$a_date' ;"
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
                        $total_percentage+=$data->total_percentage;
                        
                    }
                }
                $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);     
            ?> 
            <tr class='w3-light-blue'>

                <td><?php echo 'Kasun B Shift' ?></td>
                <td><?php echo $total_ica ?></td>
                <td><?php echo $total_ac ?></td>
                <td><?php echo $total_ml ?></td>
                <td><?php echo $total_mli ?></td>
                <td><?php echo $total_aac ?></td>
                <td><?php echo $total_eto ?></td>
                <td><?php echo $total_shortage ?></td>
                <td><?php echo $total_leave ?></td>
                <td><?php echo $total_absent ?></td>
                <td><?php echo $total_normal_absent ?></td>
                <td><?php echo $total_covid ?></td>
                <td class='w3-yellow'><?php echo $total_symptom ?></td>
                <td class='w3-red'><?php echo $total_positive ?></td>
                <td class='w3-light-green'><?php echo $total_c1 ?></td>
                <td class='w3-text-red'><?php echo $total_absent_head ?></td>
                <td class='w3-text-green'><?php echo $total_present_head ?></td>
                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>

            </tr>    
            
<!--======================================Miyuranga-B-Shift==============================================-->
<?php
                $a_date = $_GET['date'];
                $all = $db->query("SELECT id FROM team WHERE c_date='$a_date' AND VSL='Miyuranga'");
                $fetch = $all->fetchAll(PDO::FETCH_OBJ);
                foreach($fetch as $data){
                    $id = $data->id;
                    $a_date = $_GET['date'];

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
                    ((((pc.leave_count+pc.absent_count)+pc.mli_count)/((t.AC-pc.ml_count)+pc.mli_count))*100) AS ab2,
                    (((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) AS ab3 
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
                    WHERE  a_date='$a_date' AND t.id='$id' AND DATE(Entered_date) = '$a_date') 
                    pc ON pc.Team=t.Team WHERE c_date='$a_date' ;");
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
                <td><?php echo $data->Shortage ?></td>
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
                $a_date = $_GET['date'];
                $all = $db->query("SELECT id FROM team WHERE c_date='$a_date' AND VSL='Miyuranga'");
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
                    $a_date = $_GET['date'];

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
                            a_date='$a_date' AND t.id='$id' AND DATE(Entered_date) = '$a_date'
                            ) 
                        pc ON pc.Team=t.Team WHERE c_date='$a_date' ;"
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
                        $total_percentage+=$data->total_percentage;
                        
                    }
                }
                $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);     
            ?> 
            <tr class='w3-light-blue'>

                <td><?php echo 'Sanjeewa B Shift' ?></td>
                <td><?php echo $total_ica ?></td>
                <td><?php echo $total_ac ?></td>
                <td><?php echo $total_ml ?></td>
                <td><?php echo $total_mli ?></td>
                <td><?php echo $total_aac ?></td>
                <td><?php echo $total_eto ?></td>
                <td><?php echo $total_shortage ?></td>
                <td><?php echo $total_leave ?></td>
                <td><?php echo $total_absent ?></td>
                <td><?php echo $total_normal_absent ?></td>
                <td><?php echo $total_covid ?></td>
                <td class='w3-yellow'><?php echo $total_symptom ?></td>
                <td class='w3-red'><?php echo $total_positive ?></td>
                <td class='w3-light-green'><?php echo $total_c1 ?></td>
                <td class='w3-text-red'><?php echo $total_absent_head ?></td>
                <td class='w3-text-green'><?php echo $total_present_head ?></td>
                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>

            </tr>  

<!--======================================Thilina-B-Shift==============================================-->
            
            <?php
                $a_date = $_GET['date'];
                $all = $db->query("SELECT id FROM team WHERE c_date='$a_date' AND Area='B-Thilina'");
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
                    $a_date = $_GET['date'];

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
                                COUNT(case Reason when 'Leaves' then 1 else null end) mli_count,
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
                            a_date='$a_date' AND t.id='$id' AND DATE(Entered_date) = '$a_date'
                            ) 
                        pc ON pc.Team=t.Team WHERE c_date='$a_date' ;"
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
                        $total_percentage+=$data->total_percentage;
                        
                    }
                }
                $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);     
            ?> 
            <tr class='w3-blue'>

                <td><?php echo 'Prasanna B Shift' ?></td>
                <td><?php echo $total_ica ?></td>
                <td><?php echo $total_ac ?></td>
                <td><?php echo $total_ml ?></td>
                <td><?php echo $total_mli ?></td>
                <td><?php echo $total_aac ?></td>
                <td><?php echo $total_eto ?></td>
                <td><?php echo $total_shortage ?></td>
                <td><?php echo $total_leave ?></td>
                <td><?php echo $total_absent ?></td>
                <td><?php echo $total_normal_absent ?></td>
                <td><?php echo $total_covid ?></td>
                <td class='w3-yellow'><?php echo $total_symptom ?></td>
                <td class='w3-red'><?php echo $total_positive ?></td>
                <td class='w3-light-green'><?php echo $total_c1 ?></td>
                <td class='w3-text-red'><?php echo $total_absent_head ?></td>
                <td class='w3-text-green'><?php echo $total_present_head ?></td>
                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>

            </tr>

<!--======================================Saman-B-Shift==============================================-->

            <?php
                $a_date = $_GET['date'];
                $all = $db->query("SELECT id FROM team WHERE c_date='$a_date' AND VSL='Saman'");
                $fetch = $all->fetchAll(PDO::FETCH_OBJ);
                foreach($fetch as $data){
                    $id = $data->id;
                    $a_date = $_GET['date'];

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
                    ((((pc.leave_count+pc.absent_count)+pc.mli_count)/((t.AC-pc.ml_count)+pc.mli_count))*100) AS ab2,
                    (((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) AS ab3 
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
                    WHERE  a_date='$a_date' AND t.id='$id' AND DATE(Entered_date) = '$a_date') 
                    pc ON pc.Team=t.Team WHERE c_date='$a_date' ;");
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
                <td><?php echo $data->Shortage ?></td>
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
                $a_date = $_GET['date'];
                $all = $db->query("SELECT id FROM team WHERE c_date='$a_date' AND VSL='Saman'");
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
                    $a_date = $_GET['date'];

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
                            a_date='$a_date' AND t.id='$id' AND DATE(Entered_date) = '$a_date'
                            ) 
                        pc ON pc.Team=t.Team WHERE c_date='$a_date' ;"
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
                        $total_percentage+=$data->total_percentage;
                        
                    }
                }
                $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);     
            ?> 
            <tr class='w3-light-blue'>

                <td><?php echo 'VSL B Shift' ?></td>
                <td><?php echo $total_ica ?></td>
                <td><?php echo $total_ac ?></td>
                <td><?php echo $total_ml ?></td>
                <td><?php echo $total_mli ?></td>
                <td><?php echo $total_aac ?></td>
                <td><?php echo $total_eto ?></td>
                <td><?php echo $total_shortage ?></td>
                <td><?php echo $total_leave ?></td>
                <td><?php echo $total_absent ?></td>
                <td><?php echo $total_normal_absent ?></td>
                <td><?php echo $total_covid ?></td>
                <td class='w3-yellow'><?php echo $total_symptom ?></td>
                <td class='w3-red'><?php echo $total_positive ?></td>
                <td class='w3-light-green'><?php echo $total_c1 ?></td>
                <td class='w3-text-red'><?php echo $total_absent_head ?></td>
                <td class='w3-text-green'><?php echo $total_present_head ?></td>
                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>

            </tr>   
            
<!--======================================Pradeep-B-Shift==============================================-->
<?php
                $a_date = $_GET['date'];
                $all = $db->query("SELECT id FROM team WHERE c_date='$a_date' AND VSL='Pradeep'");
                $fetch = $all->fetchAll(PDO::FETCH_OBJ);
                foreach($fetch as $data){
                    $id = $data->id;
                    $a_date = $_GET['date'];

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
                    ((((pc.leave_count+pc.absent_count)+pc.mli_count)/((t.AC-pc.ml_count)+pc.mli_count))*100) AS ab2,
                    (((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) AS ab3 
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
                    WHERE  a_date='$a_date' AND t.id='$id' AND DATE(Entered_date) = '$a_date') 
                    pc ON pc.Team=t.Team WHERE c_date='$a_date' ;");
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
                <td><?php echo $data->Shortage ?></td>
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
                $a_date = $_GET['date'];
                $all = $db->query("SELECT id FROM team WHERE c_date='$a_date' AND VSL='Pradeep'");
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
                    $a_date = $_GET['date'];

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
                            a_date='$a_date' AND t.id='$id' AND DATE(Entered_date) = '$a_date'
                            ) 
                        pc ON pc.Team=t.Team WHERE c_date='$a_date' ;"
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
                        $total_percentage+=$data->total_percentage;
                        
                    }
                }
                $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);     
            ?> 
            <tr class='w3-light-blue'>

                <td><?php echo 'Pradeep B Shift' ?></td>
                <td><?php echo $total_ica ?></td>
                <td><?php echo $total_ac ?></td>
                <td><?php echo $total_ml ?></td>
                <td><?php echo $total_mli ?></td>
                <td><?php echo $total_aac ?></td>
                <td><?php echo $total_eto ?></td>
                <td><?php echo $total_shortage ?></td>
                <td><?php echo $total_leave ?></td>
                <td><?php echo $total_absent ?></td>
                <td><?php echo $total_normal_absent ?></td>
                <td><?php echo $total_covid ?></td>
                <td class='w3-yellow'><?php echo $total_symptom ?></td>
                <td class='w3-red'><?php echo $total_positive ?></td>
                <td class='w3-light-green'><?php echo $total_c1 ?></td>
                <td class='w3-text-red'><?php echo $total_absent_head ?></td>
                <td class='w3-text-green'><?php echo $total_present_head ?></td>
                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>

            </tr>  

<!--======================================Chanuka-B-Shift==============================================-->
            
            <?php
                $a_date = $_GET['date'];
                $all = $db->query("SELECT id FROM team WHERE c_date='$a_date' AND Area='B-Chanuka'");
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
                    $a_date = $_GET['date'];

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
                                COUNT(case Reason when 'Leaves' then 1 else null end) mli_count,
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
                            a_date='$a_date' AND t.id='$id' AND DATE(Entered_date) = '$a_date'
                            ) 
                        pc ON pc.Team=t.Team WHERE c_date='$a_date' ;"
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
                        $total_percentage+=$data->total_percentage;
                        
                    }
                }
                $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);     
            ?> 
            <tr class='w3-blue'>

                <td><?php echo 'Chanuka B Shift' ?></td>
                <td><?php echo $total_ica ?></td>
                <td><?php echo $total_ac ?></td>
                <td><?php echo $total_ml ?></td>
                <td><?php echo $total_mli ?></td>
                <td><?php echo $total_aac ?></td>
                <td><?php echo $total_eto ?></td>
                <td><?php echo $total_shortage ?></td>
                <td><?php echo $total_leave ?></td>
                <td><?php echo $total_absent ?></td>
                <td><?php echo $total_normal_absent ?></td>
                <td><?php echo $total_covid ?></td>
                <td class='w3-yellow'><?php echo $total_symptom ?></td>
                <td class='w3-red'><?php echo $total_positive ?></td>
                <td class='w3-light-green'><?php echo $total_c1 ?></td>
                <td class='w3-text-red'><?php echo $total_absent_head ?></td>
                <td class='w3-text-green'><?php echo $total_present_head ?></td>
                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>

            </tr>
<!--======================================Total-Shift-A==============================================-->
<?php
                $a_date = $_GET['date'];
                $all = $db->query("SELECT id FROM team WHERE c_date='$a_date' AND Area IN ('A-Thilina','A-Chanuka')");
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
                    $a_date = $_GET['date'];

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
                                COUNT(case Reason when 'Leaves' then 1 else null end) mli_count,
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
                            a_date='$a_date' AND t.id='$id' AND DATE(Entered_date) = '$a_date'
                            ) 
                        pc ON pc.Team=t.Team WHERE c_date='$a_date' ;"
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
                        $total_percentage+=$data->total_percentage;
                        
                    }
                }
                $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);     
            ?> 
            <tr class='w3-orange'>

                <td><?php echo 'Total A Shift' ?></td>
                <td><?php echo $total_ica ?></td>
                <td><?php echo $total_ac ?></td>
                <td><?php echo $total_ml ?></td>
                <td><?php echo $total_mli ?></td>
                <td><?php echo $total_aac ?></td>
                <td><?php echo $total_eto ?></td>
                <td><?php echo $total_shortage ?></td>
                <td><?php echo $total_leave ?></td>
                <td><?php echo $total_absent ?></td>
                <td><?php echo $total_normal_absent ?></td>
                <td><?php echo $total_covid ?></td>
                <td class='w3-yellow'><?php echo $total_symptom ?></td>
                <td class='w3-red'><?php echo $total_positive ?></td>
                <td class='w3-light-green'><?php echo $total_c1 ?></td>
                <td class='w3-text-red'><?php echo $total_absent_head ?></td>
                <td class='w3-text-green'><?php echo $total_present_head ?></td>
                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>

            </tr>

<!--======================================Total-Shift-B==============================================-->
<?php
                $a_date = $_GET['date'];
                $all = $db->query("SELECT id FROM team WHERE c_date='$a_date' AND Area IN ('B-Thilina','B-Chanuka')");
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
                    $a_date = $_GET['date'];

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
                                COUNT(case Reason when 'Leaves' then 1 else null end) mli_count,
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
                            a_date='$a_date' AND t.id='$id' AND DATE(Entered_date) = '$a_date'
                            ) 
                        pc ON pc.Team=t.Team WHERE c_date='$a_date' ;"
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
                        $total_percentage+=$data->total_percentage;
                        
                    }
                }
                $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);     
            ?> 
            <tr class='w3-orange'>

                <td><?php echo 'Total B Shift' ?></td>
                <td><?php echo $total_ica ?></td>
                <td><?php echo $total_ac ?></td>
                <td><?php echo $total_ml ?></td>
                <td><?php echo $total_mli ?></td>
                <td><?php echo $total_aac ?></td>
                <td><?php echo $total_eto ?></td>
                <td><?php echo $total_shortage ?></td>
                <td><?php echo $total_leave ?></td>
                <td><?php echo $total_absent ?></td>
                <td><?php echo $total_normal_absent ?></td>
                <td><?php echo $total_covid ?></td>
                <td class='w3-yellow'><?php echo $total_symptom ?></td>
                <td class='w3-red'><?php echo $total_positive ?></td>
                <td class='w3-light-green'><?php echo $total_c1 ?></td>
                <td class='w3-text-red'><?php echo $total_absent_head ?></td>
                <td class='w3-text-green'><?php echo $total_present_head ?></td>
                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>

            </tr>

<!--======================================Total-MO==============================================-->
            <?php
                $a_date = $_GET['date'];
                $all = $db->query("SELECT id FROM team WHERE c_date='$a_date' AND Area IN ('A-Thilina','A-Chanuka','B-Thilina','B-Chanuka')");
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
                    $a_date = $_GET['date'];

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
                                COUNT(case Reason when 'Leaves' then 1 else null end) mli_count,
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
                            a_date='$a_date' AND t.id='$id' AND DATE(Entered_date) = '$a_date'
                            ) 
                        pc ON pc.Team=t.Team WHERE c_date='$a_date' ;"
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
                        $total_percentage+=$data->total_percentage;
                        
                    }
                }
                $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);     
            ?> 
            <tr class='w3-orange'>

                <td><?php echo 'Total MO' ?></td>
                <td><?php echo $total_ica ?></td>
                <td><?php echo $total_ac ?></td>
                <td><?php echo $total_ml ?></td>
                <td><?php echo $total_mli ?></td>
                <td><?php echo $total_aac ?></td>
                <td><?php echo $total_eto ?></td>
                <td><?php echo $total_shortage ?></td>
                <td><?php echo $total_leave ?></td>
                <td><?php echo $total_absent ?></td>
                <td><?php echo $total_normal_absent ?></td>
                <td><?php echo $total_covid ?></td>
                <td class='w3-yellow'><?php echo $total_symptom ?></td>
                <td class='w3-red'><?php echo $total_positive ?></td>
                <td class='w3-light-green'><?php echo $total_c1 ?></td>
                <td class='w3-text-red'><?php echo $total_absent_head ?></td>
                <td class='w3-text-green'><?php echo $total_present_head ?></td>
                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>

            </tr>

<!--======================================Elastic Hub - SY==============================================-->
<?php
                $a_date = $_GET['date'];
                $all = $db->query("SELECT id FROM team WHERE c_date='$a_date' AND Team='Elastic Hub - SY'");
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
                    $a_date = $_GET['date'];

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
                                COUNT(case Reason when 'Leaves' then 1 else null end) mli_count, 
                                COUNT(case Reason when 'Leaves' then 1 else null end) leave_count,
                                COUNT(case Sub_reason when 'ETO Possible' then 1 else null end) eto_count,
                                COUNT(case Reason when 'Absents' then 1 else null end) absent_count,
                                COUNT(case Reason when 'Symptoms' then 1 else null end) symptom_count,
                                COUNT(case Reason when 'Positive' then 1 else null end) positive_count,
                                COUNT(case Reason when 'C1' then 1 else null end) c1_count
                      
                            FROM emp e 
                            INNER JOIN team t ON e.Team=t.Team 
                            INNER JOIN attendance a ON e.PN=a.PN 
                            WHERE 
                            a_date='$a_date' AND t.id='$id' AND DATE(Entered_date) = '$a_date'
                            ) 
                        pc ON pc.Team=t.Team WHERE c_date='$a_date' ;"
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
                        $total_percentage+=$data->total_percentage;
                        
                    }
                }
                $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);     
            ?> 
            <tr>

                <td><?php echo 'Elastic Hub' ?></td>
                <td><?php echo $total_ica ?></td>
                <td><?php echo $total_ac ?></td>
                <td><?php echo $total_ml ?></td>
                <td><?php echo $total_mli ?></td>
                <td><?php echo $total_aac ?></td>
                <td><?php echo $total_eto ?></td>
                <td><?php echo $total_shortage ?></td>
                <td><?php echo $total_leave ?></td>
                <td><?php echo $total_absent ?></td>
                <td><?php echo $total_normal_absent ?></td>
                <td><?php echo $total_covid ?></td>
                <td class='w3-yellow'><?php echo $total_symptom ?></td>
                <td class='w3-red'><?php echo $total_positive ?></td>
                <td class='w3-light-green'><?php echo $total_c1 ?></td>
                <td class='w3-text-red'><?php echo $total_absent_head ?></td>
                <td class='w3-text-green'><?php echo $total_present_head ?></td>
                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>

            </tr>

<!--======================================Supreme-Operators============================================-->
<?php
                $a_date = $_GET['date'];
                $all = $db->query("SELECT id FROM team WHERE c_date='$a_date' AND Team='Supreme Operators - SY'");
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
                    $a_date = $_GET['date'];

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
                                COUNT(case Reason when 'Leaves' then 1 else null end) mli_count,
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
                            a_date='$a_date' AND t.id='$id' AND DATE(Entered_date) = '$a_date'
                            ) 
                        pc ON pc.Team=t.Team WHERE c_date='$a_date' ;"
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
                        $total_percentage+=$data->total_percentage;
                        
                    }
                }
                $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);     
            ?> 
            <tr>

                <td><?php echo 'Supreme Operators' ?></td>
                <td><?php echo $total_ica ?></td>
                <td><?php echo $total_ac ?></td>
                <td><?php echo $total_ml ?></td>
                <td><?php echo $total_mli ?></td>
                <td><?php echo $total_aac ?></td>
                <td><?php echo $total_eto ?></td>
                <td><?php echo $total_shortage ?></td>
                <td><?php echo $total_leave ?></td>
                <td><?php echo $total_absent ?></td>
                <td><?php echo $total_normal_absent ?></td>
                <td><?php echo $total_covid ?></td>
                <td class='w3-yellow'><?php echo $total_symptom ?></td>
                <td class='w3-red'><?php echo $total_positive ?></td>
                <td class='w3-light-green'><?php echo $total_c1 ?></td>
                <td class='w3-text-red'><?php echo $total_absent_head ?></td>
                <td class='w3-text-green'><?php echo $total_present_head ?></td>
                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>

            </tr>
 
<!--=====================================Dancing-Production-Jumpers==========================================-->
<?php
                $a_date = $_GET['date'];
                $all = $db->query("SELECT id FROM team WHERE c_date='$a_date' AND Team='Standing Jumpers - SY'");
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
                    $a_date = $_GET['date'];

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
                                COUNT(case Reason when 'Leaves' then 1 else null end) mli_count,
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
                            a_date='$a_date' AND t.id='$id' AND DATE(Entered_date) = '$a_date'
                            ) 
                        pc ON pc.Team=t.Team WHERE c_date='$a_date' ;"
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
                        $total_percentage+=$data->total_percentage;
                        
                    }
                }
                $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);     
            ?> 
            <tr>

                <td><?php echo 'Dancing Production Jumpers' ?></td>
                <td><?php echo $total_ica ?></td>
                <td><?php echo $total_ac ?></td>
                <td><?php echo $total_ml ?></td>
                <td><?php echo $total_mli ?></td>
                <td><?php echo $total_aac ?></td>
                <td><?php echo $total_eto ?></td>
                <td><?php echo $total_shortage ?></td>
                <td><?php echo $total_leave ?></td>
                <td><?php echo $total_absent ?></td>
                <td><?php echo $total_normal_absent ?></td>
                <td><?php echo $total_covid ?></td>
                <td class='w3-yellow'><?php echo $total_symptom ?></td>
                <td class='w3-red'><?php echo $total_positive ?></td>
                <td class='w3-light-green'><?php echo $total_c1 ?></td>
                <td class='w3-text-red'><?php echo $total_absent_head ?></td>
                <td class='w3-text-green'><?php echo $total_present_head ?></td>
                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>

            </tr>            

            
<!--======================================Total-Production-Floor========================================-->
<?php
                $a_date = $_GET['date'];
                $all = $db->query("SELECT id FROM team WHERE c_date='$a_date' AND Area IN ('A-Thilina','A-Chanuka','B-Thilina','B-Chanuka') OR Team IN ('Elastic Hub - SY','Supreme Operators - SY','Standing Jumpers - SY' AND c_date='$a_date')");
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
                    $a_date = $_GET['date'];

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
                                COUNT(case Reason when 'Leaves' then 1 else null end) mli_count,
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
                            a_date='$a_date' AND t.id='$id' AND DATE(Entered_date) = '$a_date'
                            ) 
                        pc ON pc.Team=t.Team WHERE c_date='$a_date' ;"
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
                        $total_percentage+=$data->total_percentage;
                        
                    }
                }
                $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);     
            ?> 
            <tr class='w3-blue'>

                <td><?php echo 'Total Production Floor' ?></td>
                <td><?php echo $total_ica ?></td>
                <td><?php echo $total_ac ?></td>
                <td><?php echo $total_ml ?></td>
                <td><?php echo $total_mli ?></td>
                <td><?php echo $total_aac ?></td>
                <td><?php echo $total_eto ?></td>
                <td><?php echo $total_shortage ?></td>
                <td><?php echo $total_leave ?></td>
                <td><?php echo $total_absent ?></td>
                <td><?php echo $total_normal_absent ?></td>
                <td><?php echo $total_covid ?></td>
                <td class='w3-yellow'><?php echo $total_symptom ?></td>
                <td class='w3-red'><?php echo $total_positive ?></td>
                <td class='w3-light-green'><?php echo $total_c1 ?></td>
                <td class='w3-text-red'><?php echo $total_absent_head ?></td>
                <td class='w3-text-green'><?php echo $total_present_head ?></td>
                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>

            </tr>
<!--====================================Sitting-QCO-Jumpers==========================================-->

<?php
                $a_date = $_GET['date'];
                $all = $db->query("SELECT id FROM team WHERE c_date='$a_date' AND Team='Sitting Jumpers - SY'");
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
                    $a_date = $_GET['date'];

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
                                COUNT(case Reason when 'Leaves' then 1 else null end) mli_count,
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
                            a_date='$a_date' AND t.id='$id' AND DATE(Entered_date) = '$a_date'
                            ) 
                        pc ON pc.Team=t.Team WHERE c_date='$a_date' ;"
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
                        $total_percentage+=$data->total_percentage;
                        
                    }
                }
                $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);     
            ?> 
            <tr>

                <td><?php echo 'Sitting Jumpers' ?></td>
                <td><?php echo $total_ica ?></td>
                <td><?php echo $total_ac ?></td>
                <td><?php echo $total_ml ?></td>
                <td><?php echo $total_mli ?></td>
                <td><?php echo $total_aac ?></td>
                <td><?php echo $total_eto ?></td>
                <td><?php echo $total_shortage ?></td>
                <td><?php echo $total_leave ?></td>
                <td><?php echo $total_absent ?></td>
                <td><?php echo $total_normal_absent ?></td>
                <td><?php echo $total_covid ?></td>
                <td class='w3-yellow'><?php echo $total_symptom ?></td>
                <td class='w3-red'><?php echo $total_positive ?></td>
                <td class='w3-light-green'><?php echo $total_c1 ?></td>
                <td class='w3-text-red'><?php echo $total_absent_head ?></td>
                <td class='w3-text-green'><?php echo $total_present_head ?></td>
                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>

            </tr>

<!--=====================================AQL-Foldings==========================================-->
<?php
                $a_date = $_GET['date'];
                $all = $db->query("SELECT id FROM team WHERE c_date='$a_date' AND Team='Team - 52 - SY'");
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
                    $a_date = $_GET['date'];

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
                                COUNT(case Reason when 'Leaves' then 1 else null end) mli_count,
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
                            a_date='$a_date' AND t.id='$id' AND DATE(Entered_date) = '$a_date'
                            ) 
                        pc ON pc.Team=t.Team WHERE c_date='$a_date' ;"
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
                        $total_percentage+=$data->total_percentage;
                        
                    }
                }
                $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);     
            ?> 
            <tr>

                <td><?php echo 'AQL Foldings' ?></td>
                <td><?php echo $total_ica ?></td>
                <td><?php echo $total_ac ?></td>
                <td><?php echo $total_ml ?></td>
                <td><?php echo $total_mli ?></td>
                <td><?php echo $total_aac ?></td>
                <td><?php echo $total_eto ?></td>
                <td><?php echo $total_shortage ?></td>
                <td><?php echo $total_leave ?></td>
                <td><?php echo $total_absent ?></td>
                <td><?php echo $total_normal_absent ?></td>
                <td><?php echo $total_covid ?></td>
                <td class='w3-yellow'><?php echo $total_symptom ?></td>
                <td class='w3-red'><?php echo $total_positive ?></td>
                <td class='w3-light-green'><?php echo $total_c1 ?></td>
                <td class='w3-text-red'><?php echo $total_absent_head ?></td>
                <td class='w3-text-green'><?php echo $total_present_head ?></td>
                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>

            </tr>

<!--======================================Spot-Cleaning-Lab============================================-->
<?php
                $a_date = $_GET['date'];
                $all = $db->query("SELECT id FROM team WHERE c_date='$a_date' AND Team='Spot Cleaning - Lab - SY'");
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
                    $a_date = $_GET['date'];

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
                                COUNT(case Reason when 'Leaves' then 1 else null end) mli_count,
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
                            a_date='$a_date' AND t.id='$id' AND DATE(Entered_date) = '$a_date'
                            ) 
                        pc ON pc.Team=t.Team WHERE c_date='$a_date' ;"
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
                        $total_percentage+=$data->total_percentage;
                        
                    }
                }
                $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);     
            ?> 
            <tr>

                <td><?php echo 'Spot Cleaning Lab' ?></td>
                <td><?php echo $total_ica ?></td>
                <td><?php echo $total_ac ?></td>
                <td><?php echo $total_ml ?></td>
                <td><?php echo $total_mli ?></td>
                <td><?php echo $total_aac ?></td>
                <td><?php echo $total_eto ?></td>
                <td><?php echo $total_shortage ?></td>
                <td><?php echo $total_leave ?></td>
                <td><?php echo $total_absent ?></td>
                <td><?php echo $total_normal_absent ?></td>
                <td><?php echo $total_covid ?></td>
                <td class='w3-yellow'><?php echo $total_symptom ?></td>
                <td class='w3-red'><?php echo $total_positive ?></td>
                <td class='w3-light-green'><?php echo $total_c1 ?></td>
                <td class='w3-text-red'><?php echo $total_absent_head ?></td>
                <td class='w3-text-green'><?php echo $total_present_head ?></td>
                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>

            </tr>

<!--======================================Total-Other-Directs=========================================-->
<?php
                $a_date = $_GET['date'];
                $all = $db->query("SELECT id FROM team WHERE c_date='$a_date' AND Area='O.Directs'");
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
                    $a_date = $_GET['date'];
                    $a_date = $_GET['date'];

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
                                COUNT(case Reason when 'Leaves' then 1 else null end) mli_count,
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
                            a_date='$a_date' AND t.id='$id' AND DATE(Entered_date) = '$a_date'
                            ) 
                        pc ON pc.Team=t.Team WHERE c_date='$a_date' ;"
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
                        $total_percentage+=$data->total_percentage;
                        
                    }
                }
                $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);     
            ?> 
            <tr class='w3-blue'>

                <td><?php echo 'Other Directs' ?></td>
                <td><?php echo $total_ica ?></td>
                <td><?php echo $total_ac ?></td>
                <td><?php echo $total_ml ?></td>
                <td><?php echo $total_mli ?></td>
                <td><?php echo $total_aac ?></td>
                <td><?php echo $total_eto ?></td>
                <td><?php echo $total_shortage ?></td>
                <td><?php echo $total_leave ?></td>
                <td><?php echo $total_absent ?></td>
                <td><?php echo $total_normal_absent ?></td>
                <td><?php echo $total_covid ?></td>
                <td class='w3-yellow'><?php echo $total_symptom ?></td>
                <td class='w3-red'><?php echo $total_positive ?></td>
                <td class='w3-light-green'><?php echo $total_c1 ?></td>
                <td class='w3-text-red'><?php echo $total_absent_head ?></td>
                <td class='w3-text-green'><?php echo $total_present_head ?></td>
                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>

            </tr>
            
<!--======================================Total-Directs==============================================-->
<?php
                $a_date = $_GET['date'];
                $all = $db->query("SELECT id FROM team WHERE c_date='$a_date' AND Area IN ('A-Thilina','A-Chanuka','B-Thilina','B-Chanuka','O.Directs')");
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
                    $a_date = $_GET['date'];

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
                                COUNT(case Reason when 'Leaves' then 1 else null end) mli_count,
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
                            a_date='$a_date' AND t.id='$id' AND DATE(Entered_date) = '$a_date'
                            ) 
                        pc ON pc.Team=t.Team WHERE c_date='$a_date' ;"
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
                        $total_percentage+=$data->total_percentage;
                        
                    }
                }
                $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);     
            ?> 
            <tr class='w3-orange'>

                <td><?php echo 'Total Directs' ?></td>
                <td><?php echo $total_ica ?></td>
                <td><?php echo $total_ac ?></td>
                <td><?php echo $total_ml ?></td>
                <td><?php echo $total_mli ?></td>
                <td><?php echo $total_aac ?></td>
                <td><?php echo $total_eto ?></td>
                <td><?php echo $total_shortage ?></td>
                <td><?php echo $total_leave ?></td>
                <td><?php echo $total_absent ?></td>
                <td><?php echo $total_normal_absent ?></td>
                <td><?php echo $total_covid ?></td>
                <td class='w3-yellow'><?php echo $total_symptom ?></td>
                <td class='w3-red'><?php echo $total_positive ?></td>
                <td class='w3-light-green'><?php echo $total_c1 ?></td>
                <td class='w3-text-red'><?php echo $total_absent_head ?></td>
                <td class='w3-text-green'><?php echo $total_present_head ?></td>
                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>

            </tr>
            </tbody>
    </table>
    </div>
    </div>
    </div>
</div>
<script>
    var table2excel = new Table2Excel();
    document.getElementById('export').addEventListener('click', function(){
    table2excel.export(document.querySelectorAll('#table3'));
    });
</script>


<?php include 'theme/foot.php'; ?>


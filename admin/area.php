<?php include '../db/db.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php error_reporting(0); ?>
                
<div class="w3-main" style="margin-left:250px;margin-top:30px;">

    <div class="w3-container" style="padding-top:22px">
    <div class="w3-row">
        <h2>Carder Report</h2><br>
        <button class="btn btn-primary btn-md" id='export'>Export excel</button><br><br>

    <div class="table-responsive">
        <table class="table table-hover table-striped" id="table3" style='font-weight: 900; font-size:7px;'>
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
                    <th><?php echo date('Y.m.d'); ?></th>
                    <th><?php echo date('Y.m.d',strtotime("+1 days")); ?></th>
                    <th><?php echo date('Y.m.d',strtotime("+2 days")); ?></th>
                    <th><?php echo date('Y.m.d',strtotime("+3 days")); ?></th>
                    <th><?php echo date('Y.m.d',strtotime("+4 days")); ?></th>
                    <th>Total Absent %</th>
                    <th>Total Absent % with ML</th>
                    <th>Total Absent % with Covid</th>
                </tr>
            </thead>
            <tbody>

<!--============================================Prasanna-A-Shift======================================-->               
            <?php
                $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND VSL='Prasanna'");
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
                    $date = date('Y.m.d');
                    $date1 = date('Y.m.d',strtotime('+1 days'));
                    $date2 = date('Y.m.d',strtotime('+2 days'));
                    $date3 = date('Y.m.d',strtotime('+3 days'));
                    $date4 = date('Y.m.d',strtotime('+4 days'));

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
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                        SUM(pc.date_count) total_datecount,
                        SUM(pc.date_count1) total_datecount1,
                        SUM(pc.date_count2) total_datecount2,
                        SUM(pc.date_count3) total_datecount3,
                        SUM(pc.date_count4) total_datecount4,
                        t.VSL
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
                                COUNT(case Reason when 'C1' then 1 else null end) c1_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date' then 1 else null end) date_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date1' then 1 else null end) date_count1,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date2' then 1 else null end) date_count2,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date3' then 1 else null end) date_count3,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date4' then 1 else null end) date_count4  
                            FROM emp e 
                            INNER JOIN team t ON e.Team=t.Team 
                            INNER JOIN attendance a ON e.PN=a.PN 
                            WHERE 
                            DATE(a_date) = CURDATE() and t.id='$id' AND DATE(Entered_date) = CURDATE()
                            ) 
                        pc ON pc.Team=t.Team WHERE DATE(c_date) = CURDATE() ;"
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
                        $total_datecount+=$data->total_datecount;
                        $total_datecount1+=$data->total_datecount1;
                        $total_datecount2+=$data->total_datecount2;
                        $total_datecount3+=$data->total_datecount3;
                        $total_datecount4+=$data->total_datecount4;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount ?></a></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date1 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount1 ?></a></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date2 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount2 ?></a></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date3 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount3 ?></a></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date4 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount4 ?></a></td>
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td><!---->

            </tr>

                            
<!--============================================Viraj-A-Shift======================================-->               
<?php
                $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND VSL='Viraj'");
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
                    $date = date('Y.m.d');
                    $date1 = date('Y.m.d',strtotime('+1 days'));
                    $date2 = date('Y.m.d',strtotime('+2 days'));
                    $date3 = date('Y.m.d',strtotime('+3 days'));
                    $date4 = date('Y.m.d',strtotime('+4 days'));

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
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                        SUM(pc.date_count) total_datecount,
                        SUM(pc.date_count1) total_datecount1,
                        SUM(pc.date_count2) total_datecount2,
                        SUM(pc.date_count3) total_datecount3,
                        SUM(pc.date_count4) total_datecount4,
                        t.VSL
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
                                COUNT(case Reason when 'C1' then 1 else null end) c1_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date' then 1 else null end) date_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date1' then 1 else null end) date_count1,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date2' then 1 else null end) date_count2,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date3' then 1 else null end) date_count3,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date4' then 1 else null end) date_count4  
                            FROM emp e 
                            INNER JOIN team t ON e.Team=t.Team 
                            INNER JOIN attendance a ON e.PN=a.PN 
                            WHERE 
                            DATE(a_date) = CURDATE() and t.id='$id' AND DATE(Entered_date) = CURDATE()
                            ) 
                        pc ON pc.Team=t.Team WHERE DATE(c_date) = CURDATE() ;"
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
                        $total_datecount+=$data->total_datecount;
                        $total_datecount1+=$data->total_datecount1;
                        $total_datecount2+=$data->total_datecount2;
                        $total_datecount3+=$data->total_datecount3;
                        $total_datecount4+=$data->total_datecount4;

                    }
                }
                $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);   
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount ?></a></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date1 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount1 ?></a></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date2 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount2 ?></a></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date3 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount3 ?></a></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date4 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount4 ?></a></td>
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td><!---->

            </tr>

                            
<!--============================================Thilina-A-Shift======================================-->               
<?php
                $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Area='A-Thilina'");
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
                    $id = $data->id;
                    $date = date('Y.m.d');
                    $date1 = date('Y.m.d',strtotime('+1 days'));
                    $date2 = date('Y.m.d',strtotime('+2 days'));
                    $date3 = date('Y.m.d',strtotime('+3 days'));
                    $date4 = date('Y.m.d',strtotime('+4 days'));
                    

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
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                        SUM(pc.date_count) total_datecount,
                        SUM(pc.date_count1) total_datecount1,
                        SUM(pc.date_count2) total_datecount2,
                        SUM(pc.date_count3) total_datecount3,
                        SUM(pc.date_count4) total_datecount4,
                        t.Area
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
                                COUNT(case Reason when 'C1' then 1 else null end) c1_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date' then 1 else null end) date_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date1' then 1 else null end) date_count1,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date2' then 1 else null end) date_count2,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date3' then 1 else null end) date_count3,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date4' then 1 else null end) date_count4  
                            FROM emp e 
                            INNER JOIN team t ON e.Team=t.Team 
                            INNER JOIN attendance a ON e.PN=a.PN 
                            WHERE 
                            DATE(a_date) = CURDATE() and t.id='$id' AND DATE(Entered_date) = CURDATE()
                            ) 
                        pc ON pc.Team=t.Team WHERE DATE(c_date) = CURDATE() ;"
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
                        $total_datecount+=$data->total_datecount;
                        $total_datecount1+=$data->total_datecount1;
                        $total_datecount2+=$data->total_datecount2;
                        $total_datecount3+=$data->total_datecount3;
                        $total_datecount4+=$data->total_datecount4;
                    }
                }    
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                <td><a href="date-count2.php?VSL=A-Thilina&date=<?php echo $date ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount ?></a></td>
                <td><a href="date-count2.php?VSL=A-Thilina&date=<?php echo $date1 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount1 ?></a></td>
                <td><a href="date-count2.php?VSL=A-Thilina&date=<?php echo $date2 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount2 ?></a></td>
                <td><a href="date-count2.php?VSL=A-Thilina&date=<?php echo $date3 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount3 ?></a></td>
                <td><a href="date-count2.php?VSL=A-Thilina&date=<?php echo $date4 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount4 ?></a></td>
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>

            </tr>

<!--===========================================Sanjeewa-A-Shift======================================-->               
            <?php
                $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND VSL='Sanjeewa'");
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
                    $date = date('Y.m.d');
                    $date1 = date('Y.m.d',strtotime('+1 days'));
                    $date2 = date('Y.m.d',strtotime('+2 days'));
                    $date3 = date('Y.m.d',strtotime('+3 days'));
                    $date4 = date('Y.m.d',strtotime('+4 days'));

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
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                        SUM(pc.date_count) total_datecount,
                        SUM(pc.date_count1) total_datecount1,
                        SUM(pc.date_count2) total_datecount2,
                        SUM(pc.date_count3) total_datecount3,
                        SUM(pc.date_count4) total_datecount4,
                        t.VSL
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
                                COUNT(case Reason when 'C1' then 1 else null end) c1_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date' then 1 else null end) date_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date1' then 1 else null end) date_count1,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date2' then 1 else null end) date_count2,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date3' then 1 else null end) date_count3,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date4' then 1 else null end) date_count4  
                            FROM emp e 
                            INNER JOIN team t ON e.Team=t.Team 
                            INNER JOIN attendance a ON e.PN=a.PN 
                            WHERE 
                            DATE(a_date) = CURDATE() and t.id='$id' AND DATE(Entered_date) = CURDATE()
                            ) 
                        pc ON pc.Team=t.Team WHERE DATE(c_date) = CURDATE() ;"
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
                        $total_datecount+=$data->total_datecount;
                        $total_datecount1+=$data->total_datecount1;
                        $total_datecount2+=$data->total_datecount2;
                        $total_datecount3+=$data->total_datecount3;
                        $total_datecount4+=$data->total_datecount4;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount ?></a></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date1 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount1 ?></a></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date2 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount2 ?></a></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date3 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount3 ?></a></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date4 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount4 ?></a></td>
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td><!---->

            </tr>

                            
<!--============================================Naveen-A-Shift======================================-->               
<?php
                $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND VSL='Naveen'");
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
                    $date = date('Y.m.d');
                    $date1 = date('Y.m.d',strtotime('+1 days'));
                    $date2 = date('Y.m.d',strtotime('+2 days'));
                    $date3 = date('Y.m.d',strtotime('+3 days'));
                    $date4 = date('Y.m.d',strtotime('+4 days'));

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
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                        SUM(pc.date_count) total_datecount,
                        SUM(pc.date_count1) total_datecount1,
                        SUM(pc.date_count2) total_datecount2,
                        SUM(pc.date_count3) total_datecount3,
                        SUM(pc.date_count4) total_datecount4,
                        t.VSL
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
                                COUNT(case Reason when 'C1' then 1 else null end) c1_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date' then 1 else null end) date_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date1' then 1 else null end) date_count1,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date2' then 1 else null end) date_count2,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date3' then 1 else null end) date_count3,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date4' then 1 else null end) date_count4  
                            FROM emp e 
                            INNER JOIN team t ON e.Team=t.Team 
                            INNER JOIN attendance a ON e.PN=a.PN 
                            WHERE 
                            DATE(a_date) = CURDATE() and t.id='$id' AND DATE(Entered_date) = CURDATE()
                            ) 
                        pc ON pc.Team=t.Team WHERE DATE(c_date) = CURDATE() ;"
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
                        $total_datecount+=$data->total_datecount;
                        $total_datecount1+=$data->total_datecount1;
                        $total_datecount2+=$data->total_datecount2;
                        $total_datecount3+=$data->total_datecount3;
                        $total_datecount4+=$data->total_datecount4;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount ?></a></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date1 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount1 ?></a></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date2 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount2 ?></a></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date3 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount3 ?></a></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date4 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount4 ?></a></td>
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td><!---->

            </tr>

                            
<!--============================================Chanuka-A-Shift======================================-->               
<?php
                $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Area='A-Chanuka'");
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
                    $date = date('Y.m.d');
                    $date1 = date('Y.m.d',strtotime('+1 days'));
                    $date2 = date('Y.m.d',strtotime('+2 days'));
                    $date3 = date('Y.m.d',strtotime('+3 days'));
                    $date4 = date('Y.m.d',strtotime('+4 days'));

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
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                        SUM(pc.date_count) total_datecount,
                        SUM(pc.date_count1) total_datecount1,
                        SUM(pc.date_count2) total_datecount2,
                        SUM(pc.date_count3) total_datecount3,
                        SUM(pc.date_count4) total_datecount4,
                        t.Area
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
                                COUNT(case Reason when 'C1' then 1 else null end) c1_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date' then 1 else null end) date_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date1' then 1 else null end) date_count1,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date2' then 1 else null end) date_count2,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date3' then 1 else null end) date_count3,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date4' then 1 else null end) date_count4  
                            FROM emp e 
                            INNER JOIN team t ON e.Team=t.Team 
                            INNER JOIN attendance a ON e.PN=a.PN 
                            WHERE 
                            DATE(a_date) = CURDATE() and t.id='$id' AND DATE(Entered_date) = CURDATE()
                            ) 
                        pc ON pc.Team=t.Team WHERE DATE(c_date) = CURDATE() ;"
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
                        $total_datecount+=$data->total_datecount;
                        $total_datecount1+=$data->total_datecount1;
                        $total_datecount2+=$data->total_datecount2;
                        $total_datecount3+=$data->total_datecount3;
                        $total_datecount4+=$data->total_datecount4;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                <td><a href="date-count2.php?VSL=<?php echo $data->Area ?>&date=<?php echo $date ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount ?></a></td>
                <td><a href="date-count2.php?VSL=<?php echo $data->Area ?>&date=<?php echo $date1 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount1 ?></a></td>
                <td><a href="date-count2.php?VSL=<?php echo $data->Area ?>&date=<?php echo $date2 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount2 ?></a></td>
                <td><a href="date-count2.php?VSL=<?php echo $data->Area ?>&date=<?php echo $date3 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount3 ?></a></td>
                <td><a href="date-count2.php?VSL=<?php echo $data->Area ?>&date=<?php echo $date4 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount4 ?></a></td>
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td><!---->

            </tr>
<!--============================================Kasun-B-Shift======================================-->               
            <?php
                $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND VSL='Kasun'");
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
                    $date = date('Y.m.d');
                    $date1 = date('Y.m.d',strtotime('+1 days'));
                    $date2 = date('Y.m.d',strtotime('+2 days'));
                    $date3 = date('Y.m.d',strtotime('+3 days'));
                    $date4 = date('Y.m.d',strtotime('+4 days'));

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
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                        SUM(pc.date_count) total_datecount,
                        SUM(pc.date_count1) total_datecount1,
                        SUM(pc.date_count2) total_datecount2,
                        SUM(pc.date_count3) total_datecount3,
                        SUM(pc.date_count4) total_datecount4,
                        t.VSL
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
                                COUNT(case Reason when 'C1' then 1 else null end) c1_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date' then 1 else null end) date_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date1' then 1 else null end) date_count1,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date2' then 1 else null end) date_count2,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date3' then 1 else null end) date_count3,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date4' then 1 else null end) date_count4  
                            FROM emp e 
                            INNER JOIN team t ON e.Team=t.Team 
                            INNER JOIN attendance a ON e.PN=a.PN 
                            WHERE 
                            DATE(a_date) = CURDATE() and t.id='$id' AND DATE(Entered_date) = CURDATE()
                            ) 
                        pc ON pc.Team=t.Team WHERE DATE(c_date) = CURDATE() ;"
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
                        $total_datecount+=$data->total_datecount;
                        $total_datecount1+=$data->total_datecount1;
                        $total_datecount2+=$data->total_datecount2;
                        $total_datecount3+=$data->total_datecount3;
                        $total_datecount4+=$data->total_datecount4;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount ?></a></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date1 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount1 ?></a></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date2 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount2 ?></a></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date3 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount3 ?></a></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date4 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount4 ?></a></td>
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td><!---->

            </tr>

                            
<!--============================================Miyuranga-A-Shift======================================-->               
<?php
                $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND VSL='Miyuranga'");
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
                    $date = date('Y.m.d');
                    $date1 = date('Y.m.d',strtotime('+1 days'));
                    $date2 = date('Y.m.d',strtotime('+2 days'));
                    $date3 = date('Y.m.d',strtotime('+3 days'));
                    $date4 = date('Y.m.d',strtotime('+4 days'));

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
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                        SUM(pc.date_count) total_datecount,
                        SUM(pc.date_count1) total_datecount1,
                        SUM(pc.date_count2) total_datecount2,
                        SUM(pc.date_count3) total_datecount3,
                        SUM(pc.date_count4) total_datecount4,
                        t.VSL
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
                                COUNT(case Reason when 'C1' then 1 else null end) c1_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date' then 1 else null end) date_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date1' then 1 else null end) date_count1,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date2' then 1 else null end) date_count2,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date3' then 1 else null end) date_count3,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date4' then 1 else null end) date_count4  
                            FROM emp e 
                            INNER JOIN team t ON e.Team=t.Team 
                            INNER JOIN attendance a ON e.PN=a.PN 
                            WHERE 
                            DATE(a_date) = CURDATE() and t.id='$id' AND DATE(Entered_date) = CURDATE()
                            ) 
                        pc ON pc.Team=t.Team WHERE DATE(c_date) = CURDATE() ;"
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
                        $total_datecount+=$data->total_datecount;
                        $total_datecount1+=$data->total_datecount1;
                        $total_datecount2+=$data->total_datecount2;
                        $total_datecount3+=$data->total_datecount3;
                        $total_datecount4+=$data->total_datecount4;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount ?></a></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date1 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount1 ?></a></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date2 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount2 ?></a></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date3 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount3 ?></a></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date4 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount4 ?></a></td>
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td><!---->

            </tr>

                            
<!--============================================Thilina-B-Shift======================================-->               
<?php
                $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Area='B-Thilina'");
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
                    $date = date('Y.m.d');
                    $date1 = date('Y.m.d',strtotime('+1 days'));
                    $date2 = date('Y.m.d',strtotime('+2 days'));
                    $date3 = date('Y.m.d',strtotime('+3 days'));
                    $date4 = date('Y.m.d',strtotime('+4 days'));

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
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                        SUM(pc.date_count) total_datecount,
                        SUM(pc.date_count1) total_datecount1,
                        SUM(pc.date_count2) total_datecount2,
                        SUM(pc.date_count3) total_datecount3,
                        SUM(pc.date_count4) total_datecount4,
                        t.Area
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
                                COUNT(case Reason when 'C1' then 1 else null end) c1_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date' then 1 else null end) date_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date1' then 1 else null end) date_count1,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date2' then 1 else null end) date_count2,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date3' then 1 else null end) date_count3,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date4' then 1 else null end) date_count4  
                            FROM emp e 
                            INNER JOIN team t ON e.Team=t.Team 
                            INNER JOIN attendance a ON e.PN=a.PN 
                            WHERE 
                            DATE(a_date) = CURDATE() and t.id='$id' AND DATE(Entered_date) = CURDATE()
                            ) 
                        pc ON pc.Team=t.Team WHERE DATE(c_date) = CURDATE() ;"
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
                        $total_datecount+=$data->total_datecount;
                        $total_datecount1+=$data->total_datecount1;
                        $total_datecount2+=$data->total_datecount2;
                        $total_datecount3+=$data->total_datecount3;
                        $total_datecount4+=$data->total_datecount4;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                <td><a href="date-count2.php?VSL=<?php echo $data->Area ?>&date=<?php echo $date ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount ?></a></td>
                <td><a href="date-count2.php?VSL=<?php echo $data->Area ?>&date=<?php echo $date1 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount1 ?></a></td>
                <td><a href="date-count2.php?VSL=<?php echo $data->Area ?>&date=<?php echo $date2 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount2 ?></a></td>
                <td><a href="date-count2.php?VSL=<?php echo $data->Area ?>&date=<?php echo $date3 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount3 ?></a></td>
                <td><a href="date-count2.php?VSL=<?php echo $data->Area ?>&date=<?php echo $date4 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount4 ?></a></td>
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td><!---->

            </tr>

<!--============================================Saman-B-Shift======================================-->               
            <?php
                $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND VSL='Saman'");
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
                    $date = date('Y.m.d');
                    $date1 = date('Y.m.d',strtotime('+1 days'));
                    $date2 = date('Y.m.d',strtotime('+2 days'));
                    $date3 = date('Y.m.d',strtotime('+3 days'));
                    $date4 = date('Y.m.d',strtotime('+4 days'));

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
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                        SUM(pc.date_count) total_datecount,
                        SUM(pc.date_count1) total_datecount1,
                        SUM(pc.date_count2) total_datecount2,
                        SUM(pc.date_count3) total_datecount3,
                        SUM(pc.date_count4) total_datecount4,
                        t.VSL
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
                                COUNT(case Reason when 'C1' then 1 else null end) c1_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date' then 1 else null end) date_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date1' then 1 else null end) date_count1,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date2' then 1 else null end) date_count2,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date3' then 1 else null end) date_count3,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date4' then 1 else null end) date_count4  
                            FROM emp e 
                            INNER JOIN team t ON e.Team=t.Team 
                            INNER JOIN attendance a ON e.PN=a.PN 
                            WHERE 
                            DATE(a_date) = CURDATE() and t.id='$id' AND DATE(Entered_date) = CURDATE()
                            ) 
                        pc ON pc.Team=t.Team WHERE DATE(c_date) = CURDATE() ;"
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
                        $total_datecount+=$data->total_datecount;
                        $total_datecount1+=$data->total_datecount1;
                        $total_datecount2+=$data->total_datecount2;
                        $total_datecount3+=$data->total_datecount3;
                        $total_datecount4+=$data->total_datecount4;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount ?></a></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date1 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount1 ?></a></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date2 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount2 ?></a></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date3 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount3 ?></a></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date4 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount4 ?></a></td>
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td><!---->

            </tr>

                            
<!--============================================Miyuranga-A-Shift======================================-->               
<?php
                $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND VSL='Pradeep'");
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
                    $date = date('Y.m.d');
                    $date1 = date('Y.m.d',strtotime('+1 days'));
                    $date2 = date('Y.m.d',strtotime('+2 days'));
                    $date3 = date('Y.m.d',strtotime('+3 days'));
                    $date4 = date('Y.m.d',strtotime('+4 days'));

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
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                        SUM(pc.date_count) total_datecount,
                        SUM(pc.date_count1) total_datecount1,
                        SUM(pc.date_count2) total_datecount2,
                        SUM(pc.date_count3) total_datecount3,
                        SUM(pc.date_count4) total_datecount4,
                        t.VSL
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
                                COUNT(case Reason when 'C1' then 1 else null end) c1_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date' then 1 else null end) date_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date1' then 1 else null end) date_count1,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date2' then 1 else null end) date_count2,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date3' then 1 else null end) date_count3,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date4' then 1 else null end) date_count4  
                            FROM emp e 
                            INNER JOIN team t ON e.Team=t.Team 
                            INNER JOIN attendance a ON e.PN=a.PN 
                            WHERE 
                            DATE(a_date) = CURDATE() and t.id='$id' AND DATE(Entered_date) = CURDATE()
                            ) 
                        pc ON pc.Team=t.Team WHERE DATE(c_date) = CURDATE() ;"
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
                        $total_datecount+=$data->total_datecount;
                        $total_datecount1+=$data->total_datecount1;
                        $total_datecount2+=$data->total_datecount2;
                        $total_datecount3+=$data->total_datecount3;
                        $total_datecount4+=$data->total_datecount4;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount ?></a></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date1 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount1 ?></a></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date2 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount2 ?></a></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date3 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount3 ?></a></td>
                <td><a href="date-count.php?VSL=<?php echo $data->VSL ?>&date=<?php echo $date4 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount4 ?></a></td>
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td><!---->

            </tr>

                            
<!--============================================Chanuka-B-Shift======================================-->               
<?php
                $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Area='B-Chanuka'");
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
                    $date = date('Y.m.d');
                    $date1 = date('Y.m.d',strtotime('+1 days'));
                    $date2 = date('Y.m.d',strtotime('+2 days'));
                    $date3 = date('Y.m.d',strtotime('+3 days'));
                    $date4 = date('Y.m.d',strtotime('+4 days'));

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
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                        SUM(pc.date_count) total_datecount,
                        SUM(pc.date_count1) total_datecount1,
                        SUM(pc.date_count2) total_datecount2,
                        SUM(pc.date_count3) total_datecount3,
                        SUM(pc.date_count4) total_datecount4,
                        t.Area
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
                                COUNT(case Reason when 'C1' then 1 else null end) c1_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date' then 1 else null end) date_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date1' then 1 else null end) date_count1,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date2' then 1 else null end) date_count2,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date3' then 1 else null end) date_count3,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date4' then 1 else null end) date_count4  
                            FROM emp e 
                            INNER JOIN team t ON e.Team=t.Team 
                            INNER JOIN attendance a ON e.PN=a.PN 
                            WHERE 
                            DATE(a_date) = CURDATE() and t.id='$id' AND DATE(Entered_date) = CURDATE()
                            ) 
                        pc ON pc.Team=t.Team WHERE DATE(c_date) = CURDATE() ;"
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
                        $total_datecount+=$data->total_datecount;
                        $total_datecount1+=$data->total_datecount1;
                        $total_datecount2+=$data->total_datecount2;
                        $total_datecount3+=$data->total_datecount3;
                        $total_datecount4+=$data->total_datecount4;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                <td><a href="date-count2.php?VSL=<?php echo $data->Area ?>&date=<?php echo $date ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount ?></a></td>
                <td><a href="date-count2.php?VSL=<?php echo $data->Area ?>&date=<?php echo $date1 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount1 ?></a></td>
                <td><a href="date-count2.php?VSL=<?php echo $data->Area ?>&date=<?php echo $date2 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount2 ?></a></td>
                <td><a href="date-count2.php?VSL=<?php echo $data->Area ?>&date=<?php echo $date3 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount3 ?></a></td>
                <td><a href="date-count2.php?VSL=<?php echo $data->Area ?>&date=<?php echo $date4 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount4 ?></a></td>
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td><!---->

            </tr>


<!--============================================Total-Shift-A======================================-->  
            <?php
                $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Area IN ('A-Thilina','A-Chanuka')");
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
                    $date = date('Y.m.d');
                    $date1 = date('Y.m.d',strtotime('+1 days'));
                    $date2 = date('Y.m.d',strtotime('+2 days'));
                    $date3 = date('Y.m.d',strtotime('+3 days'));
                    $date4 = date('Y.m.d',strtotime('+4 days'));

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
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                        SUM(pc.date_count) total_datecount,
                        SUM(pc.date_count1) total_datecount1,
                        SUM(pc.date_count2) total_datecount2,
                        SUM(pc.date_count3) total_datecount3,
                        SUM(pc.date_count4) total_datecount4,
                        t.VSL
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
                                COUNT(case Reason when 'C1' then 1 else null end) c1_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date' then 1 else null end) date_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date1' then 1 else null end) date_count1,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date2' then 1 else null end) date_count2,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date3' then 1 else null end) date_count3,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date4' then 1 else null end) date_count4  
                            FROM emp e 
                            INNER JOIN team t ON e.Team=t.Team 
                            INNER JOIN attendance a ON e.PN=a.PN 
                            WHERE 
                            DATE(a_date) = CURDATE() and t.id='$id' AND DATE(Entered_date) = CURDATE()
                            ) 
                        pc ON pc.Team=t.Team WHERE DATE(c_date) = CURDATE() ;"
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
                        $total_datecount+=$data->total_datecount;
                        $total_datecount1+=$data->total_datecount1;
                        $total_datecount2+=$data->total_datecount2;
                        $total_datecount3+=$data->total_datecount3;
                        $total_datecount4+=$data->total_datecount4;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                <td><a href="date-count3.php?VSL=A-Thilina&VSL1=A-Chanuka&date=<?php echo $date ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount ?></a></td>
                <td><a href="date-count3.php?VSL=A-Thilina&VSL1=A-Chanuka&date=<?php echo $date1 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount1 ?></a></td>
                <td><a href="date-count3.php?VSL=A-Thilina&VSL1=A-Chanuka&date=<?php echo $date2 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount2 ?></a></td>
                <td><a href="date-count3.php?VSL=A-Thilina&VSL1=A-Chanuka&date=<?php echo $date3 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount3 ?></a></td>
                <td><a href="date-count3.php?VSL=A-Thilina&VSL1=A-Chanuka&date=<?php echo $date4 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount4 ?></a></td>
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td><!---->

            </tr>

<!--============================================Total-Shift-B======================================-->
            <?php
                $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Area IN ('B-Thilina','B-Chanuka')");
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
                    $date = date('Y.m.d');
                    $date1 = date('Y.m.d',strtotime('+1 days'));
                    $date2 = date('Y.m.d',strtotime('+2 days'));
                    $date3 = date('Y.m.d',strtotime('+3 days'));
                    $date4 = date('Y.m.d',strtotime('+4 days'));

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
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                        SUM(pc.date_count) total_datecount,
                        SUM(pc.date_count1) total_datecount1,
                        SUM(pc.date_count2) total_datecount2,
                        SUM(pc.date_count3) total_datecount3,
                        SUM(pc.date_count4) total_datecount4,
                        t.VSL
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
                                COUNT(case Reason when 'C1' then 1 else null end) c1_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date' then 1 else null end) date_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date1' then 1 else null end) date_count1,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date2' then 1 else null end) date_count2,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date3' then 1 else null end) date_count3,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date4' then 1 else null end) date_count4  
                            FROM emp e 
                            INNER JOIN team t ON e.Team=t.Team 
                            INNER JOIN attendance a ON e.PN=a.PN 
                            WHERE 
                            DATE(a_date) = CURDATE() and t.id='$id' AND DATE(Entered_date) = CURDATE()
                            ) 
                        pc ON pc.Team=t.Team WHERE DATE(c_date) = CURDATE() ;"
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
                        $total_datecount+=$data->total_datecount;
                        $total_datecount1+=$data->total_datecount1;
                        $total_datecount2+=$data->total_datecount2;
                        $total_datecount3+=$data->total_datecount3;
                        $total_datecount4+=$data->total_datecount4;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                <td><a href="date-count3.php?VSL=B-Thilina&VSL1=B-Chanuka&date=<?php echo $date ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount ?></a></td>
                <td><a href="date-count3.php?VSL=B-Thilina&VSL1=B-Chanuka&date=<?php echo $date1 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount1 ?></a></td>
                <td><a href="date-count3.php?VSL=B-Thilina&VSL1=B-Chanuka&date=<?php echo $date2 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount2 ?></a></td>
                <td><a href="date-count3.php?VSL=B-Thilina&VSL1=B-Chanuka&date=<?php echo $date3 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount3 ?></a></td>
                <td><a href="date-count3.php?VSL=B-Thilina&VSL1=B-Chanuka&date=<?php echo $date4 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount4 ?></a></td>
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td><!---->
            </tr>
<!--============================================Total-MO======================================-->
            <?php
                $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Area IN ('A-Thilina','A-Chanuka','B-Thilina','B-Chanuka')");
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
                    $date = date('Y.m.d');
                    $date1 = date('Y.m.d',strtotime('+1 days'));
                    $date2 = date('Y.m.d',strtotime('+2 days'));
                    $date3 = date('Y.m.d',strtotime('+3 days'));
                    $date4 = date('Y.m.d',strtotime('+4 days'));

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
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                        SUM(pc.date_count) total_datecount,
                        SUM(pc.date_count1) total_datecount1,
                        SUM(pc.date_count2) total_datecount2,
                        SUM(pc.date_count3) total_datecount3,
                        SUM(pc.date_count4) total_datecount4,
                        t.VSL
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
                                COUNT(case Reason when 'C1' then 1 else null end) c1_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date' then 1 else null end) date_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date1' then 1 else null end) date_count1,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date2' then 1 else null end) date_count2,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date3' then 1 else null end) date_count3,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date4' then 1 else null end) date_count4  
                            FROM emp e 
                            INNER JOIN team t ON e.Team=t.Team 
                            INNER JOIN attendance a ON e.PN=a.PN 
                            WHERE 
                            DATE(a_date) = CURDATE() and t.id='$id' AND DATE(Entered_date) = CURDATE()
                            ) 
                        pc ON pc.Team=t.Team WHERE DATE(c_date) = CURDATE() ;"
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
                        $total_datecount+=$data->total_datecount;
                        $total_datecount1+=$data->total_datecount1;
                        $total_datecount2+=$data->total_datecount2;
                        $total_datecount3+=$data->total_datecount3;
                        $total_datecount4+=$data->total_datecount4;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                <td><a href="date-count4.php?VSL=A-Thilina&VSL1=A-Chanuka&VSL2=B-Thilina&VSL3=B-Chanuka&date=<?php echo $date ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount ?></a></td>
                <td><a href="date-count4.php?VSL=A-Thilina&VSL1=A-Chanuka&VSL2=B-Thilina&VSL3=B-Chanuka&date=<?php echo $date1 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount1 ?></a></td>
                <td><a href="date-count4.php?VSL=A-Thilina&VSL1=A-Chanuka&VSL2=B-Thilina&VSL3=B-Chanuka&date=<?php echo $date2 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount2 ?></a></td>
                <td><a href="date-count4.php?VSL=A-Thilina&VSL1=A-Chanuka&VSL2=B-Thilina&VSL3=B-Chanuka&date=<?php echo $date3 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount3 ?></a></td>
                <td><a href="date-count4.php?VSL=A-Thilina&VSL1=A-Chanuka&VSL2=B-Thilina&VSL3=B-Chanuka&date=<?php echo $date4 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount4 ?></a></td>
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td><!---->

            </tr>

<!--============================================Elastic-Hub=========================================-->
            <?php
                $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team='Elastic Hub - SY'");
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
                    $date = date('Y.m.d');
                    $date1 = date('Y.m.d',strtotime('+1 days'));
                    $date2 = date('Y.m.d',strtotime('+2 days'));
                    $date3 = date('Y.m.d',strtotime('+3 days'));
                    $date4 = date('Y.m.d',strtotime('+4 days'));

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
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                        SUM(pc.date_count) total_datecount,
                        SUM(pc.date_count1) total_datecount1,
                        SUM(pc.date_count2) total_datecount2,
                        SUM(pc.date_count3) total_datecount3,
                        SUM(pc.date_count4) total_datecount4,
                        t.Team
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
                                COUNT(case Reason when 'C1' then 1 else null end) c1_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date' then 1 else null end) date_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date1' then 1 else null end) date_count1,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date2' then 1 else null end) date_count2,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date3' then 1 else null end) date_count3,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date4' then 1 else null end) date_count4  
                            FROM emp e 
                            INNER JOIN team t ON e.Team=t.Team 
                            INNER JOIN attendance a ON e.PN=a.PN 
                            WHERE 
                            DATE(a_date) = CURDATE() and t.id='$id' AND DATE(Entered_date) = CURDATE()
                            ) 
                        pc ON pc.Team=t.Team WHERE DATE(c_date) = CURDATE() ;"
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
                        $total_datecount+=$data->total_datecount;
                        $total_datecount1+=$data->total_datecount1;
                        $total_datecount2+=$data->total_datecount2;
                        $total_datecount3+=$data->total_datecount3;
                        $total_datecount4+=$data->total_datecount4;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                <td><a href="date-count5.php?VSL=<?php echo $data->Team ?>&date=<?php echo $date ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount ?></a></td>
                <td><a href="date-count5.php?VSL=<?php echo $data->Team ?>&date=<?php echo $date1 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount1 ?></a></td>
                <td><a href="date-count5.php?VSL=<?php echo $data->Team ?>&date=<?php echo $date2 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount2 ?></a></td>
                <td><a href="date-count5.php?VSL=<?php echo $data->Team  ?>&date=<?php echo $date3 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount3 ?></a></td>
                <td><a href="date-count5.php?VSL=<?php echo $data->Team ?>&date=<?php echo $date4 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount4 ?></a></td>
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td><!---->

            </tr>

<!--============================================Supreme-Operators======================================-->
            <?php
                $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team='Supreme Operators - SY'");
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
                    $date = date('Y.m.d');
                    $date1 = date('Y.m.d',strtotime('+1 days'));
                    $date2 = date('Y.m.d',strtotime('+2 days'));
                    $date3 = date('Y.m.d',strtotime('+3 days'));
                    $date4 = date('Y.m.d',strtotime('+4 days'));

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
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                        SUM(pc.date_count) total_datecount,
                        SUM(pc.date_count1) total_datecount1,
                        SUM(pc.date_count2) total_datecount2,
                        SUM(pc.date_count3) total_datecount3,
                        SUM(pc.date_count4) total_datecount4,
                        t.Team
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
                                COUNT(case Reason when 'C1' then 1 else null end) c1_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date' then 1 else null end) date_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date1' then 1 else null end) date_count1,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date2' then 1 else null end) date_count2,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date3' then 1 else null end) date_count3,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date4' then 1 else null end) date_count4  
                            FROM emp e 
                            INNER JOIN team t ON e.Team=t.Team 
                            INNER JOIN attendance a ON e.PN=a.PN 
                            WHERE 
                            DATE(a_date) = CURDATE() and t.id='$id' AND DATE(Entered_date) = CURDATE()
                            ) 
                        pc ON pc.Team=t.Team WHERE DATE(c_date) = CURDATE() ;"
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
                        $total_datecount+=$data->total_datecount;
                        $total_datecount1+=$data->total_datecount1;
                        $total_datecount2+=$data->total_datecount2;
                        $total_datecount3+=$data->total_datecount3;
                        $total_datecount4+=$data->total_datecount4;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                <td><a href="date-count5.php?VSL=<?php echo $data->Team ?>&date=<?php echo $date ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount ?></a></td>
                <td><a href="date-count5.php?VSL=<?php echo $data->Team ?>&date=<?php echo $date1 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount1 ?></a></td>
                <td><a href="date-count5.php?VSL=<?php echo $data->Team ?>&date=<?php echo $date2 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount2 ?></a></td>
                <td><a href="date-count5.php?VSL=<?php echo $data->Team  ?>&date=<?php echo $date3 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount3 ?></a></td>
                <td><a href="date-count5.php?VSL=<?php echo $data->Team ?>&date=<?php echo $date4 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount4 ?></a></td>
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td><!---->

            </tr>


<!--============================================Dancing-Production-Jumpers======================================-->

            <?php
                $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team='Standing Jumpers - SY'");
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
                    $date = date('Y.m.d');
                    $date1 = date('Y.m.d',strtotime('+1 days'));
                    $date2 = date('Y.m.d',strtotime('+2 days'));
                    $date3 = date('Y.m.d',strtotime('+3 days'));
                    $date4 = date('Y.m.d',strtotime('+4 days'));

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
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                        SUM(pc.date_count) total_datecount,
                        SUM(pc.date_count1) total_datecount1,
                        SUM(pc.date_count2) total_datecount2,
                        SUM(pc.date_count3) total_datecount3,
                        SUM(pc.date_count4) total_datecount4,
                        t.Team
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
                                COUNT(case Reason when 'C1' then 1 else null end) c1_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date' then 1 else null end) date_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date1' then 1 else null end) date_count1,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date2' then 1 else null end) date_count2,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date3' then 1 else null end) date_count3,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date4' then 1 else null end) date_count4  
                            FROM emp e 
                            INNER JOIN team t ON e.Team=t.Team 
                            INNER JOIN attendance a ON e.PN=a.PN 
                            WHERE 
                            DATE(a_date) = CURDATE() and t.id='$id' AND DATE(Entered_date) = CURDATE()
                            ) 
                        pc ON pc.Team=t.Team WHERE DATE(c_date) = CURDATE() ;"
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
                        $total_datecount+=$data->total_datecount;
                        $total_datecount1+=$data->total_datecount1;
                        $total_datecount2+=$data->total_datecount2;
                        $total_datecount3+=$data->total_datecount3;
                        $total_datecount4+=$data->total_datecount4;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                <td><a href="date-count5.php?VSL=<?php echo $data->Team ?>&date=<?php echo $date ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount ?></a></td>
                <td><a href="date-count5.php?VSL=<?php echo $data->Team ?>&date=<?php echo $date1 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount1 ?></a></td>
                <td><a href="date-count5.php?VSL=<?php echo $data->Team ?>&date=<?php echo $date2 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount2 ?></a></td>
                <td><a href="date-count5.php?VSL=<?php echo $data->Team  ?>&date=<?php echo $date3 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount3 ?></a></td>
                <td><a href="date-count5.php?VSL=<?php echo $data->Team ?>&date=<?php echo $date4 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount4 ?></a></td>
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td><!---->

            </tr>

<!--=======================================Total-Production-Floor======================================-->

            <?php
                $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Area IN ('A-Thilina','A-Chanuka','B-Thilina','B-Chanuka') OR Team IN ('Elastic Hub - SY','Supreme Operators - SY','Standing Jumpers - SY') AND DATE(c_date) = CURDATE()");
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
                    $date = date('Y.m.d');
                    $date1 = date('Y.m.d',strtotime('+1 days'));
                    $date2 = date('Y.m.d',strtotime('+2 days'));
                    $date3 = date('Y.m.d',strtotime('+3 days'));
                    $date4 = date('Y.m.d',strtotime('+4 days'));

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
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                        SUM(pc.date_count) total_datecount,
                        SUM(pc.date_count1) total_datecount1,
                        SUM(pc.date_count2) total_datecount2,
                        SUM(pc.date_count3) total_datecount3,
                        SUM(pc.date_count4) total_datecount4,
                        t.VSL
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
                                COUNT(case Reason when 'C1' then 1 else null end) c1_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date' then 1 else null end) date_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date1' then 1 else null end) date_count1,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date2' then 1 else null end) date_count2,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date3' then 1 else null end) date_count3,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date4' then 1 else null end) date_count4  
                            FROM emp e 
                            INNER JOIN team t ON e.Team=t.Team 
                            INNER JOIN attendance a ON e.PN=a.PN 
                            WHERE 
                            DATE(a_date) = CURDATE() and t.id='$id' AND DATE(Entered_date) = CURDATE()
                            ) 
                        pc ON pc.Team=t.Team WHERE DATE(c_date) = CURDATE() ;"
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
                        $total_datecount+=$data->total_datecount;
                        $total_datecount1+=$data->total_datecount1;
                        $total_datecount2+=$data->total_datecount2;
                        $total_datecount3+=$data->total_datecount3;
                        $total_datecount4+=$data->total_datecount4;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                <td><a href="date-count6.php?date=<?php echo $date ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount ?></a></td>
                <td><a href="date-count6.php?date=<?php echo $date1 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount1 ?></a></td>
                <td><a href="date-count6.php?date=<?php echo $date2 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount2 ?></a></td>
                <td><a href="date-count6.php?date=<?php echo $date3 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount3 ?></a></td>
                <td><a href="date-count6.php?date=<?php echo $date4 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount4 ?></a></td>
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td><!---->

            </tr>
<!--============================================Sitting-Jumpers======================================-->
            <?php
                $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team='Sitting Jumpers - SY'");
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
                    $date = date('Y.m.d');
                    $date1 = date('Y.m.d',strtotime('+1 days'));
                    $date2 = date('Y.m.d',strtotime('+2 days'));
                    $date3 = date('Y.m.d',strtotime('+3 days'));
                    $date4 = date('Y.m.d',strtotime('+4 days'));

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
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                        SUM(pc.date_count) total_datecount,
                        SUM(pc.date_count1) total_datecount1,
                        SUM(pc.date_count2) total_datecount2,
                        SUM(pc.date_count3) total_datecount3,
                        SUM(pc.date_count4) total_datecount4,
                        t.Team
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
                                COUNT(case Reason when 'C1' then 1 else null end) c1_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date' then 1 else null end) date_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date1' then 1 else null end) date_count1,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date2' then 1 else null end) date_count2,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date3' then 1 else null end) date_count3,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date4' then 1 else null end) date_count4  
                            FROM emp e 
                            INNER JOIN team t ON e.Team=t.Team 
                            INNER JOIN attendance a ON e.PN=a.PN 
                            WHERE 
                            DATE(a_date) = CURDATE() and t.id='$id' AND DATE(Entered_date) = CURDATE()
                            ) 
                        pc ON pc.Team=t.Team WHERE DATE(c_date) = CURDATE() ;"
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
                        $total_datecount+=$data->total_datecount;
                        $total_datecount1+=$data->total_datecount1;
                        $total_datecount2+=$data->total_datecount2;
                        $total_datecount3+=$data->total_datecount3;
                        $total_datecount4+=$data->total_datecount4;
                    }
                }    
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);
                ?> 
            <tr>

                <td><?php echo 'Sitting QCO Jumpers' ?></td>
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                <td><a href="date-count5.php?VSL=<?php echo $data->Team ?>&date=<?php echo $date ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount ?></a></td>
                <td><a href="date-count5.php?VSL=<?php echo $data->Team ?>&date=<?php echo $date1 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount1 ?></a></td>
                <td><a href="date-count5.php?VSL=<?php echo $data->Team ?>&date=<?php echo $date2 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount2 ?></a></td>
                <td><a href="date-count5.php?VSL=<?php echo $data->Team  ?>&date=<?php echo $date3 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount3 ?></a></td>
                <td><a href="date-count5.php?VSL=<?php echo $data->Team ?>&date=<?php echo $date4 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount4 ?></a></td>
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td><!---->
            </tr>

<!--============================================AQL-Foldings======================================-->

            <?php
                $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team='Team - 52 - SY'");
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
                    $date = date('Y.m.d');
                    $date1 = date('Y.m.d',strtotime('+1 days'));
                    $date2 = date('Y.m.d',strtotime('+2 days'));
                    $date3 = date('Y.m.d',strtotime('+3 days'));
                    $date4 = date('Y.m.d',strtotime('+4 days'));

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
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                        SUM(pc.date_count) total_datecount,
                        SUM(pc.date_count1) total_datecount1,
                        SUM(pc.date_count2) total_datecount2,
                        SUM(pc.date_count3) total_datecount3,
                        SUM(pc.date_count4) total_datecount4,
                        t.Team
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
                                COUNT(case Reason when 'C1' then 1 else null end) c1_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date' then 1 else null end) date_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date1' then 1 else null end) date_count1,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date2' then 1 else null end) date_count2,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date3' then 1 else null end) date_count3,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date4' then 1 else null end) date_count4  
                            FROM emp e 
                            INNER JOIN team t ON e.Team=t.Team 
                            INNER JOIN attendance a ON e.PN=a.PN 
                            WHERE 
                            DATE(a_date) = CURDATE() and t.id='$id' AND DATE(Entered_date) = CURDATE()
                            ) 
                        pc ON pc.Team=t.Team WHERE DATE(c_date) = CURDATE() ;"
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
                        $total_datecount+=$data->total_datecount;
                        $total_datecount1+=$data->total_datecount1;
                        $total_datecount2+=$data->total_datecount2;
                        $total_datecount3+=$data->total_datecount3;
                        $total_datecount4+=$data->total_datecount4;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                <td><a href="date-count5.php?VSL=<?php echo $data->Team ?>&date=<?php echo $date ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount ?></a></td>
                <td><a href="date-count5.php?VSL=<?php echo $data->Team ?>&date=<?php echo $date1 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount1 ?></a></td>
                <td><a href="date-count5.php?VSL=<?php echo $data->Team ?>&date=<?php echo $date2 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount2 ?></a></td>
                <td><a href="date-count5.php?VSL=<?php echo $data->Team  ?>&date=<?php echo $date3 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount3 ?></a></td>
                <td><a href="date-count5.php?VSL=<?php echo $data->Team ?>&date=<?php echo $date4 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount4 ?></a></td>
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td><!---->

            </tr>

<!--============================================Spot-Cleaning======================================-->

            <?php
                $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team='Spot Cleaning - Lab - SY'");
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
                    $date = date('Y.m.d');
                    $date1 = date('Y.m.d',strtotime('+1 days'));
                    $date2 = date('Y.m.d',strtotime('+2 days'));
                    $date3 = date('Y.m.d',strtotime('+3 days'));
                    $date4 = date('Y.m.d',strtotime('+4 days'));

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
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                        SUM(pc.date_count) total_datecount,
                        SUM(pc.date_count1) total_datecount1,
                        SUM(pc.date_count2) total_datecount2,
                        SUM(pc.date_count3) total_datecount3,
                        SUM(pc.date_count4) total_datecount4,
                        t.Team
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
                                COUNT(case Reason when 'C1' then 1 else null end) c1_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date' then 1 else null end) date_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date1' then 1 else null end) date_count1,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date2' then 1 else null end) date_count2,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date3' then 1 else null end) date_count3,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date4' then 1 else null end) date_count4  
                            FROM emp e 
                            INNER JOIN team t ON e.Team=t.Team 
                            INNER JOIN attendance a ON e.PN=a.PN 
                            WHERE 
                            DATE(a_date) = CURDATE() and t.id='$id' AND DATE(Entered_date) = CURDATE()
                            ) 
                        pc ON pc.Team=t.Team WHERE DATE(c_date) = CURDATE() ;"
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
                        $total_datecount+=$data->total_datecount;
                        $total_datecount1+=$data->total_datecount1;
                        $total_datecount2+=$data->total_datecount2;
                        $total_datecount3+=$data->total_datecount3;
                        $total_datecount4+=$data->total_datecount4;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                <td><a href="date-count5.php?VSL=<?php echo $data->Team ?>&date=<?php echo $date ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount ?></a></td>
                <td><a href="date-count5.php?VSL=<?php echo $data->Team ?>&date=<?php echo $date1 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount1 ?></a></td>
                <td><a href="date-count5.php?VSL=<?php echo $data->Team ?>&date=<?php echo $date2 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount2 ?></a></td>
                <td><a href="date-count5.php?VSL=<?php echo $data->Team  ?>&date=<?php echo $date3 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount3 ?></a></td>
                <td><a href="date-count5.php?VSL=<?php echo $data->Team ?>&date=<?php echo $date4 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount4 ?></a></td>
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td><!---->

            </tr>

<!--===========================================Total Other Directs======================================-->

            <?php
                $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Area='O.Directs'");
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
                    $date = date('Y.m.d');
                    $date1 = date('Y.m.d',strtotime('+1 days'));
                    $date2 = date('Y.m.d',strtotime('+2 days'));
                    $date3 = date('Y.m.d',strtotime('+3 days'));
                    $date4 = date('Y.m.d',strtotime('+4 days'));

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
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                        SUM(pc.date_count) total_datecount,
                        SUM(pc.date_count1) total_datecount1,
                        SUM(pc.date_count2) total_datecount2,
                        SUM(pc.date_count3) total_datecount3,
                        SUM(pc.date_count4) total_datecount4,
                        t.Area
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
                                COUNT(case Reason when 'C1' then 1 else null end) c1_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date' then 1 else null end) date_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date1' then 1 else null end) date_count1,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date2' then 1 else null end) date_count2,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date3' then 1 else null end) date_count3,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date4' then 1 else null end) date_count4  
                            FROM emp e 
                            INNER JOIN team t ON e.Team=t.Team 
                            INNER JOIN attendance a ON e.PN=a.PN 
                            WHERE 
                            DATE(a_date) = CURDATE() and t.id='$id' AND DATE(Entered_date) = CURDATE()
                            ) 
                        pc ON pc.Team=t.Team WHERE DATE(c_date) = CURDATE() ;"
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
                        $total_datecount+=$data->total_datecount;
                        $total_datecount1+=$data->total_datecount1;
                        $total_datecount2+=$data->total_datecount2;
                        $total_datecount3+=$data->total_datecount3;
                        $total_datecount4+=$data->total_datecount4;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                <td><a href="date-count2.php?VSL=<?php echo $data->Area ?>&date=<?php echo $date ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount ?></a></td>
                <td><a href="date-count2.php?VSL=<?php echo $data->Area ?>&date=<?php echo $date1 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount1 ?></a></td>
                <td><a href="date-count2.php?VSL=<?php echo $data->Area ?>&date=<?php echo $date2 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount2 ?></a></td>
                <td><a href="date-count2.php?VSL=<?php echo $data->Area ?>&date=<?php echo $date3 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount3 ?></a></td>
                <td><a href="date-count2.php?VSL=<?php echo $data->Area ?>&date=<?php echo $date4 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount4 ?></a></td>
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td><!---->

            </tr>

<!--============================================Total Directs======================================-->

            <?php
                $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Area IN ('A-Thilina','A-Chanuka','B-Thilina','B-Chanuka','O.Directs')");
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
                    $date = date('Y.m.d');
                    $date1 = date('Y.m.d',strtotime('+1 days'));
                    $date2 = date('Y.m.d',strtotime('+2 days'));
                    $date3 = date('Y.m.d',strtotime('+3 days'));
                    $date4 = date('Y.m.d',strtotime('+4 days'));

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
                        SUM((((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100)) total_percentage,
                        SUM(pc.date_count) total_datecount,
                        SUM(pc.date_count1) total_datecount1,
                        SUM(pc.date_count2) total_datecount2,
                        SUM(pc.date_count3) total_datecount3,
                        SUM(pc.date_count4) total_datecount4,
                        t.VSL
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
                                COUNT(case Reason when 'C1' then 1 else null end) c1_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date' then 1 else null end) date_count,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date1' then 1 else null end) date_count1,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date2' then 1 else null end) date_count2,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date3' then 1 else null end) date_count3,
                                COUNT(case when Reason IN ('C1','Symptoms','Positive') AND Date_To='$date4' then 1 else null end) date_count4  
                            FROM emp e 
                            INNER JOIN team t ON e.Team=t.Team 
                            INNER JOIN attendance a ON e.PN=a.PN 
                            WHERE 
                            DATE(a_date) = CURDATE() and t.id='$id' AND DATE(Entered_date) = CURDATE()
                            ) 
                        pc ON pc.Team=t.Team WHERE DATE(c_date) = CURDATE() ;"
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
                        $total_datecount+=$data->total_datecount;
                        $total_datecount1+=$data->total_datecount1;
                        $total_datecount2+=$data->total_datecount2;
                        $total_datecount3+=$data->total_datecount3;
                        $total_datecount4+=$data->total_datecount4;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                <td><a href="date-count7.php?VSL=A-Thilina&VSL1=A-Chanuka&VSL2=B-Thilina&VSL3=B-Chanuka&VSL4=O.Directs&date=<?php echo $date ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount ?></a></td>
                <td><a href="date-count7.php?VSL=A-Thilina&VSL1=A-Chanuka&VSL2=B-Thilina&VSL3=B-Chanuka&VSL4=O.Directs&date=<?php echo $date1 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount1 ?></a></td>
                <td><a href="date-count7.php?VSL=A-Thilina&VSL1=A-Chanuka&VSL2=B-Thilina&VSL3=B-Chanuka&VSL4=O.Directs&date=<?php echo $date2 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount2 ?></a></td>
                <td><a href="date-count7.php?VSL=A-Thilina&VSL1=A-Chanuka&VSL2=B-Thilina&VSL3=B-Chanuka&VSL4=O.Directs&date=<?php echo $date3 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount3 ?></a></td>
                <td><a href="date-count7.php?VSL=A-Thilina&VSL1=A-Chanuka&VSL2=B-Thilina&VSL3=B-Chanuka&VSL4=O.Directs&date=<?php echo $date4 ?>" style="color:black; text-decoration:none;"><?php echo $total_datecount4 ?></a></td>
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td><!---->

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

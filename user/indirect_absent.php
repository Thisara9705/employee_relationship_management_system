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
            <a class="btn btn-primary btn-md w3-right" href="team.php" style="margin:5px;" > Team Wise Cadre Report</a>
            <a class="btn btn-primary btn-md w3-right" href="work_report.php" style="margin:5px;" > Work Reporting Date</a>
            <a class="btn btn-primary btn-md w3-right w3-hide-medium w3-hide-small" href="graph.php" style="margin:5px;" > Line Charts</a>
            
            <a class="btn btn-primary btn-md w3-right w3-hide-medium w3-hide-small" href="https://ts.hrsynergy.online/req_table.php" style="margin:5px;"> Requisitions</a>
        </div>
    </div>
  </div>
</div> 

<div class="w3-row-padding w3-margin-bottom w3-hide-small w3-hide-medium" style="margin-top:90px;">

</div>

<!--###############################################################################################-->
<div class="w3-main w3-hide-small w3-hide-medium" style="margin-top: 0px;">
		<div class="w3-container" style="padding-top: 22px;">
			<div class="w3-row">
				<div class="table-responsive" style="overflow: auto; height: 690px;">
					<table class="table table-hover table-striped w3-tiny display" id="ex1" style="font-weight: 900; margin-top: 0px; width: 100%;">
						<thead style="position: sticky; top:0; z-index:1; "> <!-- fixed-top-->
							<tr style="background:#f3f3f3;">
							<!--	<th style="width:10%">Area</th>	-->
								<th style = "width:10%">Area</th>	
								<th>Internal Cadre Allocation</th>
								<th>Actual Cadre</th>
								<th>ML/PL/SL</th>
								<th>ML Impacted to the cadre</th>
								<th>Actual Cadre After ML</th>
								<th>Possible ETO</th>
								<th>Shortage</th>
								<th>Leaves</th>
								<th>Absents</th>
								<th>Normal Absent</th>
								<th>Covid</th>
								<th class="w3-yellow">Sympthoms</th>
								<th class="w3-red">Positive</th>
	
								<th class="w3-light-green">C1</th>
								<th class="w3-text-red">Total Absent Heads</th>
								<th class="w3-text-green">Total Present Heads</th>

					
								<th>Total Absent %</th>
								<th>Total Absent % with ML</th>
								<th>Total Absent % with Covid</th>
							</tr>
						</thead>

						<tbody>
							
							<!--	Administration-SY	-->

						<?php
							$all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'Administration - SY'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'Administration - SY' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>

            <!--   AQL-SY -->

					<?php
                     $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'AQL - SY'");
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
                                $id1 = $data->id;
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                            DATE(a_date) = CURDATE() and t.id='$id1' AND DATE(Entered_date) = CURDATE()
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'AQL-SY' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>	

            <!--    Autonomation - SY  -->

                        <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'Autonomation - SY'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'Autonomation - SY' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>


              <!--    Bonding - Method Study - SY  -->

                        <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'Bonding - Method Study - SY'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'Bonding - Method Study - SY' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>



              <!--    CAD CAM - SY  -->

                        <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'CAD CAM - SY'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'CAD CAM - SY' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>



              <!--    Cutting - SY  -->

                        <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'Cutting - SY'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'Cutting - SY' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>

            <!-- Elastic Hub - SY   -->

            <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'Elastic Hub - SY'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'Elastic Hub - SY' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>


                          <!--    Elastic Hub - Indirects - SY  -->

                        <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'Elastic Hub Indirects - SY'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'Elastic Hub - Indirects - SY' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>

                         

                          <!--    Embroidery - SY  -->

                        <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'Embroidery - SY'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'Embroidery - SY' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>
          
                        <!--  Fabric Inspection - SY  -->

                        <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'Fabric Inspection - SY'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'Fabric Inspection - SY' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>

                          <!--    Finished Goods Warehouse - SY  -->

                        <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'Finished Goods Warehouse - SY'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'Finished Goods Warehouse - SY' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>

                          <!--   General Maintenance - SY  -->

                        <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'General Maintenance - SY'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'General Maintenance - SY' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>

                          <!--   Human Resources - SY  -->

                        <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'Human Resources - SY'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'Human Resources - SY' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>

                      

                          <!--    Input  -->

                        <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'Input'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'Input' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>

            <!-- Input - Trainee -->

            <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'Input - Trainee'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'Input - Trainee' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>


                          <!--   Laboratory - SY  -->

                        <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'Laboratory - SY'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'Laboratory - SY' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>

                          <!--   Laser Cutting - SY  -->

                        <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'Laser Cutting - SY'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'Laser Cutting - SY' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>

                          <!--   Lean Enterprise - SY  -->

                          <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'Lean Enterprise - SY'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'Lean Enterprise - SY' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>


                          <!--   Method Study - SY  -->

                        <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'Method Study - SY'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'Method Study - SY' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>


                          <!--   Operation - SY  -->

                        <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'Operation - SY'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'Operation - SY' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>

                          <!--   Pad Printing - SY  -->

                        <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'Pad Printing - SY'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'Pad Printing - SY' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>

                          <!--   Printing - SY  -->

                        <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'Printing - SY'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'Printing - SY' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>

                          <!--   Production - General - SY  -->

                        <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'Production - General - SY'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'Production - General - SY' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>


                          <!--   Production Engineering - SY  -->

                        <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'Production Engineering - SY'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'Production Engineering - SY' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>

                          <!--  Quality Assurance - Cutting - SY -->

                        <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'Quality Assurance - Cutting - SY'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'Quality Assurance - Cutting - SY' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>

                          <!-- Quality Assurance - General - SY -->

                        <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'Quality Assurance - General - SY'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'Quality Assurance - General - SY' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>

                          <!-- Raw Material Warehouse - SY -->

                        <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'Raw Material Warehouse - SY'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'Raw Material Warehouse - SY' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>

                          <!-- SAP - SY -->

                        <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'SAP - SY'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'SAP - SY' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>

            <!--Sitting Jumpers - SY -->

            <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'Sitting Jumpers - SY'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'Sitting Jumpers - SY' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>

            <!--  Spot Cleaning - Lab -SY  -->

            <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'Spot Cleaning - Lab - SY'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'Spot Cleaning - Lab - SY' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>

            <!--  Standing Jumpers - SY  -->

            <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'Standing Jumpers - SY'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'Standing Jumpers - SY' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>

                    <!--  Supreme Operators - SY  -->

                    <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'Supreme Operators - SY'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'Supreme Operators - SY' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>

                          <!-- Training Line - SY -->

                        <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'Training Line - SY'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'Training Line - SY' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>

                    <!--  TS - Anuradhi B  -->

                    <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'TS - Anuradhi B'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'TS - Anuradhi B' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>

                    <!--  TS -Ira A  -->

                    <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'TS - Ira A'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'TS - Ira A' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>

            <!--  TS - Kumudu B  -->

            <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'TS - Kumudu B'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'TS - Kumudu B' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>

                    <!--  TS - Maleesha A  -->

                    <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'TS - Maleesha A'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'TS - Maleesha A' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>

                    <!--  TS - Pool A  -->

                    <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'TS - Pool A'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'TS - Pool A' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>

                    <!--  TS - Pool B  -->

                    <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'TS - Pool B'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'TS - Pool B' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>


            <!--  TS - Ranjani B  -->

            <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'TS - Ranjani B'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'TS - Ranjani B' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>

            <!--  TS - Sandamali B  -->

            <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'TS - Sandamali B'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'TS - Sandamali B' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>

                    <!-- Sanjeewani B -->

                    <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'TS - Sanjeewani B'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'TS - Sanjeewani B' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>

                    <!--  TS - Sukitha B  -->

                    <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'TS - Sukitha B'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'TS - Sukitha B' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>


                          <!-- Work Study - SY -->

                        <?php
                            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'Work Study - SY'");
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
                            }
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
                        SUM(pc.symptom_count+pc.positive_count+pc.c1_count) total_covid,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count))/t.AC)*100) total_percentage,
                       
                        t.Team
                        
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
                       
                    }
                
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);

                ?> 
            <tr class="">

                <td><?php echo 'Work Study - SY' ?></td>
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

                
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td>  

            </tr>
            
            <!--=======================================Total-Production-Floor======================================-->
<!--$all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team = 'Method Study - SY'");-->
<?php
            //    $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team IN ('Administration - SY','AQL - SY','Autonomation - SY') AND DATE(c_date) = CURDATE()");
            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Team IN ('Administration - SY','AQL - SY','Autonomation - SY','Bonding - Method Study - SY','CAD CAM - SY','Cutting - SY','Elastic Hub - SY','Elastic Hub Indirects - SY','Embroidery - SY','Fabric Inspection - SY','Finished Goods Warehouse - SY','General Maintenance - SY','Human Resources - SY','Input','Input - Trainee','Laboratory - SY','Laser Cutting - SY','Lean Enterprise - SY','Method Study - SY','Operation - SY','Pad Printing - SY','Printing - SY','Production - General - SY','Production Engineering - SY','Quality Assurance - Cutting - SY','Quality Assurance - General - SY','Raw Material Warehouse - SY','SAP - SY','Sitting Jumpers - SY','Spot Cleaning - Lab - SY','Standing Jumpers - SY','Supreme Operators - SY','Training Line - SY','TS - Anuradhi B','TS - Ira A','TS - Kumudu B','TS - Maleesha A','TS - Pool A','TS - Pool B','TS - Ranjani B','TS - Sandamali B','TS - Sanjeewani B','TS - Sukitha B','Work Study - SY') AND DATE(c_date) = CURDATE()");
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
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                        
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
                                COUNT(case Reason when 'C1' then 1 else null end) c1_count
                                
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
                      
                    }
                }    
           
           $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);
                ?> 
            <tr class='w3-blue'>

                <td><?php echo 'Total' ?></td>
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



<?php include 'theme/foot.php'; ?>

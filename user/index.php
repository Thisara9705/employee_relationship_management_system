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
            <a class="btn btn-primary btn-sm w3-right" href="team.php" style="margin:5px;" > Team Wise Cadre Report</a>
            <a class="btn btn-primary btn-sm w3-right" href="work_report.php" style="margin:5px;" > Work Reporting Date</a>
            <a class="btn btn-primary btn-sm w3-right w3-hide-medium w3-hide-small" href="graph.php" style="margin:5px;" > Line Charts</a>
            
            <a class="btn btn-primary btn-sm w3-right w3-hide-medium w3-hide-small" href="https://ts.hrsynergy.online/req_table.php" style="margin:5px;"> Requisitions</a>
            <a class="btn btn-primary btn-sm w3-right w3-hide-mediium w3-hide-small" href="indirect_absent.php" style="margin:5px;">Indirect Absent</a>
        </div>
    </div>
  </div>
</div> 

<div class="w3-row-padding w3-margin-bottom w3-hide-small w3-hide-medium" style="margin-top:90px;">
<?php 

        
            $all = $db->query("SELECT * FROM planned_cadre ORDER BY id DESC LIMIT 1");
            $data = $all->fetch(PDO::FETCH_ASSOC);

            $p_cadre = $data['total'];

        ?>

    <div class="w3-col" style="width:14.25%">

        <div class="w3-container w3-blue w3-padding-16 w3-animate-zoom">
            <div class="w3-left"><i class="fa fa-users w3-xxlarge"></i></div>
            <div class="w3-right">
                <h4><?php echo $p_cadre ?></h4>
            </div>
            <div class="w3-clear"></div>
            <h5>Planned Cadre <br><br></h5>
        </div>

    </div>
    

<?php
$all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Area IN ('A-Thilina','A-Chanuka','B-Thilina','B-Chanuka') OR Team IN ('Elastic Hub - SY','Supreme Operators - SY','Standing Jumpers - SY') AND DATE(c_date) = CURDATE()");
$fetch = $all->fetchAll(PDO::FETCH_OBJ);
$total_aac=0;
$total_present_head=0;

foreach($fetch as $data){
    $id = $data->id;

    $all = $db->query(
    "SELECT 
        SUM(t.AC) t_ac,
        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))) total_present_head
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
        $total_present_head+=$data->total_present_head;
    }
}    


?> 


        <div  class="w3-col" style="width:14.25%">
       
        <div class="w3-container w3-red w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><i class="fa fa-users w3-xxlarge"></i></div>
        <div class="w3-right">
            <h4>
            <?php echo $total_present_head ?>
            </h4>
            </div>
                <div class="w3-clear"></div>
                <h5>Present Cadre <br><br></h5>
        </div>
       
        </div>
        
        
        <?php
            $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Area IN ('A-Thilina','A-Chanuka','B-Thilina','B-Chanuka')");
            $fetch = $all->fetchAll(PDO::FETCH_OBJ);
            $total_aac=0;
            $total_normal_absent=0;

            foreach($fetch as $data){
                $id = $data->id;

                $all = $db->query(
                "SELECT 
                    SUM(t.AC) t_ac,
                    SUM(t.AC-pc.ml_count) total_aac,
                    SUM(pc.leave_count+pc.absent_count) total_normal_absent
                    FROM team t INNER JOIN 
                        (SELECT e.Team,e.Classification,t.Area,a.Attendance,a.Reason,t.Shift,
                        COUNT(case when Reason IN ('Maternity Leave','Special Leave','Pregnant Leave') then 1 else null end) ml_count, 
                            COUNT(case Reason when 'Leaves' then 1 else null end) leave_count,
                            COUNT(case Reason when 'Absents' then 1 else null end) absent_count
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
                    $total_aac+=$data->total_aac;
                    $total_normal_absent+=$data->total_normal_absent;
                }
            }  
            $total_percentage=round(($total_normal_absent/$total_aac)*100,2);  


            ?>
                <div  class="w3-col" style="width:14.25%">

                        <div class="w3-container w3-2020-mosaic-blue w3-padding-16 w3-animate-zoom">
                            <div class="w3-left"><i class="fa fa-percent w3-xxlarge"></i></div>
                            <div class="w3-right">
                                <h4>
                                <?php echo $total_percentage ?>%
                                </h4>
                            </div>
                            <div class="w3-clear"></div>
                            <h5>Total MO Absent<br><br></h5>
                    </div>

                </div>
                
                 <?php
                $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Area IN ('A-Thilina','A-Chanuka','B-Thilina','B-Chanuka') OR Team IN ('Elastic Hub - SY','Supreme Operators - SY','Standing Jumpers - SY') AND DATE(c_date) = CURDATE()");
                $fetch = $all->fetchAll(PDO::FETCH_OBJ);
                $total_aac=0;
                $total_mli=0;
                $total_normal_absent=0;
         
                foreach($fetch as $data){
                    $id = $data->id;
                    $all = $db->query(
                    "SELECT 
                        SUM(pc.mli_count) mli_count,
                        SUM(t.AC-pc.ml_count) total_aac,
                        SUM(pc.leave_count+pc.absent_count) total_normal_absent
                        FROM team t INNER JOIN 
                            (SELECT e.Team,e.Classification,t.Area,a.Attendance,a.Reason,t.Shift,
                            COUNT(case when Reason IN ('Maternity Leave','Special Leave','Pregnant Leave') then 1 else null end) ml_count, 
                            COUNT(case Reason when 'Maternity Leave' then 1 else null end) mli_count, 
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
                        $total_aac+=$data->total_aac;
                        $total_mli+=$data->mli_count;
                        $total_normal_absent+=$data->total_normal_absent;
        
                    }
                }    
           
               
                $total_percentage2=round((($total_normal_absent+$total_mli)/($total_aac+$total_mli))*100,2);
                
                ?>

        <div  class="w3-col" style="width:14.25%">

        <div class="w3-container w3-2020-chive w3-padding-16 w3-animate-zoom">
            <div class="w3-left"><i class="fa fa-percent w3-xxlarge"></i></div>
            <div class="w3-right">
                <h4>
                <?php echo $total_percentage2 ?>%
                </h4>
            </div>
            <div class="w3-clear"></div>
            <h5>Total MO Absent With ML<br><br></h5>
        </div>

        </div>

                <?php
                $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Area IN ('A-Thilina','A-Chanuka','B-Thilina','B-Chanuka')");
                $fetch = $all->fetchAll(PDO::FETCH_OBJ);
                $total_ac=0;
                $total_absent_head=0;
         
                foreach($fetch as $data){
                    $id = $data->id;
                    $all = $db->query(
                    "SELECT 
                        SUM(t.AC) t_ac,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head
                        FROM team t INNER JOIN 
                            (SELECT e.Team,e.Classification,t.Area,a.Attendance,a.Reason,t.Shift,
                            COUNT(case when Reason IN ('Maternity Leave','Special Leave','Pregnant Leave') then 1 else null end) ml_count, 
                            COUNT(case Reason when 'Maternity Leave' then 1 else null end) mli_count, 
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
                        $total_ac+=$data->t_ac;
                        $total_absent_head+=$data->total_absent_head;
        
                    }
                }    
           
               
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);
                
                ?>

        <div  class="w3-col" style="width:14.25%">

        <div class="w3-container w3-2021-inkwell w3-padding-16 w3-animate-zoom">
            <div class="w3-left"><i class="fa fa-percent w3-xxlarge"></i></div>
            <div class="w3-right">
                <h4>
                <?php echo $total_percentage3 ?>%
                </h4>
            </div>
            <div class="w3-clear"></div>
            <h5>Total MO Absent With Covid</h5>
        </div>

        </div>     

<?php
$all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Area IN ('A-Thilina','A-Chanuka','B-Thilina','B-Chanuka') OR Team IN ('Elastic Hub - SY','Supreme Operators - SY','Standing Jumpers - SY') AND DATE(c_date) = CURDATE()");
$fetch = $all->fetchAll(PDO::FETCH_OBJ);
$total_aac=0;
$total_normal_absent=0;

foreach($fetch as $data){
    $id = $data->id;

    $all = $db->query(
    "SELECT 
        SUM(t.AC) t_ac,
        SUM(t.AC-pc.ml_count) total_aac,
        SUM(pc.leave_count+pc.absent_count) total_normal_absent
        FROM team t INNER JOIN 
            (SELECT e.Team,e.Classification,t.Area,a.Attendance,a.Reason,t.Shift,
            COUNT(case when Reason IN ('Maternity Leave','Special Leave','Pregnant Leave') then 1 else null end) ml_count, 
                COUNT(case Reason when 'Leaves' then 1 else null end) leave_count,
                COUNT(case Reason when 'Absents' then 1 else null end) absent_count
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
        $total_aac+=$data->total_aac;
        $total_normal_absent+=$data->total_normal_absent;
    }
}  
$total_percentage=round(($total_normal_absent/$total_aac)*100,2);  


?>
    <div  class="w3-col" style="width:14.25%">

            <div class="w3-container w3-teal w3-padding-16 w3-animate-zoom">
                <div class="w3-left"><i class="fa fa-percent w3-xxlarge"></i></div>
                <div class="w3-right">
                    <h4>
                    <?php echo $total_percentage ?>%
                    </h4>
                </div>
                <div class="w3-clear"></div>
                <h5>Total Absent <br><br></h5>
        </div>

    </div>

   
    <?php
                $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = CURDATE() AND Area IN ('A-Thilina','A-Chanuka','B-Thilina','B-Chanuka') OR Team IN ('Elastic Hub - SY','Supreme Operators - SY','Standing Jumpers - SY') AND DATE(c_date) = CURDATE()");
                $fetch = $all->fetchAll(PDO::FETCH_OBJ);
                $total_ac=0;
                $total_absent_head=0;
         
                foreach($fetch as $data){
                    $id = $data->id;
                    $all = $db->query(
                    "SELECT 
                        SUM(t.AC) t_ac,
                        SUM(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+pc.c1_count)) total_absent_head
                        FROM team t INNER JOIN 
                            (SELECT e.Team,e.Classification,t.Area,a.Attendance,a.Reason,t.Shift,
                            COUNT(case when Reason IN ('Maternity Leave','Special Leave','Pregnant Leave') then 1 else null end) ml_count, 
                            COUNT(case Reason when 'Maternity Leave' then 1 else null end) mli_count, 
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
                        $total_ac+=$data->t_ac;
                        $total_absent_head+=$data->total_absent_head;
        
                    }
                }    
           
               
                $total_percentage3=round(($total_absent_head/$total_ac)*100,2);
                
                ?>

        <div  class="w3-col" style="width:14.25%">

        <div class="w3-container w3-2017-lapis-blue w3-padding-16 w3-animate-zoom">
            <div class="w3-left"><i class="fa fa-percent w3-xxlarge"></i></div>
            <div class="w3-right">
                <h4>
                <?php echo $total_percentage3 ?>%
                </h4>
            </div>
            <div class="w3-clear"></div>
            <h5>Total Absent With Covid<br><br></h5>
        </div>

        </div> 

        

</div>



<div class="w3-main w3-hide-small w3-hide-medium" style="margin-top:0px;">

    <div class="w3-container" style="padding-top:22px">
    <div class="w3-row">

    <div class="table-responsive" style="overflow: auto; height: 690px;">
        <table class="table table-hover table-striped w3-tiny display" id="exampl" style='font-weight: 900; margin-top:0px; width: 100%;'>
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

                    <th class='w3-text-blue'>Planned Cadre</th>

                    <th class='w3-text-green'>Total Present Heads</th>
                    
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

                $total_planned_cadre=0;

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

                        SUM(t.planned_cadre) total_planned_cadre, 

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

                        $total_planned_cadre+=$data->total_planned_cadre;

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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>

                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>

                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                
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

                $total_planned_cadre=0;

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

                /*        SUM(t.planned_cadre) t_p_cadre,*/
                        SUM(t.planned_cadre) total_planned_cadre,

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

                        $total_planned_cadre+=$data->total_planned_cadre;

                        $total_present_head+=$data->total_present_head;
                        $total_percentage+=$data->total_percentage;
                       

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

                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>

                <td class='w3-text-black'><?php echo $total_present_head ?></td>
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

                $total_planned_cadre=0;

                $total_present_head=0;
                $total_percentage=0;
                
                foreach($fetch as $data){
                    $id = $data->id;
         //           $id = $data->id;
                    

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

                        SUM(t.planned_cadre) total_planned_cadre,

                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                        
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

                        $total_planned_cadre+=$data->total_planned_cadre;

                        $total_present_head+=$data->total_present_head;
                        $total_percentage+=$data->total_percentage;
                       
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

                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>

                <td class='w3-text-black'><?php echo $total_present_head ?></td>
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

                $total_planned_cadre=0;

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

                        SUM(t.planned_cadre) total_planned_cadre,

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

                        $total_planned_cadre+=$data->total_planned_cadre;

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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>

                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>

                <td class='w3-text-black'><?php echo $total_present_head ?></td>
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

                $total_planned_cadre=0;

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

                        SUM(t.planned_cadre) total_planned_cadre,

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

                        $total_planned_cadre+=$data->total_planned_cadre;

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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>

                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>

                <td class='w3-text-black'><?php echo $total_present_head ?></td>
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

                $total_planned_cadre=0;

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

                        SUM(t.planned_cadre) total_planned_cadre,

                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                       
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

                        $total_planned_cadre+=$data->total_planned_cadre;

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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>

                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>

                <td class='w3-text-black'><?php echo $total_present_head ?></td>
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

                $total_planned_cadre=0;

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

                        SUM(t.planned_cadre) total_planned_cadre,

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

                        $total_planned_cadre+=$data->total_planned_cadre;

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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>

                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>

                <td class='w3-text-black'><?php echo $total_present_head ?></td>
               
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

                $total_planned_cadre=0;

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

                        SUM(t.planned_cadre) total_planned_cadre,

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

                        $total_planned_cadre+=$data->total_planned_cadre;

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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>

                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>

                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                
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

                $total_planned_cadre=0;

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

                        SUM(t.planned_cadre) total_planned_cadre,

                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                        
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

                        $total_planned_cadre+=$data->total_planned_cadre;

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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>

                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>
                
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                
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

                $total_planned_cadre=0;

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

                        SUM(t.planned_cadre) total_planned_cadre,

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

                        $total_planned_cadre+=$data->total_planned_cadre;

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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>

                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>

                <td class='w3-text-black'><?php echo $total_present_head ?></td>
               
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

                $total_planned_cadre=0;

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

                        SUM(t.planned_cadre) total_planned_cadre,

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

                        $total_planned_cadre+=$data->total_planned_cadre;

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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>

                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>

                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                
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

                $total_planned_cadre=0;

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

                        SUM(t.planned_cadre) total_planned_cadre,

                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                       
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

                        $total_planned_cadre+=$data->total_planned_cadre;

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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>

                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>

                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                
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

                $total_planned_cadre=0;

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

                        SUM(t.planned_cadre) total_planned_cadre,

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

                        $total_planned_cadre+=$data->total_planned_cadre;

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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>

                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>

                <td class='w3-text-black'><?php echo $total_present_head ?></td>
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

                $total_planned_cadre=0;

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

                        SUM(t.planned_cadre) total_planned_cadre,

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

                        $total_planned_cadre+=$data->total_planned_cadre;

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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>

                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>

                <td class='w3-text-black'><?php echo $total_present_head ?></td>
               
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

                $total_planned_cadre=0;

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

                        SUM(t.planned_cadre) total_planned_cadre,
                        
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

                        $total_planned_cadre+=$data->total_planned_cadre;

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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>

                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>

                <td class='w3-text-black'><?php echo $total_present_head ?></td>
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

                $total_planned_cadre=0;

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

                        SUM(t.planned_cadre) total_planned_cadre,

                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                        
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

                        $total_planned_cadre+=$data->total_planned_cadre;

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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>

                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>

                <td class='w3-text-black'><?php echo $total_present_head ?></td>
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

                $total_planned_cadre=0;

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

                        SUM(t.planned_cadre) total_planned_cadre,

                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                        
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

                        $total_planned_cadre+=$data->total_planned_cadre;

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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>

                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>

                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td><!---->
            </tr>
    
<!--=======================================Dancing-Production-Jumpers======================================-->

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

                $total_planned_cadre=0;

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

                        SUM(t.planned_cadre) total_planned_cadre,
                        
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                        
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

                        $total_planned_cadre+=$data->total_planned_cadre;

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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>

                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>

                <td class='w3-text-black'><?php echo $total_present_head ?></td>
               
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

                $total_planned_cadre=0;

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

                        SUM(t.planned_cadre) total_planned_cadre,

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

                        $total_planned_cadre+=$data->total_planned_cadre;

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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>

                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>

                <td class='w3-text-black'><?php echo $total_present_head ?></td>
              
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

                $total_planned_cadre=0;

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

                        SUM(t.planned_cadre) total_planned_cadre,

                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                       
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

                        $total_planned_cadre+=$data->total_planned_cadre;

                        $total_present_head+=$data->total_present_head;
                        $total_percentage+=$data->total_percentage;
                        
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

                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>

                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                
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

                $total_planned_cadre=0;

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

                        SUM(t.planned_cadre) total_planned_cadre,

                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                       
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

                        $total_planned_cadre+=$data->total_planned_cadre;

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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>

                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>

                <td class='w3-text-black'><?php echo $total_present_head ?></td>
               
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

                $total_planned_cadre=0;

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

                        SUM(t.planned_cadre) total_planned_cadre,

                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                       
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

                        $total_planned_cadre+=$data->total_planned_cadre;

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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>

                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>

                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                
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

                $total_planned_cadre=0;

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

                        SUM(t.planned_cadre) total_planned_cadre,

                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                       
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

                        $total_planned_cadre+=$data->total_planned_cadre;

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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>

                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>

                <td class='w3-text-black'><?php echo $total_present_head ?></td>
               
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

                $total_planned_cadre=0;

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

                        SUM(t.planned_cadre) total_planned_cadre,

                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))) total_present_head,
                        SUM((((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100)) total_percentage,
                       
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

                        $total_planned_cadre+=$data->total_planned_cadre;

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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>

                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>

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


<div class="w3-main w3-hide-large" style="margin-top:60px;">

<div class="w3-container" style="padding-top:22px">
    <div class="w3-row">

    <div class="table-responsive" style="overflow: auto; height: 690px;">
    <table class="table table-hover table-striped w3-tiny display" id="exampl" style='font-weight: 900; margin-top:0px; width: 100%;'>
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
                    <th class='w3-text-blue'>Planned Cadre</th>
                    <th class='w3-text-green'>Total Present Heads</th>
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

                $total_planned_cadre=0;

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

                        SUM(t.planned_cadre) total_planned_cadre, 

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

                        $total_planned_cadre+=$data->total_planned_cadre;

                        $total_present_head+=$data->total_present_head;
                        $total_percentage+=$data->total_percentage;
                        
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

                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>

                <td class='w3-text-black'><?php echo $total_present_head ?></td>
               
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

                $total_planned_cadre=0;

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

                        SUM(t.planned_cadre) total_planned_cadre, 

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

                        $total_planned_cadre+=$data->total_planned_cadre;

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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>

                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>

                <td class='w3-text-black'><?php echo $total_present_head ?></td>
               
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

                $total_planned_cadre=0;

                $total_present_head=0;
                $total_percentage=0;
               
                foreach($fetch as $data){
                    $id = $data->id;
                   // $id = $data->id;
                   
                    

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

                        SUM(t.planned_cadre) total_planned_cadre, 

                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                       
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

                        $total_planned_cadre+=$data->total_planned_cadre;

                        $total_present_head+=$data->total_present_head;
                        $total_percentage+=$data->total_percentage;
                      
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
                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
               
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
                $total_planned_cadre=0;
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
                        SUM(t.planned_cadre) total_planned_cadre, 
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
                        $total_planned_cadre+=$data->total_planned_cadre;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                
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
                $total_planned_cadre=0;
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
                        SUM(t.planned_cadre) total_planned_cadre,
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
                        $total_planned_cadre+=$data->total_planned_cadre;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
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
                $total_planned_cadre=0;
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
                        SUM(t.planned_cadre) total_planned_cadre,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                        
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
                        $total_planned_cadre+=$data->total_planned_cadre;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
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
                $total_planned_cadre=0;
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
                        SUM(t.planned_cadre) total_planned_cadre, 
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
                        $total_planned_cadre+=$data->total_planned_cadre;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td><!---->
            </tr>
            
    <!--======================================Miyuranga-A-Shift======================================-->               
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
                $total_planned_cadre=0;
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
                        SUM(t.planned_cadre) total_planned_cadre, 
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
                        $total_planned_cadre+=$data->total_planned_cadre;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
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
                $total_planned_cadre=0;
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
                        SUM(t.planned_cadre) total_planned_cadre,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                     
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
                        $total_planned_cadre+=$data->total_planned_cadre;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td><!---->
            </tr>

        <!--===================================Saman-B-Shift======================================-->               
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
                $total_planned_cadre=0;
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
                        SUM(t.planned_cadre) total_planned_cadre,
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
                        $total_planned_cadre+=$data->total_planned_cadre;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
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
                $total_planned_cadre=0;
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
                        SUM(t.planned_cadre) total_planned_cadre,
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
                        $total_planned_cadre+=$data->total_planned_cadre;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
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
                $total_planned_cadre=0;
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
                        SUM(t.planned_cadre) total_planned_cadre,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                    
                    
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
                        $total_planned_cadre+=$data->total_planned_cadre;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
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
                $total_planned_cadre=0;
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
                        SUM(t.planned_cadre) total_planned_cadre, 
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
                        $total_planned_cadre+=$data->total_planned_cadre;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
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
                $total_planned_cadre=0;
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
                        SUM(t.planned_cadre) total_planned_cadre,
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
                        $total_planned_cadre+=$data->total_planned_cadre;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
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
                $total_planned_cadre=0;
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
                        SUM(t.planned_cadre) total_planned_cadre,
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
                        $total_planned_cadre+=$data->total_planned_cadre;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
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
                $total_planned_cadre=0;
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
                        SUM(t.planned_cadre) total_planned_cadre,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                     
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
                        $total_planned_cadre+=$data->total_planned_cadre;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td><!---->
            </tr>
        
        <!--======================================Supreme-Operators======================================-->
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
                $total_planned_cadre=0;
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
                        SUM(t.planned_cadre) total_planned_cadre, 
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                       
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
                        $total_planned_cadre+=$data->total_planned_cadre;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td><!---->
            </tr>
            
        <!--==================================Dancing-Production-Jumpers==================================-->

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
                $total_planned_cadre=0;
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
                        SUM(t.planned_cadre) total_planned_cadre,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                       
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
                        $total_planned_cadre+=$data->total_planned_cadre;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td><!---->
            </tr>
            
            <!--================================Total-Production-Floor===================================-->

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
                $total_planned_cadre=0;
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
                        SUM(t.planned_cadre) total_planned_cadre, 
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
                        $total_planned_cadre+=$data->total_planned_cadre;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td><!---->
            </tr>
            
            <!--====================================Sitting-Jumpers======================================-->
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
                $total_planned_cadre=0;
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
                        SUM(t.planned_cadre) total_planned_cadre, 
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                      
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
                        $total_planned_cadre+=$data->total_planned_cadre;
                        $total_present_head+=$data->total_present_head;
                        $total_percentage+=$data->total_percentage;
                      
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
                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td><!---->
            </tr>
            
        <!--========================================AQL-Foldings======================================-->

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
                $total_planned_cadre=0;
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
                        SUM(t.planned_cadre) total_planned_cadre, 
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                      
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
                        $total_planned_cadre+=$data->total_planned_cadre;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td><!---->
            </tr>
            
        <!--===================================Spot-Cleaning======================================-->

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
                $total_planned_cadre=0;
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
                        SUM(t.planned_cadre) total_planned_cadre,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                      
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
                        $total_planned_cadre+=$data->total_planned_cadre;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td><!---->
            </tr>
            
        <!--==================================Total Other Directs===================================-->

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
                $total_planned_cadre=0;
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
                        SUM(t.planned_cadre) total_planned_cadre,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))) total_present_head,
                        SUM(((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100) total_percentage,
                       
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
                        $total_planned_cadre+=$data->total_planned_cadre;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>
                <td class='w3-text-black'><?php echo $total_present_head ?></td>
                <td><?php echo $total_percentage ?>%</td>
                <td><?php echo $total_percentage2 ?>%</td>
                <td><?php echo $total_percentage3 ?>%</td><!---->
            </tr>
            
        <!--======================================Total Directs===============================-->

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
                $total_planned_cadre=0;
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
                        SUM(t.planned_cadre) total_planned_cadre,
                        SUM(AC-(pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))) total_present_head,
                        SUM((((pc.ml_count+(pc.leave_count+pc.absent_count)+(pc.symptom_count+pc.positive_count+c1_count))/t.AC)*100)) total_percentage,
                       
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
                        $total_planned_cadre+=$data->total_planned_cadre;
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
                <td class='w3-text-black'><?php echo $total_absent_head ?></td>
                <td class='w3-test-black'><?php echo $total_planned_cadre ?></td>
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

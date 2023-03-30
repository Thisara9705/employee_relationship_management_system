<?php include '../db/db.php'; ?>
<?php include 'theme/head.php'; ?>
<?php
    $begin = new DateTime('first day of last month', new DateTimeZone('Asia/Colombo'));
    $en   = new DateTime('today', new DateTimeZone('Asia/Colombo'));
    $end = $en->modify('+1 day');
    for($i = $begin; $i < $end; $i->modify('+1 day') ){

        $date = $i->format("Y-m-d");
        $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = '$date' AND VSL='Prasanna'");
        $fetch = $all->fetchAll(PDO::FETCH_OBJ);
        $total_ac=0;
        $total_mli=0;
        $total_aac=0;
        $total_normal_absent=0;
        $total_absent_head=0;


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
                    DATE(a_date) = '$date' and t.id='$id' AND DATE(Entered_date) = '$date'
                    ) 
                pc ON pc.Team=t.Team WHERE DATE(c_date) = '$date' ;"
            );
            $fetch = $all->fetchAll(PDO::FETCH_OBJ);
            foreach($fetch as $data){
                $total_aac+=$data->total_aac;
                $total_normal_absent+=$data->total_normal_absent;
                
            }
            
        }
        if ($total_aac == 0 AND $total_ac == 0 AND $total_mli == 0 ) {
            continue;
        }else{
        $total_percentage=round(($total_normal_absent/$total_aac)*100,2);

        $cons = 5;
        }
        $total_percentages[] =  $total_percentage;
        $dates[] = $date;
        $conss[] = $cons;
        
    }
?> 
<?php
    $begin = new DateTime('first day of last month', new DateTimeZone('Asia/Colombo'));
    $en   = new DateTime('today', new DateTimeZone('Asia/Colombo'));
    $end = $en->modify('+1 day');
    for($i = $begin; $i < $end; $i->modify('+1 day') ){

        $date = $i->format("Y-m-d");
        $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = '$date' AND VSL='Viraj'");
        $fetch = $all->fetchAll(PDO::FETCH_OBJ);
        $total_ac=0;
        $total_mli=0;
        $total_aac=0;
        $total_normal_absent=0;
        $total_absent_head=0;


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
                    DATE(a_date) = '$date' and t.id='$id' AND DATE(Entered_date) = '$date'
                    ) 
                pc ON pc.Team=t.Team WHERE DATE(c_date) = '$date' ;"
            );
            $fetch = $all->fetchAll(PDO::FETCH_OBJ);
            foreach($fetch as $data){
                $total_aac+=$data->total_aac;
                $total_normal_absent+=$data->total_normal_absent;
                
            }
            
        }
        if ($total_aac == 0 AND $total_ac == 0 AND $total_mli == 0 ) {
            continue;
        }else{
        $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
        }
        $total_percentage1s[] =  $total_percentage;
    }
?> 
<?php
    $begin = new DateTime('first day of last month', new DateTimeZone('Asia/Colombo'));
    $en   = new DateTime('today', new DateTimeZone('Asia/Colombo'));
    $end = $en->modify('+1 day');
    for($i = $begin; $i < $end; $i->modify('+1 day') ){

        $date = $i->format("Y-m-d");
        $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = '$date' AND VSL='Naveen'");
        $fetch = $all->fetchAll(PDO::FETCH_OBJ);
        $total_ac=0;
        $total_mli=0;
        $total_aac=0;
        $total_normal_absent=0;
        $total_absent_head=0;


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
                    DATE(a_date) = '$date' and t.id='$id' AND DATE(Entered_date) = '$date'
                    ) 
                pc ON pc.Team=t.Team WHERE DATE(c_date) = '$date' ;"
            );
            $fetch = $all->fetchAll(PDO::FETCH_OBJ);
            foreach($fetch as $data){
                $total_aac+=$data->total_aac;
                $total_normal_absent+=$data->total_normal_absent;
                
            }
            
        }
        if ($total_aac == 0 AND $total_ac == 0 AND $total_mli == 0 ) {
            continue;
        }else{
        $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
        }
        $total_percentage2s[] =  $total_percentage;
    }
?> 

<?php
    $begin = new DateTime('first day of last month', new DateTimeZone('Asia/Colombo'));
    $en   = new DateTime('today', new DateTimeZone('Asia/Colombo'));
    $end = $en->modify('+1 day');
    for($i = $begin; $i < $end; $i->modify('+1 day') ){

        $date = $i->format("Y-m-d");
        $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = '$date' AND VSL='Kasun'");
        $fetch = $all->fetchAll(PDO::FETCH_OBJ);
        $total_ac=0;
        $total_mli=0;
        $total_aac=0;
        $total_normal_absent=0;
        $total_absent_head=0;


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
                    DATE(a_date) = '$date' and t.id='$id' AND DATE(Entered_date) = '$date'
                    ) 
                pc ON pc.Team=t.Team WHERE DATE(c_date) = '$date' ;"
            );
            $fetch = $all->fetchAll(PDO::FETCH_OBJ);
            foreach($fetch as $data){
                $total_aac+=$data->total_aac;
                $total_normal_absent+=$data->total_normal_absent;
                
            }
            
        }
        if ($total_aac == 0 AND $total_ac == 0 AND $total_mli == 0 ) {
            continue;
        }else{
        $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
        }
        $total_percentage3s[] =  $total_percentage;
    }
?>

<?php
    $begin = new DateTime('first day of last month', new DateTimeZone('Asia/Colombo'));
    $en   = new DateTime('today', new DateTimeZone('Asia/Colombo'));
    $end = $en->modify('+1 day');
    for($i = $begin; $i < $end; $i->modify('+1 day') ){

        $date = $i->format("Y-m-d");
        $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = '$date' AND VSL='Miyuranga'");
        $fetch = $all->fetchAll(PDO::FETCH_OBJ);
        $total_ac=0;
        $total_mli=0;
        $total_aac=0;
        $total_normal_absent=0;
        $total_absent_head=0;


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
                    DATE(a_date) = '$date' and t.id='$id' AND DATE(Entered_date) = '$date'
                    ) 
                pc ON pc.Team=t.Team WHERE DATE(c_date) = '$date' ;"
            );
            $fetch = $all->fetchAll(PDO::FETCH_OBJ);
            foreach($fetch as $data){
                $total_aac+=$data->total_aac;
                $total_normal_absent+=$data->total_normal_absent;
                
            }
            
        }
        if ($total_aac == 0 AND $total_ac == 0 AND $total_mli == 0 ) {
            continue;
        }else{
        $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
        }
        $total_percentage4s[] =  $total_percentage;
    }
?>

<?php
    $begin = new DateTime('first day of last month', new DateTimeZone('Asia/Colombo'));
    $en   = new DateTime('today', new DateTimeZone('Asia/Colombo'));
    $end = $en->modify('+1 day');
    for($i = $begin; $i < $end; $i->modify('+1 day') ){

        $date = $i->format("Y-m-d");
        $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = '$date' AND VSL='Pradeep'");
        $fetch = $all->fetchAll(PDO::FETCH_OBJ);
        $total_ac=0;
        $total_mli=0;
        $total_aac=0;
        $total_normal_absent=0;
        $total_absent_head=0;


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
                    DATE(a_date) = '$date' and t.id='$id' AND DATE(Entered_date) = '$date'
                    ) 
                pc ON pc.Team=t.Team WHERE DATE(c_date) = '$date' ;"
            );
            $fetch = $all->fetchAll(PDO::FETCH_OBJ);
            foreach($fetch as $data){
                $total_aac+=$data->total_aac;
                $total_normal_absent+=$data->total_normal_absent;
                
            }
            
        }
        if ($total_aac == 0 AND $total_ac == 0 AND $total_mli == 0 ) {
            continue;
        }else{
        $total_percentage=round(($total_normal_absent/$total_aac)*100,2);
        }
        $total_percentage5s[] =  $total_percentage;
    }
?>
<div class="w3-bar w3-top w3-large w3-mobile" style="z-index:4; background-color:#294257; color:white;">
  <div class="w3-row">
    <div class="w3-col" style="width:45%">
      <h4><span class="w3-bar-item w3-hide-large w3-hide-medium w3-left"><a href="index.php" style="color:white;"><i class="fa fa-angle-left" style="font-weight:1000;"></i></a>&nbsp; &nbsp;HR Digitization</span></h4>
      <h4><span class="w3-bar-item w3-hide-large w3-hide-small w3-left"><a href="index.php" style="color:white;"><i class="fa fa-angle-left" style="font-weight:1000;"></i></a>&nbsp; &nbsp;Synergy HR Digitization</span></h4>
      <h3><span class="w3-bar-item w3-hide-small w3-hide-medium w3-left"><a href="index.php" style="color:white;"><i class="fa fa-angle-left" style="font-weight:1000;"></i></a>&nbsp; &nbsp;Synergy HR Digitization</span></h3>
    </div>
    <div class="w3-col" style="width:55%">
       
    </div>
  </div>
</div>

<div class="w3-row-padding w3-margin-bottom" style="margin-top:90px;">
    <div class="w3-col s2">
        <a href="graph.php" style="text-decoration:none;">
        <div class="w3-container w3-blue w3-padding-16 w3-animate-zoom">
            <div class="w3-left"><i class="fa fa-line-chart w3-xlarge" aria-hidden="true"></i></div>
            <div class="w3-right"><h5>Cadre Status</h5></div>
        </div>
        </a>
    </div>
    <div class="w3-col s2">
        <a href="graph2.php" style="text-decoration:none;">
        <div class="w3-container w3-red w3-padding-16 w3-animate-zoom">
            <div class="w3-left"><i class="fa fa-line-chart w3-xlarge" aria-hidden="true"></i></div>
            <div class="w3-right"><h5> Reason Wise Absent</h5></div>
        </div>
        </a>
    </div>
    <div class="w3-col s2">
        <a href="graph3.php" style="text-decoration:none;">
        <div class="w3-container w3-green w3-padding-16 w3-animate-zoom">
            <div class="w3-left"><i class="fa fa-line-chart w3-xlarge" aria-hidden="true"></i></div>
            <div class="w3-right"><h5> Absenteeism Summary</h5></div>
        </div>
        </a>
    </div>
</div>

<div class="w3-main" style="margin-top:50px;">
    <div id="myPlot2" style="width:100%; height:100%;"></div>
</div>


<script>
var yArray1 = <?php echo json_encode($total_percentages); ?>;
var yArray2 = <?php echo json_encode($total_percentage1s); ?>;
var yArray3 = <?php echo json_encode($total_percentage2s); ?>;
var yArray4 = <?php echo json_encode($total_percentage3s); ?>;
var yArray5 = <?php echo json_encode($total_percentage4s); ?>;
var yArray6 = <?php echo json_encode($total_percentage5s); ?>;
var yArray7= <?php echo json_encode($conss); ?>;
var xArray = <?php echo json_encode($dates); ?>;


var trace1 = {
  x: xArray,
  y: yArray1,
  mode: 'lines+markers',
  type: 'scatter',

  name: 'Naveen A Shift %'
};

var trace2 = {
  x: xArray,
  y: yArray2,
  mode: 'lines+markers',
  type: 'scatter',

  name: 'Miyuranga A Shift %'
};
var trace3 = {
  x: xArray,
  y: yArray3,
  mode: 'lines+markers',
  type: 'scatter',

  name: 'Saman A Shift %'
  
};
var trace4 = {
  x: xArray,
  y: yArray4,
  mode: 'lines+markers',
  type: 'scatter',

  name: 'Kasun B Shift %'
  
};
var trace5 = {
  x: xArray,
  y: yArray5,
  mode: 'lines+markers',
  type: 'scatter',

  name: 'Sanjeewa B Shift %'
  
};
var trace6 = {
  x: xArray,
  y: yArray6,
  mode: 'lines+markers',
  type: 'scatter',

  name: 'Pradeep B Shift %'
  
};
var trace7 = {
  x: xArray,
  y: yArray7,
  mode: 'lines+markers',
  type: 'scatter',

  name: 'Target Absenteeism %'
  
};

var data = [trace1, trace2,trace3,trace4,trace5,trace6,trace7];

// Define Layout
var layout = {
  xaxis: {range: ['xArray[0]', 'xArray[xArray.length-1]'], type: 'date', title: "Date"},
  yaxis: {title: "Absenteeism %"}, 
  font: {
    family: 'Poppins',
    size: 14,
    color: '#000000'
  },
  title: "Daily Absenteeism Summary VSL Vice"
};

// Display using Plotly
Plotly.newPlot("myPlot2", data, layout);

</script>


<?php include 'theme/foot.php'; ?>
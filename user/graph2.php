<?php include '../db/db.php'; ?>
<?php include 'theme/head.php'; ?>
<?php
    $begin = new DateTime('first day of last month', new DateTimeZone('Asia/Colombo'));
    $en   = new DateTime('today', new DateTimeZone('Asia/Colombo'));
    $end = $en->modify('+1 day');

    for($i = $begin; $i < $end; $i->modify('+1 day') ){

        $date = $i->format("Y-m-d");
        $all = $db->query("SELECT id FROM team WHERE DATE(c_date) = '$date' AND Area IN ('A-Thilina','A-Chanuka','B-Thilina','B-Chanuka') OR Team IN ('Elastic Hub - SY','Supreme Operators - SY','Standing Jumpers - SY') AND DATE(c_date) = '$date'");
        $fetch = $all->fetchAll(PDO::FETCH_OBJ);
        $total_symptom=0;
        $total_positive=0;
        $total_c1=0;
        $total_leave=0;
        $total_absent=0;
        $total_ml=0;         

        foreach($fetch as $data){
            $id = $data->id;

            $all = $db->query(
            "SELECT 
                SUM(pc.ml_count) ml_count,
                SUM(pc.leave_count) total_leave,
                SUM(pc.absent_count) total_absent,
                SUM(pc.symptom_count) total_symptom,
                SUM(pc.positive_count) total_positive,
                SUM(pc.c1_count) total_c1
                FROM team t INNER JOIN 
                    (SELECT e.Team,e.Classification,t.Area,a.Attendance,a.Reason,t.Shift,
                        COUNT(case when Reason IN ('Maternity Leave','Special Leave','Pregnant Leave') then 1 else null end) ml_count, 
                        COUNT(case Reason when 'Leaves' then 1 else null end) leave_count,
                        COUNT(case Reason when 'Absents' then 1 else null end) absent_count,
                        COUNT(case Reason when 'Symptoms' then 1 else null end) symptom_count,
                        COUNT(case Reason when 'Positive' then 1 else null end) positive_count,
                        COUNT(case Reason when 'C1' then 1 else null end) c1_count 
                    FROM emp e 
                    INNER JOIN team t ON e.Team=t.Team 
                    INNER JOIN attendance a ON e.PN=a.PN 
                    WHERE 
                    DATE(a_date) = '$date ' and t.id='$id' AND DATE(Entered_date) = '$date'
                    ) 
                pc ON pc.Team=t.Team WHERE DATE(c_date) = '$date' ;"
            );
            $fetch = $all->fetchAll(PDO::FETCH_OBJ);
            foreach($fetch as $data){
                
                    $total_ml+=$data->ml_count;
                    $total_leave+=$data->total_leave;
                    $total_absent+=$data->total_absent;
                    $total_symptom+=$data->total_symptom;
                    $total_positive+=$data->total_positive;
                    $total_c1+=$data->total_c1;
                
            }
            
        }
        if ($total_symptom == 0 AND $total_positive == 0 AND $total_c1 == 0 AND $total_ml == 0 AND $total_leave == 0 AND $total_absent == 0 ) {
            continue;
        }else{
            $total_mls[] =  $total_ml;
            $total_absents[] =  $total_absent;
            $total_leaves[] =  $total_leave;
            $total_symptoms[] =  $total_symptom;
            $total_c1s[] =  $total_c1;
            $total_positives[] =  $total_positive;
            $dates[] = $date;
        }
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
        <a href="graph3.php" style="text-decoration:none;">
            <div class="w3-container w3-blue w3-padding-16 w3-animate-zoom">
                <div class="w3-left"><i class="fa fa-line-chart w3-xlarge" aria-hidden="true"></i></div>
                <div class="w3-right"><h5>Absenteeism Summary</h5></div>
            </div>
        </a>
    </div>
    <div class="w3-col s2">
        <a href="graph.php" style="text-decoration:none;">
        <div class="w3-container w3-red w3-padding-16 w3-animate-zoom">
            <div class="w3-left"><i class="fa fa-line-chart w3-xlarge" aria-hidden="true"></i></div>
            <div class="w3-right"><h5>Cadre Status</h5></div>
        </div>
        </a>
    </div>
    <div class="w3-col s2">
        <a href="graph4.php" style="text-decoration:none;">
        <div class="w3-container w3-green w3-padding-16 w3-animate-zoom">
            <div class="w3-left"><i class="fa fa-line-chart w3-xlarge" aria-hidden="true"></i></div>
            <div class="w3-right"><h5> VSL Vice Absenteeism</h5></div>
        </div>
        </a>
    </div>
</div>

<div class="w3-main" style="margin-top:50px;">
    <div id="myPlot" style="width:100%; height:100%;"></div>
</div>


<script>
var yArray1 = <?php echo json_encode($total_mls); ?>;
var yArray2 = <?php echo json_encode($total_leaves); ?>;
var yArray3 = <?php echo json_encode($total_absents); ?>;
var yArray4 = <?php echo json_encode($total_c1s); ?>;
var yArray5 = <?php echo json_encode($total_positives); ?>;
var yArray6 = <?php echo json_encode($total_symptoms); ?>;
var xArray = <?php echo json_encode($dates); ?>;

var trace1 = {
  x: xArray,
  y: yArray1,
  mode: 'lines+markers',
  type: 'scatter',

  name: 'ML/PL/SL'
};

var trace2 = {
  x: xArray,
  y: yArray2,
  mode: 'lines+markers',
  type: 'scatter',

  name: 'Leaves'
};
var trace3 = {
  x: xArray,
  y: yArray3,
  mode: 'lines+markers',
  type: 'scatter',

  name: 'Absents'
};
var trace4 = {
  x: xArray,
  y: yArray4,
  mode: 'lines+markers',
  type: 'scatter',

  name: 'C1'
};
var trace5 = {
  x: xArray,
  y: yArray5,
  mode: 'lines+markers',
  type: 'scatter',

  name: 'Positive'
};
var trace6 = {
  x: xArray,
  y: yArray6,
  mode: 'lines+markers',
  type: 'scatter',

  name: 'Symptoms'
};
var data = [trace1, trace2,trace3, trace4,trace5, trace6];

var layout = {
  xaxis: {range: ['xArray[0]', 'xArray[xArray.length-1]'], type: 'date', title: "Date"},
  yaxis: {title: "Count"},
  font: {
    family: 'Poppins',
    size: 14,
    color: '#000000'
  },
  title: "Reason Wise Daily Absenteeism"
};

Plotly.newPlot("myPlot", data, layout);

</script>

<?php include 'theme/foot.php'; ?>
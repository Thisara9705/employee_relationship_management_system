<?php ob_start(); ?>
<!DOCTYPE html>
<html>

<head>

  <script type="text/javascript">
    function myAccFunc() {
      var x = document.getElementById("demoAcc");
      if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
        x.previousElementSibling.className += " w3-blue";
      } else {
        x.className = x.className.replace(" w3-show", "");
        x.previousElementSibling.className =
          x.previousElementSibling.className.replace(" w3-green", "");
      }
    }

    function myAccFunc1() {
      var x = document.getElementById("demoAcc1");
      if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
        x.previousElementSibling.className += " w3-blue";
      } else {
        x.className = x.className.replace(" w3-show", "");
        x.previousElementSibling.className =
          x.previousElementSibling.className.replace(" w3-green", "");
      }
    }

    function myAccFunc2() {
      var x = document.getElementById("demoAcc2");
      if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
        x.previousElementSibling.className += " w3-blue";
      } else {
        x.className = x.className.replace(" w3-show", "");
        x.previousElementSibling.className =
          x.previousElementSibling.className.replace(" w3-green", "");
      }
    }

    function myAccFunc3() {
      var x = document.getElementById("demoAcc3");
      if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
        x.previousElementSibling.className += " w3-blue";
      } else {
        x.className = x.className.replace(" w3-show", "");
        x.previousElementSibling.className =
          x.previousElementSibling.className.replace(" w3-green", "");
      }
    }
  </script>
</head>

<body>
  <div class="w3-bar w3-top w3-large w3-mobile" style="z-index:4; background-color:#294257; color:white;">
    <div class="w3-row">
      <div class="w3-col" style="width:30%">
        <button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey" onclick="w3_open();"><i class="fa fa-bars"></i></button>
        <h3><span class="w3-bar-item w3-hide-small w3-left">HR Digitization</span></h3>
      </div>
      <div class="w3-col" style="width:70%">
        <span class="w3-bar-item w3-right w3-hide-small">Active Talents Monitoring ER Application</span>
        <span class="w3-bar-item w3-right w3-hide-large">ER Application</span>
      </div>
    </div>
  </div>

  <!-- Sidebar/menu -->
  <nav class="w3-sidebar w3-collapse w3-white " style="z-index:3;width:250px;" id="mySidebar"><br>
    <hr>
    <!-- <div class="w3-container">
    <h4>Menu</h4>
  </div>-->


    <div class="w3-sidebar" style="width:250px;">

      <a href="dashboard.php" class="w3-bar-item w3-button" style="font-size:14px; padding-top:5px; text-decoration:none; color:black;"><i class="fa fa-dashboard"></i> Dashboard</a>

      <a href="fact01sa.php?s=A&area=A-Thilina" class="w3-bar-item w3-button " style="font-size:14px; padding-top:5px; text-decoration:none; color:black;"><i class="fa fa-building"></i> Factory 01 Shift A</a>

      <a href="fact02sa.php?s=A&area=A-Chanuka" class="w3-bar-item w3-button " style="font-size:14px; padding-top:5px; text-decoration:none; color:black;"><i class="fa fa-building"></i> Factory 02 Shift A</a><br>

      <a href="fact01sb.php?s=B&area=B-Thilina" class="w3-bar-item w3-button " style="font-size:14px; padding-top:5px; text-decoration:none; color:black;"><i class="fa fa-building"></i> Factory 01 Shift B</a><br>

      <a href="fact02sb.php?s=B&area=B-Chanuka" class="w3-bar-item w3-button " style="font-size:14px; padding-top:5px; text-decoration:none; color:black;"><i class="fa fa-building"></i> Factory 02 Shift B</a><br>

      <a href="departments.php" class="w3-bar-item w3-button " style="font-size:14px; padding-top:5px; text-decoration:none; color:black;"><i class="fa fa-bookmark"></i> Departments</a><br>

      <a href="odirects.php" class="w3-bar-item w3-button " style="font-size:14px; padding-top:5px; text-decoration:none; color:black;"><i class="fa fa-bookmark"></i> Other Directs</a><br>

      <a href="classification.php" class="w3-bar-item w3-button " style="font-size:14px; padding-top:5px; text-decoration:none; color:black;"><i class="fa fa-star"></i> Classification Vice</a><br>

      <a href="reason_vice.php" class="w3-bar-item w3-button " style="font-size:14px; padding-top:5px; text-decoration:none; color:black;"><i class=" fa fa-stethoscope"></i> Reason Vice</a><br>

      <a href="service.php" class="w3-bar-item w3-button " style="font-size:14px; padding-top:5px; text-decoration:none; color:black;"><i class="fa fa-calendar"></i> Service Duration Vice</a><br>

      <a href="details13.php" class="w3-bar-item w3-button " style="font-size:14px; padding-top:5px; text-decoration:none; color:black;"><i class="fa fa-exclamation-triangle"></i> Possible ETO List</a><br>

      <a href="eto_analysis.php" class="w3-bar-item w3-button " style="font-size:14px; padding-top:5px; text-decoration:none; color:black;"><i class="fa fa-exclamation-triangle"></i> ETO Analysis</a><br>

      <a href="carder.php" class="w3-bar-item w3-button " style="font-size:14px; padding-top:5px; text-decoration:none; color:black;"><i class="fa fa-bar-chart"></i> Cadre Report</a><br>

      <a href="oldcarder.php" class="w3-bar-item w3-button " style="font-size:14px; padding-top:5px; text-decoration:none; color:black;"><i class="fa fa-bar-chart"></i> Old Cadre Reports</a><br>

      <a href="add_emp.php" class="w3-bar-item w3-button " style="font-size:14px; padding-top:5px; text-decoration:none; color:black;"><i class="fa fa-users"></i> Add Employee</a><br>

      <a href="add_acarder.php" class="w3-bar-item w3-button " style="font-size:14px; padding-top:5px; text-decoration:none; color:black;"><i class="fa fa-line-chart "></i> Add Actual Cadre</a><br>

      <a href="carder_status.php" class="w3-bar-item w3-button " style="font-size:14px; padding-top:5px; text-decoration:none; color:black;"><i class="fa fa-user-plus "></i> Cadre Status</a><br>

      <a href="attendance_report.php" class="w3-bar-item w3-button " style="font-size:14px; padding-top:5px; text-decoration:none; color:black;"><i class="fa fa-calendar "></i> Attendance Report</a><br>

      <a href="actual_cadre_report.php" class="w3-bar-item w3-button " style="font-size:14px; padding-top:5px; text-decoration:none; color:black;"><i class="fa fa-calendar "></i> Actual Cadre Report</a><br>

      <a href="emp_cadre_report.php" class="w3-bar-item w3-button " style="font-size:14px; padding-top:5px; text-decoration:none; color:black;"><i class="fa fa-calendar "></i> Employee Cadre Report</a><br>

      <a href="logout.php" class="w3-bar-item w3-button w3-padding"><i class="fa fa-power-off fa-fw"></i> Log out</a><br><br>

    </div>
  </nav>



  <!-- Overlay effect when opening sidebar on small screens -->
  <div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

</body>

</html>
<?php include '../db/db.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:250px;margin-top:43px;">

<p class="text-center w3-hide-large" style="padding-top:30px;"></p>
<!-- Header -->
    <header class="w3-container w3-hide-small" style="padding-top:22px;">
        <h3><b><i class="fa fa-user-plus"></i> Carder Status</b></h3>
    </header>

<p class="text-center" style="margin-top: 3%"></p>

<div class="w3-row-padding w3-margin-bottom">

        <?php

        $all = $db->query("SELECT e.Team,t.Area,COUNT(e.PN) a_count FROM emp e INNER JOIN team t ON e.Team=t.Team WHERE DATE(c_date) = CURDATE() AND DATE(Entered_date) = CURDATE()");
        $fetch = $all->fetchAll(PDO::FETCH_OBJ);
        foreach($fetch as $data){ 

        ?>

        <div class=" w3-half">
        <a href="details9.php" style="text-decoration:none; color:white;">    
        <div class="w3-container w3-deep-purple w3-margin-16 w3-padding-16 w3-animate-zoom" >
                <div class="w3-left"><i class="fa fa-users w3-xxxlarge"></i></div>
        <div class="w3-right">
            <h3>
                <?php echo $data->a_count ?>
            </h3>
            </div>
                <div class="w3-clear"></div>
                <h4>Total Heads</h4>
            </div>
        </a>    
        </div>
        <?php 
        }
        ?>
        <div class=" w3-half">
            <a href="details21.php" style="text-decoration:none; color:white;">
            <div class="w3-container w3-2017-lapis-blue w3-margin-16 w3-padding-16 w3-animate-zoom" >
                <div class="w3-left"><i class="fa fa-users w3-xxxlarge"></i></div>
            <div class="w3-right">
            <h3>
            <i class="fa fa-angle-right w3-xlarge"></i>
            </h3>
            </div>
                <div class="w3-clear"></div>
                <h4>All Present and Absent Members</h4>
                
            </div>
            </a>
        </div>

</div>

<div class="w3-row-padding w3-margin-bottom">
    
        <?php

        $all = $db->query("SELECT e.Team,t.Area,t.Shift,COUNT(DISTINCT e.PN) a_count FROM emp e INNER JOIN team t ON e.Team=t.Team WHERE Area='A-Chanuka' AND Shift='A' AND DATE(Entered_date) = CURDATE();");
        $fetch = $all->fetchAll(PDO::FETCH_OBJ);
        foreach($fetch as $data){ 

        ?>

        <div class=" w3-third">
        <a href="carder_module.php?area=A-Chanuka&shift=A" style="text-decoration:none; color:white;">    
        <div class="w3-container w3-red w3-margin-16 w3-padding-16 w3-animate-zoom " >
                <div class="w3-left"><i class="fa fa-building w3-xxxlarge"></i></div>
        <div class="w3-right">
            <h3>
            <?php echo $data->a_count ?>
            </h3>
            </div>
                <div class="w3-clear"></div>
                <h4>Factory 01 Shift A Total Count</h4>
            </div>
        </a>    
        </div>
        <?php
        }
        ?>

        <?php

        $all = $db->query("SELECT e.Team,t.Area,t.Shift,COUNT(DISTINCT e.PN) a_count FROM emp e INNER JOIN team t ON e.Team=t.Team WHERE Area='A-Thilina' AND Shift='A' AND DATE(Entered_date) = CURDATE();");
        $fetch = $all->fetchAll(PDO::FETCH_OBJ);
        foreach($fetch as $data){ 

        ?>
    <div class="w3-third">
        <a href="carder_module.php?area=A-Thilina&shift=A" style="text-decoration:none; color:white;">
        <div class="w3-container w3-blue w3-padding-16 w3-animate-zoom">
            <div class="w3-left"><i class="fa fa-building w3-xxxlarge"></i></div>
            <div class="w3-right">
                <h3>
                <?php echo $data->a_count ?>
                </h3>
            </div>
            <div class="w3-clear"></div>
            <h4>Factory 02 Shift A Total Count</h4>
        </div>
        </a>
    </div>
    <?php
        }
    ?>

    <?php

        $all = $db->query("SELECT e.Team,t.Area,t.Shift,COUNT(DISTINCT e.PN) a_count FROM emp e INNER JOIN team t ON e.Team=t.Team WHERE Area='B-Chanuka' AND Shift='B' AND DATE(Entered_date) = CURDATE();");
        $fetch = $all->fetchAll(PDO::FETCH_OBJ);
        foreach($fetch as $data){ 

    ?>
    <div class="w3-third">
        <a href="carder_module.php?area=B-Chanuka&shift=B" style="text-decoration:none; color:white;">
            <div class="w3-container w3-teal w3-padding-16 w3-animate-zoom">
                <div class="w3-left"><i class="fa fa-building w3-xxxlarge"></i></div>
                <div class="w3-right">
                    <h3>
                    <?php echo $data->a_count ?></i>
                    </h3>
                </div>
                <div class="w3-clear"></div>
                <h4><h4>Factory 01 Shift B Total Count</h4></h4>
        </div>
        </a>
    </div>
    <?php
        }
    ?>
</div>   
<div class="w3-row-padding w3-margin-bottom">
        <?php

        $all = $db->query("SELECT e.Team,t.Area,t.Shift,COUNT(DISTINCT e.PN) a_count FROM emp e INNER JOIN team t ON e.Team=t.Team WHERE Area='B-Thilina' AND Shift='B' AND DATE(Entered_date) = CURDATE();");
        $fetch = $all->fetchAll(PDO::FETCH_OBJ);
        foreach($fetch as $data){ 

        ?>     
        <div class="w3-third">
            <a href="carder_module.php?area=B-Thilina&shift=B" style="text-decoration:none; color:white;">
                <div class="w3-container w3-orange w3-text-white w3-padding-16 w3-animate-zoom">
                    <div class="w3-left"><i class="fa fa-building w3-xxxlarge"></i></div>
                    <div class="w3-right">
                        <h3>
                        <?php echo $data->a_count ?>
                        </h3>
                    </div>
                    <div class="w3-clear"></div>
                    <h4><h4>Factory 02 Shift B Total Count</h4> </h4>
                </div>
            </a>    
                </div>
        <?php
            }
        ?>                

        <?php

        $all = $db->query("SELECT e.Team,t.Area,t.Shift,COUNT(DISTINCT e.PN) a_count FROM emp e INNER JOIN team t ON e.Team=t.Team WHERE Area='Departments' AND DATE(Entered_date) = CURDATE();");
        $fetch = $all->fetchAll(PDO::FETCH_OBJ);
        foreach($fetch as $data){ 

        ?> 
        <div class="w3-third">
            <a href="carder_module2.php?area=Departments" style="text-decoration:none; color:white;">
            <div class="w3-container w3-yellow  w3-text-white w3-padding-16 w3-animate-zoom " >
                    <div class="w3-left"><i class="fa fa-bookmark w3-xxxlarge"></i></div>
            <div class="w3-right">
                <h3>
                <?php echo $data->a_count ?>
                </h3>
                </div>
                    <div class="w3-clear"></div>
                    <h4>Departments Total Count</h4>
                </div>
            </a>    
            </div>
        <?php
            }
        ?>

        <?php

        $all = $db->query("SELECT e.Team,t.Area,t.Shift,COUNT(DISTINCT e.PN) a_count FROM emp e INNER JOIN team t ON e.Team=t.Team WHERE Area='O.Directs' AND DATE(Entered_date) = CURDATE();");
        $fetch = $all->fetchAll(PDO::FETCH_OBJ);
        foreach($fetch as $data){ 

        ?>
        <div class="w3-third" >
            <a href="carder_module2.php?area=O.Directs" style="text-decoration:none; color:white;">
            <div class="w3-container w3-pink w3-text-white w3-padding-16 w3-animate-zoom">
                    <div class="w3-left"><i class="fa fa-bookmark w3-xxxlarge"></i></div>
                        <div class="w3-right">
                        <h3>
                        <?php echo $data->a_count ?>
                        </h3>
                    </div>
                    <div class="w3-clear"></div>
                <h4>Other Directs Total Count</h4>
            </div>
            </a>
        </div>
        <?php
            }
        ?>


</div>
<?php include 'theme/foot.php'; ?>


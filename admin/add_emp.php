<?php include '../db/db.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:250px;margin-top:43px;">

<!-- Header -->
<header class="w3-container" style="padding-top:22px">
    <h3><b><i class="fa fa-user"></i> Add Daily Cadre</b></h3>
</header>



<?php

$message = '';

    if(isset($_POST["upload"])){

    if($_FILES['product_file']['name']){

    $filename = explode(".", $_FILES['product_file']['name']);

    if(end($filename) == "csv"){

    $handle = fopen($_FILES['product_file']['tmp_name'], "r");
    while($data = fgetcsv($handle)){

        $date_e = $_POST["datef"];
        $pn = $data[0];
        $epf = $data[1];
        $name = $data[2];
        $gender = $data[3];
        $team = $data[4];
        $classification = $data[5];
        $pdate = $data[6];
        $jdate = date("Y-m-d", strtotime($pdate));
        $all = $db->query("INSERT IGNORE INTO emp (Entered_date,PN,EPF,Name,Gender,Team,Classification,Join_Date) VALUES ('$date_e','$pn','$epf','$name','$gender','$team','$classification','$jdate')"); 

    }
    fclose($handle);
    header("location: add_emp.php?updation=1");

    }
    else{
    $message = '<label class="text-danger">Please Select CSV File only</label>';
    }
    }
    else{
    $message = '<label class="text-danger">Please Select File</label>';
    }
    }

    if(isset($_GET["updation"])){?>
        <div class="alert alert-success alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Employee Updation is Done <i class="fa fa-check"></i></strong>
        </div>
    <?php
    }
?>


<div class="w3-left">

    <div class="w3-row-padding w3-margin-bottom">
        <form method="post" enctype='multipart/form-data' style="margin:10px;">
            <div class="form-group date" data-provide="datepicker">
                <label class="control-label">Date :</label>
                <input type="text" name="datef" value="<?php echo $datef; ?>" class="form-control" required>
            </div>
            <h5><label>Please Select File(Only CSV Formate)</label>
            <input type="file" name="product_file" /></h5>
            <input type="submit" name="upload" class="w3-button w3-teal w3-hover-blue-gray" value="Upload" />
            <a class="btn btn-danger btn-md" onclick="return confirm('Continue Delete Today Cadre ?')" href="delete-cadre.php?id=1"><i class="fa fa-trash"></i> Delete Today Cadre</a>
        </form>
    </div>
</div>

<div class="w3-row-padding w3-margin-bottom">
<div class="table-responsive">
<table class="table table-hover table-striped" id="table">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Date</th>
                    <th>PN</th>
                    <th>EPF</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Team</th>
                    <th>Classification</th>
                    <th>Join Date</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $count = 1;
                $all = $db->query("SELECT * FROM emp WHERE DATE(Entered_date) = CURDATE()");
                $fetch = $all->fetchAll(PDO::FETCH_OBJ);
                foreach($fetch as $data){ 

            ?>
            <tr>
                <td><?php echo $count  ?></td>
                <td><?php echo $data->Entered_date  ?></td>
                <td><?php echo $data->PN ?></td>
                <td><?php echo $data->EPF ?></td>
                <td><?php echo $data->Name ?></td>
                <td><?php echo $data->Gender ?></td>
                <td><?php echo $data->Team ?></td>
                <td><?php echo $data->Classification ?></td>
                <td><?php echo $data->Join_Date ?></td>
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
<?php include 'theme/foot.php'; ?>
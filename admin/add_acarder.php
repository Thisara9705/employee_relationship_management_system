<?php include '../db/db.php'; ?>
<?php include 'theme/head.php'; ?>
<?php include 'theme/sidebar.php'; ?>
<?php ob_start();?>
<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:250px;margin-top:43px;">
<!-- Header -->
<header class="w3-container" style="padding-top:22px">
    <h3><b><i class="fa fa-line-chart"></i> Add Actual Carder</b></h3>
</header>

<p class="text-center" style="margin-top: 3%"></p>

<?php

$message = '';

    if(isset($_POST["upload"])){

    if($_FILES['product_file']['name']){

    $filename = explode(".", $_FILES['product_file']['name']);

    if(end($filename) == "csv"){

    $handle = fopen($_FILES['product_file']['tmp_name'], "r");
    while($data = fgetcsv($handle)){

        $date_c = $_POST["datef"];
        $team = $data[0];
        $vsl = $data[1];
        $er = $data[2];
        $area = $data[3];
        $shift = $data[4];
        $ica = $data[5];
        $ac = $data[6];
        $planned_cadre = $data[7];
        
        $Style = $data[8];
        
        $all = $db->query("INSERT IGNORE INTO team (c_date,Team,VSL,ER,Area,Shift,ICA,AC,planned_cadre,Style) VALUES ('$date_c','$team','$vsl','$er','$area','$shift','$ica','$ac','$planned_cadre','$Style')"); 

    }
    fclose($handle);
    header("location: add_acarder.php?updation=1");

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
            <strong>Actual Carder Updation is Done <i class="fa fa-check"></i></strong>
        </div>
    <?php
    }
?>

<?php

$message = '';

    if(isset($_POST["upload3"])){


        $date_p = date('Y-m-d');
        $p_cadre = $_POST['p_cadre'];
        $all = $db->query("INSERT IGNORE INTO planned_cadre (p_date,total) VALUES ('$date_p','$p_cadre')");

    header("location: add_acarder.php?updation2=1");
   
    }

    if(isset($_GET["updation2"])){?>
        <div class="alert alert-success alert-dismissable">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Planned Carder Updation is Done <i class="fa fa-check"></i></strong>
        </div>
    <?php
    }
?>
<?php 

$all = $db->query("SELECT * FROM planned_cadre ORDER BY id DESC LIMIT 1");
$data = $all->fetch(PDO::FETCH_ASSOC);

$p_cadre = $data['total'];

?>

<div class="w3-row-padding w3-margin-bottom">
    <div class="w3-left">
        <form method="post" enctype='multipart/form-data' style="margin:10px;">
            <div class="form-group date" data-provide="datepicker">
                <label class="control-label">Date :</label>
                <input type="text" name="datef" value="<?php echo $datef; ?>" class="form-control" required>
            </div>
            <h5><label>Please Select File(Only CSV Formate)</label>
            <input type="file" name="product_file" /></h5>
            <input type="submit" name="upload" class="w3-button w3-teal w3-hover-blue-gray" value="Upload" />
            <a class="btn btn-danger btn-md" onclick="return confirm('Continue Delete Today Cadre ?')" href="delete-acadre.php?id=1"><i class="fa fa-trash"></i> Delete Today Actual Cadre</a>
        </form>
    </div>
    <div class="w3-right">
        <form method="post" enctype='multipart/form-data' style="margin:10px;">
            <h5><label>Edit Planned Cadre</label>
            <div class="form-group">
                <input type="text" name="p_cadre" class="form-control" value="<?php echo $p_cadre ?>" required>
            </div>
            <input type="submit" name="upload3" class="w3-button w3-teal w3-hover-blue-gray" value="Upload" />
            
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
                    <th>Team</th>
                    <th>VSL</th>
                    <th>ER</th>
                    <th>Area</th>
                    <th>Internal Carder Allocation</th>
                    <th>Actual Carder</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $count = 1;
                $all = $db->query("SELECT * FROM team WHERE DATE(c_date) = CURDATE()");
                $fetch = $all->fetchAll(PDO::FETCH_OBJ);
                foreach($fetch as $data){ 

            ?>
            <tr>
                <td><?php echo $count  ?></td>
                <td><?php echo $data->c_date  ?></td>
                <td><?php echo $data->Team ?></td>
                <td><?php echo $data->VSL ?></td>
                <td><?php echo $data->ER ?></td>
                <td><?php echo $data->Area ?></td>
                <td><?php echo $data->ICA ?></td>
                <td><?php echo $data->AC ?></td>
            </tr>    
            <?php
            $count = 1+$count; 
            }
            ?>
            </tbody>
    </table>
    </div>    
</div>
<?php include 'theme/foot.php'; ?>
<!DOCTYPE html>
<html>
<head>
<title>User | ER Application</title>
<link rel="shortcut icon" type="image/jpg" href="https://hrsynergy.online/favicon.png"/>
<meta name="viewport" content="width=device-width, initial-scale=1">


<meta charset="UTF-8">
<!-- FILE BASED -->
<link rel="stylesheet" href="./plugin/w3.css">
<link rel="stylesheet" href="./plugin/w3-colors.css">
<link rel="stylesheet" href="./plugin/bootstrap.min.css">
<script src="./plugin/jquery-2.2.4.min.js"></script>
<script src="./plugin/bootstrap.min.js"></script>
<script src="./plugin/table2excel.js"></script>
<script src="./plugin/dataTables.fixedHeader.min.js"></script>
<link rel="stylesheet" href="./plugin/font-awesome.min.css">

<!-- CDN BASED  -->
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400&display=swap" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/fixedheader/3.1.9/js/dataTables.fixedHeader.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.9/css/fixedHeader.dataTables.min.css">
<script src="https://cdn.plot.ly/plotly-latest.min.js"></script>


<style>
body {
    font-family: 'Poppins', sans-serif;
    background-color: #d4d8dd;
}
</style>

<script>
 $(document).ready(function(){
    $('#table').DataTable();
});

 $(document).ready(function(){
 	 $('#table_pig').DataTable();
 })

 $(document).ready(function() {
    var table = $('#example').DataTable( {
        fixedHeader: {
            header: true,
            footer: true
        }
    } );
} );

$(document).ready(function(){
    $('#example').dataTable( {
        "searching": false
    } );
});

</script>

<script>
	$.fn.datepicker.defaults.format = "yyyy-mm-dd";
	$('.datepicker').datepicker();
</script>
</head>
<body>



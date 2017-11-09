<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/materialize.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="fonts/icons/material-icons.css">
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery.validate.js"></script>
    <script type="text/javascript" src="js/additional-methods.js"></script>
    <script type="text/javascript" src="js/materialize.js"></script>
    <title>GENERAR REPORTES</title>
</head>
<body>
<nav>
    <?php
    require_once("menu.php");
    ?>
</nav>
<div class="container">
    <div>
        <h3>GENERAR REPORTES</h3>
    </div>
    <div class="card-panel center-align">
        <div  class="row" id="btn_corte"><a href="#!" class="btn">Corte de Caja </a></div>
        <div class="row" ><a href="reporte.html" class="btn" target="_blank">Reporte Semanal </a></div>
        <div class="row" ><a href="reporte.html" class="btn" target="_blank">Reporte Mensual </a></div>
        <div class="row" ><a href="reporte.html" class="btn" target="_blank">Reporte Anual </a></div>
    </div>
</div>
    <aside id="container_modal"></aside>
</body>
<script>
    $("#btn_corte").click(function(){
        $('#container_modal').load("core/cortes/form_create_corte.php");
    })
</script>
</html>
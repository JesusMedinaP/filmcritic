<?php if(isset($_GET['controlador']) && $_GET['controlador'] == 'admin') { ?> <h1 class="title hover_scale"><a href="index.php?controlador=admin&action=home">Administración</a></h1><?php }
else { ?>
<h1 class="title hover_scale"><a href="index.php?controlador=catalogue&action=home">Pelis Review</a></h1>
<?php } ?>
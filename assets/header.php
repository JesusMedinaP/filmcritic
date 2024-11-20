<?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) { ?> <h1 class="title hover_scale"><a href="index.php?controlador=admin&action=home">Administración</a></h1><?php }
else { ?>
<h1 class="title hover_scale"><a href="index.php?controlador=catalogue&action=home"><?php echo APP_NAME ?></a></h1>
<?php } ?>
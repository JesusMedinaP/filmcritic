<?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) { ?> <h1 class="title hover_scale"><img src="assets/logo.png" alt="logo" style="height: 40px; width: 40px;"/><a href="index.php?controlador=admin&action=home">Administración</a></h1><?php }
else { ?>
<h1 class="title hover_scale"><a href="index.php?controlador=catalogue&action=home"><img src="assets/logo.png" style="height: 35px; width: 35px;"><?php echo APP_NAME ?></a></h1>
<?php } ?>
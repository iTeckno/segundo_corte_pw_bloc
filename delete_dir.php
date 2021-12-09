<?php
if ($_GET['archivo']) {
    rmdir(urldecode("notas/{$_GET['archivo']}"));
}


header('Location: index.php');
die;
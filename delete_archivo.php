<?php
if ($_GET['archivo']) {
    unlink(urldecode("notas/{$_GET['archivo']}"));
}


header('Location: index.php');
die;
<?php
session_start();
session_destroy();
header("Location: ../index.php"); // Przekierowanie na stronę główną
exit;
?>

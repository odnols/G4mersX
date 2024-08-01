<?php

if (!isset($_SESSION)) {
	session_start();
}

if ($_SESSION["logado"] == 0) {
	header("Location: ../index.php");
}

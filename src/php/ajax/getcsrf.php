<?php
session_start();
echo $_SESSION['csrf_token'];
?>
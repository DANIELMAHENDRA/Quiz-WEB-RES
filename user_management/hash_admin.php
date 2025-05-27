<?php
$password = "112233";
$hash = password_hash($password, PASSWORD_DEFAULT);
echo $hash;
?>
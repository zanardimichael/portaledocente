<?php

session_start();
session_destroy();

setcookie('user_session', '', 1);

header("Location: /admin/login.php");
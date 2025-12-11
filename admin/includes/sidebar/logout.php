<?php
session_start();
session_destroy();
header("Location: ../../../users/home/index.php");
exit();
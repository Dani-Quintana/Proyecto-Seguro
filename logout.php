<?php
session_start();
session_destroy();
header("Location: controller/loginController.php");
exit();

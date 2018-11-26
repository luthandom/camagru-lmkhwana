<?php
/**
 * Created by PhpStorm.
 * User: sshayi
 * Date: 2018/10/22
 * Time: 16:30
 */

session_start();
unset($_SESSION);
session_destroy();
header("Location: login/login.php");
<?php
include_once '../includes/utils.php';

safe_session_start();
unset($_SESSION['user-id']);

header('Location: /index.php');
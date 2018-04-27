<?php
include_once '../includes/utils.php';

safe_session_start();
session_unset();

redirect_back();

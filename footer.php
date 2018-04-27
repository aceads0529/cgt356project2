<?php
global $store_url;

if ($store_url) {
    safe_session_start();
    $_SESSION['last-url'] = $_SERVER['REQUEST_URI'];
    echo $_SESSION['last-url'];
}
?>
</body>
</html>

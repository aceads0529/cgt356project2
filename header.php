<?php
include_once 'includes/user.php';

global $active_user;
$active_user = get_active_user();
?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Sauce & Co.</title>

        <link href="https://fonts.googleapis.com/css?family=Montserrat:500,500i,700" rel="stylesheet">

        <link href="styles/main.css" rel="stylesheet"/>
        <link href="styles/forms.css" rel="stylesheet"/>

        <script src="https://code.jquery.com/jquery-3.3.1.js"
                integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
                crossorigin="anonymous"></script>
        <script src="/scripts/ui.js"></script>
    </head>

<body>

    <header>
        <div id="page-logo"></div>
        <nav>
            <ul>
                <li><a href="/index.php">Home</a></li>
                <li><a href="#">Gallery</a></li>
                <li><a href="#">Readme</a></li>
                <?php if (!$active_user): ?>
                    <li id="login"><a href="/user_login.php">Login</a></li>
                <?php else: ?>
                    <li id="login">
                        <span id="greeting">Hello, <?php echo $active_user['FirstName']; ?></span>
                        <a href="/api/user_logout.php">Logout</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
<?php if ($active_user): ?>
    <div id="toolbar">
        <ul>
            <?php if ($active_user['AcctType'] == 'ADMIN'): ?>
                <li>Admin Toolbar</li>
                <li><a href="/user_admin.php">Accounts</a></li>
                <li><a href="#">Categories</a></li>
                <li><a href="#">Images</a></li>
            <?php endif; ?>
            <li><a href="/user_edit.php?user-id=<?php echo $active_user['UserId']; ?>">Edit Account</a></li>
        </ul>
    </div>
<?php endif; ?>
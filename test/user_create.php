<?php
include '../includes/utils.php';
include '../includes/user.php';

$active_user = get_active_user();

if (!$active_user)
    header('Location: ../test/user_login.php')
?>

<!DOCTYPE html>
<html>

<head>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 50px;
        }

        label {
            display: inline-block;
            min-width: 120px;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
            display: flex;
        }

        input[type="text"], input[type="password"], select {
            width: 150px;
            margin: 0;
            padding: 2px;
            border: 1px solid #aaa;
        }

        input[type="submit"] {
            min-width: 50px;
            margin-left: 120px;
        }

        #message {
            height: 1.25em;
            font-weight: bold;
            color: coral;
        }
    </style>
</head>

<body>
<h1>New User</h1>
<div id="message"></div>
<form onsubmit="onSubmit(event)">
    <ul>
        <li>
            <label for="username">Username</label>
            <input id="username" name="username" type="text"/>
        </li>
        <li>
            <label for="password">Password</label>
            <input id="password" name="password" type="password"/>
        </li>
        <li>
            <label for="password-confirm">Pass. (confirm)</label>
            <input id="password-confirm" name="password-confirm" type="password"/>
        </li>
        <li>
            <label for="first-name">First name</label>
            <input id="first-name" name="first-name" type="text"/>
        </li>
        <li>
            <label for="last-name">Last name</label>
            <input id="last-name" name="last-name" type="text"/>
        </li>
        <li>
            <label for="acct-type">Acct. type</label>
            <select id="acct-type" name="acct-type">
                <option value="curator">Curator</option>
                <option value="admin">Administrator</option>
            </select>
        </li>
        <input type="submit" value="Create"/>
    </ul>
</form>

<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
        crossorigin="anonymous"></script>
<script>
    function onSubmit(event) {
        event.preventDefault();

        var form = event.target;
        var params = {
            username: form.elements['username'].value,
            password: form.elements['password'].value,
            'acct-type': form.elements['acct-type'].value,
            'first-name': form.elements['first-name'].value,
            'last-name': form.elements['last-name'].value
        };

        $.post('/api/user_create.php', params, function (result) {
            if (result.success)
                $('#message').text('New user created successfully!');
            else
                $('#message').text(result.error);
        });
    }
</script>
</body>

</html>
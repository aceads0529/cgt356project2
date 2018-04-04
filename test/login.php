<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            margin: 50px;
        }

        label {
            display: inline-block;
            min-width: 100px;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
            display: flex;
        }

        input[type="submit"] {
            min-width: 50px;
            margin-left: 100px;
        }

        #message {
            height: 1.25em;
            font-weight: bold;
            color: coral;
        }
    </style>
</head>

<body>
<h1>Login</h1>
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
        <input type="submit" value="Login"/>
    </ul>
</form>

<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
        crossorigin="anonymous"></script>
<script>
    function onSubmit(event) {
        event.preventDefault();

        var form = event.target;
        var params = {username: form.elements['username'].value, password: form.elements['password'].value};

        $.post('/api/user_login.php', params, function (result) {
            if (result.success)
                $('#message').text('Login successful!');
            else
                $('#message').text(result.error);
        });
    }
</script>
</body>

</html>
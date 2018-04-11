<!DOCTYPE html>
<html>

<head>
    <link href="/styles/main.css" rel="stylesheet"/>
    <link href="/styles/ui.css" rel="stylesheet"/>
</head>

<body>
<h1>Login</h1>

<form id="form" onsubmit="onSubmit(event)">
    <div id="message"></div>
    <input type="submit" value="Submit"/>
</form>

<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
        crossorigin="anonymous"></script>
<script src="/scripts/ui.js"></script>

<script>
    const form = new UIGroup('form');

    var textbox = new UITextbox('hello', 'Hello');
    textbox.value('Aaron Eads');

    form.add(new UITextbox('username', 'Username'));
    form.add(new UITextbox('password', 'Password', 'password'));

    form.prependTo('#form');

    function onSubmit(event) {
        event.preventDefault();

        let params = form.value();

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
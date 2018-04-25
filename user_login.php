<!DOCTYPE html>
<html>

<head>
    <title>User Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="/styles/loginrules.css" type="text/css">
</head>

<body>

<div class="container-fluid">
    <div class="message"></div>
    <form id="form-login" onsubmit="onSubmit(event)">
        <button type="submit">Login</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
        crossorigin="anonymous"></script>
<script src="/scripts/ui.js"></script>

<script>
    const form = new UIGroup('form');
    const username = new UITextbox('username', 'Username');
    const password = new UITextbox('password', 'Password');

    form.add(username);
    form.add(password);

    form.prependTo('#form-login');

    function onSubmit(event) {
        event.preventDefault();

        var values = form.value();

        $.post('api/user_login.php', values, function (result) {
            if (result.success) {
                window.location = 'index.php';
            }
            else {
                $('.message').text(result.error);
            }
        });
    }
</script>

</body>

</html>
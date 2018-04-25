<!DOCTYPE html>
<html>

<head>
    <title>Create!</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="/styles/loginrules.css" type="text/css">
</head>

<body>

<div class="container-fluid">
    <form id="form-create" onsubmit="onSubmit(event)">
        <fieldset>
            <legend>Create Account</legend>
            <div class="message"></div>
            <div id="form-fields"></div>
            <span style="color:white"><input type="submit" value="Create Account"></span>
        </fieldset>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
        crossorigin="anonymous"></script>
<script src="/scripts/ui.js"></script>

<script>
    const form = new UIGroup('form');
    const username = new UITextbox('username', 'Username');
    const password = new UITextbox('password', 'Password', 'password');
    const passwordConfirm = new UITextbox('password-confirm', 'Password (confirm)', 'password');
    const firstName = new UITextbox('first-name', 'First name');
    const lastName = new UITextbox('last-name', 'Last name');

    form.add(username);
    form.add(password);
    form.add(passwordConfirm);
    form.add(firstName);
    form.add(lastName);

    form.prependTo('#form-fields');

    function onSubmit(event) {
        event.preventDefault();

        var values = form.value();
        values['acct-type'] = 'CURATOR';

        if (values['password'] !== values['password-confirm']) {
            $('.message').text('Passwords do not match');
            return;
        }

        $.post('api/user_create.php', values, function (result) {
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
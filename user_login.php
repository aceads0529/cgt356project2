<?php include 'header.php'; ?>

    <div class="form-container">
        <form id="form-login" onsubmit="onSubmit(event)">
            <div class="title">User Login</div>
            <div class="message"></div>
            <div id="form-fields"></div>

            <div style="text-align: right"><input type="submit" value="Login"/></div>
        </form>
    </div>

    <script>
        const form = new UIGroup('form');
        const username = new UITextbox('username', 'Username');
        const password = new UITextbox('password', 'Password', 'password');

        form.add(username);
        form.add(password);

        form.prependTo('#form-fields');

        username.focus();

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

<?php include 'footer.php'; ?>
<?php
include_once 'includes/utils.php';
include_once 'includes/user.php';

if (!user_is_authorized(null, AUTH_USER_CREATE))
    redirect_login();

if (!isset($_GET['acct-type']))
    $_GET['acct-type'] = 'CURATOR';
?>

<?php include_once 'header.php'; ?>

    <div class="form-container">
        <form id="form-create" onsubmit="onSubmit(event)">
            <div class="title">Create Account</div>
            <div class="message"></div>
            <div id="form-fields"></div>

            <div style="text-align: right"><input type="submit" value="Create"/></div>
        </form>
    </div>

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

        <?php if ($_GET['acct-type'] == 'CURATOR'): ?>
        const categories = new UIGroup('categories');

        <?php $categories = get_all_categories();
        foreach ($categories as $cat): ?>

        categories.add(new UICheckbox('<?php echo $cat['CategoryId']; ?>', '<?php echo $cat['Label']; ?>'));

        <?php endforeach; ?>
        form.add(categories);
        <?php endif; ?>

        form.prependTo('#form-fields');

        function onSubmit(event) {
            event.preventDefault();

            let values = form.value();
            values['acct-type'] = '<?php echo $_GET['acct-type']; ?>';

            if (values['password'] !== values['password-confirm']) {
                $('.message').text('Passwords do not match');
                return;
            }

            $.post('api/user_create.php', values, function (result) {
                if (result.success) {
                    window.location = '/user_admin.php';
                }
                else {
                    $('.message').text(result.error);
                }
            });
        }
    </script>

<?php include_once 'footer.php'; ?>
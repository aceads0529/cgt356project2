<?php
include_once 'includes/utils.php';
include_once 'includes/user.php';

$user = read_get_id('user-id');

if (!$user)
    redirect_back();

if (!user_is_authorized($user['UserId'], AUTH_USER_EDIT))
    redirect_login();
?>
<?php include_once 'header.php';
global $store_url;
$store_url = false; ?>

    <div class="form-container">
        <form id="form-create" onsubmit="onSubmit(event)">
            <div class="title">Edit Account</div>
            <div class="message"></div>
            <div id="form-fields"></div>

            <div style="text-align: right"><input type="submit" value="Update"/></div>
        </form>
    </div>

    <script>
        const form = new UIGroup('form');
        const username = new UITextbox('username', 'Username').value('<?php echo addslashes($user['Login']); ?>');
        const password = new UITextbox('password', 'Change password', 'password');
        const passwordConfirm = new UITextbox('password-confirm', 'Password (confirm)', 'password');
        const firstName = new UITextbox('first-name', 'First name').value('<?php echo addslashes($user['FirstName']); ?>');
        const lastName = new UITextbox('last-name', 'Last name').value('<?php echo addslashes($user['LastName']); ?>');

        form.add(username);
        form.add(password);
        form.add(passwordConfirm);
        form.add(firstName);
        form.add(lastName);

        <?php if ($user['AcctType'] == 'CURATOR' && $active_user['AcctType'] == 'ADMIN'): ?>
        const categories = new UIGroup('categories');

        <?php $categories = get_all_categories();
        foreach ($categories as $cat): ?>

        categories.add(new UICheckbox('<?php echo $cat['CategoryId']; ?>', '<?php echo addslashes($cat['Label']); ?>').value(<?php echo user_has_category($_GET['user-id'], $cat['CategoryId']) ? 'true' : 'false'; ?>));

        <?php endforeach; ?>
        form.add(categories);
        <?php endif; ?>

        form.prependTo('#form-fields');

        function onSubmit(event) {
            event.preventDefault();

            let values = form.value();
            values['acct-type'] = '<?php echo $user['AcctType']; ?>';
            values['user-id'] = '<?php echo $user['UserId']; ?>';

            if (values['password'] !== values['password-confirm']) {
                $('.message').text('Passwords do not match');
                return;
            }

            $.post('api/user_edit.php', values, function (result) {
                if (result.success) {
                    window.location = '<?php echo get_back_url(); ?>';
                }
                else {
                    $('.message').text(result.error);
                }
            });
        }
    </script>

<?php include_once 'footer.php'; ?>
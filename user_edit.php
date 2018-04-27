<?php
include_once 'includes/utils.php';
include_once 'includes/user.php';

if (isset($_GET['user-id']))
    $user_edit = get_user_by_id($_GET['user-id'])->fetch_assoc();
else
    $user_edit = user_empty();

include_once 'header.php'; ?>

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
        const username = new UITextbox('username', 'Username').value('<?php echo addslashes($user_edit['Login']); ?>');
        const password = new UITextbox('password', 'Change password', 'password');
        const passwordConfirm = new UITextbox('password-confirm', 'Password (confirm)', 'password');
        const firstName = new UITextbox('first-name', 'First name').value('<?php echo addslashes($user_edit['FirstName']); ?>');
        const lastName = new UITextbox('last-name', 'Last name').value('<?php echo addslashes($user_edit['LastName']); ?>');

        form.add(username);
        form.add(password);
        form.add(passwordConfirm);
        form.add(firstName);
        form.add(lastName);

        <?php if ($user_edit['AcctType'] == 'CURATOR'): ?>
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

            var values = form.value();
            values['acct-type'] = '<?php echo $user_edit['AcctType']; ?>';
            values['user-id'] = '<?php echo $user_edit['UserId']; ?>';

            if (values['password'] !== values['password-confirm']) {
                $('.message').text('Passwords do not match');
                return;
            }

            $.post('api/user_edit.php', values, function (result) {
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
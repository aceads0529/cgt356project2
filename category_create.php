<?php
include_once 'includes/utils.php';
include_once 'includes/user.php';

if (!user_is_authorized(null, AUTH_CATEGORY_CREATE))
    redirect_login();
?>

<?php include_once 'header.php'; ?>

    <div class="form-container">
        <form onsubmit="onSubmit(event)">
            <div class="title">Create Category</div>
            <div class="message"></div>
            <div id="form-fields"></div>

            <div style="text-align: right"><input type="submit" value="Create"/></div>
        </form>
    </div>

    <script>
        const form = new UIGroup('form');
        const label = new UITextbox('label', 'Label');
        const description = new UITextbox('description', 'Description');

        form.add(label);
        form.add(description);

        form.prependTo('#form-fields');

        function onSubmit(event) {
            event.preventDefault();

            let values = form.value();

            $.post('api/category_create.php', values, function (result) {
                if (result.success) {
                    window.location = 'index.php';
                }
                else {
                    $('.message').text(result.error);
                }
            });
        }
    </script>

<?php include_once 'footer.php'; ?>
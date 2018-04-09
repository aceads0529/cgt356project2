<?php
include '../includes/utils.php';
include '../includes/user.php';

$active_user = get_active_user();
?>

<!DOCTYPE html>
<html>

<head>
    <link href="/styles/main.css" rel="stylesheet"/>
    <link href="/styles/ui.css" rel="stylesheet"/>
</head>

<body>
<h1>Edit User</h1>
<div id="message"></div>

<button onclick="onSubmit(event)">SUBMIT</button>

<div id="form">Loading...</div>

<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
        crossorigin="anonymous"></script>
<script src="/scripts/ui.js"></script>

<script>
    const form = new UIGroup('form');
    const categories = form.add(new UIGroup('categories'));

    // AJAX get user by ID
    $.post('/api/user_select.php', {selection: 11}, function (result) {
        if (!result.success) {
            $('#message').text(result.error);
            return;
        }

        let user = result.users[0];

        // Add controls to form, supplying values with user details
        form.add(new UITextbox('username', 'Username')).value(user['Login']);
        form.add(new UITextbox('password', 'Password', 'password'));
        form.add(new UITextbox('password-confirm', 'Pass (confirm)', 'password'));
        form.add(new UITextbox('first-name', 'First name')).value(user['FirstName']);
        form.add(new UITextbox('last-name', 'Last name')).value(user['LastName']);

        // AJAX get all categories
        $.post('/api/category_select.php', {}, function (result) {
            if (result.success) {
                for (let i = 0; i < result.categories.length; i++) {
                    let cat = result.categories[i];

                    // Add checkboxes to categories, marking as checked if user has permission to category
                    categories.add(new UICheckbox(cat['CategoryId'], cat['Label'])).value(user.categories.includes(cat['CategoryId']));
                }

                form.add(categories);

                // Add form to page
                $('#form').html('');
                form.appendTo('#form');
            }
        });
    });

    function onSubmit(event) {
        event.preventDefault();

        let params = form.value();
        params['user-id'] = 11;

        // Verify passwords match
        if ((params['password'] || params['password-confirm']) && (params['password-confirm']) !== params['password']) {
            $('#message').text('Passwords do not match!');
            return;
        }

        $.post('/api/user_edit.php', params, function (result) {
            if (result.success) {
                $('#message').text('User updated successfully!');
            } else {
                $('#message').text(result.error);
            }
        });
    }
</script>

</body>

</html>
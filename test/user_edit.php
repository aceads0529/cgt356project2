<?php
include '../includes/utils.php';
include '../includes/user.php';

$active_user = get_active_user();
?>

<!DOCTYPE html>
<html>

<head>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 50px;
        }

        label {
            display: inline-block;
            min-width: 120px;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
            display: flex;
        }

        input[type="text"], input[type="password"], select {
            width: 150px;
            margin: 0;
            padding: 2px;
            border: 1px solid #aaa;
        }

        input[type="submit"] {
            min-width: 50px;
            margin-left: 120px;
        }

        #message {
            height: 1.25em;
            font-weight: bold;
            color: coral;
        }
    </style>
</head>

<body>
<h1>Edit User</h1>
<div id="message"></div>
<form onsubmit="onSubmit(event)">
    <ul>
        <li>
            <label for="user-id">User</label>
            <select id="user-id" name="user-id">
                <?php
                $db = db_connect();

                if (user_is_authorized('admin')) {
                    $result = db_query($db, 'SELECT UserId, Login FROM users');
                } elseif (user_is_authorized('curator')) {
                    $result = db_query($db, 'SELECT UserId, Login FROM users WHERE UserId=?', $active_user['UserId']);
                }

                db_close($db);

                if ($result) :
                    while ($row = $result->fetch_assoc()) : ?>
                        <option value="<?php echo $row['UserId'] ?>"><?php echo $row['Login'] ?></option>
                    <?php endwhile; endif ?>
            </select>
        </li>
        <li>
            <label for="username">Username</label>
            <input id="username" name="username" type="text"/>
        </li>
        <li>
            <label for="password">Password</label>
            <input id="password" name="password" type="password"/>
        </li>
        <li>
            <label for="password-confirm">Pass. (confirm)</label>
            <input id="password-confirm" name="password-confirm" type="password"/>
        </li>
        <li>
            <label for="first-name">First name</label>
            <input id="first-name" name="first-name" type="text"/>
        </li>
        <li>
            <label for="last-name">Last name</label>
            <input id="last-name" name="last-name" type="text"/>
        </li>
    </ul>

    <ul id="categories"></ul>

    <input type="submit" value="Update"/>
</form>

<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
        crossorigin="anonymous"></script>
<script>
    var categories = [];

    $(function () {
        $.post('/api/category_select.php', {'user-id': 11}, function (result) {
            console.log(result);

            if (result.success) {
                var listItemTemplate = '<li></li>';
                var checkboxTemplate = '<input type="checkbox"/>';
                var labelTemplate = '<label></label>';

                var categoriesElement = $('#categories');

                result.categories.forEach(function (cat) {
                    var elementId = 'category-' + cat.CategoryId;

                    var listItem = $(listItemTemplate);

                    var checkbox = $(checkboxTemplate);
                    checkbox.attr('id', elementId);

                    if (cat.UserPermission)
                        checkbox[0].checked = true;

                    var label = $(labelTemplate);
                    label.text(cat.Label);
                    label.attr('for', elementId);

                    listItem.append(checkbox);
                    listItem.append(label);
                    categoriesElement.append(listItem);

                    categories.push({id: cat.CategoryId, elementId: elementId, element: checkbox[0]});
                });
            }
        });
    });

    function getCategoryArray() {
        var result = [];

        categories.forEach(function (cat) {
            if (cat.element.checked)
                result.push(cat.id);
        });

        console.log(result);
        return result;
    }

    function onSubmit(event) {
        event.preventDefault();

        var form = event.target;
        var params = {
            'user-id': form.elements['user-id'].value,
            username: form.elements['username'].value,
            password: form.elements['password'].value,
            'first-name': form.elements['first-name'].value,
            'last-name': form.elements['last-name'].value,
            'categories': getCategoryArray()
        };

        console.log(form.elements['category-6'].checked);

        $.post('/api/user_edit.php', params, function (result) {
            if (result.success)
                $('#message').text('User updated successfully!');
            else
                $('#message').text(result.error);
        });
    }
</script>
</body>

</html>
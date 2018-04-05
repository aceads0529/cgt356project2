<?php
include '../includes/db.php';
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
<h1>Delete User</h1>
<div id="message"></div>
<form onsubmit="onSubmit(event)">
    <ul>
        <li>
            <label for="user-id">User</label>
            <select id="user-id" name="user-id">
                <?php
                $db = db_connect();
                $result = db_query($db, 'SELECT UserId, Login FROM users');

                while ($row = $result->fetch_assoc()) : ?>
                    <option value="<?php echo $row['UserId'] ?>"><?php echo $row['Login'] ?></option>
                <?php endwhile ?>
            </select>
        </li>
    </ul>
    <input type="submit" value="Delete"/>
</form>

<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
        crossorigin="anonymous"></script>
<script>
    function onSubmit(event) {
        event.preventDefault();

        var form = event.target;
        var params = {
            'user-id': form.elements['user-id'].value
        };

        $.post('/api/user_delete.php', params, function (result) {
            if (result.success) {
                $('#message').text('User deleted successfully!');

                setTimeout(function () {
                    location.reload(true);
                }, 1000);
            }
            else
                $('#message').text(result.error);
        });
    }
</script>
</body>

</html>
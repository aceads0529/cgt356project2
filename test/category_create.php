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

        input[type="text"], input[type="password"], select, textarea {
            width: 200px;
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
<h1>Create Category</h1>
<div id="message"></div>
<form onsubmit="onSubmit(event)">
    <ul>
        <li>
            <label for="label">Label</label>
            <input id="label" name="label" type="text"/>
        </li>
        <li>
            <label for="description">Description</label>
            <textarea id="description" name="description" maxlength="250" rows="4"></textarea>
        </li>
    </ul>
    <input type="submit" value="Create"/>
</form>

<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
        crossorigin="anonymous"></script>
<script>
    function onSubmit(event) {
        event.preventDefault();

        var form = event.target;
        var params = {label: form.elements['label'].value, description: form.elements['description'].value};

        $.post('/api/category_create.php', params, function (result) {
            if (result.success)
                $('#message').text('Category created successfully!');
            else
                $('#message').text(result.error);
        });
    }
</script>
</body>

</html>
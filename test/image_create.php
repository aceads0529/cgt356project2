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

        #img-preview {
            border: 1px solid #ccc;
            margin: 20px 0 20px 120px;
            background: no-repeat center;
            background-size: contain;
        }
    </style>
</head>

<body>
<h1>Upload Image</h1>
<div id="message"></div>
<form method="post" action="/api/image_create.php" enctype="multipart/form-data">
    <ul>
        <li>
            <label for="label">Label</label>
            <input id="label" name="label" type="text"/>
        </li>
        <li>
            <label for="description">Description</label>
            <textarea id="description" name="description" maxlength="250" rows="4"></textarea>
        </li>
        <li>
            <label for="file">Image</label>
            <input id="file" name="file" type="file"/>
        </li>
        <li>
            <img src="" id="img-preview"/>
        </li>
    </ul>
    <input type="submit" value="Upload"/>
</form>
</body>

</html>
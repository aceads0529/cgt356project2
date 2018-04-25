<?php
<<<<<<< HEAD
include '../includes/db.php';
include '../includes/utils.php';

$_GET['ImageId'] = 4;

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

<?php
$image = db_connect_query('SELECT * FROM images WHERE ImageId=?', $_GET['ImageId'])->fetch_assoc();
?>

<form id="form" method="post" action="/api/image_edit.php" enctype="multipart/form-data">
    <?php $categories = get_all_categories(); ?>

    <input type="text" name="image-id" value="<?php echo $image['ImageId']; ?>"/>
    <select id="category" name="category-id">
        <?php foreach ($categories as $cat): ?>
            <option value="<?php echo $cat['CategoryId']; ?>"><?php echo $cat['Label']; ?></option>
        <?php endforeach; ?>
    </select>
    <input type="submit" value="Submit"/>
</form>

<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
        crossorigin="anonymous"></script>
<script src="/scripts/ui.js"></script>

<script>

    const uiLabel = new UITextbox('label', 'Label').value('<?php echo addslashes($image['Label']); ?>');
    const uiDescr = new UITextbox('description', 'Description').value('<?php echo addslashes($image['Description']); ?>');
    const uiFile = new UIFile('upload', 'Upload');

    uiLabel.prependTo('#form');
    uiDescr.prependTo('#form');
    uiFile.prependTo('#form');

    $('#category').val(<?php echo $image['CategoryId']; ?>);
</script>

</body>

</html>
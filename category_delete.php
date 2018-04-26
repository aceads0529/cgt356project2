<?php
include 'includes/db.php';
?>

<!DOCTYPE html>
<html>

<head>
    <title>Delete category</title>
    <!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"-->
    <!--          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">-->
    <!--    <link rel="stylesheet" href="/styles/loginrules.css" type="text/css">-->
    <link href="styles/main.css" rel="stylesheet"/>
    <link href="styles/forms.css" rel="stylesheet"/>
</head>

<body>

<div class="form-container">
    <form id="form-create" onsubmit="onSubmit(event)">
        <div class="title">Delete Category</div>
        <div class="message"></div>

        <?php $result = db_connect_query('SELECT * FROM categories'); ?>

        <select id="category-id" name="category-id">
            <?php while ($row = $result->fetch_assoc()): ?>
                <option value="<?php echo $row['CategoryId']; ?>"><?php echo $row['Label']; ?></option>
            <?php endwhile; ?>
        </select>
        <div style="text-align: right"><input type="submit" value="Delete"/></div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
        crossorigin="anonymous"></script>
<script src="/scripts/ui.js"></script>

<script>
    function onSubmit(event) {
        event.preventDefault();

        $.post('api/category_delete.php', {'category-id': event.target.elements['category-id'].value}, function (result) {
            window.location = 'index.php';
        });
    }
</script>

</body>

</html>
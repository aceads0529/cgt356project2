<?php
include_once 'includes/utils.php';
include_once 'includes/db.php';
include_once 'includes/user.php';

if (!user_is_authorized(null, AUTH_CATEGORY_EDIT))
    redirect_login();

$category = read_get_id('category-id');

if (!$category)
    redirect_back();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Category</title>
    <!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"-->
    <!--          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">-->
    <!--    <link rel="stylesheet" href="/styles/loginrules.css" type="text/css">-->
    <link href="styles/main.css" rel="stylesheet"/>
    <link href="styles/forms.css" rel="stylesheet"/>
</head>

<body>
<div class="form-container">
    <form onsubmit="onSubmit(event)">
        <div class="title">Edit Category</div>
        <div class="message"></div>
        <div id="form-fields"></div>

        <div style="text-align: right"><input type="submit" value="Update"/></div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
        crossorigin="anonymous"></script>
<script src="/scripts/ui.js"></script>

<script>
    const form = new UIGroup('form');
    const label = new UITextbox('label', 'Label').value('<?php echo addslashes($category['Label']); ?>');
    const description = new UITextbox('description', 'Description').value('<?php echo addslashes($category['Description']); ?>');

    form.add(label);
    form.add(description);

    form.prependTo('#form-fields');

    function onSubmit(event) {
        event.preventDefault();

        let values = form.value();
        values['category-id'] = <?php echo $category['CategoryId']; ?>

            $.post('api/category_edit.php', values, function (result) {
                if (result.success) {
                    window.location = '/category_admin.php';
                }
                else {
                    $('.message').text(result.error);
                }
            });
    }
</script>

</body>

</html>
<!DOCTYPE html>
<html>
<head>
    <title>Create Category</title>
    <!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"-->
    <!--          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">-->
    <!--    <link rel="stylesheet" href="/styles/loginrules.css" type="text/css">-->
    <link href="styles/main.css" rel="stylesheet"/>
    <link href="styles/forms.css" rel="stylesheet"/>

</head>

<body>
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

        var values = form.value();

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

</body>

</html>
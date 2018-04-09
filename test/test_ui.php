<!DOCTYPE html>
<html>

<head>
    <title>CGT 356 - UI Test</title>
    <link href="/styles/main.css" rel="stylesheet"/>
    <link href="/styles/ui.css" rel="stylesheet"/>

    <style>
        body {
            margin: 50px;
            font: 14px 'Montserrat Medium', sans-serif;
        }
    </style>
</head>

<body>

<h1>UI Test</h1>

<button onclick="print()">Print</button>

<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
        crossorigin="anonymous"></script>
<script src="/scripts/ui.js"></script>

<script>
    var form = new UIGroup('form');

    const uiUsername = new UITextbox('username', 'Username');
    const uiPassword = new UITextbox('password', 'Password', 'password');

    let categories = new UIGroup('categories');

    const uiCat01 = new UICheckbox('1', 'America').value(true);
    const uiCat02 = new UICheckbox('3', 'Canada');
    const uiCat03 = new UICheckbox('6', 'Mexico').value(true);
    const uiCat04 = new UICheckbox('9', 'Japan').value(true);
    const uiCat05 = new UICheckbox('11', 'Russia');

    uiUsername.value('aceads0529');
    uiPassword.value('ascii32');

    categories.elements.push(uiCat01, uiCat02, uiCat03, uiCat04, uiCat05);
    form.elements.push(uiUsername, uiPassword, categories);

    form.appendTo('body');

    function print() {
        console.log(form.value());
    }
</script>

</body>

</html>
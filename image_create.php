<?php
include_once 'includes/user.php';

if (!isset($_GET['category-id']))
    redirect_back();

if (!user_is_authorized($_GET['category-id'], AUTH_IMAGE_CREATE))
    redirect_login();
?>
<?php include_once 'header.php';
global $store_url;
$store_url = false; ?>

    <div class="form-container">
        <form onsubmit="onSubmit(event)" method="post" action="/api/image_create.php" enctype="multipart/form-data">
            <div class="title">Upload Image</div>
            <div class="message"></div>
            <div id="form-fields"></div>

            <input type="hidden" name="category-id" value="<?php echo $_GET['category-id']; ?>"/>
            <input name="upload" type="file"/>

            <div style="text-align: right"><input type="submit" value="Upload"/></div>
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
            if (label.value() === '' || event.target.elements['upload'].files.length === 0) {
                $('.message').text('Please fill out all required fields');
                event.preventDefault();
            }
        }
    </script>

<?php include_once 'footer.php'; ?>
<?php
include_once 'includes/utils.php';
include_once 'includes/image.php';

$image = get_from_id('image-id');

if (!$image) {
    header('Location: /index.php');
    exit;
}

?>
<?php include_once 'header.php'; ?>

    <div class="form-container">
        <form onsubmit="onSubmit(event)" method="post" action="/api/image_edit.php" enctype="multipart/form-data">
            <div class="title">Edit Image</div>

            <div style="text-align: center">
                <img src="<?php echo get_img_thumb_path($image); ?>"
                     width="<?php echo 200 * $image['AspectRatio']; ?>"
                     height="200"/>
            </div>

            <div class="message"></div>
            <div id="form-fields"></div>

            <input type="hidden" name="image-id" value="<?php echo $image['ImageId']; ?>"/>
            <input type="hidden" name="category-id" value="<?php echo $image['CategoryId']; ?>"/>
            <input name="upload" type="file"/>

            <div style="text-align: right"><input type="submit" value="Update"/></div>
        </form>
    </div>

    <script>
        const form = new UIGroup('form');
        const label = new UITextbox('label', 'Label').value('<?php echo addslashes($image['Label']); ?>');
        const description = new UITextbox('description', 'Description').value('<?php echo addslashes($image['Description']); ?>');

        form.add(label);
        form.add(description);

        form.prependTo('#form-fields');

        function onSubmit(event) {
            if (label.value() === '') {
                $('.message').text('Please fill out all required fields');
                event.preventDefault();
            }
        }
    </script>

<?php include_once 'footer.php'; ?>
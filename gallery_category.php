<?php
include_once 'includes/utils.php';
include_once 'includes/category.php';
include_once 'includes/image.php';
include_once 'includes/user.php';

$category = get_from_id('category-id');
$can_edit = user_is_authorized($category['CategoryId'], AUTH_IMAGE_CREATE);

if (!$category) {
    header('Location: /index.php');
    exit;
}

$images = get_category_images($category['CategoryId']);
?>
<?php include_once 'header.php'; ?>

<div id="page-content-container">
    <div id="page-content" style="text-align: center">
        <h1 style="font-size: 5em"><?php echo $category['Label']; ?></h1>
        <div class="description"><?php echo $category['Description']; ?></div>
        <div class="gallery-filter">
            <a href="/gallery_all.php">Back to gallery</a>
        </div>
        <?php if (count($images) == 0): ?>
            <i>There are no images in this category...</i>
        <?php endif; ?>

        <?php if ($can_edit): ?>
            <a class="link-add" href="/image_upload.php?category-id=<?php echo $category['CategoryId']; ?>">+ Add
                image</a>
        <?php endif; ?>

        <?php foreach ($images as $img): ?>
            <div class="image-thumb">
                <img src="<?php echo get_img_thumb_path($img); ?>" width="<?php echo 200 * $img['AspectRatio']; ?>"
                     height="200"/>
                <div class="label">
                    <b><?php echo $img['Label']; ?></b>
                    <?php if ($can_edit): ?>
                        <span style="margin-left: 20px">
                            <a href="/image_edit.php?image-id=<?php echo $img['ImageId']; ?>">Edit</a>
                            &nbsp;
                            <a href="/image_delete.php?image-id=<?php echo $img['ImageId']; ?>">Delete</a>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="/scripts/lightbox.js"></script>
<?php include_once 'footer.php'; ?>

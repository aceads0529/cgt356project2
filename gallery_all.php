<?php
include_once 'includes/utils.php';
include_once 'includes/category.php';
include_once 'includes/image.php';
include_once 'includes/user.php';

$result = db_connect_query('SELECT * FROM images');
$images = [];

while ($row = $result->fetch_assoc())
    $images[] = $row;

$can_edit = get_active_user()['AcctType'] == 'ADMIN';
$categories = get_all_categories();
?>
<?php include_once 'header.php'; ?>

<div id="page-content-container">
    <div id="page-content" style="text-align: center">
        <h1 style="font-size: 5em">Gallery</h1>
        <div class="description">View all images here, or filter by category below</div>
        <div class="gallery-filter">
            <?php foreach ($categories as $cat): ?>
                <a href="/gallery_category.php?category-id=<?php echo $cat['CategoryId']; ?>"><?php echo $cat['Label']; ?></a>
            <?php endforeach; ?>
        </div>
        <?php foreach ($images as $img): ?>
            <div class="image-thumb">
                <img src="<?php echo get_img_thumb_path($img); ?>" width="<?php echo 200 * $img['AspectRatio']; ?>"
                     height="200" data-src-large="<?php echo get_img_large_path($img); ?>"/>
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

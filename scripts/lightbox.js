$(function () {
    const width = 600;

    $.get('/lightbox.html', function (result) {
        $('body').append(result);

        $('.lightbox-blackout').click(function (event) {
            $(event.currentTarget).removeClass('show');
        });
    });

    $('.image-thumb').click(function (event) {
        let image = $(event.currentTarget).find('img');
        let srcLarge = image.attr('data-src-large');

        $('.lightbox-blackout').addClass('show');
        $('.lightbox-blackout img').attr('src', image.attr('data-src-large'));
    });
});
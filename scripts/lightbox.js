$(function () {
    $.get('/lightbox.html', function (result) {
        $('body').append(result);

        $('.lightbox-blackout').click(function (event) {
            $(event.currentTarget).removeClass('show');
        });
    });

    $('.image-thumb img').click(function (event) {
        let image = $(event.currentTarget);

        let source = image.attr('data-src-large');
        let filename = source.substr(source.lastIndexOf('/') + 1);

        $('.lightbox-blackout').addClass('show');
        $('.lightbox-blackout img').attr('src', source);

        $('.lightbox-blackout .download').attr('download', filename).attr('href', source);
    });
});
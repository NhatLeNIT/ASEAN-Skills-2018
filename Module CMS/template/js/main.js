jQuery(document).ready(function ($) {
    $('#bar').click(function () {
        $('#menu ul, #search').toggleClass('active');
    });

    $('.show-all').click(function () {
        let id = $(this).data('id');
        $('#photo-' + id).addClass('active');
    });
    $('.btn-close').click(function () {
        $('.photo-wrapper').removeClass('active');
    });
    $('.photo-box div').click(function () {
        let img = $(this).find('img').attr('src');
        let desc = $(this).find('p').html();
        let id = $(this).data('id');

        $('.photo-box div').removeClass('active');
        $(this).addClass('active');

        $('#' + id).find('img').last().attr('src', img);
        $('#' + id).find('p').html(desc);
    });
});
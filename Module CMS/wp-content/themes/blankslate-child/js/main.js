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

//    url
    let adminURL = new URL(window.location.href);
    let catID = adminURL.searchParams.get('cat');
    if (catID == 2) {
        $('#adminmenu').find('.wp-has-current-submenu').removeClass('wp-has-current-submenu').addClass('wp-not-current-submenu');
        $('#toplevel_page_food-recipe').addClass('current').removeClass('wp-not-current-submenu');
    }
    if (catID == 3) {
        $('#adminmenu').find('.wp-has-current-submenu').removeClass('wp-has-current-submenu').addClass('wp-not-current-submenu');
        $('#toplevel_page_food-blog').addClass('current').removeClass('wp-not-current-submenu');
    }

    $('.btn-like').click(function () {
        let dataObj = {
            action: 'tfg_like',
            postID: $(this).data('id')
        }
        $.ajax({
            url: ajax_var.url,
            method: 'POST',
            data: dataObj,
            success: function (data) {
                $('#single .count').html('Like: ' + data);
            }
        })
    });
});
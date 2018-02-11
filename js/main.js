$(function () {
    $messageList = $('.flash-message');

    if ($messageList.length) {
        setTimeout(function () {
            $messageList.slideUp(200);
        }, 3000);
    }

    var follow = $('a.follow');
    follow.on('click', function (e) {
        e.preventDefault();
        var userId = follow.attr('data-user_id');
        var productId = follow.attr('data-product_id');
        $.ajax({
            url: 'productPage.php',
            type: 'POST',
            dataType: 'json',
            data: {data_user_id: userId, data_product_id: productId},
            complete: function () {
                $('div.vote').html(
                    '<strong class="added">Obserwujesz tą ofertę!</strong><br/>' +
                    '<a href="followedProductPage.php">Przejdź do listy obserwowanych ofert</a>');
            }
        });
    });
});
$(function () {
    $messageList = $('.flash-message');

    if ($messageList.length) {
        setTimeout(function () {
            $messageList.slideUp(200);
        }, 3000);
    }
});
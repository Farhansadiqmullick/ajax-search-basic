; (function () {
    $(document).ready(function () {
        $("#keyword").on("keyup", function (e) {
            e.preventDefault();
            $.ajax({
                url: fetch.ajaxurl,
                type: 'post',
                data: {
                    action: 'ajax-search-handle',
                    keyword: $('#keyword').val()
                },
                success: function (data) {
                    $('#datafetch').html(data);
                }
            });

        });
    });
})(jQuery);
; (function () {
    $(document).ready(function () {
        $("input#keyword").keyup(function () {
            console.log(this);
            if ($(this).val().length > 2) {
                $("#datafetch").show();
            } else {
                $("#datafetch").hide();
            }
        });
    });
})(jQuery);
$(document).ready(function () {

    $('#analyser').on('submit', function (e) {
        e.preventDefault();
        $(".results").slideUp('slow', 'swing');
        var url = $('#site_url').val();
        $.ajax({
            type: "GET",
            url: 'api/check',
            data: {url: url},
            success: function (result) {

                $.each(result, function (pkey, data) {
                    $.each(data, function (key, val) {
                        if (key == 'passed' && !val) {
                            result[pkey][key] = 'failed warning';
                        } else if (key == 'passed') {
                            result[pkey][key] = 'passed';
                        }

                    });
                });

                $('.results > ul').loadTemplate($('#template'), result);

                $(".results").slideDown('slow', 'swing');

            }, error: function (XMLHttpRequest, textStatus, errorThrown) {
                var data = {
                    "passed": 'failed warning',
                    "message": "Something went wrong!! Please try again!",
                    "level": "Warning"
                };

                $('.results > ul').loadTemplate($('#template'), data);
            }
        });
    });
});


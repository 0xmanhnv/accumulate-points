jQuery(document).ready(function($) {
    var contact = {
        init: function () {
            contact.registerEvent();
        },
        registerEvent: function () {
            $('#form-look-points form').off('submit').on('submit', function (e) {
                e.preventDefault();
                $('#form-look-points #txtmess').hide();
                $('#form-look-points .loader').css('display', 'inline-block');

                var url = "/accumulate-points?action_point=point_look&phone_number=" + $('#form-look-points form #phone_number').val();

                $.ajax({
                    url: url,
                    type: 'GET',
                    processData: false,
                    contentType: false,
                    caches: false,
                    success: function (response) {
                        var resp = JSON.parse(response);

                        if( typeof resp.errors != 'undefined' && resp.errors != true) {
                            var point = "Điểm của bạn là: " + resp.data.point;
                            $('#form-look-points #txtmess').show();
                            $('#form-look-points #txtmess').css('visibility', 'visible');
                            
                            $('#form-look-points #txtmess').css('color', 'green');
                            $('#form-look-points #txtmess').html(point);
                            // contact.resetForm();
                        }else {
                            $('#form-look-points #txtmess').show();
                            $('#form-look-points #txtmess').css('visibility', 'visible');
                            $('#form-look-points #txtmess').css('color', 'red');
                            $('#form-look-points #txtmess').html("Số điện thoại đã được chuyển về 10 số, vui lòng kiểm tra lại!");
                        }
                        $('#form-look-points .loader').css('display', 'none');
                    },
                    error: function (error) {
                        $('#form-look-points #txtmess').show();
                        $('#form-look-points #txtmess').css('visibility', 'visible');
                        $('#form-look-points #txtmess').css('color', 'red');
                        $('#form-look-points #txtmess').html("Số điện thoại đã được chuyển về 10 số, vui lòng kiểm tra lại!");
                        $('#form-look-points .loader').css('display', 'none');
                    }
                });
            });
        },
        resetForm: function () {
            $('#form-look-points form')[0].reset();
        }
    }
    contact.init();
});
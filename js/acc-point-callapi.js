jQuery(document).ready(function($) {
    var contact = {
        init: function () {
            contact.registerEvent();
        },
        registerEvent: function () {
            $('#form-accumulate-points form').off('submit').on('submit', function (e) {
                e.preventDefault();
                $('#form-accumulate-points #txtmess').css('display', 'none');
                $('#form-accumulate-points .loader').css('display', 'inline-block');

                var data = new FormData();
                data.append( 'phone_number', $('#form-accumulate-points form #phone_number').val());
                data.append( 'code', $('#form-accumulate-points form #code').val());
                data.append( 'submit', 'submit');

                $.ajax({
                    url: '/accumulate-points?action_point=acc_point',
                    type: 'POST',
                    data: data,
                    processData: false,
                    contentType: false,
                    caches: false,
                    success: function (response) {
                        var resp = JSON.parse(response);

                        if( typeof resp.errors != 'undefined' && resp.errors != true) {
                            $('#form-accumulate-points #txtmess').css('display', 'inline-block');
                            $('#form-accumulate-points #txtmess').css('visibility', 'visible');
                            $('#form-accumulate-points #txtmess').css('color', 'green');
                            $('#form-accumulate-points #txtmess').html("Tích điểm thành công!");
                            contact.resetForm();
                        }else {
                            $('#form-accumulate-points #txtmess').css('display', 'inline-block');
                            $('#form-accumulate-points #txtmess').css('visibility', 'visible');
                            $('#form-accumulate-points #txtmess').css('color', 'red');
                            $('#form-accumulate-points #txtmess').html("Tích điểm thất bại!");
                        }
                        $('#form-accumulate-points .loader').css('display', 'none');
                    },
                    error: function (error) {
                        $('#form-accumulate-points #txtmess').css('display', 'inline-block');
                        $('#form-accumulate-points #txtmess').css('visibility', 'visible');
                        $('#form-accumulate-points #txtmess').css('color', 'red');
                        $('#form-accumulate-points #txtmess').html("Tích điểm thất bại!");
                        $('#form-accumulate-points .loader').css('display', 'none');
                    }
                });
            });
        },
        resetForm: function () {
            $('#form-accumulate-points form')[0].reset();
        }
    }
    contact.init();
});
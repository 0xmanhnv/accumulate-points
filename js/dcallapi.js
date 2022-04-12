var contact = {
    init: function () {
        contact.registerEvent();
    },
    registerEvent: function () {
        $('#dbtnSend').off('click').on('click', function () {
            var username = $('#dphone').val();
            var maqua = $('#dmaqua').val();
            var address = $('#daddress').val();
            var pass = $('#dpassword').val();
            var commandcode = $('#dcommandcode').val();
            $('#dloader').css('display', 'inline-block');

            $.ajax({
                url: '/Home/CallApi',
                type: 'POST',
                dataType: 'json',
                data: {
                    username: username,
                    pass: pass,
                    commandcode: commandcode,
                    maqua: maqua,
                    address: address
                },
                success: function (response) {
                    if (response.success) {
                        $('#dtxtmess').css('display', 'inline-block');
                        $('#dtxtmess').css('visibility', 'unset');
                        $('#dtxtmess').html(response.message);
                        $('#dloader').css('display', 'none');
                        
                    }
                    contact.resetForm();
                },
            });
        });
    },
    resetForm: function () {
        $('#dform-contact')[0].reset();
    }
}
contact.init();
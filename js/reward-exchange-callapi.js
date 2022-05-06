jQuery(document).ready(function ($) {
    var contact = {
        init: function () {
            contact.registerEvent();
            contact.checkPoint()
            contact.getGifts()
        },

        getGifts: function () {
            var url = "/accumulate-points?action_point=get_gifs"
            $.ajax({
                url: url,
                type: 'GET',
                processData: false,
                contentType: false,
                success: function (response) {
                    var resp = JSON.parse(response);
                    var listRewardExchange = resp.data.gifts
                    console.log(listRewardExchange)
                    $.each( listRewardExchange, function( index, value ){
                        console.log(value.gifts)
                        var htmlGifts = '';
                        $('#ddiem').append(`<option value="${value.point}">Đổi ${value.point} điểm</option>`);

                        for (let i = 0; i < value.gifts.length; i++) {
                            htmlGifts += `<option class="${value.point}" value="${value.gifts[i]}">Đổi ${value.gifts[i]} hộp</option>`
                        }
                        // value.gifts.forEach(function (element) {
                        //     htmlGifts += `<option class="${value.point}" value="${element}">Đổi ${element} hộp</option>`
                        // });
                        $('#dmaqua').append(htmlGifts);
                    });
                },
                error: function (error) {
                }
            });
        },

        checkPoint: function () {
            $("#dmaqua").children('option:gt(0)').hide();
            $("#ddiem").change(function () {
                $('#dmaqua option').prop('selected', function () {
                    return this.defaultSelected;
                });
                $("#dmaqua").children('option').hide();
                $("#dmaqua").children("option[class^=" + $(this).val() + "]").show();
            })
        },

        registerEvent: function () {
            $('#reward-exchange-form').off('submit').on('submit', function (e) {
                e.preventDefault();
                $('#reward-exchange-form #txtmess').hide();
                $('#reward-exchange-form .loader').css('display', 'inline-block');

                var data = new FormData();
                data.append('phone_number', $('#reward-exchange-form #phone_number').val());
                data.append('user', $('#reward-exchange-form #name').val());
                data.append('address', $('#reward-exchange-form #address').val());
                data.append('point', $('#reward-exchange-form #ddiem').val());
                data.append('gift', $('#reward-exchange-form #dmaqua').val());
                data.append('submit', 'submit');

                var url = "/accumulate-points?action_point=reward_exchange"

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        var resp = JSON.parse(response);
                        console.log(resp.errors)

                        if (typeof resp.errors != 'undefined' && resp.errors != true) {
                            $('#reward-exchange-form #txtmess').show();
                            $('#reward-exchange-form #txtmess').css('visibility', 'visible');

                            $('#reward-exchange-form #txtmess').css('color', 'green');
                            $('#reward-exchange-form #txtmess').html('Đã gửi yêu cầu đổi quà!');
                            // contact.resetForm();
                        } else {
                            $('#reward-exchange-form #txtmess').show();
                            $('#reward-exchange-form #txtmess').css('visibility', 'visible');
                            $('#reward-exchange-form #txtmess').css('color', 'red');
                            $('#reward-exchange-form #txtmess').html("Vui lòng điền đầy đủ thông tin!");
                        }
                        $('#reward-exchange-form .loader').css('display', 'none');
                    },
                    error: function (error) {
                        errorCode = JSON.parse(error.responseText).code
                        if (errorCode == 1) {
                            var messTxt = "Bạn không đủ điểm!"
                        } else {
                            var messTxt = "Vui lòng điền đầy đủ thông tin!"
                        }
                        $('#reward-exchange-form #txtmess').show();
                        $('#reward-exchange-form #txtmess').css('visibility', 'visible');
                        $('#reward-exchange-form #txtmess').css('color', 'red');
                        $('#reward-exchange-form #txtmess').html(messTxt);
                        $('#reward-exchange-form .loader').css('display', 'none');
                    }
                });
            });
        },
        resetForm: function () {
            $('#reward-exchange-form form')[0].reset();
        }
    }
    contact.init();
});
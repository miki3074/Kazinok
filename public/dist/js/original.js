function updateBalance(start, end) {
    var el = document.getElementById('userBalance');
    od = new Odometer({el: el, value: start,});
    od.update(end);
}

function updateBalanceMobile(start, end) {
    var el = document.getElementById('userBalanceMobile');
    od = new Odometer({el: el, value: start,});
    od.update(end);
}

function createGameMines() {
    $.ajax({
        type: 'POST',
        url: 'action.php',
        beforeSend: function () {
            $("#checkMines").hide();
            $("#errorMines").hide();
            $("#minesHashBlock").hide();
            $("#buttonStartMines").addClass('disabled');
        },
        data: {
            type: "createGameMines",
            sid: Cookies.get('sid'),
            bomb: $(".js-range-slider").val(),
            amount: $("#minesAmount").val()
        },
        success: function (data) {
            $("#buttonStartMines").removeClass('disabled');
            var obj = jQuery.parseJSON(data);
            if ('error' in obj) {
                $("#errorMines").show();
                return $("#errorMines").html(obj.error.text);
            }
            $("#mbl").removeClass("disabled");
            $("#mbl").html('<div class="d-flex justify-content-center "><div class="wd-65 ht-65 bg-gray-100 bd bd-1  rounded-lg mines-sq text-center" onclick="chGameMines($(this),1)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),2)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),3)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),4)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),5)"></div></div><div class="d-flex justify-content-center mg-t-10"><div class="wd-65 ht-65 bg-gray-100 bd bd-1  rounded-lg mines-sq text-center" onclick="chGameMines($(this),6)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),7)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),8)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),9)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),10)"></div></div><div class="d-flex justify-content-center mg-t-10"><div class="wd-65 ht-65 bg-gray-100 bd bd-1  rounded-lg mines-sq text-center" onclick="chGameMines($(this),11)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),12)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),13)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),14)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),15)"></div></div><div class="d-flex justify-content-center mg-t-10"><div class="wd-65 ht-65 bg-gray-100 bd bd-1  rounded-lg mines-sq text-center" onclick="chGameMines($(this),16)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),17)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),18)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),19)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),20)"></div></div><div class="d-flex justify-content-center mg-t-10"><div class="wd-65 ht-65 bg-gray-100 bd bd-1  rounded-lg mines-sq text-center" onclick="chGameMines($(this),21)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),22)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),23)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),24)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),25)"></div></div>');
            $("#mbl").fadeOut("100");
            $('#userBalance').attr('myBalance', obj.success.new_balance);
            updateBalance(parseInt($("#userBalance").html()), obj.success.new_balance);
            updateBalanceMobile(parseInt($("#userBalanceMobile").html()), obj.success.new_balance);
            $("#mbl").fadeIn("300");
            $("#minesRate").html(obj.success.sl);
            $("#mines").addClass("disabled");
            $("#minesHashBlock").show();
            $("#minesHash").html(obj.success.hash);
            $("#buttonStartMines").hide();
            $("#buttonFinishMines").show().addClass("disabled");
            $("#buttonFinishMines").html("Выберите ячейку");
        }
    });
}

function getRandomMine() {
    var list = document.getElementsByClassName('mines-sq');
    var item = list[3].click();
}

function finishMines() {
    $.ajax({
        type: 'POST', url: 'action.php', beforeSend: function () {
            $("#buttonFinishMines").addClass("disabled");
            $("#buttonFinishMinesMobile").addClass("disabled");
        }, data: {type: "finishMines", sid: Cookies.get('sid')}, success: function (data) {
            var obj = jQuery.parseJSON(data);
            if ('success' in obj) {
                $("#buttonFinishMines").removeClass("disabled").hide();
                $("#buttonFinishMinesMobile").removeClass("disabled").hide();
                $("#buttonStartMines").show();
                $("#buttonStartMinesMobile").show();
                $('#userBalance').attr('myBalance', obj.success.new_balance);
                updateBalance(parseInt($("#userBalance").html()), obj.success.new_balance);
                updateBalanceMobile(parseInt($("#userBalanceMobile").html()), obj.success.new_balance);
                $("#mbl").addClass("disabled");
                $("#mines").removeClass("disabled");
                $("#minesMobile").removeClass("disabled");
                $("#mbl").html('<div class="d-flex justify-content-center "><div class="wd-65 ht-65 bg-gray-100 bd bd-1  rounded-lg mines-sq text-center" onclick="chGameMines($(this),1)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),2)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),3)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),4)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),5)"></div></div><div class="d-flex justify-content-center mg-t-10"><div class="wd-65 ht-65 bg-gray-100 bd bd-1  rounded-lg mines-sq text-center" onclick="chGameMines($(this),6)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),7)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),8)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),9)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),10)"></div></div><div class="d-flex justify-content-center mg-t-10"><div class="wd-65 ht-65 bg-gray-100 bd bd-1  rounded-lg mines-sq text-center" onclick="chGameMines($(this),11)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),12)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),13)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),14)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),15)"></div></div><div class="d-flex justify-content-center mg-t-10"><div class="wd-65 ht-65 bg-gray-100 bd bd-1  rounded-lg mines-sq text-center" onclick="chGameMines($(this),16)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),17)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),18)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),19)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),20)"></div></div><div class="d-flex justify-content-center mg-t-10"><div class="wd-65 ht-65 bg-gray-100 bd bd-1  rounded-lg mines-sq text-center" onclick="chGameMines($(this),21)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),22)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),23)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),24)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),25)"></div></div>');
                getMinesRate();
                $("#minesHashBlock").hide();
                $("#myresponse").prepend(obj.live_table);
                $('#myresponse').children().slice(20).remove();
            }
        }
    });
}

function chGameMines(that, id) {
    $.ajax({
        type: 'POST', url: 'action.php', beforeSend: function () {
            $("#checkMines").hide();
            $("#mbl").addClass("disabled");
            $(that).html('<div role="status" style="color:#dbe0e9" class="spinner-grow-sm spinner-grow mg-t-25"><span class="sr-only">Loading...</span></div>');
        }, data: {type: "chGameMines", sid: Cookies.get('sid'), id: id}, success: function (data) {
            $("#mbl").removeClass("disabled");
            var obj = jQuery.parseJSON(data);
            if ('win' in obj) {
                $(that).html(obj.win.html);
                $("#buttonFinishMines").removeClass("disabled").html("Забрать <u>" + obj.win.finish_amount + "</u>");
                $("#buttonFinishMinesMobile").removeClass("disabled").html("Забрать <u>" + obj.win.finish_amount + "</u>");
                $("#minesRate").html(obj.win.sl);
                if (obj.win.nsl == 1) {
                    $("#nsl").click();
                }
                $(that).addClass("mines-sq-success").removeClass("mines-sq");
                if (obj.win.finish == 1) {
                    $("#buttonFinishMines").removeClass("disabled").hide();
                    $("#buttonFinishMinesMobile").removeClass("disabled").hide();
                    $("#buttonStartMines").show();
                    $("#buttonStartMinesMobile").show();
                    $('#userBalance').attr('myBalance', obj.win.new_balance);
                    updateBalance(parseInt($("#userBalance").html()), obj.win.new_balance);
                    updateBalanceMobile(parseInt($("#userBalanceMobile").html()), obj.win.new_balance);
                    $("#mbl").addClass("disabled");
                    $("#mines").removeClass("disabled");
                    $("#minesMobile").removeClass("disabled");
                    $("#mbl").html('<div class="d-flex justify-content-center "><div class="wd-65 ht-65 bg-gray-100 bd bd-1  rounded-lg mines-sq text-center" onclick="chGameMines($(this),1)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),2)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),3)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),4)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),5)"></div></div><div class="d-flex justify-content-center mg-t-10"><div class="wd-65 ht-65 bg-gray-100 bd bd-1  rounded-lg mines-sq text-center" onclick="chGameMines($(this),6)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),7)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),8)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),9)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),10)"></div></div><div class="d-flex justify-content-center mg-t-10"><div class="wd-65 ht-65 bg-gray-100 bd bd-1  rounded-lg mines-sq text-center" onclick="chGameMines($(this),11)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),12)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),13)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),14)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),15)"></div></div><div class="d-flex justify-content-center mg-t-10"><div class="wd-65 ht-65 bg-gray-100 bd bd-1  rounded-lg mines-sq text-center" onclick="chGameMines($(this),16)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),17)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),18)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),19)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),20)"></div></div><div class="d-flex justify-content-center mg-t-10"><div class="wd-65 ht-65 bg-gray-100 bd bd-1  rounded-lg mines-sq text-center" onclick="chGameMines($(this),21)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),22)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),23)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),24)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),25)"></div></div>');
                    getMinesRate();
                }
            }
            if ('lose' in obj) {
                $("#mines").removeClass("disabled");
                $("#minesMobile").removeClass("disabled");
                $("#mbl").html(obj.lose.html);
                $('#checkBet').attr('gameid', obj.lose.check_bet);
                $("#checkMinesBlocks").html(obj.lose.html);
                $(".recent-bl").removeClass('bd-primary').addClass("lose-bl");
                $("#checkMines").show();
                $("#buttonFinishMines").hide();
                $("#buttonFinishMinesMobile").hide();
                $("#buttonStartMines").show();
                $("#buttonStartMinesMobile").show();
            }
            $("#myresponse").prepend(obj.live_table);
            $('#myresponse').children().slice(20).remove();
        }
    });
}

function bet(type) {
    if ($('#userBalance').html() < $('#diceGameAmount').val()) {
        $('#error_bet').html('Недостаточно средств');
        return $('#error_bet').show();
    }
    if ($('#diceGamePercent').val() > 95 || $('#diceGamePercent').val() < 1) {
        $('#error_bet').html('% Шанс от 1 до 95');
        return $('#error_bet').show();
    }
    $.ajax({
        type: 'POST',
        url: 'action.php',
        beforeSend: function () {
            $('#checkBet').css('display', 'none');
            $('#error_bet').css('display', 'none')
            $('#succes_bet').css('display', 'none')
            $('#betLoad').css('display', '');
            $('#buttonMax').css('pointer-events', 'none');
            $('#buttonMin').css('pointer-events', 'none');
        },
        data: {
            type: type,
            betSize: $('#diceGameAmount').val(),
            betPercent: $('#diceGamePercent').val(),
            sid: Cookies.get('sid'),
            hid: Cookies.get('hid')
        },
        success: function (data) {
            $('#buttonMax').css('pointer-events', '');
            $('#buttonMin').css('pointer-events', '');
            $('#betLoad').css('display', 'none');
            var obj = jQuery.parseJSON(data);
            if ('success' in obj) {
                $("#myresponse").prepend(obj.live_table);
                $('#myresponse').children().slice(20).remove();
                if (obj.success.type == 'win') {
                    $('#succes_bet').css('display', '');
                    $("#succes_bet").html("Выиграли " + obj.success.profit);
                }
                if (obj.success.type == 'lose') {
                    $('#checkBet').css('display', 'flex');
                    $('#checkBet').attr('gameid', obj.success.check_bet);
                    $('#error_bet').css('display', '');
                    $("#error_bet").html("Выпало " + obj.success.number);
                }
                $("#hashBet").html(obj.success.hash);
                Cookies.set('hid', obj.success.hid, {path: '/', secure: true});
                $('#hashBet').attr('hid', obj.success.hid);
                sss();
                $('#userBalance').attr('myBalance', obj.success.new_balance);
                updateBalance(obj.success.balance, obj.success.new_balance);
                updateBalanceMobile(obj.success.balance, obj.success.new_balance);
            }
            if ('error' in obj) {
                $('#error_bet').html(obj.error.text);
                return $('#error_bet').css('display', '');
            }
            if ('new' in obj) {
                $("#hashBet").html(obj.new.hash);
                Cookies.set('hid', obj.new.hid, {path: '/', secure: true});
                $('#hashBet').attr('hid', obj.new.hid);
                sss();
                $('#error_bet').html(obj.new.text);
                $('#error_bet').css('display', '');
            }
        }
    });
}

function sss() {
    $('#hashBet').fadeOut('slow', function () {
        $('#hashBet').fadeIn('slow', function () {
        });
    });
}

function validateDiceGameAmount(a) {
    if (a.value > 1000000) {
        a.value = 1000000;
    }
    return a.value = a.value.replace(/[,]/g, '.').replace(/[^\d,.]*/g, '').replace(/([,.])[,.]+/g, '$1').replace(/^[^\d]*(\d+([.,]\d{0,2})?).*$/g, '$1');
}

function validateDiceGamePercent(a) {
    if (a.value > 95) {
        a.value = 95;
    }
    a.value = a.value.replace(/[,]/g, '.').replace(/[^\d,.]*/g, '').replace(/([,.])[,.]+/g, '$1').replace(/^[^\d]*(\d+([.,]\d{0,2})?).*$/g, '$1');
}

function updateResultSize() {
    if ($('#diceGameAmount').val() > 1000000) {
        $('#diceGameAmount').val(1000000);
    }
    $('#diceResult').html(((100 / $('#diceGamePercent').val()) * $('#diceGameAmount').val()).toFixed(2));
    $('#diceResultMobile').html(((100 / $('#diceGamePercent').val()) * $('#diceGameAmount').val()).toFixed(2));
    $('#minButton').html(Math.floor(($('#diceGamePercent').val() / 100) * 999999));
    $('#maxButton').html(999999 - Math.floor(($('#diceGamePercent').val() / 100) * 999999));
}

function getMinesRate() {
    if ($("#minesAmount").prop("value") > 1000000) {
        $("#minesAmount").val("1000000");
        $("#minesAmountMobile").val("1000000");
    }
    $.ajax({
        type: 'POST',
        url: 'action.php',
        beforeSend: function () {
        },
        data: {
            type: "getMinesRate",
            bomb: $(".js-range-slider").prop("value"),
            amount: $("#minesAmount").prop("value")
        },
        success: function (data) {
            $("#minesRate").html(data);
        }
    });
}

function getMinesRateMobile() {
    if ($("#minesAmountMobile").prop("value") > 1000000) {
        $("#minesAmount").val("1000000");
        $("#minesAmountMobile").val("1000000");
    }
    $(".js-range-slider").data("ionRangeSlider").update({from: $("#countBombMobile").prop("value")});
    $("#minesAmount").val($("#minesAmountMobile").prop("value"));
    $.ajax({
        type: 'POST',
        url: 'action.php',
        beforeSend: function () {
        },
        data: {
            type: "getMinesRate",
            bomb: $("#countBombMobile").prop("value"),
            amount: $("#minesAmountMobile").prop("value")
        },
        success: function (data) {
            $("#minesRate").html(data);
        }
    });
}

function getMoreWithdraws() {
    $.ajax({
        type: 'POST',
        url: 'action.php',
        beforeSend: function () {
            $('#gmw a').hide();
            $('#gmw div').show()
        },
        data: {type: "getMoreWithdraws", sid: Cookies.get('sid'), of: parseInt($("#withdrawOf").val()) + 7},
        success: function (data) {
            $('#gmw a').show();
            $('#gmw div').hide();
            $("#withdrawOf").val(parseInt($("#withdrawOf").val()) + 7);
            $("#wtBl").html(data);
        }
    });
}

function createGameMinesMobile() {
    $.ajax({
        type: 'POST',
        url: 'action.php',
        beforeSend: function () {
            $("#checkMines").hide();
        },
        data: {
            type: "createGameMines",
            sid: Cookies.get('sid'),
            bomb: $("#countBombMobile").prop("value"),
            amount: $("#minesAmountMobile").prop("value")
        },
        success: function (data) {
            var obj = jQuery.parseJSON(data);
            if ('error' in obj) {
                $("#errorMines").show();
                return $("#errorMines").html(obj.error.text);
            }
            $("#errorMines").hide();
            $("#mbl").removeClass("disabled");
            $("#mbl").html('<div class="d-flex justify-content-center "><div class="wd-65 ht-65 bg-gray-100 bd bd-1  rounded-lg mines-sq text-center" onclick="chGameMines($(this),1)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),2)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),3)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),4)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),5)"></div></div><div class="d-flex justify-content-center mg-t-10"><div class="wd-65 ht-65 bg-gray-100 bd bd-1  rounded-lg mines-sq text-center" onclick="chGameMines($(this),6)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),7)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),8)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),9)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),10)"></div></div><div class="d-flex justify-content-center mg-t-10"><div class="wd-65 ht-65 bg-gray-100 bd bd-1  rounded-lg mines-sq text-center" onclick="chGameMines($(this),11)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),12)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),13)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),14)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),15)"></div></div><div class="d-flex justify-content-center mg-t-10"><div class="wd-65 ht-65 bg-gray-100 bd bd-1  rounded-lg mines-sq text-center" onclick="chGameMines($(this),16)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),17)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),18)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),19)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),20)"></div></div><div class="d-flex justify-content-center mg-t-10"><div class="wd-65 ht-65 bg-gray-100 bd bd-1  rounded-lg mines-sq text-center" onclick="chGameMines($(this),21)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),22)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),23)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),24)"></div><div class="wd-65 ht-65 bg-gray-100 bd bd-1 mg-l-10 rounded-lg mines-sq text-center" onclick="chGameMines($(this),25)"></div></div>');
            $("#mbl").fadeOut("100");
            $("#mbl").fadeIn("300");
            $('#userBalance').attr('myBalance', obj.success.new_balance);
            updateBalance(parseInt($("#userBalance").html()), obj.success.new_balance);
            updateBalanceMobile(parseInt($("#userBalanceMobile").html()), obj.success.new_balance);
            $("#minesRate").html(obj.success.sl);
            $("#mines").addClass("disabled");
            $("#minesMobile").addClass("disabled");
            $("#buttonStartMinesMobile").hide();
            $("#buttonFinishMinesMobile").show().addClass("disabled");
            $("#buttonFinishMinesMobile").html("Выберите ячейку");
        }
    });
}

function card(type) {
    $.ajax({
        type: 'POST', url: 'action.php', beforeSend: function () {
            $('.gameinc').removeClass('present');
            $('#mainView').hide();
            $('#loadPage').show();
        }, data: {type: type, sid: Cookies.get('sid')}, success: function (data) {
            $('#loadPage').hide();
            $('#mainView').show();
            $('#mainView').html(data);
            $('[data-toggle="tooltip"]').tooltip();
            updateResultSize();
            $(".js-range-slider").ionRangeSlider({
                skin: "round",
                min: 2,
                max: 24,
                from: 3,
                grid: true,
                onChange: function (data) {
                    getMinesRate();
                }
            });
            $('#carousel').on('slid.bs.carousel', function (e) {
            });
            if (type == 'cardWithdraw') {
                $.ajax({
                    type: 'POST', url: 'action.php', beforeSend: function () {
                        $('#gmw a').hide();
                        $('#gmw div').show()
                    }, data: {type: "getMoreWithdraws", sid: Cookies.get('sid'), of: 7}, success: function (data) {
                        $('#gmw a').show();
                        $('#gmw div').hide();
                        $("#wtBl").html(data);
                    }
                });
            }
        }
    });
}

function route() {
    $("#backArr").hide();
    switch (window.location.pathname.replace("/1", "")) {
        case "/dice":
            card("cardDice");
            $(".nav-item").removeClass("active");
            $('.gameinc').removeClass('present');
            $("#menudice").addClass('present');
            $("#backArr").show();
            break;
        case "/mines":
            card("cardMines");
            $(".nav-item").removeClass("active");
            $('.gameinc').removeClass('present');
            $("#menumines").addClass('present');
            $("#backArr").show();
            break;
        case "/withdraw":
            card("cardWithdraw");
            $(".nav-item").removeClass("active");
            $("#backArr").hide();
            break;
        case "/ref":
            $(".nav-item").removeClass("active");
            $('*[datait="ref"]').addClass("active");
            card("cardRefereal");
            $("#backArr").hide();
            break;
        case "/about":
            $(".nav-item").removeClass("active");
            $('*[datait="about"]').addClass("active");
            $("#backArr").hide();
            break;
        case "/faq":
            $(".nav-item").removeClass("active");
            $('*[datait="faq"]').addClass("active");
            $("#backArr").hide();
            break;
        case "/terms":
            $(".nav-item").removeClass("active");
            $("#backArr").hide();
            break;
        case "/policy":
            $(".nav-item").removeClass("active");
            $("#backArr").hide();
            break;
        case "/bonus":
            $(".nav-item").removeClass("active");
            $('*[datait="bonus"]').addClass("active");
            $("#backArr").hide();
            break;
        case "/support":
            $(".nav-item").removeClass("active");
            $('*[datait="support"]').addClass("active");
            break;
        default:
    }
}

function removeWithdraw(id) {
    $.ajax({
        type: 'POST',
        url: 'action.php',
        data: {type: "removeWithdraw", sid: Cookies.get('sid'), id: id},
        success: function (data) {
            var obj = jQuery.parseJSON(data);
            if ('success' in obj) {
                $.ajax({
                    type: 'POST', url: 'action.php', beforeSend: function () {
                        $('#gmw a').hide();
                        $('#gmw div').show()
                    }, data: {type: "getMoreWithdraws", sid: Cookies.get('sid'), of: 7}, success: function (data) {
                        $('#gmw a').show();
                        $('#gmw div').hide();
                        $("#wtBl").html(data);
                    }
                });
                updateBalance(obj.success.balance, obj.success.new_balance);
                updateBalanceMobile(obj.success.balance, obj.success.new_balance);
            }
        }
    });
}

$(function () {
    'use strict'
    feather.replace();
    if (window.matchMedia('(max-width: 991px)').matches) {
        const psNavbar = new PerfectScrollbar('#navbarMenu', {suppressScrollX: true});
    }

    function showNavbarActiveSub() {
        if (window.matchMedia('(max-width: 991px)').matches) {
            $('#navbarMenu .active').addClass('show');
        } else {
            $('#navbarMenu .active').removeClass('show');
        }
    }

    showNavbarActiveSub()
    $(window).resize(function () {
        showNavbarActiveSub()
    })
    $('body').append('<div class="backdrop"></div>');
    $('.navbar-menu .with-sub .nav-link').on('click', function (e) {
        e.preventDefault();
        $(this).parent().toggleClass('show');
        $(this).parent().siblings().removeClass('show');
        if (window.matchMedia('(max-width: 991px)').matches) {
            psNavbar.update();
        }
    })
    $(document).on('click touchstart', function (e) {
        e.stopPropagation();
        if (window.matchMedia('(min-width: 992px)').matches) {
            var navTarg = $(e.target).closest('.navbar-menu .nav-item').length;
            if (!navTarg) {
                $('.navbar-header .show').removeClass('show');
            }
        }
    })
    $('#mainMenuClose').on('click', function (e) {
        e.preventDefault();
        $('body').removeClass('navbar-nav-show');
    });
    $('#sidebarMenuOpen').on('click', function (e) {
        e.preventDefault();
        $('body').addClass('sidebar-show');
    })
    $('#navbarSearch').on('click', function (e) {
        e.preventDefault();
        $('.navbar-search').addClass('visible');
        $('.backdrop').addClass('show');
    })
    $('#navbarSearchClose').on('click', function (e) {
        e.preventDefault();
        $('.navbar-search').removeClass('visible');
        $('.backdrop').removeClass('show');
    })
    if ($('#sidebarMenu').length) {
        const psSidebar = new PerfectScrollbar('#sidebarMenu', {suppressScrollX: true});
        $('.sidebar-nav .with-sub').on('click', function (e) {
            e.preventDefault();
            $(this).parent().toggleClass('show');
            psSidebar.update();
        })
    }
    $('#mainMenuOpen').on('click touchstart', function (e) {
        e.preventDefault();
        $('body').addClass('navbar-nav-show');
    })
    $('#sidebarMenuClose').on('click', function (e) {
        e.preventDefault();
        $('body').removeClass('sidebar-show');
    })
    $(document).on('click touchstart', function (e) {
        e.stopPropagation();
        if (!$(e.target).closest('.burger-menu').length) {
            var sb = $(e.target).closest('.sidebar').length;
            var nb = $(e.target).closest('.navbar-menu-wrapper').length;
            if (!sb && !nb) {
                if ($('body').hasClass('navbar-nav-show')) {
                    $('body').removeClass('navbar-nav-show');
                } else {
                    $('body').removeClass('sidebar-show');
                }
            }
        }
    });
})

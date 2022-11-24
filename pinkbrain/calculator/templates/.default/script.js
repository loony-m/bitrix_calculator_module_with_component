var calc = {
    'price_truba': '',
    'r1': '',
    'r2': '',
    'r3': '',
    'price_du25': 0,
    'price_otvoda57': 0,
    'price_otvoda89': 0,
    'price_dnisha': 0, /*цена днища диаметра трубы - карточка товара Днища, зависящая от выбранного диаметра трубы при расчете*/
    'price_otvoda': 0, /*цена отвода диаметра трубы - карточка товара Отвода, зависящая от выбранного диаметра трубы при расчете*/
    'zaglushka': 0,
    'konstruct': 0,
    'diametr': 0,
    'stenka': 0,
    'dlina': 0,
    'secia': 0,
    'kronshtein': 0,
    'stoyka': 0,
    'quan': 0,
    'formula1': 0,
    'formula2': 0,
    'formula3': 0,
    'formula4': 0,
    'formula5': 0,
    'formula6': 0,
    'price_final': 0,
    'name_secia': '',
    'name_zaglushka': '',

    'st_price_material': 0, // Цена материала (из настроек модуля)
    'st_rabota': 0, // Работа (из настроек модуля)
    'st_l': 0, // l (из настроек модуля)

    'st_col': 0, // Количество стоек (из прайса)
    'st_visota': 0, // Высота стойки (из прайса)
    'st_ves': 0, // Вес стойки (из прайса)

    'st_price_rabota': 0,
    'st_sebestoimost': 0,
    'st_price': 0,
}

function startCalculator(){
    BX.ajax.runAction('pinkbrain:calculator.api.getsettings', {
        data: {}
    }).then(function (response) {
        console.log(response)

        var moduleSetting = response.data.MODULE;
        calc.pricelist = response.data.PRICELIST;

        calc.price_truba = +moduleSetting.PRICE_PIPE;
        calc.r1 = +moduleSetting.DIAMETER_1;
        calc.r2 = +moduleSetting.DIAMETER_2;
        calc.r3 = +moduleSetting.DIAMETER_3;
        calc.price_du25 = +moduleSetting.PRICE_DU25;
        calc.st_price_material = +moduleSetting.RACK_MATERIAL_PRICE;
        calc.st_rabota = +moduleSetting.RACK_WORK_PRICE;
        calc.st_l = +moduleSetting.RACK_L_VARIABLE;

        calc.PRODUCT_PRICES = response.data.PRODUCT_PRICES;

        calc.price_otvoda57 = calc.PRODUCT_PRICES.W_57;
        calc.price_otvoda89 = calc.PRODUCT_PRICES.W_89;
        calc.price_dnisha = 0;
        calc.price_otvoda = 0;

        calc_sbor();
    }, function (response) {
        console.log('error', response)
    });
}

function calc_sbor() {
    var price_final = 0;
    /*вводные динамика*/
    calc.zaglushka=$('input[name="zaglushka"]:checked').val();
    calc.konstruct=$('input[name="konstruct"]:checked').val();
    calc.diametr=$('.calc-val__diametr').val();
    calc.stenka=$('.calc-val__stenka').val();
    calc.dlina=$('.calc-val__dlina').val();
    calc.secia=$('.calc-val__secia').val();
    calc.kronshtein=$('input[name="kronshtein"]:checked').val();
    calc.stoyka=$('input[name="stoyka"]:checked').val();
    calc.quan=$('.calc-val__quan').val();

    calc.price_dnisha = calc.PRODUCT_PRICES['B_'+calc.diametr];
    calc.price_otvoda = calc.PRODUCT_PRICES['W_'+calc.diametr];

    /*пред расчет формул*/
    calc.formula1=((calc.diametr - calc.stenka) * calc.stenka / 40.55 * calc.price_truba);
    calc.formula2 = calc.r1*calc.r2;
    calc.formula3 = calc.dlina / 1000 * calc.secia;
    calc.formula4 = 2 * calc.price_dnisha * calc.r3;
    calc.formula5 = calc.secia - 1;
    calc.formula6 = 2 * calc.price_dnisha;

    /*возможные расчеты*/
    if ((calc.zaglushka==1)&&(calc.konstruct)==1) {
        price_final=calc.formula1*calc.formula3*calc.formula2;
    }

    if ((calc.zaglushka==2)&&(calc.konstruct)==1) {
        price_final=(calc.formula1 * calc.formula3 * calc.formula2) + (2 * calc.secia * calc.price_dnisha * calc.r3);
    }

    if ((calc.zaglushka==1)&&(calc.konstruct)==2) {
        price_final=((calc.formula1 * calc.formula3 * calc.formula2) + (calc.formula5) * calc.price_otvoda * 2 * calc.r3);
    }

    if ((calc.zaglushka==2)&&(calc.konstruct)==2) {
        price_final=(calc.formula1 * calc.dlina/1000 * calc.secia * calc.formula2) + (calc.formula6 + calc.formula5 * calc.price_otvoda * 2) * calc.r3;
    }

    if ((calc.zaglushka==1)&&(calc.konstruct)==3) {
        switch (calc.diametr) {
            case '42':
                price_final=0;
                break;
            case '48':
                price_final=0;
                break;
            case '57':
                price_final=((calc.formula1 * calc.formula3) + (0.4 * (calc.formula5) * calc.price_du25)) * calc.formula2;
                break;
            case '76':
                price_final=((calc.formula1 * calc.formula3) + (0.75 * (calc.formula5) * calc.price_du25)) * calc.formula2;
                break;
            case '89':
                price_final=((calc.formula1 * calc.formula3) + (0.9 * (calc.formula5) * calc.price_du25)) * calc.formula2;
                break;
            case '108':
                price_final=((calc.formula1 * calc.formula3) + (1.1 * (calc.formula5) * calc.price_du25)) * calc.formula2;
                break;
            case '114':
                price_final=((calc.formula1 * calc.formula3) + (1.1 * (calc.formula5) * calc.price_du25)) * calc.formula2;
                break;
            case '133':
                price_final=((calc.formula1 * calc.formula3 * calc.formula2) + (calc.formula5) * 2 * calc.price_otvoda57 * calc.r3);
                break;
            case '159':
                price_final=((calc.formula1 * calc.formula3 * calc.formula2) + (calc.formula5) * 2 * calc.price_otvoda57 * calc.r3);
                break;
            case '219':
                price_final=((calc.formula1 * calc.formula3 * calc.formula2) + (calc.formula5) * 2 * calc.price_otvoda89 * calc.r3);
                break;
            default:calc.
                price_final=0;
                break;
        }
    }

    if ((calc.zaglushka==2)&&(calc.konstruct)==3) {
        switch (calc.diametr) {
            case '42':
                price_final=0;
                break;
            case '48':
                price_final=0;
                break;
            case '57':
                price_final=((calc.formula1 * calc.formula3) + (0.4 * calc.formula5 * calc.price_du25)) * calc.formula2 + calc.formula4;
                break;
            case '76':
                price_final=((calc.formula1 * calc.formula3) + (0.75 * (calc.formula5) * calc.price_du25)) * calc.formula2 + calc.formula4;
                break;
            case '89':
                price_final=((calc.formula1 * calc.formula3) + (0.9 * (calc.formula5) * calc.price_du25)) * calc.formula2 + calc.formula4;
                break;
            case '108':
                price_final=((calc.formula1 * calc.formula3) + (1.1 * (calc.formula5) * calc.price_du25)) * calc.formula2 + calc.formula4;
                break;
            case '114':
                price_final=0;
                break;
            case '133':
                price_final=((calc.formula1 * calc.formula3 * calc.formula2) + (calc.formula5) * 2 * calc.price_otvoda57 + calc.formula6 * calc.r3);
                break;
            case '159':
                price_final=((calc.formula1 * calc.formula3 * calc.formula2) + (calc.formula5) * 2 * calc.price_otvoda57 + calc.formula6 * calc.r3);
                break;
            case '219':
                price_final=((calc.formula1 * calc.formula3 * calc.formula2) + (calc.formula5) * 2 * calc.price_otvoda89 + calc.formula6 * calc.r3);
                break;
            default:
                price_final=0;
                break;
        }
    }

    /*формирование товара*/
    switch (calc.secia) {
        case '1':
            calc.name_secia='';
            break;
        case '2':
            calc.name_secia='-х';
            break;
        case '3':
            calc.name_secia='-х';
            break;
        case '4':
            calc.name_secia='-х';
            break;
        case '7':
            calc.name_secia='-ми';
            break;
        case '8':
            calc.name_secia='-ми';
            break;
        default:
            calc.name_secia='-ти';
            break;
    }
    if(calc.zaglushka==1) {
        name_zaglushka='плоскими';
    } else {
        name_zaglushka='эллиптическими';
    }
    $('.calc__sum-name').text('Регистр Ду '+calc.diametr+'х'+calc.stenka+', L='+calc.dlina+', '+calc.secia+calc.name_secia+' секционный с '+calc.name_zaglushka+' днищами');
    $('.modal__text strong').text('Регистр Ду '+calc.diametr+'х'+calc.stenka+', L='+calc.dlina+', '+calc.secia+calc.name_secia+' секционный с '+calc.name_zaglushka+' днищами Х '+calc.quan+' шт.');
    $('.calc__sum-gabarit').text(calc.diametr+'х'+calc.stenka+'х'+calc.dlina);
    $('.calc__sum-ves').text(Math.ceil(price_final/calc.price_truba)+' кг.');/*возможно не верно*/

    /*если ест стойки*/
    if(calc.stoyka==1) {
        price_final = Math.round(calc_stoika(calc.diametr, calc.dlina, calc.secia));
    }

    /*вывод цены*/
    if(price_final>0) {
        $('.calc__sum-price span').text(Math.round(price_final));
        $('.button__calc-send').prop('disabled', false);
        $('.calc__sum-price-all span').text(Math.round(price_final)*calc.quan);

    }else{
        $('.calc__sum-price span').text('Что то пошло не так...');
        $('.calc__sum-price-all span').text('Что то пошло не так...');
        $('.button__calc-send').prop('disabled', true);
    }
}

/*расчет стойки*/
function calc_stoika (register, length, sections) {
    for(var i = 0; i < Object.keys(calc.pricelist[register]).length; i++){
        var lengthRange = Object.keys(calc.pricelist[register])[i];
        var arLengthRange = Object.keys(calc.pricelist[register])[i].split('-');

        if(+length >= +arLengthRange[0] && +length <= +arLengthRange[1]) {
            var currentSection = calc.pricelist[register][lengthRange][sections];
            calc.st_col = +currentSection.COUNT_RACK;
            calc.st_ves = +currentSection.RACK_WEIGHT;
        }
    }

    calc.st_price_rabota = calc.st_col * calc.st_rabota;
    calc.st_sebestoimost = calc.st_ves * calc.st_col * calc.st_price_material;
    st_price = (calc.st_price_rabota + calc.st_sebestoimost) * calc.st_l;

    return st_price;
}

$(document).ready(function () {
    if($('.calc').length > 0) {
        $('#calcslider').slider({
            value: 1850,
            orientation: "horizontal",
            range: "min",
            animate: true,
            min: 200,
            max: 12000,
            slide: function (event, ui) {
                $('.calc-val__dlina').val(ui.value);
                calc_sbor();
            }
        });

        startCalculator();

        $('.button__calc-send').click(function (e) {
            $('.modal-calc').fadeIn('fast');
            $('body').addClass('hidden');
        });

        $('.modal__shadow, .modal__close').click(function (e) {
            $('.modal-calc').fadeOut('fast');
            $('body').removeClass('hidden');
        });

        $('.calc .form_radio input[type="radio"]').change(function () {
            calc_sbor();
        });

        $('.calc select.select').change(function () {
            calc_sbor();
        });

        $('.calc-val__dlina').focusout(function () {
            var v = $(this).val();
            if (v < 200) {
                $(this).val(200);
            }
            if (v > 12000) {
                $(this).val(12000);
            }
            calc_sbor();
        });

        $('.calc-val__dlina').keyup(function () {
            $('#calcslider').slider('value', $(this).val());
            calc_sbor();
        });

        $('.calc-val__quan').keyup(function () {
            calc_sbor();
        });

        $('input').click(function (e) {
            $(this).removeClass('error');
        });

        $('.button-calc-send').click(function (e) {
            var form = $(this).parents('.modal__body');
            var pattern_mail = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,6}\.)?[a-z]{2,6}$/i;
            var pattern_phone = /^(\+7|7|8)?[\s\-]?\(?[489][0-9]{2}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$/;
            if (form.find('.modal__fio').val().length < '3') {
                $('.modal__fio').addClass('error');
                return false;
            }

            if (!form.find('.modal__mail').val() || !pattern_mail.test($('.modal__mail').val())) {
                $('.modal__mail').addClass('error');
                return false;
            }

            if (!form.find('.modal__phone').val() || !pattern_phone.test($('.modal__phone').val())) {
                $('.modal__phone').addClass('error');
                return false;
            }

            var sendData = {
                'FIELDS': {
                    'TITLE': 'Регистр Ду '+calc.diametr+'х'+calc.stenka+', L='+calc.dlina+', '+calc.secia+calc.name_secia+' секционный с '+calc.name_zaglushka+' днищами Х '+calc.quan+' шт.',
                    'PLUG': $('input[name="zaglushka"]:checked').val(),
                    'CONSTRUCTION': $('input[name="konstruct"]:checked').val(),
                    'DIAMETER': $('.calc-val__diametr').val(),
                    'WALL_THICKNESS': $('.calc-val__stenka').val(),
                    'LENGTH': $('.calc-val__dlina').val(),
                    'QUANTITY_SECTIONS': $('.calc-val__secia').val(),
                    'BRACKETS': $('input[name="kronshtein"]:checked').val(),
                    'RACK': $('input[name="stoyka"]:checked').val(),
                    'QUANTITY': $('.calc-val__quan').val(),
                    'PRICE': $('.calc__sum-price span').text(),
                    'USER_NAME': form.find('.modal__fio').val(),
                    'USER_EMAIL': form.find('.modal__mail').val(),
                    'USER_PHONE': form.find('.modal__phone').val(),
                },
                'AJAX': 'Y',
            };


            $.ajax({
                method: "POST",
                url: window.componentPathAjax,
                dataType: 'json',
                data: sendData,
            }).done(function (response) {

                form.find('.modal-body__error').html("");

                if(response.success){
                    form.find('.modal-body__content').html(response.message);
                }else{
                    form.find('.modal-body__error').html(response.message);
                }
            });
        });
    }
});
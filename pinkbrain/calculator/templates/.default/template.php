<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$this->addExternalCss($this->__component->__path."/assets/jquery-ui.css");
$this->addExternalJS($this->__component->__path."/assets/jquery-ui.min.js");


if(!empty($arResult['ERROR'])){
    foreach ($arResult['ERROR'] as $error) {
        ShowMessage($error);
    }
}else{
?>
    <form>
        <div class="calc">
            <div class="calc__wrapper">
                <div class="calc__block calc__block50 calc__mr5">
                    <div class="calc__caption">Тип заглушек</div>
                    <div class="calc__body calc-val__zaglushka">
                        <div class="form_radio">
                            <input id="radio-1" type="radio" name="zaglushka" checked value="1">
                            <label for="radio-1">Плоские</label>
                        </div>
                        <div class="form_radio">
                            <input id="radio-2" type="radio" name="zaglushka" value="2">
                            <label for="radio-2">Эллиптические</label>
                        </div>
                    </div>
                </div>
                <div class="calc__block calc__block50">
                    <div class="calc__caption">Тип конструкции</div>
                    <div class="calc__body calc-val__konstruct">
                        <div class="form_radio">
                            <input id="radio-3" type="radio" name="konstruct" checked value="1">
                            <label for="radio-3">Секционный</label>
                        </div>
                        <div class="form_radio">
                            <input id="radio-4" type="radio" name="konstruct" value="2">
                            <label for="radio-4">Змеевиковый</label>
                        </div>
                        <div class="form_radio">
                            <input id="radio-5" type="radio" name="konstruct" value="3">
                            <label for="radio-5">Змеевиковый трубный</label>
                        </div>
                    </div>
                </div>
                <div class="calc__block calc__block25 calc__mr5">
                    <div class="calc__caption">Диаметр трубы, mm</div>
                    <div class="calc__body">
                        <select class="select calc-val__diametr">
                            <option>57</option>
                            <option>76</option>
                            <option>89</option>
                            <option>108</option>
                            <option>114</option>
                            <option>133</option>
                            <option>159</option>
                            <option>219</option>
                        </select>
                    </div>
                </div>
                <div class="calc__block calc__block25 calc__mr5">
                    <div class="calc__caption">Толщина стенки, mm</div>
                    <div class="calc__body">
                        <select class="select calc-val__stenka">
                            <option>2</option>
                            <option>2.5</option>
                            <option>3</option>
                            <option>3.5</option>
                            <option>4</option>
                            <option>4.5</option>
                            <option>5</option>
                            <option>6</option>
                            <option>7</option>
                            <option>8</option>
                        </select>
                    </div>
                </div>
                <div class="calc__block calc__block50">
                    <div class="calc__caption">Длина секции, mm</div>
                    <div class="calc__body">
                        <div class="calc__dp-item">
                            <input type="number" value="1850" class="calcslider__input calc-val__dlina">
                        </div>
                        <div class="calc__dp-body">
                            <div class="calc__dp-counter">
                                <div class="calc__dp-counter-min">200</div>
                                <div class="calc__dp-counter-max">12000</div>
                            </div>
                            <div class="calc__dp-line">
                                <div id="calcslider"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="calc__block calc__block25 calc__mr5">
                    <div class="calc__caption">Количество секций</div>
                    <div class="calc__body">
                        <select class="select calc-val__secia">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                            <option>6</option>
                            <option>7</option>
                            <option>8</option>
                        </select>
                    </div>
                </div>
                <div class="calc__block calc__block50 calc__mr5">
                    <div class="calc__caption">Кронштейны</div>
                    <div class="calc__body calc-val__kronshtein">
                        <div class="form_radio">
                            <input id="radio-6" type="radio" name="kronshtein" value="1">
                            <label for="radio-6">С кронштейнами</label>
                        </div>
                        <div class="form_radio">
                            <input id="radio-7" type="radio" name="kronshtein" value="2" checked>
                            <label for="radio-7">Без кронштейнами</label>
                        </div>
                    </div>
                </div>
                <div class="calc__block calc__block25">
                    <div class="calc__caption">Стойки</div>
                    <div class="calc__body calc-val__stoyka">
                        <div class="form_radio">
                            <input id="radio-8" type="radio" name="stoyka" value="1">
                            <label for="radio-8">Да</label>
                        </div>
                        <div class="form_radio">
                            <input id="radio-9" type="radio" name="stoyka" value="2" checked>
                            <label for="radio-9">Нет</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="calc__sum">
                <div class="calc__sum-caption">Результат рассчета:</div>
                <div class="calc__sum-items">
                    <div class="calc__sum-item">
                        <div class="calc__sum-name">
                            Название регистра отопления
                        </div>
                        <div class="calc__sum-h">
                            <div class="calc__sum-h-b" style="display: none;">
                                <div class="calc__sum-h-l">
                                    Габариты:
                                </div>
                                <div class="calc__sum-h-r calc__sum-gabarit">

                                </div>
                            </div>
                            <div class="calc__sum-h-b" style="display: none;">
                                <div class="calc__sum-h-l">
                                    Вес:
                                </div>
                                <div class="calc__sum-h-r calc__sum-ves">

                                </div>
                            </div>
                        </div>
                        <div class="calc__sum-price">
                            Цена за единицу: <span>0</span> руб.
                        </div>
                        <div class="calc__sum-h-b">
                            <div class="calc__sum-h-l">
                                Количество:
                            </div>
                            <div class="calc__sum-h-r calc__sum-quan">
                                <input type="number" value="1" class="calcslider__input calc-val__quan" min="1">
                            </div>
                        </div>
                        <div class="calc__sum-price-all">
                            Итого: <span>0</span> руб.
                        </div>
                        <div class="calc__sum-butt">
                            <input type="button" class="button button__calc-send" value="Купить" disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <div class="modal modal-calc">
        <div class="modal__shadow"></div>
        <div class="modal__body">
            <div class="modal__close"></div>
            <div class="modal__caption">
                Отправить заявку
            </div>
            <div class="modal-body__error"></div>
            <div class="modal-body__content">
                <div class="modal__text">
                    <span>товар:</span><br />
                    <strong></strong>
                </div>
                <div class="modal__item">
                    <input type="text" placeholder="ФИО*" class="modal__fio">
                </div>
                <div class="modal__item">
                    <input type="text" placeholder="E-mail*" class="modal__mail">
                </div>
                <div class="modal__item">
                    <input type="text" placeholder="Телефон*" class="modal__phone">
                </div>
                <div class="modal__butt">
                    <input type="button" value="Отправить" class="button button-calc-send">
                </div>
            </div>
        </div>
    </div>

    <script>
        window.componentPathAjax = '<?=$this->__component->__path?>/ajax.php';
    </script>
<? } ?>
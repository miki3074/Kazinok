<template>
    <div>
        <div class="card">
            <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
                <div>
                    <h4 class="mg-b-5 ">Инструкции</h4>
                </div>
            </div>
            <div class="card-body pd-20 bd-b pd-b-20">
                <div class="row">
                    <div class="col-12">
                        <div class=" card-crypto">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="main-tab" data-toggle="tab" href="#mainAboutTab" role="tab" aria-controls="home" aria-selected="true">Main game</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="mines-tab" data-toggle="tab" href="#minesAboutTab" role="tab" aria-controls="profile" aria-selected="false">Mines</a>
                                </li>
                            </ul>
                            <div class="tab-content bd bd-gray-300 bd-t-0 pd-20" id="myTabContent">
                                <div class="tab-pane fade show active" id="mainAboutTab" role="tabpanel" aria-labelledby="main-tab">
                                    <h5>Правила</h5>
                                    <p class="">Для начала игры укажите сумму и процент выигрыша. Далее попробуйте угадать, нажав на одну из кнопок больше или меньше, промежуток в котором выпадет победное число. Если число выпало в выбранном промежутке, то сумма умножается на коэффициент и возвращается на основной баланс, если же не угадали, то ставка сгорает.</p>
                                    <h5>Честная игра</h5>
                                    <p>Перед каждой игрой генерирутеся строка алгоритмом <u>SHA-512</u> в которой содержится <u>соль</u> и <u>победное число</u> (от 0 до 999999). Можно сказать, что перед Вами зашифрованный исход данной игры. Метод гарантирует 100% честность, так как Hash игры Вы видите заранее, а при изменении победного числа приведет к изменению Hash.</p>
                                    <p class="mg-b-0">Проверяйте самостоятельно:
                                    </p><li>Скоприруйте Hash до начала игры</li>
                                    <li>После окончания нажмите "Проверить игру"</li>
                                    <li>Откроется окно с результатом</li>
                                    <li>Скопируйте поля Salt1, Number (Победное число), Salt2</li>
                                    <li>Вставьте в любой независимый SHA-512 генератор (Например: <a style="text-decoration: underline;" href="https://passwordsgenerator.net/sha512-hash-generator/" target="_blank">Ссылка 1</a> <a href="https://emn178.github.io/online-tools/sha512.html" style="text-decoration: underline;" target="_blank">Ссылка 1</a> <a style="text-decoration: underline;" href="https://www.md5calc.com/sha512" target="_blank">Ссылка 2</a>)</li>
                                    <li>Hash должен совпадать c Hash до начала игры</li>
                                    <p></p>
                                    <hr>
                                    <p>
                                        Например:
                                    </p><li>Скопировали Hash до начала игры</li>
                                    <li>После окончания нажали на "Проверить игру", открылось окно</li>
                                    <li>Копируем значения Salt1, Number (Победное число), Salt2</li>
                                    <li>Получаем строку 8{93mW8huq|995544|a5cm28bjA0</li>
                                    <li>Вставляем строку в генератор</li>
                                    <li>Получили Hash как и до начала игры</li>
                                    <p></p>
                                </div>
                                <div class="tab-pane fade " id="minesAboutTab" role="tabpanel" aria-labelledby="mines-tab">
                                    <h5>Правила</h5>
                                    <p class="">Цель игры Mines – вскрыть пустые блоки, не вскрыв при этом ни одного, содержащей мину. Игра начинается с ввода размера игры и указания количества мин.</p>
                                    <h5>Честная игра</h5>
                                    <p>Сразу после начала игры генерирутеся строка алгоритмом <u>SHA-512</u> в которой содержится <u>информация о блоках</u> и <u>соль</u>. Можно сказать, что перед Вами зашифрованный исход данной игры. Метод гарантирует 100% честность, так как блоки открываются после получения Hash, а при изменении информации о блоках приведет к изменению Hash.</p>
                                    <p class="mg-b-0">Проверяйте самостоятельно:
                                    </p><li>Скоприруйте Hash до начала игры</li>
                                    <li>После окончания нажмите "Проверить игру"</li>
                                    <li>Откроется окно с результатом</li>
                                    <li>Скопируйте поле Full string, содержащее информацию о блоках (Отсчет идет с верхнего левого блока, 0 - пустой блок, 2 - блок содержит мину, разделитель - "|") и соль</li>
                                    <li>Вставьте в любой независимый SHA-512 генератор (Например: <a style="text-decoration: underline;" href="https://passwordsgenerator.net/sha512-hash-generator/" target="_blank">Ссылка 1</a> <a href="https://emn178.github.io/online-tools/sha512.html" style="text-decoration: underline;" target="_blank">Ссылка 1</a> <a style="text-decoration: underline;" href="https://www.md5calc.com/sha512" target="_blank">Ссылка 2</a>)</li>
                                    <li>Hash должен совпадать c Hash до начала игры</li>
                                    <p></p>
                                    <hr>
                                    <p>
                                        Например:
                                    </p><li>Скопировали Hash до начала игры</li>
                                    <li>После окончания нажали на "Проверить игру", открылось окно</li>
                                    <li>Копируем значения Full string</li>
                                    <li>Вставляем строку в генератор</li>
                                    <li>Получили Hash как и до начала игры</li>
                                    <p></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        
    }
</script>


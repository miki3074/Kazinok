<template>
    <div class="game-container game-stairs">
        <div >
            <!-- row -->
         <!-- dsa -->
                <!-- col -->
                <div class="game-content game-content-stairs game-type-local" style=" background: none; border: none;">
                <!-- width: 634px;  -->
                    <div>
                        <div class="stairsColumns" >
                            <!-- style="width: 623px;" -->
                            <div class="stairsMultipliers">
                                <div v-if="multipliers[mines-1]" v-for="m in multipliers[mines-1]" class="multiplier">{{ m }}</div>
                            </div>
                            <div class="stairsContainer">
                                <div class="stairsRow" v-for="(row, rowId) in rows" :data-row-id="rows.length - 1 - rowId" :set="cell = 0">
                                    <div :data-cell-all-id="cellIndex" :data-cell-id="type === 0 ? null : type === 2 ? null : cell" :set="type === 0 ? null : type === 2 ? null : cell++" v-for="(type, cellIndex) in row" :class="`stairsCell ${type === 0 ? 'stairsInvisible' : 'stairsVisible'}`" @click="cellClick(rows.length - 1 - rowId, $event.target)"></div>
                                </div>
                                <div class="character stand"></div>
                            </div>
                        </div>
                        <div class="d-none">
                            <img class="svg-inline--fa fa-stairs fa-w-16 transformed" style="position: absolute; left: 0" src="/img/stairs/rock.svg" data-icon-clone=""/>
                            <svg role="img" xmlns="http://www.w3.org/2000/svg" focusable="false" viewBox="0 0 32 32" class="svg-inline--fa fa-ladder fa-w-16 transformed" data-ladder-clone="">
                                <path fill="currentColor" d="M20.372 0v2.947h-8.745v-2.947h-1.901v32h1.901v-2.947h8.745v2.947h1.901v-32h-1.901zM20.372 27.152h-8.745v-2.94h8.745v2.94zM20.372 22.311h-8.745v-2.94h8.745v2.94zM20.372 17.47h-8.745v-2.94h8.745v2.94zM20.372 12.629h-8.745v-2.94h8.745v2.94zM20.372 7.788h-8.745v-2.94h8.745v2.94z">                        
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="multipliers-carousel d-flex justify-content-center ">
                <ul class="steps mg-t-20 ">
                    <div id="carousel" class="carousel slide " data-ride="carousel" data-interval="false">
                        <div class="carousel-inner pd-t-4 pd-b-2" id="minesRate">
                            <div class="carousel-item justify-content-center"
                                    v-for="i in 7"
                                    :class="[((i === 1 && !game.active) || (Math.ceil((rowcounter + 1) / 2) == i)) ? 'active' : '']">
                                <li class="step-item bd bd-1 pd-10 rounded-5"
                                    :class="[(((rowcounter + 1) === ((i - 1) * 2) + (counter + 1)) && game.active) ? 'bd-primary' : '', (((i - 1) * 2) + (counter + 1) < (rowcounter + 1)) ? 'bd-success' : '']"
                                    v-for="( m, counter) in multipliers[mines-1].slice((i-1)*2, (i-1)*2+2)"
                                    style="background: #525c68;">
                                    <span class="st-bl"
                                    style="position:absolute; margin-top: -18px; margin-left: 4px; font-size: 10px; background:#fff; padding:2px 7px;">Шаг {{ ((i - 1) * 2) + (counter + 1) }}</span>
                                    <a class="step-link">
                                        <div style="margin-left:0;width:110px;align-items: center;">
                                            <span class="step-title" style="">{{ (m).toFixed(2) }}</span>
                                            <span class="step-desc">x{{ m }}</span>
                                        </div>
                                    </a>
                                </li>
                            </div>
                        </div>
                    </div>
                </ul>
            </div>

<!-- 1 -->  <div id="stairs-game-settings" :class="[game.active ? 'disabled' : '']">   
                <div class="row justify-content-center" style="padding: 0 5px;">
                    <div class="col-md-4 col-sm-6 col-6" style="padding-right: 5px;">
                        <span class="tx-11 ">Камни</span>            
                            <select class="custom-select text-center tx-20  mg-t-5"
                            v-model="mines" style="background-color: #525c68; border-bottom-right-radius: 0px;border-bottom-left-radius: 0px;border-bottom: 2px solid #242b33;">
                                <option v-for="i in 7" :value="i" :selected="mines === (i)">{{ i }}
                                </option>
                            </select>

                        <div style="margin-top: -1px;" class="btn-group tx-rubik d-flex justify-content-center">
                            <button
                                
                                style="border-top-left-radius: 0; padding: 0;"
                                class="tx-gray-600 btn btn-xs btn-white tx-13 mb-mines">
                                <div class="control game-sidebar-buttons-container-button active" style="margin-left: 10%; " id="1" @click="mines=1" onclick="$('.game-sidebar-buttons-container-button').removeClass('active') ;$(this).addClass('active')"><span>1</span></div>
                            </button>
                            
                            <button                          
                                style="border-top-right-radius: 0; padding: 0;"
                                class="tx-gray-600 btn btn-xs btn-white tx-13 mb-mines">

                                <div class="control game-sidebar-buttons-container-button" id="3" style="" @click="mines=3" onclick="$('.game-sidebar-buttons-container-button').removeClass('active') ;$(this).addClass('active')"><span>3</span></div>
                                    
                            </button>
                            <button
                                
                                style="border-top-right-radius: 0; padding: 0;"
                                class="tx-gray-600 btn btn-xs btn-white tx-13 mb-mines">

                                <div class="control game-sidebar-buttons-container-button" id="5" style="" @click="mines=5" onclick="$('.game-sidebar-buttons-container-button').removeClass('active') ;$(this).addClass('active')"><span>5</span></div>
                            </button>
                            <button
                                
                                style="border-top-right-radius: 0; padding: 4px 0;"
                                class="tx-gray-600 btn btn-xs btn-white tx-13 mb-mines">
                                    <div class="control game-sidebar-buttons-container-button" id="7"  @click="mines=7" onclick="$('.game-sidebar-buttons-container-button').removeClass('active') ;$(this).addClass('active')"><span>7</span></div>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-6"  style="padding-left: 5px;">
                        <span class="tx-11 ">Сумма</span>
                        <div class="input-group mg-t-5">
                            <input type="text" style="border-bottom-right-radius: 0px;border-bottom-left-radius: 0px;
                            border-bottom: 2px solid #242b33;border-top: none; background: #525c68;"  
                            class="form-control tx-20 justify-content-center align-self-center text-center " 
                            v-model="bet" placeholder="Сумма ставки" :disabled="(game.active || disableBtn)"></input>
                        </div>
                        <div style="margin-top: -1px; "
                                class="btn-group tx-rubik d-flex justify-content-center ">
                            <button
                                
                                style="border-top-left-radius: 0; padding: 0;"
                                class="tx-gray-600 btn btn-xs btn-white   tx-13 mb-mines">
                                <div class="control " style="" @click="typeBet('max')">Max</div>
                            </button>
                            <button
                                
                                style="border-top-right-radius: 0; padding: 0;"
                                class="tx-gray-600 btn btn-xs btn-white   tx-13 mb-mines">
                                <div class="control" style="" @click="typeBet('min')">Min</div>
                            </button>
                            <button
                                
                                style="border-top-right-radius: 0; padding: 0;"
                                class="tx-gray-600 btn btn-xs btn-white   tx-13 mb-mines"><div class="control" @click="typeBet('x2')">x2</div>
                            </button>
                            <button
                                
                                style="border-top-right-radius: 0; padding: 4px 0;"
                                class="tx-gray-600 btn btn-xs btn-white   tx-13 mb-mines"><div class="control" @click="typeBet('/2')">/2</div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center" style="padding: 0 5px;">
                <div class="col-md-8 col-sm-12 col-12">
                    <button class="btn btn-block play-button btn-secondary mt-2 p-3 " @click="startGameSocket" v-if="!game.active && !disableBtn"> Начать игру </button>
                    <div class="stairs-ingame-buttons d-flex justify-content-between" :class="[movement ? 'disabled' : '']" v-else >
                        <button class="btn btn-block play-button btn-secondary mt-2 p-3 " style="max-width: 68%;" @click="cashOut"> Забрать <u>{{(game.profit).toFixed(2)}}</u> </button>
                        <button class="btn btn-secondary btn-block pd-t-10 pd-b-10 tx-15 "
                            style="max-width: 30%;" @click="autoSelect">Авто
                        </button>
                    </div>
                    <button id="succes_bet" v-if="success.show" style="padding: 11px;pointer-events: none;margin-top: 0px;" class="btn btn-block tx-medium btn-la-mob bg-success-dice tx-white bd-0 btn-sel-d mt-2">
                            {{ success.message }}
                    </button>
                    <button id="errorMines"
                                style="padding: 11px; pointer-events: none; margin-top: 15px; color: white !important;" v-if="errors.show"
                                class="btn btn-block tx-medium btn-la-mob bg-danger-dice tx-white bd-0 btn-sel-d mg-b-15 ">
                            {{ errors.message }}
                    </button>
                </div>
            </div>
            
<!-- 2 -->


<!-- 3   -->

               
           
<!-- 4 -->



    </div>
    
</template>

<script>
   
    export default {
        data() {
            return {
                disableBtnCashout: false,
                disableBtn: false,
                afterLose: false,
                movement: false,
                prevCell: null,
                rowcounter: 0,
                bet : 1.00,
                mines: 2,
                errors: {
                    show: false,
                    message: ''
                },
                success: {
                    show: false,
                    message: ''
                },
                game: {
                    active: false,
                    hash: '',
                    profit: 1.00,
                    win: true
                },
                multipliers: [
                    [1.02, 1.08, 1.13, 1.21, 1.28, 1.37, 1.46, 1.57, 1.72, 1.81, 2.02, 2.26, 2.59],
                    [1.08, 1.2, 1.35, 1.52, 1.71, 1.97, 2.23, 2.64, 3.16, 3.53, 4.41, 5.68, 7.58],
                    [1.14, 1.36, 1.61, 1.95, 2.32, 2.9, 3.52, 4.58, 6.1, 7.25, 10.36, 15.54, 24.85],
                    [1.21, 1.53, 1.95, 2.54, 3.22, 4.39, 5.74, 8.3, 12.45, 15.77, 26.29, 47.31, 90.62],
                    [1.29, 1.76, 2.39, 3.38, 4.58, 6.87, 9.73, 15.81, 27.11, 36.79, 73.58, 135.57, 391.52],
                    [1.39, 2.03, 2.96, 4.58, 6.68, 11.15, 17.22, 31.98, 63.96, 93.49, 213.72, 601.15, 2204.61],
                    [1.49, 2.37, 3.74, 6.36, 10.07, 18.88, 32.1, 69.55, 156.91, 234.27, 780.91, 3464.07, 23712.58]
                ],
                rows: [
                    [0, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                    [0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                    [0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                    [2, 2, 2, 2, 2, 2, 2, 2, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0],
                    [0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
                    [0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
                    [0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 2, 2, 2],
                    [0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0],
                    [2, 2, 2, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0],
                    [0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
                    [0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 2],
                    [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0],
                    [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1]
                ]
            }
        },
        methods: {
            getUser() {
                this.$root.axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.$cookie.get('token');

                this.$root.graphql.getUser().then(res => {
                    this.$root.user = res.User;
                    this.getGame();
                    //console.log("neRoot " + this.user);
                    //console.log("Root " + this.$root.user);

                }).catch(error => {
                    this.$cookie.delete('token');
                });
            },
            getGame() {
                //console.log("Root " + this.$root.user);
                this.$socket.emit('stairsGet', {
                    id: this.$root.user.id
                }, (data) => {
                    if (data.success) {
                        this.disableBtn = true;

                        this.bet = data.bet;
                        this.mines = data.bomb;

                        this.game = {
                            active: true,
                            hash: data.hash,
                            profit: data.profit,
                            win: true
                        }
                        this.restore(data.game);
                    }
                    this.$root.isLoading = false;
                });
            },
            startGameSocket() {
                if (this.disableBtn || this.game.active) {
                    return;
                }

                this.errors.show = false;
                this.success.show = false;
                this.disableBtn = true;
                this.movement = false;
                //this.disableBtnCashout = false;

                if (!this.$cookie.get('token')) {
                    this.errors = {
                        show: true,
                        message: 'Авторизуйтесь'
                    };

                    this.disableBtn = false;

                    return;
                }

                if (this.bet.length === 0) {
                    this.errors = {
                        show: true,
                        message: 'Введите ставку'
                    };

                    this.disableBtn = false;

                    return;
                }

                if (this.mines.length === 0) {
                    this.errors = {
                        show: true,
                        message: 'Укажите кол-во камней'
                    };

                    this.disableBtn = false;

                    return;
                }

                if (this.afterLose) {
                    $('[data-cell-id]').removeClass('selected').removeClass('active').find('svg').remove();
                    $('[data-cell-id]').find('img').remove();
                    $('.character').fadeOut('fast', () => $('.character').attr('data-flip', 'false').attr('style', '').attr('class', 'character stand'));
                }

                this.afterLose = false;

                this.$socket.emit('stairsCreate', {
                    mines: this.mines,
                    bet: this.bet,
                    id: this.$root.user.id
                }, (data) => {
                    if(!data.success) {
                        this.disableBtn = false;
                        this.errors = {
                            show: true,
                            message: data.message
                        };
                    } else {
                        $('.game-controller').attr('id', 'disabled');
                        this.$root.user.balance = data.balance;
                        this.setRow(0);
                        this.game = {
                            profit: this.bet,
                            active: true,
                            hash: data.hash,
                            active_path: 1,
                            bombs: [],
                            win: true
                        };

                    }
                });
            },
            callback(response) {
                if(this.isExtendedGameStarted()) {
                    $('[data-cell-id]').removeClass('selected').removeClass('active').find('svg').remove();
                    $('[data-cell-id]').find('img').remove();
                    $('.character').fadeOut('fast', () => $('.character').attr('data-flip', 'false').attr('style', '').attr('class', 'character stand').fadeIn('fast', () => this.setRow(0)));
                } else {
                    $('[data-cell-id]').removeClass('active');
                    $('.character').attr('class', 'character victory');
                    if(response) this.resultPopup(response.game);
                }
            },
            cashOut() {
                if (this.disableBtnCashout) {
                    return;
                }

                this.errors.show = false;
                this.disableBtnCashout = true;

                this.$socket.emit('stairsCashout', {
                    id: this.$root.user.id
                }, (data) => {
                    if(!data.success) {
                        this.disableBtnCashout = false;
                        this.errors = {
                            show: true,
                            message: data.message
                        };
                    } else {
                        this.$root.user.balance = data.balance;

                        //this.endGame(data.history);
                        $('.game-controller').attr('id', '');
                        this.disableBtnCashout = false;
                        this.game.active = false;
                        this.disableBtn = false;
                        this.rowcounter = 0;

                        $('.game-controller').attr('id', '');
                        $('[data-cell-id]').removeClass('selected').removeClass('active').find('svg').remove();
                        $('[data-cell-id]').find('img').remove();
                        $('.character').fadeOut('fast', () => $('.character').attr('data-flip', 'false').attr('style', '').attr('class', 'character stand'));
                    }
                });
            },
            cellClick(row, target) {
                this.success.show = false;
                this.errors.show = false;

                const cell = $(target).data('cell-id');
                const oldcell = $(target).data('cell-all-id');
                const e = $(`[data-row-id="${row}"] [data-cell-id="${cell}"]`);

                if(!e.hasClass('active')) return;
                this.movement = true;
                this.setRow(row, false);
                this.$socket.emit('stairsBet', {
                    bomb: cell,
                    id: this.$root.user.id
                }, (response) => {
                    if(!response.success) {
                        if(response.status === 'fail') {
                            this.setRow(row, true);
                            return;
                        }
                        this.errors = {
                            show: true,
                            message: response.message
                        };
                    } else {
                        //console.log(e.position().left);
                        //console.log($('.character').position().left);
                        var charpos = $('.character').position().left - 3.5999755859375;
                        //console.log(charpos);
                        //$('.character').attr('class', 'character run').animate({ left: e.position().left }, e.position().left === charpos ? 0 : 1000, () => {
                        $('.character').attr('class', 'character run').animate({ left: e.position().left }, this.prevCell === oldcell ? 0 : 1000, () => {
                            setTimeout(() => {
                                //console.log(response.row);
                                response.row.forEach((deathCell, index) => this.dropStone(row, deathCell, index));

                                $('.character').attr('class', 'character climb').animate({top: $(`[data-row-id="${row}"] [data-cell-id="${cell}"]`).position().top - $('.character').height()}, 800, () => {
                                    if (response.status === 'finish') {
                                        //this.finishExtended(false);
                                        $('.character').attr('class', 'character victory');
                                        this.$root.user.balance += response.profit;
                                        this.success = {
                                            show: true,
                                            message: "Вы выиграли " + response.profit
                                        };
                                        this.game.active = false;
                                        this.game.profit = this.bet;
                                        this.disableBtn = false;
                                        $('.game-controller').attr('id', '');
                                        $('[data-cell-id]').removeClass('selected').removeClass('active').find('svg').remove();
                                        $('[data-cell-id]').find('img').remove();
                                        $('.character').fadeOut('fast', () => $('.character').attr('data-flip', 'false').attr('style', '').attr('class', 'character stand'));
                                        this.movement = false;
                                        this.rowcounter = 0;
                                        this.prevCell = null;
                                        //this.playSound('/sounds/win.mp3');
                                    }

                                    if (response.status === 'continue') {
                                        this.setRow(row + 1, true);
                                        this.game.profit = response.profit;
                                        $('.character').attr('class', 'character stand');
                                        this.movement = false;
                                        this.rowcounter++;
                                        this.prevCell = oldcell;
                                        //this.playSound('/sounds/guessed.mp3');
                                    }
                                    if (response.status === 'lose') {
                                        //this.finishExtended(false);
                                        var i = this.rowcounter + 1;
                                        for (; i < 13; i++) {
                                            response.grid[i].forEach((deathCell, index) => this.dropStone(i, deathCell, index));
                                        }
                                        $('.character').attr('class', 'character death');

                                        //$('.game-controller').attr('id', '');
                                        this.afterLose = true;
                                        this.game.active = false;
                                        this.game.profit = this.bet;
                                        this.disableBtn = false;
                                        this.movement = false;
                                        this.rowcounter = 0;
                                        this.prevCell = null;
                                        //this.playSound('/sounds/lose.mp3');
                                    }
                                });
                            }, 200);
                        });

                        e.addClass('selected');
                        e.append($('[data-ladder-clone]').clone().attr('data-ladder-clone', null));
                    }
                });
            },
            gameDataRetrieved() {
                $(`[data-cell-id]`).on('mouseover', function() {
                    if(!$(this).hasClass('active')) return;
                    $('.character').attr('data-flip', $(this).position().left < $('.character').position().left);
                });
            },
            restore(game) {
                //console.log(game.length);
                for(let i = 0; i < game.length; i++) $(`[data-row-id="${i}"] [data-cell-id="${game[i].cell}"]`).addClass('selected');
                this.setRow(game.length);
                if(game.length > 0) {
                    const e = $(`[data-row-id="${game.length - 1}"] [data-cell-id="${game[game.length - 1].cell}"]`);
                    $('.character').css({ top: e.position().top - $('.character').height(), left: e.position().left });
                }
            },
            dropStone(row, cell, index) {
                if (cell == 0) {
                    return;
                }
                const transformId = `_${Math.random()}`;
                const e = $('[data-icon-clone]').clone().attr('data-transform-id', transformId).attr('data-icon-clone', null);
                $(`[data-row-id="${row}"] [data-cell-id="${index}"]`).append(e);
                e.hide().css({ top: -40 });
                setTimeout(() => $(`[data-transform-id="${transformId}"]`).show().animate({top: -14}, 700), 300);
            },
            setRow(id, active = true) {
                if (id > 0) {
                    const rowstairs = $(`[data-row-id="${id}"]`).children();
                    const rowstairsprevious = $(`[data-row-id="${id - 1}"]`).children();
                    for (var i = 0; i < rowstairs.length; i++) {
                        if (rowstairs[i].classList.contains('stairsVisible') && rowstairsprevious[i].classList.contains('stairsVisible')) {
                            rowstairs[i].classList.toggle('active', active);
                        } 
                    }
                } else {
                    $(`[data-row-id="${id}"] .stairsCell`).toggleClass('active', active);
                }
            },
            typeBet(type) {
                switch (type) {
                    case "max":
                        if (this.$root.user !== null) {
                            this.bet = this.$root.user.balance;
                        } else {
                            this.bet = 0;
                        }
                        break;
                    case "min":
                        this.bet = 1.00;
                        break;
                    case "x2":
                        this.bet = (this.bet * 2).toFixed(2);
                        break;
                    case "/2":
                        this.bet = (this.bet / 2).toFixed(2);
                        break;
                }
            },
            autoSelect() {
                var notActive = $('.stairsVisible.active');
                $(notActive[Math.floor(Math.random()*notActive.length)]).click();
            },
        },
        mounted() {
            this.getUser();
            
            if (this.$cookie.get('token')) {
                //this.getGame();
            } else {
                this.$root.isLoading = false;
            }
            $(`[data-cell-id]`).on('mouseover', function() {
                if(!$(this).hasClass('active')) return;
                $('.character').attr('data-flip', $(this).position().left < $('.character').position().left);
            });
        }
    }

</script>

<style>
    .game-controller#disabled {
        opacity: .5!important;
    }
    .svg-inline--fa.fa-w-16 {
        width: 1em;
    }
    .svg-inline--fa {
        display: inline-block;
        font-size: inherit;
        height: 1em;
        overflow: visible;
        vertical-align: -.125em;
    }
    svg:not(:root).svg-inline--fa {
        overflow: visible;
    }
    .game-stairs .stairsColumns .stairsContainer .stairsRow .stairsCell.stairsVisible.active, .game-stairs .stairsColumns .stairsContainer .stairsRow .stairsCell.stairsVisible.selected {
        background: #fff;
    }
    .game-container {
        position: relative;
    }
    .game-container .col:first-child {
        flex: 0 0 300px;
    }
    .game-container .col {
        min-height: 308px;
    }
    .game-container .col:last-child {
        flex: 0 0 649px;
        padding-left: 0;
    }
    .game-sidebar {
        position: relative;
        display: flex;
        flex-direction: column;
        padding: 15px;
    }
    .game-sidebar .game-sidebar-tabs {
        display: flex;
        position: relative;
        top: -15px;
        left: -15px;
        width: calc(100% + 30px);
        background: rgba(29,28,34,.4);
    }
    .game-sidebar .game-sidebar-tabs .game-sidebar-tab.active {
        background: rgba(34,33,39,.4)!important;
    }
    .game-sidebar .game-sidebar-tabs .game-sidebar-tab:last-child {
        border-top-right-radius: 3px;
    }
    .game-sidebar .game-sidebar-tabs .game-sidebar-tab:first-child {
        border-top-left-radius: 3px;
    }
    .game-sidebar .game-sidebar-tabs .game-sidebar-tab {
        display: inline-flex;
        text-align: center;
        text-transform: uppercase;
        width: 50%;
        background: rgba(29,28,34,.4);
        padding: 15px;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        cursor: pointer;
        transition: background .3s ease;
    }
    .game-sidebar .game-sidebar-label {
        color: #b2b2b2;
        margin-bottom: 5px;
    }
    .wager-classic {
        position: relative;
    }
    .wager-classic input {
        border: 1px solid #B6B397;
        background: #1f1e24;
        border-radius: 8px 8px 0 0;
        padding: 10px;
        color: #fff;
        width: 100%;
        cursor: text;
        transition: background .3s ease;
        border-bottom: 1px solid #39414A;
    }
    input:disabled, input:read-only {
        cursor: default!important;
    }
    .wager-classic .wager-input-controls {
        position: absolute;
        top: 22px;
        transform: translateY(-50%);
        right: 15px;
        display: flex;
    }
    .wager-classic .wager-input-controls .control {
        display: inline-flex;
        opacity: .5;
        transition: opacity .3s ease;
        border-radius: 3px;
        padding: 5px;
        font-size: .6em;
        margin-right: 5px;
        box-shadow: 0 4px 12px 0 rgba(17,51,83,.02);
        background: #292830;
        height: 18px;
        cursor: pointer;
    }
    .wager-controls {
        background: #1f1e24;
        padding: 10px;
        border-bottom-left-radius: 3px;
        border-bottom-right-radius: 3px;
        display: flex;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
            border-radius: 0 0 8px 8px ;
        border: 1px solid #39414A;
        border-top: none;
    }
    .wager-controls .control {
        width: 20%;
        display: inline-flex;
        height: 100%;
        justify-content: center;
        align-items: center;
        text-align: center;
        font-size: 11px;
        transition: color .3s ease;
        color: #a8a8a8;
        cursor: pointer;
    }
    .game-sidebar .game-sidebar-buttons-container {
        display: flex;
        width: 100%;
        background: #24232a;
        position: relative;
    }
    .game-sidebar .game-sidebar-buttons-container .game-sidebar-buttons-container-button {
        display: inline-flex;
        cursor: pointer;
        background: transparent;
        width: 100%;
        text-align: center;
        align-items: center;
        justify-content: center;
        padding: .5rem;
        transition: background .3s ease,color .3s ease;
        color: #a8a8a8;
    }
    .game-sidebar .game-sidebar-buttons-container .game-sidebar-buttons-container-button:first-child {
        border-top-left-radius: 3px;
        border-bottom-left-radius: 3px;
    }
    .game-sidebar .game-sidebar-buttons-container .game-sidebar-buttons-container-button.active {
        background: #5ddaff;
        color: #000;
        border-radius: 3px;
    }
    .game-sidebar .play-button {
        font-size: 14px;
    }
    .game-stairs .stairsColumns {
        display: flex;
        flex-direction: row;
        width: 15.81cm;
    }
    .game-stairs .stairsColumns .stairsMultipliers {
        display: flex;
        flex-direction: column-reverse;
        margin-right: 0px;
        min-width: 45px;
    }
    .game-stairs .stairsColumns .stairsMultipliers .multiplier {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 33%;
        text-align: center;
        color: hsla(0,0%,100%,.45);
        cursor: default;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
    .game-stairs .stairsColumns .stairsContainer {
        display: flex;
        flex-direction: column;
        position: relative;
    }
    .game-stairs .stairsColumns .stairsContainer .stairsRow {
        display: flex;
        flex-direction: row;
        margin: 11px 0;
    }
    .game-stairs .stairsColumns .stairsContainer .stairsRow .stairsCell {
        display: inline-flex;
        width: 100%;
        padding: 7px 14px;
        margin-right: 4px;
        border-radius: 3px;
        transition: background .3s ease;
        position: relative;
    }
    .game-stairs .stairsColumns .stairsContainer .stairsRow .stairsCell.stairsVisible {
        background: #5ddaff;
        cursor: default;
    }
    .game-stairs .stairsColumns .stairsContainer .stairsRow .stairsCell.stairsVisible.active {
        cursor: pointer;
    }
    .game-stairs .stairsColumns .stairsContainer .character {
        pointer-events: none;
        height: 36px;
        width: 24px;
        position: absolute;
        bottom: -19px;
    }
    .game-stairs .stairsColumns .stairsContainer .stairsRow .stairsCell svg {
        width: 30px;
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
    }
    .game-stairs .stairsColumns .stairsContainer .stairsRow .stairsCell .fa-ladder {
        color: #ffc65d;
        font-size: 3em;
        top: -1px;
    }
    .game-stairs .stairsColumns .stairsContainer [data-flip=true] {
        transform: scaleX(-1);
    }
    .game-stairs .stairsColumns .stairsContainer .character.stand {
        background-image: url(/img/stairs/stand.png);
        -webkit-animation: character-stand .8s steps(10);
        animation: character-stand .8s steps(10);
        -webkit-animation-iteration-count: infinite;
        animation-iteration-count: infinite;
    }
    .game-stairs .stairsColumns .stairsContainer .character.run {
        background-image: url(/img/stairs/run.png);
        -webkit-animation: character-run .8s steps(10);
        animation: character-run .8s steps(10);
        -webkit-animation-iteration-count: infinite;
        animation-iteration-count: infinite;
    }
    .game-stairs .stairsColumns .stairsContainer .character.climb {
        background-image: url(/img/stairs/climb.png);
        -webkit-animation: character-climb .8s steps(10);
        animation: character-climb .8s steps(10);
        -webkit-animation-iteration-count: infinite;
        animation-iteration-count: infinite;
    }
    .game-stairs .stairsColumns .stairsContainer .character.victory {
        background-image: url(/img/stairs/victory.png);
        -webkit-animation: character-victory .8s steps(10);
        animation: character-victory .8s steps(10);
        -webkit-animation-iteration-count: infinite;
        animation-iteration-count: infinite;
    }
    .game-stairs .stairsColumns .stairsContainer .character.death {
        background-image: url(/img/stairs/death.png);
        -webkit-animation: character-death .8s steps(9);
        animation: character-death .8s steps(9);
        -webkit-animation-fill-mode: forwards;
        animation-fill-mode: forwards;
        -webkit-animation-iteration-count: 1;
        animation-iteration-count: 1;
        width: 30px;
    }
    .game-type-local {
        padding: 15px;
    }
    .game-content, .game-sidebar {
        border-radius: 5px;
        height: 100%;
        position: relative;
    }
    .game-stairs .game-content-stairs {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .game-content, .game-sidebar {
        background: rgba(34,33,39,.8);
        box-shadow: 0 4px 12px 0 rgba(17,51,83,.02);
        border: 1px solid rgba(25,24,29,.8);
        -webkit-backdrop-filter: blur(20px);
        backdrop-filter: blur(20px);
    }
    .btn-primary:active, .theme--dark .btn-primary:focus, .btn-primary:hover {
        background: #5ddaff!important;
    }
    #stairs-game-settings.disabled, .stairs-ingame-buttons.disabled {
        opacity: 0.5 !important;
    }
    .multipliers-carousel {
        display: none !important;
    }
    .game-stairs .stairsColumns .character {
        transform: scale(.7) !important;
        margin-top: 5px;
    }
    @media (max-width: 1650px) {
        .game-stairs .stairsColumns .stairsMultipliers .multiplier {
            height: 22.6%;
        }
        .game-stairs .stairsColumns .stairsContainer .stairsRow {
            margin: 11px 0;
        }
        .game-stairs .stairsColumns .stairsContainer .stairsRow .stairsCell {
            padding: 7px 13px;
        }
    }
    @media (max-width: 1400px) {
        .game-stairs .stairsColumns .stairsMultipliers .multiplier {
            height: 30.5%;
            font-size: .805em;
        }
        .game-stairs .stairsColumns .stairsContainer .stairsRow {
            margin: 11px 0;
        }
    }

    @media  (max-width: 768px){ 
        .game-stairs .stairsColumns .stairsContainer .stairsRow .stairsCell {
            padding: 4px 6px;
        }
        .game-stairs .stairsColumns .stairsContainer .stairsRow .stairsCell .fa-ladder {
            top: -1px;
        }
        .game-stairs .stairsColumns .stairsContainer .stairsRow .stairsCell svg {
            width: 22px;
        }
        .game-stairs .stairsColumns .stairsContainer .character {
            margin-left: -6px;
        }
        .game-stairs .stairsColumns .character {
            transform: scale(.5) !important;
            margin-top: 7px;
        }
        .game-stairs .stairsColumns [data-flip=true] {
            transform: scale(.5) scaleX(-1) !important;
        }
    }
    /* adaptivka */
    @media  (max-width: 650px){
            .game-sidebar{
                width:328px;
                top: 137%;
            }
            .game-stairs .stairsColumns{
                width: 8.830cm;
            }
            .game-stairs .game-content-stairs{
                bottom:76%;
            }
        }

    @media  (max-width: 450px){ 
        .stairsMultipliers {
            margin-left: -18px;
        }
    }

    @media  (max-width: 375px){ 
        .multipliers-carousel {
            display: flex !important;
        }
        .stairsMultipliers {
            display: none !important;
        }
        .game-stairs .stairsColumns {
            width: 100%;
        }
        .game-stairs .stairsColumns .stairsContainer .stairsRow .stairsCell {
            margin-right: 3px;
        }
    }

    @keyframes character-climb {
        from { background-position-x: 0; }
        to { background-position-x: -253px; }
    }

    @keyframes character-stand {
        from { background-position-x: 0; }
        to { background-position-x: -236px; }
    }

    @keyframes character-victory {
        from { background-position-x: 0; }
        to { background-position-x: -253px; }
    }

    @keyframes character-death {
        from { background-position-x: 0; }
        to { background-position-x: calc(-(352px - (352 / 10))); }
    }

    @keyframes character-run {
        from { background-position-x: 0; }
        to { background-position-x: -295px; }
    }
</style>
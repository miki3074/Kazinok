<template>
    <div>
        <div class="card">
            <div class="card-header pd-t-20">
                <div class="row">
                    <div class="col-6 col-lg-9">
                        <h4 style="padding-top: 5px">Слоты</h4>
                    </div>
                    <div class="col-6 col-lg-3 icon-append">
                        <div class="input-group input-group-sm">
                            <input 
                            type="text" 
                            class="form-control"
                            autocomplete="false"
                            placeholder="Название игры"
                            v-model="search"
                            readonly="readonly"
                            onfocus="this.removeAttribute('readonly');"
                            >
                        </div>
                        <i class="fas fa-search"></i>
                    </div>
                </div>
            </div>
            <div class="card-body pd-20 bd-b pd-b-20">
                <div class="row row-xs mg-b-25">
                    <router-link v-for="game in games" tag="div" :to="{name: 'slotsGame', params: { gameId: game.game_id }}" class="col-6 col-lg-3 mt-2 card-game">
                        <div class="card card-profile">
                            <img 
                                :src="game.icon" 
                                class="card-img-top" 
                                style="height:100%"
                            >
                            <div class="card-name">{{ game.title }}</div>
                        </div>
                    </router-link>
                </div>
                <div :class="[loader ? 'spinner-border' : '']" v-show="loader && !hideloader">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="tx-13 mg-b-0 tx-light text-center pb-5" v-show="games.length == 0 && !loader">Игры с указанными параметрами не найдены</p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                search: "",
                errors: {
                    show: false,
                    message: ''
                },
                games: [],
                show: 16,
                from: 0,
                loader: false,
                hideloader: false,
                delay: null,
            }
        },
        mounted() {
            this.$root.isLoading = true;
            
            let intViewportWidth = window.innerWidth;

            if(intViewportWidth >= 3840) {
                this.show = 80;
            } else if(intViewportWidth >= 2880) {
                this.show = 52;
            } else if(intViewportWidth >= 1920) {
                this.show = 36;
            } else if(intViewportWidth >= 1440) {
                this.show = 24;
            } else {
                this.show = 20;
            }

            $.fn.scrollEnd = function(callback, timeout) {          
                $(this).on('scroll', function(){
                    var $this = $(this);
                    if ($this.data('scrollTimeout')) {
                    clearTimeout($this.data('scrollTimeout'));
                    }
                    $this.data('scrollTimeout', setTimeout(callback,timeout));
                });
            };

            $(window).scrollEnd(function(){
                this.scroll();
            }, 500);

            window.addEventListener('scroll', this.scroll);
            this.getGames();
        },
        methods: {
            scroll () {
                let bottomOfWindow = Math.ceil(document.documentElement.scrollTop) + Math.ceil(window.innerHeight);
                
                if (bottomOfWindow >= document.documentElement.offsetHeight) {
                    this.loader = true;
                    this.from += this.show;
                    this.show = 16;
                    this.getGames();
                }
            },
            getGames() {
                this.notfound = false;
                this.$root.axios.post('/slots/getGames', {
                    show: this.show,
                    from: this.from,
                    search: this.search
                })
                .then(res => {
                    const data = res.data;

                    this.$root.isLoading = false;
                    this.loader = false;

                    if(data.list.length < this.show || data.list.length == 0) {
                        this.hideloader = true;
                    }

                    data.list.forEach(e => {
                        this.games.push({
                            game_id: e.game_id,
                            title: e.title,
                            icon: e.icon
                        });
                    });
                })
            }
        },
        watch: {
            search() {
                clearTimeout(this.delay);
                this.delay = setTimeout(() => {
                    this.from = 0;
                    this.games = [];
                    this.notfound = false;
                    this.hideloader = false;
                    this.loader = true;

                    this.getGames();
                }, 750);
            }
        }
    }
</script>

<style scoped>
.icon-append > i {
    position: absolute;
    right: 23px;
    top: 12px;
    opacity: .4;
    z-index: 200;
}
.icon-append > .input-group > input {
    padding-right: 24px;
}
.spinner-border {
    display: block; 
    margin: auto;
}
.card-game {
    cursor: pointer;
    margin-bottom: 5px;
    border-radius: 10px!important;
}
.card-game * {
    border-radius: 10px;
}
.card-game:hover {
    top: -2px;
}

.card-name {
    position: absolute;
    right: 10px;
    bottom: 10px;
    font-weight: 400;
    font-size: 9px;
    padding: 4px 12px;
    background: rgb(69 97 177 / 49%);
    color: #fff;
    border-radius: 4px;
    -webkit-transition: all .1s;
    transition: all .1s;
}

/* media */

@media(min-width: 992px) {
    .card-profile > img {
        min-width: 100%;
        min-height: 172px;
        max-height: 172px;
    }    
}
@media(max-width: 992px) and (orientation: landscape) {
    .card-profile > img {
        min-height: 210px;
        max-height: 210px;
    }
}
@media(max-width: 992px) and (orientation: portrait) {
    .card-profile > img {
        min-height: 130px;
        max-height: 130px;
    }
}
</style>
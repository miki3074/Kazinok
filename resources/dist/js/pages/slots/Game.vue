<template>
    <div class="row row-xs mg-b-25" style="height: 81vh;">
        <div class="container">
            <div class="game">
                <div class="game-component">
                    <div class="game">
                        <div class="game-component">
                            <div class="casino-play__wrapper-place" id="cas">
                                <div class="casino-play__controls" style="width: 100%;">
                                    <div class="casino-play__control casino-play__control_change" @click="iframeAction(1)">
                                        <span>Назад</span>
                                    </div>
                                    <div role="button" class="casino-play__control casino-play__control_fullscreen" @click="iframeAction(2)">
                                        <img src="/img/fullscreen.svg" alt="Полноэкранный режим" />
                                    </div>
                                    <div class="casino-play__control casino-play__control_externa" @click="iframeAction(3)">
                                        <img src="/img/external.svg" alt="Открыть игру в новом окне" />
                                    </div>
                                    <div class="casino-play__control casino-play__control_refresh" @click="iframeAction(4)">
                                        <img src="/img/refresh.svg" alt="Перезагрузить игру" />
                                    </div>
                                </div>
                                <fullscreen v-model="fullscreen" ref="fullscreen" class="container1">
                                    <iframe
                                        :src="gameUrl"
                                        scrolling="no"
                                        frameborder="0"
                                        webkitAllowFullScreen="true"
                                        allowfullscreen="true"
                                        mozallowfullscreen="true"
                                    />
                                </fullscreen>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style type="text/css">
    .container1.fullscreen {
        background: black;
        z-index: 1000 !important;
    }
</style>

<script>
    export default {
        data() {
            return {
                gameUrl: null,
                fullscreen: false,
            }
        },
        mounted() {
            this.$root.isLoading = true;
            this.getGames();
        },
        methods: {
            iframeAction(type) {
                if(type == 1) {
                    this.$router.push({name: 'slots'});
                } else if(type == 2) {
                    this.fullscreen = !this.fullscreen
                    //console.log("Test1");
                    //document.getElementsByTagName("iframe")[0].webkitRequestFullScreen();
                    //document.getElementsByTagName("iframe")[0].requestFullscreen();
                } else if(type == 3) {
                    window.open(this.gameUrl);
                } else if(type == 4) {
                    document.getElementsByTagName("iframe")[0].src = this.gameUrl;
                }
            },
            getGames() {
                this.isLoading = true;
                this.$root.axios.post('/slots/getUrl', {
                    show: this.show,
                    gameId: this.$route.params.gameId
                })
                .then(res => {
                    const data = res.data;
                    
                    this.$root.isLoading = false;

                    if(!data.error) return this.gameUrl = data.url;

                    this.$router.push({name: 'slots'});
                    $('#modalSignIn').modal('show');
                })
            }
        },
    }
</script>

<style scoped>
.game {
    display: -webkit-flex;
    display: flex;
    width: 100%;
    -webkit-align-items: stretch;
    align-items: stretch;
    position: relative
}

.game-component {
    -webkit-align-items: stretch;
    align-items: stretch;
    width: 100%;
    position: relative
}

.casino-play__wrapper-place {
    position: relative;
    width: 100%;
    padding-top: 56.5%;
    margin-top: 42px;
    border-radius: 6px;
    box-shadow: 0 0 50px rgb(143 157 174 / 20%);
    background-color: #fff;
}

.casino-play__wrapper-place iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border-radius: 6px;
    overflow: hidden;
}

.casino-play__controls {
    position: absolute;
    top: -42px;
    right: 0;
    display: flex;
    align-items: center;
}

.casino-play__control {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    background: #3b4863;
    border-radius: 7px;
    cursor: pointer;
}

.casino-play__control+.casino-play__control {
    margin-left: 5px;
}

.casino-play__control_change {
    width: 100px;
    margin-right: auto;
}

.casino-play__control>img {
    width: 18px;
    height: 18px;
    transition: filter .2s ease-in-out;
}

.casino-play__control_change>span {
    font-size: 16px;
    font-weight: 700;
    margin-bottom: -1px;
    color: #fff;
    text-transform: uppercase;
    transition: color .2s ease-in-out;
}

.game-block, .game-component {
    background: none;
}
</style>
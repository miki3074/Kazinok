<template>
    <div>
        <div class="row row-xs mg-b-25">
            <!-- <div class="col-12" v-if="$root.user !== null && $root.user.bonus_use == 0 && $root.user.ban == 0">
                <div class="alert alert-primary alert-dismissible fade show tx-13 absh" role="alert" style="border-radius: 5px;">
                    Вам доступен денежный бонус, <a href="#firstBonus" data-toggle="modal" class="">получить бесплатно</a>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                </div>
            </div> -->
            <div class="col-12" v-if="$root.user !== null && $root.user.ban == 0">
                <div class="alert alert-primary alert-dismissible fade show tx-13 absh" role="alert" style="border-radius: 5px;">
                    5 на баланс за каждого приглашенного пользователя,<router-link tag="a" :to="{name: 'referral'}" class=""> Рефералы</router-link>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                </div>
            </div>
            <div class="col-12" v-if="$root.user !== null && $root.user.vk_only == 1 && $root.user.ban == 0">
                <div class="alert alert-primary alert-dismissible fade show tx-13 absh" role="alert" style="border-radius: 5px;">
                    Вы можете установить логин и пароль <a href="#userSettings" data-toggle="modal" class="">в настройках</a>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                </div>
            </div>
            <div class="col-12" v-if="$root.user !== null && $root.user.ban == 1">
                <div class="alert alert-danger alert-dismissible fade show tx-13 absh" role="alert" style="border-radius: 5px;word-break: break-word;">
                    Ваш аккаунт заблокирован. Причина: {{ $root.user.ban_reason ? $root.user.ban_reason : 'не указана' }}
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card card-profile">

                    <img src="/dice2.png" class="card-img-top" alt="" style="height:135px">
                    <div class="card-body tx-13">
                        <div>
                            <h4 class="mg-t-45">Dice</h4>
                            <router-link tag="a" :to="{name: 'dice'}" class="btn btn-block btn-white bt-mob">Играть</router-link>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 ">
                <div class="card card-profile">
                    <img src="/mines2.png" class="card-img-top " alt="" style="height:135px">
                    <div class="card-body tx-13">
                        <div>
                            <h4 class="mg-t-45">Mines</h4>
                            <router-link tag="a" :to="{name: 'mines'}" class="btn btn-block btn-white bt-mob">Играть</router-link>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 mg-t-sm-10">
                <div class="card card-profile">
                    <img src="/coin1.png" class="card-img-top" alt="" style="height:135px">
                    <div class="card-body tx-13">
                        <div>
                            <h4 class="mg-t-45">Coin</h4>
                            <router-link tag="a" :to="{name: 'coinflip'}" class="btn btn-block btn-white bt-mob">Играть</router-link>
                        </div>
                    </div>
                </div>
            </div>
             <div class="col-6 col-lg-3 mg-t-sm-10">
                <div class="card card-profile">
                    <img src="/coin1.png" class="card-img-top" alt="" style="height:135px">
                    <div class="card-body tx-13">
                        <div>
                            <h4 class="mg-t-45">Coin</h4>
                            <router-link tag="a" :to="{name: 'coinflip'}" class="btn btn-block btn-white bt-mob">Играть</router-link>
                        </div>
                    </div>
                </div>
            </div>
       <div class="col-6 col-lg-3 mt-2 mg-t-sm-10">
                <div class="card card-profile">
                    <img src="/x501.png" class="card-img-top " alt="" style="height:135px">
                    <div class="card-body tx-13">
                        <div>
                            <h4 class="mg-t-45">Roulette</h4>
                            <router-link tag="a" :to="{name: 'roulette'}" class="btn btn-block btn-white bt-mob">Играть</router-link>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 mt-2 mg-t-sm-10">
                <div class="card card-profile">
                    <img src="/classic.jpg" class="card-img-top " alt="" style="height:135px">
                    <div class="card-body tx-13">
                        <div>
                            <h4 class="mg-t-45">Classic</h4>
                            <router-link tag="a" :to="{name: 'classic'}" class="btn btn-block btn-white bt-mob">Играть</router-link>
                        </div>
                    </div>
                </div>
            </div>
           <div class="col-6 col-lg-3 mt-2 mg-t-sm-10">
                <div class="card card-profile">
                    <img src="/stairs.jpeg" class="card-img-top " alt="" style="height:135px">
                    <div class="card-body tx-13">
                        <div>
                            <h4 class="mg-t-45">Stairs</h4>
                            
                            <router-link tag="a" :to="{name: 'stairs'}" class="btn btn-block btn-white bt-mob">Играть</router-link>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-6 col-lg-3 mt-2 mg-t-sm-10">
                <div class="card card-profile">
                    <img src="/slots3.png" class="card-img-top " alt="" style="height:135px">
                    <div class="card-body tx-13">
                        <div>
                            <h4 class="mg-t-45">Slots</h4>
                            <router-link tag="a" :to="{name: 'slots'}" class="btn btn-block btn-white bt-mob">Играть</router-link>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
        <AllHistory />
    </div>
</template>

<script>
    import AllHistory from "../../components/History/AllHistory";

    export default {
        components: {
            AllHistory
        }
    }
</script>

<style scoped>
    .btn{
        background: blue;
        border-color:blue ;
    }
    .card-body{
        background:#ebf1f7f0;
    }
    .mg-t-45{
        color:blue
    }
</style>
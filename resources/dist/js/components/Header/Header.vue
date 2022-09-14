<template>
    <div>        
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
            <meta name="apple-mobile-web-app-capable" content="yes">
        </head>
        <div class="navbar navbar-header navbar-header-fixed mob-head align-items-center" style="position: fixed;top: 0;left: 0;right: 0;z-index: 999;">
            <a href="" id="mainMenuOpen" class="burger-menu"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg></a>
            <div class="navbar-brand d-md-none" style="padding: 5px 0px 0px 15px;">
                <router-link tag="a" :to="{name: 'index'}" class="df-logo tx-26 ">DEMONEY</router-link>
            </div>
            <div class="head-online-stats online-stats" :class="[(this.$root.user) && ($root.user.current_rang > 0) ? '' : 'd-none' ]"><b style="font-size: 14px">{{ online }}</b><span>Online</span></div>
            <div id="mob-h" class="head-linkbox d-lg-none" :class="[(this.$root.user) && ($root.user.current_rang > 0) ? 'mob-h-1' : '' ]">
                <ul style="display: flex;">
                    <li @click="goToTg" id="tgchannel"  class="media align-items-center gameinc">
                        <div class="avatar" style="width: 35px; height: 35px;">
                            <span class="avatar-initial rounded bg-dark men-gam" style="box-shadow: 0 5px 23px 0 rgba(59, 72, 99, 0.25);">
                                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="2em" height="1.5em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 512 512"><path fill="currentColor" d="M470.435 45.423L16.827 221.249c-18.254 8.188-24.428 24.585-4.412 33.484l116.37 37.173l281.368-174.79c15.363-10.973 31.091-8.047 17.557 4.024L186.053 341.075l-7.591 93.076c7.031 14.371 19.905 14.438 28.117 7.295l66.858-63.589l114.505 86.187c26.595 15.826 41.066 5.613 46.788-23.394l75.105-357.47c7.798-35.705-5.5-51.437-39.4-37.757z"/></svg>
                            </span>
                        </div>
                        <div class="media-body pd-l-15">
                            <!-- <h6 class="mg-b-2"><a href="tg://resolve?domain=demoney_official" target="_blank" class="link-01 tx-18">TG</a></h6> -->
                        </div>
                    </li>
                    <li id="tgchannel" @click="goToVk" class="media align-items-center gameinc">
                        <div class="avatar" style="width: 35px; height: 35px;">
                            <span class="avatar-initial rounded bg-dark men-gam" style="box-shadow: 0 5px 23px 0 rgba(59, 72, 99, 0.25);">
                                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="2em" height="1.5em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M23.45 5.948c.166-.546 0-.948-.795-.948H20.03c-.668 0-.976.347-1.143.73c0 0-1.335 3.196-3.226 5.272c-.612.602-.89.793-1.224.793c-.167 0-.418-.191-.418-.738V5.948c0-.656-.184-.948-.74-.948H9.151c-.417 0-.668.304-.668.593c0 .621.946.765 1.043 2.513v3.798c0 .833-.153.984-.487.984c-.89 0-3.055-3.211-4.34-6.885C4.45 5.288 4.198 5 3.527 5H.9c-.75 0-.9.347-.9.73c0 .682.89 4.07 4.145 8.551C6.315 17.341 9.37 19 12.153 19c1.669 0 1.875-.368 1.875-1.003v-2.313c0-.737.158-.884.687-.884c.39 0 1.057.192 2.615 1.667C19.11 18.216 19.403 19 20.405 19h2.625c.75 0 1.126-.368.91-1.096c-.238-.724-1.088-1.775-2.215-3.022c-.612-.71-1.53-1.475-1.809-1.858c-.389-.491-.278-.71 0-1.147c0 0 3.2-4.426 3.533-5.929Z" clip-rule="evenodd"/></svg>
                            </span>
                        </div>
                        <!-- <div class="media-body pd-l-15">
                            <h6 class="mg-b-2"><a href="https://vk.com/demoney_official" target="_blank" class="link-01 tx-18">VK</a></h6>
                        </div> -->
                    </li>
                </ul>                
            </div>
            <!-- <div class="navbar-right">
                <div @click="$router.go(-1)" class="dropdown dropdown-notification  d-md-none" id="backArr">
                    <a class="" data-toggle="dropdown" style="color:#8f9dae">
                        <svg viewBox="0 0 24 22" width="22" height="25" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                    </a>
                </div>
            </div> -->
        </div>
        <header class="navbar navbar-header navbar-header-fixed mg-header" style="max-width: 1180px;margin-right: auto;margin-left: auto;position: fixed;top: 0;left: 0;right: 0;z-index: 999;">
            <div class="navbar-brand d-none d-md-flex mt-1" style="width: auto;">
                <router-link tag="a" :to="{name: 'index'}" class="df-logo tx-26 ">DEMONEY</router-link>
            </div>
            <div id="navbarMenu" class="navbar-menu-wrapper" style="margin: 0;">
                <div class="navbar-menu-header">
                    <router-link tag="a" :to="{name: 'index'}"  class="df-logo">DEMONEY</router-link>
                    <a id="mainMenuClose" href=""><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg></a>
                </div>
                <ul class="nav navbar-menu">
                    <li class="nav-item" :class="[page === 'index' ? 'active': '']">
                        <router-link tag="a" :to="{name: 'index'}" class="nav-link"> Главная</router-link>
                    </li>
                    <li class="nav-item" :class="[page === 'faq' ? 'active': '']">
                        <router-link tag="a" :to="{name: 'faq'}" class="nav-link"> FAQ</router-link>
                    </li>
                    <li class="nav-item" :class="[page === 'referral' ? 'active': '']">
                        <a v-if="$root.user === null" href="#modalSignIn" data-toggle="modal" class="nav-link"> Рефералы</a>
                        <router-link v-else tag="a" :to="{name: 'referral'}" class="nav-link"> Рефералы</router-link>
                    </li>
                    <li class="nav-item" :class="[page === 'bonus' ? 'active': '']">
                        <a v-if="$root.user === null" href="#modalSignIn" data-toggle="modal" class="nav-link"> Бонусы</a>
                        <router-link v-else tag="a" :to="{name: 'bonus'}" class="nav-link"> Бонусы</router-link>
                    </li>
                    <li class="nav-item mob-his" style="display: none;" :class="[page === 'history' ? 'active': '']">
                        <router-link tag="a" :to="{name: 'history'}" class="nav-link"> Выплаты</router-link>
                    </li>
                    <li class="nav-item" :class="[page === 'contacts' ? 'active': '']">
                        <a href="https://vk.com/im?media=&sel=-209880806&v=" target="_blank" class="nav-link"> Техподдержка</a>
                    </li>
                    <li class="nav-item" :class="[page === 'wallets' ? 'active': '']">
                        <a v-if="$root.user === null" href="#modalSignIn" data-toggle="modal" class="nav-link"> Реквизиты</a>
                        <router-link v-else tag="a" :to="{name: 'wallets'}" class="nav-link"> Реквизиты</router-link>
                    </li>
                    <li v-if="$root.user !== null && $root.user.is_admin == 1" class="nav-item">
                        <a class="nav-link" href="/admin" target="_blank"> ПУ</a>
                    </li>
                    <li v-else-if="$root.user !== null && $root.user.is_moder == 1" class="nav-item">
                        <a class="nav-link" href="/admin/users" target="_blank"> ПУ</a>
                    </li>
                    <li v-else-if="$root.user !== null && $root.user.is_promocoder == 1" class="nav-item">
                        <a class="nav-link" href="/admin/promocodes" target="_blank"> ПУ</a>
                    </li>
                </ul>
            </div>
            <div class="navbar-right" style="width:220px!important" v-if="$root.user === null">
                <a href="#modalSignIn" data-toggle="modal" class="btn btn-white btn-uppercase flex-fill mg-r-10 bt-mob" style="max-width:150px;cursor:pointer">Вход</a>
                <a href="#modalSignUp" data-toggle="modal" class="btn btn-brand-01 btn-uppercase flex-fill bt-mob rega" style="">Регистрация</a>
            </div>
            <div class="navbar-right mg-l-100 d-none d-md-flex" v-else>
                <div class="dropdown dropdown-profile ">
                    <a class="dropdown-link ">
                        <div class="header-rang-box" v-if="this.$root.user && $root.user.current_rang > 0">
                            <router-link tag="a" :to="{name: 'rang'}">
                                <div class="header-rang">
                                    <img :src="'/images/headerrang-' + $root.user.current_rang + '.png'" alt="">
                                </div>
                            </router-link>
                        </div>
                        <div class="avatar d-flex mr-2" v-else><span class="avatar-initial rounded-circle bg-dark">{{ $root.user.username.substr(0, 2) }}</span></div>
                        <div href="#userSettings" data-toggle="modal" style="cursor:pointer">
                            <span class="d-flex tx-13 tx-color-03 tx-light " style="margin-top: 3px;">{{ $root.user.username }}</span>
                            <h4 id="userBalance" mybalance="0" class="mg-b-3 odometer odometer-auto-theme" style="margin-top: -1px!important; width: max-content;"><div class="odometer-inside"><span class="odometer-digit"><span class="odometer-digit-spacer">{{ $root.user.balance.toFixed(2) }}</span><span class="odometer-digit-inner"><span class="odometer-ribbon"><span class="odometer-ribbon-inner"><span class="odometer-value">{{ $root.user.balance }}</span></span></span></span></span></div></h4>
                        </div>
                        <svg viewBox="0 0 24 24" width="15" height="15" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq122 tx-dark mg-l-4">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="d-md-none d-block mg-t-8 mg-l-15" v-if="$root.user !== null">
                <router-link tag="a" :to="{'name': 'withdraw'}" class="btn btn-white btn-uppercase bt-mob" style="max-width:150px;cursor:pointer">Вывод</router-link>
                <a href="#depositModal" data-toggle="modal" class="btn btn-brand-01 btn-uppercase mg-l-5 bt-mob" style="border-color: #2576ea;background: linear-gradient(45deg, #1c65c9 0%, #2c80ff 100%);">Пополнить</a>
            </div>
            <div v-if="$root.user !== null">
                <div class="d-md-none  dropdown-profile ">
                    <a class="dropdown-link " href="#userSettings" data-toggle="modal">
                        <div class="pd-l-15 ">
                            <span class="d-flex tx-15 tx-color-03 tx-light login-mob" style="margin-top: 3px;">{{ $root.user.username }}</span>
                            <h4 class="mg-b-3 balance-mob odometer odometer-auto-theme" id="userBalanceMobile" style="margin-top: -3px;"><div class="odometer-inside"><span class="odometer-digit"><span class="odometer-digit-spacer">{{ $root.user.balance.toFixed(2) }}</span><span class="odometer-digit-inner"><span class="odometer-ribbon"><span class="odometer-ribbon-inner"><span class="odometer-value">{{ $root.user.balance }}</span></span></span></span></span></div></h4>
                        </div>
                        <svg viewBox="0 0 24 24" width="12" height="12" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq122 tx-dark mg-l-5">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="navbar-right d-none d-md-flex" style="width:220px!important" v-if="$root.user !== null">
                <router-link tag="a" :to="{'name': 'withdraw'}" class="btn btn-white btn-uppercase flex-fill mg-r-10" style="max-width:150px;cursor:pointer">Вывод</router-link>
                <a href="#depositModal" data-toggle="modal" class="btn btn-brand-01 btn-uppercase flex-fill " style="border-color: #2576ea;background: linear-gradient(45deg, #1c65c9 0%, #2c80ff 100%);">Пополнить</a>
            </div>
            <div class="online-stats pt-2 pr-3" style="order: 4" :class="[(!this.$root.user) ? 'ml-0' : '', ((this.$root.user) && ($root.user.current_rang > 0)) ? 'online-stats-n' : '']"><b style="font-size: 14px">{{ online }}</b><span>Online</span></div>
            <div class="header-rang-box">
                <router-link tag="a" :to="{name: 'rang'}">
                    <div class="header-rang d-md-none" v-if="this.$root.user && $root.user.current_rang > 0">
                        <img :src="'/images/headerrang-' + $root.user.current_rang + '.png'" alt="">
                    </div>
                </router-link>
            </div>
        </header>
    </div>
</template>

<script>
    export default {
        props: ['page'],
        data() {
            return {
                online: 0
            }
        },
        mounted() {
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
        },
        methods: {
            goToTg() {
                window.open('tg://resolve?domain=demoney_play', '_blank');
            },
            goToVk() {
                window.open('https://vk.com/demoney_play', '_blank');
            }
        },
        sockets: {
            online: function (online) {
                this.online = online;
            }
        }
    }
</script>

<style type="text/css">

.online-stats {
    text-transform: uppercase;
    color: #61ac73;
    text-align: center;
    position: relative;
    font-size: 1rem;
    font-size: 10px; 
    order: 2;
}

.online-stats span {
    margin-top: 0em!important;
}

.head-linkbox {
    order: 3;
    display: flex;
    margin: 0 10px;
}

.head-linkbox ul {
    display: flex;
    margin-bottom: 0;
    padding: 0;
}

.header-rang {
    height: 100%;
    position: relative;
    width: 47px;
    display: flex;
    align-items: center;
}

.header-rang img {
    width: 160%;
    height: auto;
    position: absolute;
    right: 50%;
    transform: translateX(50%);
}

.online-stats::before {
    content: "";
    position: absolute;
    left: -6px;
    height: 0.35rem;
    width: 0.35rem;
    top: 0.85rem;
    border-radius: 50%;
    opacity: 0.4;
    background-color: currentColor;
    -webkit-animation: 2s online infinite linear;
    animation: 2s online infinite linear;
}

.online-stats b {
    font-weight: 400;
    font-size: 1.3rem;
    color: #7fd894;
}
.online-stats span {
    margin-top: 0.2em;
    display: block;
    color: #7d8599;
    font-weight: 500;
}

.head-online-stats {
    display: none;
}

#mob-h {
    margin-left: auto;
}

@media only screen and (max-width: 768px) {
    .dropdown-profile {
        margin-right: 15px;
    }
    .header-rang {
        right: 10px; 
    }
    .online-stats {
        margin-left: auto;
    }
    .mob-his {
        display: block !important;
    }
}

@media only screen and (max-width: 440px) {
    .online-stats-n {
        display: none !important;
    }
    .head-online-stats {
        display: block;
    }
    .mob-h-1 {
        margin-left: 10px !important;
    }
    .head-online-stats::before {
        top: 0.45rem;
    }
}

@media only screen and (max-width: 375px) {
    .header-rang {
        right: 0;
    }
    .dropdown-profile {
        margin-right: 0;
    }
}

@media only screen and (max-width: 370px) {
    .header-rang-box, .online-stats {
        display: none;
    }
    .head-online-stats {
        display: block !important;
    }
    
 }

@media only screen and (max-width: 330px) {
    .head-linkbox {
        margin-left: auto;
    }
    .head-online-stats {
        display: none !important;
    }
    #mob-h {
        margin-left: auto !important;
    }
}
</style>

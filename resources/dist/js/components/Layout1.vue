<template>
    <!-- <div v-if="$root.user !== null && $root.user.is_admin == 1 || $root.user !== null && $root.user.id == 20"> -->
    <div>
        <Header :page="page"/>
        <div class="content content-fixed mg-content">
            <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
                <div class="cont-des d-none mexe-let">
                    <h1>Winmoney | Winmoney</h1>
                    <p>Самые прибыльные и интересные игры <b>Winmoney онлайн</b> представлены на данном официальном сайте.
                    </p>
                    <p>Лучшие возможности для игры на официальном сайте Winmoney лицензионные игры, бонусы и быстрые
                        платежные методы для всех игроков.</p>
                    <h2>Winmoney промокод</h2>
                    <p>Играть на нашем сайте Winmoney - просто и прибыльно! </p>
                    <p>Платежная система выбирается, учитывая интересы региона и персональных желаний потенциальных
                        клиентов. Главными критериями отбора являются удобство и безопасность. Главным достоинством
                        большинства казино является наличие большого количества платежных систем, что позволяет каждому
                        посетителю выбрать способ оплаты, наиболее удобный для него.</p>
                    <p>Играть онлайн пользователь может без депозита своих средств в игру <b>Winmoney</b> онлайн это
                        значит, что бонусы не требуют обязательного пополнения счета. Мы гарантируем честную игру,
                        большие промокоды и быстрый вывод средств. Участвуйте в чемпионатах и лотереях, забирайте
                        промокоды за активную игру. Все онлайн игры предоставлены на нашем официальном сайте без
                        изменения функционала, каждую игру проверяйте на честность.</p>
                    <h3>Мобильная версия</h3>
                    <p>Тут уже готово все: лицензия, домен, технической обеспечение, контент, платежные системы и так
                        далее. Это требует минимальных затрат в плане времени и финансов, что является несомненным
                        плюсом. Однако заказчик в этом случае не сможет самостоятельно принимать все решения, касающиеся
                        казино. И тут уже каждый сам выбирает что ему важнее: минимальные затраты или возможность
                        самостоятельно принимать решения.</p>
                    <p>Компания предлагает качественную техническую поддержку. Бесперебойная система, работающая
                        ежедневно и круглосуточно, а также компетентные сотрудники понравятся игрокам. Каждый клиент
                        может задать службе поддержки любой интересующий его вопрос и получить на него ответ.</p>
                    <h4>Winmoney бонус</h4>
                    <p>Суммы призов также варьируются – от небольших утешительных призов, до действительно солидных
                        денежных вознаграждений. Всё зависит только от удачи, от того, насколько она благосклонна
                        сегодня к конкретному игроку. Вывести свой приз можно в любое время – достаточно заглянуть в
                        личный кабинет и заказать выплату средств.</p>
                    <p>Если казино имеет лицензию, то это гарантия законности его деятельности, что является необходимым
                        условием для привлечения пользователей. Также оно получает возможность работать с различными
                        платежными системами и популярными разработчиками программного обеспечения. Вариант
                        лицензирования является оптимальным для региона, в котором проживает заказчик. Клиенту также
                        предоставляется консультация по юридическим вопросам, интересующим его.</p>
                    <p>
                        Компания предоставляет заказчику специальное программное обеспечение, позволяющее осуществлять
                        управление финансами, безопасностью и контентом. Также происходит разработка и отладка функций,
                        дизайна и так далее. Сделать это самостоятельно было бы гораздо труднее.
                    </p>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-lg-2 mg-t-10 h-81vh">
                        <!-- <div class="align-items-center justify-content-between mg-b-20 d-none d-sm-flex">
                            <h2 class="tx-uppercase tx-semibold mg-b-0 ch-gm" style="color: #031a61;">Выбор игры</h2>
                        </div> -->
                        <ul class="list-unstyled media-list mg-b-15 d-none d-sm-block">
                            <li @click="goToDice" id="menudice" class="media align-items-center mg-t-15 gameinc" :class="[page === 'dice' ? 'present' : '']">
                                <div class="avatar">
                                <span class="avatar-initial rounded bg-dark men-gam" style="box-shadow: 0 5px 23px 0 rgba(59, 72, 99, 0.25);">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box">
                                        <path d="M12.89 1.45l8 4A2 2 0 0 1 22 7.24v9.53a2 2 0 0 1-1.11 1.79l-8 4a2 2 0 0 1-1.79 0l-8-4a2 2 0 0 1-1.1-1.8V7.24a2 2 0 0 1 1.11-1.79l8-4a2 2 0 0 1 1.78 0z"></path>
                                        <polyline points="2.32 6.16 12 11 21.68 6.16"></polyline>
                                        <line x1="12" y1="22.76" x2="12" y2="11"></line>
                                    </svg>
                                </span>
                                </div>
                                <div class="media-body pd-l-15">
                                    <h6 class="mg-b-2"><router-link tag="a" :to="{name: 'dice'}" class="link-01 tx-18">Dice</router-link></h6>
                                </div>
                            </li>

                            <li @click="goToMines" id="menumines" class="media align-items-center mg-t-15 gameinc" :class="[page === 'mines' ? 'present' : '']">
                                    <div class="avatar"><span class="avatar-initial rounded bg-dark men-gam" style="box-shadow: 0 5px 23px 0 rgba(59, 72, 99, 0.25)"><i class="ion ion-md-grid tx-22"></i></span></div>
                                <div class="media-body pd-l-15">
                                    <h6 class="mg-b-2"><router-link tag="a" :to="{name: 'mines'}" class="link-01 tx-18">Mines</router-link></h6>
                                </div>
                            </li>

                            <li @click="goToCoin" id="menucoin" class="media align-items-center mg-t-15 gameinc" :class="[page === 'coinflip' ? 'present' : '']">
                                <div class="avatar">
                                    <span class="avatar-initial rounded bg-dark men-gam" style="box-shadow: 0 5px 23px 0 rgba(59, 72, 99, 0.25);">
                                        <div class="avatar">
                                            <span class="avatar-initial rounded bg-dark men-gam" style="box-shadow: 0 5px 23px 0 rgba(59, 72, 99, 0.25);">
                                                <svg width="1em" height="1em" class="iconify tx-22">
                                                    <use xlink:href="#icon-coinflip">
                                                        <svg fill="currentColor" id="icon-coinflip" viewBox="0 0 32 32">
                                                            <path
                                                                d="M16 0c8.838 0 16 7.164 16 16s-7.162 16-16 16c-8.836 0-16-7.164-16-16s7.164-16 16-16zM20.697 11.539c-1.35-2.626-3.012-4.073-4.68-4.073s-3.33 1.446-4.681 4.073c-1.081 2.103-1.808 4.718-1.808 6.507 0 3.577 2.911 6.487 6.488 6.487s6.487-2.911 6.487-6.488c0-1.788-0.726-4.403-1.808-6.507z"
                                                            ></path>
                                                        </svg>
                                                    </use>
                                                </svg>
                                            </span>
                                        </div>
                                    </span>
                                </div>
                                <div class="media-body pd-l-15">
                                    <h6 class="mg-b-2"><router-link tag="a" :to="{name: 'coinflip'}" class="link-01 tx-18">Coin</router-link></h6>
                                </div>
                            </li>

                            <li @click="goToX50" id="menux50"  class="media align-items-center mg-t-15 gameinc" :class="[page === 'x50' ? 'present' : '']">
                                <div class="avatar">
                                    <span class="avatar-initial rounded bg-dark men-gam" style="box-shadow: 0 5px 23px 0 rgba(59, 72, 99, 0.25);">
                                        <svg width="1em" height="1em" class="iconify tx-22">
                                            <use xlink:href="#icon-wheel">
                                                <svg fill="currentColor" id="icon-wheel" viewBox="0 0 32 32">
                                                    <path
                                                        d="M8.752 6.112c1.556-1.144 3.39-1.932 5.381-2.237v-3.763c-3.019 0.351-5.781 1.542-8.047 3.334l2.666 2.666zM3.875 14.133c0.306-1.991 1.093-3.825 2.237-5.381l-2.666-2.666c-1.792 2.267-2.983 5.028-3.334 8.047h3.763zM28.554 6.086l-2.666 2.666c1.145 1.556 1.932 3.39 2.237 5.382h3.763c-0.351-3.020-1.542-5.781-3.334-8.047zM17.867 3.875c1.991 0.306 3.826 1.093 5.382 2.237l2.666-2.666c-2.267-1.792-5.028-2.983-8.047-3.334v3.763zM28.125 17.867c-0.305 1.991-1.093 3.826-2.237 5.382l2.666 2.666c1.792-2.267 2.983-5.028 3.334-8.047h-3.763zM23.249 25.888c-1.556 1.145-3.39 1.932-5.382 2.237v3.763c3.019-0.351 5.781-1.542 8.047-3.334l-2.666-2.666zM6.112 23.249c-1.144-1.556-1.932-3.39-2.237-5.382h-3.763c0.351 3.019 1.542 5.781 3.334 8.047l2.666-2.666zM14.133 28.125c-1.991-0.305-3.825-1.093-5.382-2.237l-2.666 2.666c2.267 1.792 5.028 2.983 8.047 3.334v-3.763h0.001z"
                                                    ></path>
                                                </svg>
                                            </use>
                                        </svg>
                                    </span>
                                </div>
                                <div class="media-body pd-l-15">
                                    <h6 class="mg-b-2"><router-link tag="a" :to="{name: 'x50'}" class="link-01 tx-18">X50</router-link></h6>
                                </div>
                            </li>
                            <!-- <li @click="goToClassic" id="menuclassic"  class="media align-items-center mg-t-15 gameinc" :class="[page === 'classic' ? 'present' : '']">
                                <div class="avatar">
                                    <span class="avatar-initial rounded bg-dark men-gam" style="box-shadow: 0 5px 23px 0 rgba(59, 72, 99, 0.25);">
                                        <svg width="1em" height="1em" class="iconify tx-22">
                                            <use xlink:href="#icon-classic">
                                                <svg fill="currentColor" id="icon-classic" viewBox="0 0 24 25">
                                                    <path
                                                        d="M10.5006 5.3449L12 7.942L13.4995 5.3449C17.1133 6.04429 19.8425 9.22609 19.8425 13.0444C19.8425 17.3757 16.3313 20.8869 12 20.8869C7.66871 20.8869 4.15749 17.3757 4.15749 13.0444C4.15749 9.22613 6.8867 6.04427 10.5006 5.3449ZM9.8068 4.14323L9.02394 2.78727C7.45816 3.24072 6.04062 4.04309 4.86092 5.10461L5.98381 6.12985C7.06507 5.18837 8.36927 4.4963 9.8068 4.14323ZM8.34501 1.61133C3.50513 3.15742 0 7.69083 0 13.0444C0 19.6718 5.37258 25.0444 12 25.0444C15.123 25.0444 17.9673 23.8514 20.1021 21.8963L20.1524 21.9423L21.0444 20.9654L21.0278 20.9502C22.8783 18.8388 24 16.0726 24 13.0444C24 7.69084 20.4949 3.15743 15.655 1.61134L16.4188 0.288454L7.58124 0.288452L8.34501 1.61133ZM14.9761 2.78728L14.1932 4.14323C15.7164 4.51733 17.0898 5.272 18.207 6.3007L19.3272 5.27795C18.1113 4.13032 16.626 3.2651 14.9761 2.78728ZM14.1276 1.61129L12 5.29633L9.87246 1.61129L14.1276 1.61129ZM1.34283 12.3856C1.48999 9.96829 2.44154 7.76789 3.93335 6.04897L5.05027 7.06877C3.80333 8.51755 3.00176 10.3606 2.85796 12.3856L1.34283 12.3856ZM1.34315 13.7084C1.49744 16.2227 2.52187 18.5018 4.11908 20.2481L5.23554 19.2287C3.8841 17.7514 3.01024 15.8298 2.85835 13.7084L1.34315 13.7084ZM11.3412 23.7015C8.95527 23.5563 6.78092 22.6275 5.07222 21.1691L6.19884 20.1405C7.61991 21.3036 9.39675 22.0483 11.3412 22.1864L11.3412 23.7015ZM19.1208 21.0003C17.3855 22.5546 15.1388 23.5493 12.6641 23.7012L12.6641 22.186C14.6972 22.0405 16.5468 21.2317 17.9978 19.975L19.1208 21.0003ZM20.0507 20.058L18.9339 19.0384C20.1888 17.588 20.9962 15.7398 21.1417 13.7084L22.6569 13.7084C22.5081 16.1325 21.5505 18.3379 20.0507 20.058ZM22.6572 12.3856L21.1421 12.3856C21.0047 10.4506 20.2667 8.68183 19.1134 7.26436L20.2315 6.24353C21.6284 7.93252 22.5155 10.0586 22.6572 12.3856ZM15.0237 15.2176C14.554 15.2176 14.1733 15.5983 14.1733 16.068C14.1733 16.5376 14.554 16.9184 15.0237 16.9184C15.4933 16.9184 15.8741 16.5376 15.8741 16.068C15.8741 15.5983 15.4933 15.2176 15.0237 15.2176ZM14.1297 14.0866C14.4024 13.9633 14.705 13.8948 15.0237 13.8948C16.2239 13.8948 17.1969 14.8677 17.1969 16.068C17.1969 17.2682 16.2239 18.2412 15.0237 18.2412C13.8234 18.2412 12.8504 17.2682 12.8504 16.068C12.8504 15.6706 12.9571 15.2981 13.1433 14.9776L12 13.9382L10.8567 14.9776C11.0429 15.2981 11.1496 15.6706 11.1496 16.068C11.1496 17.2682 10.1766 18.2412 8.97634 18.2412C7.7761 18.2412 6.80311 17.2682 6.80311 16.068C6.80311 14.8677 7.7761 13.8948 8.97634 13.8948C9.295 13.8948 9.59764 13.9633 9.87031 14.0866L11.0167 13.0444L9.87034 12.0022C9.59769 12.1254 9.29506 12.194 8.97642 12.194C7.77618 12.194 6.80319 11.221 6.80319 10.0207C6.80319 8.82049 7.77618 7.8475 8.97642 7.8475C10.1767 7.8475 11.1496 8.82049 11.1496 10.0207C11.1496 10.4181 11.043 10.7906 10.8567 11.1111L12 12.1505L13.1433 11.1111C12.957 10.7906 12.8504 10.4181 12.8504 10.0207C12.8504 8.82049 13.8233 7.8475 15.0236 7.8475C16.2238 7.8475 17.1968 8.82049 17.1968 10.0207C17.1968 11.221 16.2238 12.194 15.0236 12.194C14.7049 12.194 14.4023 12.1254 14.1297 12.0022L12.9833 13.0444L14.1297 14.0866ZM9.82673 16.068C9.82673 15.5983 9.446 15.2176 8.97634 15.2176C8.50668 15.2176 8.12594 15.5983 8.12594 16.068C8.12594 16.5376 8.50668 16.9184 8.97634 16.9184C9.446 16.9184 9.82673 16.5376 9.82673 16.068ZM8.97642 9.17034C8.50676 9.17034 8.12603 9.55107 8.12603 10.0207C8.12603 10.4904 8.50676 10.8711 8.97642 10.8711C9.44608 10.8711 9.82681 10.4904 9.82681 10.0207C9.82681 9.55107 9.44608 9.17034 8.97642 9.17034ZM15.874 10.0207C15.874 9.55107 15.4932 9.17034 15.0236 9.17034C14.5539 9.17034 14.1732 9.55107 14.1732 10.0207C14.1732 10.4904 14.5539 10.8711 15.0236 10.8711C15.4932 10.8711 15.874 10.4904 15.874 10.0207Z"
                                                    ></path>
                                                </svg>
                                            </use>
                                        </svg>
                                    </span>
                                </div>
                                <div class="media-body pd-l-15">
                                    <h6 class="mg-b-2"><router-link tag="a" :to="{name: 'classic'}" class="link-01 tx-18">Classic</router-link></h6>
                                </div>
                            </li> -->
                            <li @click="goToSlots" id="menuslots"  class="media align-items-center mg-t-15 gameinc" :class="[page === 'slots' || page === 'slotsGame' ? 'present' : '']">
                                <div class="avatar">
                                    <span class="avatar-initial rounded bg-dark men-gam" style="box-shadow: 0 5px 23px 0 rgba(59, 72, 99, 0.25);">
                                        <svg width="1em" height="1em" class="iconify tx-22">
                                            <use xlink:href="#icon-slots">
                                                <svg fill="currentColor" id="icon-slots" viewBox="0 0 32 32">
                                                    <path 
                                                        d="M23.989 19.258c-0.611 0-1.203 0.087-1.762 0.248l-5.196-11.389h4.733c2.795 0 5.060-2.265 5.060-5.060v-3.057h-5.928c-2.795 0-5.060 2.265-5.060 5.060v0 0.867c0-2.002-1.623-3.625-3.625-3.625v0h-4.513v2.19c0 2.002 1.623 3.625 3.625 3.625v0h3.646l-5.194 11.389c-0.527-0.157-1.132-0.248-1.758-0.248-0.002 0-0.003 0-0.005 0h0c-3.514 0-6.372 2.858-6.372 6.371s2.858 6.371 6.371 6.371 6.371-2.858 6.371-6.371c0-2.235-1.157-4.205-2.904-5.343l4.521-9.911 4.521 9.911c-1.758 1.153-2.903 3.114-2.904 5.342v0c0 3.512 2.858 6.371 6.371 6.371s6.371-2.858 6.371-6.371-2.858-6.371-6.371-6.371z">
                                                    </path>
                                                </svg>
                                            </use>
                                        </svg>
                                    </span>
                                </div>
                                <div class="media-body pd-l-15">
                                    <h6 class="mg-b-2"><router-link tag="a" :to="{name: 'slots'}" class="link-01 tx-18">Slots</router-link></h6>
                                </div>
                            </li>
                            <div class="align-items-center justify-content-between mg-t-20 d-none d-sm-flex" >
                                <h2 class="tx-uppercase tx-semibold mg-b-0 ch-gm" style="color: #031a61;">Соц. сети</h2>
                            </div>
                            <div style="display: flex; ">
                                <li @click="goToTg" id="tgchannel"  class="media align-items-center mg-t-15 gameinc">
                                    <div class="avatar">
                                        <span class="avatar-initial rounded bg-dark men-gam" style="box-shadow: 0 5px 23px 0 rgba(59, 72, 99, 0.25);">
                                            <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="2em" height="1.5em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 512 512"><path fill="currentColor" d="M470.435 45.423L16.827 221.249c-18.254 8.188-24.428 24.585-4.412 33.484l116.37 37.173l281.368-174.79c15.363-10.973 31.091-8.047 17.557 4.024L186.053 341.075l-7.591 93.076c7.031 14.371 19.905 14.438 28.117 7.295l66.858-63.589l114.505 86.187c26.595 15.826 41.066 5.613 46.788-23.394l75.105-357.47c7.798-35.705-5.5-51.437-39.4-37.757z"/></svg>
                                        </span>
                                    </div>
                                    <div class="media-body pd-l-15">
                                        <!-- <h6 class="mg-b-2"><a href="tg://resolve?domain=Winmoney_official" target="_blank" class="link-01 tx-18">TG</a></h6> -->
                                    </div>
                                </li>
                                <li id="tgchannel" @click="goToVk" class="media align-items-center mg-t-15 gameinc">
                                    <div class="avatar">
                                        <span class="avatar-initial rounded bg-dark men-gam" style="box-shadow: 0 5px 23px 0 rgba(59, 72, 99, 0.25);">
                                            <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="img" width="2em" height="1.5em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M23.45 5.948c.166-.546 0-.948-.795-.948H20.03c-.668 0-.976.347-1.143.73c0 0-1.335 3.196-3.226 5.272c-.612.602-.89.793-1.224.793c-.167 0-.418-.191-.418-.738V5.948c0-.656-.184-.948-.74-.948H9.151c-.417 0-.668.304-.668.593c0 .621.946.765 1.043 2.513v3.798c0 .833-.153.984-.487.984c-.89 0-3.055-3.211-4.34-6.885C4.45 5.288 4.198 5 3.527 5H.9c-.75 0-.9.347-.9.73c0 .682.89 4.07 4.145 8.551C6.315 17.341 9.37 19 12.153 19c1.669 0 1.875-.368 1.875-1.003v-2.313c0-.737.158-.884.687-.884c.39 0 1.057.192 2.615 1.667C19.11 18.216 19.403 19 20.405 19h2.625c.75 0 1.126-.368.91-1.096c-.238-.724-1.088-1.775-2.215-3.022c-.612-.71-1.53-1.475-1.809-1.858c-.389-.491-.278-.71 0-1.147c0 0 3.2-4.426 3.533-5.929Z" clip-rule="evenodd"/></svg>
                                        </span>
                                    </div>
                                    <!-- <div class="media-body pd-l-15">
                                        <h6 class="mg-b-2"><a href="https://vk.com/Winmoney_official" target="_blank" class="link-01 tx-18">VK</a></h6>
                                    </div> -->
                                </li>
                            </div>
                        </ul>
                    </div>
                    <div class="col-lg-10 col-xl-10 mg-t-10" id="loadPage" style="height:100vh" v-show="$root.isLoading">
                        <div class="spinner-border">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <div id="mainView" style="display: contents;" v-show="!$root.isLoading">
                        <div class="col-lg-10 col-xl-10 mg-t-10" :class="[page == 'x50' ? 'overflow-hidden' : '']">
                            <router-view/>
                        </div>
                    </div>
                    <SignIn v-if="$root.user === null" />
                    <SignUp v-if="$root.user === null" />
                    <UserSettings v-if="$root.user !== null" />
                    <Deposit v-if="$root.user !== null" />
                    <FirstBonus v-if="$root.user !== null && $root.user.bonus_use == 0" />
                    <CheckModal />
                </div>
            </div>
        </div>
        <div class="footer hash-mob ft-ct" style="display: flex;">
            <div class="stats-mob col-sm-6 col-lg-2 mg-t-10" style="margin-right: 50px;"></div>
            <div class="container">
                <div class="global-stats-wrapper">
                    <div class="global-stats">
                        <div class="global-stats__item">
                            <div class="global-stats__item-image"><img src="/img/users.svg" alt="Всего игроков"></div>
                            <div class="global-stats__item-value"><span>Всего игроков</span> <strong><span id="statsAllUsers" class="">{{ allUsers }}</span></strong></div>
                        </div>
                        <div class="global-stats__item">
                            <div class="global-stats__item-image"><img src="/img/games.svg" alt="Всего игр"></div>
                            <div class="global-stats__item-value"><span>Всего игр</span> <strong><span id="statsAllGames" class="">{{ allGames }}</span></strong></div>
                        </div>
                        <div class="global-stats__item">
                            <div class="global-stats__item-image"><img src="/img/profit.svg" alt="Выиграно сегодня"></div>
                            <div class="global-stats__item-value"><span>Выиграно сегодня</span> <strong><span id="statsTodayWin" class="">{{ todayWin.toFixed(2) }}</span></strong></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer hash-mob ft-ct">
            <footer class="footer container" style="border:0">
                <div>
                    <span>© 2022 Winmoney. All rights reserved </span>
                </div>
                <div>
                    <nav class="nav">
                        <router-link tag="a" :to="{name: 'terms'}" class="nav-link">Пользовательское соглашение</router-link>
                        <router-link tag="a" :to="{name: 'policy'}" class="nav-link">Политика конфеденциальности</router-link>
                        <a href="mailto:support@winmoney.one" class="nav-link"><u>support@winmoney.one</u></a>
                    </nav>
                </div>
            </footer>
        </div>
        <div class="footer hash-mob ft-ct">
            <footer class="footer container" style="border:0">
                <div>
                    <span>Operated by FINUP Ltd. </span>
                </div>
                <ul style="list-style: none;">
                    <li><b>Beneficiary:</b> INTERNATIONAL BUSINESS SYSTEMS S.R.L.</li>
                    <li><b>Reg. number:</b> 3-102-693823</li>
                    <li><b>Beneficiary’s address:</b> SAN JOSE-SANTA ANA,THREE HUNDRED AND
FIFTY METERS OF THE RESTAURANT CEVICHE DEL REY, COSTA-RICA</li>
                </ul>
            </footer>
        </div>
        <!-- <span class="snowman" style="right: 1.7%; opacity: 1;"></span>
        <span class="snow-layer"></span> -->
    </div>
    <!-- <div v-else>
            <span>Технические работы</span>
    </div> -->
    
</template>

<script>
    //import { VueRecaptcha } from 'vue-recaptcha';
    import Header from "./Header/Header";
    import SignIn from "./Modals/SignIn";
    import SignUp from "./Modals/SignUp";
    import UserSettings from "./Modals/UserSettings";
    import CheckModal from "./Modals/CheckModal";
    import Deposit from "./Modals/Deposit";
    import FirstBonus from "./Modals/FirstBonus";

    export default {
        components: {
            Header,
            SignIn,
            SignUp,
            UserSettings,
            CheckModal,
            Deposit,
            FirstBonus,
            //VueRecaptcha
        },
        data() {
            return {
                page: null,
                allUsers: 0,
                allGames: 0,
                todayWin: 0,
            }
        },
        methods: {
            goToDice() {
                if (this.page !== 'dice') this.$router.push({name: 'dice'});
            },
            goToMines() {
                if (this.page !== 'mines') this.$router.push({name: 'mines'});
            },
            goToCoin() {
                if (this.page !== 'coin') this.$router.push({name: 'coinflip'});
            },
            goToX50() {
                if (this.page !== 'x50') this.$router.push({name: 'x50'});
            },
            goToClassic() {
                if (this.page !== 'classic') this.$router.push({name: 'classic'});
            },
            goToSlots() {
                if (this.page !== 'slots') this.$router.push({name: 'slots'});
            },
            goToTg() {
                window.open('tg://resolve?domain=Winmoney_official', '_blank');
            },
            goToVk() {
                window.open('https://vk.com/Winmoney_official', '_blank');
            }
        },
        beforeMount() {
            this.page = this.$router.currentRoute.name;
        },
        beforeUpdate() {
            this.page = this.$router.currentRoute.name;
        },
        sockets: {
            stats: function (stats) {
                var oldUsers = this.allUsers;
                var newUsers = stats.users;
                this.$root.updateStats1(newUsers, oldUsers);
                this.allUsers = newUsers;

                var oldGames = this.allGames;
                var newGames = stats.bets;
                this.$root.updateStats2(newGames, oldGames);
                this.allGames = newGames;

                var oldToday = this.todayWin;
                var newToday = stats.sum;
                this.$root.updateStats3(newToday, oldToday);
                this.todayWin = newToday;
            }
        }
    }
</script>

<style type="text/css">
    .global-stats-wrapper {
        display: flex;
        justify-content: center;
    }
    .global-stats {
        margin-bottom: 20px;
        margin-top: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        width: 100%;
    }
    .global-stats__item {
        width: 33%;
        display: flex;
        align-items: center;
    }
    .global-stats__item-image>img {
        width: 46px;
        height: 46px;
    }
    .global-stats__item-value {
        margin-left: 15px;
        display: flex;
        flex-direction: column;
    }
    .global-stats__item-value>span {
        color: #8f9dae;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .global-stats__item-value>strong {
        font-weight: 700;
        font-size: 20px;
        color: #000;
        margin-top: 12px;
    }
    @media (max-width: 991px) {
        .stats-mob {
            display: none;
        }
        .global-stats {
            width: auto;
        }
        .global-stats__item {
            width: 40%;
        }
        .global-stats__item:nth-of-type(n+3) {
            margin-top: 40px;
        }
        .global-stats {
            width: auto;
        }
    }
    @media (max-width: 500px) {
        .global-stats {
            flex-direction: column;
        }
        .global-stats__item {
            width: 100%;
        }
        .global-stats__item:nth-of-type(n+2) {
            margin-top: 30px;
        }
        .global-stats {
            flex-direction: column;
        }
    }

    
</style>
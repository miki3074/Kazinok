import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

import Index from "./pages/index/Index";
import NotFound from "./pages/not-found/NotFound";
import AuthCallback from "./pages/auth-callback/AuthCallback";
import Dice from "./pages/dice/Dice";
import Mines from "./pages/mines/Mines";
import Coinflip from "./pages/coinflip/Coinflip";
//import x50 from "./pages/x50/x50";
import Classic from "./pages/classic/Classic";
//import Slots from "./pages/slots/Slots";
//import SlotsGame from "./pages/slots/Game";
import About from "./pages/about/About";
import Faq from "./pages/faq/Faq";
import Referral from "./pages/referral/Referral";
import Bonus from "./pages/bonus/Bonus";
import Contacts from "./pages/contacts/Contacts";
import Withdraw from "./pages/withdraw/Withdraw";
import History from "./pages/withdraw/History";
import Terms from "./pages/terms/Terms";
import Policy from "./pages/policy/Policy";
import Wallets from "./pages/wallets/Wallets";
//import Stairs from "./pages/stairs/Stairs";
//import Rang from "./pages/rang/Rang";

export default new VueRouter({
    mode: 'history',
    routes: [
        //{
        //    path: '/rang',
        //    name: 'rang',
        //    component: Rang
        //},
        //{
        //    path: '/stairs',
        //    name: 'stairs',
        //    component: Stairs 
        //},
        {
            path: '/',
            name: 'index',
            component: Index
        },
        {
            path: '/history',
            name: 'history',
            component: History
        },
        {
            path: '/wallets',
            name: 'wallets',
            component: Wallets
        },
        {
            path: '/auth/callback',
            name: 'auth-callback',
            component: AuthCallback
        },
        {
            path: '/dice',
            name: 'dice',
            component: Dice
        },
        {
            path: '/mines',
            name: 'mines',
            component: Mines
        },
        {
            path: '/coinflip',
            name: 'coinflip',
            component: Coinflip
        },
        //{
        //    path: '/roulette',
        //    name: 'roulette',
        //    component: x50
        //},
        //{
        //    path: '/classic',
        //    name: 'classic',
        //    component: Classic
        //},
        //{
        //    path: '/slots',
        //    name: 'slots',
        //    component: Slots
        //},
        //{
        //    path: '/slots/game/:gameId',
        //    name: 'slotsGame',
        //    component: SlotsGame
        //},
        // {
        //     path: '/about',
        //     name: 'about',
        //     component: About
        // },
        {
            path: '/referral',
            name: 'referral',
            component: Referral
        },
        {
            path: '/bonus',
            name: 'bonus',
            component: Bonus
        },
        {
            path: '/faq',
            name: 'faq',
            component: Faq
        },
        {
            path: '/contacts',
            name: 'contacts',
            component: Contacts
        },
        {
            path: '/withdraw',
            name: 'withdraw',
            component: Withdraw
        },
        {
            path: '/terms',
            name: 'terms',
            component: Terms
        },
        {
            path: '/policy',
            name: 'policy',
            component: Policy
        },
        {
            path: '*',
            name: 'not-found',
            component: NotFound
        }
    ]
});

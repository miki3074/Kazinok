const app = require('express')(),
     fs = require('fs'),
     options = {
        key: fs.readFileSync('/var/www/httpd-cert/www-root/demoney.in_le1.key', 'utf8'),
        cert: fs.readFileSync('/var/www/httpd-cert/www-root/demoney.in_le1.crt', 'utf8')
    },
    server = require('https').createServer(options, app),
    //server = require('http').createServer(app),
    Redis = require('redis'),
    RedisClient = Redis.createClient(),
    io = require('socket.io')(server),
    axios = require('axios');

server.listen(8443);

const myArgs = process.argv.slice(2);
const domain = 'https://demoney.bid';

process.env.NODE_TLS_REJECT_UNAUTHORIZED = "0";

let timerBot = null,
    interval = null;
    wheel_start = null;
    wheel_timer = 20;
    online = 170;
    stats = null;

RedisClient.subscribe('newGame');
RedisClient.subscribe('newWithdraw');
RedisClient.subscribe('setNewBotTimer');
RedisClient.subscribe('wheel');
RedisClient.subscribe('jackpot');
RedisClient.subscribe('jackpot.timer');
RedisClient.subscribe('jackpot.slider');

RedisClient.on('message', async (channel, message) => {
    if(channel == 'jackpot.timer') {
        message = JSON.parse(message);
        startJackpotTimer(message);
        return;
    }
    if (channel == 'wheel') {
        let emit_type = JSON.parse(message);
        io.sockets.emit(emit_type.type, JSON.parse(message));
    }
    if (channel === 'setNewBotTimer') {
        clearInterval(interval);
        interval = null;
        timerBot = message;

        startBot();
    } else {
        io.sockets.emit(channel, JSON.parse(message));
    }
});

//stats
const startStats = () => {
    interval = setInterval(() => {
        axios.get(`${domain}/api/stats/make`)
            .then(res => {
                stats = res.data;
                io.sockets.emit('stats', stats);
            });
    }, 10000);
};

// bots
const startBot = () => {
    interval = setInterval(() => {
        axios.post(`${domain}/api/fake`)
            .then(res => {

            });
    }, 60000 * timerBot);
};

const getTimer = () => {
    axios.post(`${domain}/api/getTimer`)
        .then(res => {
            timerBot = res.data;

            startBot();
        });
};

// wheel

const startWheelTimer = (time) => {
    time = time - 1;
    if(wheel_start !== null) return;
    wheel_start = true;
    let timer = setInterval(() => {
        if(time < 0) {
            clearInterval(timer);
            showWheelSlider();
            return;
        } else {
            io.sockets.emit('wheel_start', {
                timer: time
            });
        }
        time--;
    }, 1*1000)
}

const showWheelSlider = () => {
    axios.post(`${domain}/api/wheel/close`)
    .then(function(res) {        
        res = res.data;

        io.sockets.emit('wheel_roll', {
            'roll': {
                'data': res.rotate[0]
            }
        });
        setTimeout(() => {
            newWheelGame();
        }, 7000);
    }, function(res) {
        console.log(res);
        console.log('???????????? ?? ?????????????? showWheelSlider');
        setTimeout(() => showWheelSlider(), 1500);
    });
}

const newWheelGame = () => {
    axios.post(`${domain}/api/wheel/end`)
    .then(function(res) {
        res = res.data;
        wheel_start = null;
        io.sockets.emit('wheel_clear', {
            'clear': {
                'data': 'clear_all'
            },
            'last': {
                'data': res.last
            }
        });
        io.sockets.emit('wheel_start', {
            timer: wheel_timer
        });
        startWheelTimer(wheel_timer);
    }, function(res) {
        console.log('???????????? ?? ?????????????? wheelNewGame');
        setTimeout(() => newWheelGame(), 1500);
    });
}

// jackpot

var currentTimers = [];
function startJackpotTimer(res) {
    if(typeof currentTimers[res.room] == 'undefined') currentTimers[res.room] = 0;
    if(currentTimers[res.room] != 0 && (currentTimers[res.room] - new Date().getTime()) < ((res.time+1)*1000)) return;
    currentTimers[res.room] = new Date().getTime();
    let time = res.time;
    let timer = setInterval(() => {
        if(time <= 0) {
            clearInterval(timer);
            io.sockets.emit('jackpot', {
                type: 'timer',
                room: res.room,
                data: {
                    min: Math.floor(time/60),
                    sec: time-(Math.floor(time/60)*60)
                }
            });
            currentTimers[res.room] = 0;
            showJackpotSlider(res.room, res.game);
            return;
        }
        time--;
        io.sockets.emit('jackpot', {
            type: 'timer',
            room: res.room,
            data: {
                min: Math.floor(time/60),
                sec: time-(Math.floor(time/60)*60)
            }
        });
    }, 1*1000)
}

function showJackpotSlider(room, game) {
    axios.post(`${domain}/api/jackpot/slider`, {
        room: room,
        game: game
    })
    .then(function(res) {
        let timeout = setTimeout(() => {
            clearInterval(timeout);
            newJackpotGame(room);
        }, 8.5*1000)
    }, function(res) {
        console.log('???????????? ?? ?????????????? slider');
        setTimeout(() => showJackpotSlider(), 1500);
    });
}

function newJackpotGame(room) {
    axios.post(`${domain}/api/jackpot/newGame`, {
        room : room
    })
    .then(function(res) {
        res = res.data;
        io.sockets.emit('jackpot', {
            type: 'newGame',
            room: room,
            data: res
        });
    }, function(res) {
        console.log('[ROOM '+room+'] ???????????? ?? ?????????????? newGame');
        setTimeout(() => newJackpotGame('easy'), 1500);
    });
}


function getJackpotStatus(room) {
    axios.post(`${domain}/api/jackpot/getStatus`, {
        room: room
    })
    .then(function() {

    }, function(res) {
        console.log('???????????? ?? ?????????????? getStatus');
        setTimeout(() => getJackpotStatus('easy'), 1500);
    });
}

io.on('connection', (socket) => {

    online += 1;

    socket.on('disconnect', () => {
        online -= 1;
    });

    io.sockets.emit('online', online);
    io.sockets.emit('stats', stats);

    //wdrs

    socket.on('withdraw', (newWithdraw,callback)=> {
        axios.post(`${domain}/api/withdraw/create`, {
            sum: newWithdraw.sum,
            wallet: newWithdraw.wallet,
            system: newWithdraw.system,
            page: newWithdraw.page,
            id: newWithdraw.id
        })
        .then(res => {
            const data = res.data;
            console.log("?????????? ???? ???????????????????????? #" + newWithdraw.id + " ??????????: " + newWithdraw.sum);
            callback(data);
        });
    });

    socket.on('withdrawDecline', (declineWithdraw,callback)=> {
        axios.post(`${domain}/api/withdraw/decline`, {
            id: declineWithdraw.id,
            user_id: declineWithdraw.user_id
        })
        .then(res => {
            const data = res.data;
            console.log("???????????? ???????????? #" + declineWithdraw.id + " ????????????????????????: " + declineWithdraw.user_id);
            callback(data);
        });
    });

    //

    //coin

    socket.on('coinCreate', (newCoin,callback)=> {
        axios.post(`${domain}/api/coin/create`, {
            bet: newCoin.bet,
            id: newCoin.id
        })
        .then(res => {
            const data = res.data;
            console.log("???????? ?? ????????, ???????????????????????? #" + newCoin.id + " ??????????: " + newCoin.bet);
            callback(data);
        });
    });

    socket.on('coinTake', (newCoin,callback)=> {
        axios.post(`${domain}/api/coin/take`, {
            bet: newCoin.bet,
            id: newCoin.id
        })
        .then(res => {
            const data = res.data;
            callback(data);
        });
    });

    socket.on('coinBet', (newCoin,callback)=> {
        axios.post(`${domain}/api/coin/bet`, {
            type: newCoin.type,
            id: newCoin.id
        })
        .then(res => {
            const data = res.data;
            console.log("???????? ?? ????????, ???????????????????????? #" + newCoin.id + " ???????????? ????: " + newCoin.type);
            callback(data);
        });
    });

    //

    //dice

    socket.on('diceBet', (newDice,callback)=> {
        axios.post(`${domain}/api/dice/bet`, {
            type: newDice.type,
            bet: newDice.bet,
            id: newDice.id,
            chance: newDice.chance,
            hid: newDice.hid,
            a: newDice.a
        })
        .then(res => {
            const data = res.data;
            console.log("???????? ?? ????????, ???????????????????????? #" + newDice.id + " ????????: " + newDice.chance + " ??????????????: " + data.success + " ????????????: " + newDice.bet + " ???????????? ??????????: " + data.balance);
            callback(data);
        });
    });

    //dice

    //mines

    socket.on('minesCreate', (newMines,callback)=> {
        axios.post(`${domain}/api/mines/create`, {
            bet: newMines.bet,
            bomb: newMines.bomb,
            id: newMines.id
        })
        .then(res => {
            const data = res.data;
            console.log("?????????????? ???????? ?? ????????, ???????????????????????? #" + newMines.id + " ??????????: " + newMines.bet);
            callback(data);
        });
    });

    socket.on('minesBet', (newMines,callback)=> {
        axios.post(`${domain}/api/mines/bet`, {
            bomb: newMines.bomb,
            id: newMines.id
        })
        .then(res => {
            const data = res.data;
            console.log("???????? ?? ????????, ???????????????????????? #" + newMines.id + " ?????????????? ????????: " + newMines.bomb + " ??????????????: " + data.win);
            callback(data);
        });
    });

    socket.on('minesCashout', (newMines,callback)=> {
        axios.post(`${domain}/api/mines/take`, {
            id: newMines.id
        })
        .then(res => {
            const data = res.data;
            console.log("????????, ???????????????????????? #" + newMines.id + " ??????????????: " + data.win);
            callback(data);
        });
    });

    socket.on('minesGet', (newMines,callback)=> {
        axios.post(`${domain}/api/mines/get`, {
            id: newMines.id
        })
        .then(res => {
            const data = res.data;
            callback(data);
        });
    });

    //

     //stairs

    socket.on('stairsCreate', (newStairs,callback)=> {
        axios.post(`${domain}/api/stairs/create`, {
            bet: newStairs.bet,
            bomb: newStairs.mines,
            id: newStairs.id
        })
        .then(res => {
            const data = res.data;
            console.log("?????????????? ???????? ?? ??????????????, ???????????????????????? #" + newStairs.id + " ??????????: " + newStairs.bet);
            callback(data);
        });
    });

    socket.on('stairsBet', (newStairs,callback)=> {
        axios.post(`${domain}/api/stairs/bet`, {
            bomb: newStairs.bomb,
            id: newStairs.id
        })
        .then(res => {
            const data = res.data;
            console.log("???????? ?? ??????????????, ???????????????????????? #" + newStairs.id + " ?????????????? ????????????: " + newStairs.bomb + " ????????????: " + data.status);
            callback(data);
        });
    });

    socket.on('stairsCashout', (newStairs,callback)=> {
        axios.post(`${domain}/api/stairs/take`, {
            id: newStairs.id
        })
        .then(res => {
            const data = res.data;
            console.log("??????????????, ???????????????????????? #" + newStairs.id + " ??????????????: " + data.win);
            callback(data);
        });
    });

    socket.on('stairsGet', (newStairs,callback)=> {
        axios.post(`${domain}/api/stairs/get`, {
            id: newStairs.id
        })
        .then(res => {
            const data = res.data;
            callback(data);
        });
    });

    //

    // bonus

    socket.on('getPromo', (newBonus,callback)=> {

        axios.post(`${domain}/api/promo/getPromo`, {
            id: newBonus.id
        })
        .then(res => {
            const data = res.data;
            if (data.success) {
                console.log("???????????????????????? #" + newBonus.id + " ?????????????????????? ??????????");
            }
            callback(data);
        });
    });

    socket.on('setPromo', (newPromo,callback)=> {

        axios.post(`${domain}/api/promo/setPromo`, {
            id: newPromo.id,
            promocode: newPromo.promocode
        })
        .then(res => {
            const data = res.data;
            if (data.success) {
                console.log("???????????????????????? #" + newPromo.id + " ?????????????????????? ????????????????: " + newPromo.promocode);
            }            
            callback(data);
        });
    });

    //

    //user log

    socket.on('userSession', (newSession)=> {

        var ip = socket.conn.remoteAddress;
        ip = ip.replace('::ffff:', '');
        
        axios.post(`${domain}/api/userSession`, {
            type: newSession.type,
            id: newSession.id,
            ip: ip,
        })
        .then(res => {
            //console.log(newSession.type + " ???????????????????????? #" + newSession.id);
        });
    });

    //

})

getTimer();
getJackpotStatus('easy');
startWheelTimer(wheel_timer);
startStats();
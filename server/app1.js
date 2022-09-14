const app = require('express')(),
     fs = require('fs'),
     options = {
        key: fs.readFileSync('/etc/letsencrypt/live/demoney.one/privkey.pem', 'utf8'),
        cert: fs.readFileSync('/etc/letsencrypt/live/demoney.one/fullchain.pem', 'utf8')
    },
    server = require('https').createServer(options, app),
    //server = require('http').createServer(app),
    Redis = require('redis'),
    RedisClient = Redis.createClient(),
    io = require('socket.io')(server),
    axios = require('axios');

server.listen(8443);

const myArgs = process.argv.slice(2);
const domain = 'https://demoney.one';

process.env.NODE_TLS_REJECT_UNAUTHORIZED = "0";

let timerBot = null,
    interval = null;
    wheel_start = null;
    wheel_timer = 20;

RedisClient.subscribe('newGame');
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

io.on('connection', (socket) => {
    const updateOnline = () => {
        io.sockets.emit('online', Object.keys(io.sockets.adapter.rooms).length);
    };

    socket.on('disconnect', () => {
        updateOnline();
    });
})

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
        console.log('Ошибка в функции showWheelSlider');
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
        console.log('Ошибка в функции wheelNewGame');
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
        console.log('Ошибка в функции slider');
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
        console.log('[ROOM '+room+'] Ошибка в функции newGame');
        setTimeout(() => newJackpotGame('easy'), 1500);
    });
}


function getJackpotStatus(room) {
    axios.post(`${domain}/api/jackpot/getStatus`, {
        room: room
    })
    .then(function() {

    }, function(res) {
        console.log('Ошибка в функции getStatus');
        setTimeout(() => getJackpotStatus('easy'), 1500);
    });
}

getTimer();
getJackpotStatus('easy');
startWheelTimer(wheel_timer);
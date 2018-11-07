let app = {
    width: 1600,
    height: 900,
    game: $('#game'),
    player: $('#main'),

    isRunning: true,
    isGameOver: true,
    isMute: false,

    spaceKeyState: false,

    scoreCounter: 0,
    fuelCounter: 20,
    timeCounter: 0,
    size: 24,
    volume: 1,
    fuelConsume: 1,

    main: {
        x: 100,
        y: 200,
        speed: 8
    },
    planet: {
        qty: 5,
        speed: 1,
        time: 1800,
        timer: 0
    },
    asteroid: {
        qty: 4,
        speed: 3,
        time: 4000,
        timer: 0
    },
    enemy: {
        qty: 2,
        speed: 3,
        time: 5000,
        timer: 0
    },
    friend: {
        speed: 3,
        time: 6000,
        timer: 0
    },
    fuel: {
        speed: 1.5,
        time: 7000,
        timer: 0
    },

    enemyShot: {
        speed: 15,
        time: 3000,
        timer: 0
    },
    mainShot: {
        speed: -15
    },
    counterTimer: 0,
    checkMainShotTimer: 0,
    backgroundSound: null,
    checkOver: false
};

function rand(num) {
    return Math.floor(Math.random() * num) + 1;
}

function randPos() {
    return {
        x: rand(app.width - 100),
        y: rand(app.height - 100)
    }
}

function getInfoObject(obj) {
    return {
        w: obj.width(),
        h: obj.height(),
        x: obj.position().left,
        y: obj.position().top
    }
}

function checkOverlap(mainObj, otherObj) {
    let main = getInfoObject(mainObj);
    let other = getInfoObject(otherObj);

    return (other.x + other.w >= main.x && other.x <= main.x + main.w
        && other.y + other.h >= main.y && other.y <= main.y + main.h)
}

function createPlanet() {
    app.planet.timer = setTimeout(() => {
        clearTimeout(app.planet.timer);
        if (app.isRunning) {
            let imgIndex = rand(app.planet.qty);
            $('<img>').attr({
                class: 'elm-planet',
                src: 'images/planet/planet-' + imgIndex + '.png'
            }).appendTo(app.game).css({
                left: app.width,
                top: randPos().y
            })
        }
        createPlanet();
    }, app.planet.time)
}

function createAsteroid() {
    app.asteroid.timer = setTimeout(() => {
        clearTimeout(app.asteroid.timer);
        if (app.isRunning) {
            let imgIndex = rand(app.asteroid.qty);
            $('<div>').attr({
                class: 'elm-asteroid'
            }).appendTo(app.game).css({
                background: 'url(images/asteroid/asteroid-' + imgIndex + '.png)',
                backgroundSize: 'cover',
                left: app.width,
                top: randPos().y
            })
        }
        createAsteroid();
    }, app.asteroid.time)
}

function createEnemy() {
    app.enemy.timer = setTimeout(() => {
        clearTimeout(app.enemy.timer);
        if (app.isRunning) {
            let imgIndex = rand(app.enemy.qty);
            $('<div>').attr({
                class: 'elm-enemy'
            }).appendTo(app.game).css({
                background: 'url(images/enemy/enemy-' + imgIndex + '.png)',
                backgroundSize: 'cover',
                left: app.width,
                top: randPos().y
            })
        }
        createEnemy();
    }, app.enemy.time)
}

function createFriend() {
    app.friend.timer = setTimeout(() => {
        clearTimeout(app.friend.timer);
        if (app.isRunning) {
            $('<div>').attr({
                class: 'elm-friend'
            }).appendTo(app.game).css({
                left: app.width,
                top: randPos().y
            })
        }
        createFriend();
    }, app.friend.time)
}

function createFuel() {
    app.fuel.timer = setTimeout(() => {
        clearTimeout(app.fuel.timer);
        if (app.isRunning) {
            $('<div>').attr({
                class: 'elm-fuel'
            }).appendTo(app.game).css({
                left: randPos().x,
                top: 0
            })
        }
        createFuel();
    }, app.fuel.time)
}

function createEnemyShot() {
    app.enemyShot.timer = setTimeout(() => {
        clearTimeout(app.enemyShot.timer);
        if (app.isRunning) {
            let items = $('.elm-enemy');
            let length = items.length;
            for (let i = 0; i < length; i++) {
                let obj = $(items[i]);
                $('<div>').attr({
                    class: 'elm-enemy-shot'
                }).appendTo(app.game).css({
                    left: obj.position().left,
                    top: obj.position().top + (obj.height() / 2)
                })
            }

        }
        createEnemyShot();
    }, app.enemyShot.time)
}

function createMainShot() {
    if (app.isRunning) {
        $('<div>').attr({
            class: 'elm-main-shot'
        }).appendTo(app.game).css({
            left: app.player.position().left + app.player.width(),
            top: app.player.position().top + (app.player.height() / 2) - 8
        })
    }
}

function startCounter() {
    app.counterTimer = setTimeout(() => {
        clearTimeout(app.counterTimer);
        if (app.isRunning) {
            app.fuelCounter -= app.fuelConsume;
            app.timeCounter++;
        }
        startCounter();
    }, 1000)
}

function checkMainShot() {
    app.checkMainShotTimer = setTimeout(() => {
        clearTimeout(app.checkMainShotTimer);
        if (app.isRunning) {
            let items = $('.elm-main-shot');
            let length = items.length;
            for (let i = 0; i < length; i++) {
                let obj = $(items[i]);

                let items2 = $('.elm-friend, .elm-enemy, .elm-asteroid');
                let length2 = items2.length;
                for (let j = 0; j < length2; j++) {
                    let obj2 = $(items2[j]);

                    switch (obj2.attr('class')) {
                        case 'elm-friend': {
                            if (checkOverlap(obj, obj2)) {
                                obj.remove();
                                obj2.remove();
                                playSound('destroyed');
                                app.scoreCounter -= 10;
                            }
                        }
                            break;
                        case 'elm-enemy': {
                            if (checkOverlap(obj, obj2)) {
                                obj.remove();
                                obj2.remove();
                                playSound('destroyed');
                                app.scoreCounter += 5;
                            }
                        }
                            break;
                        case 'elm-asteroid': {
                            if (checkOverlap(obj, obj2)) {
                                obj.remove();
                                if (obj2.attr('hit')) {
                                    obj2.remove();
                                    playSound('destroyed');
                                    app.scoreCounter += 10;
                                } else obj2.attr('hit', 1);

                            }
                        }
                            break;
                    }
                }
            }
        }
        checkMainShot();
    }, 1000 / 20)
}

function startGame() {
    createPlanet();
    createAsteroid();
    createEnemy();
    createFriend();
    createFuel();
    createEnemyShot();
    startCounter();
    checkMainShot();

    updateGame();
    let promise = document.getElementById('background').play();
    promise.then(r => {
    }).catch(e => {
    });
    pauseGame();
}

function updateItemsCoordinate() {
    let items = $('.elm-asteroid, .elm-friend, .elm-planet, .elm-enemy, .elm-main-shot, .elm-fuel, .elm-enemy-shot');
    let length = items.length;
    for (let i = 0; i < length; i++) {
        let obj = $(items[i]);
        let x = 0, y = 0, isRemove = false;
        switch (obj.attr('class')) {
            case 'elm-asteroid': {
                x = app.asteroid.speed;
                if (checkOverlap(app.player, obj) && app.fuelConsume === 1) {
                    obj.remove();
                    isRemove = true;
                    playSound('destroyed');
                    app.fuelCounter -= 10;
                }
            }
                break;
            case 'elm-friend': {
                x = app.friend.speed;
                if (checkOverlap(app.player, obj) && app.fuelConsume === 1) {
                    obj.remove();
                    isRemove = true;
                    playSound('destroyed');
                    app.fuelCounter -= 10;
                }
            }
                break;
            case 'elm-planet': {
                let fast = (obj.width() / 10) * 0.3;
                x = app.planet.speed + fast;
            }
                break;
            case 'elm-enemy': {
                x = app.enemy.speed;
                if (checkOverlap(app.player, obj) && app.fuelConsume === 1) {
                    obj.remove();
                    isRemove = true;
                    playSound('destroyed');
                    app.fuelCounter -= 10;
                }
            }
                break;
            case 'elm-fuel': {
                y = app.fuel.speed;
                if (checkOverlap(app.player, obj)) {
                    obj.remove();
                    isRemove = true;
                    app.fuelCounter += 20;
                }
            }
                break;
            case 'elm-main-shot': {
                x = app.mainShot.speed;
            }
                break;
            case 'elm-enemy-shot': {
                x = app.enemyShot.speed;
                if (checkOverlap(app.player, obj) && app.fuelConsume === 1) {
                    obj.remove();
                    isRemove = true;
                    app.fuelCounter -= 10;
                }
            }
                break;
        }

        if (!isRemove) {
            if (obj.position().left <= -obj.width() || obj.position().top >= app.height)
                obj.remove();
            else if (obj.attr('class') === 'elm-main-shot' && obj.position().left >= app.width)
                obj.remove();
            else obj.css({
                    left: '-=' + x,
                    top: '+=' + y
                })
        }

    }
}

function updateMainCoordinate() {
    if (app.main.x <= 0) app.main.x = 0;
    if (app.main.x >= app.width - app.player.width()) app.main.x = app.width - app.player.width();
    if (app.main.y <= 0) app.main.y = 0;
    if (app.main.y >= app.height - app.player.height()) app.main.y = app.height - app.player.height();

    app.player.css({
        left: app.main.x,
        top: app.main.y
    })
}

function updateCounter() {
    if (app.fuelCounter >= 40) app.fuelCounter = 40;
    if (app.fuelCounter <= 0) {
        app.fuelCounter = 0;
        gameOver();
    }

    let percent = (app.fuelCounter * 180) / 40;
    let deg = -90 + percent;
    $('#score').html(app.scoreCounter);
    $('#timer').html(app.timeCounter);
    $('#fuel-number').html(app.fuelCounter);
    $('#fuel-gauge').css('transform', 'rotate(' + deg + 'deg)')

}

function updateGame() {
    if (app.isRunning) {
        updateItemsCoordinate();
        updateMainCoordinate();
        updateCounter();
    }
    requestAnimationFrame(updateGame);
}

function gameOver() {
    app.isGameOver = true;
    pauseGame();
    formDialog.dialog('open');
    playSound('game_over');
    app.game.html('');
}

function muteSound(isMute) {
    if (isMute) document.getElementById('background').pause();
    else document.getElementById('background').play();
}

function playSound(name) {
    if (!app.isMute) {
        let obj = new Audio('sound/' + name + '.mp3');
        obj.volume = app.volume;
        let promise = obj.play();
        promise.then(r => {
        }).catch(e => {
        });
    }
}

function pauseGame() {
    app.isRunning = !app.isRunning;
    app.game.toggleClass('pause');
    if (app.isRunning) {
        if (!app.isMute)
            muteSound(false);
    } else {
        muteSound(true);
    }
}


function showData(data) {
    let str = '';
    $.each(data, (i, val) => {
        str += '   <tr>\n' +
            '                    <td>' + val.position + '</td>\n' +
            '                    <td>' + val.name + '</td>\n' +
            '                    <td>' + val.score + '</td>\n' +
            '                    <td>' + val.time + '</td>\n' +
            '                </tr>';
    });
    $('#ranking-dialog tbody').html(str);
}

function sortRanking(data) {
    let dataResult = data.slice().sort((a, b) => {
        let scoreA = parseInt(a.score);
        let scoreB = parseInt(b.score);
        let timeA = parseInt(a.time);
        let timeB = parseInt(b.time);
        let compare = 0;
        if (scoreA > scoreB) compare = 1;
        else if (scoreA < scoreB) compare = -1;
        else {
            if (timeA < timeB) compare = 1;
            else if (timeA > timeB) compare = -1;
        }
        return compare * -1;
    });
    return sortPosition(dataResult);
}

function sortPosition(data) {
    let length = data.length;
    let position = 1;
    data[0]['position'] = 1;
    for (let i = 1; i < length; i++) {
        if (data[i]['score'] === data[i - 1]['score'] && data[i]['time'] === data[i - 1]['time']) {
            position++;
            data[i]['position'] = data[i - 1]['position'];
        } else data[i]['position'] = ++position;
    }
    return data;
}

const helpDialog = $('#help-dialog').dialog({
    autoOpen: true,
    width: 800,
    closeOnEscape: false,
    modal: true,
    show: {effect: 'blind', duration: 1000},
    open: function () {
        $('#start').click(function () {
            $('#modal').removeClass('active');
            helpDialog.dialog('close');
            pauseGame();
            app.isGameOver = false;
        });
    }
});

const formDialog = $('#form-dialog').dialog({
    autoOpen: false,
    width: 650,
    closeOnEscape: false,
    modal: true,
    show: {effect: 'blind', duration: 1000},
    open: function () {
        $('#modal').addClass('active');
        $('#name').keyup(function () {
            $('#continue').attr('disabled', $(this).val().length === 0);
        });
        $('#continue').click(function () {
            let data = JSON.parse(localStorage.getItem('ranking'));
            if (!data) data = [];
            data.push({
                name: $('#name').val(),
                score: app.scoreCounter,
                time: app.timeCounter
            });
            localStorage.setItem('ranking', JSON.stringify(data));
            showData(sortRanking(data));
            formDialog.dialog('close');
            rankingDialog.dialog('open');
        });
    }
});

const rankingDialog = $('#ranking-dialog').dialog({
    autoOpen: false,
    width: 800,
    maxHeight: 700,
    closeOnEscape: false,
    modal: true,
    show: {effect: 'blind', duration: 1000},
    open: function () {
        $('#reload').click(function () {
            window.location.reload();
        });
    }
});


app.game.mousemove(function (e) {
    app.main.x = e.offsetX;
    app.main.y = e.offsetY;
    if (app.checkOver) {
        app.player.css('transition', 'all .2s');
        app.checkOver = false;
    } else {
        setTimeout(() => {
            app.player.css('transition', 'unset');
        }, 200);

    }
}).mouseleave(function () {
    app.checkOver = true;
});

$(document).keydown(function (e) {
    if (e.ctrlKey) {
        e.preventDefault();
        if (!app.isGameOver) {
            if (e.keyCode === 80)
                pauseGame();
            if (e.keyCode === 65)
                $('#score, #timer').css('fontSize', ++app.size);
            if (e.keyCode === 83)
                $('#score, #timer').css('fontSize', --app.size);
            if (e.keyCode === 77) {

                app.isMute = !app.isMute;
                console.log(app.isMute);
                muteSound(app.isMute);
            }
            if (e.keyCode === 187 && e.shiftKey) {
                app.volume += 0.1;

                if (app.volume > 1) app.volume = 1;
                document.getElementById('background').volume = app.volume;
            }
            if (e.keyCode === 189) {
                app.volume -= 0.1;
                if (app.volume < 0) app.volume = 0;

                document.getElementById('background').volume = app.volume;
            }
            if (e.keyCode === 66) {
                if (app.fuelCounter >= 20) {
                    app.fuelCounter -= 20;
                    let items = $('.elm-asteroid, .elm-enemy, .elm-friend, .elm-fuel');
                    let length = items.length;
                    for (let i = 0; i < length; i++) {
                        let obj = $(items[i]);
                        $('<div>').attr({
                            class: 'elm-boom'
                        }).appendTo(app.game).css({
                            left: obj.position().left,
                            top: obj.position().top
                        });
                        obj.remove();
                    }

                    $('#wrapper').css('background-position', '10px');
                    setTimeout(() => {
                        $('#wrapper').css('background-position', '0px');
                    }, 300);
                    setTimeout(() => {
                        $('.elm-boom').remove();
                    }, 1000);
                    playSound('boom');

                }
            }
            if (e.keyCode === 73) {
                if (app.fuelConsume === 1) {
                    app.fuelConsume = 2;
                    playSound('invisible');
                    app.player.attr('visible', 1);
                }
                else {
                    app.fuelConsume = 1;
                    app.player.removeAttr('visible');
                }
            }
        }
    }
    if (e.keyCode === 32 && app.timeCounter === 0) e.preventDefault();
    if (e.keyCode === 13 && app.timeCounter === 0) e.preventDefault();
    if (e.keyCode === 32 && app.isRunning) {
        if (!app.spaceKeyState) {
            app.spaceKeyState = true;
            createMainShot();
            playSound('shoot');
        }
    }

}).keyup(function (e) {
    if (e.keyCode === 32 && app.isRunning) app.spaceKeyState = false;
});

startGame();
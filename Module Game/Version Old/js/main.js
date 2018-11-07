let app = {
    width: 1200,
    height: 600,
    game: $('#game'),
    player: $('#main'),

    isRunning: true,
    isGameOver: true,
    isMute: false,

    direction: '',
    spaceKeyState: false,

    scoreCounter: 0,
    fuelCounter: 20,
    timeCounter: 0,
    level: 1,
    bonusPoint: 1,
    pointUp: 0,
    size: 24,

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
    bonus: {
        speed: 3,
        time: 8000,
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
    bonusTimer: 0
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

function createBonus() {
    app.bonus.timer = setTimeout(() => {
        clearTimeout(app.bonus.timer);
        if (app.isRunning) {
            $('<div>').attr({
                class: 'elm-bonus'
            }).appendTo(app.game).css({
                left: randPos().x,
                top: 0
            })
        }
        createBonus();
    }, app.bonus.time)
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
        let top = -10, ratio = 20;
        if (app.level === 2) {
            top = -20, ratio = 30;
        }
        if (app.level === 3) top = -30;
        for (let i = 0; i < app.level; i++) {
            $('<div>').attr({
                class: 'elm-main-shot'
            }).appendTo(app.game).css({
                left: app.player.position().left + app.player.width(),
                top: app.player.position().top + (app.player.height() / 2) + top + (i * ratio)
            })
        }

    }
}

function startCounter() {
    app.counterTimer = setTimeout(() => {
        clearTimeout(app.counterTimer);
        if (app.isRunning) {
            app.fuelCounter--;
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

                let items2 = $('.elm-friend, .elm-bonus, .elm-enemy, .elm-asteroid');
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
                                app.level--;
                            }
                        }
                            break;
                        case 'elm-enemy': {
                            if (checkOverlap(obj, obj2)) {
                                obj.remove();
                                obj2.remove();
                                playSound('destroyed');
                                app.scoreCounter += (5 * app.bonusPoint);
                                app.pointUp += (5 * app.bonusPoint);
                            }
                        }
                            break;
                        case 'elm-bonus': {
                            if (checkOverlap(obj, obj2)) {
                                obj.remove();
                                obj2.remove();
                                if (app.bonusPoint === 2) clearTimeout(app.bonusTimer);
                                app.bonusPoint = 2;
                                app.bonusTimer = setTimeout(() => {
                                    app.bonusPoint = 1;
                                }, 15000);
                            }
                        }
                            break;
                        case 'elm-asteroid': {
                            if (checkOverlap(obj, obj2)) {
                                obj.remove();
                                if (obj2.attr('hit')) {
                                    obj2.remove();
                                    playSound('destroyed');
                                    app.scoreCounter += (10 * app.bonusPoint);
                                    app.pointUp += (10 * app.bonusPoint);
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
    createBonus();
    createEnemyShot();
    startCounter();
    checkMainShot();

    updateGame();
    $('#play').click();
}


function updateItemsCoordinate() {
    let items = $('.elm-bonus, .elm-asteroid, .elm-friend, .elm-planet, .elm-enemy, .elm-main-shot, .elm-fuel, .elm-enemy-shot');
    let length = items.length;
    for (let i = 0; i < length; i++) {
        let obj = $(items[i]);
        let x = 0, y = 0, isRemove = false;
        switch (obj.attr('class')) {
            case 'elm-bonus': {
                y = app.bonus.speed;
            }
                break;
            case 'elm-asteroid': {
                x = app.asteroid.speed;
                if (checkOverlap(app.player, obj)) {
                    obj.remove();
                    isRemove = true;
                    playSound('destroyed');
                    app.fuelCounter -= 10;
                }
            }
                break;
            case 'elm-friend': {
                x = app.friend.speed;
                if (checkOverlap(app.player, obj)) {
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
                if (checkOverlap(app.player, obj)) {
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
                if (checkOverlap(app.player, obj)) {
                    obj.remove();
                    isRemove = true;
                    app.fuelCounter -= 10;
                    app.level--;
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
    if (app.direction === 'left') app.main.x -= app.main.speed;
    if (app.direction === 'right') app.main.x += app.main.speed;
    if (app.direction === 'top') app.main.y -= app.main.speed;
    if (app.direction === 'bottom') app.main.y += app.main.speed;

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

function updateLevel() {
    if (app.pointUp >= 30) {
        app.level++;
        app.pointUp -= 30;
    }
    if (app.level < 1) app.level = 1;
    if (app.level > 3) app.level = 3;

    $('#level').html(app.level);
    if (app.level === 1) app.player.attr('class', 'elm-main');
    if (app.level === 2) app.player.attr('class', 'elm-main-2');
    if (app.level === 3) app.player.attr('class', 'elm-main-3');
}

function updateGame() {
    if (app.isRunning) {
        updateItemsCoordinate();
        updateMainCoordinate();
        updateCounter();
        updateLevel();
    }
    requestAnimationFrame(updateGame);
}

function gameOver() {
    console.log('game over');
    app.isGameOver = false;
    $('#play').click();
    formDialog.dialog('open');
}

function muteSound(isMute) {
    if (isMute) document.getElementById('background').pause();
    else document.getElementById('background').play();
}

function playSound(name) {
    if (!app.isMute) {
        let promise = new Audio('sound/' + name + '.mp3').play();
        promise.then(r => {
        }).catch(e => {
        });
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
    width: 600,
    closeOnEscape: false,
    modal: true,
    show: {effect: 'blind', duration: 1000},
    open: function () {
        $('#start').click(function () {
            $('#modal').removeClass('active');
            helpDialog.dialog('close');
            $('#play').click();
            app.isGameOver = false;
        });
    }
});

const formDialog = $('#form-dialog').dialog({
    autoOpen: false,
    width: 350,
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
    width: 600,
    maxHeight: 400,
    closeOnEscape: false,
    modal: true,
    show: {effect: 'blind', duration: 1000},
    open: function () {
        $('#reload').click(function () {
            window.location.reload();
        });
    }
});


$('#sensible img').mouseover(function () {
    app.direction = $(this).attr('alt');
}).mouseleave(function () {
    app.direction = '';
});

$(document).keydown(function (e) {
    if (e.keyCode === 32 && app.timeCounter === 0) e.preventDefault();
    if (e.keyCode === 32 && app.isRunning) {
        if (!app.spaceKeyState) {
            app.spaceKeyState = true;
            createMainShot();
            playSound('shoot');
        }
    }
    if (e.keyCode === 80 && !app.isGameOver) $('#play').click();
}).keyup(function (e) {
    if (e.keyCode === 32 && app.isRunning) app.spaceKeyState = false;
});

$('#font-up').click(function () {
    $('#score, #timer').css('fontSize', ++app.size);
});

$('#font-down').click(function () {
    $('#score, #timer').css('fontSize', --app.size);
});

$('#sound').click(function () {
    app.isMute = !app.isMute;
    if (app.isMute) {
        muteSound(true);
        $(this).attr('src', 'images/sound-off.png');
    } else {
        muteSound(false);
        $(this).attr('src', 'images/sound-on.png');
    }
});

$('#play').click(function () {
    app.isRunning = !app.isRunning;
    app.game.toggleClass('pause');
    if (app.isRunning) {
        if (!app.isMute)
            muteSound(false);
        $(this).attr('src', 'images/play.png');
        $('#font-up, #font-down, #sound, #sensible').css('pointer-events', 'auto');
    } else {
        muteSound(true);
        $(this).attr('src', 'images/pause.png');
        $('#font-up, #font-down, #sound, #sensible').css('pointer-events', 'none');
    }
});
startGame();
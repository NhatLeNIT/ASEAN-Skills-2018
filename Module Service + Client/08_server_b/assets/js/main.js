function getUsername() {
    return localStorage.username;
}

function getRole() {
    return localStorage.role;
}

function getToken() {
    return localStorage.token;
}

const api = axios.create({
    baseURL: 'http://competitorvn.asc.local/08_server_a/api/v1/',
    headers: {
        Authorization: 'Bearer ' + getToken()
    }
});

const app = new Vue({
    el: '#app',
    data() {
        return {
            isLogin: false,
            isShowFormLogin: false,
            message: '',
            component: 'search',

            username: '',
            password: '',
            role: '',

            fromPlace: {
                id: 0,
                input: '',
                list: [],
                focus: false
            },
            toPlace: {
                id: 0,
                input: '',
                list: [],
                focus: false
            },
            departureTime: '',

            placeList: [],
            routeList: [],
            scheduleList: [],

            placeObj: {
                id: 0,
                name: '',
                latitude: 0,
                longitude: 0,
                open_time: '',
                close_time: '',
                image_path: '',
                description: '',
                type: ''
            },
            scheduleObj: {
                id: 0,
                line: 0,
                from_place: '',
                to_place: '',
                departure_time: '',
                arrival_time: '',
                distance: 0,
                speed: 0,
                status: ''
            },
            selected: null,

            lineLabels: [],
            paths: [],
            imgURL: 'http://competitorvn.asc.local/08_server_a/public/uploads/'
        }
    },
    mounted() {
        this.hasLogin();
        $(document).click(() => {
            this.selected = null;
        })
    },
    methods: {
        //    helper
        setMessage(msg) {
            this.message = msg;
            setTimeout(() => {
                this.message = ''
            }, 2500);
        },
        feedback(e) {
            let data = e.response.data;
            this.setMessage(data.message);
        },
        //    authentication
        login() {
            let data = {
                username: this.username,
                password: this.password
            };

            api.post('auth/login', data)
                .then(r => {
                    localStorage.username = data.username;
                    localStorage.token = r.data.token;
                    localStorage.role = r.data.role;
                    this.hasLogin();
                    this.isShowFormLogin = false;
                }).catch(this.feedback);
        },
        logout() {
            api.get('auth/logout')
                .then(r => {
                    localStorage.clear();
                    this.hasLogin();
                    this.setMessage(r.data.message);
                }).catch(this.feedback);
        },
        hasLogin() {
            if (getToken()) {
                this.username = getUsername();
                this.role = getRole();
                this.isLogin = true;
                api.defaults.headers.Authorization = 'Bearer ' + getToken();
                this.getPlaces();
                if (this.role === 'ADMIN')
                    this.getSchedules();
            } else {
                localStorage.clear();
                this.username = '';
                this.password = '';
                this.role = '';
                api.defaults.headers.Authorization = '';
                this.isLogin = false;
                this.placeList = [];
                $('#path').html('');
                this.lineLabels = [];
                this.paths = [];
                this.scheduleList = [];
                this.component = 'search';
                this.routeList = [];
            }
        },
        //    search route
        filterPlace(place, key) {
            api.get('place')
                .then(r => {
                    this[key].list = r.data.filter(val => {
                        return val.name.toLowerCase().indexOf(place.toLowerCase()) !== -1;
                    })
                }).catch(this.feedback);
        },
        selectedKeyword(place, key) {
            this[key].input = place.name;
            this[key].id = place.id;
        },
        //    route list
        searchRoute() {
            if (this.isLogin) {
                let url = 'route/search/' + (this.fromPlace.id) + '/' + (this.toPlace.id) + (this.departureTime ? '/' + this.departureTime + ':00' : '');
                api.get(url)
                    .then(r => {
                        this.routeList = r.data;
                        this.clearVisible(this.fromPlace.id, this.toPlace.id);
                        $('#path').html('');
                        this.lineLabels = [];
                        this.paths = [];
                    }).catch(this.feedback);
            } else this.isShowFormLogin = true;

        },
        calTravelTime(schedules) {
            let timeBase = new Date('September 01, 2018 00:00:00');
            let time = 0;
            for (let schedule of schedules) {
                let timeTemp = new Date('September 01, 2018 ' + schedule.travel_time);
                time += (timeTemp.getTime() - timeBase.getTime()) / 1000 / 60;
            }
            return time + ' minutes';
        },
        calType(schedules) {
            let attraction = 0;
            let length = schedules.length + 1;
            let flag = true;
            for (let schedule of schedules) {
                if (flag && schedule.from_place.type === 'Attraction')
                    attraction++;
                if (schedule.to_place.type === 'Attraction')
                    attraction++;
                flag = false;
            }
            return attraction + ' attractions and ' + (length - attraction) + ' local restaurants';
        },
        displayLine(schedules) {
            let data = [];
            for (let schedule of schedules) {
                if (data.indexOf('Line: ' + schedule.line) === -1)
                    data.push('Line: ' + schedule.line);
            }
            return data;
        },
        //    map view
        getPlaces() {
            api.get('place')
                .then(r => {
                    this.placeList = r.data;
                }).catch(this.feedback);
        },
        getClass(place) {
            let timeCurrent = new Date().toTimeString();

            let current = new Date('Sep 01, 2018 ' + timeCurrent);
            let close = new Date('Sep 01, 2018 ' + place.close_time);

            let isClose = false;
            if (current.getTime() >= close.getTime()) isClose = true;
            return {
                attraction: place.type === 'Attraction',
                restaurant: place.type === 'Restaurant',
                closed: isClose
            }
        },
        cutStr(str) {
            return str.length > 100 ? str.substring(0, 100) + '...' : str;
        },
        clearVisible(from, to) {
            $('.place-text').removeClass('from-place to-place');
            $('.place').attr('r', 5);
            $('.place[data-id=' + from + ']').attr('r', 10).addClass('dot-active');
            $('.place[data-id=' + to + ']').attr('r', 10).addClass('dot-active');
            $('.place-text[data-id=' + from + ']').addClass('from-place');
            $('.place-text[data-id=' + to + ']').addClass('to-place');
        },
        selectedRoute(route) {
            route.number_of_history++;
            let schedule_id = [];
            for (let schedule of route.schedules)
                schedule_id.push(schedule.id);
            let data = {
                from_place_id: route.schedules[0].from_place.id,
                to_place_id: route.schedules[route.schedules.length - 1].to_place.id,
                schedule_id: schedule_id
            };
            api.post('route/selection', data)
                .then(r => {
                    this.setMessage(r.data.message);
                }).catch(this.feedback);

            this.drawAllPath(route.schedules);
        },
        rand(min, max) {
            return Math.floor(Math.random() * (max - min)) + 1;
        },
        randColor() {
            let r = this.rand(0, 255);
            let g = this.rand(0, 255);
            let b = this.rand(0, 255);
            return 'rgb(' + r + ',' + g + ',' + b + ')';
        },
        drawAllPath(schedules) {
            $('#path').html('');
            this.lineLabels = [];
            this.paths = [];

            schedules.forEach((schedule, i) => {
                if (!this.paths[schedule.line])
                    this.paths[schedule.line] = {
                        color: this.randColor(),
                        line: schedule.line
                    };

                setTimeout(() => {
                    this.drawPath(schedule)
                }, i * 300);
            });

            for (let key in this.paths) {
                this.lineLabels.push(this.paths[key])
            }
        },
        drawPath(schedule) {
            let line = $(document.createElementNS('http://www.w3.org/2000/svg', 'line'));
            let animate1 = $(document.createElementNS('http://www.w3.org/2000/svg', 'animate'));
            let animate2 = $(document.createElementNS('http://www.w3.org/2000/svg', 'animate'));

            let from = schedule.from_place;
            let to = schedule.to_place;

            line.addClass('line').css('stroke', this.paths[schedule.line].color).attr({
                x1: from.x,
                x2: to.x,
                y1: from.y,
                y2: to.y
            });
            animate1.attr({
                attributeName: 'x2',
                from: from.x,
                to: to.x,
                dur: '0.3s'
            });
            animate2.attr({
                attributeName: 'y2',
                from: from.y,
                to: to.y,
                dur: '0.3s'
            });
            line.append(animate1, animate2);
            $('#path').append(line);

            animate1[0].beginElement();
            animate2[0].beginElement();
        },
        //    admin
        createPlace($event) {
            let data = new FormData($event.target);
            api.post('place', data)
                .then(r => {
                    this.setMessage(r.data.message);
                    this.getPlaces();
                }).catch(this.feedback);
        },
        getInfoObject() {
            let obj = this.placeList.filter(val => {
                return val.id === this.placeObj.id;
            })[0];


            obj.open_time = this.changeTimeEdit(obj.open_time);
            obj.close_time = this.changeTimeEdit(obj.close_time);
            Object.assign(this.placeObj, obj);
            console.log(this.placeObj);
        },
        updatePlace($event) {
            let data = new FormData($event.target);
            console.log(data);
            data.set('open_time', data.get('open_time') + ':00');
            data.set('close_time', data.get('close_time') + ':00');
            api.post('place/' + this.placeObj.id, data)
                .then(r => {
                    this.setMessage(r.data.message);
                    this.getPlaces();
                    this.placeObj = {
                        id: 0,
                        name: '',
                        latitude: 0,
                        longitude: 0,
                        open_time: '',
                        close_time: '',
                        image_path: '',
                        description: ''
                    };
                }).catch(this.feedback);
        },
        deletePlace(id) {
            if (confirm('Are you sure want to delete this item?')) {
                let dotActive = $('.place[data-id=' + id + ']').hasClass('dot-active');
                if (dotActive) {
                    $('.place[data-id=' + id + ']').removeClass('dot-active');
                    $('.place-text').removeClass('from-place to-place');
                    $('.place').attr('r', 5);
                }

                $('#path').html('');
                this.lineLabels = [];
                this.paths = [];
                api.delete('place/' + id)
                    .then(r => {
                        this.setMessage(r.data.message);
                        this.getPlaces();
                    }).catch(this.feedback);
            }
        },
        getSchedules() {
            api.get('schedule')
                .then(r => {
                    this.scheduleList = r.data;
                }).catch(this.feedback);
        },
        createSchedule($event) {
            let data = new FormData($event.target);
            data.set('departure_time', data.get('departure_time') + ':00');
            data.set('arrival_time', data.get('arrival_time') + ':00');
            api.post('schedule', data)
                .then(r => {
                    this.setMessage(r.data.message);
                    this.getSchedules();
                }).catch(this.feedback);
        },
        getInfoSchedule() {
            let obj = this.scheduleList.filter(val => {
                return val.id === this.scheduleObj.id;
            })[0];
            Object.assign(this.scheduleObj, obj);
        },
        filterPlaceByName(name) {
            return this.placeList.filter(val => {
               return val.name.toLowerCase().indexOf(name.toLowerCase()) !== -1;
            })[0];
        },
        updateSchedule($event) {
            let data = new FormData($event.target);
            let from_place_id = this.filterPlaceByName(data.get('from_place')).id;
            let to_place_id = this.filterPlaceByName(data.get('to_place')).id;

            data.set('from_place_id', from_place_id);
            data.set('to_place_id', to_place_id);
            data.delete('from_place');
            data.delete('to_place');

            //luu y khi change (xu ly truoc khi do ra)
            data.set('departure_time', data.get('departure_time') + ':00');
            data.set('arrival_time', data.get('arrival_time') + ':00');

            api.post('schedule/' + this.scheduleObj.id, data)
                .then(r => {
                    this.setMessage(r.data.message);
                    this.getPlaces();
                    this.scheduleObj = {
                        id: 0,
                        line: 0,
                        from_place: '',
                        to_place: '',
                        departure_time: '',
                        arrival_time: '',
                        distance: 0,
                        speed: 0,
                        status: ''
                    };
                }).catch(this.feedback);
        },
        deleteSchedule(id) {
            if (confirm('Are you sure want to delete this item?')) {
                api.delete('schedule/' + id)
                    .then(r => {
                        this.setMessage(r.data.message);
                        this.getSchedules();
                    }).catch(this.feedback);
            }
        },
        changeTimeEdit(time) {
            return time.substring(0,5);
        }
    },
    watch: {
        'fromPlace.input'(val) {
            this.filterPlace(val, 'fromPlace');
        },
        'toPlace.input'(val) {
            this.filterPlace(val, 'toPlace');
        }
    }
});
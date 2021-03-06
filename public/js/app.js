document.addEventListener("DOMContentLoaded", function(){

    // Generate new quote
    let link = document.querySelector('#new-quote');
    let actualQuote = document.querySelector('#display-quote');

    if(link){
        link.addEventListener('click', function (e) {
            e.preventDefault();
            let httpRequest = new XMLHttpRequest();
            httpRequest.open('GET', this.getAttribute('href'), true);
            httpRequest.responseType = "document";
            httpRequest.send();
            httpRequest.onreadystatechange = function(){
                if(httpRequest.readyState === 4 ) {
                    let responseQuote = httpRequest.response.querySelector('#display-quote');
                    while (actualQuote.hasChildNodes()) {
                        actualQuote.removeChild(actualQuote.firstChild);
                    }
                    while (responseQuote.hasChildNodes()){
                        actualQuote.appendChild(responseQuote.firstChild);
                    }
                }
            };

        });
    }

    // Modal
    let modal = document.getElementById('modal');
    if(modal)
    {
        let modalImg = document.getElementById("modalImg");
        let modalTitle = document.getElementById("modalTitle");
        let modalDesc = document.getElementById("modalDesc");

        let works = document.getElementsByClassName('work');

        for (let i = 0; i < works.length; i++) {
            let img = works[i];
            img.onclick = function() {
                modal.style.display = "block";
                let i = img.childNodes[1].childNodes[1];
                let t = img.childNodes[3].childNodes[1];
                let d = img.childNodes[3].childNodes[3];
                modalImg.src = i.src.replace('_thumb', '');
                modalTitle.innerHTML = t.innerHTML;
                modalDesc.innerHTML = d.innerHTML;
            }
        }

        let span = document.getElementsByClassName("close")[0];

        span.onclick = function() {
            modal.style.display = "none";
        };

        modal.onclick = function () {
            modal.style.display = "none";
        };
    }

    // Get and generate meteo

    let API_KEY = "7cdad1875c5955549d7df72ef2bd6179";
    let weather, wind;

    function build_url(city, countryCode){

        return "http://api.openweathermap.org/data/2.5/weather"
            +'?q=' + city
            +',' + countryCode
            +'&APPID=' + API_KEY
            +'&lang=fr&units=metric';
    }

    let httprequest = new XMLHttpRequest();
    httprequest.open('GET', build_url('tours', 'fr', true));
    httprequest.responseType = "json"
    httprequest.send();
    httprequest.onreadystatechange = function(){
        if(httprequest.readyState === 4 ) {
            let response = httprequest.response;
            weather = response.weather[0].id;
            wind = response.wind.speed;
            setWeather(weather, wind)
        }
    };

    function setWeather(weather, wind){
        if (wind > 24.4){
            generateWind();

        } else if (weather >= 200 && weather <= 232){
            generateLightings();

        } else if (weather >= 300 && weather <= 321){
            generateRain(8);

        } else if (weather >= 500 && weather <= 531){
            generateRain(2);

        } else if (weather >= 600 && weather <= 622){
            generateSnow();

        } else if (weather >= 701 && weather <= 781){
            generateFog()

        } else if (weather === 800){
            generateClear();

        } else if (weather >= 801 && weather <= 804){
            generateClouds()

        }else{
            console.log('Pas de méteo');
        }

    }

    function generateWind(){
        particlesJS.load('particles-js', '/js/wind.json', function() {
            console.log('callback - particles.js config loaded. Vent');
        });
    }

    function generateLightings() {
        console.log('orage');
        let element = document.getElementById('cloud');
        element.style.display = 'block';
        if(isCanvasSupported){
            let c = document.getElementById('canvas');
            let cw = c.width = window.innerWidth;
            let ch = c.height = window.innerHeight;
            let cl = new canvasLightning(c, cw, ch);

            setupRAF();
            cl.init();
        }
    }

    function generateRain(){
        particlesJS.load('particles-js', '/js/rain.json', function() {
            console.log('Pluie');
        });
    }

    function generateSnow(){
        particlesJS.load('particles-js', '/js/snow.json', function() {
            console.log('Neige');
        });
    }

    function generateFog(){
        particlesJS.load('particles-js', '/js/fog.json', function() {
            console.log('Brume');
        });
    }

    function generateClear(){
        console.log('beau temps');
        document.body.style.backgroundColor = "#dce7ec";
        let element = document.getElementById('sun');
        element.style.display = 'block';
    }

    function generateClouds(){
        console.log('nuageux');
        document.body.style.backgroundColor = "#dce7ec";
        let element = document.getElementById('cloud');
        element.style.display = 'block';
    }

    let canvasLightning = function(c, cw, ch){

        /* Initialize */
        this.init = function(){
            this.loop();
        };

        /* Variables */
        let _this = this;
        this.c = c;
        this.ctx = c.getContext('2d');
        this.cw = cw;
        this.ch = ch;
        this.mx = 0;
        this.my = 0;

        this.lightning = [];
        this.lightTimeCurrent = 0;
        this.lightTimeTotal = 50;

        /* Utility Functions */
        this.rand = function(rMi, rMa){return ~~((Math.random()*(rMa-rMi+1))+rMi);};
        this.hitTest = function(x1, y1, w1, h1, x2, y2, w2, h2){return !(x1 + w1 < x2 || x2 + w2 < x1 || y1 + h1 < y2 || y2 + h2 < y1);};

        /* Create Lightning */
        this.createL= function(x, y, canSpawn){
            this.lightning.push({
                x: x,
                y: y,
                xRange: this.rand(5, 30),
                yRange: this.rand(5, 25),
                path: [{
                    x: x,
                    y: y
                }],
                pathLimit: this.rand(10, 35),
                canSpawn: canSpawn,
                hasFired: false
            });
        };

        /* Update Lightning */
        this.updateL = function(){
            let i = this.lightning.length;
            while(i--){
                let light = this.lightning[i];

                light.path.push({
                    x: light.path[light.path.length-1].x + (this.rand(0, light.xRange)-(light.xRange/2)),
                    y: light.path[light.path.length-1].y + (this.rand(0, light.yRange))
                });

                if(light.path.length > light.pathLimit){
                    this.lightning.splice(i, 1)
                }
                light.hasFired = true;
            };
        };

        /* Render Lightning */
        this.renderL = function(){
            let i = this.lightning.length;
            while(i--){
                let light = this.lightning[i];

                this.ctx.strokeStyle = 'hsla(0, 100%, 100%, '+this.rand(10, 100)/100+')';
                this.ctx.lineWidth = 1;
                if(this.rand(0, 30) === 0){
                    this.ctx.lineWidth = 2;
                }
                if(this.rand(0, 60) === 0){
                    this.ctx.lineWidth = 3;
                }
                if(this.rand(0, 90) === 0){
                    this.ctx.lineWidth = 4;
                }
                if(this.rand(0, 120) === 0){
                    this.ctx.lineWidth = 5;
                }
                if(this.rand(0, 150) === 0){
                    this.ctx.lineWidth = 6;
                }

                this.ctx.beginPath();

                let pathCount = light.path.length;
                this.ctx.moveTo(light.x, light.y);
                for(let pc = 0; pc < pathCount; pc++){

                    this.ctx.lineTo(light.path[pc].x, light.path[pc].y);

                    if(light.canSpawn){
                        if(this.rand(0, 100) === 0){
                            light.canSpawn = false;
                            this.createL(light.path[pc].x, light.path[pc].y, false);
                        }
                    }
                }

                if(!light.hasFired){
                    this.ctx.fillStyle = 'rgba(255, 255, 255, '+this.rand(4, 12)/100+')';
                    this.ctx.fillRect(0, 0, this.cw, this.ch);
                }

                if(this.rand(0, 30) === 0){
                    this.ctx.fillStyle = 'rgba(255, 255, 255, '+this.rand(1, 3)/100+')';
                    this.ctx.fillRect(0, 0, this.cw, this.ch);
                }

                this.ctx.stroke();
            };
        };

        /* Lightning Timer */
        this.lightningTimer = function(){
            this.lightTimeCurrent++;
            if(this.lightTimeCurrent >= this.lightTimeTotal){
                let newX = this.rand(100, cw - 100);
                let newY = this.rand(0, ch / 2);
                let createCount = this.rand(1, 3);
                while(createCount--){
                    this.createL(newX, newY, true);
                }
                this.lightTimeCurrent = 0;
                this.lightTimeTotal = this.rand(30, 100);
            }
        };

        /* Clear Canvas */
        this.clearCanvas = function(){
            this.ctx.globalCompositeOperation = 'destination-out';
            this.ctx.fillStyle = 'rgba(0,0,0,'+this.rand(1, 30)/100+')';
            this.ctx.fillRect(0,0,this.cw,this.ch);
            this.ctx.globalCompositeOperation = 'source-over';
        };

        /* Resize on Canvas on Window Resize
        $(window).on('resize', function(){
            _this.cw = _this.c.width = window.innerWidth;
            _this.ch = _this.c.height = window.innerHeight;
        });*/

        /* Animation Loop */
        this.loop = function(){
            let loopIt = function(){
                requestAnimationFrame(loopIt, _this.c);
                _this.clearCanvas();
                _this.updateL();
                _this.lightningTimer();
                _this.renderL();
            };
            loopIt();
        };

    };

    let isCanvasSupported = function(){
        let elem = document.createElement('canvas');
        return !!(elem.getContext && elem.getContext('2d'));
    };

    let setupRAF = function(){
        let lastTime = 0;
        let vendors = ['ms', 'moz', 'webkit', 'o'];
        for(let x = 0; x < vendors.length && !window.requestAnimationFrame; ++x){
            window.requestAnimationFrame = window[vendors[x]+'RequestAnimationFrame'];
            window.cancelAnimationFrame = window[vendors[x]+'CancelAnimationFrame'] || window[vendors[x]+'CancelRequestAnimationFrame'];
        }

        if(!window.requestAnimationFrame){
            window.requestAnimationFrame = function(callback, element){
                let currTime = new Date().getTime();
                let timeToCall = Math.max(0, 16 - (currTime - lastTime));
                let id = window.setTimeout(function() { callback(currTime + timeToCall); }, timeToCall);
                lastTime = currTime + timeToCall;
                return id;
            };
        }

        if (!window.cancelAnimationFrame){
            window.cancelAnimationFrame = function(id){
                clearTimeout(id);
            };
        }
    };

    // Scroll
    function scrollTo(element, duration) {
        let e = document.documentElement;
        if(e.scrollTop===0){
            let t = e.scrollTop;
            ++e.scrollTop;
            e = t+1===e.scrollTop--?e:document.body;
        }
        scrollToC(e, e.scrollTop, element, duration);
    }

    function scrollToC(element, from, to, duration) {
        if (duration < 0) return;
        if(typeof from === "object")from=from.offsetTop;
        if(typeof to === "object")to=to.offsetTop;
        scrollToX(element, from, to, 0, 1/duration, 20, easeOutCuaic);
    }

    function scrollToX(element, x1, x2, t, v, step, operacion) {
        if (t < 0 || t > 1 || v <= 0) return;
        element.scrollTop = x1 - (x1-x2)*operacion(t);
        t += v * step;
        setTimeout(function() {
            scrollToX(element, x1, x2, t, v, step, operacion);
        }, step);
    }

    function easeOutCuaic(t){
        t--;
        return t*t*t+1;
    }

    let birdScrollTop = function () {

        let btnTop = document.querySelector('#go-top');
        let top = document.querySelector('#top');
        let btnReveal = function () {
            if (window.scrollY >= 100) {
                btnTop.classList.add('visible');
            }else {
                btnTop.classList.remove('visible');
            }
        };
        let TopScrollTo = function () {
            if(window.scrollY !== 0) {
                scrollTo(top,1000);
            }
        };

        window.addEventListener('scroll', btnReveal);
        btnTop.addEventListener('click', TopScrollTo);

    };

    birdScrollTop();

});
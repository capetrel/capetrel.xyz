document.addEventListener("DOMContentLoaded", function(){

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


});
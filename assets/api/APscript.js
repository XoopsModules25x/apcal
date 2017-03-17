var _GET = new Object;
window.onload = getEvents;

function getParams() {
    var s = document.getElementById('APScript').src;
    var p = s.substring(s.indexOf('?') + 1, s.length).split("&");

    _GET['URL'] = s.substring(0, s.indexOf('/', 7)) + '';

    for (var i in p) {
        var v = p[i].split("=");
        _GET[v[0]] = v[1];
    }
    if (!('h' in _GET) || _GET['h'] == '') {
        _GET['h'] = '';
    }
    if (!('c' in _GET) || _GET['c'] < 0) {
        _GET['c'] = 0;
    }
    if (!('n' in _GET) || _GET['n'] < 1) {
        _GET['n'] = 10;
    }
    if (!('w' in _GET) || _GET['w'] == '') {
        _GET['w'] = '100';
    }
    if (!('t' in _GET) || _GET['t'] == '') {
        _GET['t'] = 'default';
    }
}

function addCSS() {
    var head = document.getElementsByTagName('head')[0];
    var link = document.createElement('link');

    link.rel = 'stylesheet';
    link.href = _GET['t'] == 'theme' ? _GET['URL'] + '/modules/apcal/assets/api/APstyle.css' : _GET['URL'] + '/modules/apcal/assets/api/APdefault.css';
    head.appendChild(link);
}

function display(v) {
    var container = document.getElementById('APContainer');
    var t = '<dl>';
    var e = v[0];
    var d = v[1];
    var c = v[2];
    var h = '<div class="APtitle">' + decodeURIComponent(_GET['h']) + '</div>';
    var s = '';

    if (_GET['t'] == 'custom') {
        s += '<style>';
        s += '#APContainer{' + APborder + '}';
        s += '#APContainer{' + APtext + '}';
        s += '#APContainer a{' + APlink + '}';
        s += '#APContainer div.APtitle{' + APtitle + '}';
        s += '#APContainer dl dt.even{' + APeven + '}';
        s += '#APContainer dl dd.even{' + APeven + '}';
        s += '#APContainer dl dt.odd{' + APodd + '}';
        s += '#APContainer dl dd.odd{' + APodd + '}';
        s += '</style>';
    }

    container.style.width = _GET['w'];

    for (var i in e)
        t += '<dt class=' + (i % 2 ? 'even' : 'odd') + '>' + e[i].start + '</dt><dd class=' + (i % 2 ? 'even' : 'odd') + '><a href="' + e[i].link + '" title="' + e[i].summary + '" target="_blank">' + e[i].summary + '</a></dd>';
    container.innerHTML = s + h + t + d;
}

function getEvents() {
    getParams();
    addCSS();

    var xmlhttp = null;
    if (typeof XDomainRequest != "undefined") {
        xmlhttp = new XDomainRequest();
        xmlhttp.open('GET', _GET['URL'] + '/modules/apcal/getevents.php?c=' + _GET['c'] + '&n=' + _GET['n']);
        xmlhttp.onload = function () {
            display(eval('(' + xmlhttp.responseText + ')'));
        }
    }
    else {
        xmlhttp = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
        xmlhttp.open('GET', _GET['URL'] + '/modules/apcal/getevents.php?c=' + _GET['c'] + '&n=' + _GET['n'], true);
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                display(eval('(' + xmlhttp.responseText + ')'));
            }
        }
    }
    xmlhttp.send();

    /*xmlhttp.open('GET', _GET['URL']+'/modules/apcal/getevents.php?c='+_GET['c']+'&n='+_GET['n'], false);
     xmlhttp.send();
     display(eval('('+xmlhttp.responseText+')'));*/
}

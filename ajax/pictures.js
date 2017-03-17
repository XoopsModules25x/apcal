function deleteMain() {
    document.getElementById("mainPicture").innerHTML =
        '<input type="hidden" name="MAX_FILE_SIZE" value="5000000" />' +
        '<input type="file" name="picture0" id="picture0" />' +
        '<input type="hidden" name="files[]" id="files[]" value="picture0">';
}

function deleteOther(p, n) {
    document.getElementById("picList").innerHTML +=
        '<input type="hidden" name="MAX_FILE_SIZE" value="5000000" />' +
        '<input type="file" name="picture' + n + '" id="picture' + n + '" />' +
        '<input type="hidden" name="files[]" id="files[]" value="picture' + n + '">' +
        '<br>';

    document.getElementById("pic" + p).innerHTML = '';
}

function deletePic(url, p, e, m, n) {
    var xmlhttp = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"); //IF ie7+, FF, Chrome, Opera, Safari, ... ELSE IE6, IE5

    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200 && xmlhttp.responseText > -1) {
            if (m)
                deleteMain();
            else
                deleteOther(p, n - 1 - xmlhttp.responseText);
        }
    }

    xmlhttp.open("GET", url + "/modules/apcal/ajax/pictures.php?p=" + p + "&e=" + e, true);
    xmlhttp.send();
}

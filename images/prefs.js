function getColors()
{
    var isTheme = document.pref_form.apcal_thmORdefault.value;
    if(isTheme == 1 || isTheme == 2)
    {
        var xmlhttp = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
        var themeFile = document.pref_form.apcal_thmCSS.value;
        xmlhttp.open("GET", xoops_url + "/modules/APCal/admin/getThmColor.php?filename=" + themeFile + "&default=" + isTheme, false);
        xmlhttp.send();
        
        var colors = eval('(' + xmlhttp.responseText + ')');
        for(var name in colors)
        {
            document.pref_form.elements[name].value = colors[name];
        }
    }
    
    return true;
}

function addListener()
{
    var el = document.pref_form.apcal_thmORdefault;

    if(el)
    {
        if(el.addEventListener) {el.addEventListener('change', getColors, false);} 
        else if(el.attachEvent) {el.attachEvent('onchange', getColors);}
    }
}

window.onload = addListener;

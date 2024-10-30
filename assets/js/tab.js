function openCity(evt, cityName,target) {
    var i, tabcontent, tablinks;
    console.log(evt.currentTarget.getAttribute('data-id'));
    tabcontent = document.getElementsByClassName(target);
    for (i = 0; i < tabcontent.length; i++) {
        if(evt.currentTarget.getAttribute('data-id')==tabcontent[i].getAttribute('data-id'))
        {
            tabcontent[i].style.display = "none";
        }
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        if(evt.currentTarget.getAttribute('data-id')==tablinks[i].getAttribute('data-id'))
        {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}
// (function ($) {
window.onload = function(e){
    var adminTogMenu = $('.mobile-toggle-menu');
    var adminTogMenuHide = $('.bx-arrow-to-left');
    console.log(adminTogMenuHide)
    adminTogMenu.on('click', function () {
        console.log(adminTogMenu)

        $('.sidebar-wrapper').css('left','0px');
        $('.bx bx-arrow-to-left').css('left','-350px');
    });
    adminTogMenuHide.on('click', function () {
        $('.sidebar-wrapper').css('left','-350px');
    });

}



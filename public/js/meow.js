window.addEventListener("load", myInitFunction);

// window.onload = function () {
function myInitFunction()
{
    if (window.innerWidth < 1200) {
        let current_width = window.innerWidth
        let offset_finall = Number(-450) + Number(((current_width - 300) / 2))
        document.getElementById("meow_main").style.left = offset_finall + 'px';
        document.getElementById("second_meow").style.left = offset_finall + 'px';

    }
}
    // }


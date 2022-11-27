function show_hide_menu_adminka()
{

    let elem = document.getElementById("menu_adminka_column");
    let elem_button = document.getElementById("menu_button_adminka_burger");
    console.log(elem.offsetWidth)
    if(elem.offsetWidth!==1)
    {
        elem.style.width = 0+'px';
        elem_button.classList.remove("active");
    }
    else
    {
        elem.style.width = 250+'px';
        elem_button.classList.add("active");
    }

}

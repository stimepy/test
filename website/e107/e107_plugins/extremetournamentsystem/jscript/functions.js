// JavaScript Document
function updatechecked($elem_name){
    if(!document.getElementsByName($elem_name).defaultChecked){
        document.getElementsByName($elem_name).click;
    }
}

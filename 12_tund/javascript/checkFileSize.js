//console.log("Töötab!");

//muutujad
let fileSizeLimit = 1024 * 1024;

window.onload = function(){
    document.querySelector("#photo_submit").disabled = true;
    document.querySelector("#photo_input").addEventListener("change", checkSize);
}

function checkSize(){
    if(document.querySelector("#photo_input").files[0].size <= fileSizeLimit){
        document.querySelector("#photo_submit").disabled = false;
        //<span></span>
        document.querySelector("#notice").innerHTML = "";
    } else {
        document.querySelector("#photo_submit").disabled = true;
        document.querySelector("#notice").innerHTML = "Valitud fail on <strong>liiga suure</strong> mahuga!";
    }
}
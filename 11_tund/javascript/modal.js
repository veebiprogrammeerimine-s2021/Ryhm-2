let modal;
let modalImg;
let captionText;
let photoId;
let photoDir = "../upload_photos_normal/";

window.onload = function(){
    modal = document.querySelector("#modalarea");
    modalImg = document.querySelector("#modalimg");
    captionText = document.querySelector("#modalcaption");
    let allThumbs = document.querySelector("#gallery").querySelectorAll(".thumbs");
    //console.log(allThumbs);
    for(let i = 0; i < allThumbs.length; i ++){
        allThumbs[i].addEventListener("click", openModal);
    }
    document.querySelector("#closemodal").addEventListener("click", closeModal);
}

function openModal(){
    
}

function closeModal(){
    
}
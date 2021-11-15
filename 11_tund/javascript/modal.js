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
    document.querySelector("#modalclose").addEventListener("click", closeModal);
}

function openModal(e){
    modalImg.src = photoDir + e.target.dataset.fn;
    photoId = e.target.dataset.id;
    captionText.innerHTML = e.target.alt;
    modalImg.alt = e.target.alt;
    document.querySelector("#avgRating").innerHTML = "";
    for(let i = 0; i < 5; i ++){
        document.querySelector("#rate" + (i + 1)).checked = false;
    }
    modal.style.display = "block";
    document.querySelector("#storeRating").addEventListener("click", storeRating);
}

function closeModal(){
    modal.style.display = "none";
    modalimg.src = "../pics/empty.png";
    modalImg.alt = "Galeriipilt";
}

function storeRating(){
    let rating = 0;
    for(let i = 1; i < 6; i ++){
        if(document.querySelector("#rate" + i).checked){
            rating = i;
        }
    }
    if(rating > 0){
        //AJAX
        let webRequest = new XMLHttpRequest();
        webRequest.onreadystatechange = function(){
            //kas õnnestus
            if(this.readyState == 4 && this.status == 200){
                //mida teeme, kui tuli vastus
                document.querySelector("#avgRating").innerHTML = "Keskmine hinne: " + this.responseText;
                document.querySelector("#storeRating").removeEventListener("click", storeRating);
            }
        };
        webRequest.open("GET", "store_photorating.php?photo=" + photoId + "&rating=" + rating, true);
        webRequest.send();
        //AJAX lõppeb
    }
}
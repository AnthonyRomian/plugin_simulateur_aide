let ChauffageOk = document.getElementById("utilisateur_produit_vise_1");
let ChauffageNok = document.getElementById("utilisateur_produit_vise_0");
let Chauffage = document.getElementById("menu-deroulant");


let div1 = document.getElementById("ElemChauffage1");
let div2 = document.getElementById("ElemChauffage2");

ChauffageOk.addEventListener("click", () => {

    if(getComputedStyle(div1).display === "none"){
        div1.style.display = "block";
        div2.style.display = "block";
        Chauffage.style.height = "38em"
        Chauffage.style.transition = "0.5s ease"
    } else {
        ChauffageNok.addEventListener("click", () => {
            if(getComputedStyle(div1).display === "block"){
                div1.style.display = "none";
                Chauffage.style.height = "10em"
                Chauffage.style.transition = "0.5s ease"
            }
        })
    }

    if(getComputedStyle(div2).display === "none"){

        div1.style.display = "block";
        div2.style.display = "block";
        Chauffage.style.height = "38em"
        Chauffage.style.transition = "0.5s ease"
    } else {
        ChauffageNok.addEventListener("click", () => {
            if(getComputedStyle(div2).display === "block"){
                div2.style.display = "none";
                div1.style.display = "none";
                Chauffage.style.height = "10em"
                Chauffage.style.transition = "0.5s ease"
            }
        })
    }
})


const slidePage = document.querySelector(".slide-page");
const nextBtnFirst = document.querySelector(".firstNext");
const prevBtnSec = document.querySelector(".prev-1");
const submitBtn = document.querySelector(".submit");
const progressText = document.querySelectorAll(".step p");
const progressCheck = document.querySelectorAll(".step .check");
const bullet = document.querySelectorAll(".step .bullet");
let current = 1;

nextBtnFirst.addEventListener("click", function(event){
    event.preventDefault();
    slidePage.style.display = "block";
    slidePage.style.marginLeft = "-25%";
    bullet[current - 1].classList.add("active");
    progressCheck[current - 1].classList.add("active");
    progressText[current - 1].classList.add("active");
    current += 1;
});
submitBtn.addEventListener("click", function(){
    bullet[current - 1].classList.add("active");
    progressCheck[current - 1].classList.add("active");
    progressText[current - 1].classList.add("active");
    current += 1;
});

prevBtnSec.addEventListener("click", function(event){
    event.preventDefault();
    slidePage.style.marginLeft = "0%";
    bullet[current - 2].classList.remove("active");
    progressCheck[current - 2].classList.remove("active");
    progressText[current - 2].classList.remove("active");
    current -= 1;
});



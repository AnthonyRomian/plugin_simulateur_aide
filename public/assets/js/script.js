const Choix_1 = document.getElementById("utilisateur_produit_vise_0");
const Choix_2 = document.getElementById("utilisateur_produit_vise_1");
const Choix_3 = document.getElementById("utilisateur_produit_vise_2");

const Chauffage = document.getElementById("menu-deroulant");

let div1 = document.getElementById("ElemChauffage1");
let div2 = document.getElementById("ElemChauffage2");

Choix_2.addEventListener("click", () => {

    if(getComputedStyle(div1).display === "none"){
        if (window.matchMedia("(min-width: 768px)").matches) {
            div1.style.display = "flex";
            div2.style.display = "flex";
            Chauffage.style.height = "26em"
        } else if (window.matchMedia("(min-width: 460px) and (max-width: 767px)").matches) {
            div1.style.display = "flex";
            div2.style.display = "flex";
            Chauffage.style.height = "31em"
        } else {
            div1.style.display = "flex";
            div2.style.display = "flex";
            Chauffage.style.height = "40em"
        }

    } else {
        Choix_1.addEventListener("click", () => {
            if(getComputedStyle(div1).display === "flex"){
                if (window.matchMedia("(min-width: 768px)").matches) {
                    div2.style.display = "none";
                    div1.style.display = "none";
                    Chauffage.style.height = "7em"
                } else if (window.matchMedia("(min-width: 460px) and (max-width: 767px)").matches) {
                    div2.style.display = "none";
                    div1.style.display = "none";
                    Chauffage.style.height = "10em"
                } else {
                    div2.style.display = "none";
                    div1.style.display = "none";
                    Chauffage.style.height = "15em"
                }
            }
        })
        Choix_3.addEventListener("click", () => {
            if(getComputedStyle(div2).display === "flex"){
                if (window.matchMedia("(min-width: 768px)").matches) {
                    div2.style.display = "none";
                    div1.style.display = "none";
                    Chauffage.style.height = "7em"
                } else if( window.matchMedia("(min-width: 460px) and (max-width: 767px)").matches  ) {
                    div2.style.display = "none";
                    div1.style.display = "none";
                    Chauffage.style.height = "10em"
                } else {
                    div2.style.display = "none";
                    div1.style.display = "none";
                    Chauffage.style.height = "15em"
                }
            }
        })
    }
    if(getComputedStyle(div2).display === "none"){
        if (window.matchMedia("(min-width: 768px)").matches) {
            div1.style.display = "flex";
            div2.style.display = "flex";
            Chauffage.style.height = "26em"
        } else if (window.matchMedia("(min-width: 460px) and (max-width: 767px)").matches) {
            div1.style.display = "flex";
            div2.style.display = "flex";
            Chauffage.style.height = "31em"
        } else {
            div1.style.display = "flex";
            div2.style.display = "flex";
            Chauffage.style.height = "40em"
        }
    } else {
        Choix_1.addEventListener("click", () => {
            if(getComputedStyle(div2).display === "flex"){
                if (window.matchMedia("(min-width: 768px)").matches) {
                    div2.style.display = "none";
                    div1.style.display = "none";
                    Chauffage.style.height = "7em"
                } else if( window.matchMedia("(min-width: 460px) and (max-width: 767px)").matches  ) {
                    div2.style.display = "none";
                    div1.style.display = "none";
                    Chauffage.style.height = "10em"
                } else {
                    div2.style.display = "none";
                    div1.style.display = "none";
                    Chauffage.style.height = "15em"
                }
            }
        })
        Choix_3.addEventListener("click", () => {
            if(getComputedStyle(div2).display === "flex"){
                if (window.matchMedia("(min-width: 768px)").matches) {
                    div2.style.display = "none";
                    div1.style.display = "none";
                    Chauffage.style.height = "7em"
                } else if( window.matchMedia("(min-width: 460px) and (max-width: 767px)").matches  ) {
                    div2.style.display = "none";
                    div1.style.display = "none";
                    Chauffage.style.height = "10em"
                } else {
                    div2.style.display = "none";
                    div1.style.display = "none";
                    Chauffage.style.height = "15em"
                }
            }
        })
    }
})


const slidePage = document.querySelector(".slide-page");
const nextBtnFirst = document.querySelector(".firstNext");
const prevBtnSec = document.querySelector(".prev-1");
const submitBtn = document.querySelector(".submit");
const reframe_top = document.getElementById('reframe_top');
let current = 1;


nextBtnFirst.addEventListener("click", function(event){
    event.preventDefault();
    slidePage.style.display = "block";
    slidePage.style.marginLeft = "-50%";
    current += 1;
    setTimeout(function(){
        reframe_top.scrollIntoView();
    },500);
});
submitBtn.addEventListener("click", function(){
    current += 1;
});

prevBtnSec.addEventListener("click", function(event){
    event.preventDefault();
    slidePage.style.marginLeft = "0%";
    current -= 1;
    setTimeout(function(){
        reframe_top.scrollIntoView();
    },500);
});


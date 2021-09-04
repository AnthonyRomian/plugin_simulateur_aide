let ChauffageOk = document.getElementById("utilisateur_produit_vise_1");
let ChauffageNok = document.getElementById("utilisateur_produit_vise_0");


let div1 = document.getElementById("ElemChauffage1");
let div2 = document.getElementById("ElemChauffage2");

ChauffageOk.addEventListener("click", () => {
    if(getComputedStyle(div1).display === "none"){
        div1.style.display = "block";
    } else {
        ChauffageNok.addEventListener("click", () => {
            if(getComputedStyle(div1).display === "block"){
                div1.style.display = "none";
            }
        })
    }

    if(getComputedStyle(div2).display === "none"){
        div2.style.display = "block";
    } else {
        ChauffageNok.addEventListener("click", () => {
            if(getComputedStyle(div2).display === "block"){
                div2.style.display = "none";
            }
        })
    }


})


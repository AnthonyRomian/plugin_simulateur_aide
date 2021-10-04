let ChauffageOk = document.getElementById("utilisateur_produit_vise_1");
let ChauffageNok = document.getElementById("utilisateur_produit_vise_0");
let Chauffage = document.getElementById("menu-deroulant");
let div1 = document.getElementById("ElemChauffage1");
let div2 = document.getElementById("ElemChauffage2");

ChauffageOk.addEventListener("click", () => {
    if (getComputedStyle(div1).display === "none") {
        div1.style.display = "flex";
        div2.style.display = "flex";
        Chauffage.style.height = "6em"
    } else {
        ChauffageNok.addEventListener("click", () => {
            if (getComputedStyle(div1).display === "flex") {
                div2.style.display = "none";
                div1.style.display = "none";
                Chauffage.style.height = "6em"
            }
        })
    }
    if (getComputedStyle(div2).display === "none") {
        div1.style.display = "flex";
        div2.style.display = "flex";
        Chauffage.style.height = "6em"

    } else {
        ChauffageNok.addEventListener("click", () => {
            if (getComputedStyle(div2).display === "flex") {
                div2.style.display = "none";
                div1.style.display = "none";
                Chauffage.style.height = "6em"
            }
        })
    }
})

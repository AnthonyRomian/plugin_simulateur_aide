var fournisseurChoisiSelect = document.getElementById('fournisseurChoisi');
var fournisseurname = fournisseurChoisiSelect.options[fournisseurChoisiSelect.selectedIndex];
const buttonEnvoyer = document.getElementById('SendMailButton');
var idUtil = document.getElementById('utilId').textContent;
console.log(idUtil);
var fournisseurChoisi = fournisseurChoisiSelect.value;
console.log(fournisseurChoisiSelect.value);

document.addEventListener('DOMContentLoaded', function() {
    getNameFournisseur();
});


function getNameFournisseur() {
    fournisseurChoisi = fournisseurChoisiSelect.value;
    console.log(fournisseurChoisi);
    var url_edit = "http://localhost/simulateur_aide/public/admin/sendFournisseur/fournisseurId";
    url_edit = url_edit.replace("http://localhost/simulateur_aide/public/admin/sendFournisseur/fournisseurId", "http://localhost/simulateur_aide/public/admin/sendFournisseur/"+fournisseurChoisi+"/"+idUtil);
    buttonEnvoyer.href = (url_edit);
    console.log(buttonEnvoyer.href)
}





function sendMail() {
        fetch("http://localhost/simulateur_aide/public/admin/sendFournisseur/"+fournisseurChoisi+"/"+idUtil, {
                method: "POST"
        }).then(response => {

                        if (response.ok) {

                        } else {


                        }
        });
}
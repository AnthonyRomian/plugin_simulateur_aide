const searchInput = document.getElementById('searchInput');
const villeField = document.getElementById('villeField')
const divVille = document.getElementsByClassName('test2')


let search = '';


const fetchSearch = async(url) => {
    cp = await fetch(
        `https://apicarto.ign.fr/api/codes-postaux/communes/${url}`)
        .then(res => res.json())

    console.log(cp)

    //'https://geo.api.gouv.fr/communes?nom=${url}&fields=code,nom,centre,codesPostaux'
    //        `https://api-adresse.data.gouv.fr/search/?q=${url}&type=municipality&autocomplete=1`)

    //console.log(ville[0]['properties']['postcode']);

};


//search
const searchDisplay = async() => {
    await fetchSearch(search);
    let villes = [];


    var select = document.createElement('select')
    var div = document.createElement('div')
    for (var i = 0 ; i < cp.length ; i++){
        let ville = cp[i]['nomCommune'];
        if (cp.length <= 1 ) {

            villeField.value = ville;

        }
        else if (cp.length > 1){


            a = document.createElement('div')
            //tableau de ville

            villes.push(ville);
            a.setAttribute('id', ville)
            a.setAttribute('value', ville)
            a.setAttribute('href', '#')
            a.setAttribute('onclick', "remplirField(this.textContent)")
            a.innerHTML = ville;

            div.append(a)
            console.log(div)
        }

    }

    console.log(divVille[0])
    divVille[0].appendChild(div);



};

searchInput.addEventListener('input', (e) => {
    search = `${e.target.value}`
    searchDisplay();
    console.log(search)
})




fetchSearch();
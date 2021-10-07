const searchInput = document.getElementById('searchInput');
const villeField = document.getElementById('villeField')
const divVille = document.getElementsByClassName('test2')

let search = '';

const fetchSearch = async(url) => {
    cp = await fetch(
        `https://apicarto.ign.fr/api/codes-postaux/communes/${url}`)
        .then(res => res.json())
    console.log(cp)
};

let dataList = document.getElementById('lstVilles')

//search
const searchDisplay = async() => {
    await fetchSearch(search);

    var villes = [];

    var select = document.createElement('select')
    var div = document.createElement('div')
    for (var i = 0 ; i < cp.length ; i++){

        let ville = cp[i]['nomCommune'];
        if (cp.length <= 1 ) {
            villeField.value = ville;
        }
        else if (cp.length > 1){

            optionListVille = document.createElement('option')

            //tableau de ville
            villes.push(ville);
            optionListVille.setAttribute('id', ville)
            optionListVille.setAttribute('value', ville)
            optionListVille.setAttribute('href', '#')
            optionListVille.setAttribute('onclick', "remplirField(this.textContent)")

            optionListVille.innerHTML = ville;
            dataList.append(optionListVille)
        }

    }
    console.log(divVille[0])
    divVille[0].appendChild(dataList);
};

searchInput.addEventListener('input', (e) => {
    search = `${e.target.value}`
    if(search.length == 5)
    {
        searchDisplay();
        console.log(search);
    }
})

fetchSearch();
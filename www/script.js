window.addEventListener('load', function () {
    let searchbar_input = document.getElementById('searchbar-input');
    let ville_select = document.getElementById('ville-select');
    let categorie_select = document.getElementById('categorie-select');

    if (searchbar_input !== null && ville_select !== null && categorie_select !== null) {
        let listener = function () {
            let urlSearchParams = new URLSearchParams({
                'search': searchbar_input.value,
                'ville': ville_select.value,
                'categorie': categorie_select.value,
            });
            fetch('?action=catalogue', {
                method: 'POST',
                body: urlSearchParams.toString(),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
            }).then(function (response) {
                response.text().then(
                    function (chaine) {
                        let newDocument = new DOMParser().parseFromString(chaine, 'text/html');
                        let newCat = newDocument.getElementById("catalogue");
                        document.body.replaceChild(newCat, document.getElementById("catalogue"))
                    }
                )
            })
        };
        searchbar_input.addEventListener('input', listener)
        ville_select.addEventListener('change', listener)
        categorie_select.addEventListener('change', listener)

    }
})
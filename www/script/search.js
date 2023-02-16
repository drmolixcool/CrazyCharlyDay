window.addEventListener('load', function () {
    let searchbar_input = document.getElementById('searchbar-input');
    searchbar_input.addEventListener('input', function () {
        let urlSearchParams = new URLSearchParams({
            'search': searchbar_input.value
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
    })
})
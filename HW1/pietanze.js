function chiudiModale() {
    document.body.classList.remove('no-scroll');
    modalView.classList.add('hidden');
    modalView.innerHTML = '';
}

function apriModale(event) {
    const image = createImage(event.currentTarget.src);
    document.body.classList.add('no-scroll');
    modalView.style.top = window.pageYOffset + 'px';
    modalView.appendChild(image);
    modalView.classList.remove('hidden');

    image.addEventListener('click', chiudiModale);
}

const modalView = document.getElementById('modal-view');

function mostraPietanze(json) {
    const pietanzeContainer = document.querySelector('.pietanze');
    pietanzeContainer.innerHTML = '';
    document.body.appendChild(pietanzeContainer);

    for(let j of json) {
        const div = document.createElement('div');
        div.classList.add('pietanza');
        const nomePietanza = document.createElement('h4');
        nomePietanza.textContent = j.NOME;
        nomePietanza.classList.add('pietanza');
        const immaginePietanza = document.createElement('img');
        immaginePietanza.src = './images/' + j.IMMAGINE;
        immaginePietanza.classList.add('pietanza-img');
        const prezzoPietanza = document.createElement('p');
        prezzoPietanza.textContent = j.PREZZO + " €";

        div.appendChild(nomePietanza);
        div.appendChild(immaginePietanza);
        div.appendChild(prezzoPietanza);

        immaginePietanza.addEventListener('click', apriModale);

        pietanzeContainer.appendChild(div);
    }
}

function onResponse(response) {
    return response.json();
}

function createImage(src) {
    const image = document.createElement('img');
    image.src = src;
    return image;
}

var qs = getQueryStrings(); //per poter usare GET passo la categoria nell'URL dopo averla ottenuta tramite la getQueryStrings()
var category = qs["category"];

fetch('pietanze_api.php?category=' + category).then(onResponse).then(mostraPietanze);
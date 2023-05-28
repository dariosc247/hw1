const ratingStars = [...document.getElementsByClassName("rating__star")];

function executeRating(stars) {
    const starClassActive = "rating__star fa fa-star";
    const starClassInactive = "rating__star fa fa-star-o";
    const starsLength = stars.length;
    let i;

    stars.map((star) => {
        star.onclick = () => {
          i = stars.indexOf(star);

          if (star.className === starClassInactive) {
            for (i; i >= 0; --i) stars[i].className = starClassActive;
          }
          else {
            for (i; i < starsLength; ++i) stars[i].className = starClassInactive;
          }
        };
    });

    return i;
}

function getVoteFromStars(stars) {
    let i = 0;
    const starClassActive = "rating__star fa fa-star";

    for(let star of stars) {
        if(star.className === starClassActive) {
            i++;
        }
    }

    return i;
}

function databaseResponse(json) {
    console.log(json.result);

    const form = document.getElementById('review-form');
    const stars = document.querySelectorAll('.rating__star');
    const commentoField = document.getElementById('review-content');

    form.reset();
    for(i = 0; i < 5; i++) {
        stars[i].classList.add('fa-star-o');
    }
    commentoField.value = '';

    const resultP = document.querySelector('.result-p');
    resultP.classList.add('result-p');
    resultP.textContent = json.result;
    form.appendChild(resultP);
}

function onResponse(response) {
    return response.json();
}

function saveReview(event) {
    const form_data = new FormData();
    const vote = getVoteFromStars(ratingStars);

    form_data.append('voto', vote);
    form_data.append('commento', document.querySelector('#review-content').value);

    fetch('savereview.php', {method: 'POST', body: form_data}).then(onResponse).then(databaseResponse);
    event.preventDefault();
}

executeRating(ratingStars);

document.querySelector('#review-form').addEventListener('submit', saveReview);

function displayReviews(json) {
    const table = document.querySelector('#table');
    const contentTable = document.querySelector('#content-table');
    document.querySelector('#th1').classList.remove('hidden');
    document.querySelector('#th2').classList.remove('hidden');
    document.querySelector('#th3').classList.remove('hidden');

    for(let review of json) {
        const tr = document.createElement('tr');
        
        const tdUtente = document.createElement('td');
        tdUtente.textContent = review.USERNAME;
        tr.appendChild(tdUtente);
        
        const tdVoto = document.createElement('td');
        tdVoto.textContent = review.VOTO + "/5";
        tr.appendChild(tdVoto);

        const tdCommento = document.createElement('td');
        tdCommento.textContent = review.COMMENTO;
        tr.appendChild(tdCommento);

        contentTable.appendChild(tr);

        table.appendChild(contentTable);
    }
}

function visualizzaRecensioni() {
    document.getElementById('table').classList.remove('hidden');
    fetch('visualizzarecensioni.php').then(onResponse).then(displayReviews);
    document.getElementById('reviews-button').removeEventListener('click', visualizzaRecensioni);
}


document.getElementById('reviews-button').addEventListener('click', visualizzaRecensioni);
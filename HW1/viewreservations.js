function displayReservations(json) {
    console.log(json);

    const table = document.querySelector('#table');
    const contentTable = document.querySelector('#content-table');
    document.querySelector('#th1').classList.remove('hidden');
    document.querySelector('#th2').classList.remove('hidden');
    document.querySelector('#th3').classList.remove('hidden');
    document.querySelector('#th4').classList.remove('hidden');
    document.querySelector('#th5').classList.remove('hidden');
    document.querySelector('#th6').classList.remove('hidden');

    for(let j of json) {
        const tr = document.createElement('tr');
        
        const tdTitolo = document.createElement('td');
        tdTitolo.textContent = j.TITOLO;
        tr.appendChild(tdTitolo);

        const tdDescrizione = document.createElement('td');
        tdDescrizione.textContent = j.DESCRIZIONE;
        tr.appendChild(tdDescrizione);

        const tdData = document.createElement('td');
        tdData.textContent = j.DATA;
        tr.appendChild(tdData);

        const tdFrom = document.createElement('td');
        tdFrom.textContent = j.ORARIO_DA;
        tr.appendChild(tdFrom);

        const tdTo = document.createElement('td');
        tdTo.textContent = j.ORARIO_FINO;
        tr.appendChild(tdTo);

        const tdUtente = document.createElement('td');
        tdUtente.textContent = j.USERNAME;
        tr.appendChild(tdUtente);

        contentTable.appendChild(tr);

        table.appendChild(contentTable);
    }
}

function onResponse(response) {
    return response.json();
}

fetch('viewreservations_api.php').then(onResponse).then(displayReservations);
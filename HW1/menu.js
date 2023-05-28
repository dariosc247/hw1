function displayMenu(json) {
    
    const menuContainer = document.querySelector('.menu-items');
    menuContainer.innerHTML = '';

    for(let j of json) {
        const elem = document.createElement('div');
        elem.textContent = j.NOME;
        elem.classList.add('menu-item');
        elem.style.backgroundImage = 'url(./images/'+ j.IMMAGINE +')';
        menuContainer.appendChild(elem);
        elem.addEventListener('mouseover', () => {elem.classList.add('hover');});
        elem.addEventListener('click', () => {window.location.href = 'pietanze.php?category='+j.ID;});
    }

}

function onResponse(response) {
    return response.json();
}

fetch('menu.php').then(onResponse).then(displayMenu);
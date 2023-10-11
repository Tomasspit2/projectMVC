document.getElementById('sort-asc').addEventListener('click', function() {
    sortAnnonces(true);
});

document.getElementById('sort-desc').addEventListener('click', function() {
    sortAnnonces(false);
});

function sortAnnonces(isAscending) {
    const cardContainer = document.getElementById('cd-container-card');
    const cards = Array.from(cardContainer.getElementsByClassName('cd-card'));

    cards.sort(function(a, b) {
        const aText = a.getElementsByClassName('cd-model')[0].innerText;
        const bText = b.getElementsByClassName('cd-model')[0].innerText;

        if (aText > bText) return isAscending ? 1 : -1;
        if (aText < bText) return isAscending ? -1 : 1;
        return 0;
    });

    // Remove all existing cards
    while (cardContainer.firstChild) {
        cardContainer.removeChild(cardContainer.firstChild);
    }

    // Append sorted cards
    cards.forEach(function(card) {
        cardContainer.appendChild(card);
    });
}

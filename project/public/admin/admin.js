function openTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablink");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}

// Fonction pour récupérer la liste des matchs sportifs pour un tournoi
function fetchSportMatches(tournamentId) {
    fetch(`/api/tournaments/${tournamentId}/sport-matchs`)
        .then(response => response.json())
        .then(data => displaySportMatches(data))
        .catch(error => console.error('Error:', error));
}

// Fonction pour afficher les matchs sportifs sur la page
function displaySportMatches(matches) {
    const matchesDiv = document.getElementById('Parties');
    matches.forEach(match => {
        const matchDiv = document.createElement('div');
        matchDiv.textContent = `Match ${match.id}: ${match.player1} vs ${match.player2}`;
        matchesDiv.appendChild(matchDiv);
    });
}

// Appeler la fonction fetchSportMatches lorsque la page est chargée
document.addEventListener("DOMContentLoaded", function() {
    fetchSportMatches(1);  // Remplacez 1 par l'ID du tournoi que vous voulez afficher
});

// Default open tab
document.addEventListener("DOMContentLoaded", function() {
    document.getElementsByClassName("tablink")[0].click();
});
async function loadPublisherGames() {
    try {
        const res = await fetch("/PenaconyExchange/db/backend/publisher/loadPublisherGame.php");
        const games = await res.json();

        const gamesContainer = document.getElementById("gamesContainer");
        const noGamesMessage = document.getElementById("noGamesMessage");

        if (!games || games.length === 0) {
            noGamesMessage.style.display = "block";
            return;
        }

        games.forEach(game => {
            const card = document.createElement("div");
            card.className = "gameCard";
            card.onclick = () => {
                window.location.href = `/PenaconyExchange/pages/publisher/gameMaintenance.php?gameId=${game.gameId}`;
            };

            card.innerHTML = `
                <img class="gameImage" src="${game.mainPicture}" alt="${game.gameTitle}">
                <div class="gameDetails">
                    <div>
                        <p class="gameTitle">${game.gameTitle}</p>
                        <p class="gameYear">Release Year: ${new Date(game.releaseDate).getFullYear()}</p>
                        <p class="gameDesc">${game.gameDesc ? game.gameDesc : "No description provided."}</p>
                    </div>
                    <p class="price">Price: RM${parseFloat(game.price).toFixed(2)}</p>
                </div>
            `;
            gamesContainer.appendChild(card);
        });
    } catch (err) {
        console.error("Error loading games:", err);
    }
}

loadPublisherGames();
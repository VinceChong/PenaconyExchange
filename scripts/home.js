document.addEventListener("DOMContentLoaded", () => {
    fetch("/PenaconyExchange/db/retrieveGames.php")
        .then(response => {
            if (!response.ok) {
                throw new Error("HTTP status " + response.status);
            }
            return response.json();
        })
        .then(games => {

            const container = document.getElementById("gameList");

            if (!Array.isArray(games)) {
                container.innerHTML = "<p>No game data available.</p>";
                return;
            }

            games.forEach(game => {
                const card = document.createElement("div");
                card.className = "game-card";

                card.innerHTML = `
                    <a href="/PenaconyExchange/pages/gameDetail.php?gameId=${game.gameId}">
                        <img src="${game.mainPicture}" alt="${game.gameTitle}" />
                    </a>
                    <p>${game.gameTitle}</p>
                `;

                container.appendChild(card);
            });
        })
        .catch(error => {
            console.error("Fetch error:", error);
            document.getElementById("gameList").innerHTML = `<p>Error loading games: ${error.message}</p>`;
        });
});

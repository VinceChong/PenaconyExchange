<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION["user"])) {
        header("Location: /PenaconyExchange/pages/authentication.php");
        exit;
    }

    include "../db/backend/db.php";

    $userId = intval($_SESSION["user"]["id"]);
    $query = "SELECT g.gameId, g.gameTitle, g.mainPicture FROM purchasedgame p JOIN game g ON p.gameId = g.gameId WHERE p.userId = ?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $games = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $games[] = $row;
        }
    }
?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Library</title>
        <link rel="stylesheet" href="/PenaconyExchange/styles/common.css"/>
        <link rel="stylesheet" href="/PenaconyExchange/styles/library.css"/>
        <link rel="icon" href="/PenaconyExchange/assets/image/harmony.png">
    </head>

    <body>
        <?php include("../includes/header.php"); ?>

        <div class="pageWrapper">
            <div class="pageContent">
                <div class="library">
                    <!-- LEFT: Sidebar -->
                    <aside class="library__sidebar">
                        <div class="sidebar__header">Home</div>

                        <div class="sidebar__section">
                            <div class="sidebar__title">Games</div>

                            <div class="sidebar__search">
                                <input type="search" placeholder="Search" aria-label="Search games" />
                            </div>

                            <div class="sidebar__filter">
                                <button class="filter__btn filter__btn--active">
                                    ALL <span class="filter__count"><?php echo count($games); ?></span>
                                </button>
                            </div>

                            <ul class="sidebar__list">
                                <?php foreach ($games as $game): ?>
                                    <li class="sidebar__item">
                                        <img
                                            class="sidebar__thumb"
                                            src="<?php echo htmlspecialchars($game['mainPicture']); ?>"
                                            alt="<?php echo htmlspecialchars($game['gameTitle']); ?> thumbnail"
                                        />
                                        <span class="sidebar__name"><?php echo htmlspecialchars($game['gameTitle']); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </aside>

                    <!-- RIGHT: Main content -->
                    <main class="library__main">
                        <!-- Recent Games -->
                        <section class="shelf shelf--recent">
                            <div class="shelf__header">
                                <h2 class="shelf__title">Recent Games</h2>
                                <span class="shelf__sub">September 2023</span>
                            </div>

                            <div class="shelf__row">
                                <?php foreach ($games as $game): ?>
                                    <article class="capsule">
                                        <img
                                            class="capsule__img"
                                            src="<?php echo htmlspecialchars($game['mainPicture']); ?>"
                                            alt="<?php echo htmlspecialchars($game['gameTitle']); ?> cover"
                                        />
                                        <div class="capsule__overlay">
                                            <div class="timecard">
                                                <div class="timecard__icon" aria-hidden="true">
                                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                                                        <path d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14"
                                                            stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </div>
                                                <div class="timecard__info">
                                                    <div class="timecard__label">TIME PLAYED</div>
                                                    <div class="timecard__line">Last two weeks: 0 min</div>
                                                    <div class="timecard__line">Total: 81 min</div>
                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        </section>

                        <!-- All Games -->
                        <section class="shelf shelf--all">
                            <div class="shelf__header shelf__header--row">
                                <h2 class="shelf__title">All Games <span class="shelf__count"><?php echo count($games); ?></span></h2>
                                <div class="shelf__tools">
                                    <label class="shelf__sort-label" for="sort-select">Sort by</label>
                                    <select id="sort-select" class="shelf__sort">
                                        <option selected>Alphabetical</option>
                                        <option>Hours played</option>
                                        <option>Recently played</option>
                                        <option>Date added</option>
                                    </select>
                                    <button class="shelf__layout" aria-label="Toggle layout">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                                            <path d="M4 7h16M4 12h16M4 17h16"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="grid">
                                <?php foreach ($games as $game): ?>
                                    <article class="capsule">
                                        <img
                                            class="capsule__img"
                                            src="<?php echo $game['mainPicture']; ?>"
                                            alt="<?php echo $game['gameTitle']; ?> cover"
                                        />
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    </main>
                </div>
            </div>
        </div>

        <?php include("../includes/footer.php"); ?>
        <script src="/PenaconyExchange/scripts/library.js"></script>
    </body>
</html>
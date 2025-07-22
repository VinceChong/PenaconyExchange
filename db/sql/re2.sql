USE PenaconyExchange;

-- Insert Game
INSERT INTO Game (
    gameTitle, gameDesc, mainPicture, price, releaseDate, publisherId, developerId
) VALUES (
    'Resident Evil 2',
    'A remake of the classic survival horror game, bringing terrifying visuals and immersive storytelling.',
    '/PenaconyExchange/db/assets/re2/re2_main.jpg',
    135.90,
    '2019-01-25',
    1, -- Capcom as both publisher & developer
    1
);

-- Link to Categories: Action, RPG (assuming both exist in your Category table)
INSERT INTO GameCategory (gameId, categoryId) VALUES
(5, 1), -- Action
(5, 2), -- RPG
(5, 4), -- Single Player
(5, 6); -- Horror

-- About Game
INSERT INTO AboutGame (gameId, detailedDesc, videoUrl, imageUrl) VALUES (
    5,
    'Experience a terrifying reimagining of the classic horror game. Step into the shoes of Leon S. Kennedy and Claire Redfield as they attempt to escape Raccoon City in a stunningly recreated survival horror experience.',
    '/PenaconyExchange/db/assets/re2/re2_trailer.mp4',
    '/PenaconyExchange/db/assets/re2/re2_banner.jpg'
);

-- Minimum System Requirements
INSERT INTO SystemRequirement (
    gameId, type, os, processor, memory, graphic, directX, storage, soundCard
) VALUES (
    5, 'minimum', 'Windows 7 64-bit',
    'Intel Core i5-4460 / AMD FX-6300',
    '8 GB RAM',
    'NVIDIA GeForce GTX 760 / AMD Radeon R7 260x',
    'Version 11',
    '26 GB available space',
    'DirectSound compatible'
);

-- Recommended System Requirements
INSERT INTO SystemRequirement (
    gameId, type, os, processor, memory, graphic, directX, storage, soundCard
) VALUES (
    5, 'recommended', 'Windows 10 64-bit',
    'Intel Core i7-3770 / AMD FX-9590',
    '8 GB RAM',
    'NVIDIA GeForce GTX 1060 / AMD Radeon RX 480',
    'Version 11',
    '26 GB available space',
    'DirectSound compatible'
);

-- Game Trailer
INSERT INTO GamePreview (gameId, type, title, url) VALUES (
    5, 'video', 'Resident Evil 2 Launch Trailer', '/PenaconyExchange/db/assets/re2/re2_trailer.mp4'
);

-- Game Screenshot
INSERT INTO GamePreview (gameId, type, title, url) VALUES (
    5, 'image', 'Gameplay Screenshot', '/PenaconyExchange/db/assets/re2/re2_banner.jpg'
);

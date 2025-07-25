USE PenaconyExchange;

-- Insert Game
INSERT INTO Game (
    gameTitle, gameDesc, mainPicture, price, releaseDate, publisherId
) VALUES (
    'Monster Hunter: World',
    'Battle gigantic monsters in epic locales. Monster Hunter: World delivers a vast open-world hunting experience.',
    '/PenaconyExchange/db/assets/mhw/mhw_main.jpg',
    169.80,
    '2018-01-26',
    1
);

-- Link Game to Categories (Action, RPG)
INSERT INTO GameCategory (gameId, categoryId) VALUES
(2, 1),  -- Action
(2, 2),  -- RPG
(2, 5);  -- Multiplayer

-- Insert About Game
INSERT INTO AboutGame (gameId, detailedDesc, videoUrl, imageUrl) VALUES (
    2,
    'Monster Hunter: World lets you hunt gigantic monsters in epic environments using powerful weapons and tools. Join friends online for co-op missions.',
    '/PenaconyExchange/db/assets/mhw/mhw_trailer.mp4',
    '/PenaconyExchange/db/assets/mhw/mhw_banner.jpg'
);

-- Insert Minimum System Requirement
INSERT INTO SystemRequirement (
    gameId, type, os, processor, memory, graphic, directX, storage, soundCard
) VALUES (
    2, 'minimum', 'Windows 7/8/10 (64-bit)',
    'Intel Core i5-4460 / AMD FX-6300',
    '8 GB RAM',
    'NVIDIA GTX 760 / AMD Radeon R7 260x',
    'Version 11',
    '48 GB available space',
    'DirectSound compatible'
);

-- Insert Recommended System Requirement
INSERT INTO SystemRequirement (
    gameId, type, os, processor, memory, graphic, directX, storage, soundCard
) VALUES (
    2, 'recommended', 'Windows 10 (64-bit)',
    'Intel Core i7 3770 / AMD Ryzen 5 1500X',
    '8 GB RAM',
    'NVIDIA GTX 1060 / AMD Radeon RX 570',
    'Version 11',
    '48 GB available space',
    'DirectSound compatible'
);

-- Insert Game Video Trailer
INSERT INTO GamePreview (gameId, type, title, url) VALUES (
    2, 'video', 'Official Trailer', '/PenaconyExchange/db/assets/mhw/mhw_trailer.mp4'
);

-- Insert Game Screenshot
INSERT INTO GamePreview (gameId, type, title, url) VALUES (
    2, 'image', 'Gameplay Screenshot', '/PenaconyExchange/db/assets/mhw/mhw_banner.mp4'
);

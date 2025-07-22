use PenaconyExchange;

-- Insert Game
INSERT INTO Game (
    gameTitle, gameDesc, mainPicture, price, releaseDate, publisherId, developerId
) VALUES (
    'Monster Hunter Rise: Sunbreak',
    'An expansion to Monster Hunter Rise, bringing new monsters, quests, and a master rank difficulty.',
    '/PenaconyExchange/db/assets/mhr/mhr_main.jpg',  
    204.90,
    '2022-06-30',
    1,
    1
);

-- Link Game to Categories
INSERT INTO GameCategory (gameId, categoryId) VALUES
(1, 1),  -- Action
(1, 2),  -- RPG
(1, 5);  -- Multiplayer

-- Insert About Game
INSERT INTO AboutGame (gameId, detailedDesc, videoUrl, imageUrl) VALUES (
    1,
    'Monster Hunter Rise: Sunbreak introduces the Master Rank, new monsters like Malzeno, and exciting new locales.',
    '/PenaconyExchange/db/assets/mhr/mhr_trailer.mp4',
    '/PenaconyExchange/db/assets/mhr/mhr_banner.jpg'  
);

-- Insert Minimum System Requirement
INSERT INTO SystemRequirement (
    gameId, type, os, processor, memory, graphic, directX, storage, soundCard
) VALUES (
    1, 'minimum', 'Windows 10 (64-bit)',
    'Intel Core i3-4130 / AMD FX-6100',
    '8 GB RAM',
    'NVIDIA GTX 1030 / AMD Radeon RX 550',
    'Version 12',
    '36 GB available space',
    'DirectSound compatible'
);

-- Insert Recommended System Requirement
INSERT INTO SystemRequirement (
    gameId, type, os, processor, memory, graphic, directX, storage, soundCard
) VALUES (
    1, 'recommended', 'Windows 10 (64-bit)',
    'Intel Core i5-4460 / AMD FX-8300',
    '8 GB RAM',
    'NVIDIA GTX 1060 / AMD Radeon RX 570',
    'Version 12',
    '36 GB available space',
    'DirectSound compatible'
);

-- Insert Game Video Trailer
INSERT INTO GamePreview (gameId, type, title, url) VALUES (
    1, 'video', 'Official Trailer', '/PenaconyExchange/db/assets/mhr/mhr_trailer.mp4'
);

-- Insert Game Screenshot
INSERT INTO GamePreview (gameId, type, title, url) VALUES (
    1, 'image', 'Gameplay Screenshot', '/PenaconyExchange/db/assets/mhr/mhr_banner.jpg'  
);

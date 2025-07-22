USE PenaconyExchange;

-- Insert Game
INSERT INTO Game (
    gameTitle, gameDesc, mainPicture, price, releaseDate, publisherId, developerId
) VALUES (
    'Honkai: Star Rail',
    'Honkai: Star Rail is a space fantasy RPG with turn-based combat and a rich sci-fi story.',
    '/PenaconyExchange/db/image/hsr/hsr_main.jpg',
    0.00,
    '2023-04-26',
    2,  
    2  
);

-- Link Game to Categories (RPG, Turn-Based)
INSERT INTO GameCategory (gameId, categoryId) VALUES
(3, 2), -- RPG
(3, 3), -- Turn Based
(3, 4); -- Single Player

-- Insert About Game
INSERT INTO AboutGame (gameId, detailedDesc, videoUrl, imageUrl) VALUES (
    3,
    'Honkai: Star Rail takes players on a journey across the galaxy aboard the Astral Express. Enjoy tactical turn-based combat and an immersive sci-fi story.',
    '/PenaconyExchange/db/assets/hsr/hsr_trailer.mp4',
    '/PenaconyExchange/db/assets/hsr/hsr_banner.jpg'
);

-- Insert Minimum System Requirement
INSERT INTO SystemRequirement (
    gameId, type, os, processor, memory, graphic, directX, storage, soundCard
) VALUES (
    3, 'minimum', 'Windows 10 (64-bit)',
    'Intel Core i5',
    '8 GB RAM',
    'NVIDIA GeForce GTX 650',
    'Version 11',
    '20 GB available space',
    'DirectSound compatible'
);

-- Insert Recommended System Requirement
INSERT INTO SystemRequirement (
    gameId, type, os, processor, memory, graphic, directX, storage, soundCard
) VALUES (
    3, 'recommended', 'Windows 10 (64-bit)',
    'Intel Core i7',
    '16 GB RAM',
    'NVIDIA GeForce GTX 1060 or better',
    'Version 11',
    '20 GB available space',
    'DirectSound compatible'
);

-- Insert Game Video Trailer
INSERT INTO GamePreview (gameId, type, title, url) VALUES (
    3, 'video', 'Official Trailer', '/PenaconyExchange/db/assets/hsr/hsr_trailer.mp4'
);

-- Insert Game Screenshot
INSERT INTO GamePreview (gameId, type, title, url) VALUES (
    3, 'image', 'Gameplay Screenshot', '/PenaconyExchange/db/assets/hsr/hsr_banner.jpg'
);

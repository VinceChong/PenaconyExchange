USE PenaconyExchange;

-- Insert Game
INSERT INTO Game (
    gameTitle, gameDesc, mainPicture, price, releaseDate, publisherId, developerId
) VALUES (
    'Genshin Impact',
    'Genshin Impact is an open-world action RPG where players explore the fantasy land of Teyvat.',
    '/PenaconyExchange/db/assets/gi/gi_main.jpg',
    0.00,
    '2020-09-28',
    2, 
    2
);

-- Link to Categories: RPG, Adventure
INSERT INTO GameCategory (gameId, categoryId) VALUES
(4, 2), -- RPG
(4, 5); -- Multiplayer

-- About Game
INSERT INTO AboutGame (gameId, detailedDesc, videoUrl, imageUrl) VALUES (
    4,
    'Step into Teyvat, a vast world teeming with life and elemental energy. Explore, fight, and uncover the mysteries of the Seven.',
    '/PenaconyExchange/db/assets/gi/gi_trailer.mp4',
    '/PenaconyExchange/db/assets/gi/gi_banner.jpg'
);

-- Minimum System Requirement
INSERT INTO SystemRequirement (
    gameId, type, os, processor, memory, graphic, directX, storage, soundCard
) VALUES (
    4, 'minimum', 'Windows 7 SP1 64-bit',
    'Intel Core i5 or equivalent',
    '8 GB RAM',
    'NVIDIA GT 1030 or equivalent',
    'Version 11',
    '30 GB available space',
    'DirectSound compatible'
);

-- Recommended System Requirement
INSERT INTO SystemRequirement (
    gameId, type, os, processor, memory, graphic, directX, storage, soundCard
) VALUES (
    4, 'recommended', 'Windows 10 64-bit',
    'Intel Core i7 equivalent',
    '16 GB RAM',
    'NVIDIA GTX 1060 6GB or better',
    'Version 11',
    '30 GB available space',
    'DirectSound compatible'
);

-- Video Trailer
INSERT INTO GamePreview (gameId, type, title, url) VALUES (
    4, 'video', 'Official Trailer', '/PenaconyExchange/db/assets/gi/gi_trailer.mp4'
);

-- Screenshot
INSERT INTO GamePreview (gameId, type, title, url) VALUES (
    4, 'image', 'Gameplay Screenshot', '/PenaconyExchange/db/assets/gi/gi_banner.jpg'
);

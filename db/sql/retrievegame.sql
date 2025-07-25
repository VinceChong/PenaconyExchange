SELECT 
    g.gameId,
    g.gameTitle,
    g.gameDesc,
    g.mainPicture,
    g.price,
    g.releaseDate,
    
    -- Publisher
    p.publisherName,
    
    -- About Game
    ag.detailedDesc,
    ag.videoUrl AS aboutVideo,
    ag.imageUrl AS aboutImage,
    
    -- System Requirements
    srMin.os AS minOS,
    srMin.processor AS minProcessor,
    srMin.memory AS minMemory,
    srMin.graphic AS minGraphic,
    srMin.directX AS minDirectX,
    srMin.storage AS minStorage,
    srMin.soundCard AS minSoundCard,

    srRec.os AS recOS,
    srRec.processor AS recProcessor,
    srRec.memory AS recMemory,
    srRec.graphic AS recGraphic,
    srRec.directX AS recDirectX,
    srRec.storage AS recStorage,
    srRec.soundCard AS recSoundCard,

    -- Categories
    GROUP_CONCAT(c.categoryName) AS categories,

    -- can join previews separately if needed (multiple video and images)
    gp.type AS previewType,
    gp.title AS previewTitle,
    gp.url AS previewUrl

FROM Game g

-- JOIN with Publisher and Developer
JOIN Publisher p ON g.publisherId = p.publisherId

-- JOIN with AboutGame
LEFT JOIN AboutGame ag ON g.gameId = ag.gameId

-- JOIN with SystemRequirement (min & recommended)
LEFT JOIN SystemRequirement srMin ON g.gameId = srMin.gameId AND srMin.type = 'minimum'
LEFT JOIN SystemRequirement srRec ON g.gameId = srRec.gameId AND srRec.type = 'recommended'

-- JOIN with Category via GameCategory
LEFT JOIN GameCategory gc ON g.gameId = gc.gameId
LEFT JOIN Category c ON gc.categoryId = c.categoryId

-- JOIN with GamePreview (optional - limit one row per game in this query)
LEFT JOIN GamePreview gp ON g.gameId = gp.gameId AND gp.type = 'video'

GROUP BY g.gameId;

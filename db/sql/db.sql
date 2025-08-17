CREATE DATABASE IF NOT EXISTS PenaconyExchange;

USE PenaconyExchange;

CREATE TABLE IF NOT EXISTS User (
    userId INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    profilePicture VARCHAR(255) DEFAULT "N/A"
);

CREATE TABLE IF NOT EXISTS ContactUs (
    contactId INT AUTO_INCREMENT PRIMARY KEY,
    userId INT NOT NULL,
    message VARCHAR(255) NOT NULL,
    FOREIGN KEY (userId) REFERENCES User(userId) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Publisher (
    publisherId INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    logo VARCHAR(255) DEFAULT "N/A"
);

CREATE TABLE IF NOT EXISTS IsFollowing (
    userId INT NOT NULL,
    publisherId INT NOT NULL,
    PRIMARY KEY (userId, publisherId),
    FOREIGN KEY (userId) REFERENCES User(userId) ON DELETE CASCADE,
    FOREIGN KEY (publisherId) REFERENCES Publisher(publisherId) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Category (
    categoryId INT AUTO_INCREMENT PRIMARY KEY,
    categoryName VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS Game (
    gameId INT AUTO_INCREMENT PRIMARY KEY,
    gameTitle VARCHAR(255) NOT NULL,
    gameDesc VARCHAR(255) NOT NULL,
    mainPicture VARCHAR(255) NOT NULL,
    price DOUBLE NOT NULL DEFAULT 0,
    releaseDate DATE NOT NULL,
    publisherId INT NOT NULL,
    FOREIGN KEY (publisherId) REFERENCES Publisher(publisherId)
);

CREATE TABLE IF NOT EXISTS GameCategory (
    gameId INT NOT NULL,
    categoryId INT NOT NULL,
    PRIMARY KEY (gameId, categoryId),
    FOREIGN KEY (gameId) REFERENCES Game(gameId) ON DELETE CASCADE,
    FOREIGN KEY (categoryId) REFERENCES Category(categoryId) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS AboutGame (
    aboutId INT AUTO_INCREMENT PRIMARY KEY,
    gameId INT NOT NULL,
    detailedDesc VARCHAR(500) NOT NULL,
    videoUrl VARCHAR(255) NOT NULL,
    imageUrl VARCHAR(255) NOT NULL,
    FOREIGN KEY (gameId) REFERENCES Game(gameId) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS SystemRequirement (
    srId INT AUTO_INCREMENT PRIMARY KEY,
    gameId INT NOT NULL,
    type ENUM('minimum', 'recommended') NOT NULL, 
    os VARCHAR(100) NOT NULL,
    processor VARCHAR(100) NOT NULL,
    memory VARCHAR(100) NOT NULL,
    graphic VARCHAR(100) NOT NULL,
    directX VARCHAR(50) NOT NULL DEFAULT "N/A",
    storage VARCHAR(100) NOT NULL,
    soundCard VARCHAR(100) NOT NULL DEFAULT "N/A",
    FOREIGN KEY (gameId) REFERENCES Game(gameId) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS GamePreview (
    gamePreviewId INT AUTO_INCREMENT PRIMARY KEY,
    gameId INT NOT NULL,
    type ENUM('video', 'image') NOT NULL, 
    title TEXT NOT NULL,
    url VARCHAR(255) NOT NULL,
    FOREIGN KEY (gameId) REFERENCES Game(gameId) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Cart (
    userId INT NOT NULL,
    gameId INT NOT NULL,
    PRIMARY KEY (userId, gameId),
    FOREIGN KEY (userId) REFERENCES User(userId) ON DELETE CASCADE,
    FOREIGN KEY (gameId) REFERENCES Game(gameId) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Wishlist (
    userId INT NOT NULL,
    gameId INT NOT NULL,
    PRIMARY KEY (userId, gameId),
    FOREIGN KEY (userId) REFERENCES User(userId) ON DELETE CASCADE,
    FOREIGN KEY (gameId) REFERENCES Game(gameId) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Library (
    userId INT NOT NULL,
    gameId INT NOT NULL,
    PRIMARY KEY (userId, gameId),
    FOREIGN KEY (userId) REFERENCES User(userId) ON DELETE CASCADE,
    FOREIGN KEY (gameId) REFERENCES Game(gameId) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS UserTransaction (
    transactionId INT AUTO_INCREMENT PRIMARY KEY,
    userId INT NOT NULL,
    gameId INT NOT NULL,
    totalAmount DOUBLE NOT NULL DEFAULT 0,
    paymentMethod ENUM('e-wallet', 'credit', 'debit') NOT NULL, 
    transactionDate DATE NOT NULL,
    FOREIGN KEY (userId) REFERENCES User(userId) ON DELETE CASCADE,
    FOREIGN KEY (gameId) REFERENCES Game(gameId) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Comment (
    commentId INT AUTO_INCREMENT PRIMARY KEY,
    userId INT NOT NULL,
    gameId INT NOT NULL,
    rating ENUM('1', '2', '3', '4', '5') NOT NULL,
    FOREIGN KEY (userId) REFERENCES User(userId) ON DELETE CASCADE,
    FOREIGN KEY (gameId) REFERENCES Game(gameId) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS PurchasedGame (
    userId INT NOT NULL,
    gameId INT NOT NULL,
    PRIMARY KEY (userId, gameId),
    FOREIGN KEY (userId) REFERENCES User(userId) ON DELETE CASCADE,
    FOREIGN KEY (gameId) REFERENCES Game(gameId) ON DELETE CASCADE
);
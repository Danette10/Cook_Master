CREATE DATABASE IF NOT EXISTS cookorama;
ALTER DATABASE cookorama CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
SET GLOBAL time_zone = 'Europe/Paris';

CREATE TABLE IF NOT EXISTS users
(
    idUser          INT AUTO_INCREMENT,
    lastname        VARCHAR(30) NOT NULL,
    firstname       VARCHAR(30) NOT NULL,
    profilePicture  VARCHAR(30) NOT NULL,
    email           VARCHAR(50) NOT NULL,
    password        CHAR(128)   NOT NULL,
    birthdate       DATE        NOT NULL,
    address         VARCHAR(50) NOT NULL,
    postalCode      CHAR(5)     NOT NULL,
    city            VARCHAR(40) NOT NULL,
    role            INT         NOT NULL,
    token           CHAR(128)   NOT NULL,
    fidelityCounter INT         NOT NULL,
    cardIdentity    VARCHAR(30),
    diploma         VARCHAR(30),
    creation        DATETIME    NOT NULL,
    PRIMARY KEY (idUser)
);

CREATE TABLE IF NOT EXISTS message
(
    id         INT AUTO_INCREMENT,
    message    TEXT     NOT NULL,
    dateSend   DATETIME NOT NULL,
    status     INT      NOT NULL,
    idSender   INT      NOT NULL,
    idReceiver INT      NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (idSender) REFERENCES users (idUser),
    FOREIGN KEY (idReceiver) REFERENCES users (idUser)
);

CREATE TABLE IF NOT EXISTS place
(
    idPlace   INT AUTO_INCREMENT,
    address           VARCHAR(50),
    postalCode        CHAR(5),
    city              VARCHAR(40),
    PRIMARY KEY (idPlace)
);

CREATE TABLE IF NOT EXISTS rooms
(
    idRoom   INT AUTO_INCREMENT,
    name     VARCHAR(30) NOT NULL,
    capacity INT         NOT NULL,
    availability BOOLEAN NOT NULL DEFAULT TRUE,
    image          VARCHAR(100) NOT NULL,
    description       VARCHAR(150) NOT NULL,
    creation          DATETIME    NOT NULL,
    idPlace  INT,
    PRIMARY KEY (idRoom),
    FOREIGN KEY (idPlace) REFERENCES place (idPlace)
);

CREATE TABLE IF NOT EXISTS products
(
    id          INT AUTO_INCREMENT,
    idProduct   VARCHAR(50)  NOT NULL,
    name        VARCHAR(30)  NOT NULL,
    description VARCHAR(150) NOT NULL,
    image       VARCHAR(100) NOT NULL,
    type        INT          NOT NULL,
    price       VARCHAR(15)  NOT NULL,
    quantity    INT          NOT NULL DEFAULT 0,
    creation    DATETIME     NOT NULL,
    endDate     DATETIME     NOT NULL DEFAULT '1900-01-01 00:00:00',
    PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS rooms_equipment
(
    idRoom     INT NOT NULL,
    idProduct  INT NOT NULL,
    quantity   INT NOT NULL,
    PRIMARY KEY (idRoom, idProduct),
    FOREIGN KEY (idRoom) REFERENCES rooms (idRoom),
    FOREIGN KEY (idProduct) REFERENCES products (id)
);

CREATE TABLE IF NOT EXISTS events
(
    idEvent           INT AUTO_INCREMENT,
    type              INT          NOT NULL,
    typePlace         INT          NOT NULL,
    name              VARCHAR(30)  NOT NULL,
    description       VARCHAR(150) NOT NULL,
    maxParticipant    INT          NOT NULL,
    startEvent        DATETIME     NOT NULL,
    endEvent          DATETIME     NOT NULL,
    status INT          NOT NULL,
    idMeeting           CHAR(13)     DEFAULT NULL,
    idPresta          INT          NOT NULL,
    idRoom           INT,
    PRIMARY KEY (idEvent),
    FOREIGN KEY (idPresta) REFERENCES users (idUser),
    FOREIGN KEY (idRoom) REFERENCES rooms (idRoom)
);

CREATE TABLE IF NOT EXISTS cart
(
    idCart INT AUTO_INCREMENT,
    idUser INT,
    PRIMARY KEY (idCart),
    FOREIGN KEY (idUser) REFERENCES users (idUser)
);

CREATE TABLE IF NOT EXISTS cart_item
(
    idCartItem INT AUTO_INCREMENT,
    quantity   INT NOT NULL,
    id         INT NOT NULL,
    idCart     INT NOT NULL,
    PRIMARY KEY (idCartItem),
    FOREIGN KEY (id) REFERENCES products (id),
    FOREIGN KEY (idCart) REFERENCES cart (idCart)
);

CREATE TABLE IF NOT EXISTS review
(
    idReview INT AUTO_INCREMENT,
    message  VARCHAR(150) NOT NULL,
    idUser   INT          NOT NULL,
    idEvent INT          NOT NULL,
    PRIMARY KEY (idReview),
    FOREIGN KEY (idUser) REFERENCES users (idUser),
    FOREIGN KEY (idEvent) REFERENCES events (idEvent)
);

CREATE TABLE IF NOT EXISTS notification
(
    idNotif    INT AUTO_INCREMENT,
    creation   DATETIME NOT NULL,
    status     INT      NOT NULL,
    idSender   INT      NOT NULL,
    idReceiver INT      NOT NULL,
    PRIMARY KEY (idNotif),
    FOREIGN KEY (idSender) REFERENCES users (idUser),
    FOREIGN KEY (idReceiver) REFERENCES users (idUser)
);

CREATE TABLE IF NOT EXISTS stripe_consumer
(
    id                 INT AUTO_INCREMENT,
    idConsumer         VARCHAR(50) NOT NULL,
    creation           DATETIME    NOT NULL,
    idUser             INT         NOT NULL,
    subscriptionId     VARCHAR(50) NOT NULL,
    subscriptionStatus VARCHAR(50) NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (idUser) REFERENCES users (idUser)
);

CREATE TABLE IF NOT EXISTS training_course
(
    idTrainingCourse INT AUTO_INCREMENT,
    name             VARCHAR(30)  NOT NULL,
    description      VARCHAR(150) NOT NULL,
    type             INT          NOT NULL,
    image            VARCHAR(100) NOT NULL,
    idMeeting        CHAR(13)     NOT NULL,
    pathDiploma      VARCHAR(100) NOT NULL,
    start            DATETIME     NOT NULL,
    nbDays           INT          NOT NULL,
    idPresta         INT          NOT NULL,
    PRIMARY KEY (idTrainingCourse)
);

CREATE TABLE IF NOT EXISTS recipe
(
    idRecipe     INT AUTO_INCREMENT,
    recipeName   VARCHAR(50)  NOT NULL,
    recipeImage  VARCHAR(100) NOT NULL,
    description  TEXT         NOT NULL,
    creationDate DATETIME     NOT NULL,
    idUser       INT          NOT NULL,
    PRIMARY KEY (idRecipe),
    FOREIGN KEY (idUser) REFERENCES users (idUser)
);

CREATE TABLE IF NOT EXISTS recipe_ingredients
(
    idIngredient       INT AUTO_INCREMENT,
    ingredientName     VARCHAR(50) NOT NULL,
    ingredientQuantity INT         NOT NULL,
    unit               VARCHAR(50) NOT NULL,
    idRecipe           INT,
    PRIMARY KEY (idIngredient),
    FOREIGN KEY (idRecipe) REFERENCES recipe (idRecipe)
);

CREATE TABLE IF NOT EXISTS recipe_images
(
    id       INT AUTO_INCREMENT,
    imgPath  VARCHAR(100) NOT NULL,
    idRecipe INT,
    PRIMARY KEY (id),
    FOREIGN KEY (idRecipe) REFERENCES recipe (idRecipe)
);

CREATE TABLE IF NOT EXISTS recipe_steps
(
    id              INT AUTO_INCREMENT,
    stepDescription TEXT NOT NULL,
    idRecipe        INT  NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (idRecipe) REFERENCES recipe (idRecipe)
);

CREATE TABLE IF NOT EXISTS register
(
    idUser  INT,
    idEvent INT,
    PRIMARY KEY (idUser, idEvent),
    FOREIGN KEY (idUser) REFERENCES users (idUser),
    FOREIGN KEY (idEvent) REFERENCES events (idEvent)
);

CREATE TABLE IF NOT EXISTS orders
(
    idUser      INT,
    idCart      INT,
    idInvoice   VARCHAR(50)  NOT NULL,
    pathInvoice VARCHAR(100) NOT NULL,
    status      INT          NOT NULL DEFAULT 0,
    PRIMARY KEY (idUser, idCart),
    FOREIGN KEY (idUser) REFERENCES users (idUser),
    FOREIGN KEY (idCart) REFERENCES cart (idCart)
);

CREATE TABLE IF NOT EXISTS apart
(
    idUser           INT,
    idTrainingCourse INT,
    PRIMARY KEY (idUser, idTrainingCourse),
    FOREIGN KEY (idUser) REFERENCES users (idUser),
    FOREIGN KEY (idTrainingCourse) REFERENCES training_course (idTrainingCourse)
);

INSERT INTO products (idProduct, name, description, image, type, price, creation)
VALUES ('prod_NeXRwi2aT28FXA', 'Master', 'Master yearly', '', 1, 220, NOW());

INSERT INTO products (idProduct, name, description, image, type, price, creation)
VALUES ('prod_NeXRHRc6d5UjRq', 'Master', 'Master monthly', '', 1, 19, NOW());

INSERT INTO products (idProduct, name, description, image, type, price, creation)
VALUES ('prod_NeXQV8mjycAaDE', 'Starter', 'Starter yearly', '', 1, 113, NOW());

INSERT INTO products (idProduct, name, description, image, type, price, creation)
VALUES ('prod_NeXPCayzYQqXgS', 'Starter', 'Starter monthly', '', 1, 10, NOW());

DROP TABLE IF EXISTS participate;
DROP TABLE IF EXISTS courses;

ALTER TABLE training_course ADD COLUMN start DATETIME NOT NULL;
ALTER TABLE training_course ADD COLUMN nbDays INT NOT NULL;
ALTER TABLE training_course ADD COLUMN linkMeeting CHAR(33) NOT NULL AFTER image;
ALTER TABLE training_course ADD COLUMN idPresta INT NOT NULL;
ALTER TABLE training_course ADD CONSTRAINT fk_training_course_presta FOREIGN KEY (idPresta) REFERENCES users(idUser);
ALTER TABLE training_course DROP COLUMN type;

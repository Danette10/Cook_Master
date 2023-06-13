CREATE DATABASE IF NOT EXISTS cookorama;
ALTER DATABASE cookorama CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
SET GLOBAL time_zone = 'Europe/Paris';

CREATE TABLE users
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

CREATE TABLE message
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

CREATE TABLE place
(
    idPlace   INT AUTO_INCREMENT,
    address           VARCHAR(50),
    postalCode        CHAR(5),
    city              VARCHAR(40),
    PRIMARY KEY (idPlace)
);

CREATE TABLE events
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
    linkMeeting       CHAR(33)     DEFAULT NULL,
    idPresta          INT          NOT NULL,
    idPlace           INT,
    PRIMARY KEY (idEvent),
    FOREIGN KEY (idPresta) REFERENCES users (idUser),
    FOREIGN KEY (idPlace) REFERENCES place (idPlace)
);

CREATE TABLE products
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

CREATE TABLE courses
(
    idCourse       INT AUTO_INCREMENT,
    name           VARCHAR(30)  NOT NULL,
    description    VARCHAR(150) NOT NULL,
    type           INT          NOT NULL,
    typePlace      INT          NOT NULL,
    image          VARCHAR(100) NOT NULL,
    maxParticipant INT          NOT NULL,
    linkMeeting       CHAR(33)     DEFAULT NULL,
    idPresta       INT          NOT NULL,
    idPlace        INT,
    PRIMARY KEY (idCourse),
    FOREIGN KEY (idPresta) REFERENCES users (idUser),
    FOREIGN KEY (idPlace) REFERENCES place (idPlace)
);

CREATE TABLE cart
(
    idCart INT AUTO_INCREMENT,
    idUser INT,
    PRIMARY KEY (idCart),
    FOREIGN KEY (idUser) REFERENCES users (idUser)
);

CREATE TABLE cart_item
(
    idCartItem INT AUTO_INCREMENT,
    quantity   INT NOT NULL,
    id         INT NOT NULL,
    idCart     INT NOT NULL,
    PRIMARY KEY (idCartItem),
    FOREIGN KEY (id) REFERENCES products (id),
    FOREIGN KEY (idCart) REFERENCES cart (idCart)
);

CREATE TABLE review
(
    idReview INT AUTO_INCREMENT,
    message  VARCHAR(150) NOT NULL,
    idUser   INT          NOT NULL,
    idCourse INT          NOT NULL,
    PRIMARY KEY (idReview),
    FOREIGN KEY (idUser) REFERENCES users (idUser),
    FOREIGN KEY (idCourse) REFERENCES courses (idCourse)
);

CREATE TABLE notification
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

CREATE TABLE stripe_consumer
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

CREATE TABLE training_course
(
    idTrainingCourse INT AUTO_INCREMENT,
    name             VARCHAR(30)  NOT NULL,
    description      VARCHAR(150) NOT NULL,
    type             INT          NOT NULL,
    image            VARCHAR(100) NOT NULL,
    pathDiploma      VARCHAR(100) NOT NULL,
    PRIMARY KEY (idTrainingCourse)
);

CREATE TABLE recipe
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

CREATE TABLE recipe_ingredients
(
    idIngredient       INT AUTO_INCREMENT,
    ingredientName     VARCHAR(50) NOT NULL,
    ingredientQuantity INT         NOT NULL,
    unit               VARCHAR(50) NOT NULL,
    idRecipe           INT,
    PRIMARY KEY (idIngredient),
    FOREIGN KEY (idRecipe) REFERENCES recipe (idRecipe)
);

CREATE TABLE recipe_images
(
    id       INT AUTO_INCREMENT,
    imgPath  VARCHAR(100) NOT NULL,
    idRecipe INT,
    PRIMARY KEY (id),
    FOREIGN KEY (idRecipe) REFERENCES recipe (idRecipe)
);

CREATE TABLE recipe_steps
(
    id              INT AUTO_INCREMENT,
    stepDescription TEXT NOT NULL,
    idRecipe        INT  NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (idRecipe) REFERENCES recipe (idRecipe)
);

CREATE TABLE register
(
    idUser  INT,
    idEvent INT,
    PRIMARY KEY (idUser, idEvent),
    FOREIGN KEY (idUser) REFERENCES users (idUser),
    FOREIGN KEY (idEvent) REFERENCES events (idEvent)
);

CREATE TABLE orders
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

CREATE TABLE participate
(
    idUser   INT,
    idCourse INT,
    PRIMARY KEY (idUser, idCourse),
    FOREIGN KEY (idUser) REFERENCES users (idUser),
    FOREIGN KEY (idCourse) REFERENCES courses (idCourse)
);

CREATE TABLE apart
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
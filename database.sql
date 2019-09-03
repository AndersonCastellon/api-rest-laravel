create DATABASE
if NOT EXISTS api_rest_laravel;
USE api_rest_laravel;

CREATE TABLE users
(
    id               INT(255) auto_increment NOT NULL,
    name             VARCHAR(100) NOT NULL,
    surname          VARCHAR(100) not NULL,
    role             VARCHAR(20),
    email            VARCHAR(255) NOT NULL,
    password         VARCHAR(255) not NULL,
    descripcion      text,
    image            VARCHAR(255),
    created_at       DATETIME DEFAULT NULL,
    updated_at       DATETIME DEFAULT NULL,
    remember_token   VARCHAR(255),
    CONSTRAINT pk_users PRIMARY KEY(id)
) ENGINE = InnoDB;

    CREATE TABLE categories
    (
        id               INT(255) auto_increment NOT NULL,
        name             VARCHAR(100),
        created_at       DATETIME DEFAULT NULL,
        updated_at       DATETIME DEFAULT NULL,
    CONSTRAINT pk_categories PRIMARY KEY(id)
) ENGINE=InnoDB;

CREATE TABLE posts(
    id              INT(255)auto_increment NOT NULL,
    user_id         INT(255) NOT NULL,
    category_id     INT(255) NOT NULL,
    title           VARCHAR(255) NOT NULL,
    content         text,
    image           VARCHAR(255),
    created_at      DATETIME DEFAULT NULL,
    updated_at      DATETIME DEFAULT NULL,
    CONSTRAINT pk_posts PRIMARY KEY(id),
    CONSTRAINT fk_post_user FOREIGN KEY(user_id) REFERENCES users(id),
    CONSTRAINT fk_post_category FOREIGN KEY(category_id) REFERENCES categories(id)
)ENGINE=InnoDB;

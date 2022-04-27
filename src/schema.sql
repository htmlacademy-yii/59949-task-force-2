CREATE DATABASE taskforce
    DEFAULT CHARSET utf8
    DEFAULT COLLATE utf8_general_ci;

USE taskforce;

CREATE TABLE categories
(
    PRIMARY KEY (id),
    id         INT UNSIGNED NOT NULL auto_increment,
    title      VARCHAR(255) NOT NULL,
    code       VARCHAR(100) NOT NULL,
    created_at DATETIME     NOT NULL DEFAULT NOW(),
    updated_at DATETIME     NOT NULL,
    deleted_at DATETIME     NOT NULL
);

CREATE TABLE files
(
    PRIMARY KEY (id),
    id         INT UNSIGNED NOT NULL auto_increment,
    path       VARCHAR(255) NOT NULL,
    created_at DATETIME     NOT NULL DEFAULT NOW(),
    updated_at DATETIME     NOT NULL,
    deleted_at DATETIME     NOT NULL
);

CREATE TABLE cities
(
    PRIMARY KEY (id),
    id          INT UNSIGNED NOT NULL auto_increment,
    name        VARCHAR(100) NOT NULL,
    coordinates POINT,
    created_at  DATETIME     NOT NULL DEFAULT NOW(),
    updated_at  DATETIME     NOT NULL,
    deleted_at  DATETIME     NOT NULL
);

CREATE TABLE users
(
    PRIMARY KEY (id),
    id                 INT UNSIGNED NOT NULL auto_increment,
    email              VARCHAR(128) NOT NULL UNIQUE,
    name               VARCHAR(150) NOT NULL,
    password           CHAR(64)     NOT NULL,
    phone              VARCHAR(20)  NOT NULL,
    telegram           VARCHAR(100) NOT NULL,
    description        VARCHAR(500) NOT NULL,
    birth_date         DATETIME     NULL,
    avatar_file_id     INT UNSIGNED REFERENCES files (id),
    category_id        INT UNSIGNED NOT NULL REFERENCES categories (id),
    city_id            INT UNSIGNED NOT NULL REFERENCES cities (id),
    is_executor        BOOLEAN               DEFAULT FALSE,
    is_busy            BOOLEAN               DEFAULT FALSE,
    is_hidden_contacts BOOLEAN               DEFAULT FALSE,
    created_at         DATETIME     NOT NULL DEFAULT NOW(),
    updated_at         DATETIME     NOT NULL,
    deleted_at         DATETIME     NOT NULL
);

CREATE TABLE tasks
(
    PRIMARY KEY (id),
    id          INT UNSIGNED NOT NULL auto_increment,
    title       VARCHAR(255) NOT NULL,
    budget      INT          NULL,
    status      TINYINT      NOT NULL DEFAULT 1,
    customer_id INT UNSIGNED NOT NULL REFERENCES users (id),
    executor_id INT UNSIGNED NULL REFERENCES users (id),
    category_id INT UNSIGNED NOT NULL REFERENCES categories (id),
    city_id     INT UNSIGNED REFERENCES cities (id),
    coordinates POINT,
    expiry_dt   DATETIME     NULL,
    created_at  DATETIME     NOT NULL DEFAULT NOW(),
    updated_at  DATETIME     NOT NULL,
    deleted_at  DATETIME     NOT NULL
);

CREATE TABLE tasks_files
(
    PRIMARY KEY (id),
    id         INT UNSIGNED NOT NULL auto_increment,
    task_id    INT UNSIGNED NOT NULL REFERENCES tasks (id),
    file_id    INT UNSIGNED NOT NULL REFERENCES files (id),
    created_at DATETIME     NOT NULL DEFAULT NOW(),
    updated_at DATETIME     NOT NULL,
    deleted_at DATETIME     NOT NULL
);

CREATE TABLE responses
(
    PRIMARY KEY (id),
    id          INT UNSIGNED NOT NULL auto_increment,
    comment     VARCHAR(500) NOT NULL,
    price       INT          NOT NULL,
    executor_id INT UNSIGNED NOT NULL REFERENCES users (id),
    task_id     INT UNSIGNED NOT NULL REFERENCES tasks (id),
    is_rejected BOOLEAN               DEFAULT FALSE,
    created_at  DATETIME     NOT NULL DEFAULT NOW(),
    updated_at  DATETIME     NOT NULL,
    deleted_at  DATETIME     NOT NULL
);

CREATE TABLE reviews
(
    PRIMARY KEY (id),
    id          INT UNSIGNED NOT NULL auto_increment,
    comment     VARCHAR(500) NOT NULL,
    rating      TINYINT      NOT NULL,
    customer_id INT UNSIGNED NOT NULL REFERENCES users (id),
    executor_id INT UNSIGNED NOT NULL REFERENCES users (id),
    task_id     INT UNSIGNED NOT NULL REFERENCES tasks (id),
    created_at  DATETIME     NOT NULL DEFAULT NOW(),
    updated_at  DATETIME     NOT NULL,
    deleted_at  DATETIME     NOT NULL
);

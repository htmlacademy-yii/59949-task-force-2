DROP DATABASE IF EXISTS taskforce;

CREATE DATABASE IF NOT EXISTS taskforce
    DEFAULT CHARSET utf8
    DEFAULT COLLATE utf8_general_ci;

USE taskforce;

CREATE TABLE IF NOT EXISTS categories
(
    PRIMARY KEY (id),
    id         INT UNSIGNED NOT NULL auto_increment,
    name      VARCHAR(255) NOT NULL,
    icon       VARCHAR(100) NOT NULL,
    created_at DATETIME     NOT NULL DEFAULT NOW(),
    updated_at DATETIME,
    deleted_at DATETIME
);

CREATE TABLE IF NOT EXISTS files
(
    PRIMARY KEY (id),
    id         INT UNSIGNED NOT NULL auto_increment,
    path       VARCHAR(255) NOT NULL,
    created_at DATETIME     NOT NULL DEFAULT NOW(),
    updated_at DATETIME,
    deleted_at DATETIME
);

CREATE TABLE IF NOT EXISTS cities
(
    PRIMARY KEY (id),
    id          INT UNSIGNED NOT NULL auto_increment,
    name        VARCHAR(100) NOT NULL,
    coordinates POINT,
    created_at  DATETIME     NOT NULL DEFAULT NOW(),
    updated_at  DATETIME,
    deleted_at  DATETIME
);

CREATE TABLE IF NOT EXISTS users
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
    updated_at         DATETIME,
    deleted_at         DATETIME
);

CREATE TABLE IF NOT EXISTS tasks
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
    updated_at  DATETIME,
    deleted_at  DATETIME
);

CREATE TABLE IF NOT EXISTS tasks_files
(
    PRIMARY KEY (id),
    id         INT UNSIGNED NOT NULL auto_increment,
    task_id    INT UNSIGNED NOT NULL REFERENCES tasks (id),
    file_id    INT UNSIGNED NOT NULL REFERENCES files (id),
    created_at DATETIME     NOT NULL DEFAULT NOW(),
    updated_at DATETIME,
    deleted_at DATETIME
);

CREATE TABLE IF NOT EXISTS responses
(
    PRIMARY KEY (id),
    id          INT UNSIGNED NOT NULL auto_increment,
    comment     VARCHAR(500) NOT NULL,
    price       INT          NOT NULL,
    executor_id INT UNSIGNED NOT NULL REFERENCES users (id),
    task_id     INT UNSIGNED NOT NULL REFERENCES tasks (id),
    is_rejected BOOLEAN               DEFAULT FALSE,
    created_at  DATETIME     NOT NULL DEFAULT NOW(),
    updated_at  DATETIME,
    deleted_at  DATETIME
);

CREATE TABLE IF NOT EXISTS reviews
(
    PRIMARY KEY (id),
    id          INT UNSIGNED NOT NULL auto_increment,
    comment     VARCHAR(500) NOT NULL,
    rating      TINYINT      NOT NULL,
    customer_id INT UNSIGNED NOT NULL REFERENCES users (id),
    executor_id INT UNSIGNED NOT NULL REFERENCES users (id),
    task_id     INT UNSIGNED NOT NULL REFERENCES tasks (id),
    created_at  DATETIME     NOT NULL DEFAULT NOW(),
    updated_at  DATETIME,
    deleted_at  DATETIME
);

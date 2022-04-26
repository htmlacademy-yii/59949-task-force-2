CREATE DATABASE taskforce
    DEFAULT CHARSET utf8
    DEFAULT COLLATE utf8_general_ci;

USE taskforce;

CREATE TABLE categories (
    PRIMARY KEY (id),
    id              INT              NOT NULL        auto_increment,
    title           VARCHAR(255)     NOT NULL,
    code            VARCHAR(255)     NOT NULL,
    created_at      DATETIME         NOT NULL        DEFAULT NOW(),
    updated_at      DATETIME         NOT NULL,
    deleted_at      DATETIME         NOT NULL
);

CREATE TABLE statuses (
    PRIMARY KEY (id),
    id              INT              NOT NULL        auto_increment,
    title           VARCHAR(255)     NOT NULL,
    code            VARCHAR(255)     NOT NULL,
    created_at      DATETIME         NOT NULL        DEFAULT NOW(),
    updated_at      DATETIME         NOT NULL,
    deleted_at      DATETIME         NOT NULL
);

CREATE TABLE cities (
    PRIMARY KEY (id),
    id              INT              NOT NULL        auto_increment,
    name            VARCHAR(255)     NOT NULL,
    code            VARCHAR(255)     NOT NULL,
    created_at      DATETIME         NOT NULL        DEFAULT NOW(),
    updated_at      DATETIME         NOT NULL,
    deleted_at      DATETIME         NOT NULL
);

CREATE TABLE roles (
    PRIMARY KEY (id),
    id              INT              NOT NULL        auto_increment,
    type            VARCHAR(255)     NOT NULL,
    created_at      DATETIME         NOT NULL        DEFAULT NOW(),
    updated_at      DATETIME         NOT NULL,
    deleted_at      DATETIME         NOT NULL
);

CREATE TABLE users (
    PRIMARY KEY (id),
    id                      INT                NOT NULL             auto_increment,
    email                   VARCHAR(128)       NOT NULL             UNIQUE,
    name                    VARCHAR(255)       NOT NULL,
    password                VARCHAR(255)       NOT NULL,
    phone                   VARCHAR(255)       NOT NULL,
    telegram                VARCHAR(255)       NOT NULL,
    description             VARCHAR(255)       NOT NULL,
    birth_date              DATETIME                                DEFAULT NULL,
    avatar                  VARCHAR(255)                            DEFAULT NULL,
    token                   VARCHAR(255)                            DEFAULT NULL,
    categorie_id            INT                NOT NULL             REFERENCES  categories(id),
    role_id                 INT                NOT NULL             REFERENCES  roles(id),
    city_id                 INT                NOT NULL             REFERENCES  cities(id),
    is_executor             BOOLEAN                                 DEFAULT FALSE,
    is_busy                 BOOLEAN                                 DEFAULT FALSE,
    is_hidden_contacts      BOOLEAN                                 DEFAULT FALSE,
    created_at              DATETIME           NOT NULL             DEFAULT NOW(),
    updated_at              DATETIME           NOT NULL,
    deleted_at              DATETIME           NOT NULL
);

CREATE TABLE tasks (
    PRIMARY KEY (id),
    id                      INT                NOT NULL             auto_increment,
    title                   VARCHAR(255)       NOT NULL,
    budget                  INT                                     DEFAULT NULL,
    author_id               INT                NOT NULL             REFERENCES  users(id),
    executor_id             INT                                     REFERENCES  users(id),
    categorie_id            INT                NOT NULL             REFERENCES  categories(id),
    status_id               INT                NOT NULL             REFERENCES  statuses(id),
    city_id                 INT                                     REFERENCES  cities(id),
    latitude                VARCHAR(255)                            DEFAULT NULL,
    longitude               VARCHAR(255)                            DEFAULT NULL,
    expiry_dt               DATETIME                                DEFAULT NULL,
    created_at              DATETIME           NOT NULL             DEFAULT NOW(),
    updated_at              DATETIME           NOT NULL,
    deleted_at              DATETIME           NOT NULL
);

CREATE TABLE responses (
    PRIMARY KEY (id),
    id                      INT                NOT NULL             auto_increment,
    comment                 VARCHAR(255)       NOT NULL,
    price                   INT                NOT NULL,
    author_id               INT                NOT NULL             REFERENCES  users(id),
    task_id                 INT                NOT NULL             REFERENCES  tasks(id),
    is_rejected             BOOLEAN                                 DEFAULT FALSE,
    created_at              DATETIME           NOT NULL             DEFAULT NOW(),
    updated_at              DATETIME           NOT NULL,
    deleted_at              DATETIME           NOT NULL
);

CREATE TABLE reviews (
    PRIMARY KEY (id),
    id                      INT                NOT NULL             auto_increment,
    comment                 VARCHAR(255)       NOT NULL,
    rating                  INT                NOT NULL,
    author_id               INT                NOT NULL             REFERENCES  users(id),
    executor_id             INT                NOT NULL             REFERENCES  users(id),
    task_id                 INT                NOT NULL             REFERENCES  tasks(id),
    created_at              DATETIME           NOT NULL             DEFAULT NOW(),
    updated_at              DATETIME           NOT NULL,
    deleted_at              DATETIME           NOT NULL
);

-- Cherry: database schema (MySQL 8.4).
--
-- Fresh Docker volume: applied automatically by the db container
-- (mounted into /docker-entrypoint-initdb.d in compose.yaml).
-- Existing database: see "База данных" in readme.md.

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS categories (
    id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    slug         VARCHAR(255) NOT NULL,
    title        VARCHAR(255) NOT NULL,
    description  TEXT         NOT NULL,
    date_created TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY categories_slug_unique (slug)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS posts (
    id           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    slug         VARCHAR(255)    NOT NULL,
    title        VARCHAR(255)    NOT NULL,
    description  TEXT            NOT NULL,
    content      MEDIUMTEXT      NOT NULL,
    image        VARCHAR(255)    NULL,
    views        INT UNSIGNED    NOT NULL DEFAULT 0,
    published_at DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY posts_slug_unique (slug),
    -- category page: ORDER BY published_at DESC, id DESC / views DESC, id DESC
    KEY posts_published_at_index (published_at, id),
    KEY posts_views_index (views, id)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS posts_categories (
    category_id INT UNSIGNED    NOT NULL,
    post_id     BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (category_id, post_id),
    KEY posts_categories_posts_ID_fk (post_id),
    CONSTRAINT posts_categories_categories_ID_fk
        FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE CASCADE,
    CONSTRAINT posts_categories_posts_ID_fk
        FOREIGN KEY (post_id) REFERENCES posts (id) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

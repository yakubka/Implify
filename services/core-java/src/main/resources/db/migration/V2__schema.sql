-- =============================================================================
-- Implify Crisis Response — доменная схема
-- =============================================================================

-- Пользователи -----------------------------------------------------------------
CREATE TABLE users (
    id            BIGSERIAL PRIMARY KEY,
    username      VARCHAR(50)  NOT NULL UNIQUE,
    email         VARCHAR(120) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    bio           TEXT,
    avatar_url    VARCHAR(512),
    home_lat      DOUBLE PRECISION,
    home_lon      DOUBLE PRECISION,
    enabled       BOOLEAN      NOT NULL DEFAULT TRUE,
    last_login_at TIMESTAMPTZ,
    created_at    TIMESTAMPTZ  NOT NULL DEFAULT now(),
    updated_at    TIMESTAMPTZ  NOT NULL DEFAULT now()
);

CREATE TABLE user_roles (
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    role    VARCHAR(40) NOT NULL,
    PRIMARY KEY (user_id, role)
);

CREATE TABLE user_skills (
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    skill   VARCHAR(60) NOT NULL,
    PRIMARY KEY (user_id, skill)
);

-- Зоны бедствия ----------------------------------------------------------------
CREATE TABLE zones (
    id            BIGSERIAL PRIMARY KEY,
    name          VARCHAR(120) NOT NULL,
    description   TEXT,
    center_lat    DOUBLE PRECISION NOT NULL,
    center_lon    DOUBLE PRECISION NOT NULL,
    radius_m      INTEGER NOT NULL DEFAULT 1000,
    polygon_geojson TEXT,
    created_at    TIMESTAMPTZ NOT NULL DEFAULT now()
);

-- Запросы помощи ---------------------------------------------------------------
CREATE TABLE help_requests (
    id          BIGSERIAL PRIMARY KEY,
    title       VARCHAR(160) NOT NULL,
    description TEXT NOT NULL,
    status      VARCHAR(20)  NOT NULL DEFAULT 'OPEN',
    urgency     VARCHAR(20)  NOT NULL DEFAULT 'NORMAL',
    lat         DOUBLE PRECISION NOT NULL,
    lon         DOUBLE PRECISION NOT NULL,
    capacity    INTEGER NOT NULL DEFAULT 1,
    filled      INTEGER NOT NULL DEFAULT 0,
    zone_id     BIGINT REFERENCES zones(id) ON DELETE SET NULL,
    created_by  BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    created_at  TIMESTAMPTZ NOT NULL DEFAULT now(),
    updated_at  TIMESTAMPTZ NOT NULL DEFAULT now()
);
CREATE INDEX idx_help_requests_status ON help_requests(status);
CREATE INDEX idx_help_requests_geo ON help_requests(lat, lon);

CREATE TABLE request_skills (
    request_id BIGINT NOT NULL REFERENCES help_requests(id) ON DELETE CASCADE,
    skill      VARCHAR(60) NOT NULL,
    PRIMARY KEY (request_id, skill)
);

CREATE TABLE request_resources (
    id         BIGSERIAL PRIMARY KEY,
    request_id BIGINT NOT NULL REFERENCES help_requests(id) ON DELETE CASCADE,
    resource   VARCHAR(80) NOT NULL,
    quantity   INTEGER NOT NULL DEFAULT 1
);

-- Отклики волонтёров -----------------------------------------------------------
CREATE TABLE responses (
    id         BIGSERIAL PRIMARY KEY,
    request_id BIGINT NOT NULL REFERENCES help_requests(id) ON DELETE CASCADE,
    user_id    BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    status     VARCHAR(20) NOT NULL DEFAULT 'PENDING',
    message    TEXT,
    created_at TIMESTAMPTZ NOT NULL DEFAULT now(),
    UNIQUE (request_id, user_id)
);

-- Эмбеддинги (заполняются Python-сервисом матчинга) ----------------------------
CREATE TABLE request_embeddings (
    request_id BIGINT PRIMARY KEY REFERENCES help_requests(id) ON DELETE CASCADE,
    embedding  vector(384),
    updated_at TIMESTAMPTZ NOT NULL DEFAULT now()
);

CREATE TABLE user_embeddings (
    user_id    BIGINT PRIMARY KEY REFERENCES users(id) ON DELETE CASCADE,
    embedding  vector(384),
    updated_at TIMESTAMPTZ NOT NULL DEFAULT now()
);

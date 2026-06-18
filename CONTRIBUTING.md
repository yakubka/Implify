# Contributing to Implify

Thanks for your interest in improving Implify! This guide keeps contributions smooth.

## Getting started

1. Fork and clone the repository.
2. Bring up the stack: `docker compose --profile app up --build`
   (or infra only: `docker compose up postgres redis redpanda`).
3. Create a feature branch: `git checkout -b feat/<short-name>`.

## Project layout

| Path | Stack | Notes |
|---|---|---|
| `services/core-java` | Spring Boot 3 / Java 17 | Auth, domain, realtime, Kafka |
| `services/matching-python` | FastAPI | ML matching (pgvector) |
| `services/geo-rust` | Rust / axum | Geospatial compute |
| `packages/geo-wasm` | Rust → WASM | In-browser spatial index |
| `web` | React + Vite + TS | SPA |

## Building & testing

- **Java:** `cd services/core-java && mvn test`
  (or via Docker: `docker run --rm -v "$PWD":/app -v implify-m2:/root/.m2 -w /app maven:3.9-eclipse-temurin-17 mvn -B test`).

## Commit style

We use [Conventional Commits](https://www.conventionalcommits.org/):
`feat:`, `fix:`, `chore:`, `build:`, `docs:`, `test:`, `refactor:`.
Keep commits focused and write meaningful messages.

## Pull requests

- Keep PRs small and scoped to one concern.
- Make sure the build and tests pass.
- Describe **what** changed and **why**.

## Code of conduct

Be respectful and constructive. We're building tools meant to help people in crisis —
let's keep the community in that spirit.

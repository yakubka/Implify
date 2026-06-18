<div align="center">

<picture>
  <source media="(prefers-color-scheme: dark)" srcset="docs/assets/logo-dark.svg">
  <img src="docs/assets/logo.svg" alt="Implify" width="420">
</picture>

### _When every minute counts, coordination saves lives_

An open, real-time **crisis-response coordination platform**. Help requests appear on a live
map; an ML matching engine pairs each one with the nearest, best-skilled volunteers and the
resources they need — from first alert to a fulfilled request, in real time.

<br/>

[![Quick Start](https://img.shields.io/badge/🚀_QUICK_START-16243f?style=for-the-badge)](#-quick-start)
[![API Docs](https://img.shields.io/badge/📡_API_DOCS-ee5a24?style=for-the-badge)](#-api-documentation)
[![Architecture](https://img.shields.io/badge/🏗_ARCHITECTURE-4b5d78?style=for-the-badge)](#-architecture)
[![Roadmap](https://img.shields.io/badge/🗺_ROADMAP-2f3e57?style=for-the-badge)](#-roadmap)

<br/>

![License](https://img.shields.io/badge/license-MIT-green?style=for-the-badge)
![Status](https://img.shields.io/badge/status-active_rebuild-orange?style=for-the-badge)
![Java](https://img.shields.io/badge/Java-17-007396?style=for-the-badge&logo=openjdk&logoColor=white)
![Spring Boot](https://img.shields.io/badge/Spring_Boot-3.4-6DB33F?style=for-the-badge&logo=springboot&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-16-4169E1?style=for-the-badge&logo=postgresql&logoColor=white)
![pgvector](https://img.shields.io/badge/pgvector-embeddings-4169E1?style=for-the-badge)
![Kafka](https://img.shields.io/badge/Apache_Kafka-events-231F20?style=for-the-badge&logo=apachekafka&logoColor=white)
![Redis](https://img.shields.io/badge/Redis-cache-DC382D?style=for-the-badge&logo=redis&logoColor=white)
![WebSocket](https://img.shields.io/badge/WebSocket-realtime-010101?style=for-the-badge&logo=socketdotio&logoColor=white)
![Python](https://img.shields.io/badge/Python-matching-3776AB?style=for-the-badge&logo=python&logoColor=white)
![Rust](https://img.shields.io/badge/Rust-geo_+_WASM-000000?style=for-the-badge&logo=rust&logoColor=white)
![React](https://img.shields.io/badge/React-SPA-61DAFB?style=for-the-badge&logo=react&logoColor=black)
![Docker](https://img.shields.io/badge/Docker-compose-2496ED?style=for-the-badge&logo=docker&logoColor=white)

</div>

---

## 📖 Overview

Implify connects people who urgently need help during emergencies — floods, blackouts,
evacuations — with volunteers and resources nearby.

- **Coordinators** create *help requests* on a map, specifying required skills, resources and
  how many people are needed.
- **Volunteers** see open requests in real time, respond, and get matched.
- **The platform** ranks who fits best using semantic ML matching, and broadcasts live updates
  (filled slots, status changes) over WebSocket.

It reuses a simple, battle-tested domain — `users → help requests → responses` — on a modern,
polyglot stack where **each language earns its place**:

| Language | Role | Why |
|---|---|---|
| **Java** (Spring Boot) | Core domain, auth, realtime, event bus | Reliable transactional backbone |
| **Python** (FastAPI) | ML matching & recommendations | Embeddings + `pgvector` similarity |
| **Rust** (axum + WASM) | Geospatial compute | Fast proximity/ETA; in-browser marker clustering |
| **React** (Vite/TS) | Map-based SPA | Live UI on top of the API |

---

## 🏗 Architecture

```
React SPA (Vite/TS, MapLibre)  ──HTTP/REST + WebSocket──┐
   └─ loads Rust→WASM (clustering / geofencing)          │
                                                         ▼
                       Java Core (Spring Boot 3, Java 17)
                       • JWT auth + roles  • domain CRUD
                       • WebSocket (STOMP) • Kafka producer
            ┌───────────────┬───────────────┬───────────────┐
            ▼               ▼               ▼               ▼
   PostgreSQL 16        Python FastAPI    Rust geo-svc     Kafka (Redpanda)
   + pgvector           (embeddings,      (axum: proximity, → audit / notify
   (system of record)    matching)         ETA, isochrones)    consumers
            ▲
            └── Redis (cache)      Prometheus + Grafana (observability)
```

---

## ✨ Features

- 🔐 **JWT authentication** with roles (`VOLUNTEER`, `COORDINATOR`, `ADMIN`)
- 🗺 **Geo help-requests** with skills, resources, capacity and urgency
- 🙋 **Volunteer responses** with accept flow and live slot counting
- ⚡ **Real-time updates** via STOMP-over-WebSocket
- 🔁 **Event-driven** pipeline on Kafka (`request.events`, `response.events`)
- 🧠 **ML matching** on `pgvector` embeddings *(in progress)*
- 📍 **Rust geospatial** service + in-browser WASM *(planned)*
- 📊 **Observability** via Actuator → Prometheus → Grafana

---

## 🚀 Quick Start

**Prerequisites:** Docker + Docker Compose.

```bash
git clone https://github.com/yakubka/Implify.git
cd Implify
cp .env.example .env          # adjust secrets if you like

# bring up the full stack (infra + app services)
docker compose --profile app up --build
```

| Service | URL |
|---|---|
| Core API (Java) | http://localhost:8080 |
| Health | http://localhost:8080/actuator/health |
| Prometheus | http://localhost:9090 |
| Grafana | http://localhost:3001 |
| Web SPA | http://localhost:5173 *(when built)* |

> Infra only: `docker compose up postgres redis redpanda`.
> Demo data (users/zones/requests) is seeded automatically; all demo users share the
> password `password123` (e.g. login `coordinator`).

---

## 📡 API Documentation

**Base URL:** `http://localhost:8080`
**Auth:** send `Authorization: Bearer <token>` (obtain a token from `register`/`login`).
All bodies and responses are JSON.

### 🔐 Auth

| Method | Endpoint | Auth | Body | Description |
|---|---|---|---|---|
| `POST` | `/api/auth/register` | public | `{ username, email, password }` | Create account, returns JWT |
| `POST` | `/api/auth/login` | public | `{ username, password }` | Log in, returns JWT |

**Response (`AuthResponse`):**
```json
{ "token": "eyJhbGci…", "tokenType": "Bearer", "userId": 1,
  "username": "coordinator", "roles": ["ROLE_COORDINATOR", "ROLE_VOLUNTEER"] }
```

### 🆘 Help Requests

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET`  | `/api/requests?status=&zoneId=` | public | List requests (optional filters) |
| `GET`  | `/api/requests/{id}` | public | Get one request |
| `POST` | `/api/requests` | required | Create a help request |
| `POST` | `/api/requests/{id}/responses` | required | Volunteer responds to a request |
| `GET`  | `/api/requests/{id}/responses` | required | List responses for a request |
| `POST` | `/api/requests/{id}/cancel` | owner | Cancel a request |

**Create body (`CreateRequestDto`):**
```json
{
  "title": "Boats needed for evacuation",
  "description": "Ground floors flooded",
  "urgency": "CRITICAL",
  "lat": 50.4510, "lon": 30.5240,
  "capacity": 5,
  "zoneId": 1,
  "skills": ["driving", "swimming"],
  "resources": [{ "resource": "Boat", "quantity": 3 }]
}
```

### 🤝 Responses

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `POST` | `/api/responses/{id}/accept` | owner / coordinator | Accept a response → fills a slot, updates status |

### 📍 Zones

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET`  | `/api/zones` | public | List disaster zones |
| `GET`  | `/api/zones/{id}` | public | Get one zone |
| `POST` | `/api/zones` | required | Create a zone (center + radius / GeoJSON) |

### 👤 Users

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| `GET` | `/api/users/me` | required | Current user profile |
| `PUT` | `/api/users/me` | required | Update `{ bio, skills, homeLat, homeLon }` |

### 🔭 Observability

| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/actuator/health` | Liveness / readiness |
| `GET` | `/actuator/prometheus` | Prometheus metrics |

### ⚡ Realtime (WebSocket / STOMP)

- **Endpoint:** `/ws` (SockJS)
- **Subscribe `/topic/requests`** → `RequestCreatedEvent`, `RequestUpdatedEvent`
- **Subscribe `/topic/requests/{id}`** → `RequestUpdatedEvent`, `ResponseChangedEvent`

Domain events are also published to **Kafka** topics `request.events` and `response.events`.

### Enums

| Type | Values |
|---|---|
| `RequestStatus` | `OPEN`, `IN_PROGRESS`, `FULFILLED`, `CANCELLED` |
| `Urgency` | `LOW`, `NORMAL`, `HIGH`, `CRITICAL` |
| `ResponseStatus` | `PENDING`, `ACCEPTED`, `DECLINED`, `WITHDRAWN` |
| `Role` | `ROLE_VOLUNTEER`, `ROLE_COORDINATOR`, `ROLE_ADMIN` |

### Example: end-to-end with `curl`

```bash
# 1) log in (seeded coordinator)
TOKEN=$(curl -s -X POST localhost:8080/api/auth/login \
  -H 'Content-Type: application/json' \
  -d '{"username":"coordinator","password":"password123"}' | jq -r .token)

# 2) create a help request
curl -s -X POST localhost:8080/api/requests \
  -H "Authorization: Bearer $TOKEN" -H 'Content-Type: application/json' \
  -d '{"title":"Generators for hospital","description":"Power outage",
       "urgency":"CRITICAL","lat":50.45,"lon":30.52,"capacity":2,
       "skills":["electrician"]}'
```

---

## 🗂 Project Structure

```
services/
  core-java/        # Spring Boot: auth, domain, realtime, Kafka  ✅
  matching-python/  # FastAPI: embeddings + pgvector matching     🚧
  geo-rust/         # axum: proximity / ETA / isochrones          ⏳
packages/
  geo-wasm/         # Rust→WASM: spatial index, clustering         ⏳
web/                # React + Vite + TS SPA                        ⏳
infra/              # Prometheus, Grafana, DB assets
legacy/             # archived original PHP + Mongo prototype
docker-compose.yml  # full stack
```

---

## 🗺 Roadmap

- [x] **Phase 0** — Infrastructure (Docker Compose, Postgres+pgvector, Kafka, Redis)
- [x] **Phase 1** — Java core: JWT auth + crisis-response domain
- [x] **Phase 2** — Realtime WebSocket + Kafka event pipeline
- [ ] **Phase 3** — Python ML matching (FastAPI + pgvector)
- [ ] **Phase 4** — Rust geo service (axum) + in-browser WASM
- [ ] **Phase 5** — React SPA with live map
- [ ] **Phase 6** — Observability dashboards + CI

---

## 🤝 Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md). Issues and pull requests are welcome.

## 🔒 Security

Please report vulnerabilities responsibly — see [SECURITY.md](SECURITY.md).

## 📄 License

Released under the [MIT License](LICENSE).

# Security Policy

## Reporting a vulnerability

If you discover a security vulnerability in Implify, please report it **privately**:

- Open a [GitHub Security Advisory](https://github.com/yakubka/Implify/security/advisories/new), or
- Contact the maintainer directly rather than opening a public issue.

Please include steps to reproduce, affected components, and potential impact. We aim to
acknowledge reports within a few days.

## Scope & good practices

This project is an educational, in-development platform. When deploying:

- **Never** commit real secrets. Use `.env` (git-ignored) and rotate the `JWT_SECRET`.
- Do not reuse the bundled demo credentials in any non-local environment.
- Run PostgreSQL, Redis and Kafka behind a private network, not exposed publicly.
- The `legacy/` directory contains an archived prototype and is **not** maintained or secured —
  do not deploy it.

## Supported versions

The `main` / active rebuild branch receives fixes. Legacy prototype code is unsupported.

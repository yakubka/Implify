package com.implify.core.security;

/** Аутентифицированный пользователь, извлечённый из JWT (без обращения к БД). */
public record AuthPrincipal(Long userId, String username) {
}

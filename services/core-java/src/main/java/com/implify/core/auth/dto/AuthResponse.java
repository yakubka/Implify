package com.implify.core.auth.dto;

import java.util.List;

public record AuthResponse(
        String token,
        String tokenType,
        Long userId,
        String username,
        List<String> roles
) {
    public static AuthResponse bearer(String token, Long userId, String username, List<String> roles) {
        return new AuthResponse(token, "Bearer", userId, username, roles);
    }
}

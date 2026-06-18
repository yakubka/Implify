package com.implify.core.security;

import io.jsonwebtoken.Claims;
import org.junit.jupiter.api.Test;

import java.util.List;

import static org.assertj.core.api.Assertions.assertThat;

class JwtServiceTest {

    private final JwtService jwt =
            new JwtService("0123456789012345678901234567890123456789", 3_600_000L);

    @Test
    void generatesAndParsesToken() {
        String token = jwt.generateToken(42L, "alice", List.of("ROLE_VOLUNTEER"));
        Claims claims = jwt.parse(token);

        assertThat(claims.getSubject()).isEqualTo("42");
        assertThat(claims.get("username", String.class)).isEqualTo("alice");
        assertThat(claims.get("roles", List.class)).containsExactly("ROLE_VOLUNTEER");
        assertThat(claims.getExpiration()).isAfter(claims.getIssuedAt());
    }
}

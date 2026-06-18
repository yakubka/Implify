package com.implify.core.auth;

import com.implify.core.auth.dto.AuthResponse;
import com.implify.core.auth.dto.LoginRequest;
import com.implify.core.auth.dto.RegisterRequest;
import com.implify.core.domain.Role;
import com.implify.core.domain.User;
import com.implify.core.repo.UserRepository;
import com.implify.core.security.JwtService;
import org.junit.jupiter.api.Test;
import org.junit.jupiter.api.extension.ExtendWith;
import org.mockito.InjectMocks;
import org.mockito.Mock;
import org.mockito.junit.jupiter.MockitoExtension;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.web.server.ResponseStatusException;

import java.util.List;
import java.util.Optional;
import java.util.Set;

import static org.assertj.core.api.Assertions.assertThat;
import static org.assertj.core.api.Assertions.assertThatThrownBy;
import static org.mockito.ArgumentMatchers.any;
import static org.mockito.ArgumentMatchers.anyString;
import static org.mockito.Mockito.*;

@ExtendWith(MockitoExtension.class)
class AuthServiceTest {

    @Mock UserRepository users;
    @Mock PasswordEncoder encoder;
    @Mock JwtService jwt;
    @InjectMocks AuthService authService;

    @Test
    void registerCreatesUserAndReturnsToken() {
        when(users.existsByUsername("alice")).thenReturn(false);
        when(users.existsByEmail("alice@example.test")).thenReturn(false);
        when(encoder.encode("secret123")).thenReturn("hashed");
        when(users.save(any(User.class))).thenAnswer(inv -> {
            User u = inv.getArgument(0);
            u.setId(1L);
            return u;
        });
        when(jwt.generateToken(eq(1L), eq("alice"), anyList())).thenReturn("tok");

        AuthResponse res = authService.register(
                new RegisterRequest("alice", "alice@example.test", "secret123"));

        assertThat(res.token()).isEqualTo("tok");
        assertThat(res.username()).isEqualTo("alice");
        assertThat(res.roles()).containsExactly(Role.ROLE_VOLUNTEER.name());
        verify(users).save(any(User.class));
    }

    @Test
    void registerRejectsDuplicateUsername() {
        when(users.existsByUsername("alice")).thenReturn(true);
        assertThatThrownBy(() -> authService.register(
                new RegisterRequest("alice", "a@example.test", "secret123")))
                .isInstanceOf(ResponseStatusException.class);
        verify(users, never()).save(any());
    }

    @Test
    void loginRejectsBadPassword() {
        User u = new User();
        u.setId(5L);
        u.setUsername("bob");
        u.setPasswordHash("hashed");
        u.setRoles(Set.of(Role.ROLE_VOLUNTEER));
        when(users.findByUsername("bob")).thenReturn(Optional.of(u));
        when(encoder.matches("wrong", "hashed")).thenReturn(false);

        assertThatThrownBy(() -> authService.login(new LoginRequest("bob", "wrong")))
                .isInstanceOf(ResponseStatusException.class);
    }

    @Test
    void loginSucceedsWithValidCredentials() {
        User u = new User();
        u.setId(5L);
        u.setUsername("bob");
        u.setPasswordHash("hashed");
        u.setRoles(Set.of(Role.ROLE_VOLUNTEER));
        when(users.findByUsername("bob")).thenReturn(Optional.of(u));
        when(encoder.matches("right", "hashed")).thenReturn(true);
        when(jwt.generateToken(eq(5L), eq("bob"), anyList())).thenReturn("tok");

        AuthResponse res = authService.login(new LoginRequest("bob", "right"));

        assertThat(res.token()).isEqualTo("tok");
        verify(users).save(u); // lastLoginAt updated
    }
}

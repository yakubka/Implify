package com.implify.core.auth;

import com.implify.core.auth.dto.AuthResponse;
import com.implify.core.auth.dto.LoginRequest;
import com.implify.core.auth.dto.RegisterRequest;
import com.implify.core.domain.Role;
import com.implify.core.domain.User;
import com.implify.core.repo.UserRepository;
import com.implify.core.security.JwtService;
import org.springframework.http.HttpStatus;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;
import org.springframework.web.server.ResponseStatusException;

import java.time.OffsetDateTime;
import java.util.List;
import java.util.Set;

@Service
public class AuthService {

    private final UserRepository users;
    private final PasswordEncoder encoder;
    private final JwtService jwt;

    public AuthService(UserRepository users, PasswordEncoder encoder, JwtService jwt) {
        this.users = users;
        this.encoder = encoder;
        this.jwt = jwt;
    }

    @Transactional
    public AuthResponse register(RegisterRequest req) {
        if (users.existsByUsername(req.username())) {
            throw new ResponseStatusException(HttpStatus.CONFLICT, "Username already exists");
        }
        if (users.existsByEmail(req.email())) {
            throw new ResponseStatusException(HttpStatus.CONFLICT, "Email already exists");
        }
        User user = new User();
        user.setUsername(req.username());
        user.setEmail(req.email());
        user.setPasswordHash(encoder.encode(req.password()));
        user.setRoles(Set.of(Role.ROLE_VOLUNTEER));
        users.save(user);
        return toAuth(user);
    }

    @Transactional
    public AuthResponse login(LoginRequest req) {
        User user = users.findByUsername(req.username())
                .orElseThrow(() -> new ResponseStatusException(HttpStatus.UNAUTHORIZED, "Invalid credentials"));
        if (!user.isEnabled() || !encoder.matches(req.password(), user.getPasswordHash())) {
            throw new ResponseStatusException(HttpStatus.UNAUTHORIZED, "Invalid credentials");
        }
        user.setLastLoginAt(OffsetDateTime.now());
        users.save(user);
        return toAuth(user);
    }

    private AuthResponse toAuth(User user) {
        List<String> roles = user.getRoles().stream().map(Enum::name).toList();
        String token = jwt.generateToken(user.getId(), user.getUsername(), roles);
        return AuthResponse.bearer(token, user.getId(), user.getUsername(), roles);
    }
}

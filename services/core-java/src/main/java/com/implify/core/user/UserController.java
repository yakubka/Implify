package com.implify.core.user;

import com.implify.core.domain.User;
import com.implify.core.repo.UserRepository;
import com.implify.core.security.AuthPrincipal;
import org.springframework.http.HttpStatus;
import org.springframework.security.core.annotation.AuthenticationPrincipal;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.server.ResponseStatusException;

import java.util.Set;

@RestController
@RequestMapping("/api/users")
public class UserController {

    private final UserRepository users;

    public UserController(UserRepository users) {
        this.users = users;
    }

    public record MeDto(Long id, String username, String email, String bio,
                        Set<String> skills, Set<String> roles) {
        static MeDto from(User u) {
            return new MeDto(u.getId(), u.getUsername(), u.getEmail(), u.getBio(),
                    u.getSkills(),
                    u.getRoles().stream().map(Enum::name).collect(java.util.stream.Collectors.toSet()));
        }
    }

    public record UpdateMeDto(String bio, Set<String> skills, Double homeLat, Double homeLon) {}

    @GetMapping("/me")
    public MeDto me(@AuthenticationPrincipal AuthPrincipal principal) {
        return MeDto.from(load(principal.userId()));
    }

    @PutMapping("/me")
    public MeDto updateMe(@AuthenticationPrincipal AuthPrincipal principal,
                          @RequestBody UpdateMeDto dto) {
        User u = load(principal.userId());
        if (dto.bio() != null) u.setBio(dto.bio());
        if (dto.skills() != null) u.setSkills(dto.skills());
        if (dto.homeLat() != null) u.setHomeLat(dto.homeLat());
        if (dto.homeLon() != null) u.setHomeLon(dto.homeLon());
        return MeDto.from(users.save(u));
    }

    private User load(Long id) {
        return users.findById(id)
                .orElseThrow(() -> new ResponseStatusException(HttpStatus.NOT_FOUND, "User not found"));
    }
}

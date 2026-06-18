package com.implify.core.bootstrap;

import com.implify.core.domain.*;
import com.implify.core.repo.HelpRequestRepository;
import com.implify.core.repo.UserRepository;
import com.implify.core.repo.ZoneRepository;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.boot.CommandLineRunner;
import org.springframework.boot.autoconfigure.condition.ConditionalOnProperty;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Component;

import java.util.List;
import java.util.Set;

/**
 * Заполняет БД демонстрационными данными для локальной разработки.
 * НЕ переносит реальные email/хэши из старого дампа — только синтетические данные.
 * Отключается через app.seed-demo=false.
 */
@Component
@ConditionalOnProperty(name = "app.seed-demo", havingValue = "true", matchIfMissing = true)
public class DemoDataSeeder implements CommandLineRunner {

    private static final Logger log = LoggerFactory.getLogger(DemoDataSeeder.class);
    private static final String DEMO_PASSWORD = "password123";

    private final UserRepository users;
    private final ZoneRepository zones;
    private final HelpRequestRepository requests;
    private final PasswordEncoder encoder;

    public DemoDataSeeder(UserRepository users, ZoneRepository zones,
                          HelpRequestRepository requests, PasswordEncoder encoder) {
        this.users = users;
        this.zones = zones;
        this.requests = requests;
        this.encoder = encoder;
    }

    @Override
    public void run(String... args) {
        if (users.count() > 0) {
            return;
        }
        log.info("Seeding demo data (password for all demo users: '{}')", DEMO_PASSWORD);

        User coordinator = user("coordinator", "coordinator@example.test",
                Set.of(Role.ROLE_COORDINATOR, Role.ROLE_VOLUNTEER), Set.of("logistics", "first-aid"));
        User medic = user("medic_anna", "anna@example.test",
                Set.of(Role.ROLE_VOLUNTEER), Set.of("first-aid", "driving"));
        User driver = user("driver_ivan", "ivan@example.test",
                Set.of(Role.ROLE_VOLUNTEER), Set.of("driving", "heavy-lifting"));
        users.saveAll(List.of(coordinator, medic, driver));

        Zone flood = new Zone();
        flood.setName("Riverside flood zone");
        flood.setDescription("Подтопление прибрежных кварталов");
        flood.setCenterLat(50.4501);
        flood.setCenterLon(30.5234);
        flood.setRadiusM(3000);
        zones.save(flood);

        requests.save(helpRequest(coordinator.getId(), flood,
                "Нужны лодки для эвакуации", "Затоплены первые этажи, нужны гребные/моторные лодки",
                Urgency.CRITICAL, 50.4510, 30.5240, 5, Set.of("driving", "swimming"),
                resource("Лодка", 3), resource("Спасательный жилет", 20)));

        requests.save(helpRequest(coordinator.getId(), flood,
                "Раздача питьевой воды", "Пункт раздачи воды у школы №3",
                Urgency.HIGH, 50.4495, 30.5210, 10, Set.of("logistics"),
                resource("Вода 5л", 200)));

        log.info("Demo data seeded: {} users, {} zones, {} requests",
                users.count(), zones.count(), requests.count());
    }

    private User user(String username, String email, Set<Role> roles, Set<String> skills) {
        User u = new User();
        u.setUsername(username);
        u.setEmail(email);
        u.setPasswordHash(encoder.encode(DEMO_PASSWORD));
        u.setRoles(roles);
        u.setSkills(skills);
        return u;
    }

    private HelpRequest helpRequest(Long authorId, Zone zone, String title, String desc,
                                    Urgency urgency, double lat, double lon, int capacity,
                                    Set<String> skills, RequestResource... resources) {
        HelpRequest r = new HelpRequest();
        r.setCreatedBy(authorId);
        r.setZone(zone);
        r.setTitle(title);
        r.setDescription(desc);
        r.setUrgency(urgency);
        r.setLat(lat);
        r.setLon(lon);
        r.setCapacity(capacity);
        r.getSkills().addAll(skills);
        for (RequestResource res : resources) r.addResource(res);
        return r;
    }

    private RequestResource resource(String name, int qty) {
        RequestResource rr = new RequestResource();
        rr.setResource(name);
        rr.setQuantity(qty);
        return rr;
    }
}

package com.implify.core.zone;

import com.implify.core.domain.Zone;
import com.implify.core.repo.ZoneRepository;
import com.implify.core.zone.dto.CreateZoneDto;
import com.implify.core.zone.dto.ZoneDto;
import jakarta.validation.Valid;
import org.springframework.http.HttpStatus;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.server.ResponseStatusException;

import java.util.List;

@RestController
@RequestMapping("/api/zones")
public class ZoneController {

    private final ZoneRepository zones;

    public ZoneController(ZoneRepository zones) {
        this.zones = zones;
    }

    @GetMapping
    public List<ZoneDto> list() {
        return zones.findAll().stream().map(ZoneDto::from).toList();
    }

    @GetMapping("/{id}")
    public ZoneDto get(@PathVariable Long id) {
        return zones.findById(id).map(ZoneDto::from)
                .orElseThrow(() -> new ResponseStatusException(HttpStatus.NOT_FOUND, "Zone not found"));
    }

    @PostMapping
    @ResponseStatus(HttpStatus.CREATED)
    public ZoneDto create(@Valid @RequestBody CreateZoneDto dto) {
        Zone z = new Zone();
        z.setName(dto.name());
        z.setDescription(dto.description());
        z.setCenterLat(dto.centerLat());
        z.setCenterLon(dto.centerLon());
        z.setRadiusM(dto.radiusM() > 0 ? dto.radiusM() : 1000);
        z.setPolygonGeoJson(dto.polygonGeoJson());
        return ZoneDto.from(zones.save(z));
    }
}

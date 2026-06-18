package com.implify.core.zone.dto;

import com.implify.core.domain.Zone;

public record ZoneDto(
        Long id,
        String name,
        String description,
        double centerLat,
        double centerLon,
        int radiusM,
        String polygonGeoJson
) {
    public static ZoneDto from(Zone z) {
        return new ZoneDto(z.getId(), z.getName(), z.getDescription(),
                z.getCenterLat(), z.getCenterLon(), z.getRadiusM(), z.getPolygonGeoJson());
    }
}

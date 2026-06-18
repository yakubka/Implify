package com.implify.core.zone.dto;

import jakarta.validation.constraints.*;

public record CreateZoneDto(
        @NotBlank @Size(max = 120) String name,
        String description,
        @NotNull @DecimalMin("-90") @DecimalMax("90") Double centerLat,
        @NotNull @DecimalMin("-180") @DecimalMax("180") Double centerLon,
        @Min(1) int radiusM,
        String polygonGeoJson
) {
}

package com.implify.core.request.dto;

import com.implify.core.domain.Urgency;
import jakarta.validation.Valid;
import jakarta.validation.constraints.*;

import java.util.List;
import java.util.Set;

public record CreateRequestDto(
        @NotBlank @Size(max = 160) String title,
        @NotBlank String description,
        Urgency urgency,
        @NotNull @DecimalMin("-90") @DecimalMax("90") Double lat,
        @NotNull @DecimalMin("-180") @DecimalMax("180") Double lon,
        @Min(1) int capacity,
        Long zoneId,
        Set<String> skills,
        @Valid List<ResourceDto> resources
) {
}

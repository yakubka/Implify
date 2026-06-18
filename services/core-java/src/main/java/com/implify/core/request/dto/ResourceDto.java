package com.implify.core.request.dto;

import jakarta.validation.constraints.Min;
import jakarta.validation.constraints.NotBlank;

public record ResourceDto(
        @NotBlank String resource,
        @Min(1) int quantity
) {
}

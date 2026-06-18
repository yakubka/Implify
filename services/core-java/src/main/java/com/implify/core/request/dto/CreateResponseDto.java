package com.implify.core.request.dto;

import jakarta.validation.constraints.Size;

public record CreateResponseDto(
        @Size(max = 1000) String message
) {
}

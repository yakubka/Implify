package com.implify.core.request.dto;

import com.implify.core.domain.ResponseStatus;
import com.implify.core.domain.VolunteerResponse;

import java.time.OffsetDateTime;

public record ResponseDto(
        Long id,
        Long requestId,
        Long userId,
        ResponseStatus status,
        String message,
        OffsetDateTime createdAt
) {
    public static ResponseDto from(VolunteerResponse r) {
        return new ResponseDto(r.getId(), r.getRequestId(), r.getUserId(),
                r.getStatus(), r.getMessage(), r.getCreatedAt());
    }
}

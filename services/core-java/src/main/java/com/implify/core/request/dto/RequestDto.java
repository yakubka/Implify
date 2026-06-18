package com.implify.core.request.dto;

import com.implify.core.domain.HelpRequest;
import com.implify.core.domain.RequestStatus;
import com.implify.core.domain.Urgency;

import java.time.OffsetDateTime;
import java.util.List;
import java.util.Set;
import java.util.stream.Collectors;

public record RequestDto(
        Long id,
        String title,
        String description,
        RequestStatus status,
        Urgency urgency,
        double lat,
        double lon,
        int capacity,
        int filled,
        Long zoneId,
        Long createdBy,
        Set<String> skills,
        List<ResourceDto> resources,
        OffsetDateTime createdAt
) {
    public static RequestDto from(HelpRequest r) {
        return new RequestDto(
                r.getId(), r.getTitle(), r.getDescription(), r.getStatus(), r.getUrgency(),
                r.getLat(), r.getLon(), r.getCapacity(), r.getFilled(),
                r.getZone() != null ? r.getZone().getId() : null,
                r.getCreatedBy(),
                r.getSkills(),
                r.getResources().stream()
                        .map(res -> new ResourceDto(res.getResource(), res.getQuantity()))
                        .collect(Collectors.toList()),
                r.getCreatedAt()
        );
    }
}

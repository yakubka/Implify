package com.implify.core.event;

import com.implify.core.domain.ResponseStatus;

/** Публикуется при создании/смене статуса отклика волонтёра. */
public record ResponseChangedEvent(Long requestId, Long responseId, Long userId, ResponseStatus status) {
}

package com.implify.core.event;

import com.implify.core.domain.RequestStatus;

/** Публикуется при изменении статуса/заполненности запроса. Слушают: WebSocket, Kafka. */
public record RequestUpdatedEvent(Long requestId, RequestStatus status, int filled, int capacity) {
}

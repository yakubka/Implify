package com.implify.core.event;

/** Публикуется при создании запроса помощи. Слушают: Kafka-продюсер, ML-индексатор. */
public record RequestCreatedEvent(Long requestId, String title, double lat, double lon) {
}

package com.implify.core.messaging;

import com.implify.core.event.RequestCreatedEvent;
import com.implify.core.event.RequestUpdatedEvent;
import com.implify.core.event.ResponseChangedEvent;
import org.springframework.kafka.core.KafkaTemplate;
import org.springframework.stereotype.Component;
import org.springframework.transaction.event.TransactionalEventListener;

/** Публикует доменные события в Kafka после коммита транзакции. */
@Component
public class KafkaEventPublisher {

    private final KafkaTemplate<String, Object> kafka;

    public KafkaEventPublisher(KafkaTemplate<String, Object> kafka) {
        this.kafka = kafka;
    }

    @TransactionalEventListener
    public void onRequestCreated(RequestCreatedEvent e) {
        kafka.send(KafkaTopics.REQUEST_EVENTS, String.valueOf(e.requestId()), e);
    }

    @TransactionalEventListener
    public void onRequestUpdated(RequestUpdatedEvent e) {
        kafka.send(KafkaTopics.REQUEST_EVENTS, String.valueOf(e.requestId()), e);
    }

    @TransactionalEventListener
    public void onResponseChanged(ResponseChangedEvent e) {
        kafka.send(KafkaTopics.RESPONSE_EVENTS, String.valueOf(e.requestId()), e);
    }
}

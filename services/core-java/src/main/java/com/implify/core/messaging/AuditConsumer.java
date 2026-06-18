package com.implify.core.messaging;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.kafka.annotation.KafkaListener;
import org.springframework.messaging.handler.annotation.Header;
import org.springframework.messaging.handler.annotation.Payload;
import org.springframework.stereotype.Component;

/**
 * Демонстрационный consumer: журналирует доменные события (аудит).
 * В реальной системе сюда вешаются рассылка уведомлений, проекции и т.п.
 */
@Component
public class AuditConsumer {

    private static final Logger log = LoggerFactory.getLogger(AuditConsumer.class);

    @KafkaListener(topics = {KafkaTopics.REQUEST_EVENTS, KafkaTopics.RESPONSE_EVENTS},
            groupId = "implify-audit")
    public void onEvent(@Payload String payload,
                        @Header(name = "kafka_receivedTopic", required = false) String topic) {
        log.info("[audit] topic={} event={}", topic, payload);
    }
}

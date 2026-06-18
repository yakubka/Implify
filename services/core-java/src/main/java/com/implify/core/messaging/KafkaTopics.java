package com.implify.core.messaging;

import org.apache.kafka.clients.admin.NewTopic;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.kafka.config.TopicBuilder;

@Configuration
public class KafkaTopics {

    public static final String REQUEST_EVENTS = "request.events";
    public static final String RESPONSE_EVENTS = "response.events";

    @Bean
    public NewTopic requestEventsTopic() {
        return TopicBuilder.name(REQUEST_EVENTS).partitions(1).replicas(1).build();
    }

    @Bean
    public NewTopic responseEventsTopic() {
        return TopicBuilder.name(RESPONSE_EVENTS).partitions(1).replicas(1).build();
    }
}

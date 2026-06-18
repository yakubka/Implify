package com.implify.core.realtime;

import com.implify.core.event.RequestCreatedEvent;
import com.implify.core.event.RequestUpdatedEvent;
import com.implify.core.event.ResponseChangedEvent;
import org.springframework.messaging.simp.SimpMessagingTemplate;
import org.springframework.stereotype.Component;
import org.springframework.transaction.event.TransactionalEventListener;

/** Транслирует доменные события в STOMP-топики после коммита транзакции. */
@Component
public class RealtimeBroadcaster {

    private final SimpMessagingTemplate ws;

    public RealtimeBroadcaster(SimpMessagingTemplate ws) {
        this.ws = ws;
    }

    @TransactionalEventListener
    public void onCreated(RequestCreatedEvent e) {
        ws.convertAndSend("/topic/requests", e);
    }

    @TransactionalEventListener
    public void onUpdated(RequestUpdatedEvent e) {
        ws.convertAndSend("/topic/requests", e);
        ws.convertAndSend("/topic/requests/" + e.requestId(), e);
    }

    @TransactionalEventListener
    public void onResponse(ResponseChangedEvent e) {
        ws.convertAndSend("/topic/requests/" + e.requestId(), e);
    }
}

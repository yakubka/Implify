package com.implify.core.domain;

/** Жизненный цикл запроса помощи. */
public enum RequestStatus {
    OPEN,
    IN_PROGRESS,
    FULFILLED,
    CANCELLED
}

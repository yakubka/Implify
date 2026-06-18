package com.implify.core.domain;

import jakarta.persistence.*;
import lombok.Getter;
import lombok.Setter;

@Entity
@Table(name = "request_resources")
@Getter
@Setter
public class RequestResource {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @ManyToOne(fetch = FetchType.LAZY, optional = false)
    @JoinColumn(name = "request_id", nullable = false)
    private HelpRequest request;

    @Column(nullable = false, length = 80)
    private String resource;

    @Column(nullable = false)
    private int quantity = 1;
}

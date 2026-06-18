package com.implify.core.repo;

import com.implify.core.domain.HelpRequest;
import com.implify.core.domain.RequestStatus;
import org.springframework.data.jpa.repository.JpaRepository;

import java.util.List;

public interface HelpRequestRepository extends JpaRepository<HelpRequest, Long> {
    List<HelpRequest> findByStatus(RequestStatus status);
    List<HelpRequest> findByZoneId(Long zoneId);
}

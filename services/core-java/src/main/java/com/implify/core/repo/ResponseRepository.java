package com.implify.core.repo;

import com.implify.core.domain.VolunteerResponse;
import org.springframework.data.jpa.repository.JpaRepository;

import java.util.List;
import java.util.Optional;

public interface ResponseRepository extends JpaRepository<VolunteerResponse, Long> {
    List<VolunteerResponse> findByRequestId(Long requestId);
    List<VolunteerResponse> findByUserId(Long userId);
    Optional<VolunteerResponse> findByRequestIdAndUserId(Long requestId, Long userId);
    boolean existsByRequestIdAndUserId(Long requestId, Long userId);
}

package com.implify.core.request;

import com.implify.core.domain.*;
import com.implify.core.event.RequestCreatedEvent;
import com.implify.core.event.RequestUpdatedEvent;
import com.implify.core.event.ResponseChangedEvent;
import com.implify.core.repo.HelpRequestRepository;
import com.implify.core.repo.ResponseRepository;
import com.implify.core.repo.ZoneRepository;
import com.implify.core.request.dto.CreateRequestDto;
import org.springframework.context.ApplicationEventPublisher;
import org.springframework.http.HttpStatus;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;
import org.springframework.web.server.ResponseStatusException;

import java.util.List;

@Service
public class HelpRequestService {

    private final HelpRequestRepository requests;
    private final ResponseRepository responses;
    private final ZoneRepository zones;
    private final ApplicationEventPublisher events;

    public HelpRequestService(HelpRequestRepository requests, ResponseRepository responses,
                              ZoneRepository zones, ApplicationEventPublisher events) {
        this.requests = requests;
        this.responses = responses;
        this.zones = zones;
        this.events = events;
    }

    @Transactional(readOnly = true)
    public List<HelpRequest> list(RequestStatus status, Long zoneId) {
        if (status != null) return requests.findByStatus(status);
        if (zoneId != null) return requests.findByZoneId(zoneId);
        return requests.findAll();
    }

    @Transactional(readOnly = true)
    public HelpRequest get(Long id) {
        return requests.findById(id)
                .orElseThrow(() -> new ResponseStatusException(HttpStatus.NOT_FOUND, "Request not found"));
    }

    @Transactional
    public HelpRequest create(CreateRequestDto dto, Long authorId) {
        HelpRequest r = new HelpRequest();
        r.setTitle(dto.title());
        r.setDescription(dto.description());
        r.setUrgency(dto.urgency() != null ? dto.urgency() : Urgency.NORMAL);
        r.setLat(dto.lat());
        r.setLon(dto.lon());
        r.setCapacity(dto.capacity() > 0 ? dto.capacity() : 1);
        r.setCreatedBy(authorId);
        if (dto.skills() != null) r.getSkills().addAll(dto.skills());
        if (dto.zoneId() != null) {
            Zone z = zones.findById(dto.zoneId())
                    .orElseThrow(() -> new ResponseStatusException(HttpStatus.BAD_REQUEST, "Zone not found"));
            r.setZone(z);
        }
        if (dto.resources() != null) {
            dto.resources().forEach(res -> {
                RequestResource rr = new RequestResource();
                rr.setResource(res.resource());
                rr.setQuantity(res.quantity());
                r.addResource(rr);
            });
        }
        HelpRequest saved = requests.save(r);
        events.publishEvent(new RequestCreatedEvent(saved.getId(), saved.getTitle(), saved.getLat(), saved.getLon()));
        return saved;
    }

    /** Волонтёр откликается на запрос. */
    @Transactional
    public VolunteerResponse respond(Long requestId, Long userId, String message) {
        HelpRequest r = get(requestId);
        if (r.getStatus() == RequestStatus.CANCELLED || r.getStatus() == RequestStatus.FULFILLED) {
            throw new ResponseStatusException(HttpStatus.CONFLICT, "Request is not accepting responses");
        }
        if (responses.existsByRequestIdAndUserId(requestId, userId)) {
            throw new ResponseStatusException(HttpStatus.CONFLICT, "Already responded");
        }
        VolunteerResponse resp = new VolunteerResponse();
        resp.setRequestId(requestId);
        resp.setUserId(userId);
        resp.setMessage(message);
        VolunteerResponse saved = responses.save(resp);
        events.publishEvent(new ResponseChangedEvent(requestId, saved.getId(), userId, saved.getStatus()));
        return saved;
    }

    /** Координатор/автор принимает отклик: увеличивает filled и при заполнении меняет статус. */
    @Transactional
    public VolunteerResponse acceptResponse(Long responseId, Long actingUserId) {
        VolunteerResponse resp = responses.findById(responseId)
                .orElseThrow(() -> new ResponseStatusException(HttpStatus.NOT_FOUND, "Response not found"));
        HelpRequest r = get(resp.getRequestId());
        requireOwnerOrCoordinator(r, actingUserId);
        if (resp.getStatus() == ResponseStatus.ACCEPTED) return resp;
        if (r.getFilled() >= r.getCapacity()) {
            throw new ResponseStatusException(HttpStatus.CONFLICT, "Request already full");
        }
        resp.setStatus(ResponseStatus.ACCEPTED);
        r.setFilled(r.getFilled() + 1);
        if (r.getStatus() == RequestStatus.OPEN) r.setStatus(RequestStatus.IN_PROGRESS);
        if (r.getFilled() >= r.getCapacity()) r.setStatus(RequestStatus.FULFILLED);
        requests.save(r);
        responses.save(resp);
        events.publishEvent(new ResponseChangedEvent(r.getId(), resp.getId(), resp.getUserId(), resp.getStatus()));
        events.publishEvent(new RequestUpdatedEvent(r.getId(), r.getStatus(), r.getFilled(), r.getCapacity()));
        return resp;
    }

    @Transactional
    public HelpRequest cancel(Long requestId, Long actingUserId) {
        HelpRequest r = get(requestId);
        requireOwnerOrCoordinator(r, actingUserId);
        r.setStatus(RequestStatus.CANCELLED);
        requests.save(r);
        events.publishEvent(new RequestUpdatedEvent(r.getId(), r.getStatus(), r.getFilled(), r.getCapacity()));
        return r;
    }

    @Transactional(readOnly = true)
    public List<VolunteerResponse> responsesFor(Long requestId) {
        return responses.findByRequestId(requestId);
    }

    private void requireOwnerOrCoordinator(HelpRequest r, Long actingUserId) {
        if (!r.getCreatedBy().equals(actingUserId)) {
            // На уровне контроллера дополнительно проверяется роль ROLE_COORDINATOR.
            throw new ResponseStatusException(HttpStatus.FORBIDDEN, "Not the owner of this request");
        }
    }
}

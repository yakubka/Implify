package com.implify.core.request;

import com.implify.core.domain.RequestStatus;
import com.implify.core.request.dto.CreateRequestDto;
import com.implify.core.request.dto.CreateResponseDto;
import com.implify.core.request.dto.RequestDto;
import com.implify.core.request.dto.ResponseDto;
import com.implify.core.security.AuthPrincipal;
import jakarta.validation.Valid;
import org.springframework.http.HttpStatus;
import org.springframework.security.core.annotation.AuthenticationPrincipal;
import org.springframework.web.bind.annotation.*;

import java.util.List;

@RestController
@RequestMapping("/api/requests")
public class HelpRequestController {

    private final HelpRequestService service;

    public HelpRequestController(HelpRequestService service) {
        this.service = service;
    }

    @GetMapping
    public List<RequestDto> list(@RequestParam(required = false) RequestStatus status,
                                 @RequestParam(required = false) Long zoneId) {
        return service.list(status, zoneId).stream().map(RequestDto::from).toList();
    }

    @GetMapping("/{id}")
    public RequestDto get(@PathVariable Long id) {
        return RequestDto.from(service.get(id));
    }

    @PostMapping
    @ResponseStatus(HttpStatus.CREATED)
    public RequestDto create(@Valid @RequestBody CreateRequestDto dto,
                             @AuthenticationPrincipal AuthPrincipal principal) {
        return RequestDto.from(service.create(dto, principal.userId()));
    }

    @PostMapping("/{id}/responses")
    @ResponseStatus(HttpStatus.CREATED)
    public ResponseDto respond(@PathVariable Long id,
                               @Valid @RequestBody(required = false) CreateResponseDto body,
                               @AuthenticationPrincipal AuthPrincipal principal) {
        String message = body != null ? body.message() : null;
        return ResponseDto.from(service.respond(id, principal.userId(), message));
    }

    @GetMapping("/{id}/responses")
    public List<ResponseDto> responses(@PathVariable Long id) {
        return service.responsesFor(id).stream().map(ResponseDto::from).toList();
    }

    @PostMapping("/{id}/cancel")
    public RequestDto cancel(@PathVariable Long id,
                             @AuthenticationPrincipal AuthPrincipal principal) {
        return RequestDto.from(service.cancel(id, principal.userId()));
    }
}

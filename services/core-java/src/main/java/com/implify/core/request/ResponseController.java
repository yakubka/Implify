package com.implify.core.request;

import com.implify.core.request.dto.ResponseDto;
import com.implify.core.security.AuthPrincipal;
import org.springframework.security.core.annotation.AuthenticationPrincipal;
import org.springframework.web.bind.annotation.*;

@RestController
@RequestMapping("/api/responses")
public class ResponseController {

    private final HelpRequestService service;

    public ResponseController(HelpRequestService service) {
        this.service = service;
    }

    @PostMapping("/{id}/accept")
    public ResponseDto accept(@PathVariable Long id,
                              @AuthenticationPrincipal AuthPrincipal principal) {
        return ResponseDto.from(service.acceptResponse(id, principal.userId()));
    }
}

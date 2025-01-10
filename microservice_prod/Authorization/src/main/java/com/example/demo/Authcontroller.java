package com.example.demo.controller;

import com.example.demo.model.User;
import com.example.demo.service.AuthService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

@RestController
@RequestMapping("/auth")
public class Authсontroller {

    @Autowired
    private AuthService authService;

    @PostMapping("/register")
    public ResponseEntity<String> register(@RequestBody User user) {
        if (authService.register(user)) {
            return ResponseEntity.ok("Registration successful!");
        } else {
            return ResponseEntity.status(HttpStatus.BAD_REQUEST).body("User already exists!");
        }
    }
}

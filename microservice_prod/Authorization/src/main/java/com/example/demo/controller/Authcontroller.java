package com.example.demo.controller;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.RestController;

import com.example.demo.model.User;
import com.example.demo.service.Authservice;

@RestController
@RequestMapping("/auth")
public class Authcontroller {

    @Autowired
    private Authservice authService;

    // Regestartion 

    @PostMapping("/register")
    public ResponseEntity<?> register(@RequestBody User user) {
        boolean ok = authService.register(user);
        if (!ok) {
            return ResponseEntity.badRequest().body("Пользователь с таким логином уже есть!");
        }
        return ResponseEntity.ok("Регистрация успешна!");
    }

    // Login
    
    @PostMapping("/login")
    public ResponseEntity<?> login(@RequestParam String username,
                                   @RequestParam String password) {
        boolean ok = authService.login(username, password);
        if (!ok) {
            return ResponseEntity.status(401).body("Неверный логин или пароль!");
        }
        return ResponseEntity.ok("Вы успешно вошли!");
    }
}

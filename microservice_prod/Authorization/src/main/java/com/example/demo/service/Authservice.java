package com.example.demo.service;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.crypto.bcrypt.BCryptPasswordEncoder;
import org.springframework.stereotype.Service;

import com.example.demo.model.User;
import com.example.demo.repository.UserRepository;

@Service
public class Authservice {

    @Autowired
    private UserRepository userRepository;

    private final BCryptPasswordEncoder passwordEncoder = new BCryptPasswordEncoder();

    public boolean register(User user) {
        // checking by  username
        if (userRepository.findByUsername(user.getUsername()).isPresent()) {
            return false; 
        }
        
        // Hashing pswd
        user.setPassword(passwordEncoder.encode(user.getPassword()));
    
        userRepository.save(user);
        return true;
    }

    public boolean login(String username, String password) {
        return userRepository.findByUsername(username)
                .map(user -> passwordEncoder.matches(password, user.getPassword()))
                .orElse(false);
    }
}

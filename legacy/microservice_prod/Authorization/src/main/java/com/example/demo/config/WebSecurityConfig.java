package com.example.demo.config;

import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.security.config.Customizer;
import org.springframework.security.config.annotation.web.builders.HttpSecurity;
import org.springframework.security.web.SecurityFilterChain;

@Configuration
public class WebSecurityConfig {

    @Bean
    public SecurityFilterChain securityFilterChain(HttpSecurity http) throws Exception {
        // доступ к /auth/register, /auth/login (POST запросы) без аутентификации
        http.authorizeHttpRequests(authorize -> authorize
                .requestMatchers("/auth/register").permitAll()
                .requestMatchers("/auth/login").permitAll()
                // Всё остальное требует аутентификации
                .anyRequest().authenticated()
        );

        // стандартная форма логина 
        http.formLogin(Customizer.withDefaults());

        // отключение csrf для POST запросов на время потом
        http.csrf(csrf -> csrf.disable());
        http.formLogin(Customizer.withDefaults());

        return http.build();
    }
}

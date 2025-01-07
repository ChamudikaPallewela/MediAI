package com.example.mediai;

public class User {
    public String email;
    public String username;
    public String patientId;

    public User() {
        // Default constructor required for calls to DataSnapshot.getValue(User.class)
    }

    public User(String email, String username, String patientId) {
        this.email = email;
        this.username = username;
        this.patientId = patientId;
    }
}

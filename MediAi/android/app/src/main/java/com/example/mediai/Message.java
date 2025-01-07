package com.example.mediai;

public class Message {
    public String content;
    public boolean isSentByUser;

    public Message(String content, boolean isSentByUser) {
        this.content = content;
        this.isSentByUser = isSentByUser;
    }
}


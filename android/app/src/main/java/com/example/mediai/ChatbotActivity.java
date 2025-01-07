package com.example.mediai;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import java.util.ArrayList;
import java.util.List;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;
import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;
import retrofit2.http.Body;
import retrofit2.http.POST;
import com.google.android.material.bottomnavigation.BottomNavigationView;

public class ChatbotActivity extends AppCompatActivity {

    private RecyclerView recyclerView;
    private MessageAdapter messageAdapter;
    private List<Message> messageList;
    private EditText editTextMessage;
    private Button buttonSend;

    private interface ChatService {
        @POST("/chat")
        Call<ChatResponse> chat(@Body ChatInput chatInput);
    }


    private class ChatInput {
        String message;

        ChatInput(String message) {
            this.message = message;
        }
    }

    private ChatService chatService;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_chatbot);

        recyclerView = findViewById(R.id.recyclerView);
        editTextMessage = findViewById(R.id.editTextMessage);
        buttonSend = findViewById(R.id.buttonSend);

        messageList = new ArrayList<>();
        messageAdapter = new MessageAdapter(messageList);

        recyclerView.setLayoutManager(new LinearLayoutManager(this));
        recyclerView.setAdapter(messageAdapter);



        Retrofit retrofit = new Retrofit.Builder()
                .baseUrl("http://10.0.2.2:5000/")
                .addConverterFactory(GsonConverterFactory.create())
                .build();

        chatService = retrofit.create(ChatService.class);

        buttonSend.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                String messageText = editTextMessage.getText().toString().trim();
                if (!messageText.isEmpty()) {
                    addMessage(messageText, true);
                    sendMessageToServer(messageText);
                    editTextMessage.setText("");
                }
            }
        });
        BottomNavigationView bottomNavigationView = findViewById(R.id.bottom_navigation);
        bottomNavigationView.setSelectedItemId(R.id.navigation_chatbot);

        bottomNavigationView.setOnNavigationItemSelectedListener(new BottomNavigationView.OnNavigationItemSelectedListener() {
            @Override
            public boolean onNavigationItemSelected(@NonNull MenuItem item) {
                if (item.getItemId() == R.id.navigation_home) {
                    startActivity(new Intent(getApplicationContext(), HomeActivity.class));
                    overridePendingTransition(0, 0);
                    finish();
                    return true;
                } else if (item.getItemId() == R.id.navigation_predict) {
                    startActivity(new Intent(getApplicationContext(), PredictActivity.class));
                    overridePendingTransition(0, 0);
                    finish();
                    return true;
                } else if (item.getItemId() == R.id.navigation_chatbot) {
                    return true;
                } else if (item.getItemId() == R.id.navigation_remaning) {
                    startActivity(new Intent(getApplicationContext(), RemaningDaysActivity.class));
                    overridePendingTransition(0, 0);
                    finish();
                    return true;
                } else if (item.getItemId() == R.id.navigation_settings) {
                    startActivity(new Intent(getApplicationContext(), SettingsActivity.class));
                    overridePendingTransition(0, 0);
                    finish();
                    return true;
                }
                return false;
            }
        });
    }


    private void addMessage(String content, boolean isSentByUser) {
        messageList.add(new Message(content, isSentByUser));
        messageAdapter.notifyDataSetChanged();
        recyclerView.scrollToPosition(messageList.size() - 1);
    }

    private void sendMessageToServer(String message) {
        chatService.chat(new ChatInput(message)).enqueue(new Callback<ChatResponse>() {
            @Override
            public void onResponse(Call<ChatResponse> call, Response<ChatResponse> response) {
                if (response.isSuccessful()) {
                    addMessage(response.body().getResponse(), false);
                } else {
                    addMessage("Error: " + response.code(), false);
                }
            }

            @Override
            public void onFailure(Call<ChatResponse> call, Throwable t) {
                addMessage("Error: " + t.getMessage(), false);
            }
        });
    }
}

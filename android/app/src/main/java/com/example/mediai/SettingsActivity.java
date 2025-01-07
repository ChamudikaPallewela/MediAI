package com.example.mediai;


import android.content.Intent;
import android.os.Bundle;
import android.text.TextUtils;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;
import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;

import com.google.android.material.bottomnavigation.BottomNavigationView;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.auth.FirebaseUser;
import com.google.firebase.database.DatabaseReference;
import com.google.firebase.database.FirebaseDatabase;
import java.util.HashMap;
import java.util.Map;

public class SettingsActivity extends AppCompatActivity {

    private EditText etUsername;
    private Button btnUpdateUsername, btnLogout, btnDeleteAccount;
    private FirebaseAuth mAuth;
    private DatabaseReference mDatabase;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_settings);

        etUsername = findViewById(R.id.etUsername);
        btnUpdateUsername = findViewById(R.id.btnUpdateUsername);
        btnLogout = findViewById(R.id.btnLogout);
        btnDeleteAccount = findViewById(R.id.btnDeleteAccount);

        mAuth = FirebaseAuth.getInstance();
        mDatabase = FirebaseDatabase.getInstance().getReference();

        btnUpdateUsername.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                updateUsername();
            }
        });

        btnLogout.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                logout();
            }
        });

        btnDeleteAccount.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                deleteAccount();
            }
        });
        BottomNavigationView bottomNavigationView = findViewById(R.id.bottom_navigation);
        bottomNavigationView.setSelectedItemId(R.id.navigation_settings);

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
                    startActivity(new Intent(getApplicationContext(), ChatbotActivity.class));
                    overridePendingTransition(0, 0);
                    finish();
                    return true;
                } else if (item.getItemId() == R.id.navigation_remaning) {
                    startActivity(new Intent(getApplicationContext(), RemaningDaysActivity.class));
                    overridePendingTransition(0, 0);
                    finish();
                    return true;
                } else if (item.getItemId() == R.id.navigation_settings) {

                    return true;
                }
                return false;
            }
        });
    }

    private void updateUsername() {
        String newUsername = etUsername.getText().toString().trim();

        if (TextUtils.isEmpty(newUsername)) {
            Toast.makeText(SettingsActivity.this, "Please enter a new username", Toast.LENGTH_SHORT).show();
            return;
        }

        FirebaseUser user = mAuth.getCurrentUser();
        if (user != null) {
            String userId = user.getUid();
            Map<String, Object> updates = new HashMap<>();
            updates.put("username", newUsername);

            mDatabase.child("users").child(userId).updateChildren(updates)
                    .addOnCompleteListener(task -> {
                        if (task.isSuccessful()) {
                            Toast.makeText(SettingsActivity.this, "Username updated", Toast.LENGTH_SHORT).show();
                        } else {
                            Toast.makeText(SettingsActivity.this, "Failed to update username", Toast.LENGTH_SHORT).show();
                        }
                    });
        }
    }

    private void logout() {
        mAuth.signOut();
        Intent intent = new Intent(SettingsActivity.this, LoginActivity.class);
        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
        startActivity(intent);
        finish();
    }

    private void deleteAccount() {
        FirebaseUser user = mAuth.getCurrentUser();
        if (user != null) {
            String userId = user.getUid();

            mDatabase.child("users").child(userId).removeValue()
                    .addOnCompleteListener(task -> {
                        if (task.isSuccessful()) {
                            user.delete()
                                    .addOnCompleteListener(deleteTask -> {
                                        if (deleteTask.isSuccessful()) {
                                            Toast.makeText(SettingsActivity.this, "Account deleted", Toast.LENGTH_SHORT).show();
                                            Intent intent = new Intent(SettingsActivity.this, LoginActivity.class);
                                            intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
                                            startActivity(intent);
                                            finish();
                                        } else {
                                            Toast.makeText(SettingsActivity.this, "Failed to delete account", Toast.LENGTH_SHORT).show();
                                        }
                                    });
                        } else {
                            Toast.makeText(SettingsActivity.this, "Failed to delete account data", Toast.LENGTH_SHORT).show();
                        }
                    });
        }
    }
}

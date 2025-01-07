package com.example.mediai;

import android.content.Intent;
import android.os.Bundle;
import android.text.method.HideReturnsTransformationMethod;
import android.text.method.PasswordTransformationMethod;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.auth.FirebaseUser;
import com.google.firebase.database.DatabaseReference;
import com.google.firebase.database.FirebaseDatabase;

public class SignUpActivity extends AppCompatActivity {

    private EditText etEmail, etUsername, etPatientId, etPassword;
    private CheckBox cbShowPassword;
    private Button btnSignUp;
    private FirebaseAuth mAuth;
    private FirebaseDatabase database;
    private DatabaseReference usersRef;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_sign_up);

        etEmail = findViewById(R.id.etEmail);
        etUsername = findViewById(R.id.etUsername);
        etPatientId = findViewById(R.id.etPatientId);
        etPassword = findViewById(R.id.etPassword);
        cbShowPassword = findViewById(R.id.cbShowPassword);
        btnSignUp = findViewById(R.id.btnSignUp);

        mAuth = FirebaseAuth.getInstance();
        database = FirebaseDatabase.getInstance();
        usersRef = database.getReference("users");

        cbShowPassword.setOnCheckedChangeListener((buttonView, isChecked) -> {
            if (isChecked) {
                etPassword.setTransformationMethod(HideReturnsTransformationMethod.getInstance());
            } else {
                etPassword.setTransformationMethod(PasswordTransformationMethod.getInstance());
            }
        });

        btnSignUp.setOnClickListener(v -> {
            String email = etEmail.getText().toString().trim();
            String username = etUsername.getText().toString().trim();
            String password = etPassword.getText().toString().trim();
            String patientId = etPatientId.getText().toString().trim();

            if (email.isEmpty() || username.isEmpty() || password.isEmpty()) {
                Toast.makeText(SignUpActivity.this, "Email, Username, and Password are required", Toast.LENGTH_SHORT).show();
                return;
            }

            mAuth.createUserWithEmailAndPassword(email, password)
                    .addOnCompleteListener(this, task -> {
                        if (task.isSuccessful()) {
                            FirebaseUser user = mAuth.getCurrentUser();
                            if (user != null) {
                                String userId = user.getUid();

                                // Prepare user data
                                User newUser = new User(email, username, patientId);

                                // Save user data to Realtime Database
                                usersRef.child(userId).setValue(newUser)
                                        .addOnCompleteListener(saveTask -> {
                                            if (saveTask.isSuccessful()) {
                                                Toast.makeText(SignUpActivity.this, "Registration Successful", Toast.LENGTH_SHORT).show();

                                                // Navigate to HomeActivity
                                                Intent intent = new Intent(SignUpActivity.this, HomeActivity.class);
                                                startActivity(intent);
                                                finish(); // Optional: close the current activity
                                            } else {
                                                Toast.makeText(SignUpActivity.this, "Failed to save user data: " + saveTask.getException(), Toast.LENGTH_SHORT).show();
                                            }
                                        });
                            }
                        } else {
                            Toast.makeText(SignUpActivity.this, "Registration Failed: " + task.getException(), Toast.LENGTH_SHORT).show();
                        }
                    });
        });
    }
}

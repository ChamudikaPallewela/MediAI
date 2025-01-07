package com.example.mediai;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.MenuItem;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.google.android.material.bottomnavigation.BottomNavigationView;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.database.DataSnapshot;
import com.google.firebase.database.DatabaseError;
import com.google.firebase.database.DatabaseReference;
import com.google.firebase.database.FirebaseDatabase;
import com.google.firebase.database.ValueEventListener;
import java.util.ArrayList;
import java.util.List;

public class HomeActivity extends AppCompatActivity {

    private static final String TAG = "HomeActivity";

    private FirebaseAuth mAuth;
    private DatabaseReference mDatabase;
    private TextView usernameTextView;
    private TextView emailTextView;
    private ImageView profileImageView;
    private RecyclerView prescriptionsRecyclerView;
    private PrescriptionAdapter prescriptionAdapter;
    private List<Prescription> prescriptionList;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_home);

        usernameTextView = findViewById(R.id.username_text);
        emailTextView = findViewById(R.id.email_text);
        profileImageView = findViewById(R.id.profile_image);
        prescriptionsRecyclerView = findViewById(R.id.prescriptions_recycler_view);

        mAuth = FirebaseAuth.getInstance();
        mDatabase = FirebaseDatabase.getInstance().getReference();

        prescriptionList = new ArrayList<>();
        prescriptionAdapter = new PrescriptionAdapter(prescriptionList);

        prescriptionsRecyclerView.setLayoutManager(new LinearLayoutManager(this));
        prescriptionsRecyclerView.setAdapter(prescriptionAdapter);

        loadUserInfo();
        loadPrescriptions();
        BottomNavigationView bottomNavigationView = findViewById(R.id.bottom_navigation);
        bottomNavigationView.setSelectedItemId(R.id.navigation_home);

        bottomNavigationView.setOnNavigationItemSelectedListener(new BottomNavigationView.OnNavigationItemSelectedListener() {
            @Override
            public boolean onNavigationItemSelected(@NonNull MenuItem item) {
                if (item.getItemId() == R.id.navigation_home) {
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
                    startActivity(new Intent(getApplicationContext(), SettingsActivity.class));
                    overridePendingTransition(0, 0);
                    finish();
                    return true;
                }
                return false;
            }
        });
    }

    private void loadUserInfo() {
        String userId = mAuth.getCurrentUser().getUid();
        mDatabase.child("users").child(userId).addListenerForSingleValueEvent(new ValueEventListener() {
            @Override
            public void onDataChange(@NonNull DataSnapshot dataSnapshot) {
                if (dataSnapshot.exists()) {
                    String username = dataSnapshot.child("username").getValue(String.class);
                    String email = dataSnapshot.child("email").getValue(String.class);

                    usernameTextView.setText(username);
                    emailTextView.setText(email);
                }
            }

            @Override
            public void onCancelled(@NonNull DatabaseError databaseError) {
                // Handle possible errors.
                Log.e(TAG, "Failed to read user info", databaseError.toException());
            }
        });
    }

    private void loadPrescriptions() {
        String userId = mAuth.getCurrentUser().getUid();
        mDatabase.child("users").child(userId).child("prescriptions").addListenerForSingleValueEvent(new ValueEventListener() {
            @Override
            public void onDataChange(@NonNull DataSnapshot dataSnapshot) {
                prescriptionList.clear();
                if (dataSnapshot.exists()) {
                    for (DataSnapshot snapshot : dataSnapshot.getChildren()) {
                        Prescription prescription = snapshot.getValue(Prescription.class);
                        if (prescription != null) {
                            Log.d(TAG, "Fetched prescription: " + prescription.getDrugName());
                            prescriptionList.add(prescription);
                        } else {
                            Log.e(TAG, "Prescription data is null");
                        }
                    }
                } else {
                    Log.d(TAG, "No prescriptions found");
                    prescriptionList.add(new Prescription("No prescription available", 0));
                }
                prescriptionAdapter.notifyDataSetChanged();
            }

            @Override
            public void onCancelled(@NonNull DatabaseError databaseError) {
                // Handle possible errors.
                Log.e(TAG, "Failed to read prescriptions", databaseError.toException());
            }
        });
    }

    public void signOut(View view) {
        mAuth.signOut();
        Intent intent = new Intent(HomeActivity.this, LoginActivity.class);
        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
        startActivity(intent);
        finish();
    }
}

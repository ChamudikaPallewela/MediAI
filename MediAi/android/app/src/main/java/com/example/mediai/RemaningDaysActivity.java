package com.example.mediai;

import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.util.Log;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;
import androidx.work.PeriodicWorkRequest;
import androidx.work.WorkManager;
import androidx.work.WorkRequest;
import com.google.android.material.bottomnavigation.BottomNavigationView;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.database.DataSnapshot;
import com.google.firebase.database.DatabaseError;
import com.google.firebase.database.DatabaseReference;
import com.google.firebase.database.FirebaseDatabase;
import com.google.firebase.database.ValueEventListener;
import com.google.zxing.integration.android.IntentIntegrator;
import com.google.zxing.integration.android.IntentResult;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Locale;
import java.util.concurrent.TimeUnit;

public class RemaningDaysActivity extends AppCompatActivity {

    private EditText patientIdEditText;
    private Button fetchDataButton;
    private TextView resultTextView;
    private DatabaseReference databaseReference;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_remaning_days);

        patientIdEditText = findViewById(R.id.patientIdEditText);
        fetchDataButton = findViewById(R.id.fetchDataButton);
        resultTextView = findViewById(R.id.resultTextView);

        // Initialize Firebase Database reference
        databaseReference = FirebaseDatabase.getInstance().getReference();

        BottomNavigationView bottomNavigationView = findViewById(R.id.bottom_navigation);
        bottomNavigationView.setSelectedItemId(R.id.navigation_remaning);

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

        fetchDataButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                String patientId = patientIdEditText.getText().toString();
                new FetchDataAsyncTask().execute(patientId);
            }
        });

        findViewById(R.id.scanQRButton).setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                new IntentIntegrator(RemaningDaysActivity.this).initiateScan();
            }
        });

        scheduleMedicineUpdateWorker();
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
        IntentResult result = IntentIntegrator.parseActivityResult(requestCode, resultCode, data);
        if (result != null) {
            if (result.getContents() != null) {
                patientIdEditText.setText(result.getContents());
            }
        } else {
            super.onActivityResult(requestCode, resultCode, data);
        }
    }

    private void scheduleMedicineUpdateWorker() {
        WorkRequest medicineUpdateWorkRequest =
                new PeriodicWorkRequest.Builder(MedicineUpdateWorker.class, 1, TimeUnit.DAYS)
                        .build();
        WorkManager.getInstance(this).enqueue(medicineUpdateWorkRequest);
    }

    private class FetchDataAsyncTask extends AsyncTask<String, Void, String> {
        @Override
        protected String doInBackground(String... params) {
            String patientId = params[0];
            String apiUrl = "http://10.0.2.2:5000/api/patient/" + patientId;

            try {
                URL url = new URL(apiUrl);
                HttpURLConnection urlConnection = (HttpURLConnection) url.openConnection();

                try {
                    InputStream in = urlConnection.getInputStream();
                    BufferedReader reader = new BufferedReader(new InputStreamReader(in));
                    StringBuilder result = new StringBuilder();
                    String line;

                    while ((line = reader.readLine()) != null) {
                        result.append(line);
                    }

                    Log.d("AsyncTask", "Raw JSON response from server: " + result.toString());

                    return result.toString();
                } finally {
                    urlConnection.disconnect();
                }
            } catch (IOException e) {
                e.printStackTrace();
                return "Error: " + e.getMessage();
            }
        }

        @Override
        protected void onPostExecute(String result) {
            Log.d("AsyncTask", "Raw JSON response from server: " + result);

            try {
                JSONObject jsonObject = new JSONObject(result);
                JSONObject patient = jsonObject.getJSONObject("patient");
                JSONArray prescriptions = jsonObject.getJSONArray("prescriptions");

                String patientName = patient.getString("patient_name");

                StringBuilder drugDetails = new StringBuilder();
                for (int i = 0; i < prescriptions.length(); i++) {
                    JSONObject prescription = prescriptions.getJSONObject(i);
                    String drugName = prescription.getString("drug_name");

                    String endDateStr = prescription.getString("end_date");
                    Date endDate = parseDate(endDateStr);
                    long daysRemaining = calculateDaysRemaining(endDate);

                    saveMedicineToFirebase(patientName, drugName, daysRemaining);

                    drugDetails.append(drugName).append(" (").append(daysRemaining).append(" days remaining)\n");
                }

                resultTextView.setText("Patient Name: " + patientName + "\n\nPrescriptions:\n" + drugDetails.toString());
                resultTextView.setVisibility(View.VISIBLE); // Make the TextView visible

            } catch (JSONException | ParseException e) {
                e.printStackTrace();
                Log.e("JSONParsing", "Error parsing JSON: " + e.getMessage());
            }
        }


        private Date parseDate(String dateStr) throws ParseException {
            SimpleDateFormat dateFormatWithTime = new SimpleDateFormat("yyyy-MM-dd'T'HH:mm:ss.SSS'Z'", Locale.getDefault());
            SimpleDateFormat dateFormatWithoutTime = new SimpleDateFormat("yyyy-MM-dd", Locale.getDefault());

            try {
                return dateFormatWithTime.parse(dateStr);
            } catch (ParseException e) {
                return dateFormatWithoutTime.parse(dateStr);
            }
        }

        private void saveMedicineToFirebase(String patientName, String drugName, long daysRemaining) {
            String userId = FirebaseAuth.getInstance().getCurrentUser().getUid();
            Log.d("FirebaseSave", "Current User ID: " + userId);

            DatabaseReference userRef = databaseReference.child("users").child(userId);

            // Fetch the patient ID from Firebase
            userRef.child("patientId").addListenerForSingleValueEvent(new ValueEventListener() {
                @Override
                public void onDataChange(@NonNull DataSnapshot dataSnapshot) {
                    if (dataSnapshot.exists()) {
                        String patientIdInFirebase = dataSnapshot.getValue(String.class).trim();
                        Log.d("FirebaseSave", "Firebase Patient ID: " + patientIdInFirebase);

                        String enteredPatientId = patientIdEditText.getText().toString().trim();

                        if (enteredPatientId.equals(patientIdInFirebase)) {
                            DatabaseReference newMedicineRef = userRef.child("prescriptions").push();
                            newMedicineRef.child("drug_name").setValue(drugName);
                            newMedicineRef.child("days_remaining").setValue(daysRemaining);
                        } else {
                            Log.e("FirebaseSave", "Patient ID does not match. Prescription details not saved.");
                        }
                    } else {
                        Log.e("FirebaseSave", "Patient ID does not exist in Firebase.");
                    }
                }

                @Override
                public void onCancelled(@NonNull DatabaseError databaseError) {
                    Log.e("FirebaseSave", "Error fetching patient ID from Firebase: " + databaseError.getMessage());
                }
            });
        }




        private long calculateDaysRemaining(Date endDate) {
            Date currentDate = new Date();
            long diffInMillies = endDate.getTime() - currentDate.getTime();
            long daysRemaining = TimeUnit.DAYS.convert(diffInMillies, TimeUnit.MILLISECONDS);
            return daysRemaining + 1;
        }
    }

}

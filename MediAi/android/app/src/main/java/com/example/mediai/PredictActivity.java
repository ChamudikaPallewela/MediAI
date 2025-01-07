package com.example.mediai;

import android.Manifest;
import android.annotation.SuppressLint;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.graphics.Bitmap;
import android.os.Bundle;
import android.provider.MediaStore;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;
import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.app.ActivityCompat;
import androidx.core.content.ContextCompat;
import com.bumptech.glide.Glide;
import com.bumptech.glide.load.engine.DiskCacheStrategy;
import com.bumptech.glide.request.RequestOptions;
import com.google.android.material.bottomnavigation.BottomNavigationView;

import java.io.ByteArrayOutputStream;
import java.io.IOException;

import okhttp3.MediaType;
import okhttp3.MultipartBody;
import okhttp3.RequestBody;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;
import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;

public class PredictActivity extends AppCompatActivity {
    private static final int REQUEST_CAMERA_PERMISSION = 1;
    private static final int REQUEST_IMAGE_CAPTURE = 2;
    private static final int REQUEST_PICK_IMAGE = 3;

    private ImageView imageView;
    private Button captureButton, pickButton, predictButton;
    private TextView predictionTextView;

    private Bitmap selectedImageBitmap;

    @SuppressLint("WrongViewCast")
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_predict);

        imageView = findViewById(R.id.imageView);
        captureButton = findViewById(R.id.captureButton);
        pickButton = findViewById(R.id.pickButton);
        predictButton = findViewById(R.id.predictButton);
        predictionTextView = findViewById(R.id.predictionTextView);

        captureButton.setOnClickListener(v -> requestCameraPermissionAndCapture());
        pickButton.setOnClickListener(v -> pickImage());
        predictButton.setOnClickListener(v -> predictImage());

        // Make the Predict button visible
       // predictButton.setVisibility(View.VISIBLE);

        BottomNavigationView bottomNavigationView = findViewById(R.id.bottom_navigation);
        bottomNavigationView.setSelectedItemId(R.id.navigation_predict);

        bottomNavigationView.setOnNavigationItemSelectedListener(new BottomNavigationView.OnNavigationItemSelectedListener() {
            @Override
            public boolean onNavigationItemSelected(@NonNull MenuItem item) {
                if (item.getItemId() == R.id.navigation_home) {
                    startActivity(new Intent(getApplicationContext(), HomeActivity.class));
                    overridePendingTransition(0, 0);
                    finish();
                    return true;
                } else if (item.getItemId() == R.id.navigation_predict) {
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


    private void requestCameraPermissionAndCapture() {
        if (ContextCompat.checkSelfPermission(this, android.Manifest.permission.CAMERA) != PackageManager.PERMISSION_GRANTED) {
            ActivityCompat.requestPermissions(this, new String[]{Manifest.permission.CAMERA}, REQUEST_CAMERA_PERMISSION);
        } else {
            captureImage();
        }
    }

    private void captureImage() {
        Intent takePictureIntent = new Intent(MediaStore.ACTION_IMAGE_CAPTURE);
        if (takePictureIntent.resolveActivity(getPackageManager()) != null) {
            startActivityForResult(takePictureIntent, REQUEST_IMAGE_CAPTURE);
        }
    }

    private void pickImage() {
        Intent intent = new Intent(Intent.ACTION_PICK, MediaStore.Images.Media.EXTERNAL_CONTENT_URI);
        startActivityForResult(intent, REQUEST_PICK_IMAGE);
    }


    private void predictImage() {
        if (selectedImageBitmap == null) {
            Toast.makeText(this, "Select an image first", Toast.LENGTH_SHORT).show();
            return;
        }

        // Convert Bitmap to byte array
        ByteArrayOutputStream byteArrayOutputStream = new ByteArrayOutputStream();
        selectedImageBitmap.compress(Bitmap.CompressFormat.JPEG, 100, byteArrayOutputStream);
        byte[] byteArray = byteArrayOutputStream.toByteArray();

        // Create Retrofit instance
        Retrofit retrofit = new Retrofit.Builder()
                .baseUrl("http://10.0.2.2:5000/") // Replace with your server IP http://10.0.2.2:5000/
                .addConverterFactory(GsonConverterFactory.create())
                .build();

        // Create API service
        ApiService apiService = retrofit.create(ApiService.class);

        // Create RequestBody and MultipartBody.Part instances
        RequestBody requestBody = RequestBody.create(MediaType.parse("image/*"), byteArray);
        MultipartBody.Part filePart = MultipartBody.Part.createFormData("file", "image.jpg", requestBody);

        // Make API call
        Call<PredictionResponse> call = apiService.predictImage(filePart);
        call.enqueue(new Callback<PredictionResponse>() {
            @Override
            public void onResponse(Call<PredictionResponse> call, Response<PredictionResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    PredictionResponse prediction = response.body();

                    // Call displayPrediction with the correct arguments
                    displayPrediction(prediction.getPredictedClass(), prediction.getScientificName(), prediction.getDescription(),
                            prediction.getUsage(), prediction.getDosage());
                } else {
                    Toast.makeText(PredictActivity.this, "Prediction failed", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<PredictionResponse> call, Throwable t) {
                Toast.makeText(PredictActivity.this, "Prediction failed: " + t.getMessage(), Toast.LENGTH_SHORT).show();
            }

        });
    }


    private void displayPrediction(String predictedClass, String scientificName, String description, String usage, String dosage) {
        String predictionText = "Class: " + predictedClass +
                "\nScientific Name: " + scientificName +
                "\nDescription: " + description +
                "\nUsage: " + usage +
                "\nDosage: " + dosage;

        // Update the TextView with the prediction details
        predictionTextView.setText(predictionText);

        // Display a toast with the prediction details
        Toast.makeText(this, predictionText, Toast.LENGTH_LONG).show();
    }




    @Override
    protected void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
        super.onActivityResult(requestCode, resultCode, data);

        if (resultCode == RESULT_OK) {
            switch (requestCode) {
                case REQUEST_IMAGE_CAPTURE:
                    if (data != null && data.getExtras() != null) {
                        selectedImageBitmap = (Bitmap) data.getExtras().get("data");
                        displaySelectedImage();
                    }
                    break;
                case REQUEST_PICK_IMAGE:
                    if (data != null && data.getData() != null) {
                        try {
                            selectedImageBitmap = MediaStore.Images.Media.getBitmap(getContentResolver(), data.getData());
                            displaySelectedImage();
                        } catch (IOException e) {
                            e.printStackTrace();
                        }
                    }
                    break;
            }
        }
    }

    private void displaySelectedImage() {
        int desiredWidth = 244; // set the desired width
        int desiredHeight = 244; // set the desired height

        Glide.with(this)
                .load(selectedImageBitmap)
                .apply(new RequestOptions()
                        .override(desiredWidth, desiredHeight) // resizing the image
                        .diskCacheStrategy(DiskCacheStrategy.NONE))
                .into(imageView);
    }


    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults);

        if (requestCode == REQUEST_CAMERA_PERMISSION) {
            if (grantResults.length > 0 && grantResults[0] == PackageManager.PERMISSION_GRANTED) {
                captureImage();
            } else {
                Toast.makeText(this, "Camera permission denied", Toast.LENGTH_SHORT).show();
            }
        }
    }
}
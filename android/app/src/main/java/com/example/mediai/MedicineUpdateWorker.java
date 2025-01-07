package com.example.mediai;

import android.content.Context;
import androidx.annotation.NonNull;
import androidx.work.Worker;
import androidx.work.WorkerParameters;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.database.DatabaseReference;
import com.google.firebase.database.FirebaseDatabase;
import com.google.firebase.database.DataSnapshot;
import com.google.firebase.database.DatabaseError;
import com.google.firebase.database.ValueEventListener;
import java.util.concurrent.CountDownLatch;

public class MedicineUpdateWorker extends Worker {

    private DatabaseReference databaseReference;

    public MedicineUpdateWorker(@NonNull Context context, @NonNull WorkerParameters workerParams) {
        super(context, workerParams);
        databaseReference = FirebaseDatabase.getInstance().getReference();
    }

    @NonNull
    @Override
    public Result doWork() {
        String userId = FirebaseAuth.getInstance().getCurrentUser().getUid();
        DatabaseReference userRef = databaseReference.child("users").child(userId).child("prescriptions");

        final CountDownLatch latch = new CountDownLatch(1);

        userRef.addListenerForSingleValueEvent(new ValueEventListener() {
            @Override
            public void onDataChange(@NonNull DataSnapshot snapshot) {
                for (DataSnapshot prescriptionSnapshot : snapshot.getChildren()) {
                    Prescription prescription = prescriptionSnapshot.getValue(Prescription.class);
                    if (prescription != null) {
                        int daysRemaining = prescription.getDaysRemaining() - 1;
                        if (daysRemaining <= 0) {
                            prescriptionSnapshot.getRef().removeValue();
                        } else {
                            prescriptionSnapshot.getRef().child("days_remaining").setValue(daysRemaining);
                        }
                    }
                }
                latch.countDown();
            }

            @Override
            public void onCancelled(@NonNull DatabaseError error) {
                latch.countDown();
            }
        });

        try {
            latch.await();
        } catch (InterruptedException e) {
            e.printStackTrace();
            return Result.failure();
        }

        return Result.success();
    }
}

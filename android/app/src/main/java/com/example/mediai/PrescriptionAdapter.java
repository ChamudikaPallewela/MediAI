package com.example.mediai;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;
import java.util.List;

public class PrescriptionAdapter extends RecyclerView.Adapter<PrescriptionAdapter.ViewHolder> {

    private List<Prescription> prescriptionList;

    public static class ViewHolder extends RecyclerView.ViewHolder {
        public TextView drugNameTextView;
        public TextView daysRemainingTextView;

        public ViewHolder(View view) {
            super(view);
            drugNameTextView = view.findViewById(R.id.drug_name);
            daysRemainingTextView = view.findViewById(R.id.remaining_days);
        }
    }

    public PrescriptionAdapter(List<Prescription> prescriptionList) {
        this.prescriptionList = prescriptionList;
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.prescription_item, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        Prescription prescription = prescriptionList.get(position);
        holder.drugNameTextView.setText("Drug name: " + prescription.getDrugName());
        holder.daysRemainingTextView.setText("Remaining days: " + prescription.getDaysRemaining());
    }

    @Override
    public int getItemCount() {
        return prescriptionList.size();
    }
}

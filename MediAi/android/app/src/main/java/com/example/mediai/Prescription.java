package com.example.mediai;

import com.google.firebase.database.PropertyName;

public class Prescription {
    private String drugName;
    private int daysRemaining;

    public Prescription() {
        // Default constructor required for calls to DataSnapshot.getValue(Prescription.class)
    }

    public Prescription(String drugName, int daysRemaining) {
        this.drugName = drugName;
        this.daysRemaining = daysRemaining;
    }

    @PropertyName("drug_name")
    public String getDrugName() {
        return drugName;
    }

    @PropertyName("drug_name")
    public void setDrugName(String drugName) {
        this.drugName = drugName;
    }

    @PropertyName("days_remaining")
    public int getDaysRemaining() {
        return daysRemaining;
    }

    @PropertyName("days_remaining")
    public void setDaysRemaining(int daysRemaining) {
        this.daysRemaining = daysRemaining;
    }
}

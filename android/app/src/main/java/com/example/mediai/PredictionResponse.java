package com.example.mediai;
import com.google.gson.annotations.SerializedName;

public class PredictionResponse {
    @SerializedName("class")
    private String predictedClass;

//    @SerializedName("confidence")
//    private float confidence;

    private String scientific_name;
    private String description;
    private String usage;
    private String dosage;

    // Getters and setters
    public String getPredictedClass() {
        return predictedClass;
    }

    public void setPredictedClass(String predictedClass) {
        this.predictedClass = predictedClass;
    }

//    public float getConfidence() {
//        return confidence;
//    }
//
//    public void setConfidence(float confidence) {
//        this.confidence = confidence;
//    }

    public String getScientificName() {
        return scientific_name;
    }

    public void setScientificName(String scientificName) {
        this.scientific_name = scientificName;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public String getUsage() {
        return usage;
    }

    public void setUsage(String usage) {
        this.usage = usage;
    }

    public String getDosage() {
        return dosage;
    }

    public void setDosage(String dosage) {
        this.dosage = dosage;
    }
}


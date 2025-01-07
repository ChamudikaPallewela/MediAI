import os
import pandas as pd
from fastapi import FastAPI, File, UploadFile, HTTPException
from fastapi.middleware.cors import CORSMiddleware
import uvicorn
import numpy as np
from io import BytesIO
from PIL import Image
import tensorflow as tf

from pydantic import BaseModel
import joblib
import re
import gpt_2_simple as gpt2
import openai
from sklearn.linear_model import LogisticRegression
from textblob import TextBlob
from typing import List, Dict
import datetime
from typing import List, Optional
from pydantic import BaseModel
import asyncpg

# Set your OpenAI API key here
openai.api_key = "api key"


app = FastAPI()

origins = [
    "http://localhost",
    "http://localhost:3000",
    "https://localhost",
]
app.add_middleware(
    CORSMiddleware,
    allow_origins=origins,
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

MODEL = tf.keras.models.load_model("D:/MediAI/Models/models/ENB0.h5")
CLASS_NAMES = ["Albendazole", "Amoxicillin", "Beclovent dry powder capsule", "Bisacodyl", "Cephalexin", "Cetirizine", "Cypro", "Dexamethasone", "Diclofenac", "Domperidone", "Famotidine", "Flutihale dry powder capsule", "Folic acid", "Iprahale dry powder capsule", "Losartan", "Metformin", "Nifitepin", "Not Medicine", "Omeprazole", "Paracetamol", "Piriton", "Propranolol", "Salbutamol", "Salhale dry powder capsule", "Vitamin C", "Vitamin B-complex", "gastro-resistant tablets"]

## Define drug descriptions and scientific names
drug_info = {
    "Albendazole": {
        "scientific_name": "Albendazole",
        "description": "Albendazole is used to treat infections caused by worms.",
        "usage": "Take as directed by your healthcare provider.",
        "dosage": "adult: 400 mg once a day , child: 200 mg once a day"
    },
    "Amoxicillin": {
        "scientific_name": "Amoxicillin",
        "description": "Amoxicillin is an antibiotic used to treat a variety of bacterial infections.",
        "usage": "Take as prescribed by your doctor.",
        "dosage": "adult: 500 mg every 8 hours, child: 20-40 mg/kg/day in divided doses"
    },
    "Beclovent dry powder capsule": {
        "scientific_name": "Beclomethasone dipropionate",
        "description": "Beclovent is used for the maintenance treatment of asthma.",
        "usage": "Inhale as directed by your healthcare provider.",
        "dosage": "adult : 1-2 inhalations twice daily, child : 1 inhalation twice daily"
    },
    "Bisacodyl": {
        "scientific_name": "Bisacodyl",
        "description": "Bisacodyl is a laxative used to treat constipation.",
        "usage": "Take as directed by your healthcare provider.",
        "dosage": "adult: 5-15 mg orally once a day, child: 5 mg orally once a day"
    },
    "Cephalexin": {
        "scientific_name": "Cephalexin",
        "description": "Cephalexin is an antibiotic used to treat bacterial infections.",
        "usage": "Take as prescribed by your healthcare provider.",
        "dosage": "adult: 500 mg every 6 hours, child: 25-50 mg/kg/day in divided doses"
    },
    "Cetirizine": {
        "scientific_name": "Cetirizine",
        "description": "Cetirizine is an antihistamine used to relieve allergy symptoms.",
        "usage": "Take as needed for allergy relief.",
        "dosage": "adult: 10 mg once a day, child: 5-10 mg once a day"
    },
    "Cypro": {
        "scientific_name": "Cyproheptadine",
        "description": "Cyproheptadine is an antihistamine used to relieve allergy symptoms and stimulate appetite.",
        "usage": "Take as directed by your healthcare provider.",
        "dosage": "adult: 4 mg 2-3 times a day, child: 2 mg 2-3 times a day"
    },
    "Dexamethasone": {
        "scientific_name": "Dexamethasone",
        "description": "Dexamethasone is a corticosteroid used to treat inflammation and other conditions.",
        "usage": "Take as directed by your healthcare provider.",
        "dosage": "adult: 0.75-9 mg per day in divided doses, child: 0.02-0.3 mg/kg/day in divided doses"
    },
    "Diclofenac": {
        "scientific_name": "Diclofenac",
        "description": "Diclofenac is a nonsteroidal anti-inflammatory drug (NSAID) used to treat pain and inflammation.",
        "usage": "Take as directed by your healthcare provider.",
        "dosage": "adult: 50 mg 2-3 times a day,child: 1-3 mg/kg/day in divided doses"
    },
    "Domperidone": {
        "scientific_name": "Domperidone",
        "description": "Domperidone is used to treat nausea and vomiting.",
        "usage": "Take as directed by your healthcare provider.",
        "dosage": "adult: 10 mg 3 times a day, child: 0.25-0.5 mg/kg 3 times a day"
    },
    "Famotidine": {
        "scientific_name": "Famotidine",
        "description": "Famotidine is used to treat and prevent ulcers and to treat gastroesophageal reflux disease (GERD).",
        "usage": "Take as directed by your healthcare provider.",
        "dosage":
            "adult: 20-40 mg once a day, child: 0.5 mg/kg/day in divided doses"

    },
    "Flutihale dry powder capsule": {
        "scientific_name": "Fluticasone propionate",
        "description": "Flutihale is used for the maintenance treatment of asthma.",
        "usage": "Inhale as directed by your healthcare provider.",
        "dosage": "adult: 1-2 inhalations twice daily,child: 1 inhalation twice daily"
    },
    "Folic acid": {
        "scientific_name": "Folic acid",
        "description": "Folic acid is a type of B vitamin used to treat or prevent folate deficiency.",
        "usage": "Take as directed by your healthcare provider.",
        "dosage": "adult: 400-800 mcg once a day ,child : 100-400 mcg once a day"
    },
    "Iprahale dry powder capsule": {
        "scientific_name": "Ipratropium bromide",
        "description": "Iprahale is used to treat chronic obstructive pulmonary disease (COPD).",
        "usage": "Inhale as directed by your healthcare provider.",
        "dosage": "adult: 2 inhalations 4 times a day, child: 1 inhalation 3-4 times a day"
    },
    "Losartan": {
        "scientific_name": "Losartan",
        "description": "Losartan is used to treat high blood pressure and protect the kidneys from damage due to diabetes.",
        "usage": "Take as directed by your healthcare provider.",
        "dosage": "adult: 50 mg once a day ,child: 0.7 mg/kg once a day"
    },
    "Metformin": {
        "scientific_name": "Metformin",
        "description": "Metformin is used to treat type 2 diabetes.",
        "usage": "Take as directed by your healthcare provider.",
        "dosage": "adult: 500-1000 mg twice a day, child: 500 mg twice a day"

    },
    "Nifitepin": {
        "scientific_name": "Nifedipine",
        "description": "Nifedipine is used to treat high blood pressure and angina (chest pain).",
        "usage": "Take as directed by your healthcare provider.",
        "dosage": "adult: 30-60 mg once a day, child: Not typically used in children"
    },
    "Not Medicine": {
        "scientific_name": "N/A",
        "description": "The uploaded item is not identified as a medicine.",
        "usage": "N/A",
        "dosage": "adult: N/A, child: N/A"
    },
    "Omeprazole": {
        "scientific_name": "Omeprazole",
        "description": "Omeprazole is used to treat gastroesophageal reflux disease (GERD) and other stomach acid conditions.",
        "usage": "Take as directed by your healthcare provider.",
        "dosage": "adult: 20 mg once a day, child: 1 mg/kg once a day"

    },
    "Paracetamol": {
        "scientific_name": "Acetaminophen",
        "description": "Paracetamol is used to treat pain and fever.",
        "usage": "Take as needed for pain or fever.",
        "dosage": "adult: 500-1000 mg every 4-6 hours, child: 10-15 mg/kg every 4-6 hours"
    },
    "Piriton": {
        "scientific_name": "Chlorphenamine",
        "description": "Piriton is used to relieve allergy symptoms.",
        "usage": "Take as needed for allergy relief.",
        "dosage": "adult: 4 mg every 4-6 hours, child: 2 mg every 4-6 hours"
    },
    "Propranolol": {
        "scientific_name": "Propranolol",
        "description": "Propranolol is used to treat high blood pressure, irregular heartbeats, and other conditions.",
        "usage": "Take as directed by your healthcare provider.",
        "dosage": "adult: 40 mg twice a day, child: 0.5-1 mg/kg/day in divided doses"
    },
    "Salbutamol": {
        "scientific_name": "Albuterol",
        "description": "Salbutamol is used to treat wheezing and shortness of breath caused by breathing problems.",
        "usage": "Inhale as directed by your healthcare provider.",
        "dosage":
            "adult: 2 inhalations every 4-6 hours, child: 1-2 inhalations every 4-6 hours"
    },
    "Salhale dry powder capsule": {
        "scientific_name": "Salmeterol",
        "description": "Salhale is used to prevent asthma attacks and chronic obstructive pulmonary disease (COPD) symptoms.",
        "usage": "Inhale as directed by your healthcare provider.",
        "dosage": "adult: 1 inhalation twice a day, child: 1 inhalation twice a day"
    },
    "Vitamin C": {
        "scientific_name": "Ascorbic acid",
        "description": "Vitamin C is used to prevent or treat low levels of vitamin C.",
        "usage": "Take as directed by your healthcare provider.",
        "dosage": "adult: 500-1000 mg once a day, child: 50-100 mg once a day"
    },
    "Vitamin B-complex": {
        "scientific_name": "Vitamin B-complex",
        "description": "Vitamin B-complex supplements are used to treat or prevent vitamin B deficiencies.",
        "usage": "Take as directed by your healthcare provider.",
        "dosage": {
            "adult: 1-2 tablets once a day, child: 1 tablet once a day"
        }
    },
    "gastro-resistant tablets": {
        "scientific_name": "gastro-resistant tablets",
        "description": "Gastro-resistant tablets are designed to resist the acidic environment of the stomach.",
        "usage": "Take as directed by your healthcare provider.",
        "dosage": "adult: 2 pills per day"
    }
}


@app.get("/ping")
async def ping():
    return "Hello, I am alive"

def read_file_as_image(data) -> np.ndarray:
    image = np.array(Image.open(BytesIO(data)))
    return image

def resize_image(image, size=(224, 224)):
    return np.array(Image.fromarray(image).resize(size))


@app.post("/predict")
async def predict(file: UploadFile = File(...)):
    image = read_file_as_image(await file.read())
    resized_image = resize_image(image)

    img_batch = np.expand_dims(resized_image, 0)
    predictions = MODEL.predict(img_batch)
    predicted_class = CLASS_NAMES[np.argmax(predictions[0])]
    confidence = np.max(predictions[0])

    # Get drug information
    scientific_name = drug_info.get(predicted_class, {}).get("scientific_name", "Scientific name not available.")
    description = drug_info.get(predicted_class, {}).get("description", "Description not available.")
    usage = drug_info.get(predicted_class, {}).get("usage", "Usage information not available.")
    dosage = drug_info.get(predicted_class, {}).get("dosage", {})

    return {
        'class': predicted_class,
        'scientific_name': scientific_name,
        'description': description,
        'usage': usage,
        'dosage': dosage,
    }


conversation_history: List[Dict[str, str]] = []


class ChatRequest(BaseModel):
    message: str


class ChatResponse(BaseModel):
    response: str


def validate_symptom_with_chatgpt(user_input):
    prompt = (
        f"Does the following text describe a medical symptom or a request for advice on dealing with a symptom? '{user_input}'. "
        "Reply with 'yes' if it is a symptom or a request for advice, and 'no' if it is not."
    )
    response = openai.ChatCompletion.create(
        model="gpt-3.5-turbo",
        messages=[
            {"role": "user", "content": prompt}
        ],
        max_tokens=10
    )
    return response.choices[0].message['content'].strip().lower() == 'yes'


async def generate_gratitude_response():
    prompt = "Generate a friendly and polite gratitude response for a user who says 'thank you'."
    response = await openai.ChatCompletion.acreate(
        model="gpt-3.5-turbo",
        messages=[
            {"role": "system", "content": "You are a helpful assistant."},
            {"role": "user", "content": prompt}
        ],
        max_tokens=50
    )
    return response['choices'][0]['message']['content'].strip()


@app.post("/chat", response_model=ChatResponse)
async def chat_with_user(chat_request: ChatRequest):
    message = chat_request.message

    # Add user message to history
    conversation_history.append({"role": "user", "content": message})

    # Check for greetings
    if any(word in message.lower() for word in ["hi", "hello"]):
        response_message = "Hello! I'm MediBot. How can I assist you today?"

    # Check for gratitude messages
    elif any(word in message.lower() for word in ["thank you", "thanks"]):
        response_message = await generate_gratitude_response()

    # Validate if the message is related to a symptom or asking for advice
    elif not validate_symptom_with_chatgpt(message):
        response_message = "I'm here to assist with questions related to symptoms or advice on managing symptoms only. Please ask a related question."

    else:
        # Construct the prompt for diagnosis or advice
        prompt = (
            f"The user has described the following symptoms or asked for advice: {message}. "
            "Provide appropriate precautions, remedies, or general advice. Do not recommend any medication."
        )

        try:
            # Get the model's response
            response = await openai.ChatCompletion.acreate(
                model="gpt-3.5-turbo",
                messages=conversation_history + [
                    {"role": "user", "content": prompt}
                ],
                max_tokens=150
            )
            response_message = response['choices'][0]['message']['content'].strip()
        except Exception as e:
            raise HTTPException(status_code=500, detail=str(e))

    # Add the model's response to the history
    conversation_history.append({"role": "assistant", "content": response_message})

    return ChatResponse(response=response_message)


# PostgreSQL connection details
DATABASE_CONFIG = {
    "user": "postgres",
    "password": "aaAA12!@",
    "database": "mydb",
    "host": "127.0.0.1",
    "port": 5432,
}

class Patient(BaseModel):
    id: int
    patient_name: str
    clinic_id: int
    address: str
    phone_number: str
    id_number: str
    date_of_birth: datetime.date

class Prescription(BaseModel):
    id: int
    patient_id: int
    drug_id: int
    start_date: datetime.date
    end_date: datetime.date
    dosage: Optional[str] = None
    instructions: Optional[str] = None
    drug_name: str

class PatientResponse(BaseModel):
    patient: Patient
    prescriptions: List[Prescription]


# Startup event to create the connection pool
@app.on_event("startup")
async def startup():
    app.state.pool = await asyncpg.create_pool(**DATABASE_CONFIG)

# Shutdown event to close the connection pool
@app.on_event("shutdown")
async def shutdown():
    await app.state.pool.close()

@app.get("/api/patient/{patient_id}", response_model=PatientResponse)
async def get_patient(patient_id: int):
    try:
        async with app.state.pool.acquire() as connection:
            # Query the patient data
            patient_query = 'SELECT * FROM patients WHERE id = $1'
            patient_row = await connection.fetchrow(patient_query, patient_id)

            if not patient_row:
                raise HTTPException(status_code=404, detail="Patient not found")

            # Query the patient's prescriptions
            prescriptions_query = '''
                SELECT p.*, d.drug_name
                FROM prescriptions p
                JOIN drugs d ON p.drug_id = d.id
                WHERE p.patient_id = $1
                  AND p.end_date >= CURRENT_DATE
            '''
            prescriptions_rows = await connection.fetch(prescriptions_query, patient_id)

            # Create response data
            patient = Patient(**dict(patient_row))
            prescriptions = [Prescription(**dict(row)) for row in prescriptions_rows]

            response_data = PatientResponse(patient=patient, prescriptions=prescriptions)
            return response_data

    except Exception as error:
        print("Error executing query", error)
        raise HTTPException(status_code=500, detail="Internal Server Error")

if __name__ == "__main__":
    uvicorn.run(app, host='127.0.0.1', port=5000)

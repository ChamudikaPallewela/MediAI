
# MediAI: AI-Powered Healthcare Management System  

MediAI is an intelligent platform designed to enhance medication management, improve patient safety, and make healthcare more accessible. Leveraging cutting-edge technologies like Generative AI, Machine Learning and QR Code tracking, MediAI bridges the gap between patients and efficient healthcare management.

---

## Key Features  

- **AI-Powered Medicine Identification**  
  - Utilizes advanced Machine Learning models (EfficientNet, ResNet, DenseNet, MobileNet, etc.) for accurate medicine recognition via image analysis.  

- **Symptoms Analysis Chatbot**  
  - A generative AI chatbot (MediBot) powered by OpenAI GPT-3.5 Turbo for providing personalized symptom analysis and remedy recommendations.  

- **QR Code Tracking System**  
  - Streamlines prescription management with real-time QR-code-based tracking for dosage schedules, reminders, and inventory management.  

- **Cross-Platform Applications**  
  - A mobile app for patients and a web dashboard for healthcare providers, ensuring seamless interaction and data visualization.  

---

## Technologies Used  

### Programming Languages  
- Python  
- Java  
- JavaScript  
- HTML/CSS  

### Frameworks and Libraries  
- TensorFlow, PyTorch, Keras  
- FastAPI  
- Android Studio  
- Bootstrap  

### Databases  
- Firebase Realtime Database  
- MySQL  

### APIs  
- OpenAI GPT-3.5 Turbo  
- Infermedica API  
- Symptomate API  

### Tools  
- PowerBI for data visualization  
- Figma for UI/UX design  
- Ngrok for API testing and deployment  
- Uvicorn for FastAPI server deployment  

---

## System Architecture  

MediAI consists of the following components:  
1. **Mobile Application**: Built using Android Studio and connected to Firebase for user data, medicine identification, and prescription tracking.  
2. **Web Dashboard**: A web interface for healthcare providers to manage patients, prescriptions, and analytics.  
3. **Backend API**: Powered by FastAPI for communication between mobile/web applications and the database.  
4. **Machine Learning Models**: Deployed to perform accurate medicine identification based on image recognition.  
5. **QR Code Tracking**: Ensures real-time updates on prescription compliance and medicine availability.  

---

## Installation  

1. Clone this repository:  
   ```bash
   git clone https://github.com/yourusername/MediAI.git
   cd MediAI
   ```  

2. Install dependencies for the backend:  
   ```bash
   pip install -r requirements.txt
   ```  

3. Run the backend server:  
   ```bash
   uvicorn main:app --reload
   ```  

4. Set up Firebase:  
   - Create a Firebase project and configure the Realtime Database and Authentication.  
   - Add your Firebase configuration file (`google-services.json`) to the mobile app.  

5. Build the mobile application:  
   - Open the project in Android Studio.  
   - Sync Gradle and run the app on an emulator or physical device.  

---

## Usage  

- **Patients**:  
  - Install the mobile application and register your account.  
  - Use MediBot to analyze symptoms or identify medicines by uploading an image.  
  - Get reminders for your prescription schedule via QR code tracking.  

- **Healthcare Providers**:  
  - Access the web dashboard to manage patient records, prescriptions, and analytics.  

---

## Testing  

- **Unit Testing**: Conducted for machine learning models and backend APIs.  
- **Integration Testing**: Tested the communication between mobile apps, web dashboard, and backend API.  
- **Usability Testing**: Evaluated user experience with healthcare professionals and patients.  

---

## Future Enhancements  

- Expand the scope of symptom analysis with multi-lingual support.  
- Integrate voice-based interaction for the chatbot.  
- Enable interoperability with wearable health devices for real-time monitoring.  

---


## Acknowledgments  

Special thanks to the supervisors, healthcare professionals, and participants who contributed to the development and testing of MediAI.  

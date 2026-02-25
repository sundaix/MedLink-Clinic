# MedLink Clinic Website with Admin Dashboard

A complete healthcare clinic website with Firebase backend and PHP-based admin dashboard for easy content management without programming knowledge.

## 📁 File Structure

```
medlink-clinic/
├── index.html # Main public website
├── admin.php # Admin dashboard (PHP)
├── login.php # Admin login page
├── logout.php # Logout handler
├── firebase-php-api.php # PHP wrapper for Firebase
├── css/
│ ├── xstyle.css # Main website styles
│ └── admin-style.css # Admin dashboard styles
├── js/
│ ├── script.js # Main website JavaScript
│ ├── admin-script.js # Admin dashboard JavaScript
│ ├── email-service.js # EmailJS integration
│ └── firebase-config.js # Firebase configuration
├── images/
│ ├── MedLinkLogo.png # Clinic logo
│ └── doctorillustration.png # Doctor illustration
└── README.md # This file

## 🚀 Local Development Setup (XAMPP)

### Step 1: Install XAMPP
1. Download from [Apache Friends](https://www.apachefriends.org/)
2. Install with default settings
3. Locate `htdocs` folder:
   - Windows: `C:\xampp\htdocs\`
   - Mac: `/Applications/XAMPP/htdocs/`
   - Linux: `/opt/lampp/htdocs/`

### Step 2: Create Project Folder

C:\xampp\htdocs\medlink\

### Step 3: Copy All Files
Copy all your project files into the `medlink` folder maintaining the structure above.

### Step 4: Start XAMPP
1. Open XAMPP Control Panel
2. Start **Apache** server
3. (Optional) Start **MySQL** if needed

### Step 5: Access Website

Main website: http://localhost/medlink/
Admin panel: http://localhost/medlink/admin.php
Login page: http://localhost/medlink/login.php

## 🔥 Firebase Setup

### Step 1: Create Firebase Project
1. Go to [Firebase Console](https://console.firebase.google.com/)
2. Click "Add project"
3. Enter project name: "medlink-clinic"
4. Disable Google Analytics (optional)
5. Click "Create project"

### Step 2: Register Web App
1. Click **Web icon** (</>) in the Firebase console
2. Register app: "MedLink Clinic Web"
3. Click "Register app"
4. **Copy the firebaseConfig object** - you'll need this!

### Step 3: Enable Firebase Services

#### A. Authentication
1. Go to **Build → Authentication**
2. Click "Get started"
3. Go to **Sign-in method** tab
4. Enable **Email/Password**
5. Click "Save"

#### B. Realtime Database
1. Go to **Build → Realtime Database**
2. Click "Create Database"
3. Choose location (closest to you)
4. Start in **Test mode**
5. Click "Enable"
6. Go to **Rules** tab and paste:
```json
{
  "rules": {
    "appointments": {
      ".read": "auth != null",
      ".write": "auth != null",
      ".indexOn": ["date", "status"]
    },
    "doctors": {
      ".read": true,
      ".write": "auth != null"
    },
    "images": {
      ".read": true,
      ".write": "auth != null"
    },
    "content": {
      ".read": true,
      ".write": "auth != null"
    }
  }
}

Click "Publish"

C. Enable Storage (for images)
Go to Build → Storage

Click "Get started"

Start in Test mode

Click "Next" and "Done"

Go to Rules tab and paste:

rules_version = '2';
service firebase.storage {
  match /b/{bucket}/o {
    match /images/{allPaths=**} {
      allow read: if true;
      allow write: if request.auth != null;
    }
  }
}

Click "Publish"

Step 4: Configure Firebase in Your Code
Open js/firebase-config.js

Replace with your Firebase config:

// Your Firebase configuration
const firebaseConfig = {
  apiKey: "YOUR_API_KEY",
  authDomain: "your-project.firebaseapp.com",
  databaseURL: "https://your-project-default-rtdb.firebaseio.com",
  projectId: "your-project-id",
  storageBucket: "your-project.appspot.com",
  messagingSenderId: "123456789",
  appId: "1:123456789:web:abc123"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);
Step 5: Create Admin User in Firebase
Go to Authentication → Users

Click "Add user"

Enter:

Email: admin@medlinkclinic.com (or your preferred email)

Password: Choose a strong password

Click "Add user"

Save these credentials - you'll use them to login!

Step 6: Initialize Default Data (Optional)
In Firebase Console → Realtime Database, click the + icon next to your database name and add:
{
  "doctors": {
    "dr-karl": {
      "name": "Dr. Karl Go",
      "specialty": "General Medicine",
      "shift_start": "09:00",
      "shift_end": "21:00",
      "status": "active"
    },
    "dr-haniven": {
      "name": "Dr. Haniven Alfonso",
      "specialty": "Pediatrics",
      "shift_start": "08:00",
      "shift_end": "20:00",
      "status": "active"
    },
    "dr-jared": {
      "name": "Dr. Jared Vergara",
      "specialty": "Internal Medicine",
      "shift_start": "10:00",
      "shift_end": "22:00",
      "status": "active"
    },
    "dr-francine": {
      "name": "Dr. Francine Abayan",
      "specialty": "Family Medicine",
      "shift_start": "09:00",
      "shift_end": "21:00",
      "status": "active"
    }
  },
  "content": {
    "heroHeading": "Your Health, Our Priority",
    "heroDescription": "Your trusted healthcare clinic is now open 24/7! We're here for you every day, all day, with expert doctors and modern facilities.",
    "heroButtonText": "Book Appointment",
    "aboutHeading": "About MedLink Clinic",
    "aboutSubheading": "Your Trusted Healthcare Partner in Marikina",
    "aboutDescription": "At MedLink Clinic, we are dedicated to providing exceptional healthcare services tailored to meet the unique needs of our community. Our commitment extends beyond treatment to encompass comprehensive wellness and preventive care.",
    "aboutFeatures": [
      "Experienced Medical Professionals",
      "Modern Medical Equipment",
      "Patient-Centered Care",
      "Comprehensive Healthcare Services"
    ],
    "contactAddress": "123 Healthcare St., Marikina, Philippines",
    "contactPhone": "+63 905 517 7314",
    "contactEmail": "info.medlinkclinic@gmail.com",
    "footerCopyright": "2025 MedLink Clinic. All rights reserved."
  }
}
🎯 Using the Admin Dashboard
Login to Admin Panel
Go to http://localhost/medlink/login.php

Enter your Firebase admin credentials

You'll be redirected to admin.php

Dashboard Features
1. Dashboard Overview
View total appointments count

See pending appointments

Monitor doctors count

Track uploaded images

View recent appointments list

2. Appointments Management
View all appointments in sortable table

Filter by status: All, Pending, Confirmed, Cancelled

Confirm appointments (green checkmark)

Cancel appointments (red X)

View full appointment details (eye icon)

Smart cleanup of old appointments

3. Manage Images
Logo Image: Upload/update clinic logo (appears in navbar)

Doctor Illustration: Upload/update hero section image

Real-time preview of uploaded images

Supported formats: JPG, PNG, GIF, WebP

Max size: 5MB per image

4. Content Management
Hero Section:

Edit main heading

Edit description text

Edit call-to-action button text

About Section:

Update heading, subheading, description

Add/edit feature list items (checkmarks)

Contact Information:

Update address

Update phone number

Update email

Footer: Update copyright text

Specialties: Add new medical specialties

5. Doctor Management
Add new doctors with:

Full name

Specialty (select from dropdown)

Shift start time

Shift end time (must be 12-hour shift validation)

Edit existing doctors

Delete doctors

Filter doctors by specialty

View doctor status (active/inactive)

6. Appointment Archive
View past appointments

Smart cleanup tool to archive old appointments

Maintain history without cluttering active appointments

📱 Main Website Features
For Visitors:
Responsive Design: Works on mobile, tablet, and desktop

Service Cards: Interactive flip cards showing service details

Specialty Filtering: Filter services by medical specialty

Appointment Booking: Online form to schedule appointments

Doctor Information: View available doctors and their specialties

Contact Information: Address, phone, email, and social media links

Accessibility: Skip links, ARIA labels, keyboard navigation

Dynamic Content (Managed from Admin):
Logo updates automatically

Hero section text

About section content and features

Doctor list with shift schedules

Contact information

Medical specialties list

📧 Email Notifications (EmailJS Setup)
Step 1: Create EmailJS Account
Go to EmailJS.com

Sign up for a free account

Click "Add New Service"

Connect your email service (Gmail, Outlook, etc.)

Step 2: Create Email Template
Go to Email Templates

Click "Create New Template"

Design your appointment confirmation template

Save and note the Template ID

Step 3: Configure in email-service.js
// Initialize EmailJS
emailjs.init("YOUR_USER_ID");

// Template IDs
const CONFIRMATION_TEMPLATE = "template_your_template_id";
const NOTIFICATION_TEMPLATE = "template_your_notification_id";
🔒 Security Best Practices
Firebase Rules (Production)
Update your Realtime Database rules:
{
  "rules": {
    "appointments": {
      ".read": "auth != null",
      ".write": "auth != null",
      ".indexOn": ["date", "status"]
    },
    "doctors": {
      ".read": true,
      ".write": "auth != null"
    },
    "images": {
      ".read": true,
      ".write": "auth != null"
    },
    "content": {
      ".read": true,
      ".write": "auth != null"
    }
  }
}

Storage Rules (Production)

rules_version = '2';
service firebase.storage {
  match /b/{bucket}/o {
    match /images/{allPaths=**} {
      allow read: if true;
      allow write: if request.auth != null && 
                     request.resource.size < 5 * 1024 * 1024 &&
                     request.resource.contentType.matches('image/.*');
    }
  }
}

PHP Session Security
In login.php and admin.php, ensure:
session_start();
session_regenerate_id(true); // Prevent session fixation

🐛 Troubleshooting Guide
Problem: "Firebase is not defined"
Solution: Make sure Firebase SDKs are loaded in correct order:

<script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-auth-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-database-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-storage-compat.js"></script>

Problem: "Failed to load resource: net::ERR_FILE_NOT_FOUND"
Solution: Check file paths:

Use correct case sensitivity

Verify files exist in the specified locations

For XAMPP, ensure files are in htdocs/medlink/

Problem: Cannot login to admin panel
Solution:

Check Firebase Authentication for the user

Verify credentials are correct

Check browser console for errors

Ensure login.php has proper session handling

Problem: Images not uploading
Solution:

Check Firebase Storage rules

Verify file size (<5MB)

Check file type (must be image)

Ensure user is authenticated

Problem: Appointments not showing
Solution:

Check Firebase Database rules

Verify data structure matches

Check browser console for errors

Ensure user is authenticated

Problem: PHP session not persisting
Solution:

Ensure session_start() at beginning of PHP files

Check php.ini session save path

Verify cookies are enabled in browser

📝 Additional Configuration
Email Service Configuration
In js/email-service.js:

// Initialize with your EmailJS credentials
(function() {
    emailjs.init("YOUR_EMAILJS_USER_ID");
})();

// Template IDs
const TEMPLATE_IDS = {
    CONFIRMATION: "template_confirmation",
    NOTIFICATION: "template_notification",
    CANCELLATION: "template_cancellation"
};

PHP Firebase API Configuration
In firebase-php-api.php:
class FirebasePHP {
    private $databaseURL = "https://your-project-default-rtdb.firebaseio.com/";
    private $apiKey = "YOUR_API_KEY";
    
    // ... rest of the code
}

🌐 Deployment to Live Server
Option 1: Traditional Web Hosting
Purchase web hosting (e.g., HostGator, Bluehost, GoDaddy)

Upload all files via FTP

Ensure PHP version 7.4 or higher

Configure domain to point to your hosting

Option 2: Vercel/Netlify (for static files) + Separate PHP Hosting
Host PHP files on a PHP-compatible server

Host static files (HTML, CSS, JS) on Vercel/Netlify

Configure CORS if needed

Option 3: Firebase Hosting + Cloud Functions
Deploy static files to Firebase Hosting

Use Cloud Functions for PHP-like functionality

More complex but fully integrated

📊 Database Structure
Appointments Node
appointments/
  ├── appointment_id_1/
  │   ├── name: "John Doe"
  │   ├── email: "john@example.com"
  │   ├── phone: "09123456789"
  │   ├── doctor: "Dr. Karl Go"
  │   ├── date: "2025-03-15"
  │   ├── time: "10:00"
  │   ├── reason: "Check-up"
  │   ├── status: "pending"
  │   └── timestamp: 1647356400000
  └── appointment_id_2/
      └── ...
Doctors Node
doctors/
  ├── dr-karl/
  │   ├── name: "Dr. Karl Go"
  │   ├── specialty: "General Medicine"
  │   ├── shift_start: "09:00"
  │   ├── shift_end: "21:00"
  │   └── status: "active"
  └── dr-haniven/
      └── ...
Content Node
content/
  ├── heroHeading: "Your Health, Our Priority"
  ├── heroDescription: "..."
  ├── heroButtonText: "Book Appointment"
  ├── aboutHeading: "About MedLink Clinic"
  ├── aboutSubheading: "..."
  ├── aboutDescription: "..."
  ├── aboutFeatures: ["Feature 1", "Feature 2", ...]
  ├── contactAddress: "..."
  ├── contactPhone: "..."
  ├── contactEmail: "..."
  └── footerCopyright: "..."
🎨 Customization
Changing Colors
In css/xstyle.css, modify the CSS variables:
:root {
    --primary-color: #0d6efd;
    --secondary-color: #6c757d;
    --success-color: #198754;
    --bg-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
Adding New Specialties
In admin panel, go to Content Management

Scroll to "Manage Specialties"

Enter new specialty name

Click "Add Specialty"

Create corresponding service card in index.html

Modifying Service Cards
In index.html, copy and modify this template:
<div class="col-md-6 col-lg-4" data-specialty="Your Specialty">
    <div class="service-card h-100">
        <div class="service-card-inner">
            <div class="service-card-front">
                <div class="service-icon">
                    <i class="fas fa-icon-name"></i>
                </div>
                <h3 class="service-title h5">Service Name</h3>
                <p class="service-description">Description here</p>
                <div class="service-badge">Badge Text</div>
            </div>
            <div class="service-card-back">
                <h4 class="h6">What We Offer:</h4>
                <ul>
                    <li>Item 1</li>
                    <li>Item 2</li>
                    <li>Item 3</li>
                </ul>
                <button class="btn-book-specialty" data-specialty="Your Specialty">
                    Book Appointment
                </button>
            </div>
        </div>
    </div>
</div>
📄 License
This project is for educational purposes as part of a web design course. All rights reserved.

👥 Contributors
Project Lead: sundaix (Karl Go)

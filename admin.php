<?php
session_start();
require_once 'firebase-php-api.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$firebase = new FirebasePHP();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - MedLink Clinic</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="admin-style.css">
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">
      <i class="fas fa-hospital-user me-2"></i>
      MedLink Admin
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="adminNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <span class="nav-link">
            <i class="fas fa-user me-1"></i> <?php echo htmlspecialchars($_SESSION['admin_email']); ?>
          </span>
        </li>
        <li class="nav-item">
          <a href="https://health-medlinkclinic.wuaze.com/" class="btn btn-success btn-sm me-2" target="_blank">
            <i class="fas fa-external-link-alt me-1"></i> MedLink Clinic Website
          </a>
        </li>
        <li class="nav-item">
          <a href="logout.php" class="btn btn-outline-light btn-sm">
            <i class="fas fa-sign-out-alt me-1"></i> Logout
          </a>
        </li>
      </ul>
    </div>
  </div>  <!-- ADD THIS MISSING CLOSING DIV -->
</nav>
    <div class="container-fluid">
      <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 sidebar">
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link active" href="#" data-section="dashboard">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" data-section="appointments">
                <i class="fas fa-calendar-check me-2"></i> Appointments
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" data-section="images">
                <i class="fas fa-images me-2"></i> Manage Images
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" data-section="content">
                <i class="fas fa-edit me-2"></i> Edit Content
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" data-section="doctors">
                <i class="fas fa-user-md me-2"></i> Manage Doctors
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" data-section="archive">
                <i class="fas fa-archive me-2"></i> Appointment Archive
              </a>
            </li>
          </ul>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 main-content">
          <!-- Dashboard Section -->
          <div id="dashboardSection" class="content-section">
            <h2 class="mb-4">Dashboard Overview</h2>
            <div class="row g-4">
              <div class="col-md-3">
                <div class="stat-card">
                  <div class="stat-icon bg-primary">
                    <i class="fas fa-calendar-check"></i>
                  </div>
                  <div class="stat-details">
                    <h3 id="totalAppointments">0</h3>
                    <p>Total Appointments</p>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="stat-card">
                  <div class="stat-icon bg-success">
                    <i class="fas fa-clock"></i>
                  </div>
                  <div class="stat-details">
                    <h3 id="pendingAppointments">0</h3>
                    <p>Pending</p>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="stat-card">
                  <div class="stat-icon bg-info">
                    <i class="fas fa-user-md"></i>
                  </div>
                  <div class="stat-details">
                    <h3 id="totalDoctors">4</h3>
                    <p>Doctors</p>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="stat-card">
                  <div class="stat-icon bg-warning">
                    <i class="fas fa-images"></i>
                  </div>
                  <div class="stat-details">
                    <h3 id="totalImages">0</h3>
                    <p>Images Uploaded</p>
                  </div>
                </div>
              </div>
            </div>

            <div class="row mt-4">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h5 class="mb-0">Recent Appointments</h5>
                  </div>
                  <div class="card-body">
                    <div id="recentAppointmentsList"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Appointments Section -->
          <div id="appointmentsSection" class="content-section d-none">
            <h2 class="mb-4">Appointments Management</h2>
            <div class="d-flex justify-content-between align-items-center mb-3">
              <div>
                <button class="btn btn-outline-primary filter-btn active" onclick="filterAppointments('all')">
                  <i class="fas fa-list me-1"></i> All
                </button>
                <button class="btn btn-outline-warning filter-btn" onclick="filterAppointments('pending')">
                  <i class="fas fa-clock me-1"></i> Pending
                </button>
                <button class="btn btn-outline-success filter-btn" onclick="filterAppointments('confirmed')">
                  <i class="fas fa-check-circle me-1"></i> Confirmed
                </button>
                <button class="btn btn-outline-danger filter-btn" onclick="filterAppointments('cancelled')">
                  <i class="fas fa-times-circle me-1"></i> Cancelled
                </button>
              </div>
              <button class="btn btn-outline-secondary btn-sm" onclick="smartCleanupAppointments()" title="Clean up old appointments">
                <i class="fas fa-broom me-1"></i> Clean Up Old
              </button>
            </div>
            
            <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody id="appointmentsTableBody">
                      <tr>
                        <td colspan="7" class="text-center">Loading appointments...</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- Images Section -->
          <div id="imagesSection" class="content-section d-none">
            <h2 class="mb-4">Manage Website Images</h2>
            
            <div class="row g-4">
              <div class="col-md-6">
                <div class="card">
                  <div class="card-header">
                    <h5 class="mb-0">Logo Image</h5>
                  </div>
                  <div class="card-body">
                    <div class="image-preview mb-3" id="logoPreview">
                      <img src="MedLinkLogo.png" alt="Logo" class="img-fluid">
                    </div>
                    <input type="file" class="form-control mb-2" id="logoUpload" accept="image/*">
                    <button class="btn btn-primary" onclick="uploadImage('logo', 'logoUpload')">
                      <i class="fas fa-upload me-1"></i> Upload Logo
                    </button>
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="card">
                  <div class="card-header">
                    <h5 class="mb-0">Doctor Illustration</h5>
                  </div>
                  <div class="card-body">
                    <div class="image-preview mb-3" id="doctorIllustrationPreview">
                      <img src="doctorillustration.png" alt="Doctor" class="img-fluid">
                    </div>
                    <input type="file" class="form-control mb-2" id="doctorUpload" accept="image/*">
                    <button class="btn btn-primary" onclick="uploadImage('doctorIllustration', 'doctorUpload')">
                      <i class="fas fa-upload me-1"></i> Upload Image
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Content Management Section (IMPROVED) -->
          <div id="contentSection" class="content-section d-none">
            <h2 class="mb-4">Content Management</h2>
            
            <!-- Quick Actions -->
            <div class="card mb-4">
              <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-save me-2"></i>Quick Actions</h5>
                <button class="btn btn-light btn-sm" onclick="saveAllContent()" id="saveAllContentBtn">
                  <i class="fas fa-save me-1"></i> Save All Changes
                </button>
              </div>
            </div>

            <!-- Hero Section -->
            <div class="card mb-4">
              <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-home me-2 text-primary"></i>Hero Section</h5>
              </div>
              <div class="card-body">
                <div class="mb-3">
                  <label class="form-label fw-bold">Main Heading</label>
                  <input type="text" class="form-control" id="heroHeading" placeholder="Your Health, Our Priority">
                  <div class="form-text">This appears as the main headline on your homepage</div>
                </div>
                <div class="mb-3">
                  <label class="form-label fw-bold">Description</label>
                  <textarea class="form-control" id="heroDescription" rows="3" placeholder="Providing trusted healthcare services..."></textarea>
                  <div class="form-text">Supporting text below the main heading</div>
                </div>
                <div class="mb-3">
                  <label class="form-label fw-bold">Call-to-Action Button Text</label>
                  <input type="text" class="form-control" id="heroButtonText" placeholder="Book Appointment">
                  <div class="form-text">Text displayed on the main action button</div>
                </div>
              </div>
            </div>

            <!-- About Section (NEW) -->
            <div class="card mb-4">
              <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2 text-primary"></i>About Section</h5>
              </div>
              <div class="card-body">
                <div class="mb-3">
                  <label class="form-label fw-bold">About Heading</label>
                  <input type="text" class="form-control" id="aboutHeading" placeholder="About MedLink Clinic">
                  <div class="form-text">Main heading for the About section</div>
                </div>
                <div class="mb-3">
                  <label class="form-label fw-bold">Subheading</label>
                  <input type="text" class="form-control" id="aboutSubheading" placeholder="Your Trusted Healthcare Partner">
                  <div class="form-text">Highlighted subheading text</div>
                </div>
                <div class="mb-3">
                  <label class="form-label fw-bold">Description</label>
                  <textarea class="form-control" id="aboutDescription" rows="4" placeholder="At MedLink Clinic, we are dedicated to..."></textarea>
                  <div class="form-text">Main descriptive text for the About section</div>
                </div>
                
                <!-- About Features -->
                <div class="mb-3">
                  <label class="form-label fw-bold">Feature Items</label>
                  <div id="aboutFeaturesList" class="mb-3">
                    <!-- Features will be loaded here -->
                  </div>
                  <div class="input-group">
                    <input type="text" class="form-control" id="newAboutFeature" placeholder="Enter new feature (e.g., Experienced Medical Professionals)">
                    <button class="btn btn-success" onclick="addAboutFeature()">
                      <i class="fas fa-plus me-1"></i> Add Feature
                    </button>
                  </div>
                  <div class="form-text">These appear as checkmark items in the About section</div>
                </div>
              </div>
            </div>

            <!-- Contact Information -->
            <div class="card mb-4">
              <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-phone me-2 text-primary"></i>Contact Information</h5>
              </div>
              <div class="card-body">
                <div class="mb-3">
                  <label class="form-label fw-bold">Address</label>
                  <input type="text" class="form-control" id="contactAddress" placeholder="123 Healthcare St., Marikina, Philippines">
                  <div class="form-text">Your clinic's physical address</div>
                </div>
                <div class="mb-3">
                  <label class="form-label fw-bold">Phone</label>
                  <input type="text" class="form-control" id="contactPhone" placeholder="+63 905 517 7314">
                  <div class="form-text">Primary contact phone number</div>
                </div>
                <div class="mb-3">
                  <label class="form-label fw-bold">Email</label>
                  <input type="email" class="form-control" id="contactEmail" placeholder="info.medlinkclinic@gmail.com">
                  <div class="form-text">Contact email address</div>
                </div>
              </div>
            </div>

            <!-- Footer Content (NEW) -->
            <div class="card mb-4">
              <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-copyright me-2 text-primary"></i>Footer Content</h5>
              </div>
              <div class="card-body">
                <div class="mb-3">
                  <label class="form-label fw-bold">Copyright Text</label>
                  <input type="text" class="form-control" id="footerCopyright" placeholder="© 2025 MedLink Clinic. All rights reserved.">
                  <div class="form-text">Copyright notice displayed in the footer</div>
                </div>
              </div>
            </div>

            <!-- Specialty Management -->
            <div class="card">
              <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-stethoscope me-2 text-primary"></i>Manage Specialties & Services</h5>
              </div>
              <div class="card-body">
                <!-- Add New Specialty -->
                <div class="row mb-4">
                  <div class="col-md-8">
                    <input type="text" class="form-control" id="newSpecialtyInput" placeholder="Enter new specialty (e.g., Pediatrics)">
                  </div>
                  <div class="col-md-4">
                    <button class="btn btn-success w-100" onclick="addSpecialty()">
                      <i class="fas fa-plus me-1"></i> Add Specialty
                    </button>
                  </div>
                </div>

                <!-- Current Specialties -->
                <h6 class="fw-bold">Current Specialties</h6>
                <div id="specialtiesManagementList" class="specialties-management-list">
                  <!-- Specialties will be loaded here dynamically -->
                </div>
              </div>
            </div>
          </div>

          <!-- Archive Section -->
          <div id="archiveSection" class="content-section d-none">
            <h2 class="mb-4">Appointment Archive</h2>
            
            <div class="card">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Archived Appointments (Past Dates)</h5>
              </div>
              <div class="card-body">
                <div id="appointmentArchive">
                  <!-- Archive content will be loaded here -->
                </div>
              </div>
            </div>
          </div>

          <!-- Doctors Section -->
          <div id="doctorsSection" class="content-section d-none">
            <h2 class="mb-4">Manage Doctors</h2>
            
            <div class="d-flex justify-content-between align-items-center mb-3">
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDoctorModal">
                <i class="fas fa-plus me-1"></i> Add New Doctor
              </button>
            </div>

            <!-- Specialty Filters -->
            <div id="specialtyFilters" class="mb-3">
              <!-- Filters will be loaded here dynamically -->
            </div>

            <div class="row g-4" id="doctorsList">
              <!-- Doctors will be loaded here -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Add/Edit Doctor Modal -->
  <div class="modal fade" id="addDoctorModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="doctorModalTitle">Add New Doctor</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="addDoctorForm">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Doctor Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="doctorName" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Specialty <span class="text-danger">*</span></label>
                <select class="form-select" id="doctorSpecialty" required>
                  <option value="">Select Specialty</option>
                  <!-- Options loaded dynamically -->
                </select>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Shift Start Time <span class="text-danger">*</span></label>
                <select class="form-select" id="doctorShiftStart" required>
                  <option value="">Select Start Time</option>
                  <option value="00:00">12:00 AM</option>
                  <option value="01:00">1:00 AM</option>
                  <option value="02:00">2:00 AM</option>
                  <option value="03:00">3:00 AM</option>
                  <option value="04:00">4:00 AM</option>
                  <option value="05:00">5:00 AM</option>
                  <option value="06:00">6:00 AM</option>
                  <option value="07:00">7:00 AM</option>
                  <option value="08:00">8:00 AM</option>
                  <option value="09:00">9:00 AM</option>
                  <option value="10:00">10:00 AM</option>
                  <option value="11:00">11:00 AM</option>
                  <option value="12:00">12:00 PM</option>
                  <option value="13:00">1:00 PM</option>
                  <option value="14:00">2:00 PM</option>
                  <option value="15:00">3:00 PM</option>
                  <option value="16:00">4:00 PM</option>
                  <option value="17:00">5:00 PM</option>
                  <option value="18:00">6:00 PM</option>
                  <option value="19:00">7:00 PM</option>
                  <option value="20:00">8:00 PM</option>
                  <option value="21:00">9:00 PM</option>
                  <option value="22:00">10:00 PM</option>
                  <option value="23:00">11:00 PM</option>
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Shift End Time <span class="text-danger">*</span></label>
                <select class="form-select" id="doctorShiftEnd" required>
                  <option value="">Select End Time</option>
                  <option value="00:00">12:00 AM</option>
                  <option value="01:00">1:00 AM</option>
                  <option value="02:00">2:00 AM</option>
                  <option value="03:00">3:00 AM</option>
                  <option value="04:00">4:00 AM</option>
                  <option value="05:00">5:00 AM</option>
                  <option value="06:00">6:00 AM</option>
                  <option value="07:00">7:00 AM</option>
                  <option value="08:00">8:00 AM</option>
                  <option value="09:00">9:00 AM</option>
                  <option value="10:00">10:00 AM</option>
                  <option value="11:00">11:00 AM</option>
                  <option value="12:00">12:00 PM</option>
                  <option value="13:00">1:00 PM</option>
                  <option value="14:00">2:00 PM</option>
                  <option value="15:00">3:00 PM</option>
                  <option value="16:00">4:00 PM</option>
                  <option value="17:00">5:00 PM</option>
                  <option value="18:00">6:00 PM</option>
                  <option value="19:00">7:00 PM</option>
                  <option value="20:00">8:00 PM</option>
                  <option value="21:00">9:00 PM</option>
                  <option value="22:00">10:00 PM</option>
                  <option value="23:00">11:00 PM</option>
                </select>
              </div>
            </div>
            
            <div class="alert alert-info" id="shiftValidation">
              <i class="fas fa-info-circle me-2"></i>
              <span id="shiftDuration">Please select shift times (must be 12 hours)</span>
            </div>
            
            <input type="hidden" id="doctorId">
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" onclick="saveDoctor()" id="saveDoctorBtn">
            <i class="fas fa-save me-1"></i> Save Doctor
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Notification Toast -->
  <div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="notificationToast" class="toast" role="alert">
      <div class="toast-header">
        <strong class="me-auto">Notification</strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
      </div>
      <div class="toast-body"></div>
    </div>
  </div>

  <!-- Add this script right before </body> in admin.php -->
<script>
// Initialize admin functionality
document.addEventListener('DOMContentLoaded', function() {
    console.log('Admin PHP loaded - initializing...');
    
    // Initialize navigation
    const navLinks = document.querySelectorAll('.sidebar .nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Update active state
            navLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
            
            // Show/hide sections
            const sectionName = this.getAttribute('data-section');
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.add('d-none');
            });
            
            const targetSection = document.getElementById(sectionName + 'Section');
            if (targetSection) {
                targetSection.classList.remove('d-none');
            }
            
            // Load section data
            loadSectionData(sectionName);
        });
    });
    
    // Load dashboard data
    loadDashboardData();
    
    // Setup logout - FIXED: Check if element exists
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function() {
            window.location.href = 'logout.php';
        });
    }
});

// Load section-specific data
function loadSectionData(sectionName) {
    switch(sectionName) {
        case 'dashboard':
            loadDashboardData();
            break;
        case 'appointments':
            loadAllAppointments();
            break;
        case 'images':
            loadImages();
            break;
        case 'content':
            loadUnifiedContent();
            break;
        case 'doctors':
            loadDoctors();
            loadSpecialtiesForDropdown();
            loadSpecialtyFilters();
            break;
        case 'archive':
            loadAppointmentArchive();
            break;
    }
}

// Force reload doctors data
function loadDoctors() {
    console.log('Loading doctors...');
    const container = document.getElementById('doctorsList');
    if (!container) {
        console.error('Doctors container not found!');
        return;
    }
    
    container.innerHTML = '<div class="col-12 text-center"><p>Loading doctors...</p></div>';

    // Use Firebase directly to load doctors
    firebase.database().ref('doctors').once('value')
        .then(snapshot => {
            const doctors = snapshot.val() || {};
            console.log('Doctors found:', Object.keys(doctors).length);
            
            if (Object.keys(doctors).length === 0) {
                container.innerHTML = '<div class="col-12 text-center"><p class="text-muted">No doctors added yet.</p></div>';
                return;
            }

            let html = '';
            Object.entries(doctors).forEach(([id, doctor]) => {
                const shiftInfo = doctor.shift_start && doctor.shift_end 
                    ? `${doctor.shift_start} - ${doctor.shift_end}`
                    : 'No shift set';
                
                html += `
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">${doctor.name}</h5>
                                <p class="card-text">
                                    <strong>Specialty:</strong> ${doctor.specialty}<br>
                                    ${doctor.email ? `<strong>Email:</strong> ${doctor.email}<br>` : ''}
                                    ${doctor.phone ? `<strong>Phone:</strong> ${doctor.phone}<br>` : ''}
                                    <strong>Shift:</strong> ${shiftInfo}<br>
                                    <strong>Status:</strong> 
                                    <span class="badge ${doctor.status === 'active' ? 'bg-success' : 'bg-secondary'}">
                                        ${doctor.status || 'active'}
                                    </span>
                                </p>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-sm btn-outline-primary me-1" onclick="editDoctor('${id}', '${doctor.name.replace(/'/g, "\\'")}', '${doctor.specialty}', '${doctor.shift_start || ''}', '${doctor.shift_end || ''}')">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteDoctor('${id}', '${doctor.name.replace(/'/g, "\\'")}')">
                                    <i class="fas fa-trash me-1"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });
            container.innerHTML = html;
        })
        .catch(error => {
            console.error('Error loading doctors:', error);
            container.innerHTML = '<div class="col-12 text-center"><p class="text-danger">Error loading doctors.</p></div>';
        });
}

// Load dashboard data
function loadDashboardData() {
    console.log('Loading dashboard data...');
    
    // Load appointments count
    firebase.database().ref('appointments').once('value')
        .then(snapshot => {
            const appointments = snapshot.val() || {};
            const appointmentsArray = Object.values(appointments);
            
            document.getElementById('totalAppointments').textContent = appointmentsArray.length;
            document.getElementById('pendingAppointments').textContent = 
                appointmentsArray.filter(a => a.status === 'pending').length;
            
            loadRecentAppointments(appointmentsArray);
        });
    
    // Load doctors count
    firebase.database().ref('doctors').once('value')
        .then(snapshot => {
            const doctors = snapshot.val() || {};
            document.getElementById('totalDoctors').textContent = Object.keys(doctors).length;
        });
    
    // Load images count
    firebase.database().ref('images').once('value')
        .then(snapshot => {
            const images = snapshot.val() || {};
            document.getElementById('totalImages').textContent = Object.keys(images).length;
        });
}

// Load recent appointments
function loadRecentAppointments(appointments) {
    const container = document.getElementById('recentAppointmentsList');
    
    if (!appointments || appointments.length === 0) {
        container.innerHTML = '<p class="text-muted text-center">No appointments yet.</p>';
        return;
    }
    
    const sortedAppointments = appointments
        .sort((a, b) => new Date(b.timestamp) - new Date(a.timestamp))
        .slice(0, 5);
    
    let html = '<div class="list-group">';
    sortedAppointments.forEach(apt => {
        const statusBadge = getStatusBadge(apt.status || 'pending');
        html += `
            <div class="list-group-item">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">${apt.name}</h6>
                        <small class="text-muted">${apt.doctor} - ${apt.date} at ${apt.time}</small>
                    </div>
                    ${statusBadge}
                </div>
            </div>
        `;
    });
    html += '</div>';
    
    container.innerHTML = html;
}

function getStatusBadge(status) {
    const badges = {
        'pending': '<span class="badge bg-warning">Pending</span>',
        'confirmed': '<span class="badge bg-success">Confirmed</span>',
        'cancelled': '<span class="badge bg-danger">Cancelled</span>'
    };
    return badges[status] || badges['pending'];
}
</script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Firebase SDKs -->
  <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js"></script>
  <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-auth-compat.js"></script>
  <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-database-compat.js"></script>
  <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-storage.js"></script>

  <!-- Firebase config and Admin script -->
  <script src="firebase-config.js"></script>
  <script src="admin-script.js"></script>
</div>
</body>
</html>
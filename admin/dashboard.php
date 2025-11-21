<?php
// admin/dashboard_simple.php - Panel de administración sin DataTables

// Verificar autenticación
session_start();
require_once '../config.php';

// Función simple de verificación de autenticación
function check_admin_auth() {
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header('Location: login.php');
        exit;
    }
}

check_admin_auth();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Appointment Management</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #005A9C;
            --secondary-color: #FDB813;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--primary-color) 0%, #003d6b 100%);
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            margin: 0.25rem;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
        }
        
        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
        }
        
        .stats-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-2px);
        }
        
        .card-header-custom {
            background: linear-gradient(45deg, var(--primary-color), #0066b3);
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }
        
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .btn-action {
            margin: 0 2px;
            padding: 0.25rem 0.5rem;
        }
        
        .status-badge {
            font-size: 0.75rem;
            padding: 0.35rem 0.65rem;
        }
        
        .modal-header-custom {
            background: linear-gradient(45deg, var(--primary-color), #0066b3);
            color: white;
        }
        
        .alert-float {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
        }
        
        .table-simple {
            background: white;
        }
        
        .table-simple th {
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }
        
        .pagination-simple {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }
        
        .pagination-simple button {
            margin: 0 5px;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }
            
            .table-responsive {
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center text-white mb-4">
                        <h4><i class="fas fa-home me-2"></i>Panel Admin</h4>
                        <small>Welcome: <?php echo $_SESSION['admin_username'] ?? 'Admin'; ?></small>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#" onclick="showTab('dashboard')">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" onclick="showTab('appointments')">
                                <i class="fas fa-calendar-alt me-2"></i>Appointments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" onclick="showTab('settings')">
                                <i class="fas fa-cog me-2"></i>Settings
                            </a>
                        </li>
                        <li class="nav-item mt-4">
                            <a class="nav-link text-warning" href="../index.php" target="_blank">
                                <i class="fas fa-external-link-alt me-2"></i>View Website
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-danger" href="logout.php">
                                <i class="fas fa-sign-out-alt me-2"></i>Sign Out
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <!-- Dashboard Tab -->
                <div id="dashboardContent" class="tab-content">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2 text-primary">Dashboard</h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <div class="btn-group me-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="loadDashboard()">
                                    <i class="fas fa-sync-alt"></i> Refresh
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Cards -->
                    <div class="row mb-4" id="statsCards">
                        <div class="col-md-3 mb-3">
                            <div class="card stats-card">
                                <div class="card-body text-center">
                                    <i class="fas fa-calendar-check fa-2x text-success mb-2"></i>
                                    <h5 class="card-title">Today's Appointments</h5>
                                    <h3 class="text-success" id="todayAppointments">-</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card stats-card">
                                <div class="card-body text-center">
                                    <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                    <h5 class="card-title">Pending</h5>
                                    <h3 class="text-warning" id="pendingAppointments">-</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card stats-card">
                                <div class="card-body text-center">
                                    <i class="fas fa-calendar fa-2x text-info mb-2"></i>
                                    <h5 class="card-title">This Week</h5>
                                    <h3 class="text-info" id="weekAppointments">-</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card stats-card">
                                <div class="card-body text-center">
                                    <i class="fas fa-chart-line fa-2x text-primary mb-2"></i>
                                    <h5 class="card-title">Total Month</h5>
                                    <h3 class="text-primary" id="monthAppointments">-</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Appointments -->
                    <div class="card">
                        <div class="card-header card-header-custom">
                            <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Recent Appointments</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-simple" id="recentAppointmentsTable">
                                    <thead>
                                        <tr>
                                            <th>Customer</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="recentAppointmentsBody">
                                        <tr>
                                            <td colspan="5" class="text-center p-4">
                                                <div class="spinner-border spinner-border-sm me-2"></div>
                                                Loading recent appointments...
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Appointments Tab -->
                <div id="appointmentsContent" class="tab-content d-none">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2 text-primary">Appointment Management</h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <div class="btn-group me-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="loadAppointments()">
                                    <i class="fas fa-sync-alt"></i> Refresh
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="filterStatus" class="form-label">Status</label>
                                    <select class="form-select" id="filterStatus">
                                        <option value="">All</option>
                                        <option value="pending">Pending</option>
                                        <option value="confirmed">Confirmed</option>
                                        <option value="completed">Completed</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="filterDateFrom" class="form-label">From</label>
                                    <input type="date" class="form-control" id="filterDateFrom">
                                </div>
                                <div class="col-md-3">
                                    <label for="filterDateTo" class="form-label">To</label>
                                    <input type="date" class="form-control" id="filterDateTo">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div>
                                        <button type="button" class="btn btn-primary" onclick="applyFilters()">
                                            <i class="fas fa-filter"></i> Filter
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" onclick="clearFilters()">
                                            <i class="fas fa-times"></i> Clear
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Search Box -->
                    <div class="mb-3">
                        <input type="text" class="form-control" id="searchBox" placeholder="Search by name, email or phone..." onkeyup="filterTable()">
                    </div>

                    <!-- Appointments Table -->
                    <div class="card">
                        <div class="card-header card-header-custom">
                            <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>All Appointments</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-simple" id="appointmentsTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Customer</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="appointmentsTableBody">
                                        <tr>
                                            <td colspan="8" class="text-center p-4">Click "Refresh" to load appointments</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Simple Pagination -->
                            <div class="pagination-simple" id="paginationContainer" style="display: none;">
                                <button class="btn btn-outline-primary btn-sm" id="prevBtn" onclick="prevPage()" disabled>
                                    <i class="fas fa-chevron-left"></i> Previous
                                </button>
                                <span id="pageInfo" class="mx-3">Page 1 of 1</span>
                                <button class="btn btn-outline-primary btn-sm" id="nextBtn" onclick="nextPage()" disabled>
                                    Next <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings Tab -->
                <div id="settingsContent" class="tab-content d-none">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2 text-primary">Settings</h1>
                    </div>

                    <!-- Business Hours -->
                    <div class="card">
                        <div class="card-header card-header-custom">
                            <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Business Hours</h5>
                        </div>
                        <div class="card-body">
                            <form id="businessHoursForm">
                                <div id="businessHoursContainer">
                                    <div class="text-center p-4">
                                        <div class="spinner-border" role="status"></div>
                                        <p class="mt-2">Loading hours...</p>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Edit Appointment Modal -->
    <div class="modal fade" id="editAppointmentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Appointment</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editAppointmentForm">
                        <input type="hidden" id="editAppointmentId">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editCustomerName" class="form-label">Customer Name</label>
                                <input type="text" class="form-control" id="editCustomerName" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editCustomerPhone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="editCustomerPhone" readonly>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="editCustomerEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editCustomerEmail" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label for="editCustomerAddress" class="form-label">Address</label>
                            <textarea class="form-control" id="editCustomerAddress" rows="2" readonly></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="editAppointmentDate" class="form-label">Date</label>
                                <input type="date" class="form-control" id="editAppointmentDate" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="editAppointmentTime" class="form-label">Time</label>
                                <input type="time" class="form-control" id="editAppointmentTime" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="editAppointmentStatus" class="form-label">Status</label>
                                <select class="form-select" id="editAppointmentStatus" required>
                                    <option value="pending">Pending</option>
                                    <option value="confirmed">Confirmed</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="editNotes" class="form-label">Notes</label>
                            <textarea class="form-control" id="editNotes" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveAppointmentChanges()">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Details Modal -->
    <div class="modal fade" id="viewDetailsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title"><i class="fas fa-info-circle me-2"></i>Appointment Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="appointmentDetailsBody">
                    <!-- Content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let allAppointments = [];
        let currentPage = 1;
        let itemsPerPage = 10;
        let filteredAppointments = [];

        $(document).ready(function() {
            console.log('Dashboard simplificado inicializado');
            
            // Initialize dashboard
            loadDashboard();

            // Initialize business hours form
            $('#businessHoursForm').on('submit', function(e) {
                e.preventDefault();
                saveBusinessHours();
            });
        });

        function showTab(tabName) {
            console.log('Switching to tab:', tabName);
            
            // Remove active class from all nav links
            $('.sidebar .nav-link').removeClass('active');
            $('.tab-content').addClass('d-none');
            
            // Add active class to clicked tab
            event.target.classList.add('active');
            
            // Show corresponding content
            $('#' + tabName + 'Content').removeClass('d-none');
            
            // Load content based on tab
            if (tabName === 'appointments') {
                loadAppointments();
            } else if (tabName === 'settings') {
                loadSettings();
            }
        }

        function loadDashboard() {
            console.log('Loading dashboard...');
            
            // Reset stats to loading state
            $('#todayAppointments, #pendingAppointments, #weekAppointments, #monthAppointments').html('<div class="spinner-border spinner-border-sm"></div>');
            
            $.ajax({
                url: 'api/get_dashboard_stats.php',
                type: 'GET',
                dataType: 'json',
                timeout: 15000,
                success: function(response) {
                    console.log('Dashboard response:', response);
                    
                    if (response && response.success) {
                        const stats = response.data || {};
                        $('#todayAppointments').text(stats.today || 0);
                        $('#pendingAppointments').text(stats.pending || 0);
                        $('#weekAppointments').text(stats.week || 0);
                        $('#monthAppointments').text(stats.month || 0);
                        
                        // Load recent appointments
                        loadRecentAppointments(stats.recent || []);
                    } else {
                        console.error('Error en respuesta:', response);
                        showAlert('danger', response ? response.message : 'Error loading statistics');
                        loadRecentAppointments([]);
                        // Reset stats to show zeros
                        $('#todayAppointments, #pendingAppointments, #weekAppointments, #monthAppointments').text('0');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error AJAX Dashboard:', {
                        status: status,
                        error: error,
                        xhr: xhr,
                        responseText: xhr.responseText
                    });
                    
                    // Reset stats to show error
                    $('#todayAppointments, #pendingAppointments, #weekAppointments, #monthAppointments').text('!');
                    
                    // Show user-friendly error based on status
                    let errorMessage = 'Connection error loading dashboard';
                    if (xhr.status === 404) {
                        errorMessage = 'API not found. Verify that the file admin/api/get_dashboard_stats.php exists.';
                    } else if (xhr.status === 500) {
                        errorMessage = 'Server error. Check PHP logs and database configuration.';
                    } else if (xhr.status === 0) {
                        errorMessage = 'Unable to connect to server. Check your internet connection.';
                    }
                    
                    showAlert('danger', errorMessage);
                    loadRecentAppointments([]);
                }
            });
        }

        function loadRecentAppointments(appointments) {
            let html = '';
            
            if (appointments && appointments.length > 0) {
                appointments.forEach(function(apt) {
                    const statusBadge = getStatusBadge(apt.status);
                    const actions = `
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-outline-info" onclick="viewAppointmentDetails(${apt.id})" title="View details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-primary" onclick="editAppointment(${apt.id})" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    `;
                    
                    html += `
                        <tr>
                            <td>${apt.customer_name || 'No name'}</td>
                            <td>${formatDate(apt.appointment_date)}</td>
                            <td>${formatTime(apt.appointment_time)}</td>
                            <td>${statusBadge}</td>
                            <td>${actions}</td>
                        </tr>
                    `;
                });
            } else {
                html = '<tr><td colspan="5" class="text-center text-muted p-4">No recent appointments</td></tr>';
            }
            
            $('#recentAppointmentsBody').html(html);
        }

        function loadAppointments() {
            console.log('Loading appointments...');
            
            const filters = {
                status: $('#filterStatus').val(),
                dateFrom: $('#filterDateFrom').val(),
                dateTo: $('#filterDateTo').val()
            };
            
            $('#appointmentsTableBody').html('<tr><td colspan="8" class="text-center p-4"><div class="spinner-border spinner-border-sm me-2"></div>Loading appointments...</td></tr>');
            
            $.ajax({
                url: 'api/get_appointments.php',
                type: 'GET',
                data: filters,
                dataType: 'json',
                timeout: 15000,
                success: function(response) {
                    console.log('Appointments response:', response);
                    
                    if (response && response.success) {
                        allAppointments = response.data || [];
                        filteredAppointments = [...allAppointments];
                        currentPage = 1;
                        displayAppointments();
                    } else {
                        console.error('Error in appointment response:', response);
                        $('#appointmentsTableBody').html('<tr><td colspan="8" class="text-center text-danger p-4">Error loading appointments: ' + (response ? response.message : 'Unknown error') + '</td></tr>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error AJAX Appointments:', {
                        status: status,
                        error: error,
                        xhr: xhr,
                        responseText: xhr.responseText
                    });
                    
                    let errorMessage = 'Connection error';
                    if (xhr.status === 404) {
                        errorMessage = 'API not found. Verify that the file admin/api/get_appointments.php exists.';
                    } else if (xhr.status === 500) {
                        errorMessage = 'Server error. Check database configuration.';
                    }
                    
                    $('#appointmentsTableBody').html('<tr><td colspan="8" class="text-center text-danger p-4">' + errorMessage + '</td></tr>');
                }
            });
        }

        function displayAppointments() {
            let html = '';
            
            if (filteredAppointments && filteredAppointments.length > 0) {
                // Calcular paginación
                const totalPages = Math.ceil(filteredAppointments.length / itemsPerPage);
                const startIndex = (currentPage - 1) * itemsPerPage;
                const endIndex = startIndex + itemsPerPage;
                const appointmentsToShow = filteredAppointments.slice(startIndex, endIndex);
                
                appointmentsToShow.forEach(function(apt) {
                    const statusBadge = getStatusBadge(apt.status);
                    const actions = `
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-outline-info" onclick="viewAppointmentDetails(${apt.id})" title="View details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-primary" onclick="editAppointment(${apt.id})" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteAppointment(${apt.id})" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                    
                    html += `
                        <tr>
                            <td><strong>#${apt.id}</strong></td>
                            <td>${apt.customer_name || 'No name'}</td>
                            <td>${apt.customer_phone || 'No phone'}</td>
                            <td><small>${apt.customer_email || 'No email'}</small></td>
                            <td>${formatDate(apt.appointment_date)}</td>
                            <td>${formatTime(apt.appointment_time)}</td>
                            <td>${statusBadge}</td>
                            <td>${actions}</td>
                        </tr>
                    `;
                });
                
                // Mostrar paginación
                $('#paginationContainer').show();
                $('#pageInfo').text(`Page ${currentPage} of ${totalPages} (${filteredAppointments.length} appointments)`);
                $('#prevBtn').prop('disabled', currentPage <= 1);
                $('#nextBtn').prop('disabled', currentPage >= totalPages);
                
            } else {
                html = '<tr><td colspan="8" class="text-center text-muted p-4">No appointments found</td></tr>';
                $('#paginationContainer').hide();
            }
            
            $('#appointmentsTableBody').html(html);
        }

        function prevPage() {
            if (currentPage > 1) {
                currentPage--;
                displayAppointments();
            }
        }

        function nextPage() {
            const totalPages = Math.ceil(filteredAppointments.length / itemsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                displayAppointments();
            }
        }

        function filterTable() {
            const searchTerm = $('#searchBox').val().toLowerCase();
            
            if (searchTerm === '') {
                filteredAppointments = [...allAppointments];
            } else {
                filteredAppointments = allAppointments.filter(function(apt) {
                    return (apt.customer_name && apt.customer_name.toLowerCase().includes(searchTerm)) ||
                           (apt.customer_email && apt.customer_email.toLowerCase().includes(searchTerm)) ||
                           (apt.customer_phone && apt.customer_phone.includes(searchTerm));
                });
            }
            
            currentPage = 1;
            displayAppointments();
        }

        function loadSettings() {
            console.log('Loading settings...');
            
            $.ajax({
                url: 'api/get_business_hours.php',
                type: 'GET',
                dataType: 'json',
                timeout: 10000,
                success: function(response) {
                    console.log('Settings response:', response);
                    
                    if (response && response.success) {
                        displayBusinessHours(response.data || []);
                    } else {
                        $('#businessHoursContainer').html('<div class="alert alert-danger">Error loading hours: ' + (response ? response.message : 'Unknown error') + '</div>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error AJAX Settings:', {
                        status: status,
                        error: error,
                        xhr: xhr,
                        responseText: xhr.responseText
                    });
                    
                    let errorMessage = 'Connection error loading hours';
                    if (xhr.status === 404) {
                        errorMessage = 'API not found. Verify that the file admin/api/get_business_hours.php exists.';
                    } else if (xhr.status === 500) {
                        errorMessage = 'Server error. Check database configuration.';
                    }
                    
                    $('#businessHoursContainer').html('<div class="alert alert-danger">' + errorMessage + '</div>');
                }
            });
        }

        function displayBusinessHours(hours) {
            const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            let html = '';
            
            // Ensure we have 7 days - create default if not exist
            for (let i = 1; i <= 7; i++) {
                let dayData = hours.find(h => h.day_of_week == i) || {
                    day_of_week: i,
                    start_time: '08:00:00',
                    end_time: '17:00:00',
                    is_active: i <= 5 ? 1 : 0 // Monday to Friday active by default
                };
                
                const checked = dayData.is_active ? 'checked' : '';
                const disabled = dayData.is_active ? '' : 'disabled';
                const startTime = dayData.start_time ? dayData.start_time.substring(0,5) : '08:00';
                const endTime = dayData.end_time ? dayData.end_time.substring(0,5) : '17:00';
                
                html += `
                    <div class="row mb-3 align-items-center border-bottom pb-2">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input day-active" type="checkbox" value="${i}" id="day${i}" ${checked} onchange="toggleDayInputs(${i})">
                                <label class="form-check-label fw-bold" for="day${i}">
                                    ${days[i-1]}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Start Time</label>
                            <input type="time" class="form-control start-time" data-day="${i}" value="${startTime}" ${disabled}>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">End Time</label>
                            <input type="time" class="form-control end-time" data-day="${i}" value="${endTime}" ${disabled}>
                        </div>
                        <div class="col-md-2 text-center">
                            <small class="text-muted">
                                <i class="fas ${dayData.is_active ? 'fa-check-circle text-success' : 'fa-times-circle text-danger'}"></i><br>
                                ${dayData.is_active ? 'Active' : 'Closed'}
                            </small>
                        </div>
                    </div>
                `;
            }
            
            $('#businessHoursContainer').html(html);
        }

        function toggleDayInputs(dayOfWeek) {
            const isChecked = $(`#day${dayOfWeek}`).is(':checked');
            const startInput = $(`.start-time[data-day="${dayOfWeek}"]`);
            const endInput = $(`.end-time[data-day="${dayOfWeek}"]`);
            
            if (isChecked) {
                startInput.prop('disabled', false);
                endInput.prop('disabled', false);
                if (!startInput.val()) startInput.val('08:00');
                if (!endInput.val()) endInput.val('17:00');
            } else {
                startInput.prop('disabled', true);
                endInput.prop('disabled', true);
                startInput.val('00:00');
                endInput.val('00:00');
            }
        }

        function saveBusinessHours() {
            console.log('Saving hours...');
            
            const hours = [];
            $('.day-active').each(function() {
                const dayOfWeek = $(this).val();
                const isActive = $(this).is(':checked');
                const startTime = $(`.start-time[data-day="${dayOfWeek}"]`).val() + ':00';
                const endTime = $(`.end-time[data-day="${dayOfWeek}"]`).val() + ':00';
                
                hours.push({
                    day_of_week: dayOfWeek,
                    is_active: isActive,
                    start_time: startTime,
                    end_time: endTime
                });
            });
            
            $.ajax({
                url: 'api/update_business_hours.php',
                type: 'POST',
                data: { hours: JSON.stringify(hours) },
                dataType: 'json',
                success: function(response) {
                    console.log('Business hours response:', response);
                    
                    if (response && response.success) {
                        showAlert('success', 'Hours updated successfully');
                        loadSettings(); // Reload to show updated status
                    } else {
                        showAlert('danger', response ? response.message : 'Error updating hours');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error updating business hours:', error);
                    showAlert('danger', 'Connection error updating hours');
                }
            });
        }

        function applyFilters() {
            loadAppointments();
        }

        function clearFilters() {
            $('#filterStatus').val('');
            $('#filterDateFrom').val('');
            $('#filterDateTo').val('');
            $('#searchBox').val('');
            loadAppointments();
        }

        function viewAppointmentDetails(id) {
            console.log('Viewing appointment details:', id);
            
            $.ajax({
                url: 'api/get_appointment_details.php',
                type: 'GET',
                data: { id: id },
                dataType: 'json',
                success: function(response) {
                    if (response && response.success) {
                        const apt = response.data;
                        const statusBadge = getStatusBadge(apt.status);
                        
                        const html = `
                            <div class="row mb-3">
                                <div class="col-md-6"><strong>Customer:</strong> ${apt.customer_name}</div>
                                <div class="col-md-6"><strong>Status:</strong> ${statusBadge}</div>
                            </div>
                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-6"><strong>Phone:</strong> ${apt.customer_phone}</div>
                                <div class="col-md-6"><strong>Email:</strong> ${apt.customer_email}</div>
                            </div>
                            <hr>
                            <div class="mb-3"><strong>Address:</strong><br>${apt.customer_address}</div>
                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-6"><strong>Date:</strong> ${formatDate(apt.appointment_date)}</div>
                                <div class="col-md-6"><strong>Time:</strong> ${formatTime(apt.appointment_time)}</div>
                            </div>
                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-6"><strong>Created:</strong> ${formatDateTime(apt.created_at)}</div>
                                <div class="col-md-6"><strong>Updated:</strong> ${formatDateTime(apt.updated_at)}</div>
                            </div>
                            ${apt.notes ? '<hr><div><strong>Notes:</strong><br>' + apt.notes + '</div>' : ''}
                        `;
                        
                        $('#appointmentDetailsBody').html(html);
                        $('#viewDetailsModal').modal('show');
                    } else {
                        showAlert('danger', 'Error loading appointment details: ' + (response ? response.message : 'Unknown error'));
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading appointment details:', error);
                    showAlert('danger', 'Connection error loading details');
                }
            });
        }

        function editAppointment(id) {
            console.log('Editing appointment:', id);
            
            $.ajax({
                url: 'api/get_appointment_details.php',
                type: 'GET',
                data: { id: id },
                dataType: 'json',
                success: function(response) {
                    if (response && response.success) {
                        const apt = response.data;
                        
                        $('#editAppointmentId').val(apt.id);
                        $('#editCustomerName').val(apt.customer_name);
                        $('#editCustomerPhone').val(apt.customer_phone);
                        $('#editCustomerEmail').val(apt.customer_email);
                        $('#editCustomerAddress').val(apt.customer_address);
                        $('#editAppointmentDate').val(apt.appointment_date);
                        $('#editAppointmentTime').val(apt.appointment_time.substring(0,5));
                        $('#editAppointmentStatus').val(apt.status);
                        $('#editNotes').val(apt.notes || '');
                        
                        $('#editAppointmentModal').modal('show');
                    } else {
                        showAlert('danger', 'Error loading appointment data: ' + (response ? response.message : 'Unknown error'));
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading appointment for edit:', error);
                    showAlert('danger', 'Connection error loading appointment');
                }
            });
        }

        function saveAppointmentChanges() {
            console.log('Saving appointment changes...');
            
            const formData = {
                id: $('#editAppointmentId').val(),
                appointment_date: $('#editAppointmentDate').val(),
                appointment_time: $('#editAppointmentTime').val(),
                status: $('#editAppointmentStatus').val(),
                notes: $('#editNotes').val()
            };
            
            $.ajax({
                url: 'api/update_appointment.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response && response.success) {
                        $('#editAppointmentModal').modal('hide');
                        showAlert('success', 'Appointment updated successfully');
                        loadAppointments();
                        loadDashboard(); // Refresh dashboard stats
                    } else {
                        showAlert('danger', response ? response.message : 'Error updating appointment');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error updating appointment:', error);
                    showAlert('danger', 'Connection error updating appointment');
                }
            });
        }

        function deleteAppointment(id) {
            if (!confirm('Are you sure you want to delete this appointment? This action cannot be undone.')) {
                return;
            }
            
            console.log('Deleting appointment:', id);
            
            $.ajax({
                url: 'api/delete_appointment.php',
                type: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function(response) {
                    if (response && response.success) {
                        showAlert('success', 'Appointment deleted successfully');
                        loadAppointments();
                        loadDashboard(); // Refresh dashboard stats
                    } else {
                        showAlert('danger', response ? response.message : 'Error deleting appointment');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error deleting appointment:', error);
                    showAlert('danger', 'Connection error deleting appointment');
                }
            });
        }

        // Utility functions
        function getStatusBadge(status) {
            const badges = {
                pending: '<span class="badge bg-warning status-badge text-dark">Pending</span>',
                confirmed: '<span class="badge bg-success status-badge">Confirmed</span>',
                completed: '<span class="badge bg-info status-badge">Completed</span>',
                cancelled: '<span class="badge bg-danger status-badge">Cancelled</span>'
            };
            return badges[status] || '<span class="badge bg-secondary status-badge">Unknown</span>';
        }

        function formatDate(dateStr) {
            if (!dateStr) return 'No date';
            try {
                const date = new Date(dateStr + 'T00:00:00');
                return date.toLocaleDateString('es-ES', {
                    weekday: 'short',
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            } catch (e) {
                console.error('Error formatting date:', e);
                return dateStr;
            }
        }

        function formatTime(timeStr) {
            if (!timeStr) return 'No time';
            try {
                const time = new Date('2000-01-01 ' + timeStr);
                return time.toLocaleTimeString('es-ES', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                });
            } catch (e) {
                console.error('Error formatting time:', e);
                return timeStr;
            }
        }

        function formatDateTime(dateTimeStr) {
            if (!dateTimeStr) return 'No date';
            try {
                const dateTime = new Date(dateTimeStr);
                return dateTime.toLocaleString('es-ES', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            } catch (e) {
                console.error('Error formatting datetime:', e);
                return dateTimeStr;
            }
        }

        function showAlert(type, message) {
            // Remove existing alerts
            $('.alert-float').remove();
            
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show alert-float" role="alert">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'danger' ? 'fa-exclamation-triangle' : 'fa-info-circle'} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            $('body').append(alertHtml);
            
            // Auto-dismiss after 6 seconds
            setTimeout(function() {
                $('.alert-float').fadeOut(function() {
                    $(this).remove();
                });
            }, 6000);
        }

        // Test connection function for debugging
        function testConnection() {
            console.log('Testing connection...');
            showAlert('info', 'Testing database connection...');
            
            $.ajax({
                url: 'api/get_dashboard_stats.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log('Test connection success:', response);
                    showAlert('success', 'Connection successful. Database working correctly.');
                },
                error: function(xhr, status, error) {
                    console.error('Test connection error:', {
                        status: xhr.status,
                        statusText: xhr.statusText,
                        responseText: xhr.responseText,
                        error: error
                    });
                    
                    let errorMsg = `Error ${xhr.status}: ${error}`;
                    if (xhr.responseText) {
                        errorMsg += `<br><small>Response: ${xhr.responseText.substring(0, 200)}...</small>`;
                    }

                    showAlert('danger', 'Connection error: ' + errorMsg);
                }
            });
        }

        // Add test connection button for debugging (always visible in this simple version)
        $(document).ready(function() {
            $('.btn-toolbar').append('<button class="btn btn-sm btn-warning ms-2" onclick="testConnection()"><i class="fas fa-bug"></i> Test Connection</button>');
        });
    </script>
</body>
</html>
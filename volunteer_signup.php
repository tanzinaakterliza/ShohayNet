<?php
// ==================== DATABASE CONNECTION ====================
include 'db_config.php';

$success = false;
$showPopup = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $full_name    = trim($_POST['full_name'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $phone        = trim($_POST['phone'] ?? '');
    $skills       = $_POST['skills'] ?? '';
    $availability = $_POST['availability'] ?? '';

    // Validation
    if (!empty($full_name) && !empty($email) && !empty($phone) && !empty($skills) && !empty($availability)) {
        
        $sql = "INSERT INTO volunteers (full_name, email, phone, skills, availability, status) 
                VALUES (?, ?, ?, ?, ?, 'Pending')";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $full_name, $email, $phone, $skills, $availability);

        if ($stmt->execute()) {
            $success = true;
            $showPopup = true;        // তোমার পুরোনো Popup দেখানোর জন্য
        } else {
            // যদি এরর হয় তাহলে দেখাবে
            echo "<script>alert('Database Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Please fill all required fields!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShohayNet - Volunteer & Disaster Response</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    
    <style>
        * { box-sizing: border-box; margin:0; padding:0; }
        body {
            font-family: 'Poppins', sans-serif;
            background: #d4f283;
            color: #333;
            line-height: 1.6;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 20px;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            position: sticky;
            top: 0;
            z-index: 1100;
        }
        .logo { font-size: 1.6rem; font-weight:700; color: #0b5d2a; }

        .nav-icons {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .menu-icon, .home-icon {
            font-size: 2.1rem;
            cursor: pointer;
            color: #0b5d2a;
        }

        /* Side Menu */
        .side-menu {
            position: fixed;
            top: 0; left: -320px;
            width: 320px; height: 100%;
            background: #0b5d2a;
            color: white;
            transition: left 0.35s ease;
            z-index: 1000;
            overflow-y: auto;
        }
        .side-menu.active { left: 0; }
        .side-menu-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .close-btn { font-size: 2.4rem; cursor: pointer; }
        .menu-items a {
            display: block;
            padding: 16px 25px;
            color: white;
            text-decoration: none;
            font-size: 1.05rem;
            border-bottom: 1px solid rgba(255,255,255,0.12);
            transition: 0.2s;
        }
        .menu-items a:hover { background: rgba(255,255,255,0.15); }
        .menu-items a.active {
            background: rgba(255,255,255,0.25);
            font-weight: 600;
        }

        .overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.5);
            opacity: 0; visibility: hidden;
            transition: opacity 0.35s;
            z-index: 900;
        }
        .overlay.active { opacity: 1; visibility: visible; }

        .hero {
            background:   linear-gradient(rgba(255, 255, 255, 0.39), rgba(255, 255, 255, 0.44)), url('volunteer image.jpg') center/cover;
            height: 40vh; min-height: 220px;
            display: flex; align-items: center;
            padding: 0 20px; color: rgba(1, 103, 45, 0.53);
            text-shadow: 1px 1px 4px rgba(2, 27, 10, 0.35);
        }
        .hero h2 { font-size: clamp(1.7rem, 5.5vw, 2.4rem); }
        .hero p { max-width: 90%; font-size: 1rem; }

        .section { background:white; text-align:center; padding:40px 20px; }
        .steps { display: flex; flex-wrap: wrap; justify-content: center; gap:30px; margin-top:30px; }
        @media (min-width:768px) { .steps { flex-direction:row; } .step { flex:1; max-width:32%; } }

        .form-area {
            display: flex; flex-direction:column; gap:30px;
            padding: 30px 15px; background: #d4f283;
        }
        @media (min-width:992px) {
            .form-area { flex-direction:row; padding:40px 40px; }
            .form-box { flex: 2; }
            .dashboard { flex: 1; max-width: 420px; }
        }


        .form-title-wrapper{
    text-align:left; padding:0 20px; 
    background: #d4f283;
    padding-top:20px;
}

.form-main-title{
    color: #0b5d2a;
    font-size:1.6rem;
    font-weight:700;
    margin-bottom:20px;
}
        .form-box { background:white; border-radius:16px; overflow:hidden; }
        .form-header { background:#0b5d2a; color:white; padding:16px 20px; font-size:1.3rem; }
        form { padding:25px; }
        input, select {
            width:100%; padding:14px 18px; margin:12px 0;
            border:1px solid #bbb; border-radius:999px; font-size:1rem;
        }
        button {
            width:100%; padding:14px; margin-top:10px;
            background:#0b5d2a; color:white; border:none;
            border-radius:999px; font-size:1.05rem; cursor:pointer;
        }
        button:hover { background:#085123; }

        .dashboard-box {
            background:white; border-radius:16px; padding:20px; margin-bottom:25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        }
        .dashboard-title {
            background:#0b5d2a; color:white; text-align:center;
            padding:14px; border-radius:12px; margin:-20px -20px 20px -20px;
            font-size:1.25rem;
        }
        .stat-card {
            background:#f8fff8; border:1px solid #b2ec74;
            padding:16px; margin:12px 0; border-radius:12px;
            text-align:center;
        }
        .stat-card h5 { margin:0 0 8px; color:#0b5d2a; font-size:1.1rem; }
        .stat-card .value { font-size:1.8rem; font-weight:700; color:#0b5d2a; }

        /* Opportunity Card */
        .opportunity-card {
            background: white;
            border-radius: 16px;
            padding: 18px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 15px;
        }
        .opportunity-top {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .opportunity-top img {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            object-fit: cover;
            flex-shrink: 0;
        }
        .opportunity-info {
            flex: 1;
        }
        .opportunity-info h4 {
            margin: 0 0 4px 0;
            color: #0b5d2a;
            font-size: 1.18rem;
            font-weight: 600;
        }
        .opportunity-info p {
            margin: 0;
            color: #666;
            font-size: 0.95rem;
        }
        .btn-view {
            background: #0b5d2a;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 999px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            width: 100%;
        }
        .btn-view:hover {
            background: #085123;
        }

        /* ==================== PROFESSIONAL FULL DASHBOARD ==================== */
        .full-dashboard {
            padding: 25px;
        }
        .welcome-section {
            text-align: center;
            margin-bottom: 30px;
        }
        .progress-bar {
            height: 10px;
            background: #e0e0e0;
            border-radius: 999px;
            overflow: hidden;
            margin-top: 8px;
        }
        .progress {
            height: 100%;
            background: linear-gradient(90deg, #0b5d2a, #4ade80);
            border-radius: 999px;
        }
        .badge {
            padding: 5px 14px;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .badge-active { background: #d1fae5; color: #0b5d2a; }
        .badge-pending { background: #fef3c7; color: #d97706; }
        .badge-upcoming { background: #dbeafe; color: #1e40af; }

        .mission-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0;
            border-bottom: 1px solid #eee;
        }
        .mission-row:last-child { border-bottom: none; }

        .modal, .popup {
            position: fixed; inset:0;
            background: rgba(0,0,0,0.6);
            display: none; justify-content:center; align-items:center;
            z-index: 2000;
        }
        .modal-content, .popup-content {
            background:white; padding:30px; border-radius:16px;
            max-width:520px; width:92%; text-align:center;
        }
        .close-modal {
            position: absolute; top:10px; right:20px;
            font-size:2.2rem; cursor:pointer; color:#555;
        }

        .footer {
            background:#0b5d2a; color:white; text-align:center; padding:20px;
            margin-top:50px;
        }
    </style>
</head>
<body>

<!-- HEADER -->
<header>
  <div class="header">
    <div class="logo">ShohayNet</div>
<div class="top-bar" style="display:flex; gap:15px; align-items:center;">
    
    <!-- Home Icon -->
    <span onclick="location.href='index.php'" style="cursor:pointer;">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="#0b5d2a" viewBox="0 0 24 24">
            <path d="M12 3l9 8h-3v9h-5v-6H11v6H6v-9H3z"/>
        </svg>
    </span>

    <!-- Menu Icon -->
    <span onclick="toggleFeatures()" style="font-size:26px; cursor:pointer; color:#0b5d2a;">
        ☰</span>

</div>
</div>

</div>

</header>

<!-- SIDE MENU -->
<div class="side-menu" id="sideMenu">
    <div class="side-menu-header">
        <h2 class="text-xl font-bold text-green-800">Our Key Features</h2>
        <span class="close-btn" onclick="toggleFeatures()">×</span>
       
    </div>
    <div class="menu-items">
        <a href="disaster.php" class="block p-3 hover:bg-green-50 rounded">Real Time Disaster Updates</a>
        <a href="livelocation.php" class="block p-3 hover:bg-green-50 rounded">Live Location Mapping</a>
        <a href="resource inventory tracking.php" class="block p-3 hover:bg-green-50 rounded">Resource Inventory Tracking</a>  
        <a href="volunteer_signup.php" class="block p-3 bg-green-700 text-white rounded shadow-md">Volunteer Sign-up & Assignment</a>
            
        <a href="emergency report.php" class="block p-3 hover:bg-green-50 rounded">Emergency Reporting Tool</a>
            <a href="chat communication.php" class="block p-3 hover:bg-green-50 rounded">Chat & Communication Hub</a>
            <a href="aid-request.php" class="block p-3 hover:bg-green-50 rounded">Aid Request Form</a>
            <a href="offline mode.php" class="block p-3 hover:bg-green-50 rounded">Offline Mode Support</a>
            <a href="data_analytics.php" class="block p-3 hover:bg-green-50 rounded">Data Analytics Dashboard</a>
            <a href="integration.php" class="block p-3 hover:bg-green-50 rounded">Integration with Government Services</a>
    </div>
</div>
        
        <div class="overlay" id="overlay" onclick="toggleFeatures()"></div>

<!-- HERO -->
<div class="hero">
    <div>
        <h2>Volunteer Sign up & Assignment</h2>
        <p>Join the ShohayNet team and help those </p>
        <p>in urgent need during disasters.</p>
    </div>
</div>

<!-- HOW IT WORKS -->
<div class="section">
    <h3>How It Works</h3>
    <img src="how it work.jpg" alt="How it works" style="max-width:100%; margin:20px 0;">
    <div class="steps">
        <div class="step"><h4>Sign Up</h4><p>Create your volunteer profile by filling out the sign-up form. Share your skills, availability, and preferences</p></div>
        <div class="step"><h4>Get Assigned</h4><p>Receive notifications of disaster relief missions that match your skills and location. Accept assignments acand get briefed.</p></div>
        <div class="step"><h4>Start Helping</h4><p>Join missions on the ground and assist affected communities .Track your work and provide feed-batter completion.</p></div>
    </div>
</div>

<!-- FORM + DASHBOARD -->
<div class="form-title-wrapper">
 <h2 class="form-main-title">Volunteer Sign-up Form</h2>
</div>
<div class="form-area">
    
<div class="form-box">
    <div class="form-header">Volunteer Information</div>
    <form method="POST">
        <input type="text" name="full_name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="tel" name="phone" placeholder="Phone Number" required>
        
        <select name="skills" required>
            <option value="" disabled selected>Select Your Skills</option>
            <option value="Medical">Medical</option>
            <option value="Rescue">Rescue</option>
            <option value="Food Distribution">Food Distribution</option>
            <option value="Logistics">Logistics</option>
            <option value="Counseling">Counseling</option>
        </select>
        
        <select name="availability" required>
            <option value="" disabled selected>Availability</option>
            <option value="Full Time">Full Time</option>
            <option value="Part Time">Part Time</option>
            <option value="Weekends Only">Weekends Only</option>
        </select>
        
        <button type="submit">Sign Up Now</button>
    </form>
</div>

    

    <div class="dashboard">
        <!-- Mini Dashboard -->
        <div class="dashboard-box" id="miniDashboard">
            <div class="dashboard-title">My Volunteer Dashboard</div>
            <div class="stat-card"><h5>Active Missions :3</h5></div>
            <div class="stat-card"><h5>Hours Served :12</h5></div>
            <div class="stat-card"><h5>Pending Assignments : 1</h5></div>
            <button onclick="showFullDashboard()">Go to Dashboard</button>
        </div>

        <!-- ==================== UNIQUE & PROFESSIONAL FULL DASHBOARD ==================== -->
        <div class="dashboard-box full-dashboard" id="fullDashboard" style="display:none;">
            <div class="dashboard-title">Volunteer Dashboard</div>
            
            <div class="welcome-section">
                <h3 style="color:#0b5d2a; margin-bottom:5px;">Welcome back </h3>
                <p style="color:#555; font-size:0.98rem;">Level 8 • Silver Responder • 142 Impact Points</p>
            </div>

            <!-- Stats with Progress -->
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(130px, 1fr)); gap:16px; margin:25px 0 35px;">
                <div class="stat-card">
                    <h5>Total Missions</h5>
                    <div class="value">14</div>
                </div>
                <div class="stat-card">
                    <h5>Hours Contributed</h5>
                    <div class="value">168</div>
                </div>
                <div class="stat-card">
                    <h5>Impact Score</h5>
                    <div class="value">94%</div>
                    <div class="progress-bar"><div class="progress" style="width:94%"></div></div>
                </div>
            </div>

            <h4 style="margin:20px 0 15px; color:#0b5d2a;">Active & Upcoming Missions</h4>
            
            <div class="mission-row">
                <div>
                    <strong>Flood Relief – Sylhet</strong><br>
                    <small>Mar 25 – 30, 2026 • Food Distribution</small>
                </div>
                <span class="badge badge-active">Active</span>
            </div>
            
            <div class="mission-row">
                <div>
                    <strong>Medical Camp – Dhaka</strong><br>
                    <small>Mar 28, 2026 • First Aid</small>
                </div>
                <span class="badge badge-pending">Pending Approval</span>
            </div>
            
            <div class="mission-row">
                <div>
                    <strong>Cyclone Preparedness – Chittagong</strong><br>
                    <small>Apr 5 – 10, 2026 • Logistics Support</small>
                </div>
                <span class="badge badge-upcoming">Upcoming</span>
            </div>

            <h4 style="margin:30px 0 15px; color:#0b5d2a;">Recent Activity</h4>
            <ul style="list-style:none; padding:0; font-size:0.95rem; color:#444;">
                <li style="padding:10px 0; border-bottom:1px solid #eee;">✅ Delivered 210 food kits in Sylhet (Mar 23)</li>
                <li style="padding:10px 0; border-bottom:1px solid #eee;">✅ Completed 8-hour medical shift in Dhaka (Mar 20)</li>
                <li style="padding:10px 0;">✅ Accepted Flood Relief assignment (Mar 18)</li>
            </ul>

            <button onclick="hideFullDashboard()" style="margin-top:35px; background:#555; width:100%; padding:14px; border-radius:999px; color:white; border:none; cursor:pointer; font-size:1.02rem;">
                ← Back to Summary
            </button>
        </div>

        <!-- Recent Opportunities -->
        <h4 style="margin:25px 0 15px; color:#0b5d2a;">Recent Volunteer Opportunities</h4>
        
        <div class="opportunity-card">
            <div class="opportunity-top">
                <img src="flood relief.jpg" alt="Flood Relief">
                <div class="opportunity-info">
                    <h4>Flood Relief in Sylhet</h4>
                    <p>Assigned just now</p>
                </div>
            </div>
            <button class="btn-view" onclick="showMissionDetails('flood')">View Details</button>
        </div>
    </div>
</div>

<!-- MISSION DETAILS MODAL -->
<div class="modal" id="missionModal">
    <div class="modal-content" style="position:relative;">
        <span class="close-modal" onclick="document.getElementById('missionModal').style.display='none'">×</span>
        <h2 id="modalTitle">Mission Details</h2>
        <div id="modalBody" style="margin:20px 0; text-align:left; line-height:1.7;"></div>
        <button onclick="document.getElementById('missionModal').style.display='none'">Close</button>
    </div>
</div>

<!-- SUCCESS POPUP -->
<div class="popup" id="successPopup" style="display:<?php echo $showPopup ? 'flex' : 'none'; ?>;">
    <div class="popup-content">
        <h2>Congratulations!</h2>
        <p>You have successfully signed up 🎉</p>
        <button onclick="document.getElementById('successPopup').style.display='none'">Close</button>
    </div>
</div>

<!-- FOOTER -->
<div class="footer">
    © 2026 ShohayNet, All rights reserved.
</div>

<script>
function toggleFeatures() {
    document.getElementById("sideMenu").classList.toggle("active");
    document.getElementById("overlay").classList.toggle("active");
}

function showFullDashboard() {
    document.getElementById("miniDashboard").style.display = "none";
    document.getElementById("fullDashboard").style.display = "block";
}

function hideFullDashboard() {
    document.getElementById("fullDashboard").style.display = "none";
    document.getElementById("miniDashboard").style.display = "block";
}

function showMissionDetails(type) {
    const modal = document.getElementById("missionModal");
    const title = document.getElementById("modalTitle");
    const body = document.getElementById("modalBody");

    if (type === 'flood') {
        title.textContent = "Flood Relief in Sylhet";
        body.innerHTML = `
            <p><strong>Location:</strong> Sylhet Sadar, Sylhet Division</p>
            <p><strong>Period:</strong> March 25 – 30, 2026</p>
            <p><strong>Required Skills:</strong> Food Distribution, Logistics, Basic Medical</p>
            <p><strong>Description:</strong> Severe monsoon flooding has impacted thousands of families. 
            Volunteers needed for food/water distribution, hygiene kit delivery and community support.</p>
            <p><strong>Current Status:</strong> 22 volunteers assigned | Urgent need for more hands</p>
        `;
    }
    modal.style.display = "flex";
}
</script>

</body>
</html>
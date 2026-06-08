
<?php
$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShohayNet - Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sidebar-green: #4E9A51;
            --sidebar-dark-green: #3E7C40;
            --sidebar-separator: #2E5C30;
            --active-gray: #D9D9D9;
            --main-bg: #BDD7EE;
            --kpi-yellow: #FFFFC2;
            --footer-dark: #224D24;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body {
            background-color: var(--main-bg);
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 280px;
            background-color: var(--sidebar-green);
            display: flex;
            flex-direction: column;
            border-right: 1px solid #ccc;
        }

        .logo-area {
            background-color: white;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 32px;
            gap: 15px;
        }

        .logo-area img {
            height: 50px;
            width: auto;
        }

        .sidebar-dropdown {
            background-color: var(--sidebar-dark-green);
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
        }

        .nav-item {
            padding: 20px 25px;
            color: black;
            font-size: 18px;
            display: flex;
            align-items: center;
            border-bottom: 5px solid var(--sidebar-separator);
            cursor: pointer;
        }

        .nav-item i { margin-right: 15px; font-size: 20px; }
        .nav-item.active { background-color: var(--active-gray); }

        /* Main Content Styling */
        .content {
            flex-grow: 1;
            padding: 10px 20px;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .header-icons {
            display: flex;
            justify-content: flex-end;
            padding: 10px;
            gap: 20px;
        }

        .header-icons i {
            font-size: 28px;
            color: #4CAF50;
            background-color: white;
            padding: 5px;
            border-radius: 4px;
        }

        /* FEATURE PANEL */
.feature-panel {
    position: fixed;
    top: 0;
    right: -100%; /* initially hidden */
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    display: flex;
    justify-content: flex-end;
    transition: 0.4s ease;
    z-index: 999;
}

.feature-panel.active {
    right: 0;
}

.feature-box {
    width: 350px;
    height: 100%;
    background: white;
    padding: 20px;
    overflow-y: auto;
}

.feature-list div {
    padding: 15px;
    border-bottom: 1px solid #ddd;
    cursor: pointer;
    font-weight: bold;
}

.feature-list div:hover {
    background: #f0f0f0;
}
        /* Dashboard Grid */
        .grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .kpi-card {
            background-color: var(--kpi-yellow);
            padding: 15px;
            border: 1px solid #ddd;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .kpi-card h3 { font-size: 18px; margin-bottom: 5px; }
        .kpi-card .value { font-size: 22px; font-weight: bold; }
        .card {
            background: white;
            padding: 15px;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.1);
        }

        .span-2 { grid-column: span 2; }

        /* Bar Chart */
        .chart-box {
            display: flex;
            align-items: flex-end;
            justify-content: space-around;
            height: 180px;
            border-left: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            padding: 10px;
            position: relative;
        }

        .bar-pair { display: flex; align-items: flex-end; gap: 2px; }
        .bar { width: 40px; }
        /* Map Placeholder */
        .map-placeholder {
            width: 100%;
            height: 350px;
            background-color: #EAF2F8;
            background-image: url('https://upload.wikimedia.org/wikipedia/commons/thumb/9/9e/Bangladesh_rel_location_map.svg/960px-Bangladesh_rel_location_map.svg.png');
            background-position: center;
            background-size: 100% 100%; /* Stretched to cover the whole section */
            background-repeat: no-repeat;
            position: relative;
            border: 1px solid #ddd;
        }

        .map-marker {
            position: absolute;
            color: #D32F2F; /* Default red as in image */
            font-size: 20px;
            filter: drop-shadow(0 2px 2px rgba(0,0,0,0.5));
        }

        .map-legend {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: white;
            padding: 10px 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            font-size: 14px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .legend-row {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .legend-box {
            width: 20px;
            height: 20px;
            border-radius: 4px;
        }
        /* Bottom Row */
        .table-card { background-color: #E2E2E2; padding: 0; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 12px; border-top: 1px solid #ccc; }

        .bubble {
            width: 130px;
            height: 130px;
            border-radius: 15px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 10px;
            font-weight: bold;
        }

        .bubble-red { background-color: #E53935; }
        .bubble-maroon { background-color: #6D4C41; }
        .bubble-blue { background-color: #3F51B5; }

        footer {
            background-color: var(--footer-dark);
            color: white;
            text-align: center;
            padding: 10px;
            margin-top: auto;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="logo-area">
        <img src="logo.jpeg" alt="Logo"> ShohayNet
    </div>
    <div class="sidebar-dropdown">
        <i class="fas fa-chevron-down"></i> Data Analytics
    </div>
    
   

 <ul class="nav-list">
<li class="nav-item active" onclick="goToPage('dashboard.php')">
    <i class="fas fa-check" style="color: #FF5252;"></i> Dashboard
</li>

<li class="nav-item" onclick="goToPage('incident.php')">
    <i class="fas fa-file-alt"></i> Incident Reports
</li>

<li class="nav-item" onclick="goToPage('areas.php')">
    <i class="fas fa-map-marker-alt" style="color: red;"></i> Affected Areas
</li>

<li class="nav-item" onclick="goToPage('reports.php')">
    <i class="fas fa-chart-line" style="color: #FBC02D;"></i> Reports
</li>

<li class="nav-item" onclick="goToPage('settings.php')">
    <i class="fas fa-cog"></i> Settings
</li>
 </ul>
<div style="flex-grow:1;"></div>
    <div style="height:40px; background:var(--sidebar-dark-green);"></div>
</div>  

<div class="content">
    <div class="header-icons">
    <i class="fas fa-home" onclick="goToPage('index.php')"></i> 
  
    <i class="fas fa-bars" onclick="openPanel()"></i>
</div>

<!-- FEATURE PANEL -->
<div class="feature-panel" id="featurePanel" onclick="closePanel(event)">
    <div class="feature-box">

        <div class="feature-list">
            <a href="disaster.php"><div>Real Time Disaster Updates</div></a>
            <a href="livelocation.php"><div>Live Location Mapping</div></a>
            <a href="resource inventory tracking.php"><div>Resource Inventory Tracking</div></a>
            <a href="volunteer_signup.php"><div>Volunteer Sign-up & Assignment</div></a>
            <a href="emergency report.php"><div>Emergency Reporting Tool</div></a>
            <a href="chat communication.php"><div>Chat & Communication Hub</div></a>
            <a href="aid-request.php"><div>Aid Request Form</div></a>
            <a href="offline mode.php"><div>Offline Mode Support</div></a>
            <a href="data_analytics.php"><div>Data Analytics Dashboard</div></a>      
            <a href="integration.php"><div>Integration With Government Services</div></a>
        </div>

    </div>
</div>

    <div class="grid">
        <div class="kpi-card">
            <h3><i class="fas fa-book" style="color: #D32F2F;"></i> Total Incidents</h3>
            <div class="value">1,250 <span style="color: #D32F2F;">+8.2%</span></div>
        </div>
        <div class="kpi-card">
            <h3><i class="fas fa-users" style="color: #1976D2;"></i> People Affected</h3>
            <div class="value">45,300 <span style="color: #4CAF50;">+5.4%</span></div>
        </div>
        <div class="kpi-card">
            <h3><i class="fas fa-ambulance" style="color: #D32F2F;"></i> Resources Deployed</h3>
            <div class="value">320 <span style="color: #D32F2F;">+3.3%</span></div>
        </div>
        <div class="kpi-card">
            <h3><i class="fas fa-hand-holding-heart" style="color: #FBC02D;"></i> Volunteers Active</h3>
            <div class="value">850 <span style="color: #1976D2;">+6.8%</span></div>
        </div>

        <div class="card span-2">
            <div style="color:#88A9D6; font-size:12px; font-weight:bold; margin-bottom:10px;">Incident Overview</div>
            <div class="chart-box">
                <div class="bar-pair">
                    <div class="bar" style="height: 150px; background: #D32F2F;"></div>
                    <div class="bar" style="height: 80px; background: #FFB74D;"></div>
                </div>
                <div class="bar-pair">
                    <div class="bar" style="height: 110px; background: #FFA000;"></div>
                    <div class="bar" style="height: 60px; background: #B71C1C;"></div>
                </div>
                <div class="bar-pair">
                    <div class="bar" style="height: 70px; background: #1A237E;"></div>
                    <div class="bar" style="height: 40px; background: #90CAF9;"></div>
                </div>
            </div>
        </div>
        <div class="card span-2">
            <div style="color:#000; font-size:24px; font-weight:bold; margin-bottom:15px;">Affected Areas Map</div>
            <div class="map-placeholder">
                <!-- Northern Markers -->
                <i class="fas fa-map-marker-alt map-marker" style="top: 15%; left: 25%;"></i>
                <i class="fas fa-map-marker-alt map-marker" style="top: 25%; left: 35%;"></i>
                
                <!-- Central Markers -->
                <i class="fas fa-map-marker-alt map-marker" style="top: 45%; left: 45%;"></i>
                <i class="fas fa-map-marker-alt map-marker" style="top: 48%; left: 52%;"></i>
                <i class="fas fa-map-marker-alt map-marker" style="top: 55%; left: 48%;"></i>
                
                <!-- Western/Central Markers -->
                <i class="fas fa-map-marker-alt map-marker" style="top: 52%; left: 28%;"></i>
                <i class="fas fa-map-marker-alt map-marker" style="top: 55%; left: 26%;"></i>
                
                <!-- Southern Markers -->
                <i class="fas fa-map-marker-alt map-marker" style="top: 65%; left: 38%;"></i>
                <i class="fas fa-map-marker-alt map-marker" style="top: 80%; left: 36%;"></i>
                
                <div class="map-legend">
                    <div class="legend-row">
                        <div class="legend-box" style="background: #F59E0B;"></div> 
                        <span>High Impact</span>
                    </div>
                    <div class="legend-row">
                        <div class="legend-box" style="background: #EF4444;"></div> 
                        <span>Medium Impact</span>
                    </div>
                    <div class="legend-row">
                        <div class="legend-box" style="background: #3B82F6;"></div> 
                        <span>Low Impact</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card span-2 table-card">
            <table>
                    <tr><td>04/02/2025</td><td>Dhaka</td><td>Earthquake</td></tr>
                    <tr><td>04/11/2024</td><td>Rajshahi</td><td>Fire</td></tr>
                    <tr><td>04/10/2024</td><td>Sylhet</td><td>Flood</td></tr>
            </table>
        </div>

        <div class="span-2" style="display:flex; justify-content:space-around; align-items:center;">
            <div class="bubble bubble-red"><span>180</span> People Affected</div>
            <div class="bubble bubble-maroon"><span>220</span> Volunteers On-Site</div>
            <div class="bubble bubble-blue"><span>75 sites:</span> Shelters Set Up</div>
        </div>
            </div>
    <footer>&copy 2026 ShohayNet. All rights reserved.</footer>
            </div>

            <script>
function goToPage(page) {
    window.location.href = page;
}

function toggleMenu() {
    alert("Menu clicked! (You can add sidebar toggle here)");
}
</script>
<script>
function openPanel() {
    document.getElementById("featurePanel").classList.add("active");
}

function closePanel(event) {
    // outside click করলে close হবে
    if (event.target.id === "featurePanel") {
        document.getElementById("featurePanel").classList.remove("active");
    }
}
</script>
</body>
</html>




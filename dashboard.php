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
            --card-bg: #ffffff;
            --text-dark: #1a1a1a;
            --text-muted: #666;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body {
            background-color: var(--main-bg);
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        .sidebar {
            width: 280px;
            min-width: 280px;
            background-color: var(--sidebar-green);
            display: flex;
            flex-direction: column;
            border-right: 1px solid #ccc;
            overflow-y: auto;
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
            cursor: pointer;
            transition: filter 0.2s;
        }
        .logo-area:hover { filter: brightness(0.95); }
        .logo-area img { height: 50px; width: auto; }

        .sidebar-dropdown {
            background-color: var(--sidebar-dark-green);
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
            cursor: pointer;
            user-select: none;
            transition: background-color 0.2s;
        }
        .sidebar-dropdown:hover { background-color: #357038; }

        .dropdown-arrow {
            margin-right: 12px;
            transition: transform 0.3s ease;
            display: inline-block;
        }
        .dropdown-arrow.open { transform: rotate(180deg); }

        .dropdown-submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.35s ease;
            background-color: var(--sidebar-dark-green);
        }
        .dropdown-submenu.expanded { max-height: 200px; }

        .dropdown-submenu .sub-item {
            padding: 12px 25px 12px 52px;
            color: rgba(255,255,255,0.85);
            font-size: 15px;
            cursor: pointer;
            transition: background-color 0.2s, color 0.2s;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        .dropdown-submenu .sub-item:hover { background-color: rgba(255,255,255,0.15); color: white; }
        .dropdown-submenu .sub-item.active-sub { background-color: rgba(255,255,255,0.2); color: white; font-weight: bold; }

        .nav-list { list-style: none; }

        .nav-item {
            padding: 20px 25px;
            color: black;
            font-size: 18px;
            display: flex;
            align-items: center;
            border-bottom: 5px solid var(--sidebar-separator);
            cursor: pointer;
            transition: background-color 0.15s;
            user-select: none;
        }
        .nav-item:hover { background-color: #c5dfc6; }
        .nav-item.active { background-color: var(--active-gray); }
        .nav-item i { margin-right: 15px; font-size: 20px; }

        .content {
            flex-grow: 1;
            padding: 0;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .header-icons {
            display: flex;
            justify-content: flex-end;
            padding: 10px 20px;
            gap: 20px;
            background: var(--main-bg);
        }
        .header-icons i {
            font-size: 28px;
            color: #4CAF50;
            background-color: white;
            padding: 5px 8px;
            border-radius: 4px;
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s;
        }
        .header-icons i:hover { transform: translateY(-2px); box-shadow: 0 3px 8px rgba(0,0,0,0.15); }
        .header-icons i:active { transform: translateY(0); box-shadow: none; }

        .page {
            display: none;
            flex: 1;
            overflow-y: auto;
            padding: 10px 20px 0 20px;
        }
        .page.active { display: block; }

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
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s;
        }
        .kpi-card:hover { transform: translateY(-3px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
        .kpi-card:active { transform: translateY(0); box-shadow: none; }
        .kpi-card h3 { font-size: 18px; margin-bottom: 5px; }
        .kpi-card .value { font-size: 22px; font-weight: bold; }

        .card { background: var(--card-bg); padding: 15px; box-shadow: 2px 2px 5px rgba(0,0,0,0.1); }
        .span-2 { grid-column: span 2; }

        .chart-box {
            display: flex;
            align-items: flex-end;
            justify-content: space-around;
            height: 180px;
            border-left: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            padding: 10px;
        }
        .bar-pair { display: flex; align-items: flex-end; gap: 2px; }
        .bar { width: 40px; cursor: pointer; transition: opacity 0.15s, transform 0.15s; }
        .bar:hover { opacity: 0.8; transform: scaleY(1.03); transform-origin: bottom; }

        .map-placeholder {
            width: 100%; height: 350px; background-color: #EAF2F8;
            background-image: url('https://upload.wikimedia.org/wikipedia/commons/thumb/9/9e/Bangladesh_rel_location_map.svg/960px-Bangladesh_rel_location_map.svg.png');
            background-position: center; background-size: 100% 100%; background-repeat: no-repeat;
            position: relative; border: 1px solid #ddd;
        }
        .map-marker {
            position: absolute; color: #D32F2F; font-size: 20px;
            filter: drop-shadow(0 2px 2px rgba(0,0,0,0.5));
            cursor: pointer; transition: transform 0.2s, color 0.2s;
        }
        .map-marker:hover { transform: scale(1.3); color: #FF6F00; }

        .map-legend {
            position: absolute; bottom: 10px; right: 10px; background: white;
            padding: 10px 15px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            font-size: 14px; display: flex; flex-direction: column; gap: 8px;
        }
        .legend-row { display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 2px 4px; border-radius: 4px; transition: background-color 0.15s; }
        .legend-row:hover { background-color: #f0f0f0; }
        .legend-box { width: 20px; height: 20px; border-radius: 4px; }

        .table-card { background-color: #E2E2E2; padding: 0; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 12px; border-top: 1px solid #ccc; cursor: pointer; transition: background-color 0.15s; }
        td:hover { background-color: #d0d0d0; }
        tr:hover td { background-color: #d0d0d0; }

        .bubble {
            width: 130px; height: 130px; border-radius: 15px; color: white;
            display: flex; flex-direction: column; justify-content: center; align-items: center;
            text-align: center; padding: 10px; font-weight: bold;
            cursor: pointer; transition: transform 0.15s, box-shadow 0.15s;
        }
        .bubble:hover { transform: translateY(-4px); box-shadow: 0 6px 16px rgba(0,0,0,0.25); }
        .bubble:active { transform: translateY(0); box-shadow: none; }
        .bubble-red { background-color: #E53935; }
        .bubble-maroon { background-color: #6D4C41; }
        .bubble-blue { background-color: #3F51B5; }

        .page-title {
            font-size: 28px; font-weight: bold; color: var(--text-dark);
            margin-bottom: 20px; padding-bottom: 10px; border-bottom: 3px solid var(--sidebar-green);
        }

        .inner-card {
            background: var(--card-bg); border-radius: 8px; padding: 20px; margin-bottom: 20px;
            box-shadow: 1px 2px 6px rgba(0,0,0,0.08); transition: box-shadow 0.2s;
        }
        .inner-card:hover { box-shadow: 2px 4px 12px rgba(0,0,0,0.13); }
        .inner-card h3 { font-size: 18px; margin-bottom: 12px; color: var(--sidebar-dark-green); }

        .btn {
            display: inline-block; padding: 10px 22px; border: none; border-radius: 6px;
            font-size: 15px; font-weight: bold; cursor: pointer;
            transition: transform 0.12s, box-shadow 0.12s, background 0.2s; color: white;
        }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 3px 10px rgba(0,0,0,0.2); }
        .btn:active { transform: translateY(0); box-shadow: none; }
        .btn-green { background: var(--sidebar-green); }
        .btn-green:hover { background: var(--sidebar-dark-green); }
        .btn-red { background: #D32F2F; }
        .btn-red:hover { background: #B71C1C; }
        .btn-blue { background: #1976D2; }
        .btn-blue:hover { background: #1565C0; }
        .btn-yellow { background: #F9A825; color: #333; }
        .btn-yellow:hover { background: #F57F17; color: #fff; }

        .report-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 16px 20px; background: #f7f7f7; border-radius: 6px; margin-bottom: 10px;
            border-left: 5px solid var(--sidebar-green);
            cursor: pointer; transition: background 0.15s, transform 0.15s;
        }
        .report-row:hover { background: #eef6ee; transform: translateX(4px); }
        .report-row.severity-high { border-left-color: #D32F2F; }
        .report-row.severity-medium { border-left-color: #F9A825; }
        .report-row.severity-low { border-left-color: #1976D2; }
        .report-row .report-info { flex: 1; }
        .report-row .report-info .report-title { font-weight: bold; font-size: 16px; }
        .report-row .report-info .report-meta { font-size: 13px; color: var(--text-muted); margin-top: 3px; }
        .report-row .report-badge { padding: 5px 14px; border-radius: 20px; font-size: 12px; font-weight: bold; color: white; }

        .area-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .area-card {
            background: white; border-radius: 10px; padding: 20px;
            box-shadow: 1px 2px 6px rgba(0,0,0,0.08);
            cursor: pointer; transition: transform 0.15s, box-shadow 0.15s;
            border-top: 4px solid transparent;
        }
        .area-card:hover { transform: translateY(-4px); box-shadow: 2px 6px 16px rgba(0,0,0,0.15); }
        .area-card.high { border-top-color: #D32F2F; }
        .area-card.medium { border-top-color: #F9A825; }
        .area-card.low { border-top-color: #1976D2; }
        .area-card h4 { font-size: 18px; margin-bottom: 8px; }
        .area-card .area-stat { font-size: 14px; color: var(--text-muted); margin-bottom: 4px; }
        .area-card .area-stat strong { color: var(--text-dark); }
        .area-card .impact-badge { display: inline-block; margin-top: 10px; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: bold; color: white; }

        .stat-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 20px; }
        .stat-box { background: white; border-radius: 10px; padding: 25px; text-align: center; box-shadow: 1px 2px 6px rgba(0,0,0,0.08); }
        .stat-box .stat-number { font-size: 36px; font-weight: bold; color: var(--sidebar-green); }
        .stat-box .stat-label { font-size: 14px; color: var(--text-muted); margin-top: 5px; }

        .settings-section { background: white; border-radius: 8px; padding: 25px; margin-bottom: 20px; box-shadow: 1px 2px 6px rgba(0,0,0,0.08); }
        .settings-section h3 { font-size: 18px; margin-bottom: 18px; color: var(--sidebar-dark-green); padding-bottom: 8px; border-bottom: 2px solid #eee; }
        .form-row { display: flex; align-items: center; margin-bottom: 16px; gap: 15px; }
        .form-row label { min-width: 160px; font-weight: bold; font-size: 15px; color: var(--text-dark); }
        .form-row input, .form-row select { flex: 1; padding: 10px 14px; border: 2px solid #ddd; border-radius: 6px; font-size: 15px; transition: border-color 0.2s; outline: none; }
        .form-row input:focus, .form-row select:focus { border-color: var(--sidebar-green); }

        .toggle-switch { position: relative; width: 50px; height: 26px; cursor: pointer; }
        .toggle-switch input { display: none; }
        .toggle-slider { position: absolute; inset: 0; background: #ccc; border-radius: 26px; transition: background 0.3s; }
        .toggle-slider::before { content: ''; position: absolute; width: 20px; height: 20px; left: 3px; bottom: 3px; background: white; border-radius: 50%; transition: transform 0.3s; }
        .toggle-switch input:checked + .toggle-slider { background: var(--sidebar-green); }
        .toggle-switch input:checked + .toggle-slider::before { transform: translateX(24px); }

        .trend-bar-container { margin-bottom: 14px; }
        .trend-bar-label { display: flex; justify-content: space-between; font-size: 14px; margin-bottom: 5px; }
        .trend-bar-label span:first-child { font-weight: bold; }
        .trend-bar-track { height: 24px; background: #e0e0e0; border-radius: 12px; overflow: hidden; }
        .trend-bar-fill { height: 100%; border-radius: 12px; transition: width 0.8s ease; }

        .content-footer {
            background-color: var(--footer-dark);
            color: white;
            text-align: center;
            padding: 10px;
            margin-top: auto;
            flex-shrink: 0;
        }

        .page::-webkit-scrollbar { width: 8px; }
        .page::-webkit-scrollbar-track { background: transparent; }
        .page::-webkit-scrollbar-thumb { background: #aaa; border-radius: 4px; }
        .page::-webkit-scrollbar-thumb:hover { background: #888; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="logo-area" id="logoBtn">
        <img src="logo.jpeg" alt="Logo"> ShohayNet
    </div>
    <div class="sidebar-dropdown" id="dropdownBtn">
        <span class="dropdown-arrow" id="dropdownArrow"><i class="fas fa-chevron-down"></i></span>
        <span>Data Analytics</span>
    </div>
    <div class="dropdown-submenu" id="dropdownSubmenu">
        <div class="sub-item" data-page="Overview">Overview</div>
        <div class="sub-item active-sub" data-page="Dashboard">Dashboard</div>
        <div class="sub-item" data-page="Trends">Trends</div>
    </div>
    <ul class="nav-list">
        <li class="nav-item active" data-page="Dashboard"><i class="fas fa-check" style="color: #FF5252;"></i> Dashboard</li>
        <li class="nav-item" data-page="Incident Reports"><i class="fas fa-file-alt"></i> Incident Reports</li>
        <li class="nav-item" data-page="Affected Areas"><i class="fas fa-map-marker-alt" style="color: red;"></i> Affected Areas</li>
        <li class="nav-item" data-page="Reports"><i class="fas fa-chart-line" style="color: #FBC02D;"></i> Reports</li>
        <li class="nav-item" data-page="Settings"><i class="fas fa-cog"></i> Settings</li>
    </ul>
    <div style="flex-grow:1;"></div>
    <div style="height:40px; background:var(--sidebar-dark-green);"></div>
</div>

<div class="content">
    <div class="header-icons">
        <!-- Home icon: just clickable, redirects to external home page -->
        <a href="index.php">
            <i class="fas fa-home" id="homeBtn" title="Home"></i>
        </a>
        <i class="fas fa-user-circle" id="profileBtn" title="Profile"></i>
        <i class="fas fa-bars" id="menuBtn" title="Menu"></i>
    </div>

    <!-- ==================== PAGE: DASHBOARD ==================== -->
    <div class="page active" id="page-Dashboard">
        <div class="grid">
            <div class="kpi-card" data-action="Total Incidents">
                <h3><i class="fas fa-book" style="color: #D32F2F;"></i> Total Incidents</h3>
                <div class="value">1,250 <span style="color: #D32F2F;">+8.2%</span></div>
            </div>
            <div class="kpi-card" data-action="People Affected">
                <h3><i class="fas fa-users" style="color: #1976D2;"></i> People Affected</h3>
                <div class="value">45,300 <span style="color: #4CAF50;">+5.4%</span></div>
            </div>
            <div class="kpi-card" data-action="Resources Deployed">
                <h3><i class="fas fa-ambulance" style="color: #D32F2F;"></i> Resources Deployed</h3>
                <div class="value">320 <span style="color: #D32F2F;">+3.3%</span></div>
            </div>
            <div class="kpi-card" data-action="Volunteers Active">
                <h3><i class="fas fa-hand-holding-heart" style="color: #FBC02D;"></i> Volunteers Active</h3>
                <div class="value">850 <span style="color: #1976D2;">+6.8%</span></div>
            </div>
            <div class="card span-2">
                <div style="color:#88A9D6; font-size:12px; font-weight:bold; margin-bottom:10px;">Incident Overview</div>
                <div class="chart-box">
                    <div class="bar-pair">
                        <div class="bar" style="height: 150px; background: #D32F2F;" data-info="Earthquakes: 150"></div>
                        <div class="bar" style="height: 80px; background: #FFB74D;" data-info="Fires: 80"></div>
                    </div>
                    <div class="bar-pair">
                        <div class="bar" style="height: 110px; background: #FFA000;" data-info="Floods: 110"></div>
                        <div class="bar" style="height: 60px; background: #B71C1C;" data-info="Cyclones: 60"></div>
                    </div>
                    <div class="bar-pair">
                        <div class="bar" style="height: 70px; background: #1A237E;" data-info="Landslides: 70"></div>
                        <div class="bar" style="height: 40px; background: #90CAF9;" data-info="Others: 40"></div>
                    </div>
                </div>
            </div>
            <div class="card span-2">
                <div style="color:#000; font-size:24px; font-weight:bold; margin-bottom:15px;">Affected Areas Map</div>
                <div class="map-placeholder">
                    <i class="fas fa-map-marker-alt map-marker" style="top:15%;left:25%;" data-location="Rangpur"></i>
                    <i class="fas fa-map-marker-alt map-marker" style="top:25%;left:35%;" data-location="Mymensingh"></i>
                    <i class="fas fa-map-marker-alt map-marker" style="top:45%;left:45%;" data-location="Dhaka"></i>
                    <i class="fas fa-map-marker-alt map-marker" style="top:48%;left:52%;" data-location="Chittagong"></i>
                    <i class="fas fa-map-marker-alt map-marker" style="top:55%;left:48%;" data-location="Comilla"></i>
                    <i class="fas fa-map-marker-alt map-marker" style="top:52%;left:28%;" data-location="Rajshahi"></i>
                    <i class="fas fa-map-marker-alt map-marker" style="top:55%;left:26%;" data-location="Khulna"></i>
                    <i class="fas fa-map-marker-alt map-marker" style="top:65%;left:38%;" data-location="Barisal"></i>
                    <i class="fas fa-map-marker-alt map-marker" style="top:80%;left:36%;" data-location="Cox's Bazar"></i>
                    <div class="map-legend">
                        <div class="legend-row" data-filter="High Impact"><div class="legend-box" style="background:#F59E0B;"></div><span>High Impact</span></div>
                        <div class="legend-row" data-filter="Medium Impact"><div class="legend-box" style="background:#EF4444;"></div><span>Medium Impact</span></div>
                        <div class="legend-row" data-filter="Low Impact"><div class="legend-box" style="background:#3B82F6;"></div><span>Low Impact</span></div>
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
                <div class="bubble bubble-red" data-action="180 People Affected"><span>180</span> People Affected</div>
                <div class="bubble bubble-maroon" data-action="220 Volunteers On-Site"><span>220</span> Volunteers On-Site</div>
                <div class="bubble bubble-blue" data-action="75 Shelters Set Up"><span>75 sites:</span> Shelters Set Up</div>
            </div>
        </div>
    </div>

    <!-- ==================== PAGE: OVERVIEW ==================== -->
    <div class="page" id="page-Overview">
        <div class="page-title">Overview</div>
        <div class="stat-grid">
            <div class="stat-box"><div class="stat-number">1,250</div><div class="stat-label">Total Incidents (2025)</div></div>
            <div class="stat-box"><div class="stat-number">45,300</div><div class="stat-label">Total People Affected</div></div>
            <div class="stat-box"><div class="stat-number">64</div><div class="stat-label">Districts Impacted</div></div>
        </div>
        <div class="inner-card">
            <h3>Recent Activity Feed</h3>
            <div class="report-row"><div class="report-info"><div class="report-title"><i class="fas fa-exclamation-triangle" style="color:#D32F2F;"></i> Flash Flood Warning — Sylhet Division</div><div class="report-meta">Issued 2 hours ago &bull; Bangladesh Meteorological Department</div></div><span class="report-badge" style="background:#D32F2F;">Critical</span></div>
            <div class="report-row"><div class="report-info"><div class="report-title"><i class="fas fa-truck" style="color:#F9A825;"></i> Relief Supplies Dispatched — Cox's Bazar</div><div class="report-meta">Issued 5 hours ago &bull; Relief Coordination Cell</div></div><span class="report-badge" style="background:#F9A825;color:#333;">In Progress</span></div>
            <div class="report-row"><div class="report-info"><div class="report-title"><i class="fas fa-check-circle" style="color:#1976D2;"></i> Shelter Setup Complete — Khulna</div><div class="report-meta">Issued 1 day ago &bull; Field Operations Team</div></div><span class="report-badge" style="background:#1976D2;">Completed</span></div>
            <div class="report-row"><div class="report-info"><div class="report-title"><i class="fas fa-users" style="color:#4CAF50;"></i> 120 New Volunteers Registered — Dhaka</div><div class="report-meta">Issued 1 day ago &bull; Volunteer Management</div></div><span class="report-badge" style="background:#4CAF50;">Info</span></div>
        </div>
        <div class="inner-card">
            <h3>Quick Summary</h3>
            <p style="line-height:1.8; color:#444; font-size:15px;">In the current reporting period, Bangladesh has experienced a surge in climate-related incidents across multiple divisions. Sylhet and Cox's Bazar remain the most affected regions. 320 resources have been deployed with 850 active volunteers supporting relief operations. Real-time monitoring is active across all 64 districts.</p>
        </div>
    </div>

    <!-- ==================== PAGE: TRENDS ==================== -->
    <div class="page" id="page-Trends">
        <div class="page-title">Trends</div>
        <div class="stat-grid">
            <div class="stat-box"><div class="stat-number">+18%</div><div class="stat-label">Flood Incidents (YoY)</div></div>
            <div class="stat-box"><div class="stat-number">-5%</div><div class="stat-label">Fire Incidents (YoY)</div></div>
            <div class="stat-box"><div class="stat-number">+12%</div><div class="stat-label">Volunteer Participation</div></div>
        </div>
        <div class="inner-card">
            <h3>Incident Type Distribution</h3>
            <div class="trend-bar-container"><div class="trend-bar-label"><span>Floods</span><span>38%</span></div><div class="trend-bar-track"><div class="trend-bar-fill" style="width:38%;background:#1976D2;"></div></div></div>
            <div class="trend-bar-container"><div class="trend-bar-label"><span>Cyclones</span><span>22%</span></div><div class="trend-bar-track"><div class="trend-bar-fill" style="width:22%;background:#D32F2F;"></div></div></div>
            <div class="trend-bar-container"><div class="trend-bar-label"><span>Earthquakes</span><span>15%</span></div><div class="trend-bar-track"><div class="trend-bar-fill" style="width:15%;background:#F9A825;"></div></div></div>
            <div class="trend-bar-container"><div class="trend-bar-label"><span>Fires</span><span>13%</span></div><div class="trend-bar-track"><div class="trend-bar-fill" style="width:13%;background:#FF7043;"></div></div></div>
            <div class="trend-bar-container"><div class="trend-bar-label"><span>Landslides</span><span>8%</span></div><div class="trend-bar-track"><div class="trend-bar-fill" style="width:8%;background:#7B1FA2;"></div></div></div>
            <div class="trend-bar-container"><div class="trend-bar-label"><span>Others</span><span>4%</span></div><div class="trend-bar-track"><div class="trend-bar-fill" style="width:4%;background:#607D8B;"></div></div></div>
        </div>
        <div class="inner-card">
            <h3>Monthly Incident Trend (2025)</h3>
            <div class="trend-bar-container"><div class="trend-bar-label"><span>January</span><span>85</span></div><div class="trend-bar-track"><div class="trend-bar-fill" style="width:28%;background:#4CAF50;"></div></div></div>
            <div class="trend-bar-container"><div class="trend-bar-label"><span>February</span><span>120</span></div><div class="trend-bar-track"><div class="trend-bar-fill" style="width:40%;background:#4CAF50;"></div></div></div>
            <div class="trend-bar-container"><div class="trend-bar-label"><span>March</span><span>195</span></div><div class="trend-bar-track"><div class="trend-bar-fill" style="width:65%;background:#F9A825;"></div></div></div>
            <div class="trend-bar-container"><div class="trend-bar-label"><span>April</span><span>300</span></div><div class="trend-bar-track"><div class="trend-bar-fill" style="width:100%;background:#D32F2F;"></div></div></div>
        </div>
    </div>

    <!-- ==================== PAGE: INCIDENT REPORTS ==================== -->
    <div class="page" id="page-Incident Reports">
        <div class="page-title">Incident Reports</div>
        <div style="display:flex;gap:12px;margin-bottom:20px;flex-wrap:wrap;">
            <button class="btn btn-green" id="btnAllReports">All Reports</button>
            <button class="btn btn-red" id="btnHighReports">High Severity</button>
            <button class="btn btn-yellow" id="btnMedReports">Medium Severity</button>
            <button class="btn btn-blue" id="btnLowReports">Low Severity</button>
        </div>
        <div id="reportList">
            <div class="report-row severity-high" data-severity="high"><div class="report-info"><div class="report-title">Earthquake Magnitude 5.8 — Dhaka</div><div class="report-meta">04/02/2025 &bull; 12:45 PM &bull; Reporter: Ahmed Kabir</div></div><span class="report-badge" style="background:#D32F2F;">High</span></div>
            <div class="report-row severity-high" data-severity="high"><div class="report-info"><div class="report-title">Flash Flood — Sylhet Division</div><div class="report-meta">04/10/2025 &bull; 06:30 AM &bull; Reporter: Field Team Alpha</div></div><span class="report-badge" style="background:#D32F2F;">High</span></div>
            <div class="report-row severity-medium" data-severity="medium"><div class="report-info"><div class="report-title">Industrial Fire — Rajshahi EPZ</div><div class="report-meta">04/11/2024 &bull; 03:15 PM &bull; Reporter: Fire Service</div></div><span class="report-badge" style="background:#F9A825;color:#333;">Medium</span></div>
            <div class="report-row severity-medium" data-severity="medium"><div class="report-info"><div class="report-title">Cyclone Warning — Cox's Bazar Coastal Belt</div><div class="report-meta">04/08/2025 &bull; 09:00 AM &bull; Reporter: BMD</div></div><span class="report-badge" style="background:#F9A825;color:#333;">Medium</span></div>
            <div class="report-row severity-low" data-severity="low"><div class="report-info"><div class="report-title">Waterlogging — Chittagong City</div><div class="report-meta">04/05/2025 &bull; 07:00 AM &bull; Reporter: City Corporation</div></div><span class="report-badge" style="background:#1976D2;">Low</span></div>
            <div class="report-row severity-low" data-severity="low"><div class="report-info"><div class="report-title">Minor Landslide — Rangamati</div><div class="report-meta">03/28/2025 &bull; 11:20 AM &bull; Reporter: Hill District Authority</div></div><span class="report-badge" style="background:#1976D2;">Low</span></div>
        </div>
    </div>

    <!-- ==================== PAGE: AFFECTED AREAS ==================== -->
    <div class="page" id="page-Affected Areas">
        <div class="page-title">Affected Areas</div>
        <div class="area-grid">
            <div class="area-card high"><h4><i class="fas fa-map-marker-alt" style="color:#D32F2F;"></i> Sylhet</h4><div class="area-stat">Affected: <strong>12,400 people</strong></div><div class="area-stat">Incidents: <strong>45</strong></div><div class="area-stat">Status: <strong style="color:#D32F2F;">Critical</strong></div><span class="impact-badge" style="background:#D32F2F;">High Impact</span></div>
            <div class="area-card high"><h4><i class="fas fa-map-marker-alt" style="color:#D32F2F;"></i> Cox's Bazar</h4><div class="area-stat">Affected: <strong>9,800 people</strong></div><div class="area-stat">Incidents: <strong>32</strong></div><div class="area-stat">Status: <strong style="color:#D32F2F;">Critical</strong></div><span class="impact-badge" style="background:#D32F2F;">High Impact</span></div>
            <div class="area-card high"><h4><i class="fas fa-map-marker-alt" style="color:#D32F2F;"></i> Dhaka</h4><div class="area-stat">Affected: <strong>8,200 people</strong></div><div class="area-stat">Incidents: <strong>28</strong></div><div class="area-stat">Status: <strong style="color:#D32F2F;">Critical</strong></div><span class="impact-badge" style="background:#D32F2F;">High Impact</span></div>
            <div class="area-card medium"><h4><i class="fas fa-map-marker-alt" style="color:#F9A825;"></i> Rajshahi</h4><div class="area-stat">Affected: <strong>5,600 people</strong></div><div class="area-stat">Incidents: <strong>18</strong></div><div class="area-stat">Status: <strong style="color:#F9A825;">Monitoring</strong></div><span class="impact-badge" style="background:#F9A825;color:#333;">Medium Impact</span></div>
            <div class="area-card medium"><h4><i class="fas fa-map-marker-alt" style="color:#F9A825;"></i> Khulna</h4><div class="area-stat">Affected: <strong>4,300 people</strong></div><div class="area-stat">Incidents: <strong>14</strong></div><div class="area-stat">Status: <strong style="color:#F9A825;">Monitoring</strong></div><span class="impact-badge" style="background:#F9A825;color:#333;">Medium Impact</span></div>
            <div class="area-card low"><h4><i class="fas fa-map-marker-alt" style="color:#1976D2;"></i> Rangpur</h4><div class="area-stat">Affected: <strong>2,100 people</strong></div><div class="area-stat">Incidents: <strong>8</strong></div><div class="area-stat">Status: <strong style="color:#1976D2;">Stable</strong></div><span class="impact-badge" style="background:#1976D2;">Low Impact</span></div>
            <div class="area-card low"><h4><i class="fas fa-map-marker-alt" style="color:#1976D2;"></i> Barisal</h4><div class="area-stat">Affected: <strong>1,800 people</strong></div><div class="area-stat">Incidents: <strong>6</strong></div><div class="area-stat">Status: <strong style="color:#1976D2;">Stable</strong></div><span class="impact-badge" style="background:#1976D2;">Low Impact</span></div>
            <div class="area-card low"><h4><i class="fas fa-map-marker-alt" style="color:#1976D2;"></i> Mymensingh</h4><div class="area-stat">Affected: <strong>1,100 people</strong></div><div class="area-stat">Incidents: <strong>4</strong></div><div class="area-stat">Status: <strong style="color:#1976D2;">Stable</strong></div><span class="impact-badge" style="background:#1976D2;">Low Impact</span></div>
            <div class="area-card low"><h4><i class="fas fa-map-marker-alt" style="color:#1976D2;"></i> Comilla</h4><div class="area-stat">Affected: <strong>900 people</strong></div><div class="area-stat">Incidents: <strong>3</strong></div><div class="area-stat">Status: <strong style="color:#1976D2;">Stable</strong></div><span class="impact-badge" style="background:#1976D2;">Low Impact</span></div>
        </div>
    </div>

    <!-- ==================== PAGE: REPORTS ==================== -->
    <div class="page" id="page-Reports">
        <div class="page-title">Reports</div>
        <div style="display:flex;gap:12px;margin-bottom:20px;">
            <button class="btn btn-green" id="btnGenReport"><i class="fas fa-download"></i> Generate Report</button>
            <button class="btn btn-blue" id="btnExportCSV"><i class="fas fa-file-csv"></i> Export CSV</button>
        </div>
        <div class="inner-card">
            <h3>Available Reports</h3>
            <div class="report-row"><div class="report-info"><div class="report-title"><i class="fas fa-file-pdf" style="color:#D32F2F;"></i> Monthly Incident Summary — March 2025</div><div class="report-meta">Generated: 01/04/2025 &bull; Pages: 24 &bull; Format: PDF</div></div><button class="btn btn-green" style="padding:6px 16px;font-size:13px;" onclick="showToastMsg('Downloading: March 2025 Summary')">Download</button></div>
            <div class="report-row"><div class="report-info"><div class="report-title"><i class="fas fa-file-pdf" style="color:#D32F2F;"></i> Flood Impact Assessment — Sylhet 2025</div><div class="report-meta">Generated: 10/04/2025 &bull; Pages: 38 &bull; Format: PDF</div></div><button class="btn btn-green" style="padding:6px 16px;font-size:13px;" onclick="showToastMsg('Downloading: Sylhet Flood Assessment')">Download</button></div>
            <div class="report-row"><div class="report-info"><div class="report-title"><i class="fas fa-file-excel" style="color:#1976D2;"></i> Volunteer Deployment Log — Q1 2025</div><div class="report-meta">Generated: 02/04/2025 &bull; Format: Excel</div></div><button class="btn btn-green" style="padding:6px 16px;font-size:13px;" onclick="showToastMsg('Downloading: Volunteer Q1 Log')">Download</button></div>
            <div class="report-row"><div class="report-info"><div class="report-title"><i class="fas fa-file-pdf" style="color:#D32F2F;"></i> Resource Allocation Report — April 2025</div><div class="report-meta">Generated: 12/04/2025 &bull; Pages: 15 &bull; Format: PDF</div></div><button class="btn btn-green" style="padding:6px 16px;font-size:13px;" onclick="showToastMsg('Downloading: Resource Allocation Report')">Download</button></div>
        </div>
    </div>

    <!-- ==================== PAGE: SETTINGS ==================== -->
    <div class="page" id="page-Settings">
        <div class="page-title">Settings</div>
        <div class="settings-section">
            <h3><i class="fas fa-user"></i> Profile Settings</h3>
            <div class="form-row"><label>Full Name</label><input type="text" value="Admin User" id="settingsName"></div>
            <div class="form-row"><label>Email</label><input type="email" value="admin@shohaynet.org" id="settingsEmail"></div>
            <div class="form-row"><label>Phone</label><input type="text" value="+880 1712-345678" id="settingsPhone"></div>
            <div class="form-row"><label>Role</label><select id="settingsRole"><option>Administrator</option><option>Operator</option><option>Viewer</option></select></div>
            <div style="margin-top:10px;"><button class="btn btn-green" id="btnSaveProfile"><i class="fas fa-save"></i> Save Changes</button></div>
        </div>
        <div class="settings-section">
            <h3><i class="fas fa-bell"></i> Notification Preferences</h3>
            <div class="form-row"><label>Email Notifications</label><label class="toggle-switch"><input type="checkbox" checked><span class="toggle-slider"></span></label></div>
            <div class="form-row"><label>SMS Alerts</label><label class="toggle-switch"><input type="checkbox" checked><span class="toggle-slider"></span></label></div>
            <div class="form-row"><label>Push Notifications</label><label class="toggle-switch"><input type="checkbox"><span class="toggle-slider"></span></label></div>
            <div class="form-row"><label>Critical Alerts Only</label><label class="toggle-switch"><input type="checkbox"><span class="toggle-slider"></span></label></div>
        </div>
        <div class="settings-section">
            <h3><i class="fas fa-shield-alt"></i> Security</h3>
            <div class="form-row"><label>Current Password</label><input type="password" placeholder="Enter current password"></div>
            <div class="form-row"><label>New Password</label><input type="password" placeholder="Enter new password" id="newPass"></div>
            <div class="form-row"><label>Confirm Password</label><input type="password" placeholder="Confirm new password" id="confirmPass"></div>
            <div style="margin-top:10px;"><button class="btn btn-red" id="btnChangePass"><i class="fas fa-key"></i> Change Password</button></div>
        </div>
    </div>

    <div class="content-footer">&copy; 2026 ShohayNet. All rights reserved.</div>
</div>

<div class="toast" id="toast" style="position:fixed;top:20px;right:20px;background:#2E7D32;color:white;padding:14px 24px;border-radius:8px;font-size:15px;font-weight:bold;box-shadow:0 4px 16px rgba(0,0,0,0.25);transform:translateX(calc(100% + 40px));transition:transform 0.35s ease;z-index:9999;"></div>

<script>
    function showToastMsg(msg) {
        var t = document.getElementById('toast');
        t.textContent = msg;
        t.classList.add('show');
        clearTimeout(t._to);
        t._to = setTimeout(function() { t.classList.remove('show'); }, 2200);
    }

    var allPages = document.querySelectorAll('.page');
    var allNavItems = document.querySelectorAll('.nav-item');
    var allSubItems = document.querySelectorAll('.sub-item');

    function showPage(pageName) {
        allPages.forEach(function(p) { p.classList.remove('active'); });
        var target = document.getElementById('page-' + pageName);
        if (target) {
            target.classList.add('active');
        }
        allNavItems.forEach(function(n) { n.classList.remove('active'); });
        allSubItems.forEach(function(s) { s.classList.remove('active-sub'); });
    }

    allNavItems.forEach(function(item) {
        item.addEventListener('click', function() { showPage(item.dataset.page); });
    });
    allSubItems.forEach(function(item) {
        item.addEventListener('click', function() { showPage(item.dataset.page); });
    });

    /* =============================================
       HOME ICON — এখানে আপনার home page এর link দিন
       ============================================= */
    document.getElementById('homeBtn').addEventListener('click', function() {
        window.location.href = 'home.html';   // ← এই line এ আপনার home page এর ফাইলের নাম বা URL দিন
    });

    /* Logo → Dashboard */
    document.getElementById('logoBtn').addEventListener('click', function() {
        showPage('Dashboard');
    });

    /* Profile icon → Settings */
    document.getElementById('profileBtn').addEventListener('click', function() {
        showPage('Settings');
    });

    /* Menu icon → dropdown toggle */
    document.getElementById('menuBtn').addEventListener('click', function() {
        document.getElementById('dropdownSubmenu').classList.toggle('expanded');
        document.getElementById('dropdownArrow').classList.toggle('open');
    });

    document.getElementById('dropdownBtn').addEventListener('click', function() {
        document.getElementById('dropdownSubmenu').classList.toggle('expanded');
        document.getElementById('dropdownArrow').classList.toggle('open');
    });

    /* Dashboard interactions */
    document.querySelectorAll('.kpi-card').forEach(function(c) { c.addEventListener('click', function() { showToastMsg('Viewing: ' + c.dataset.action); }); });
    document.querySelectorAll('.bar').forEach(function(b) { b.addEventListener('click', function() { showToastMsg(b.dataset.info); }); });
    document.querySelectorAll('.map-marker').forEach(function(m) { m.addEventListener('click', function() { showToastMsg('Location: ' + m.dataset.location); }); });
    document.querySelectorAll('.legend-row').forEach(function(r) { r.addEventListener('click', function() { showToastMsg('Filter: ' + r.dataset.filter); }); });
    document.querySelectorAll('.table-card tr').forEach(function(row) {
        row.style.cursor = 'pointer';
        row.addEventListener('click', function() {
            var cells = row.querySelectorAll('td');
            if (cells.length >= 3) showToastMsg(cells[0].textContent + ' — ' + cells[1].textContent + ' — ' + cells[2].textContent);
        });
    });
    document.querySelectorAll('.bubble').forEach(function(b) { b.addEventListener('click', function() { showToastMsg(b.dataset.action); }); });

    /* Incident Reports filter */
    var reportRows = document.querySelectorAll('#reportList .report-row');
    document.getElementById('btnAllReports').addEventListener('click', function() { reportRows.forEach(function(r) { r.style.display = 'flex'; }); });
    document.getElementById('btnHighReports').addEventListener('click', function() { reportRows.forEach(function(r) { r.style.display = r.dataset.severity === 'high' ? 'flex' : 'none'; }); });
    document.getElementById('btnMedReports').addEventListener('click', function() { reportRows.forEach(function(r) { r.style.display = r.dataset.severity === 'medium' ? 'flex' : 'none'; }); });
    document.getElementById('btnLowReports').addEventListener('click', function() { reportRows.forEach(function(r) { r.style.display = r.dataset.severity === 'low' ? 'flex' : 'none'; }); });
    reportRows.forEach(function(row) {
        row.addEventListener('click', function() { showToastMsg('Opened: ' + row.querySelector('.report-title').textContent); });
    });

    document.getElementById('btnGenReport').addEventListener('click', function() { showToastMsg('Generating new report...'); });
    document.getElementById('btnExportCSV').addEventListener('click', function() { showToastMsg('Exporting data as CSV...'); });
    document.querySelectorAll('.area-card').forEach(function(card) { card.addEventListener('click', function() { showToastMsg('Viewing details: ' + card.querySelector('h4').textContent.trim()); }); });

    document.getElementById('btnSaveProfile').addEventListener('click', function() {
        if (!document.getElementById('settingsName').value || !document.getElementById('settingsEmail').value) { showToastMsg('Please fill in all required fields'); return; }
        showToastMsg('Profile saved successfully!');
    });
    document.getElementById('btnChangePass').addEventListener('click', function() {
        var np = document.getElementById('newPass').value, cp = document.getElementById('confirmPass').value;
        if (!np || !cp) { showToastMsg('Please fill in both password fields'); return; }
        if (np !== cp) { showToastMsg('Passwords do not match!'); return; }
        if (np.length < 6) { showToastMsg('Password must be at least 6 characters'); return; }
        showToastMsg('Password changed successfully!');
        document.getElementById('newPass').value = '';
        document.getElementById('confirmPass').value = '';
    });
</script>
</body>
</html>
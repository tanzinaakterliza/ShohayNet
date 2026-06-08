<?php
include 'db_config.php';

$success = false;
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_help'])) {
    
    $name        = trim($_POST['name'] ?? '');
    $phone       = trim($_POST['phone'] ?? '');
    $type        = $_POST['type'] ?? '';
    $details     = trim($_POST['details'] ?? '');

    // Default value 
    $latitude    = 23.6850;   // Default Dhaka
    $longitude   = 90.3563;

    if (!empty($name) && !empty($phone) && !empty($type) && !empty($details)) {
        
        $sql = "INSERT INTO live_locations 
                (location_name, latitude, longitude, type, severity, description, reported_by, status) 
                VALUES (?, ?, ?, ?, 'High Risk', ?, ?, 'Active')";

        $stmt = $conn->prepare($sql);
        $location_name = $name . "'s Emergency Help";
        $reported_by   = $name;

        $stmt->bind_param("sddsss", $location_name, $latitude, $longitude, $type, $details, $reported_by);

        if ($stmt->execute()) {
            $success = true;
            $message = "✅ Your emergency help request has been submitted successfully!<br>Our team will contact you soon.";
        } else {
            $message = "❌ Database Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "❌ Please fill all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Live Location Mapping</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>

<style>
body {margin:0;font-family:Arial;background:#f4f4f4;}
header{background:#2e7d32;color:white;display:flex;justify-content:space-between;align-items:center;padding:10px 20px;position:relative;z-index:1000;}
header input{width:40%;padding:8px;border:none;border-radius:20px;}

/* 🔥 Navbar highlight effect */
header .right i{
  margin-left:15px;
  cursor:pointer;
  padding:6px;
  border-radius:10px;
  transition:all 0.2s ease;
}
header .right i:hover{background:rgba(255,255,255,0.15);}
header .right i.active{background:white;color:#2e7d32;}

.container{display:flex;}
aside{width:20%;background:#e8f5e9;padding:15px;}
.box{background:white;padding:10px;margin-bottom:15px;border-radius:10px;}
.box ul{list-style:none;padding:0;}
.box li{margin:6px 0;}
.red,.yellow,.green{display:inline-block;width:10px;height:10px;border-radius:50%;}
.red{background:red;}
.yellow{background:orange;}
.green{background:green;}
main{width:60%;padding:10px;}
#map{height:500px;border-radius:10px;}
.right-panel{width:20%;padding:15px;}
.card{background:#e8f5e9;padding:15px;border-radius:10px;margin-bottom:15px;}
button{background:#2e7d32;color:white;border:none;padding:8px;border-radius:10px;cursor:pointer;}

.full-menu{position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.4);display:none;justify-content:center;align-items:center;z-index:99999;}
.menu-box{background:white;width:90%;max-width:400px;max-height:80vh;overflow-y:auto;padding:20px;border-radius:15px;display:flex;flex-direction:column;align-items:center;}
.menu-box button{width:100%;margin:8px 0;padding:14px;font-size:16px;border-radius:25px;}

.modal{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:9999;}
.modal-content{background:white;width:350px;margin:10% auto;padding:20px;border-radius:10px;text-align:center;}
.modal-content input,.modal-content textarea,.modal-content select{width:90%;padding:8px;border-radius:8px;border:1px solid #ccc;}
</style>
</head>

<body>

<header>
  <div class="left"><b>Live Location Mapping 📍</b></div>
  <input id="searchBox" placeholder="Search area/ shelter/ incident">

  <div class="right">
    <i id="menuIcon" class="fa fa-bars" onclick="toggleMenu(); setActiveIcon('menuIcon')"></i>
    <i id="homeIcon" class="fa fa-home" onclick="window.location.href='index.php'; setActiveIcon('homeIcon')"></i>
  </div>
</header>

<!-- FULL MENU -->


<div id="fullMenu" class="full-menu" onclick="closeFullMenu(event)">
  <div class="menu-box" onclick="event.stopPropagation()">
    

    
    <button onclick="location.href='disaster.php'">Real Time Disaster Updates</button>
    <button onclick="location.href='resource inventory tracking.php'">Resource Inventory Tracking</button>
    <button onclick="location.href='volunteer_signup.php'">Volunteer Sign-up & Assignment</button>
    <button onclick="location.href='emergency report.php'">Emergency Reporting Tool</button>
    <button onclick="location.href='chat communication.php'">Chat & Communication Hub</button>
    <button onclick="location.href='aid-request.php'">Aid Request Form</button>
    <button onclick="location.href='offline mode.php'">Offline Mode Support</button>
    <button onclick="location.href='data_analytics.php'">Data Analytics Dashboard</button>
    <button onclick="location.href='integration.php'">Integration with Government Services</button>
  </div>
</div>


<div class="container">
<aside>
  <div class="box">
    <h3>Filters</h3>
    <ul>
      <li>🌊 Flood</li>
      <li>🌪 Cyclone</li>
      <li>🌍 Earthquake</li>
      <li>🔥 Fire/ Wildfire</li>
      <li>🚑 Medical Emergency</li>
    </ul>
  </div>
  <div class="box">
    <h3>Map Controls</h3>
    <ul>
      <li>📍 Live Location</li>
      <li>🏠 Shelter Location</li>
      <li>🚓 Rescue Team</li>
      <li>📦 Relief Distribution</li>
    </ul>
  </div>
  <div class="box">
    <h3>Status</h3>
    <ul>
      <li><span class="red"></span> High Risk</li>
      <li><span class="yellow"></span> Medium</li>
      <li><span class="green"></span> Safe</li>
    </ul>
  </div>
</aside>

<main><div id="map"></div></main>

<section class="right-panel">
  <div class="card">
    <h3>Emergency Updates</h3>
    <p><b>Sylhet</b> - Flood Incident</p>
    <p>Updated 10 mins ago</p>
    <p>People Affected: 250+</p>
    <button onclick="openModal('needHelpModal')">Need Help</button>
  </div>
  <div class="card">
    <h3>Emergency Contacts</h3>
    <p>📞 Hotline: 990</p>
    <p>🚑 Ambulance: 199</p>
    <p>👮 Police: 999</p>
    <p>📍 Local Authority: 1234</p>
    <p>👤 Volunteer: 1090</p>
  </div>
</section>
</div>

<!-- MODAL -->
<div id="needHelpModal" class="modal">
  <?php if (!empty($message)): ?>
    <div style="padding:12px; margin:10px 0; border-radius:8px; text-align:center; 
                background: <?php echo $success ? '#d4edda' : '#f8d7da'; ?>; 
                color: <?php echo $success ? '#155724' : '#721c24'; ?>;">
        <?php echo $message; ?>
    </div>
<?php endif; ?>
<div class="modal-content">
    <h2>🚨 Emergency Help Request</h2>
    
    <form method="POST">
        <input type="text" name="name" placeholder="Your Name" required><br><br>
        <input type="tel" name="phone" placeholder="Phone Number" required><br><br>
        
        <select name="type" required>
            <option value="">-- Select Incident Type --</option>
            <option value="Flood">Flood</option>
            <option value="Fire">Fire</option>
            <option value="Medical">Medical Emergency</option>
            <option value="Rescue Needed">Rescue Needed</option>
            <option value="Other">Others</option>
        </select><br><br>
        
        <textarea name="details" placeholder="Describe your situation / Location" rows="4" required></textarea><br><br>
        
        <button type="submit" name="submit_help" style="background:#d63031; color:white; padding:12px; border:none; border-radius:8px; width:100%;">Submit Help Request</button>
    </form>
    
    <br>
    <button onclick="closeModal('needHelpModal')" style="background:#777; width:100%;">Cancel</button>
  </div>
</div>



<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
// 🔥 Navbar click highlight
let activeIcon = null;
function setActiveIcon(id){
  if(activeIcon){
    document.getElementById(activeIcon).classList.remove('active');
  }
  document.getElementById(id).classList.add('active');
  activeIcon = id;
}

// MENU/MODAL
function toggleMenu(){ document.getElementById("fullMenu").style.display="flex"; }
function closeFullMenu(e){ if(!e||e.target.id==="fullMenu"){ document.getElementById("fullMenu").style.display="none"; } }
function openModal(id){ document.getElementById(id).style.display="block"; }
function closeModal(id){ document.getElementById(id).style.display="none"; }
function openFeature(name){
  closeFullMenu();
  if(name==="Real Time Disaster Updates"){
    window.location.href="disaster.html";
  }else{
    document.getElementById("featureTitle").innerText=name;
    openModal('feature');
  }
}
function submitHelp(){
  let name=document.getElementById("name").value;
  let phone=document.getElementById("phone").value;
  if(name==""||phone==""){ alert("Fill required fields!"); return; }
  alert("✅ Help request sent successfully!");
  closeModal('needHelpModal');
}

// 🔥 Map - same as previous design
var map = L.map('map',{
  center:[23.6850,90.3563],
  zoom:7,
  minZoom:6,
  maxZoom:18,
  maxBounds:[[20.5,88.0],[26.8,92.7]],
  maxBoundsViscosity:1.0
});

L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png',{
  attribution:'&copy; OpenStreetMap & Carto', subdomains:'abcd', maxZoom:19
}).addTo(map);

// Bangladesh major cities
var bangladeshPlaces = [
  {name:"Dhaka", coords:[23.8103,90.4125]},
  {name:"Chattogram", coords:[22.3569,91.7832]},
  {name:"Chittagong", coords:[22.3569,91.7832]},
  {name:"Khulna", coords:[22.8456,89.5403]},
  {name:"Rajshahi", coords:[24.3745,88.6042]},
  {name:"Sylhet", coords:[24.8949,91.8687]},
  {name:"Barisal", coords:[22.7010,90.3535]},
  {name:"Barishal", coords:[22.7010,90.3535]},
  {name:"Rangpur", coords:[25.7439,89.2752]},
  {name:"Mymensingh", coords:[24.7471,90.4203]}
];

// SEARCH FUNCTION
document.getElementById("searchBox").addEventListener("input", function(){
  var val = this.value.toLowerCase().trim();
  if(!val){ map.setView([23.6850,90.3563],7); return; }
  var found = bangladeshPlaces.find(city => city.name.toLowerCase().includes(val));
  if(found){ map.setView(found.coords, 13); }
});
</script>

</body>
</html>
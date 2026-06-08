<?php
// PHP ready file 
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Real Time Disaster Updates</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Arial,sans-serif;}
body{background:#f3f7f5;}

/* NAVBAR */
.navbar{display:flex;justify-content:flex-end;align-items:center;padding:15px 60px;background:white;gap:10px;}
.nav-btn{font-size:22px;color:#1b6d3c;border:none;background:white;cursor:pointer;padding:8px 14px;border-radius:8px;transition:0.3s;}
.nav-btn:hover{background:#1b6d3c;color:white;}

/* HERO */
.hero{
    height:280px;
    background:linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)),
               url("background.jpg.jpg");
    background-size:cover;
    background-position:center;
    display:flex;
    align-items:center;
    padding-left:60px;
    color:white;
}
.hero h1{font-size:45px;}
.hero span{color:#2ecc71;}
.hero p{margin-top:10px;font-size:18px;}

/* FILTERS */
.filters{margin:20px 60px;display:flex;gap:10px;position:relative;}
.filters button{padding:10px 20px;border:none;border-radius:20px;background:#e0f3e8;cursor:pointer;transition:0.3s;}
.filters button:hover{background:#2ecc71;color:white;}
.filters button.active{background:#2ecc71;color:white;}
#filterDropdown{display:none;position:absolute;top:45px;left:0;background:white;border:1px solid #ccc;border-radius:10px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.2);z-index:10;}
#filterDropdown div{padding:10px 20px;cursor:pointer;transition:0.2s;}
#filterDropdown div:hover{background:#2ecc71;color:white;}

/* MAIN */
.container{display:flex;gap:25px;padding:0 60px;flex-wrap:wrap;}
.left{flex:2;min-width:300px;}
.card{position:relative;display:flex;align-items:center;padding:18px;border-radius:12px;margin-bottom:18px;background:white;box-shadow:0 3px 8px rgba(0,0,0,0.1);}
.card img{width:70px;margin-right:15px;}
.card a{color:#27ae60;font-weight:bold;cursor:pointer;}
.status{position:absolute;top:10px;right:15px;font-size:12px;}
.hurricane{background:#eef7b2;}
.flood{background:#c7dcff;}
.earthquake{background:#f7b7b7;}
.fire{background:#f6c27e;}
.right{flex:1;min-width:250px;}
.map img{width:100%;border-radius:10px;}

/* QUICK LINKS */
.quick{background:#e5f7ec;margin-top:15px;padding:18px;border-radius:12px;text-align:center;}
.quick h3{color:#27ae60;margin-bottom:10px;}
.quick button{width:100%;padding:12px;margin:6px 0;border:none;border-radius:8px;background:white;cursor:pointer;transition:0.3s;}
.quick button:hover{background:#27ae60;color:white;}
.infoBox{display:none;margin-top:10px;background:white;padding:10px;border-radius:8px;font-size:14px;}

/* DETAILS MODAL */
#detailsModal{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.7);z-index:1000;}
.modal-content{background:white;padding:25px;border-radius:10px;max-width:400px;margin:120px auto;text-align:center;position:relative;}
.modal-content .close{position:absolute;top:10px;right:15px;font-size:18px;cursor:pointer;color:#333;}

/* FEATURES MODAL */
#featuresModal{
    display:none;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.8);
    z-index:2000;
    overflow-y:auto;
    padding:40px 0;
}
#featuresModal .modal-content{
    background:white;
    margin:0 auto;
    padding:30px;
    border-radius:15px;
    max-width:500px;
}
#featuresModal .modal-content h2{color:#1b6d3c;margin-bottom:20px;text-align:center;}
#featuresModal .modal-content .feature-item{
    background:#1b6d3c;
    color:white;
    margin:10px 0;
    padding:15px;
    border-radius:20px;
    text-align:center;
    cursor:pointer;
    transition:0.3s;
}
#featuresModal .modal-content .feature-item:hover{
    transform:scale(1.05);
    box-shadow:0 8px 20px rgba(0,0,0,0.3);
}

/* FOOTER */
footer{margin-top:30px;background:#1b6d3c;color:white;text-align:center;padding:12px;}

/* RESPONSIVE */
@media(max-width:768px){
    .container{flex-direction:column;}
    .left, .right{width:100%;}
    .hero h1{font-size:32px;}
    .hero p{font-size:16px;}
    .filters{flex-direction:column;}
}
</style>
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
  <a href="index.php"><button class="nav-btn">Home</button></a>
  <button class="nav-btn" id="menuToggle">≡</button>
</div>

<!-- HERO -->
<div class="hero">
<div>
<h1>Real Time <span>Disaster</span><br>Updates</h1>
<p>Get real-time updates on natural disasters happening in Bangladesh</p>
</div>
</div>

<!-- FILTERS -->
<div class="filters">
<button id="allBtn" class="active" onclick="filterAll()">All Disasters</button>
<button id="recentBtn" onclick="filterRecent()">Last 24 Hours</button>
<button class="green" onclick="toggleDropdown()">Filters ▼</button>
<div id="filterDropdown">
  <div onclick="filterDisaster('Hurricane')">Hurricane</div>
  <div onclick="filterDisaster('Flood')">Flood</div>
  <div onclick="filterDisaster('Earthquake')">Earthquake</div>
  <div onclick="filterDisaster('Wildfire')">Wildfire</div>
</div>
</div>

<!-- MAIN -->
<div class="container">
<div class="left" id="disasterList">
  <div class="card hurricane recent">
    <div class="status">Started: 2 mins ago</div>
    <img src="Hurricane.png.jpeg">
    <div>
      <h3>Hurricane</h3>
      <p>Cox's Bazar hurricane approaching.</p>
      <a onclick="showDetails('Strong hurricane approaching Coxs Bazar coastal region. Evacuation advised.')">See Details</a>
    </div>
  </div>
  <div class="card flood recent">
    <div class="status">Started: 30 mins ago</div>
    <img src="flood.png.jpeg">
    <div>
      <h3>Flood</h3>
      <p>Sylhet severe flooding.</p>
      <a onclick="showDetails('Heavy rainfall caused flooding in Sylhet. Rescue operations ongoing.')">See Details</a>
    </div>
  </div>
  <div class="card earthquake">
    <div class="status">Started: 30 hours ago</div>
    <img src="earthquake.png.jpeg">
    <div>
      <h3>Earthquake</h3>
      <p>Magnitude 6.1 earthquake near Dhaka.</p>
      <a onclick="showDetails('Earthquake felt near Dhaka region. No major damage reported.')">See Details</a>
    </div>
  </div>
  <div class="card fire recent">
    <div class="status">Started: 3 hours ago</div>
    <img src="wildfire.png.jpeg">
    <div>
      <h3>Wildfire</h3>
      <p>Rajshahi wildfire spreading.</p>
      <a onclick="showDetails('Forest wildfire spreading in Rajshahi. Firefighters are active.')">See Details</a>
    </div>
  </div>
</div>

<div class="right">
  <div class="map">
    <img src="2nd-page.jpg.jpeg">
  </div>
  <div class="quick">
    <h3>Quick Links</h3>
    <button onclick="toggleInfo('report')">🚨 Report a Disaster</button>
    <div id="report" class="infoBox">Call local emergency services to report disasters.</div>

    <button onclick="toggleInfo('shelter')">🏠 Find Shelter</button>
    <div id="shelter" class="infoBox">Nearest shelters available in schools and community centers.</div>

    <button onclick="toggleInfo('safety')">🛟 Safety Tips</button>
    <div id="safety" class="infoBox">Stay calm, move to safe place and follow instructions.</div>

    <button onclick="toggleInfo('contact')">📞 Emergency Contacts</button>
    <div id="contact" class="infoBox">Emergency hotline: 999 | Disaster helpline: 1090</div>
  </div>
</div>
</div>

<footer>© 2026 ShohayNet. All rights reserved.</footer>

<!-- DETAILS MODAL WITH CROSS -->
<div id="detailsModal">
  <div class="modal-content">
    <span class="close" onclick="closeModal()">✖</span>
    <p id="detailsText"></p>
  </div>
</div>

<!-- FEATURES MODAL -->
<div id="featuresModal">
  <div class="modal-content">
    
    <a href="livelocation.php"><div class="feature-item">Live Location Mapping</div></a>
    <a href="resource inventory tracking.php"><div class="feature-item">Resource Inventory Tracking</div></a>
    <a href="volunteer_signup.php"><div class="feature-item">Volunteer Sign-up & Assignment</div></a>
    <a href="emergency report.php"><div class="feature-item">Emergency Reporting Tool</div></a>
    <a href="chat communication.php"><div class="feature-item">Chat & Communication Hub</div></a>
    <a href="aid-request.php"><div class="feature-item">Aid Request Form</div></a>
    <a href="offline mode.php"><div class="feature-item">Offline Mode Support</div></a>
    <a href="data_analytics.php"><div class="feature-item">Data Analytics Dashboard</div></a>
    <a href="integration.php"><div class="feature-item">Integration with Government Services</div></a>
  </div>
</div>

<script>
/* DETAILS MODAL */
function showDetails(text){
  document.getElementById("detailsModal").style.display="block";
  document.getElementById("detailsText").innerText=text;
}
function closeModal(){document.getElementById("detailsModal").style.display="none";}

/* QUICK LINKS */
function toggleInfo(id){
  let box=document.getElementById(id);
  box.style.display = box.style.display=="block"?"none":"block";
}

/* FEATURES MODAL */
const featuresModal=document.getElementById("featuresModal");
document.getElementById("menuToggle").onclick=function(){
  featuresModal.style.display="block";
  document.body.style.overflow="hidden";
}
featuresModal.onclick=function(e){
  if(e.target==featuresModal){
    featuresModal.style.display="none";
    document.body.style.overflow="auto";
  }
}

/* FILTER DROPDOWN */
function toggleDropdown(){
  let d=document.getElementById("filterDropdown");
  d.style.display = d.style.display=="block"?"none":"block";
}
window.onclick = function(e){
  if(!e.target.matches('.green')){
    document.getElementById("filterDropdown").style.display="none";
  }
}

/* FILTER FUNCTIONS */
function clearActive(){
  document.getElementById("allBtn").classList.remove("active");
  document.getElementById("recentBtn").classList.remove("active");
}
function filterAll(){clearActive();document.getElementById("allBtn").classList.add("active");document.querySelectorAll(".card").forEach(c=>c.style.display="flex");}
function filterRecent(){clearActive();document.getElementById("recentBtn").classList.add("active");document.querySelectorAll(".card").forEach(c=>c.style.display=c.classList.contains("recent")?"flex":"none");}
function filterDisaster(name){clearActive();document.querySelectorAll(".card").forEach(c=>c.style.display=c.querySelector("h3").innerText==name?"flex":"none");}
</script>

</body>
</html>
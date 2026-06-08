
<?php
// PHP ready file 
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ShohayNet</title>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
*{
  margin:0;
  padding:0;
  box-sizing:border-box;
  font-family: 'Arial', Helvetica, sans-serif;
}

body{
  background:#f4f4f4;
  overflow-x:hidden;
}

/* Header */
header{
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:10px 40px;
  background:white;
  box-shadow:0 2px 15px rgba(0,0,0,0.1);
  position:sticky;
  top:0;
  z-index:1000;
}
header:hover{
  box-shadow:0 4px 20px rgba(0,0,0,0.2);
}

.logo img{
  height:50px;
  width:auto;
  cursor:pointer;
  transition:0.3s;
}
.logo img:hover{
  transform:scale(1.1);
}

.nav-right{
  display:flex;
  align-items:center;
  gap:25px;
  font-size:22px;
}
.nav-right i{
  color:#1b6d3c;
  cursor:pointer;
  transition:0.3s;
}
.nav-right i:hover{
  color:#145a28;
  transform:scale(1.2);
}

/* Hero Section */
.hero{
  position:relative;
  height:600px;
  background:
    linear-gradient(rgba(255,255,255,0.15), rgba(255,255,255,0.15)),
    url("bg.jpg.jpeg");
  background-size:cover;
  background-position:center;
  display:flex;
  justify-content:center;
  align-items:center;
  text-align:center;
  color:white;
  filter:brightness(0.9);
  overflow:hidden;
}

.particle{
  position:absolute;
  border-radius:50%;
  background:rgba(255,255,255,0.6);
  opacity:0.6;
  animation: float linear infinite;
}
@keyframes float{
  0% {transform: translateY(0) translateX(0);}
  50% {transform: translateY(-250px) translateX(50px);}
  100% {transform: translateY(0) translateX(0);}
}

.hero-overlay{
  max-width:700px;
  position:relative;
  z-index:2;
}

.hero h1{
  font-size:60px;
  font-weight:bold;
  text-shadow:2px 2px 15px rgba(0,0,0,0.6);
  margin-bottom:10px;
}

.hero h3{
  font-size:24px;
  text-shadow:1px 1px 6px rgba(0,0,0,0.4);
  margin-bottom:20px;
}

.hero p{
  background:rgba(27,109,60,0.85);
  padding:15px 20px;
  border-radius:12px;
  line-height:1.6;
  font-size:16px;
  box-shadow:0 4px 15px rgba(0,0,0,0.3);
}

/* Buttons with ripple */
.buttons{
  margin-top:25px;
  display:flex;
  justify-content:center;
  gap:20px;
  flex-wrap:wrap;
  position:relative;
}

.join, .donate{
  position:relative;
  overflow:hidden;
  min-width:150px;
  text-align:center;
  padding:12px 35px;
  border:none;
  border-radius:35px;
  font-size:16px;
  cursor:pointer;
  transition:0.3s;
  box-shadow:0 6px 15px rgba(0,0,0,0.2);
}
.join{background:#1b6d3c; color:white;}
.donate{background:#d32027; color:white;}
.join:hover{background:#145a28; transform:scale(1.05); box-shadow:0 8px 20px rgba(0,0,0,0.3);}
.donate:hover{background:#a5171f; transform:scale(1.05); box-shadow:0 8px 20px rgba(0,0,0,0.3);}

/* Features Section */
.features{
  padding:60px 20px;
  text-align:center;
  background:#f9f9f9;
}
.features h2{
  font-size:30px;
  color:#1b6d3c;
  margin-bottom:50px;
  font-weight:bold;
}
.feature-line{
  display:flex;
  justify-content:center;
  gap:20px;
  flex-wrap:wrap;
  margin-bottom:25px;
}
.box{
  background:#1b6d3c;
  color:white;
  padding:20px;
  border-radius:30px;
  font-size:16px;
  flex:1 1 200px;
  max-width:300px;
  text-align:center;
  cursor:pointer;
  transition:0.3s;
  box-shadow:0 6px 15px rgba(0,0,0,0.2);
}
.box:hover{
  transform:scale(1.05);
  box-shadow:0 10px 25px rgba(0,0,0,0.3);
}

/* Features Modal Overlay */
#featuresModal{
  display:none;
  position:fixed;
  top:0;
  left:0;
  width:100%;
  height:100%;
  background:rgba(0,0,0,0.8);
  overflow-y:auto;
  z-index:2000;
  padding-top:50px;
}
#featuresModal .modal-content{
  background:#f9f9f9;
  margin:20px auto;
  padding:30px;
  border-radius:15px;
  max-width:500px;
}
#featuresModal .modal-content h2{
  color:#1b6d3c;
  margin-bottom:30px;
}
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
  box-shadow:0 10px 25px rgba(0,0,0,0.3);
}

/* Contact Section */
.contact{
  background:white;
  padding:50px 20px;
  text-align:center;
}
.contact h2{
  color:#1b6d3c;
  margin-bottom:30px;
  font-size:28px;
}
.contact a{
  display:inline-block;
  margin:10px;
  padding:15px 30px;
  border-radius:35px;
  text-decoration:none;
  color:white;
  font-weight:500;
  transition:0.3s;
  box-shadow:0 6px 15px rgba(0,0,0,0.2);
}
.contact a:hover{
  transform:scale(1.05);
  box-shadow:0 10px 25px rgba(0,0,0,0.3);
}

/* Contact buttons colors */
.contact a.whatsapp{background:#25D366;}
.contact a.linkedin{background:#0077B5;}
.contact a.github{background:#333;}

/* Footer */
footer{
  background:#1b6d3c;
  color:white;
  text-align:center;
  padding:15px;
  margin-top:20px;
  font-size:14px;
}

/* Responsive */
@media (max-width:768px){
  .hero h1{font-size:42px;}
  .hero h3{font-size:20px;}
  .buttons{flex-direction:row; justify-content:center;}
  .buttons button{flex:1; min-width:120px;}
}

.video-section{
  position:relative;
  width:90%;         /* container width */
  max-width:900px;   /* optional, max width */
  height:400px;      /* height */
  margin:50px auto;  /* horizontally center + top margin */
  overflow:hidden;
  
}

.video-section video{
  position:absolute;
  top:50%;
  left:50%;
  transform:translate(-50%, -50%); /* perfectly center */
  max-width:100%;
  max-height:100%;
  object-fit:contain; 
}
</style>
</head>
<body>

<header>
  <div class="logo">
    <img src="logo.png.jpeg" alt="ShohayNet Logo">
  </div>
  <div class="nav-right">
  <a href="volunteer_signup.php" class="profile-icon">
    <i class="fa-solid fa-user"></i>
  </a>
  <i class="fa-solid fa-bars" id="menuToggle"></i>
</div>
</header>

<section class="hero">
  <!-- Particles -->
  <div class="particle" style="width:10px; height:10px; top:20%; left:10%; animation-duration:12s;"></div>
  <div class="particle" style="width:15px; height:15px; top:50%; left:80%; animation-duration:18s;"></div>
  <div class="particle" style="width:8px; height:8px; top:70%; left:40%; animation-duration:14s;"></div>
  <div class="particle" style="width:12px; height:12px; top:30%; left:60%; animation-duration:16s;"></div>

  <div class="hero-overlay">
    <h1>ShohayNet</h1>
    <h3>Disaster Help Coordination Platform</h3>
    <p>
      Connecting affected people, volunteers, NGOs and donors in one secure system.<br>
      Fast emergency support, real-time updates and efficient relief operations.
    </p>
    <div class="buttons">
    
      <button class="join" onclick="location.href='volunteer_signup.php'">Join as a Helper</button>
      <button class="donate" onclick="location.href='donate.php'">Donate Now</button>
    </div>
  </div>
</section>

<section class="features">
  <h2>Our Key Features</h2>
  <div class="feature-line">
    <a href="disaster.php"><div class="box">Real Time Disaster Updates</div></a>
    <a href="livelocation.php"><div class="box">Live Location Mapping</div></a>
    <a href="resource inventory tracking.php"><div class="box">Resource Inventory Tracking</div></a>
  </div>
  <div class="feature-line">
    <a href="volunteer_signup.php"><div class="box">Volunteer Sign-up & Assignment</div></a>
    <a href="emergency report.php"><div class="box">Emergency Reporting Tool</div></a>
  </div>
  <div class="feature-line">
    <a href="chat communication.php"><div class="box">Chat & Communication Hub</div></a>
    <a href="aid-request.php"><div class="box">Aid Request Form</div></a>
    <a href="offline mode.php"><div class="box">Offline Mode Support</div></a>
  </div>
  <div class="feature-line">
    <a href="data_analytics.php"><div class="box">Data Analytics Dashboard</div></a>
    <a href="integration.php"><div class="box">Integration with Government Services</div></a>
  </div>
</section>

<!-- Features Modal -->
<div id="featuresModal">
  <div class="modal-content">
    <h2>Our Key Features</h2>
    <a href="disaster.php"><div class="feature-item">Real Time Disaster Updates</div></a>
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

<!-- ===== VIDEO SECTION ===== -->
<section class="video-section">
  <video controls loop>
    <source src="video.mp4.mp4" type="video/mp4">
  </video>

  
</section>

<section class="contact">
  <h2>Contact Us</h2>
  <a class="whatsapp" href="https://wa.me/8801793805720" target="_blank">
    <i class="fa-brands fa-whatsapp"></i> WhatsApp
  </a>
  <a class="linkedin" href="https://www.linkedin.com/in/tanzina-akter-liza/" target="_blank">
    <i class="fa-brands fa-linkedin"></i> LinkedIn
  </a>
  <a class="github" href="https://github.com/tanzinaakterliza" target="_blank">
    <i class="fa-brands fa-github"></i> GitHub
  </a>
</section>

<footer>
  © 2026 ShohayNet. All Rights Reserved.
</footer>

<script>
// Ripple effect on buttons
document.querySelectorAll('.join, .donate, .box, .contact a, .feature-item').forEach(button=>{
  button.addEventListener('click', function(e){
    let ripple = document.createElement('span');
    ripple.className='ripple';
    this.appendChild(ripple);
    ripple.style.left = e.offsetX + 'px';
    ripple.style.top = e.offsetY + 'px';
    setTimeout(()=>{ripple.remove()},600);
  });
});

// Show features modal on hamburger click
document.getElementById('menuToggle').addEventListener('click', function(){
  let modal = document.getElementById('featuresModal');
  modal.style.display = 'block';
});

// Close modal if clicked outside content
document.getElementById('featuresModal').addEventListener('click', function(e){
  if(e.target === this){
    this.style.display = 'none';
  }
});
</script>

</body>
</html>
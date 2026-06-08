<?php
include 'db_config.php';   

$success = false;
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $full_name      = trim($_POST['full_name'] ?? '');
    $mobile         = trim($_POST['mobile'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $amount         = (float)($_POST['amount'] ?? 0);
    $payment_method = $_POST['payment_method'] ?? '';
    $message_text   = trim($_POST['message'] ?? '');

    // Validation
    if (!empty($full_name) && !empty($mobile) && !empty($email) && $amount > 0 && !empty($payment_method)) {
        
        $sql = "INSERT INTO donations (full_name, mobile, email, amount, payment_method, message, status) 
                VALUES (?, ?, ?, ?, ?, ?, 'Pending')";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssdss", $full_name, $mobile, $email, $amount, $payment_method, $message_text);

        if ($stmt->execute()) {
            $success = true;
            $message = "✅ Thank you! Your donation of " . number_format($amount, 2) . " BDT has been recorded successfully.";
        } else {
            $message = "❌ Database Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "❌ Please fill all required fields correctly.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Donate - ShohayNet</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family: Arial, sans-serif;
}

body{
    background:#0f6b2e;
}

/* TOP BAR */
.top-bar{
    background:#137a36;
    padding:10px 15px;
    display:flex;
    justify-content:flex-end;
    align-items:center;
    color:#fff;
    font-size:18px;
    gap:17px;
}

.nav-btn{
    cursor:pointer;
    padding:6px 10px;
    border-radius:8px;
    transition:0.3s;
}

.nav-btn:hover{
    background:rgba(255,255,255,0.2);
}

.nav-btn:active{
    transform:scale(0.9);
}

.nav-btn.active{
    background:#0d5e28;
}

/* FEATURE PANEL */
.feature-panel{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.4);
    display:none;
    justify-content:center;
    align-items:center;
    z-index:999;
}

.feature-box{
    background:#fff;
    width:90%;
    max-width:400px;
    max-height:80vh;   /* scroll limit */
    border-radius:20px;
    padding:20px;
    overflow-y:auto;   /* scroll enable */
}

/* optional scrollbar design */
.feature-box::-webkit-scrollbar{
    width:5px;
}

.feature-box::-webkit-scrollbar-thumb{
    background:#ccc;
    border-radius:10px;
}
/* FEATURE BUTTONS */
.feature-list div{
    background:#2e7d32;
    color:#fff;
    padding:15px;
    margin:10px 0;
    border-radius:25px;
    text-align:center;
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
}

.feature-list div:hover{
    background:#1b5e20;
}

/* HERO */
.hero{
    width:100%;
    height:350px;
    background:url('donation.jpg.png') center/cover no-repeat;
}

/* TITLE */
.title{
    text-align:center;
    margin-top:-25px;
}

.title h1{
    display:inline-block;
    background:#fff;
    color:#137a36;
    padding:10px 25px;
    border-radius:10px;
    font-size:28px;
}

.title p{
    color:#fff;
    margin-top:10px;
}

.title small{
    color:#dcdcdc;
}

/* FORM */
.card{
    background:#eee;
    width:90%;
    max-width:420px;
    margin:20px auto;
    border-radius:25px;
    padding:20px;
}

.card h2{
    text-align:center;
    color:#c0392b;
}

.card p{
    text-align:center;
    font-size:13px;
    margin:10px 0 15px;
}

.form-box{
    background:linear-gradient(180deg,#7fd3a6,#4fbf84);
    padding:15px;
    border-radius:20px;
}

label{
    font-size:13px;
    display:block;
    margin-top:10px;
}

input, select, textarea{
    width:100%;
    padding:10px;
    margin-top:5px;
    border:none;
    border-radius:10px;
}

textarea{
    height:80px;
}

.btn{
    margin-top:15px;
    width:100%;
    padding:12px;
    border:none;
    border-radius:25px;
    background:#d63031;
    color:#fff;
    font-size:16px;
    cursor:pointer;
}

/* FOOTER */
.footer{
    text-align:center;
    color:#ddd;
    font-size:12px;
    margin:10px 0 20px;
}
</style>
</head>

<body>

<!-- TOP -->
<div class="top-bar">
   <span class="nav-btn" onclick="location.href='index.php'">Home</span>
    <span class="nav-btn" onclick="setActive(this); openPanel()">☰</span>
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

<!-- HERO -->
<div class="hero"></div>

<!-- TITLE -->
<div class="title">
    <h1>Donate Now</h1>
    <p>Your Help, Their Hope</p>
    <small>আপনার সহায়তা, তাদের আশা</small>
</div>

<!-- FORM -->
<div class="card">
    <h2>Make a Donation</h2>
    <p>Your contributions helps us deliver timely aid to communities affected by disasters across Bangladesh.</p>
<div class="form-box">
    <form method="POST">
        <label>Full Name *</label>
        <input type="text" name="full_name" required>

        <label>Mobile Number *</label>
        <input type="tel" name="mobile" placeholder="01XXXXXXXXX" required>

        <label>Email Address *</label>
        <input type="email" name="email" required>

        <label>Donation Amount (BDT) *</label>
        <input type="number" name="amount" min="10" required>

        <label>Payment Method (Our Number: 01987654321) *</label>
        <select name="payment_method" required>
            <option value="">--Select Method--</option>
            <option value="bKash">bKash</option>
            <option value="Nagad">Nagad</option>
            <option value="Rocket">Rocket</option>
        </select>

        <label>Message (Optional)</label>
        <textarea name="message" rows="3"></textarea>

        <button type="submit" class="btn">Complete Donation</button>
    </form>
</div>
<div class="footer">
    © 2025 ShohayNet, All rights reserved.
</div>

<script>
function openPanel(){
    document.getElementById("featurePanel").style.display = "flex";
}

function closePanel(e){
    if(e.target.id === "featurePanel"){
        document.getElementById("featurePanel").style.display = "none";
    }
}

function goHome(){
    alert("Home Clicked!");
}

function setActive(el){
    document.querySelectorAll(".nav-btn").forEach(btn=>{
        btn.classList.remove("active");
    });
    el.classList.add("active");
}

document.getElementById("donationForm").addEventListener("submit", function(e){
    e.preventDefault();
    alert("✅ Donation Submitted Successfully!");
    this.reset();
});
</script>

</body>
</html>


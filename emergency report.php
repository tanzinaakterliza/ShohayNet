<?php
// ==================== DATABASE CONNECTION ====================
include 'db_config.php';

$success = false;
$message = "";

 if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $report_type     = $_POST['report_type'] ?? '';
    $severity        = $_POST['severity'] ?? '';
    $location_name   = trim($_POST['location_name'] ?? '');
    $address         = trim($_POST['address'] ?? '');
    $city            = trim($_POST['city'] ?? '');
    $description     = trim($_POST['description'] ?? '');
    $affected_people = (int)($_POST['affected_people'] ?? 0);
    $contact_name    = trim($_POST['contact_name'] ?? '');
    $contact_phone   = trim($_POST['contact_phone'] ?? '');
    $contact_email   = trim($_POST['contact_email'] ?? '');

    if (!empty($report_type) && !empty($severity) && !empty($location_name) && !empty($description) && !empty($contact_name) && !empty($contact_phone)) {

        $sql = "INSERT INTO emergency_reports 
        (report_type, severity, location_name, address, city, description, affected_people, contact_name, contact_phone, contact_email, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("ssssssisss",
            $report_type,
            $severity,
            $location_name,
            $address,
            $city,
            $description,
            $affected_people,
            $contact_name,
            $contact_phone,
            $contact_email
        );

        if ($stmt->execute()) {
            $success = true;
            $message = "✅ Emergency report submitted successfully! Help is on the way.";
        } else {
            $message = "❌ Execute Error: " . $stmt->error;
        }

        $stmt->close();

    } else {
        $message = "❌ Required fields fill করো";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>ShohayNet – Emergency Reporting Tool</title>
<link href="https://fonts.googleapis.com/css2?family=Changa+One:ital@0;1&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
    --green-dark:  #1a4d2e;
    --green-main:  #2e7d32;
    --green-mid:   #388e3c;
    --green-light: #e8f5e9;
    --green-bg:    #f1f8f1;
    --white:       #ffffff;
    --text-dark:   #0d1f0f;
    --text-mid:    #3d5c40;
    --text-muted:  #7a9a7e;
    --border:      #d6e8d6;
    --red:         #d32f2f;
    --orange:      #f57c00;
    --yellow:      #fbc02d;
    --navy:        #1a237e;
    --font-head:   'Changa One', sans-serif;
    --font-body:   'Inter', sans-serif;
  }
  body {
    font-family: var(--font-body);
    background: #f7faf7;
    color: var(--text-dark);
    min-height: 100vh;
    padding-bottom: 90px;
  }
  /* ── TOP NAV ── */
  .topnav {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 28px;
    background: var(--white);
    border-bottom: 1px solid var(--border);
    position: sticky; top: 0; z-index: 100;
  }
  .logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
  .logo-icon {
    width: 44px; height: 44px; border-radius: 50%;
    background: var(--green-main);
    display: flex; align-items: center; justify-content: center;
  }
  .logo-text { font-family: var(--font-head); font-size: 22px; color: var(--green-dark); }
  .nav-icons { display: flex; align-items: center; gap: 20px; color: var(--green-dark); }
  .nav-icons svg { cursor: pointer; }

  /* ── PAGE ── */
  .page { max-width: 820px; margin: 0 auto; padding: 32px 20px 40px; }

  .page-title {
    font-family: var(--font-head); font-size: 32px;
    color: var(--text-dark); letter-spacing: .3px;
  }
  .page-sub { font-size: 14px; color: var(--text-muted); margin-top: 5px; }

  /* ── ACTION CARDS ── */
  .action-cards {
    display: grid; grid-template-columns: repeat(3, 1fr);
    gap: 14px; margin-top: 24px;
  }
  .action-card {
    border: 1.5px solid var(--border);
    border-radius: 12px;
    padding: 18px 18px 16px;
    background: var(--white);
    cursor: pointer; transition: box-shadow .2s;
  }
  .action-card:hover { box-shadow: 0 4px 16px rgba(46,125,50,.12); }
  .action-card.primary { background: var(--green-main); border-color: var(--green-main); }
  .action-card.primary .card-title,
  .action-card.primary .card-sub { color: #fff; }
  .action-card.primary svg { stroke: #fff; }
  .card-icon { margin-bottom: 10px; }
  .card-title { font-weight: 700; font-size: 15px; color: var(--text-dark); margin-bottom: 3px; }
  .card-sub { font-size: 12.5px; color: var(--text-muted); margin-bottom: 14px; }
  .card-btn {
    width: 100%; padding: 9px 0;
    border-radius: 7px; font-size: 13px; font-weight: 600;
    cursor: pointer; font-family: var(--font-body); transition: all .2s;
  }
  .card-btn.outline-green {
    border: 1.5px solid #c8e6c9; background: transparent;
    color: #fff;
  }
  .card-btn.outline-green:hover { background: rgba(255,255,255,.15); }
  .card-btn.outline-dark {
    border: 1.5px solid var(--border); background: transparent;
    color: var(--text-dark);
  }
  .card-btn.outline-dark:hover { background: var(--green-light); border-color: var(--green-main); }
  .card-btn.link-btn {
    border: none; background: transparent;
    color: var(--green-main); font-weight: 600;
  }
  .card-btn.link-btn:hover { text-decoration: underline; }

  /* ── SECTION ── */
  .section {
    background: var(--white);
    border: 1.5px solid var(--border);
    border-radius: 12px;
    padding: 24px 22px;
    margin-top: 22px;
  }
  .section-title {
    font-weight: 700; font-size: 16px;
    color: var(--text-dark); margin-bottom: 16px;
    display: flex; align-items: center; gap: 8px;
  }

  /* Emergency Type Grid */
  .emergency-grid {
    display: grid; grid-template-columns: repeat(3, 1fr);
    gap: 12px;
  }
  .em-card {
    border: 1.5px solid var(--border);
    border-radius: 10px; padding: 18px 12px;
    text-align: center; cursor: pointer;
    transition: all .2s; background: var(--white);
  }
  .em-card:hover, .em-card.selected {
    border-color: var(--green-main);
    background: var(--green-light);
  }
  .em-icon { font-size: 30px; margin-bottom: 8px; }
  .em-label { font-size: 13px; font-weight: 600; color: var(--text-dark); }

  /* Severity */
  .severity-list { display: flex; flex-direction: column; gap: 10px; }
  .sev-item {
    display: flex; align-items: center; gap: 13px;
    border: 1.5px solid var(--border);
    border-radius: 10px; padding: 14px 16px;
    cursor: pointer; transition: all .2s;
  }
  .sev-item:hover { border-color: var(--green-main); background: var(--green-light); }
  .sev-item input[type="radio"] { display: none; }
  .sev-item.selected { border-color: var(--green-main); background: var(--green-light); }
  .sev-dot {
    width: 13px; height: 13px; border-radius: 50%; flex-shrink: 0;
  }
  .sev-info .sev-name { font-weight: 600; font-size: 14.5px; color: var(--text-dark); }
  .sev-info .sev-desc { font-size: 12.5px; color: var(--text-muted); margin-top: 2px; }

  /* Form fields */
  .field { margin-bottom: 14px; }
  .field label {
    display: block; font-size: 13px; font-weight: 500;
    color: var(--text-mid); margin-bottom: 6px;
  }
  .field input, .field textarea {
    width: 100%; border: 1.5px solid var(--border);
    border-radius: 9px; padding: 11px 14px;
    font-family: var(--font-body); font-size: 14px;
    color: var(--text-dark); background: var(--white);
    outline: none; transition: border .2s;
  }
  .field input:focus, .field textarea:focus { border-color: var(--green-main); }
  .field input::placeholder, .field textarea::placeholder { color: #b0bdb0; }
  .field textarea { resize: vertical; min-height: 110px; }
  .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

  /* Photo upload */
  .upload-box {
    border: 1.5px dashed var(--border);
    border-radius: 10px; padding: 28px 20px;
    text-align: center; cursor: pointer;
    transition: border .2s; background: #fafcfa;
  }
  .upload-box:hover { border-color: var(--green-main); background: var(--green-light); }
  .upload-box p { font-size: 13px; color: var(--text-muted); margin: 8px 0 12px; }
  .upload-box .up-btn {
    background: transparent; border: 1.5px solid var(--border);
    color: var(--text-dark); font-family: var(--font-body);
    font-size: 13px; font-weight: 600; padding: 8px 20px;
    border-radius: 7px; cursor: pointer; transition: all .2s;
  }
  .upload-box .up-btn:hover { border-color: var(--green-main); background: var(--green-light); }
  input[type="file"] { display: none; }

  /* Additional info section label */
  .additional-header {
    font-family: var(--font-head); font-size: 17px;
    color: var(--text-dark); margin-bottom: 16px;
  }

  /* ── STICKY SUBMIT ── */
  .submit-bar {
  display: flex;
  justify-content: center;
  margin-top: 20px;
}
  
  .submit-btn {
  width: 35%;
  padding: 14px;
  background: var(--green-main);
  border: none;
  border-radius: 10px;
  font-size: 15px;
  font-weight: 600;
  color: #fff;
  cursor: pointer;
}

.submit-btn:hover {
  background: var(--green-dark);
}
   

  /* scrollbar */
  ::-webkit-scrollbar { width: 5px; }
  ::-webkit-scrollbar-track { background: transparent; }
  ::-webkit-scrollbar-thumb { background: #c5dcc5; border-radius: 4px; }

.logo img {
  width: 65px;   
  height: 65px;
  object-fit: cover;
}

.footer {
  position: fixed;
  bottom: 0;
  left: 0;
  width: 100%;
  background: #1a4d2e;
  color: #fff;
  padding: 12px 20px;
  z-index: 150;
}

.footer-content {
  display: flex;
  flex-direction: column; 
  justify-content:center;
  align-items: center;
  text-align: center;
  font-size: 13px;
}

.footer-links a {
  color: #c8e6c9;
  margin-left: 12px;
  text-decoration: none;
}

.footer-links a:hover {
  text-decoration: underline;
}

</style>
</head>
<body>

<!-- TOP NAV -->
<nav class="topnav">
    <div class="logo">
    <img src="logo.png.jpeg" alt="ShohayNet Logo">
  </div>
  <div class="nav-icons">
    <a href="index.php">
      <svg width="26" height="26" viewBox="0 0 24 24" fill="none" 
           stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
           style="cursor:pointer;">
        <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
        <polyline points="9 22 9 12 15 12 15 22"/>
      </svg>
    </a>
    
    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
  </div>
</nav>

<div class="page">
    <?php if($message): ?>
<div style="margin:15px 0;padding:12px;border-radius:8px;
background:<?= $success ? '#e8f5e9' : '#ffebee' ?>;
color:<?= $success ? '#2e7d32' : '#d32f2f' ?>;">
<?= $message ?>
</div>
<?php endif; ?>

<form method="POST">
<input type="hidden" name="report_type" id="report_type">

  <div class="page-title">Emergency Reporting Tool</div>
  <div class="page-sub">Report an emergency incident and get immediate assistance</div>

  <!-- Action Cards -->
  <div class="action-cards">
    <div class="action-card primary">
      <div class="card-icon">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.67A2 2 0 012.2 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 7.91a16 16 0 006.72 6.72l1.06-1.06a2 2 0 012.11-.45c.907.34 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
      </div>
      <div class="card-title">Call Emergency Services</div>
      <div class="card-sub">For immediate life emergencies</div>
      <button class="card-btn outline-green">Call 1-800-HELP-NOW</button>
    </div>
    <div class="action-card">
      <div class="card-icon">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#2e7d32" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
      </div>
      <div class="card-title">Share Your Location</div>
      <div class="card-sub">Help responders find you quickly</div>
      <button class="card-btn outline-dark" onclick="shareLocation(this)">Enable GPS Location</button>
    </div>
    <div class="action-card">
      <div class="card-icon">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#2e7d32" stroke-width="2"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
      </div>
      <div class="card-title">Quick Photo Report</div>
      <div class="card-sub">Capture and submit visual evidence</div>
      <button class="card-btn link-btn">Open Camera</button>
    </div>
  </div>

  <!-- Emergency Type -->
  <div class="section">
    <div class="section-title">What type of emergency is this?</div>
    <div class="emergency-grid">
      <?php
      $types = [
        ['emoji'=>'🔥','label'=>'Fire'],
        ['emoji'=>'🌊','label'=>'Flood'],
        ['emoji'=>'🏚️','label'=>'Earthquake'],
        ['emoji'=>'🏥','label'=>'Medical Emergency'],
        ['emoji'=>'🚗','label'=>'Accident'],
        ['emoji'=>'⚠️','label'=>'Other'],
      ];
      foreach($types as $t): ?>
      <div class="em-card" onclick="selectType(this)">
        <div class="em-icon"><?= $t['emoji'] ?></div>
        <div class="em-label"><?= $t['label'] ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Severity --> <div class="section"> 
    <div class="section-title">How severe is the situation?</div> 
    <div class="severity-list"> 
      <?php $severities = [ ['label'=>'Critical','desc'=>'Life-threatening, immediate response needed','color'=>'#d32f2f'], 
      ['label'=>'High', 'desc'=>'Serious situation, urgent attention required','color'=>'#f57c00'], 
      ['label'=>'Medium', 'desc'=>'Significant issue, response needed soon', 'color'=>'#fbc02d'], ['label'=>'Low', 'desc'=>'Minor issue, can be scheduled', 'color'=>'#1a237e'], ]; 
     foreach($severities as $i=>$s): ?> 
        <label class="sev-item" onclick="selectSev(this)">
        <input type="radio" name="severity" value="<?= strtolower($s['label']) ?>">
          <span class="sev-dot" style="background:<?= $s['color'] ?>"></span> 
          <div class="sev-info"> <div class="sev-name"><?= $s['label'] ?></div> 
          <div class="sev-desc"><?= $s['desc'] ?></div> </div> </label> <?php endforeach; ?> 
        </div>
       </div>
  <!-- Location Details -->
  <div class="section">
    <div class="section-title">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2e7d32" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
      Location Details
    </div>
  
    <div class="field">
      <label>Address or Landmark</label>
      <input type="text" name="location_name" placeholder="Enter specific location..."/>
    </div>
    <div class="field-row">
      <div class="field" style="margin-bottom:0">
        <label>City</label>
        <input type="text" name="city" placeholder="City name..."/>
      </div>
      <div class="field" style="margin-bottom:0">
        <label>Zip Code</label> 
     <input type="text" name="zip_code" placeholder="Enter zip code..."/>
      </div>
    </div>
  </div>

  <!-- Incident Description -->
  <div class="section">
    <div class="section-title">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#2e7d32" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
      Incident Description
    </div>
    <div class="field" style="margin-bottom:0">
      <textarea name="description" placeholder="Provide as much detail as possible about the emergency situation..."></textarea>
    </div>
  </div>

  <!-- Additional Information -->
  <div class="section">
    <div class="additional-header">Additional Information</div>

    <div class="field-row" style="margin-bottom:14px">
      <div class="field" style="margin-bottom:0">
        <label>
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle;margin-right:4px"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/></svg>
          Number of People Affected
        </label>
        <input type="number" name="affected_people" placeholder="Approximate number..."/>
      </div>
      <div class="field" style="margin-bottom:0">
        <label>
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle;margin-right:4px"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          When did this occur?
        </label>
        <input type="datetime-local"/>
      </div>
    </div>

    <div class="field" style="margin-bottom:0">
      <label>
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle;margin-right:4px"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
        Upload Photos (Optional)
      </label>
      <div class="upload-box" onclick="document.getElementById('photoUpload').click()">
        <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#9ab09a" stroke-width="1.5"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
        <p>Upload photos to help responders assess the situation</p>
        <button class="up-btn" type="button">Select Photos</button>
        <input type="file" id="photoUpload" accept="image/*" multiple/>
      </div>
    </div>
  </div>

  <!-- Contact Info -->
  <div class="section">
    <div class="additional-header">Your Contact Information</div>
    <div class="field-row">
      <div class="field">
  <label>Your Name</label>
  <input type="text" name="contact_name" placeholder="Full name..."/>
      </div>
 <div class="field">
  <label>Phone Number</label>
  <input type="tel" name="contact_phone" placeholder="Contact number..."/>
</div>

<div class="field">
  <label>Email</label>
  <input type="email" name="contact_email" placeholder="Email address..."/>
</div>

<div class="field">
  <label>Full Address</label>
  <input type="text" name="address" placeholder="Enter full address..."/>
</div>
      </div>
    </div>
  </div>
</div>

<!-- Sticky Submit -->
<div class="submit-bar">
  <button type="submit" class="submit-btn">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2">
      <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
      <line x1="12" y1="9" x2="12" y2="13"/>
      <line x1="12" y1="17" x2="12.01" y2="17"/>
    </svg>
    Submit Emergency Report
     </form>
  </button>
</div>

<footer class="footer">
  <div class="footer-content">
    <p>© 2026 ShohayNet. All rights reserved.</p>  
  </div>
</footer>

<script>
  function selectType(el) {
  document.querySelectorAll('.em-card').forEach(c => c.classList.remove('selected'));
  el.classList.add('selected');

  document.getElementById('report_type').value =
    el.querySelector('.em-label').innerText.trim();
}
  
  function selectSev(el) {
    document.querySelectorAll('.sev-item').forEach(s => s.classList.remove('selected'));
    el.classList.add('selected');
    el.querySelector('input[type="radio"]').checked = true;
  }
  function shareLocation(btn) {
    if (navigator.geolocation) {
      btn.textContent = 'Locating...';
      navigator.geolocation.getCurrentPosition(function(pos){
        btn.textContent = '✓ Location Shared';
        btn.style.borderColor = '#2e7d32';
        btn.style.color = '#2e7d32';
      }, function(){
        btn.textContent = 'Could Not Get Location';
      });
    }
  }
 document.querySelector("form").addEventListener("submit", function(e){
    if(
        document.getElementById("report_type").value === "" ||
        !document.querySelector('input[name="severity"]:checked')
    ){
        alert("Please complete required fields");
        e.preventDefault();
    }
});

</script>
</body>
</html> 







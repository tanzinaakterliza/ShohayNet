<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOS - ShohayNet</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #0d4d28; --warning: #f39c12; --online: #2e7d32; --bg: rgba(180, 210, 255, 0.6); }
        body { margin: 0; font-family: 'Segoe UI', sans-serif; background: url('https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=2070') center/cover fixed; }
        .container { background: var(--bg); min-height: 100vh; display: flex; flex-direction: column; }
        header { background: white; display: flex; justify-content: space-between; padding: 12px 20px; align-items: center; }
        .logo { color: var(--primary); font-weight: bold; display: flex; align-items: center; gap: 8px; }
        
        main { flex: 1; padding: 20px; max-width: 450px; margin: auto; width: 90%; }
        
        /* Dynamic Banner Style */
        .status-banner { padding: 18px; border-radius: 12px; display: flex; align-items: center; gap: 20px; margin-bottom: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); transition: 0.3s; }
        .status-offline { background: var(--warning); color: black; }
        .status-online { background: var(--online); color: white; }

        .sos-h1 { color: #0a3d21; text-align: center; font-size: 42px; font-weight: 800; margin: 10px 0 20px 0; }
        .sos-card { background: rgba(255, 255, 255, 0.85); padding: 30px; border-radius: 30px; }
        
        .label-text { display: block; font-weight: bold; margin-bottom: 12px; font-size: 18px; color: #333; }
        .large-select { width: 100%; padding: 18px; border-radius: 12px; border: 1px solid #aaa; background: #d9d9d9; font-size: 18px; margin-bottom: 20px; outline: none; }
        .large-area { width: 100%; padding: 18px; border-radius: 12px; border: 1px solid #aaa; background: #b0b0b0; height: 120px; box-sizing: border-box; font-size: 16px; margin-bottom: 20px; resize: none; color: #333; outline: none; }

        .btn-live { background: #052614; color: white; width: 100%; padding: 15px; border: none; border-radius: 12px; margin-bottom: 25px; font-weight: bold; cursor: pointer; display: flex; justify-content: center; align-items: center; gap: 10px; }

        .dots { display: flex; gap: 15px; font-size: 15px; margin-bottom: 30px; }
        .dots input { display: none; }
        .dot-item { display: flex; align-items: center; gap: 8px; padding: 8px 15px; border-radius: 25px; background: white; cursor: pointer; border: 2px solid transparent; }
        .dot { height: 12px; width: 12px; border-radius: 50%; display: inline-block; }
        
        input#s-high:checked + label { border-color: red; background: #ffeaea; font-weight: 800; }
        input#s-med:checked + label { border-color: orange; background: #fff5e6; font-weight: 800; }
        input#s-low:checked + label { border-color: green; background: #e6f7e6; font-weight: 800; }

        .btn-save { background: var(--warning); color: black; width: 100%; padding: 18px; border: none; border-radius: 15px; font-weight: 800; font-size: 20px; cursor: pointer; box-shadow: 0 4px 0 #b37400; transition: 0.1s; }
        .btn-online { background: var(--online); color: white; box-shadow: 0 4px 0 #1b4d20; }
        
        footer { background: var(--primary); color: white; text-align: center; padding: 20px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="logo"><i class="fas fa-hands-helping"></i> ShohayNet</div>
            <nav>
                <a href="index.php"><i class="fas fa-home"></i></a>
                <a href="volunteer_signup.php"><i class="fas fa-user-circle"></i></a>
                <i class="fas fa-bars"></i></a>
            </nav>
            
        </header>
        <main>
            <div id="connectionBanner" class="status-banner status-online">
                <i id="statusIcon" class="fas fa-wifi" style="font-size: 35px;"></i>
                <div><b id="statusTitle">ONLINE MODE</b><br><small id="statusDesc">Data will be sent directly to server.</small></div>
            </div>

            <h1 class="sos-h1">Log SOS</h1>
            <div class="sos-card">
                <span class="label-text">Type of Emergency</span>
                <select class="large-select" id="sosType">
                    <option value="Medical Support">Medical Support</option>
                    <option value="Severe Flooding">Severe Flooding</option>
                    <option value="Rescue">Rescue</option>
                    <option value="Fire Emergency">Fire Emergency</option>
                </select>
                
                <textarea class="large-area" id="sosDesc" placeholder="Describe your situation briefly..."></textarea>
                
                <button class="btn-live" id="locBtn" onclick="captureLoc()">
                    <i class="fas fa-map-marker-alt"></i> <span id="locStatus">Capture Current Location</span>
                </button>

                <div class="label-text">Urgency Level</div>
                <div class="dots">
                    <input type="radio" name="sos_u" id="s-high" value="High"><label for="s-high" class="dot-item"><span class="dot" style="background:red"></span> High</label>
                    <input type="radio" name="sos_u" id="s-med" value="Medium" checked><label for="s-med" class="dot-item"><span class="dot" style="background:orange"></span> Med</label>
                    <input type="radio" name="sos_u" id="s-low" value="Low"><label for="s-low" class="dot-item"><span class="dot" style="background:green"></span> Low</label>
                </div>

                <button id="mainBtn" class="btn-save btn-online" onclick="handleSOS()">Send SOS <i class="fas fa-paper-plane"></i></button>
            </div>
        </main>
        <footer><p>© 2026 ShohayNet, All rights reserved.</p></footer>
    </div>

    <script>
        let capturedGPS = "Not Captured";

        function updateStatus() {
            const banner = document.getElementById('connectionBanner');
            const icon = document.getElementById('statusIcon');
            const title = document.getElementById('statusTitle');
            const desc = document.getElementById('statusDesc');
            const mainBtn = document.getElementById('mainBtn');

            if (navigator.onLine) {
                banner.className = "status-banner status-online";
                icon.className = "fas fa-wifi";
                title.innerText = "ONLINE MODE";
                desc.innerText = "Data will be sent directly to server.";
                mainBtn.innerHTML = 'Send SOS <i class="fas fa-paper-plane"></i>';
                mainBtn.classList.add('btn-online');
            } else {
                banner.className = "status-banner status-offline";
                icon.className = "fas fa-wifi-slash";
                title.innerText = "YOU ARE OFFLINE";
                desc.innerText = "Emergency logs saved locally.";
                mainBtn.innerHTML = 'Save SOS Locally <i class="fas fa-save"></i>';
                mainBtn.classList.remove('btn-online');
            }
        }

        window.addEventListener('online', updateStatus);
        window.addEventListener('offline', updateStatus);
        updateStatus(); 

        function captureLoc() {
            if (navigator.geolocation) {
                document.getElementById('locStatus').innerText = "Locating...";
                navigator.geolocation.getCurrentPosition(pos => {
                    capturedGPS = `${pos.coords.latitude.toFixed(5)}, ${pos.coords.longitude.toFixed(5)}`;
                    document.getElementById('locStatus').innerText = "Location Captured";
                    document.getElementById('locBtn').style.background = "#0d4d28";
                });
            }
        }

        function handleSOS() {
            const urgencyVal = document.querySelector('input[name="sos_u"]:checked').value;
            const data = {
                full_name: "SOS User",
                emergency_types: document.getElementById('sosType').value,
                description: document.getElementById('sosDesc').value,
                urgency: urgencyVal,
                area_gps: capturedGPS,
                timestamp: new Date().toLocaleString()
            };

            if (navigator.onLine) {
                sendToServer(data, true);
            } else {
                saveToLocal(data);
            }
        }

        function saveToLocal(data) {
            let logs = JSON.parse(localStorage.getItem('shohay_sos_logs')) || [];
            logs.push(data);
            localStorage.setItem('shohay_sos_logs', JSON.stringify(logs));
            alert("You are Offline! Data is saved.");
            document.getElementById('sosDesc').value = "";
        }

        function sendToServer(data, isSingle) {
            let fd = new FormData();
            fd.append('full_name', data.full_name);
            fd.append('emergency_types', data.emergency_types);
            fd.append('description', data.description + (data.timestamp ? ` [Logged at ${data.timestamp}]` : ''));
            fd.append('urgency', data.urgency);
            fd.append('area_gps', data.area_gps);

            fetch('submit_request.php', { method: 'POST', body: fd })
            .then(res => res.text())
            .then(resData => {
                if(isSingle) alert("You are Online! Request is sent.");
                document.getElementById('sosDesc').value = "";
            })
            .catch(err => {
                if(isSingle) saveToLocal(data); 
            });
        }

        window.addEventListener('online', () => {
            const logs = JSON.parse(localStorage.getItem('shohay_sos_logs'));
            if(logs && logs.length > 0) {
                alert("You are Online! Data is signing.");
                logs.forEach((log, index) => {
                    sendToServer(log, false);
                    if(index === logs.length - 1) {
                        localStorage.removeItem('shohay_sos_logs');
                        setTimeout(() => alert("All the Offline data has been signed."), 1000);
                    }
                });
            }
        });
    </script>
</body>
</html>
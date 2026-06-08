<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aid Request - ShohayNet</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #0d4d28; --cancel: #ff1a1a; --bg: rgba(180, 210, 255, 0.6); }
        body { margin: 0; font-family: 'Segoe UI', sans-serif; background: url('https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=2070') center/cover fixed; }
        .container { background: var(--bg); min-height: 100vh; display: flex; flex-direction: column; }
        header { background: white; display: flex; justify-content: space-between; padding: 12px 20px; align-items: center; border-bottom: 1px solid #ddd; }
        .logo { color: var(--primary); font-weight: bold; display: flex; align-items: center; gap: 8px; font-size: 1.1rem; }
        .logo i { font-size: 28px; color: #2e7d32; }
        nav i { color: var(--primary); margin-left: 18px; font-size: 22px; cursor: pointer; }
        main { flex: 1; padding: 20px; max-width: 480px; margin: auto; text-align: center; width: 100%; box-sizing: border-box; }
        .card { background: rgba(255, 255, 255, 0.92); padding: 25px; border-radius: 20px; text-align: left; box-shadow: 0 8px 32px rgba(0,0,0,0.1); }
        .section-h { color: var(--primary); font-weight: bold; margin: 18px 0 10px; display: flex; align-items: center; gap: 8px; font-size: 14px; }
        .row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px; }
        input, select, textarea { width: 100%; padding: 11px; border: 1px solid #ccc; border-radius: 8px; box-sizing: border-box; font-size: 13px; background: #f9f9f9; }
        .btn-loc { background: #0a3d21; color: white; border: none; padding: 10px 15px; border-radius: 20px; width: fit-content; margin: 10px auto; display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 12px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 13px; }
        .urgency { display: flex; gap: 10px; margin-bottom: 10px; }
        .urgency input { display: none; }
        .urgency label { display: flex; align-items: center; gap: 8px; padding: 6px 12px; border-radius: 20px; border: 1.5px solid #eee; cursor: pointer; font-size: 12px; transition: 0.3s; background: white; }
        .dot { height: 10px; width: 10px; border-radius: 50%; display: inline-block; }
        input:checked + label { border-color: var(--primary); background: #e8f5e9; font-weight: bold; }
        .actions { display: flex; gap: 12px; margin-top: 25px; }
        .btn-sub { background: #0a3d21; color: white; border: none; flex: 2; padding: 14px; border-radius: 10px; font-weight: bold; cursor: pointer; }
        .btn-can { background: var(--cancel); color: white; border: none; flex: 1; padding: 14px; border-radius: 10px; font-weight: bold; cursor: pointer; }
        footer { background: var(--primary); color: white; text-align: center; padding: 20px; font-size: 12px; margin-top: 30px; }
        .socials { margin-top: 8px; font-size: 22px; display: flex; justify-content: center; gap: 15px; opacity: 0.9; }
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
            <h1>Aid Request Form</h1>
            <div class="card">
                <form id="mainAidForm">
                    <div class="section-h"><i class="fas fa-user"></i> Personal Info</div>
                    <div class="row">
                        <input type="text" name="full_name" placeholder="Full Name" required>
                        <input type="tel" name="phone" placeholder="Phone Number" required>
                    </div>
                    <div class="row">
                        <input type="text" name="nid" placeholder="NID (Optional)">
                        <select name="family_count">
                            <option value="">Family Size</option>
                            <option value="1-2">1-2</option>
                            <option value="3-5">3-5</option>
                            <option value="6+">6+</option>
                        </select>
                    </div>

                    <div class="section-h"><i class="fas fa-map-marker-alt"></i> Location</div>
                    <div class="row">
                        <select id="divisionSelect" name="division" onchange="updateDistricts()" required>
                            <option value="">Division</option>
                            <option value="Dhaka">Dhaka</option>
                            <option value="Chattogram">Chattogram</option>
                            <option value="Sylhet">Sylhet</option>
                            <option value="Rajshahi">Rajshahi</option>
                            <option value="Khulna">Khulna</option>
                            <option value="Barishal">Barishal</option>
                            <option value="Rangpur">Rangpur</option>
                            <option value="Mymensingh">Mymensingh</option>
                        </select>
                        <select id="districtSelect" name="district" required><option value="">District</option></select>
                    </div>
                    <input type="text" name="area_gps" id="areaInput" placeholder="Village / GPS Location">
                    <button type="button" class="btn-loc" onclick="getLocation()"><i class="fas fa-location-arrow"></i> Live Location</button>

                    <div class="section-h"><i class="fas fa-exclamation-triangle"></i> Emergency Type</div>
                    <div class="grid">
                        <label><input type="checkbox" name="types[]" value="Food"> Food</label>
                        <label><input type="checkbox" name="types[]" value="Water"> Water</label>
                        <label><input type="checkbox" name="types[]" value="Medical"> Medical</label>
                        <label><input type="checkbox" name="types[]" value="Rescue"> Rescue</label>
                    </div>

                    <div class="section-h">Urgency</div>
                    <div class="urgency">
                        <input type="radio" name="urgency" id="u-high" value="High"><label for="u-high"><span class="dot" style="background:red"></span> High</label>
                        <input type="radio" name="urgency" id="u-med" value="Medium" checked><label for="u-med"><span class="dot" style="background:orange"></span> Med</label>
                        <input type="radio" name="urgency" id="u-low" value="Low"><label for="u-low"><span class="dot" style="background:green"></span> Low</label>
                    </div>

                    <textarea name="description" placeholder="Additional details..." style="height:60px; margin-top:10px;"></textarea>

                    <div class="actions">
                        <button type="submit" class="btn-sub">Submit Request</button>
                        <button type="reset" class="btn-can">Cancel</button>
                    </div>
                </form>
            </div>
        </main>
        <footer>
            <div>Contact us <i class="fas fa-phone-alt"></i></div>
            <div class="socials"><i class="fab fa-github"></i> <i class="fab fa-linkedin"></i></div>
            <p>© 2026 ShohayNet</p>
        </footer>
    </div>

    <script>
        const districtData = {
            "Dhaka": ["Dhaka", "Gazipur", "Narayanganj", "Tangail", "Faridpur"],
            "Chattogram": ["Chattogram", "Cox's Bazar", "Cumilla", "Feni"],
            "Sylhet": ["Sylhet", "Moulvibazar", "Habiganj"],
            "Rajshahi": ["Rajshahi", "Bogura", "Sirajganj"],
            "Khulna": ["Khulna", "Jashore", "Satkhira"],
            "Barishal": ["Barishal", "Bhola", "Patuakhali"],
            "Rangpur": ["Rangpur", "Dinajpur", "Kurigram"],
            "Mymensingh": ["Mymensingh", "Jamalpur", "Sherpur"]
        };

        function updateDistricts() {
            const div = document.getElementById("divisionSelect").value;
            const dist = document.getElementById("districtSelect");
            dist.innerHTML = '<option value="">District</option>';
            if(div) districtData[div].forEach(d => dist.innerHTML += `<option value="${d}">${d}</option>`);
        }

        window.addEventListener('offline', () => { window.location.href = 'sos-request.html'; });

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(pos => {
                    document.getElementById('areaInput').value = `${pos.coords.latitude.toFixed(4)}, ${pos.coords.longitude.toFixed(4)}`;
                });
            }
        }

        document.getElementById('mainAidForm').onsubmit = function(e) {
            e.preventDefault();
            fetch('submit_request.php', { method: 'POST', body: new FormData(this) })
            .then(res => res.text()).then(data => {
                if(data.trim() === "success") { alert("Submitted Successfully!"); this.reset(); }
                else { alert("Error: " + data); }
            });
        };
    </script>
</body>
</html>
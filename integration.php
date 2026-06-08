<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard A - Integration with Government services</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --header-green: #2D5A27;
            --main-bg: #90EE90;
            --content-bg: #99D98C;
            --hctt-blue: #000080;
            --card-yellow: #FFD54F;
            --gob-pill: #1B5E20;
            --benefit-pill: #1B5E20;
            --benefit-light: #4CAF50;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            display: flex;
            flex-direction: column;
            background-color: var(--content-bg);
            position: relative;
        }

        .page-title {
            font-size: 0.8rem;
            color: #555;
        }

        header {
            background-color: var(--header-green);
            padding: 15px 30px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            position: relative;
            z-index: 100;
        }

        .header-icons {
            display: flex;
            gap: 20px;
            color: white;
            font-size: 1.5rem;
        }

        .header-icons i {
            cursor: pointer;
            transition: transform 0.15s, opacity 0.15s;
        }
        .header-icons i:hover {
            transform: translateY(-2px);
            opacity: 0.8;
        }

        .menu-wrapper {
            position: relative;
        }

        .menu-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 10px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 6px 24px rgba(0,0,0,0.25);
            min-width: 200px;
            overflow: hidden;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: opacity 0.25s, transform 0.25s, visibility 0.25s;
        }

        .menu-dropdown.open {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .menu-dropdown .menu-item {
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 15px;
            color: #333;
            cursor: pointer;
            transition: background 0.15s, color 0.15s;
        }

        .menu-dropdown .menu-item:hover {
            background: #e8f5e9;
            color: #2D5A27;
        }

        .menu-dropdown .menu-item i {
            width: 20px;
            text-align: center;
            color: #2D5A27;
            font-size: 16px;
        }

        .main-content {
            flex: 1;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #A9D18E;
        }

        .header-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            margin-bottom: 30px;
            padding: 0 50px;
        }

        .gob-seal {
            width: 120px;
        }

        .hctt-box {
            background-color: var(--hctt-blue);
            color: white;
            padding: 20px 40px;
            border-radius: 4px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            flex: 1;
            margin: 0 20px;
        }

        .hctt-box h1 {
            font-size: 1.4rem;
            margin-bottom: 5px;
        }

        .hctt-box p {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .un-logo {
            text-align: center;
            color: #2E7D32;
            font-weight: bold;
        }

        .un-logo img {
            width: 80px;
            display: block;
            margin: 0 auto 5px;
        }

        .approved-clusters-pill {
            background-color: #228B22;
            color: black;
            padding: 10px 40px;
            border-radius: 10px;
            font-weight: bold;
            font-size: 1.3rem;
            margin-bottom: 25px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s;
        }
        .approved-clusters-pill:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        .jsf-circle {
            background-color: #F4A460;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-weight: bold;
            margin-bottom: 30px;
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s;
        }
        .jsf-circle:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }

        .cards-grid {
            display: flex;
            gap: 20px;
            width: 100%;
            justify-content: center;
            margin-bottom: 40px;
        }

        .info-card {
            background-color: #FFD966;
            padding: 20px;
            border-radius: 8px;
            width: 30%;
            text-align: center;
            font-size: 0.9rem;
            line-height: 1.4;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s;
        }
        .info-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 14px rgba(0,0,0,0.18);
        }

        .info-card b {
            display: block;
            margin-bottom: 10px;
            color: #333;
        }

        .benefits-pill {
            background-color: #70AD47;
            color: #1B5E20;
            padding: 10px 30px;
            border-radius: 20px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 40px;
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s;
        }
        .benefits-pill:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        .pills-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            width: 100%;
            align-items: center;
        }

        .pills-row {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .pill {
            padding: 15px 25px;
            border-radius: 30px;
            color: white;
            font-weight: bold;
            font-size: 0.9rem;
            min-width: 180px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s;
        }
        .pill:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 12px rgba(0,0,0,0.22);
        }

        .pill-dark { background-color: #2D5A27; }
        .pill-light { background-color: #00B050; }
        .pill-teal { background-color: #385723; }

        footer {
            background-color: var(--header-green);
            padding: 15px;
            text-align: center;
            color: white;
            font-size: 0.7rem;
        }

        .toast {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%) translateY(-80px);
            background: #2D5A27;
            color: white;
            padding: 12px 28px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: bold;
            box-shadow: 0 4px 16px rgba(0,0,0,0.3);
            transition: transform 0.35s ease;
            z-index: 9999;
            white-space: nowrap;
        }
        .toast.show {
            transform: translateX(-50%) translateY(0);
        }
    </style>
</head>
<body>

<div class="container">
    <header>
        <div class="header-icons">
         <a href="index.php"><i class="fas fa-home" id="homeBtn" title="Home"></i>
            <div class="menu-wrapper"></a>
                <i class="fas fa-bars" id="menuBtn" title="Menu"></i>
                <div class="menu-dropdown" id="menuDropdown">
                    <div class="menu-item" data-link="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</div>
                </div>
            </div>
        </div>
    </header>

    <main class="main-content">
        <div class="header-section">
           <img src="https://upload.wikimedia.org/wikipedia/commons/8/84/Government_Seal_of_Bangladesh.svg" 
     alt="Bangladesh Govt Seal" 
     class="gob-seal">
         
            <div class="hctt-box">
                <h1>Humanitarian Coordination Task Team (HCTT)</h1>
                <p>Co-Lead: MoDMR and UNRCO</p>
            </div>

            <div class="un-logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/ee/UN_emblem_blue.svg/1200px-UN_emblem_blue.svg.png" alt="UN Logo">
                জাতিসংঘ
            </div>
        </div>

        <div class="approved-clusters-pill" id="clustersPill">
            GoB Approved Clusters(BD)
        </div>

        <div class="jsf-circle" id="jsfCircle">jsf</div>

        <div class="cards-grid">
            <div class="info-card" data-info="Food Security Cluster — Lead: MoF/MoA, Co-lead: WFP/FAO">
                <b><i class="fas fa-seedling"></i> Food Security:</b>
                This cluster is managed by Lead: MoF/MoA supported by Co-lead: WFP/FAO.
            </div>
            <div class="info-card" data-info="Health Cluster — Lead: MoHFW, Co-lead: WHO">
                <b><i class="fas fa-hand-holding-medical"></i> Health:</b>
                Ensuring health services is the responsibility of Lead: MoHFW Co-lead: WHO.
            </div>
            <div class="info-card" data-info="Shelter Cluster — Lead: MoHPW, Co-lead: IFRC/UNDP">
                <b><i class="fas fa-house-user"></i> Shelter:</b>
                Housing assistance is led by Lead: MoHPW with the support of Co-lead: IFRC/UNDP.
            </div>
        </div>

        <div class="benefits-pill" id="benefitsPill">
            <i class="fas fa-check-circle"></i> Benefits of Integration with Government Services
        </div>

        <div class="pills-container">
            <div class="pills-row">
                <div class="pill pill-dark" data-info="Elimination of Duplication">Elimination of Duplication</div>
                <div class="pill pill-light" data-info="Faster Response">Faster Response</div>
                <div class="pill pill-teal" data-info="One Source of Truth">One Source of Truth</div>
            </div>
            <div class="pills-row">
                <div class="pill pill-dark" data-info="Legal Compliance">Legal Compliance</div>
                <div class="pill pill-light" data-info="Better Reach">Better Reach</div>
                <div class="pill pill-light" data-info="High Transparency">High Transparency</div>
                <div class="pill pill-teal" data-info="Sustainability">Sustainability</div>
            </div>
        </div>
    </main>

    <footer>
        &copy; 2026 ShohayNet. All rights reserved.
    </footer>
</div>

<div class="toast" id="toast"></div>

<script>
    function showToast(msg) {
        var t = document.getElementById('toast');
        t.textContent = msg;
        t.classList.add('show');
        clearTimeout(t._to);
        t._to = setTimeout(function() { t.classList.remove('show'); }, 2200);
    }

    /* Home icon → home page */
    document.getElementById('homeBtn').addEventListener('click', function() {
        window.location.href = 'home.html';
    });

    /* Menu bar toggle */
    var menuDropdown = document.getElementById('menuDropdown');
    document.getElementById('menuBtn').addEventListener('click', function(e) {
        e.stopPropagation();
        menuDropdown.classList.toggle('open');
    });

    /* Dashboard menu item → dashboard.php */
    document.querySelectorAll('#menuDropdown .menu-item').forEach(function(item) {
        item.addEventListener('click', function() {
            window.location.href = item.dataset.link;
        });
    });

    /* বাইরে click করলে menu বন্ধ */
    document.addEventListener('click', function() {
        menuDropdown.classList.remove('open');
    });

    /* Clusters pill */
    document.getElementById('clustersPill').addEventListener('click', function() {
        showToast('GoB Approved Clusters (BD)');
    });

    /* JSF circle */
    document.getElementById('jsfCircle').addEventListener('click', function() {
        showToast('Joomla Secure Framework');
    });

    /* Info cards */
    document.querySelectorAll('.info-card').forEach(function(card) {
        card.addEventListener('click', function() {
            showToast(card.dataset.info);
        });
    });

    /* Benefits pill */
    document.getElementById('benefitsPill').addEventListener('click', function() {
        showToast('Benefits of Integration with Government Services');
    });

    /* Small pills */
    document.querySelectorAll('.pill').forEach(function(pill) {
        pill.addEventListener('click', function() {
            showToast(pill.dataset.info);
        });
    });
</script>
</body>
</html>
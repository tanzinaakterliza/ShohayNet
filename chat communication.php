<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


// ==================== DATABASE CONNECTION ====================
include __DIR__ . '/db_config.php';


$success = false;
$message = "";

error_reporting(E_ALL);
ini_set('display_errors', 1);
// ==================== CREATE TABLE IF NOT EXISTS ====================
$db->exec("CREATE TABLE IF NOT EXISTS messages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    sender TEXT NOT NULL,
    body TEXT NOT NULL,
    type TEXT NOT NULL DEFAULT 'outgoing',
    urgent INTEGER NOT NULL DEFAULT 0,
    created_at TEXT NOT NULL DEFAULT (datetime('now','localtime'))
)");

// ==================== SEED DEFAULT MESSAGES (একবারই চলবে) ====================
$stmt = $db->query("SELECT COUNT(*) FROM messages");
if ((int)$stmt->fetchColumn() === 0) {
    $seeds = [
        ['Commander Dina', 'All units, we have confirmation that flooding has receded in Zone B. Begin assessment procedures.', 'incoming', 1],
        ['You', 'Copy that. Dispatching assessment team now.', 'outgoing', 0],
        ['Lt. Maria', 'Medical team is en route to the shelter. ETA 10 minutes.', 'incoming', 0],
        ['You', "Great. I'll notify the shelter coordinator.", 'outgoing', 0],
    ];

    $st = $db->prepare("INSERT INTO messages (sender, body, type, urgent) VALUES (?,?,?,?)");
    foreach ($seeds as $s) {
        $st->execute($s);
    }
}

// ==================== HANDLE AJAX SEND MESSAGE ====================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_send'])) {
    header('Content-Type: application/json');

    $body   = trim(strip_tags($_POST['message'] ?? ''));
    $urgent = !empty($_POST['urgent']) ? 1 : 0;
    $sender = trim(strip_tags($_POST['sender'] ?? 'You'));
    if ($sender === '') $sender = 'You';

    if ($body === '') {
        echo json_encode(['success' => false, 'error' => 'Message cannot be empty.']);
        exit;
    }

    $st = $db->prepare("INSERT INTO messages (sender, body, type, urgent) VALUES (?, ?, 'outgoing', ?)");
    $st->execute([$sender, $body, $urgent]);

    $id  = (int)$db->lastInsertId();
    $now = date('g:i A');

    echo json_encode([
        'success' => true,
        'id'      => $id,
        'time'    => $now,
        'body'    => $body,
        'urgent'  => $urgent
    ]);
    exit;
}

// ==================== LOAD ALL MESSAGES ====================
$messages = $db->query("SELECT * FROM messages ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);

// ==================== STATIC CONVERSATIONS ====================
$conversations = [
    ['type'=>'group',  'label'=>'',  'name'=>'Emergency Response Team', 'time'=>'2 min ago',  'preview'=>'New volunteers arriving at Zone A',          'badge'=>3, 'active'=>true,  'priority'=>false, 'online'=>false],
    ['type'=>'user',   'label'=>'D', 'name'=>'Dr. Sarah Khan',          'time'=>'5 min ago',  'preview'=>'Medical supplies are running low',           'badge'=>1, 'active'=>false, 'priority'=>true,  'online'=>true ],
    ['type'=>'channel','label'=>'#', 'name'=>'Incident Command Center', 'time'=>'10 min ago', 'preview'=>'Weather update: Storm intensity decreasing', 'badge'=>0, 'active'=>false, 'priority'=>false, 'online'=>false],
    ['type'=>'group',  'label'=>'',  'name'=>'Volunteer Coordinators',  'time'=>'15 min ago', 'preview'=>'Shift schedule for tomorrow posted',         'badge'=>5, 'active'=>false, 'priority'=>false, 'online'=>false],
    ['type'=>'user',   'label'=>'M', 'name'=>'Rashed Hasan',            'time'=>'1 hour ago', 'preview'=>'Can you send the resource inventory?',       'badge'=>0, 'active'=>false, 'priority'=>false, 'online'=>false],
    ['type'=>'group',  'label'=>'',  'name'=>'Logistics Team',          'time'=>'2 hrs ago',  'preview'=>'Transport vehicles deployed to downtown',    'badge'=>0, 'active'=>false, 'priority'=>false, 'online'=>false],
    ['type'=>'channel','label'=>'#', 'name'=>'Public Announcements',    'time'=>'3 hrs ago',  'preview'=>'Evacuation order lifted for coastal areas',  'badge'=>0, 'active'=>false, 'priority'=>false, 'online'=>false],
];

function e($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>ShohayNet – Messages</title>
<link href="https://fonts.googleapis.com/css2?family=Changa+One:ital@0;1&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<style>
/* ── Reset & Variables ── */

:root{
  --gd:#1a4d2e;--gm:#2e7d32;--gl:#e8f5e9;
  --ub:#fff8e1;--ubr:#f5a623;
  --wh:#fff;--tx:#0d1f0f;--tm:#3d5c40;--mu:#7a9a7e;
  --bd:#d6e8d6;--bin:#f5f5f5;--bout:#1b5e20;
  --fh:'Changa One',sans-serif;--fb:'Inter',sans-serif;
}
body{font-family:var(--fb);background:#f7faf7;color:var(--tx);height:100vh;display:flex;flex-direction:column;overflow:hidden}
::-webkit-scrollbar{width:5px}::-webkit-scrollbar-thumb{background:#c5dcc5;border-radius:4px}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}


/* ── NAVBAR ── */
.nav{display:flex;align-items:center;justify-content:space-between;padding:12px 26px;background:var(--wh);border-bottom:1.5px solid var(--bd);flex-shrink:0}
.logo{display:flex;align-items:center;gap:10px;text-decoration:none}
.logo-icon{width:44px;height:44px;border-radius:50%;background:var(--gm);display:flex;align-items:center;justify-content:center}
.logo-text{font-family:var(--fh);font-size:21px;color:var(--gd)}
.nav-links{display:flex;align-items:center;gap:6px}
.nl{display:flex;align-items:center;gap:5px;padding:7px 11px;border-radius:8px;color:var(--mu);text-decoration:none;font-size:13px;font-weight:500;transition:all .2s}
.nl:hover,.nl.on{background:var(--gl);color:var(--gm)}

/* ── LAYOUT ── */
.layout{display:grid;grid-template-columns:460px 1fr;flex:1;overflow:hidden}

/* ── SIDEBAR ── */
.sidebar{border-right:1.5px solid var(--bd);display:flex;flex-direction:column;background:var(--wh);overflow:hidden}
.sb-head{display:flex;align-items:center;justify-content:space-between;padding:18px 20px 13px;flex-shrink:0}
.sb-title{font-family:var(--fh);font-size:21px;color:var(--gd)}
.btn-nc{display:flex;align-items:center;gap:6px;background:var(--gd);color:#fff;font-family:var(--fb);font-weight:600;font-size:13px;border:none;border-radius:8px;padding:8px 14px;cursor:pointer;transition:background .2s}
.btn-nc:hover{background:var(--gm)}
.sb-search{padding:0 20px 13px;flex-shrink:0}
.s-box{display:flex;align-items:center;gap:8px;background:#f5f8f5;border:1.5px solid var(--bd);border-radius:10px;padding:9px 13px}
.s-box input{border:none;background:transparent;font-family:var(--fb);font-size:13.5px;color:var(--tx);width:100%;outline:none}
.s-box input::placeholder{color:var(--mu)}
.pri-bar{margin:0 20px 10px;background:var(--ub);border:1.5px solid var(--ubr);border-radius:10px;padding:11px 13px;flex-shrink:0}
.pri-title{display:flex;align-items:center;gap:7px;font-weight:700;font-size:13.5px;color:#b45309}
.pri-sub{font-size:12px;color:#92400e;margin-top:3px}
.conv-list{flex:1;overflow-y:auto}
.ci{display:flex;align-items:center;gap:11px;padding:13px 20px;border-bottom:1px solid #f0f5f0;cursor:pointer;transition:background .15s;position:relative}
.ci:hover{background:var(--gl)}
.ci.on{background:var(--gl);border-left:3px solid var(--gm)}
.ci.pri{background:#fff9f0;border-left:3px solid var(--ubr)}
.av{width:42px;height:42px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;position:relative;overflow:hidden}
.av-g{background:#e8f5e9}
.av-c{background:#e3f2fd}
.av-c span{font-weight:700;font-size:19px;color:#1565c0}
.av-u{background:var(--gm);color:#fff;font-family:var(--fh);font-size:17px}
.odot{position:absolute;bottom:2px;right:2px;width:10px;height:10px;background:#4caf50;border-radius:50%;border:2px solid #fff}
.cb{flex:1;min-width:0}
.ct{display:flex;justify-content:space-between;align-items:center}
.cn{font-weight:600;font-size:14px;color:var(--tx);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.ctime{font-size:11.5px;color:var(--mu);flex-shrink:0}
.cb2{display:flex;justify-content:space-between;align-items:center;margin-top:3px}
.cprev{font-size:12.5px;color:var(--mu);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:250px}
.cbadge{background:var(--gm);color:#fff;font-size:10.5px;font-weight:700;border-radius:50%;width:19px;height:19px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.sb-foot{padding:12px 20px;border-top:1.5px solid var(--bd);flex-shrink:0}
.fl{display:flex;align-items:center;gap:7px;color:var(--gm);font-weight:600;font-size:13px;text-decoration:none}
.fl:hover{opacity:.75}

/* ── CHAT PANEL ── */
.chat{display:flex;flex-direction:column;background:var(--wh);overflow:hidden}
.chat-head{display:flex;align-items:center;justify-content:space-between;padding:14px 22px;border-bottom:1.5px solid var(--bd);flex-shrink:0}
.ch-left{display:flex;align-items:center;gap:11px}
.ch-av{width:43px;height:43px;border-radius:50%;background:var(--gl);display:flex;align-items:center;justify-content:center;flex-shrink:0}
.ch-name{font-family:var(--fh);font-size:17px;color:var(--tx)}
.ch-meta{font-size:12.5px;color:var(--mu);margin-top:1px}
.ch-actions{display:flex;align-items:center;gap:4px}
.ib{width:36px;height:36px;border:none;background:transparent;display:flex;align-items:center;justify-content:center;border-radius:8px;cursor:pointer;color:var(--mu);transition:all .2s}
.ib:hover{background:var(--gl);color:var(--gm)}

/* ── MESSAGES ── */
.msg-area{flex:1;overflow-y:auto;padding:20px 24px;display:flex;flex-direction:column;gap:15px;background:#fafcfa}
.date-sep{text-align:center}
.date-sep span{background:#e8e8e8;color:#666;font-size:11.5px;font-weight:500;padding:4px 13px;border-radius:20px}
.mrow{display:flex;flex-direction:column}
.in-r{align-items:flex-start}
.out-r{align-items:flex-end}
.msender{font-size:12.5px;font-weight:600;color:var(--gd);margin-bottom:4px}
.bubble{max-width:500px;padding:12px 15px;border-radius:14px;font-size:14px;line-height:1.55}
.b-in{background:var(--bin);border:1px solid #e8e8e8;border-top-left-radius:4px;color:var(--tx)}
.b-out{background:var(--bout);color:#fff;border-bottom-right-radius:4px}
.b-urg{background:var(--gm);color:#fff}
.b-urg-out{background:#b71c1c!important}
.utag{display:flex;align-items:center;gap:5px;font-weight:700;font-size:12px;margin-bottom:5px;color:#c8e6c9}
.mtime{font-size:11px;color:var(--mu);margin-top:4px}
.ticks{color:var(--gm);margin-left:3px}

/* ── QUICK BUTTONS ── */
.qbtns{display:flex;gap:8px;padding:10px 22px 0;flex-shrink:0}
.qb{display:flex;align-items:center;gap:6px;border:1.5px solid var(--bd);background:var(--wh);color:var(--tx);font-family:var(--fb);font-size:12.5px;font-weight:500;padding:7px 13px;border-radius:8px;cursor:pointer;transition:all .2s}
.qb:hover{background:var(--gl);border-color:var(--gm)}
.qb-urg{border-color:var(--ubr);color:#b45309}
.qb-urg:hover,.qb-urg.on{background:var(--ub)}
.qb-urg.on{border-color:#f57c00;color:#e65100;font-weight:700}

/* ── INPUT ── */
.input-wrap{padding:10px 22px 13px;border-top:1.5px solid var(--bd);flex-shrink:0}
.input-box{display:flex;align-items:flex-end;gap:9px;background:#f5f8f5;border:1.5px solid var(--bd);border-radius:12px;padding:10px 12px;transition:border .2s}
.input-box.urg{border-color:var(--ubr);background:#fffbf0}
.input-box textarea{flex:1;border:none;background:transparent;font-family:var(--fb);font-size:14px;color:var(--tx);resize:none;outline:none;min-height:26px;max-height:120px;line-height:1.5}
.input-box textarea::placeholder{color:var(--mu)}
.iicons{display:flex;align-items:center;gap:8px}
.ico{background:none;border:none;cursor:pointer;color:var(--mu);display:flex;align-items:center;transition:color .2s}
.ico:hover{color:var(--gm)}
.send-btn{background:var(--gm);border:none;border-radius:9px;width:42px;height:42px;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background .2s;flex-shrink:0}
.send-btn:hover{background:var(--gd)}
.send-btn.urg{background:#d32f2f}
.send-btn.urg:hover{background:#b71c1c}
.enc{text-align:center;font-size:11px;color:var(--mu);margin-top:6px;display:flex;align-items:center;justify-content:center;gap:5px}

/* ── SENDER LABEL INPUT ── */
.sender-bar{padding:6px 22px 0;flex-shrink:0}
.sender-bar label{font-size:12px;font-weight:600;color:var(--mu);margin-right:7px}
.sender-bar input{border:1.5px solid var(--bd);border-radius:7px;padding:4px 10px;font-family:var(--fb);font-size:13px;color:var(--tx);outline:none;width:180px;transition:border .2s}
.sender-bar input:focus{border-color:var(--gm)}

/* ── TOAST ── */
#toast{position:fixed;top:22px;right:22px;padding:12px 18px;border-radius:10px;font-family:var(--fb);font-size:13.5px;font-weight:600;color:#fff;z-index:9999;box-shadow:0 4px 20px rgba(0,0,0,.18);transition:opacity .3s;max-width:320px;display:none}

.logo img {
  width: 65px;   
  height: 65px;
  object-fit: cover;
}

</style>
</head>
<body>

<!-- ══ NAVBAR ══ -->
<nav class="nav">
  <div class="logo">
    <img src="logo.png.jpeg" alt="ShohayNet Logo">
  </div>
 <div class="top-bar">
   <span class="nav-btn" onclick="location.href='index.php'">Home</span>
   <span class="nav-btn" onclick="toggleFeatures()">☰</span>
</div> 
</nav>

<!-- ══ MAIN LAYOUT ══ -->
<div class="layout">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sb-head">
      <span class="sb-title">Messages</span>
      <button class="btn-nc" onclick="showToast('New chat coming soon!','info')">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
        New Chat
      </button>
    </div>

    <div class="sb-search">
      <div class="s-box">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#9ab09a" stroke-width="2.2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" placeholder="Search conversations…" oninput="filterConvs(this.value)"/>
      </div>
    </div>

    <div class="pri-bar">
      <div class="pri-title">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/></svg>
        Priority Messages
      </div>
      <p class="pri-sub">2 urgent conversations need attention</p>
    </div>

    <div class="conv-list" id="convList">
      <?php foreach ($conversations as $c):
        $cls = 'ci' . ($c['active'] ? ' on' : '') . ($c['priority'] ? ' pri' : '');
      ?>
      <div class="<?= $cls ?>" onclick="pickConv(this)" data-n="<?= e(strtolower($c['name'])) ?>">

        <?php if ($c['type'] === 'group'): ?>
          <div class="av av-g">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2e7d32" stroke-width="1.9"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
          </div>
        <?php elseif ($c['type'] === 'channel'): ?>
          <div class="av av-c"><span>#</span></div>
        <?php else: ?>
          <div class="av av-u">
            <?= e($c['label']) ?>
            <?php if ($c['online']): ?><span class="odot"></span><?php endif; ?>
          </div>
        <?php endif; ?>

        <div class="cb">
          <div class="ct">
            <span class="cn"><?= e($c['name']) ?></span>
            <span class="ctime"><?= e($c['time']) ?></span>
          </div>
          <div class="cb2">
            <span class="cprev"><?= e($c['preview']) ?></span>
            <?php if ($c['badge']): ?><span class="cbadge"><?= (int)$c['badge'] ?></span><?php endif; ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <div class="sb-foot">
      <a href="emergency.php" class="fl">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
        Emergency Reporting Tool
      </a>
    </div>
  </aside>

  <!-- CHAT PANEL -->
  <main class="chat">

    <div class="chat-head">
      <div class="ch-left">
        <div class="ch-av">
          <svg width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="#2e7d32" stroke-width="1.9"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
        </div>
        <div>
          <p class="ch-name" id="chatName">Emergency Response Team</p>
          <p class="ch-meta">12 members &bull; 8 online</p>
        </div>
      </div>
      <div class="ch-actions">
        <button class="ib" onclick="showToast('Calling…','info')" title="Call">
          <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 19.79 19.79 0 01.22 1.18 2 2 0 012.2 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 7.91a16 16 0 006.72 6.72l1.06-1.06a2 2 0 012.11-.45c.907.34 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
        </button>
        <button class="ib" onclick="showToast('Video call starting…','info')" title="Video">
          <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>
        </button>
        <button class="ib" title="Notifications">
          <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
        </button>
        <button class="ib">
          <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/></svg>
        </button>
      </div>
    </div>

    <!-- Messages Area -->
    <div class="msg-area" id="msgArea">
      <div class="date-sep"><span>Today</span></div>

      <?php foreach ($messages as $m): ?>

        <?php if ($m['type'] === 'incoming'): ?>
        <div class="mrow in-r">
          <p class="msender"><?= e($m['sender']) ?></p>
          <div class="bubble <?= $m['urgent'] ? 'b-urg' : 'b-in' ?>">
            <?php if ($m['urgent']): ?>
            <div class="utag">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/></svg>
              URGENT
            </div>
            <?php endif; ?>
            <?= e($m['body']) ?>
          </div>
          <p class="mtime"><?= date('g:i A', strtotime($m['created_at'])) ?></p>
        </div>

        <?php else: ?>
        <div class="mrow out-r">
          <div class="bubble b-out<?= $m['urgent'] ? ' b-urg-out' : '' ?>"><?= e($m['body']) ?></div>
          <p class="mtime"><?= date('g:i A', strtotime($m['created_at'])) ?> <span class="ticks">✓✓</span></p>
        </div>
        <?php endif; ?>

      <?php endforeach; ?>
    </div>

    <!-- Sender Name Bar -->
    <div class="sender-bar">
      <label>Your name:</label>
      <input type="text" id="senderName" value="You" placeholder="Your name…"/>
    </div>

    <!-- Quick Buttons -->
    <div class="qbtns">
      <button class="qb qb-urg" id="urgBtn" onclick="toggleUrgent(this)">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/></svg>
        Mark Urgent
      </button>
      <button class="qb" onclick="sendLocation()">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
        Send Location
      </button>
      <button class="qb" onclick="requestTeam()">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/></svg>
        Request Team
      </button>
    </div>

    <!-- Input Box -->
    <div class="input-wrap">
      <div class="input-box" id="inputBox">
        <textarea id="msgTxt" rows="1" placeholder="Type a message…"></textarea>
        <div class="iicons">
          <button type="button" class="ico" title="Attach">
            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48"/></svg>
          </button>
          <button type="button" class="ico" title="Emoji">
            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
          </button>
        </div>
        <button class="send-btn" id="sendBtn" onclick="doSend()">
          <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.3"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
        </button>
      </div>
      <p class="enc">
        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
        End-to-end encrypted communication
      </p>
    </div>

  </main>
</div>

<!-- Toast -->
<div id="toast"></div>

<script>
'use strict';
let isUrgent = false;

document.addEventListener('DOMContentLoaded', () => {
  const ta = document.getElementById('msgTxt');
  ta.addEventListener('input', function () {
    this.style.height = 'auto';
    this.style.height = this.scrollHeight + 'px';
  });
  ta.addEventListener('keydown', e => {
    if (e.key === 'Enter' && !e.shiftKey) { 
      e.preventDefault(); 
      doSend(); 
    }
  });
  scrollBot();
});



// ── Send Message ──
function doSend() {
  const ta     = document.getElementById('msgTxt');
  const text   = ta.value.trim();
  const sender = document.getElementById('senderName').value.trim() || 'You';
  if (!text) { ta.focus(); return; }

  const btn = document.getElementById('sendBtn');
  btn.disabled = true;

  const fd = new FormData();
  fd.append('ajax_send', '1');
  fd.append('message',   text);
  fd.append('urgent',    isUrgent ? '1' : '0');
  fd.append('sender',    sender);

  fetch(window.location.href, { method:'POST', body:fd })
    .then(r => r.json())
    .then(d => {
      if (d.success) {
        appendOut(d.body, d.time, d.urgent == 1);
        ta.value = ''; ta.style.height = 'auto';
        scrollBot();
        if (isUrgent) toggleUrgent(document.getElementById('urgBtn'));
        setTimeout(simulateReply, 1800);
      } else {
        showToast(d.error || 'Failed to send.', 'error');
      }
    })
    .catch(() => {
      // Fallback demo
      const t = new Date().toLocaleTimeString('en-US',{hour:'numeric',minute:'2-digit'});
      appendOut(text, t, isUrgent);
      ta.value = ''; ta.style.height = 'auto';
      scrollBot();
      if (isUrgent) toggleUrgent(document.getElementById('urgBtn'));
      setTimeout(simulateReply, 1800);
    })
    .finally(() => { btn.disabled = false; });
}

// ── Append outgoing bubble ──
function appendOut(text, time, urgent) {
  const area = document.getElementById('msgArea');
  const d = document.createElement('div');
  d.className = 'mrow out-r';
  d.innerHTML = `
    <div class="bubble b-out${urgent?' b-urg-out':''}">${esc(text)}</div>
    <p class="mtime">${time} <span class="ticks">✓✓</span></p>
  `;
  area.appendChild(d);
}

// ── Simulate incoming reply ──
function simulateReply() {
  const reps = [
    'Understood. Will update the team.',
    'Copy that. On it now.',
    'Received. Dispatching support.',
    'Acknowledged. Standby.',
    'Roger that. Coordinating response.',
    'Message received. Thank you.',
  ];
  const text = reps[Math.floor(Math.random()*reps.length)];
  const t    = new Date().toLocaleTimeString('en-US',{hour:'numeric',minute:'2-digit'});
  const area = document.getElementById('msgArea');
  const d    = document.createElement('div');
  d.className = 'mrow in-r';
  d.innerHTML = `
    <p class="msender">Commander Dina</p>
    <div class="bubble b-in">${esc(text)}</div>
    <p class="mtime">${t}</p>
  `;
  area.appendChild(d);
  scrollBot();
}

// ── Toggle Urgent ──
function toggleUrgent(btn) {
  isUrgent = !isUrgent;
  btn.classList.toggle('on', isUrgent);
  document.getElementById('inputBox').classList.toggle('urg', isUrgent);
  document.getElementById('sendBtn').classList.toggle('urg', isUrgent);
  document.getElementById('msgTxt').placeholder = isUrgent ? '🚨 Type URGENT message…' : 'Type a message…';
  showToast(isUrgent ? '🚨 Urgent mode ON' : 'Urgent mode OFF', isUrgent ? 'warn' : 'info');
}

// ── Send Location ──
function sendLocation() {
  if (!navigator.geolocation) { showToast('GPS not supported.','error'); return; }
  showToast('Getting location…','info');
  navigator.geolocation.getCurrentPosition(
    p => {
      document.getElementById('msgTxt').value =
        '📍 My location: Lat ' + p.coords.latitude.toFixed(5) + ', Lng ' + p.coords.longitude.toFixed(5);
      document.getElementById('msgTxt').focus();
      showToast('Location ready! Press Send.','success');
    },
    () => showToast('Could not get location.','error')
  );
}

// ── Request Team ──
function requestTeam() {
  const ta = document.getElementById('msgTxt');
  ta.value = '🚨 Requesting additional team support at current location. Please respond ASAP.';
  ta.focus(); ta.style.height='auto'; ta.style.height=ta.scrollHeight+'px';
}

// ── Filter conversations ──
function filterConvs(q) {
  q = q.toLowerCase().trim();
  document.querySelectorAll('.ci').forEach(el => {
    el.style.display = (!q || el.dataset.n.includes(q)) ? '' : 'none';
  });
}

// ── Pick conversation ──
function pickConv(el) {
  document.querySelectorAll('.ci').forEach(i => i.classList.remove('on'));
  el.classList.add('on');
  const b = el.querySelector('.cbadge');
  if (b) b.remove();
  const n = el.querySelector('.cn');
  if (n) document.getElementById('chatName').textContent = n.textContent;
}

// ── Helpers ──
function scrollBot() {
  const a = document.getElementById('msgArea');
  if (a) a.scrollTop = a.scrollHeight;
}
function esc(s) {
  return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function showToast(msg, type) {
  const el = document.getElementById('toast');
  const c  = {success:'#2e7d32',error:'#c62828',info:'#1565c0',warn:'#b45309'};
  el.style.background = c[type]||'#2e7d32';
  el.textContent = msg;
  el.style.display='block'; el.style.opacity='1';
  clearTimeout(el._t);
  el._t = setTimeout(()=>{el.style.opacity='0';setTimeout(()=>{el.style.display='none'},300);},3000);
}
function toggleFeatures(){
  showToast('Feature menu coming soon','info');
}
</script>
</body>
</html>

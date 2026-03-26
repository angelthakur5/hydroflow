<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<title>HydroFlow — Water Tracker</title>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet"/>
<style>
:root{
  --p:#7c3aed; --b:#2563eb;
  --acc:#a78bfa; --muted:#a5b4fc;
  --card:rgba(28,20,58,0.85);
  --border:rgba(167,139,250,0.18);
  --text:#f0f4ff;
}
*{box-sizing:border-box;margin:0;padding:0}
body{
  font-family:'DM Sans',sans-serif;color:var(--text);min-height:100vh;
  background:linear-gradient(135deg,#0f0c29 0%,#1a1040 35%,#1e3a8a 75%,#0f172a 100%);
  background-attachment:fixed;overflow-x:hidden;
}
body::before{
  content:'';position:fixed;inset:0;pointer-events:none;
  background:radial-gradient(ellipse 55% 45% at 15% 15%,rgba(124,58,237,.22) 0%,transparent 65%),
             radial-gradient(ellipse 45% 55% at 85% 85%,rgba(37,99,235,.18) 0%,transparent 65%);
}
.bubbles{position:fixed;inset:0;pointer-events:none;overflow:hidden;z-index:0}
.bub{position:absolute;border-radius:50%;border:1px solid rgba(167,139,250,.1);
  background:radial-gradient(circle at 30% 30%,rgba(167,139,250,.12),transparent);
  animation:rise linear infinite}
@keyframes rise{from{transform:translateY(105vh);opacity:0}10%{opacity:.7}90%{opacity:.2}to{transform:translateY(-8vh);opacity:0}}

.page{position:relative;z-index:1;max-width:720px;margin:0 auto;padding:18px 16px 80px}

header{text-align:center;padding:32px 0 14px}
.logo{font-family:'Sora',sans-serif;font-size:2.2rem;font-weight:800;letter-spacing:-1px;
  background:linear-gradient(90deg,#a78bfa,#60a5fa);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.logo small{display:block;font-size:.8rem;font-weight:400;letter-spacing:3px;text-transform:uppercase;
  -webkit-text-fill-color:var(--muted);margin-top:3px}
.tip{margin:14px auto 0;max-width:520px;background:rgba(255,255,255,.06);border:1px solid var(--border);
  border-radius:40px;padding:9px 20px;font-size:.84rem;color:var(--acc);text-align:center}

#gate{display:flex;align-items:center;justify-content:center;min-height:56vh}
.gate-box{background:var(--card);border:1px solid var(--border);border-radius:22px;
  padding:38px 34px;text-align:center;max-width:380px;width:100%;
  box-shadow:0 24px 60px rgba(0,0,0,.45);backdrop-filter:blur(18px)}
.gate-box h2{font-family:'Sora',sans-serif;font-size:1.55rem;font-weight:700;margin-bottom:8px}
.gate-box p{color:var(--muted);font-size:.9rem;margin-bottom:22px;line-height:1.55}
input.ipt{width:100%;padding:13px 16px;background:rgba(255,255,255,.06);border:1.5px solid var(--border);
  border-radius:11px;color:#fff;font-size:.95rem;font-family:'DM Sans',sans-serif;outline:none;transition:border-color .2s}
input.ipt:focus{border-color:var(--acc)}
input.ipt::placeholder{color:rgba(165,180,252,.4)}
.btn-go{width:100%;margin-top:13px;padding:13px;background:linear-gradient(135deg,var(--p),var(--b));
  border:none;border-radius:11px;color:#fff;font-family:'Sora',sans-serif;font-size:.98rem;font-weight:700;
  cursor:pointer;box-shadow:0 7px 24px rgba(124,58,237,.36);transition:transform .15s,box-shadow .15s}
.btn-go:hover{transform:translateY(-2px);box-shadow:0 11px 34px rgba(124,58,237,.5)}

#app{display:none}
.hi{font-family:'Sora',sans-serif;font-size:1.38rem;font-weight:700;text-align:center;margin:4px 0 5px}
.hi span{background:linear-gradient(90deg,#a78bfa,#60a5fa);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.switch{text-align:center;margin-bottom:18px}
.switch button{background:none;border:none;color:var(--muted);font-size:.78rem;cursor:pointer;text-decoration:underline}
.switch button:hover{color:var(--acc)}

.tabs{display:flex;gap:5px;background:rgba(255,255,255,.04);border-radius:12px;padding:4px;margin-bottom:20px}
.tab{flex:1;padding:9px;border:none;background:transparent;color:var(--muted);border-radius:9px;
  cursor:pointer;font-family:'DM Sans',sans-serif;font-size:.9rem;transition:all .15s}
.tab.on{background:linear-gradient(135deg,var(--p),var(--b));color:#fff;font-weight:600}

.ring-area{display:flex;flex-direction:column;align-items:center;gap:12px;margin:6px 0 26px}
.ring-wrap{position:relative;width:190px;height:190px}
.ring-wrap svg{display:block;transform:rotate(-90deg)}
.rb{fill:none;stroke:rgba(167,139,250,.1);stroke-width:16}
.rf{fill:none;stroke:url(#rg);stroke-width:16;stroke-linecap:round;
  transition:stroke-dashoffset .5s cubic-bezier(.4,0,.2,1)}
.ring-center{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center}
.rnum{font-family:'Sora',sans-serif;font-size:2.4rem;font-weight:800;line-height:1}
.runit{font-size:.75rem;color:var(--muted);margin-top:2px}
.rgoal{font-size:.7rem;color:var(--muted);margin-top:1px}
.rmsg{font-size:.82rem;color:var(--acc);text-align:center;max-width:300px;line-height:1.4}

.card{background:var(--card);border:1px solid var(--border);border-radius:18px;
  padding:18px 16px;margin-bottom:14px;backdrop-filter:blur(12px)}
.ctitle{font-family:'Sora',sans-serif;font-size:.78rem;font-weight:700;text-transform:uppercase;
  letter-spacing:1.5px;color:var(--muted);margin-bottom:13px}

.cbtns{display:grid;grid-template-columns:repeat(4,1fr);gap:8px;margin-bottom:11px}
.cb{background:rgba(255,255,255,.05);border:1.5px solid var(--border);border-radius:11px;
  padding:11px 5px;color:var(--text);font-size:.8rem;cursor:pointer;
  display:flex;flex-direction:column;align-items:center;gap:4px;transition:all .15s}
.cb.on{background:linear-gradient(135deg,rgba(124,58,237,.3),rgba(37,99,235,.25));border-color:var(--acc)}
.cb:hover{border-color:var(--acc)}
.ic{font-size:1.25rem}
.crow{display:flex;gap:8px;align-items:center}
.crow .ipt{flex:1}
.blog{padding:12px 20px;background:linear-gradient(135deg,var(--p),var(--b));
  border:none;border-radius:11px;color:#fff;font-family:'Sora',sans-serif;
  font-size:.9rem;font-weight:700;cursor:pointer;white-space:nowrap;
  box-shadow:0 4px 15px rgba(124,58,237,.3);transition:transform .15s}
.blog:hover{transform:translateY(-1px)}
.blog:disabled{opacity:.5;cursor:default;transform:none}
.nipt{width:100%;margin-top:9px;padding:11px 14px;background:rgba(255,255,255,.04);
  border:1.5px solid var(--border);border-radius:10px;color:#fff;
  font-size:.85rem;font-family:'DM Sans',sans-serif;outline:none}
.nipt:focus{border-color:var(--acc)}
.nipt::placeholder{color:rgba(165,180,252,.35)}
.errbox{display:none;margin-top:9px;color:#f87171;font-size:.82rem;background:rgba(248,113,113,.08);
  border:1px solid rgba(248,113,113,.2);border-radius:8px;padding:8px 12px}

.bchart{display:flex;gap:6px;align-items:flex-end;justify-content:space-around;
  height:110px;padding:8px 0 0}
.bcol{display:flex;flex-direction:column;align-items:center;gap:3px;flex:1}
.bbar{width:100%;background:linear-gradient(180deg,var(--acc),var(--b));border-radius:4px 4px 2px 2px;
  min-height:3px;transition:height .4s}
.bval{font-size:.68rem;color:var(--muted);min-height:14px}
.bday{font-size:.7rem;color:var(--muted)}

.ent{display:flex;align-items:center;gap:10px;padding:10px 0;
  border-bottom:1px solid rgba(167,139,250,.08)}
.ent:last-child{border-bottom:none}
.eico{font-size:1.4rem}
.einf{flex:1}
.ecups{font-size:.9rem;font-weight:600}
.emeta{font-size:.74rem;color:var(--muted);margin-top:2px}
.edel{background:rgba(248,113,113,.1);border:1px solid rgba(248,113,113,.2);
  color:#f87171;border-radius:7px;padding:4px 9px;cursor:pointer;font-size:.78rem}
.edel:hover{background:rgba(248,113,113,.2)}

.toast{position:fixed;bottom:28px;left:50%;transform:translateX(-50%) translateY(20px);
  background:rgba(28,20,58,.95);border:1px solid var(--border);border-radius:40px;
  padding:10px 22px;font-size:.88rem;color:var(--acc);opacity:0;
  transition:opacity .25s,transform .25s;pointer-events:none;z-index:999;
  box-shadow:0 8px 30px rgba(0,0,0,.4)}
.toast.show{opacity:1;transform:translateX(-50%) translateY(0)}

footer{text-align:center;color:var(--muted);font-size:.75rem;padding:18px 0 0}
</style>
</head>
<body>
<div class="bubbles" id="bubbles"></div>
<div class="page">

  <header>
    <div class="logo">HydroFlow<small>Water Tracker</small></div>
    <div class="tip" id="tipBar">💧 Stay hydrated — aim for 8 cups a day!</div>
  </header>

  <!-- Name gate -->
  <div id="gate">
    <div class="gate-box">
      <h2>👋 Welcome!</h2>
      <p>Enter a nickname to start. No account or password needed.</p>
      <input class="ipt" id="nickIn" type="text" placeholder="Your nickname…" maxlength="60"
             onkeydown="if(event.key==='Enter')doStart()"/>
      <button class="btn-go" onclick="doStart()">Let's Go 💧</button>
      <div class="errbox" id="gateErr"></div>
    </div>
  </div>

  <!-- Main app -->
  <div id="app">
    <div class="hi">Hey, <span id="hiName"></span>! 👋</div>
    <div class="switch"><button onclick="doSwitch()">Not you? Switch nickname</button></div>

    <div class="tabs">
      <button class="tab on" id="t-track"   onclick="showTab('track')">💧 Track</button>
      <button class="tab"    id="t-history" onclick="showTab('history')">📈 History</button>
    </div>

    <!-- Track pane -->
    <div id="pane-track">
      <div class="ring-area">
        <div class="ring-wrap">
          <svg viewBox="0 0 190 190" xmlns="http://www.w3.org/2000/svg">
            <defs>
              <linearGradient id="rg" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#a78bfa"/>
                <stop offset="100%" stop-color="#60a5fa"/>
              </linearGradient>
            </defs>
            <circle class="rb" cx="95" cy="95" r="85"/>
            <circle class="rf" id="ring" cx="95" cy="95" r="85"
                    stroke-dasharray="534" stroke-dashoffset="534"/>
          </svg>
          <div class="ring-center">
            <div class="rnum" id="rNum">0</div>
            <div class="runit">cups today</div>
            <div class="rgoal">goal: <span id="rGoal">8</span></div>
          </div>
        </div>
        <div class="rmsg" id="rMsg">Log your first cup below! 👇</div>
      </div>

      <div class="card">
        <div class="ctitle">⚡ Quick Add</div>
        <div class="cbtns">
          <button class="cb" id="q05" onclick="pickQ(0.5,'q05')"><span class="ic">🥤</span>½ cup</button>
          <button class="cb on" id="q1"  onclick="pickQ(1,'q1')"><span class="ic">💧</span>1 cup</button>
          <button class="cb" id="q15" onclick="pickQ(1.5,'q15')"><span class="ic">🫗</span>1½ cups</button>
          <button class="cb" id="q2"  onclick="pickQ(2,'q2')"><span class="ic">🍶</span>2 cups</button>
        </div>
        <div class="crow">
          <input class="ipt" id="custIn" type="number" placeholder="Custom…" min="0.1" max="50" step="0.5"
                 onkeydown="if(event.key==='Enter')doLog()"/>
          <button class="blog" id="logBtn" onclick="doLog()">Log It 💧</button>
        </div>
        <input class="nipt" id="noteIn" type="text" placeholder="Note (optional, e.g. after gym)…" maxlength="120"/>
        <div class="errbox" id="logErr"></div>
      </div>
    </div>

    <!-- History pane -->
    <div id="pane-history" style="display:none">
      <div class="card">
        <div class="ctitle">📈 Last 7 Days</div>
        <div class="bchart" id="chart"></div>
      </div>
      <div class="card">
        <div class="ctitle">🗂 Recent Entries</div>
        <div id="entries"><p style="color:var(--muted);font-size:.82rem;text-align:center">No entries yet.</p></div>
      </div>
    </div>
  </div>

  <footer>HydroFlow &copy; 2025</footer>
</div>

<div class="toast" id="toast"></div>

<script>
// ============================================================
//  Zero-DB version — all data lives in localStorage
//  hfnick  — saved nickname
//  hflogs  — JSON array of {id, day, cups, note, ts}
// ============================================================

var GOAL     = 8;
var SELECTED = 1;
var NICK     = localStorage.getItem('hfnick') || '';

function getLogs() {
  try { return JSON.parse(localStorage.getItem('hflogs') || '[]'); }
  catch(e) { return []; }
}
function saveLogs(logs) {
  localStorage.setItem('hflogs', JSON.stringify(logs));
}
function todayKey() {
  var d = new Date();
  return d.getFullYear() + '-'
    + String(d.getMonth()+1).padStart(2,'0') + '-'
    + String(d.getDate()).padStart(2,'0');
}
function todayTotal() {
  var key = todayKey();
  return getLogs()
    .filter(function(l){ return l.day === key; })
    .reduce(function(s,l){ return s + l.cups; }, 0);
}

// ── UI helpers ────────────────────────────────────────────
function toast(msg) {
  var t = document.getElementById('toast');
  t.textContent = msg; t.classList.add('show');
  setTimeout(function(){ t.classList.remove('show'); }, 2700);
}
function showErr(id, msg) {
  var el = document.getElementById(id);
  el.textContent = msg;
  el.style.display = msg ? 'block' : 'none';
}
function fmtDate(ts) {
  try {
    var d = new Date(ts);
    return d.toLocaleDateString('en-IN',{day:'numeric',month:'short'})
         + ' ' + d.toLocaleTimeString('en-IN',{hour:'2-digit',minute:'2-digit'});
  } catch(e) { return ''; }
}

// ── Gate ──────────────────────────────────────────────────
function doStart() {
  var nick = document.getElementById('nickIn').value.trim();
  if (!nick) { showErr('gateErr','Please enter a nickname!'); return; }
  showErr('gateErr','');
  NICK = nick;
  localStorage.setItem('hfnick', NICK);
  showApp();
}
function doSwitch() {
  localStorage.removeItem('hfnick');
  NICK = '';
  location.reload();
}
function showApp() {
  document.getElementById('gate').style.display = 'none';
  document.getElementById('app').style.display  = 'block';
  document.getElementById('hiName').textContent  = NICK;
  updateRing(todayTotal(), GOAL);
}

// ── Ring ──────────────────────────────────────────────────
function updateRing(val, goal) {
  GOAL = goal || 8;
  var v    = parseFloat(val) || 0;
  var circ = 2 * Math.PI * 85;
  var pct  = Math.min(v / GOAL, 1);
  document.getElementById('ring').style.strokeDasharray  = circ;
  document.getElementById('ring').style.strokeDashoffset = circ * (1 - pct);
  document.getElementById('rNum').textContent  = v % 1 === 0 ? v : v.toFixed(1);
  document.getElementById('rGoal').textContent = GOAL;
  var msgs = [
    'Log your first cup below! 👇',
    'Great start! Keep going 💪',
    "Halfway there! You're crushing it 🌊",
    'Almost done! One more push 🚀',
    '🎉 Goal reached! Amazing!'
  ];
  var idx = pct >= 1 ? 4 : Math.floor(pct * 4);
  document.getElementById('rMsg').innerHTML =
    '<b>' + Math.round(pct*100) + '%</b> of daily goal — ' + msgs[idx];
}

// ── Quick-add ─────────────────────────────────────────────
function pickQ(val, id) {
  SELECTED = val;
  document.getElementById('custIn').value = '';
  ['q05','q1','q15','q2'].forEach(function(x){
    document.getElementById(x).classList.remove('on');
  });
  document.getElementById(id).classList.add('on');
}

// ── Log ───────────────────────────────────────────────────
function doLog() {
  var cust = parseFloat(document.getElementById('custIn').value);
  var cups = cust > 0 ? cust : SELECTED;
  var note = document.getElementById('noteIn').value.trim();
  showErr('logErr','');
  if (!cups || cups <= 0) { showErr('logErr','Please select or enter an amount.'); return; }
  if (cups > 50)           { showErr('logErr','Too many cups. Max is 50.'); return; }

  var logs = getLogs();
  logs.push({ id: Date.now(), day: todayKey(), cups: Math.round(cups*100)/100, note: note, ts: Date.now() });
  saveLogs(logs);

  updateRing(todayTotal(), GOAL);
  document.getElementById('custIn').value = '';
  document.getElementById('noteIn').value = '';
  pickQ(1,'q1');
  toast('+' + cups + ' cup' + (cups !== 1 ? 's' : '') + ' logged! 💧');
}

// ── History ───────────────────────────────────────────────
function loadHistory() {
  var logs   = getLogs();
  var byDay  = {};
  logs.forEach(function(l){ byDay[l.day] = (byDay[l.day]||0) + l.cups; });

  var chart    = document.getElementById('chart');
  chart.innerHTML = '';
  var dayNames = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
  var vals     = [];

  for (var i = 6; i >= 0; i--) {
    var dt  = new Date(); dt.setDate(dt.getDate()-i);
    var key = dt.getFullYear()+'-'+String(dt.getMonth()+1).padStart(2,'0')+'-'+String(dt.getDate()).padStart(2,'0');
    vals.push(byDay[key]||0);
  }
  var maxV = Math.max.apply(null, vals.concat([GOAL, 0.1]));

  for (var j = 0; j < 7; j++) {
    var dt2  = new Date(); dt2.setDate(dt2.getDate()-(6-j));
    var key2 = dt2.getFullYear()+'-'+String(dt2.getMonth()+1).padStart(2,'0')+'-'+String(dt2.getDate()).padStart(2,'0');
    var v    = byDay[key2]||0;
    var hpx  = Math.max(Math.round((v/maxV)*88), v>0?5:2);
    var col  = document.createElement('div'); col.className='bcol';
    col.innerHTML = '<div class="bval">'+(v>0?(v%1===0?v:v.toFixed(1)):'')+'</div>'
      + '<div class="bbar" style="height:'+hpx+'px"></div>'
      + '<div class="bday">'+dayNames[dt2.getDay()]+'</div>';
    chart.appendChild(col);
  }

  var recent = logs.slice().sort(function(a,b){ return b.ts-a.ts; }).slice(0,30);
  var el = document.getElementById('entries');
  if (!recent.length) {
    el.innerHTML = '<p style="color:var(--muted);font-size:.82rem;text-align:center">No entries yet.</p>';
    return;
  }
  el.innerHTML = recent.map(function(e){
    return '<div class="ent" id="e'+e.id+'">'
      + '<div class="eico">💧</div>'
      + '<div class="einf">'
        + '<div class="ecups">'+e.cups+' cup'+(e.cups!==1?'s':'')+'</div>'
        + '<div class="emeta">'+(e.note?e.note+' · ':'')+fmtDate(e.ts)+'</div>'
      + '</div>'
      + '<button class="edel" onclick="doDelete('+e.id+')">✕</button>'
      + '</div>';
  }).join('');
}

function doDelete(id) {
  saveLogs(getLogs().filter(function(l){ return l.id !== id; }));
  var el = document.getElementById('e'+id);
  if (el) el.remove();
  updateRing(todayTotal(), GOAL);
  toast('Entry removed');
}

// ── Tabs ──────────────────────────────────────────────────
function showTab(t) {
  document.getElementById('pane-track').style.display   = t==='track'   ? 'block':'none';
  document.getElementById('pane-history').style.display = t==='history' ? 'block':'none';
  document.getElementById('t-track').className   = 'tab'+(t==='track'   ?' on':'');
  document.getElementById('t-history').className = 'tab'+(t==='history' ?' on':'');
  if (t==='history') loadHistory();
}

// ── Bubbles ───────────────────────────────────────────────
(function(){
  var c = document.getElementById('bubbles');
  for (var i = 0; i < 15; i++) {
    var b = document.createElement('div'); b.className='bub';
    var s = 20+Math.random()*65;
    b.style.cssText = 'width:'+s+'px;height:'+s+'px;left:'+Math.random()*100+'%;'
      +'animation-duration:'+(13+Math.random()*15)+'s;animation-delay:'+Math.random()*12+'s';
    c.appendChild(b);
  }
})();

// ── Init ──────────────────────────────────────────────────
(function(){
  if (NICK) showApp();
})();
</script>
</body>
</html>

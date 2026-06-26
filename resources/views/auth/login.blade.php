<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>E-Raport SMKN 1 Sungailiat</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Merriweather:wght@700&display=swap" rel="stylesheet"/>
  <style>
    :root {
      --navy: #1a2c5b;
      --navy-deep: #0f1d3d;
      --gold: #e8a020;
      --gold-light: #f5c55a;
      --cream: #fdf8f0;
      --gray-soft: #f0f2f7;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      min-height: 100vh;
      background: var(--gray-soft);
      display: flex;
    }

    /* ── LEFT PANEL: hanya tampil di layar >= 768px ── */
    .left-panel {
      display: none;
      width: 42%;
      min-height: 100vh;
      background: linear-gradient(150deg, var(--navy-deep) 0%, var(--navy) 60%, #2a4080 100%);
      position: relative;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 3rem 2.5rem;
      overflow: hidden;
    }
    @media (min-width: 768px) {
      .left-panel { display: flex; }
    }
    .left-panel::before {
      content: '';
      position: absolute;
      width: 480px; height: 480px;
      border-radius: 50%;
      border: 2px solid rgba(232,160,32,.15);
      top: -120px; left: -120px;
    }
    .left-panel::after {
      content: '';
      position: absolute;
      width: 300px; height: 300px;
      border-radius: 50%;
      border: 2px solid rgba(232,160,32,.1);
      bottom: -80px; right: -80px;
    }
    .dot-grid {
      position: absolute; inset: 0;
      background-image: radial-gradient(rgba(255,255,255,.06) 1px, transparent 1px);
      background-size: 28px 28px;
    }
    .gold-bar {
      width: 56px; height: 4px;
      background: linear-gradient(90deg, var(--gold), var(--gold-light));
      border-radius: 2px; margin-bottom: 1.5rem;
    }
    .logo-box {
      width: 120px; height: 120px;
      border-radius: 20px;
      background: rgba(255,255,255,.08);
      border: 2px dashed rgba(255,255,255,.25);
      display: flex; align-items: center; justify-content: center;
      margin-bottom: 2rem;
      backdrop-filter: blur(6px);
      position: relative; z-index: 1;
    }
    .school-name {
      font-family: 'Merriweather', serif;
      font-size: 1.45rem; color: #fff;
      text-align: center; line-height: 1.4;
      margin-bottom: .5rem; position: relative; z-index: 1;
    }
    .school-sub {
      font-size: .78rem; color: rgba(255,255,255,.5);
      letter-spacing: .12em; text-transform: uppercase;
      text-align: center; margin-bottom: 2.5rem; position: relative; z-index: 1;
    }
    .info-strip { display: flex; gap: 1rem; position: relative; z-index: 1; width: 100%; max-width: 320px; }
    .info-card {
      flex: 1;
      background: rgba(255,255,255,.07);
      border: 1px solid rgba(255,255,255,.12);
      border-radius: 12px; padding: .9rem .8rem;
      text-align: center; backdrop-filter: blur(4px);
    }
    .info-card .num { font-size: 1.35rem; font-weight: 800; color: var(--gold-light); }
    .info-card .lbl { font-size: .68rem; color: rgba(255,255,255,.5); margin-top: 2px; }

    /* ── RIGHT PANEL ── */
    .right-panel {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1.25rem;
      min-height: 100vh;
      background: var(--cream);
    }
    .form-card {
      width: 100%;
      max-width: 420px;
      background: #fff;
      border-radius: 24px;
      padding: 2.5rem 2rem;
      box-shadow: 0 8px 40px rgba(26,44,91,.1), 0 1px 4px rgba(26,44,91,.06);
      animation: slideUp .55s cubic-bezier(.22,1,.36,1) both;
    }
    @media (min-width: 768px) {
      .right-panel { padding: 2rem; }
      .form-card { padding: 2.8rem 2.5rem; }
    }
    @keyframes slideUp {
      from { opacity:0; transform: translateY(24px); }
      to   { opacity:1; transform: translateY(0); }
    }
    .form-icon {
      width: 64px; height: 64px;
      background: linear-gradient(135deg, var(--navy) 0%, #2a4080 100%);
      border-radius: 16px; display: flex; align-items: center; justify-content: center;
      margin: 0 auto 1.4rem;
      box-shadow: 0 6px 20px rgba(26,44,91,.25);
    }
    .form-icon svg { width: 30px; height: 30px; color: #fff; }
    .form-title {
      font-family: 'Merriweather', serif; font-size: 1.7rem;
      color: var(--navy-deep); text-align: center; margin-bottom: .4rem;
    }
    .form-desc { font-size: .82rem; color: #8a93a8; text-align: center; margin-bottom: 2rem; }

    .field-wrap { position: relative; margin-bottom: 1.1rem; }
    .field-icon {
      position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
      color: #b0b8cc; pointer-events: none; transition: color .2s;
      display: flex; align-items: center;
    }
    .field-wrap:focus-within .field-icon { color: var(--navy); }
    input[type="text"], input[type="password"] {
      width: 100%; padding: .85rem 1rem .85rem 2.7rem;
      border: 1.5px solid #e2e6ef; border-radius: 12px;
      font-family: 'Plus Jakarta Sans', sans-serif; font-size: .88rem;
      color: var(--navy-deep); background: var(--gray-soft); outline: none;
      transition: border-color .2s, box-shadow .2s, background .2s;
    }
    input:focus { border-color: var(--navy); background: #fff; box-shadow: 0 0 0 3px rgba(26,44,91,.1); }
    input::placeholder { color: #b0b8cc; }
    .toggle-pass {
      position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
      background: none; border: none; cursor: pointer; color: #b0b8cc;
      padding: 0; transition: color .2s; display: flex; align-items: center;
    }
    .toggle-pass:hover { color: var(--navy); }
    .btn-login {
      width: 100%; padding: .9rem;
      background: linear-gradient(135deg, var(--navy) 0%, #2a4080 100%);
      color: #fff; border: none; border-radius: 12px;
      font-family: 'Plus Jakarta Sans', sans-serif; font-size: .95rem; font-weight: 700;
      cursor: pointer; letter-spacing: .04em;
      position: relative; overflow: hidden;
      transition: transform .15s, box-shadow .15s;
      box-shadow: 0 4px 16px rgba(26,44,91,.3);
    }
    .btn-login:hover { transform: translateY(-1px); box-shadow: 0 8px 24px rgba(26,44,91,.35); }
    .btn-login:active { transform: translateY(0); }
    .btn-login .ripple {
      position: absolute; border-radius: 50%; background: rgba(255,255,255,.3);
      transform: scale(0); animation: ripple .55s linear; pointer-events: none;
    }
    @keyframes ripple { to { transform: scale(4); opacity: 0; } }
    .divider { display: flex; align-items: center; gap: .8rem; margin: 1.2rem 0; }
    .divider hr { flex:1; border: none; border-top: 1px solid #e8eaf0; }
    .divider span { font-size: .72rem; color: #b0b8cc; }
    .btn-forgot {
      width: 100%; padding: .8rem;
      border: 1.5px solid #e2e6ef; border-radius: 12px; background: transparent;
      font-family: 'Plus Jakarta Sans', sans-serif; font-size: .85rem; font-weight: 600;
      color: #8a93a8; cursor: pointer; transition: all .18s;
    }
    .btn-forgot:hover { border-color: var(--navy); color: var(--navy); background: rgba(26,44,91,.04); }
    .footer-note { text-align: center; font-size: .72rem; color: #b0b8cc; margin-top: 1.8rem; }
    .footer-note span { color: var(--gold); font-weight: 600; }
    .btn-login.loading .btn-text { opacity: 0; }
    .btn-login .spinner { display: none; position: absolute; inset: 0; align-items: center; justify-content: center; }
    .btn-login.loading .spinner { display: flex; }
    .spin {
      width: 20px; height: 20px;
      border: 2px solid rgba(255,255,255,.4); border-top-color: #fff;
      border-radius: 50%; animation: spin .6s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    @keyframes shake {
      0%,100% { transform: translateX(0); }
      20%,60% { transform: translateX(-6px); }
      40%,80% { transform: translateX(6px); }
    }
    .shake { animation: shake .4s ease; }
    .err-msg {
      display: none; background: #fef2f2;
      border: 1px solid #fecaca; border-radius: 10px;
      padding: .7rem 1rem; font-size: .8rem; color: #dc2626; margin-bottom: 1rem;
    }
    .err-msg.show { display: flex; align-items: center; gap: .5rem; }

    /* ── MODAL ── */
    .modal-overlay {
      position: fixed; inset: 0; z-index: 300;
      display: flex; align-items: center; justify-content: center; padding: 1.5rem;
      visibility: hidden; opacity: 0;
      transition: opacity .35s ease, visibility .35s ease;
    }
    .modal-overlay.open { visibility: visible; opacity: 1; }
    .modal-backdrop {
      position: absolute; inset: 0;
      background: rgba(8,16,38,.7);
      backdrop-filter: blur(8px);
    }
    .modal-card {
      position: relative; z-index: 10;
      width: 100%; max-width: 460px;
      background: #fff; border-radius: 28px; overflow: hidden;
      box-shadow: 0 40px 100px rgba(8,16,38,.4);
      transform: translateY(36px) scale(.96);
      opacity: 0;
      transition: transform .45s cubic-bezier(.22,1,.36,1), opacity .45s ease;
    }
    .modal-overlay.open .modal-card { transform: translateY(0) scale(1); opacity: 1; }
    .modal-header {
      background: linear-gradient(140deg, var(--navy-deep) 0%, var(--navy) 55%, #2a4080 100%);
      padding: 2rem 2rem 1.6rem;
      position: relative; overflow: hidden; text-align: center;
    }
    .mh-dots {
      position: absolute; inset: 0;
      background-image: radial-gradient(rgba(255,255,255,.055) 1px, transparent 1px);
      background-size: 22px 22px;
    }
    .modal-close {
      position: absolute; top: 1rem; right: 1rem;
      width: 32px; height: 32px; border-radius: 8px;
      background: rgba(255,255,255,.12); border: none;
      cursor: pointer; display: flex; align-items: center; justify-content: center;
      color: rgba(255,255,255,.7); z-index: 2;
    }
    .modal-icon-wrap {
      width: 72px; height: 72px;
      background: rgba(255,255,255,.1);
      border: 2px solid rgba(255,255,255,.18);
      border-radius: 20px;
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto 1.2rem; position: relative; z-index: 1;
    }
    .modal-body { padding: 2rem 2rem 2.5rem; }
    .mstep-title {
      font-family: 'Merriweather', serif;
      font-size: 1.2rem; color: var(--navy-deep);
      text-align: center; margin-bottom: .4rem;
    }
    .mstep-desc { font-size: .8rem; color: #8a93a8; text-align: center; line-height: 1.65; margin-bottom: 1.6rem; }
    .mlabel {
      display: block; font-size: .73rem; font-weight: 700; color: #8a93a8;
      text-transform: uppercase; letter-spacing: .08em; margin-bottom: .4rem;
    }
    .mactions { display: flex; gap: .75rem; margin-top: 1.5rem; }
    .btn-mcancel {
      flex: 1; padding: .85rem;
      border: 1.5px solid #e2e6ef; border-radius: 12px; background: transparent;
      font-family: 'Plus Jakarta Sans', sans-serif; font-size: .85rem; font-weight: 600;
      color: #8a93a8; cursor: pointer;
    }
    .btn-mprimary {
      flex: 1.6; padding: .85rem;
      background: linear-gradient(135deg, var(--navy) 0%, #2a4080 100%);
      color: #fff; border: none; border-radius: 12px;
      font-family: 'Plus Jakarta Sans', sans-serif; font-size: .88rem; font-weight: 700;
      cursor: pointer; display: flex; align-items: center; justify-content: center; gap: .45rem;
    }
    .mfooter { text-align: center; font-size: .72rem; color: #b0b8cc; margin-top: 1.4rem; }
    .mfooter a { color: var(--navy); font-weight: 700; text-decoration: none; }
    input.input-error { border-color: #dc2626 !important; box-shadow: 0 0 0 3px rgba(220,38,38,.1) !important; }
  </style>
</head>
<body>

  <!-- ═══ LEFT (hanya tampil di tablet/laptop) ═══ -->
  <div class="left-panel">
    <div class="dot-grid"></div>
    <div style="position:relative;z-index:1;display:flex;flex-direction:column;align-items:center;width:100%;max-width:320px;">
      <div class="logo-box">
        <svg width="56" height="56" viewBox="0 0 56 56" fill="none">
          <rect x="8" y="8" width="40" height="40" rx="8" stroke="white" stroke-width="2"/>
          <circle cx="28" cy="22" r="7" stroke="white" stroke-width="2"/>
          <path d="M14 44c0-7.732 6.268-14 14-14s14 6.268 14 14" stroke="white" stroke-width="2" stroke-linecap="round"/>
        </svg>
      </div>
      <div class="gold-bar"></div>
      <div class="school-name">SMKN 1 Sungailiat</div>
      <div class="school-sub">Bangka Belitung · Est. 1967</div>
      <p style="font-size:.82rem;color:rgba(255,255,255,.55);text-align:center;line-height:1.7;margin-bottom:2.5rem;position:relative;z-index:1;">
        Sistem Informasi E-Raport<br>Memudahkan pelaporan hasil belajar<br>siswa secara digital dan terintegrasi.
      </p>
      <div class="info-strip">
        <div class="info-card"><div class="num">1.2K+</div><div class="lbl">Siswa</div></div>
        <div class="info-card"><div class="num">80+</div><div class="lbl">Guru</div></div>
        <div class="info-card"><div class="num">12</div><div class="lbl">Jurusan</div></div>
      </div>
    </div>
  </div>

  <!-- ═══ RIGHT (form login, full width di HP) ═══ -->
  <div class="right-panel">
    <div class="form-card">
      <div class="form-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
          <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
        </svg>
      </div>
      <div class="form-title">Masuk</div>
      <div class="form-desc">Selamat datang di E-Raport SMKN 1 Sungailiat</div>

      <form action="{{ route('login.post') }}" method="POST" id="loginForm">
        @csrf
        <div class="err-msg {{ $errors->has('login') ? 'show' : '' }}" id="errMsg">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <span id="errText">{{ $errors->first('login') ?? 'Username atau password salah.' }}</span>
        </div>

        <div class="field-wrap">
          <span class="field-icon">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.582-7 8-7s8 3 8 7"/></svg>
          </span>
          <input type="text" id="username" name="login" placeholder="NISN / NIP / Email" autocomplete="username" value="{{ old('login') }}" required/>
        </div>

        <div class="field-wrap" style="margin-bottom:1.4rem;">
          <span class="field-icon">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          </span>
          <input type="password" id="password" name="password" placeholder="Masukkan password" required/>
          <button class="toggle-pass" id="togglePass" type="button">
            <svg id="eyeIcon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
            </svg>
          </button>
        </div>

        <div style="margin-bottom:1.5rem;display:flex;align-items:center;gap:.5rem;">
          <input type="checkbox" id="remember" name="remember" style="width:auto;margin:0;accent-color:var(--navy);">
          <label for="remember" style="font-size:.75rem;color:#8a93a8;font-weight:600;cursor:pointer;">Ingat Saya</label>
        </div>

        <button class="btn-login" id="loginBtn" type="submit">
          <span class="btn-text">Masuk</span>
          <span class="spinner"><div class="spin"></div></span>
        </button>
      </form>

      <div class="divider"><hr/><span>atau</span><hr/></div>
      <button class="btn-forgot" id="openResetModal" type="button">🔑 Lupa Password?</button>
      <div class="footer-note">Tahun Pelajaran <span>2025/2026</span> · Semester Genap</div>
    </div>
  </div>

  <!-- ═══ MODAL RESET PASSWORD ═══ -->
  <div class="modal-overlay" id="resetModal">
    <div class="modal-backdrop" id="mBackdrop"></div>
    <div class="modal-card">
      <div class="modal-header">
        <div class="mh-dots"></div>
        <button class="modal-close" id="mClose">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
        <div class="modal-icon-wrap">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/>
          </svg>
        </div>
      </div>
      <div class="modal-body">
        <form action="{{ route('forgot-password') }}" method="POST">
          @csrf
          <div class="mstep-title">Lupa Kata Sandi?</div>
          <div class="mstep-desc">Masukkan NISN (siswa) atau NIP (guru/admin) Anda. Permintaan reset akan dikirim ke Admin Sekolah.</div>
          <label class="mlabel">NISN / NIP</label>
          <div class="field-wrap">
            <span class="field-icon">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
            </span>
            <input type="text" name="username" placeholder="Masukkan NISN / NIP Anda" required/>
          </div>
          <div class="mactions">
            <button type="button" class="btn-mcancel" id="s1Cancel">Batal</button>
            <button type="submit" class="btn-mprimary">
              Ajukan Reset
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </button>
          </div>
        </form>
        <div class="mfooter">Butuh bantuan? <a href="#">Hubungi Admin Sekolah</a></div>
      </div>
    </div>
  </div>

  @if(session('success'))
    <script>alert("{{ session('success') }}");</script>
  @endif
  @if($errors->has('username'))
    <script>alert("{{ $errors->first('username') }}");</script>
  @endif

<script>
  const pIn = document.getElementById('password');
  const eIc = document.getElementById('eyeIcon');
  document.getElementById('togglePass').addEventListener('click', () => {
    const s = pIn.type === 'password';
    pIn.type = s ? 'text' : 'password';
    eIc.innerHTML = s
      ? `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>`
      : `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
  });

  document.getElementById('loginBtn').addEventListener('click', function(e) {
    const btn = this;
    const rect = btn.getBoundingClientRect();
    const r = document.createElement('span');
    r.className = 'ripple';
    const sz = Math.max(rect.width, rect.height);
    r.style.cssText = `width:${sz}px;height:${sz}px;left:${e.clientX-rect.left-sz/2}px;top:${e.clientY-rect.top-sz/2}px`;
    btn.appendChild(r);
    setTimeout(() => r.remove(), 600);
    const user = document.getElementById('username').value.trim();
    const pass = pIn.value;
    if (user && pass) {
      btn.classList.add('loading');
    } else {
      e.preventDefault();
      const err = document.getElementById('errMsg');
      document.getElementById('errText').textContent = 'Username dan password tidak boleh kosong.';
      err.classList.add('show');
      const c = document.querySelector('.form-card');
      c.classList.remove('shake'); void c.offsetWidth; c.classList.add('shake');
    }
  });

  const modal = document.getElementById('resetModal');
  function openModal()  { modal.classList.add('open'); document.body.style.overflow = 'hidden'; }
  function closeModal() { modal.classList.remove('open'); document.body.style.overflow = ''; }
  document.getElementById('openResetModal').addEventListener('click', openModal);
  document.getElementById('mClose').addEventListener('click', closeModal);
  document.getElementById('mBackdrop').addEventListener('click', closeModal);
  document.getElementById('s1Cancel').addEventListener('click', closeModal);
</script>
</body>
</html>
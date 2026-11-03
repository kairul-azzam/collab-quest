<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard - To-Do List</title>
  <style>
    * { box-sizing: border-box; }
    body {
      min-height: 100vh;
      margin: 0;
      padding: 42px 20px;
      color: #172033;
      font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      background:
        radial-gradient(ellipse at 8% 8%, rgba(129, 140, 248, .18), transparent 32%),
        radial-gradient(ellipse at 92% 88%, rgba(45, 212, 191, .14), transparent 30%),
        #f4f6fb;
    }
    .wrap { max-width: 820px; margin: 0 auto; }
    .nav { display: flex; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 24px; }
    .nav h1 { margin: 0; font-size: 26px; letter-spacing: -.7px; }
    .nav h1 small { display: inline-block; margin-left: 8px; color: #78839a; letter-spacing: 0; }
    .box {
      margin-bottom: 18px;
      padding: 26px;
      border: 1px solid rgba(255, 255, 255, .9);
      border-radius: 20px;
      background: rgba(255, 255, 255, .94);
      box-shadow: 0 16px 42px rgba(32, 45, 83, .08), 0 3px 10px rgba(32, 45, 83, .03);
    }
    .box h2 { margin: 0 0 18px; font-size: 18px; letter-spacing: -.3px; }
    label { display: block; margin-top: 16px; color: #475569; font-size: 13px; font-weight: 650; }
    input {
      width: 100%;
      margin-top: 8px;
      padding: 12px 14px;
      border: 1px solid #dce2ed;
      border-radius: 10px;
      outline: none;
      background: #fbfcfe;
      color: #172033;
      font: inherit;
      transition: border-color .2s, box-shadow .2s, background .2s;
    }
    input:focus { border-color: #7267e8; background: #fff; box-shadow: 0 0 0 4px rgba(114, 103, 232, .13); }
    button {
      padding: 10px 16px;
      border: 0;
      border-radius: 10px;
      background: linear-gradient(135deg, #7165e8, #5549cb);
      box-shadow: 0 6px 14px rgba(91, 77, 205, .16);
      color: #fff;
      font: inherit;
      font-size: 13px;
      font-weight: 700;
      cursor: pointer;
      transition: transform .2s, box-shadow .2s, opacity .2s;
    }
    button:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 9px 18px rgba(91, 77, 205, .23); }
    button:disabled { background: #a7aec0; box-shadow: none; cursor: not-allowed; }
    button:focus-visible { outline: 3px solid rgba(114, 103, 232, .35); outline-offset: 3px; }
    .nav #btn-logout { padding: 10px 16px; }
    .abu { background: #64748b; box-shadow: 0 6px 14px rgba(71, 85, 105, .13); }
    .merah { background: #dc4c55; box-shadow: 0 6px 14px rgba(220, 76, 85, .15); }
    .kecil { padding: 7px 11px; font-size: 12px; }
    .baris-tombol { display: flex; gap: 9px; margin-top: 18px; }
    .error-text { min-height: 16px; display: block; margin-top: 4px; color: #d14343; font-size: 12px; }
    .alert { display: none; margin-bottom: 16px; padding: 12px 14px; border-radius: 11px; font-size: 14px; }
    .alert-error { display: block; border: 1px solid #fecaca; background: #fff1f2; color: #a32135; }
    .alert-success { display: block; border: 1px solid #bbf7d0; background: #f0fdf4; color: #166534; }
    #info { padding: 20px 0; color: #78839a; text-align: center; }
    ul { margin: 0; padding: 0; list-style: none; }
    .item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 16px;
      padding: 16px 2px;
      border-bottom: 1px solid #edf0f6;
    }
    .item:last-child { border-bottom: 0; }
    .isi { min-width: 0; overflow-wrap: anywhere; }
    .isi strong { font-size: 14px; }
    .isi small { display: block; margin-top: 5px; color: #78839a; font-size: 12px; }
    .item.selesai strong { color: #94a3b8; text-decoration: line-through; }
    .aksi { display: flex; flex-wrap: wrap; justify-content: flex-end; gap: 7px; }
    @media (max-width: 560px) {
      body { padding: 24px 14px; }
      .nav { margin-bottom: 18px; }
      .nav h1 { font-size: 21px; }
      .nav h1 small { display: block; margin: 4px 0 0; font-size: 13px !important; }
      .box { padding: 21px 18px; border-radius: 17px; }
      .item { align-items: flex-start; flex-direction: column; }
      .aksi { justify-content: flex-start; }
    }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="nav">
      <h1>To-Do List <small id="nama" style="font-weight:normal;font-size:14px;"></small></h1>
      <button id="btn-logout" class="abu">Logout</button>
    </div>

    <div id="alert" class="alert"></div>

    <div class="box">
      <h2 id="judul-form">Tambah Tugas</h2>
      <form id="form-tugas">
        <label>Judul
          <input type="text" id="title" required maxlength="255">
          <span class="error-text" id="err-title"></span>
        </label>
        <label>Deadline (boleh kosong)
          <input type="date" id="deadline">
          <span class="error-text" id="err-deadline"></span>
        </label>
        <div class="baris-tombol">
          <button type="submit" id="btn-simpan">Tambah</button>
          <button type="button" id="btn-batal" class="abu" style="display:none;">Batal</button>
        </div>
      </form>
    </div>

    <div class="box">
      <h2>Daftar Tugas</h2>
      <div id="info">Memuat tugas...</div>
      <ul id="daftar"></ul>
    </div>
  </div>

  <script src="{{ asset('js/api.js') }}"></script>
  <script src="{{ asset('js/dashboard.js') }}"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard - To-Do List</title>
  <style>
    * { box-sizing: border-box; }
    body { font-family: Arial, sans-serif; background: #f1f5f9; margin: 0; padding: 24px 16px; }
    .wrap { max-width: 700px; margin: 0 auto; }
    .nav { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
    .nav h1 { margin: 0; font-size: 22px; }
    .box { background: #fff; padding: 16px; border-radius: 10px;
           box-shadow: 0 2px 8px rgba(0,0,0,.1); margin-bottom: 16px; }
    .box h2 { margin-top: 0; font-size: 17px; }
    label { display: block; font-size: 14px; margin-top: 10px; }
    input { width: 100%; padding: 8px; margin-top: 4px; border: 1px solid #cbd5e1; border-radius: 6px; }
    button { padding: 8px 14px; border: 0; background: #2563eb; color: #fff;
             border-radius: 6px; cursor: pointer; }
    button:disabled { background: #94a3b8; cursor: not-allowed; }
    .abu { background: #64748b; }
    .merah { background: #dc2626; }
    .kecil { padding: 5px 9px; font-size: 12px; }
    .baris-tombol { margin-top: 12px; display: flex; gap: 8px; }
    .error-text { color: #dc2626; font-size: 12px; display: block; min-height: 14px; }
    .alert { display: none; padding: 10px; border-radius: 6px; margin-bottom: 12px; font-size: 14px; }
    .alert-error { display: block; background: #fee2e2; color: #991b1b; }
    .alert-success { display: block; background: #dcfce7; color: #166534; }
    #info { color: #64748b; text-align: center; padding: 12px 0; }
    ul { list-style: none; margin: 0; padding: 0; }
    .item { display: flex; justify-content: space-between; align-items: center; gap: 12px;
            padding: 10px 0; border-bottom: 1px solid #e2e8f0; }
    .item:last-child { border-bottom: 0; }
    .isi small { display: block; color: #64748b; margin-top: 2px; }
    .item.selesai strong { text-decoration: line-through; color: #94a3b8; }
    .aksi { display: flex; gap: 6px; flex-wrap: wrap; justify-content: flex-end; }
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
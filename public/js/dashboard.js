// ===== Penjaga: harus login dulu =====
if (!getToken()) {
  window.location.href = '/todo/login';
}

// ===== Untuk tes mock =====
// ?kasus=kosong        -> daftar tugas kosong
// ?tambah=validasi_gagal -> tambah tugas gagal validasi
const params = new URLSearchParams(window.location.search);
const kasusDaftar = params.get('kasus') || 'success';
const kasusTambah = params.get('tambah') || 'success';

// ===== Data & elemen =====
let tasks = [];
let editingId = null;

const elAlert = document.getElementById('alert');
const elInfo = document.getElementById('info');
const elDaftar = document.getElementById('daftar');
const elForm = document.getElementById('form-tugas');
const elTitle = document.getElementById('title');
const elDeadline = document.getElementById('deadline');
const elSimpan = document.getElementById('btn-simpan');
const elBatal = document.getElementById('btn-batal');
const elJudulForm = document.getElementById('judul-form');

// ===== Fungsi bantu =====
function esc(teks) {
  const div = document.createElement('div');
  div.textContent = teks;
  return div.innerHTML; // mencegah kode HTML dari judul ikut jalan
}

function tampilPesan(pesan, tipe) {
  elAlert.textContent = pesan;
  elAlert.className = 'alert alert-' + tipe;
  if (tipe === 'success') {
    setTimeout(() => (elAlert.className = 'alert'), 2500);
  }
}

function bersihkanError() {
  elAlert.className = 'alert';
  document.getElementById('err-title').textContent = '';
  document.getElementById('err-deadline').textContent = '';
}

function tampilErrorField(errors) {
  if (!errors) return;
  if (errors.title) document.getElementById('err-title').textContent = errors.title[0];
  if (errors.deadline) document.getElementById('err-deadline').textContent = errors.deadline[0];
}

// Kalau token salah/habis (401), kembali ke login
function cek401(res) {
  if (res.status === 401) {
    removeToken();
    window.location.href = '/todo/login';
    return true;
  }
  return false;
}

// ===== Tampilkan daftar =====
function render() {
  elDaftar.innerHTML = '';

  if (tasks.length === 0) {
    elInfo.textContent = 'Belum ada tugas.';
    return;
  }
  elInfo.textContent = '';

  tasks.forEach((t) => {
    const li = document.createElement('li');
    li.className = 'item' + (t.status === 'done' ? ' selesai' : '');
    li.innerHTML = `
      <div class="isi">
        <strong>${esc(t.title)}</strong>
        <small>${t.deadline ? 'Deadline: ' + esc(t.deadline) : 'Tanpa deadline'}</small>
      </div>
      <div class="aksi">
        <button class="kecil" data-aksi="status" data-id="${t.id}">
          ${t.status === 'done' ? 'Tandai belum selesai' : 'Tandai selesai'}
        </button>
        <button class="kecil abu" data-aksi="edit" data-id="${t.id}">Edit</button>
        <button class="kecil merah" data-aksi="hapus" data-id="${t.id}">Hapus</button>
      </div>`;
    elDaftar.appendChild(li);
  });
}

// ===== Ambil data awal =====
async function muatProfil() {
  try {
    const res = await Api.getProfile();
    if (cek401(res)) return;
    document.getElementById('nama').textContent = '- ' + res.body.data.name;
  } catch (err) {}
}

async function muatTugas() {
  elInfo.textContent = 'Memuat tugas...';
  try {
    const res = await Api.getTasks(kasusDaftar);
    if (cek401(res)) return;
    tasks = res.body.data;
    render();
  } catch (err) {
    elInfo.textContent = 'Gagal memuat tugas. Coba refresh halaman.';
  }
}

// ===== Mode form: tambah / edit =====
function modeTambah() {
  editingId = null;
  elForm.reset();
  elJudulForm.textContent = 'Tambah Tugas';
  elSimpan.textContent = 'Tambah';
  elBatal.style.display = 'none';
  bersihkanError();
}

function modeEdit(task) {
  editingId = task.id;
  elTitle.value = task.title;
  elDeadline.value = task.deadline || '';
  elJudulForm.textContent = 'Edit Tugas';
  elSimpan.textContent = 'Simpan Perubahan';
  elBatal.style.display = 'inline-block';
  bersihkanError();
  elTitle.focus();
}

elBatal.addEventListener('click', modeTambah);

// ===== Simpan (tambah / edit) =====
elForm.addEventListener('submit', async (e) => {
  e.preventDefault();
  bersihkanError();

  const data = {
    title: elTitle.value,
    deadline: elDeadline.value || null,
  };

  const teksAwal = elSimpan.textContent;
  elSimpan.textContent = 'Memuat...';
  elSimpan.disabled = true;

  try {
    if (editingId === null) {
      // --- TAMBAH ---
      const res = await Api.addTask(data, kasusTambah);
      if (cek401(res)) return;

      if (res.status === 201) {
        const baru = res.body.data;
        if (USE_MOCK) baru.id = Date.now(); // mock selalu id 4, jadi kita buat unik
        tasks.push(baru);
        render();
        modeTambah();
        tampilPesan('Tugas berhasil ditambahkan', 'success');
      } else {
        tampilPesan('Gagal menambahkan tugas', 'error');
        tampilErrorField(res.body.errors);
      }
    } else {
      // --- EDIT ---
      const res = await Api.updateTask(editingId, data);
      if (cek401(res)) return;

      if (res.status === 200) {
        const task = tasks.find((t) => t.id === editingId);
        task.title = data.title;
        task.deadline = data.deadline;
        render();
        modeTambah();
        tampilPesan('Tugas berhasil diperbarui', 'success');
      } else {
        tampilPesan('Gagal memperbarui tugas', 'error');
        tampilErrorField(res.body.errors);
      }
    }
  } catch (err) {
    tampilPesan('Terjadi kesalahan. Coba lagi.', 'error');
  }

  elSimpan.disabled = false;
  elSimpan.textContent = editingId === null ? 'Tambah' : 'Simpan Perubahan';
});

// ===== Tombol di daftar: status, edit, hapus =====
elDaftar.addEventListener('click', async (e) => {
  const tombol = e.target.closest('button');
  if (!tombol) return;

  const id = Number(tombol.dataset.id);
  const aksi = tombol.dataset.aksi;
  const task = tasks.find((t) => t.id === id);

  if (aksi === 'edit') {
    modeEdit(task);
    return;
  }

  tombol.disabled = true;

  try {
    if (aksi === 'status') {
      const statusBaru = task.status === 'done' ? 'todo' : 'done';
      const kasus = statusBaru === 'done' ? 'success_done' : 'success_todo';
      const res = await Api.updateStatus(id, statusBaru, kasus);
      if (cek401(res)) return;

      if (res.status === 200) {
        task.status = statusBaru;
        render();
        tampilPesan('Status tugas berhasil diperbarui', 'success');
      } else {
        tampilPesan('Gagal memperbarui status tugas', 'error');
        tombol.disabled = false;
      }
    }

    if (aksi === 'hapus') {
      if (!confirm('Yakin hapus tugas ini?')) {
        tombol.disabled = false;
        return;
      }
      const res = await Api.deleteTask(id);
      if (cek401(res)) return;

      if (res.status === 200) {
        tasks = tasks.filter((t) => t.id !== id);
        if (editingId === id) modeTambah();
        render();
        tampilPesan('Tugas berhasil dihapus', 'success');
      } else {
        tampilPesan('Gagal menghapus tugas', 'error');
        tombol.disabled = false;
      }
    }
  } catch (err) {
    tampilPesan('Terjadi kesalahan. Coba lagi.', 'error');
    tombol.disabled = false;
  }
});

// ===== Logout =====
document.getElementById('btn-logout').addEventListener('click', async () => {
  try {
    await Api.logout();
  } catch (err) {}
  removeToken();
  window.location.href = '/todo/login';
});

// ===== Mulai =====
muatProfil();
muatTugas();
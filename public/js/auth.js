// Untuk tes mock: tambahkan ?kasus=gagal di URL (lihat bagian tes di bawah)
const kasus = new URLSearchParams(window.location.search).get('kasus');

function setLoading(tombol, loading) {
  if (loading) {
    tombol.dataset.teks = tombol.textContent;
    tombol.textContent = 'Memuat...';
    tombol.disabled = true;
  } else {
    tombol.textContent = tombol.dataset.teks;
    tombol.disabled = false;
  }
}

function tampilkanPesan(pesan, tipe) {
  const alert = document.getElementById('alert');
  alert.textContent = pesan;
  alert.className = 'alert alert-' + tipe; // alert-error atau alert-success
}

function tampilkanError(errors) {
  if (!errors) return;
  for (const field in errors) {
    const el = document.querySelector('[data-error="' + field + '"]');
    if (el) el.textContent = errors[field][0];
  }
}

function bersihkanPesan() {
  document.getElementById('alert').className = 'alert';
  document.querySelectorAll('.error-text').forEach((el) => (el.textContent = ''));
}

// ===== LOGIN =====
const formLogin = document.getElementById('form-login');
if (formLogin) {
  formLogin.addEventListener('submit', async (e) => {
    e.preventDefault();
    bersihkanPesan();

    const tombol = formLogin.querySelector('button');
    setLoading(tombol, true);

    const data = {
      email: formLogin.email.value,
      password: formLogin.password.value,
    };

    try {
      const res = await Api.login(data, kasus || 'success');

      if (res.status === 200) {
        setToken(res.body.data.token);
        window.location.href = '/todo/dashboard';
        return;
      }

      tampilkanPesan(res.body.message, 'error');
      tampilkanError(res.body.errors);
    } catch (err) {
      tampilkanPesan('Terjadi kesalahan. Coba lagi.', 'error');
    }

    setLoading(tombol, false);
  });
}

// ===== REGISTER =====
const formRegister = document.getElementById('form-register');
if (formRegister) {
  formRegister.addEventListener('submit', async (e) => {
    e.preventDefault();
    bersihkanPesan();

    const tombol = formRegister.querySelector('button');
    setLoading(tombol, true);

    const data = {
      name: formRegister.name.value,
      email: formRegister.email.value,
      password: formRegister.password.value,
      password_confirmation: formRegister.password_confirmation.value,
    };

    try {
      const res = await Api.register(data, kasus || 'success');

      if (res.status === 201) {
        tampilkanPesan('Registrasi berhasil! Mengarahkan ke halaman dashboard...', 'success');
        setTimeout(() => (window.location.href = '/todo/dashboard'), 1500);
        return;
      }

      tampilkanPesan(res.body.message, 'error');
      tampilkanError(res.body.errors);
    } catch (err) {
      tampilkanPesan('Terjadi kesalahan. Coba lagi.', 'error');
    }

    setLoading(tombol, false);
  });
}
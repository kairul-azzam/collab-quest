// ===== Pengaturan =====
const USE_MOCK = true; // true = pakai mock, false = pakai API asli
const BASE_URL = 'http://localhost:8000/api';
const MOCK_URL = '/mock/mock-api.json';

// ===== Token =====
function getToken() {
  return localStorage.getItem('token');
}
function setToken(token) {
  localStorage.setItem('token', token);
}
function removeToken() {
  localStorage.removeItem('token');
}

// ===== Mock =====
const delay = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

async function mockResponse(group, kasus) {
  await delay(500); // pura-pura loading
  const res = await fetch(MOCK_URL);
  const mock = await res.json();
  return mock[group][kasus]; // hasilnya { status, body }
}

// ===== API asli =====
async function request(method, path, body = null) {
  const headers = {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  };

  const token = getToken();
  if (token) {
    headers['Authorization'] = 'Bearer ' + token;
  }

  const res = await fetch(BASE_URL + path, {
    method: method,
    headers: headers,
    body: body ? JSON.stringify(body) : null,
  });

  return { status: res.status, body: await res.json() };
}

// ===== Semua pemanggilan API ada di sini =====
// Parameter "kasus" hanya dipakai saat mock, untuk mencoba kondisi lain
// (misalnya 'gagal', 'kosong', 'validasi_gagal').
const Api = {
  register(data, kasus = 'success') {
    return USE_MOCK
      ? mockResponse('register', kasus)
      : request('POST', '/register', data);
  },

  login(data, kasus = 'success') {
    return USE_MOCK
      ? mockResponse('login', kasus)
      : request('POST', '/login', data);
  },

  getProfile(kasus = 'success') {
    return USE_MOCK
      ? mockResponse('profile', kasus)
      : request('GET', '/profile');
  },

  logout(kasus = 'success') {
    return USE_MOCK
      ? mockResponse('logout', kasus)
      : request('POST', '/logout');
  },

  getTasks(kasus = 'success') {
    return USE_MOCK
      ? mockResponse('tasks_index', kasus)
      : request('GET', '/tasks');
  },

  addTask(data, kasus = 'success') {
    return USE_MOCK
      ? mockResponse('tasks_store', kasus)
      : request('POST', '/tasks', data);
  },

  updateTask(id, data, kasus = 'success') {
    return USE_MOCK
      ? mockResponse('tasks_update', kasus)
      : request('PUT', '/tasks/' + id, data);
  },

  updateStatus(id, status, kasus = 'success_done') {
    return USE_MOCK
      ? mockResponse('tasks_status', kasus)
      : request('PATCH', '/tasks/' + id + '/status', { status: status });
  },

  deleteTask(id, kasus = 'success') {
    return USE_MOCK
      ? mockResponse('tasks_destroy', kasus)
      : request('DELETE', '/tasks/' + id);
  },
};
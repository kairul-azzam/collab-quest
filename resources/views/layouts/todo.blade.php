<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title') - To-Do List</title>
  <style>
    * { box-sizing: border-box; }
    body { font-family: Arial, sans-serif; background: #f1f5f9; margin: 0;
           display: flex; justify-content: center; padding: 40px 16px; }
    .card { background: #fff; width: 100%; max-width: 400px; padding: 24px;
            border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
    h1 { margin-top: 0; font-size: 22px; }
    label { display: block; margin-top: 12px; font-size: 14px; }
    input { width: 100%; padding: 8px; margin-top: 4px;
            border: 1px solid #cbd5e1; border-radius: 6px; }
    button { width: 100%; margin-top: 16px; padding: 10px; border: 0;
             background: #2563eb; color: #fff; border-radius: 6px; cursor: pointer; }
    button:disabled { background: #94a3b8; cursor: not-allowed; }
    .error-text { color: #dc2626; font-size: 12px; display: block; min-height: 14px; }
    .alert { display: none; padding: 10px; border-radius: 6px; margin-bottom: 8px; font-size: 14px; }
    .alert-error { display: block; background: #fee2e2; color: #991b1b; }
    .alert-success { display: block; background: #dcfce7; color: #166534; }
    .link { margin-top: 16px; text-align: center; font-size: 14px; }
  </style>
</head>
<body>
  <div class="card">
    @yield('content')
  </div>

  <script src="{{ asset('js/api.js') }}"></script>
  <script src="{{ asset('js/auth.js') }}"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title') - To-Do List</title>
  <style>
    * { box-sizing: border-box; }
    body {
      min-height: 100vh;
      margin: 0;
      padding: 48px 20px;
      display: flex;
      justify-content: center;
      align-items: center;
      color: #172033;
      font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      background:
        radial-gradient(ellipse at 12% 12%, rgba(129, 140, 248, .2), transparent 34%),
        radial-gradient(ellipse at 88% 86%, rgba(45, 212, 191, .16), transparent 32%),
        #f4f6fb;
    }
    .card {
      width: 100%;
      max-width: 440px;
      padding: 38px;
      border: 1px solid rgba(255, 255, 255, .9);
      border-radius: 24px;
      background: rgba(255, 255, 255, .94);
      box-shadow: 0 24px 70px rgba(32, 45, 83, .12), 0 4px 12px rgba(32, 45, 83, .04);
    }
    h1 { margin: 0 0 26px; font-size: 29px; line-height: 1.2; letter-spacing: -.8px; }
    label { display: block; margin-top: 18px; color: #475569; font-size: 13px; font-weight: 650; }
    input {
      width: 100%;
      margin-top: 8px;
      padding: 13px 14px;
      border: 1px solid #dce2ed;
      border-radius: 11px;
      outline: none;
      background: #fbfcfe;
      color: #172033;
      font: inherit;
      transition: border-color .2s, box-shadow .2s, background .2s;
    }
    input:focus { border-color: #7267e8; background: #fff; box-shadow: 0 0 0 4px rgba(114, 103, 232, .13); }
    button {
      width: 100%;
      margin-top: 24px;
      padding: 13px 16px;
      border: 0;
      border-radius: 11px;
      background: linear-gradient(135deg, #7165e8, #5549cb);
      box-shadow: 0 8px 18px rgba(91, 77, 205, .2);
      color: #fff;
      font: inherit;
      font-weight: 700;
      cursor: pointer;
      transition: transform .2s, box-shadow .2s, opacity .2s;
    }
    button:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 11px 22px rgba(91, 77, 205, .26); }
    button:disabled { background: #a7aec0; box-shadow: none; cursor: not-allowed; }
    button:focus-visible, a:focus-visible { outline: 3px solid rgba(114, 103, 232, .35); outline-offset: 3px; }
    .error-text { min-height: 16px; display: block; margin-top: 4px; color: #d14343; font-size: 12px; }
    .alert { display: none; margin-bottom: 14px; padding: 12px 14px; border-radius: 11px; font-size: 14px; }
    .alert-error { display: block; border: 1px solid #fecaca; background: #fff1f2; color: #a32135; }
    .alert-success { display: block; border: 1px solid #bbf7d0; background: #f0fdf4; color: #166534; }
    .link { margin: 22px 0 0; color: #647087; text-align: center; font-size: 14px; }
    .link a { color: #5b50d2; font-weight: 700; text-decoration: none; }
    .link a:hover { text-decoration: underline; text-underline-offset: 3px; }
    @media (max-width: 480px) {
      body { padding: 24px 16px; }
      .card { padding: 28px 22px; border-radius: 20px; }
      h1 { font-size: 26px; }
    }
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
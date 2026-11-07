@extends('layouts.todo')
@section('title', 'Register')

@section('content')
  <h1>Daftar Akun</h1>
  <div id="alert" class="alert"></div>

  <form id="form-register">
    <label>Nama
      <input type="text" name="name" required>
      <span class="error-text" data-error="name"></span>
    </label>

    <label>Email
      <input type="email" name="email" required>
      <span class="error-text" data-error="email"></span>
    </label>

    <label>Password
      <input type="password" name="password" required minlength="8">
      <span class="error-text" data-error="password"></span>
    </label>

    <label>Konfirmasi Password
      <input type="password" name="password_confirmation" required>
      <span class="error-text" data-error="password_confirmation"></span>
    </label>

    <button type="submit">Daftar</button>
  </form>

  <p class="link">Sudah punya akun? <a href="/todo/login">Login</a></p>
@endsection
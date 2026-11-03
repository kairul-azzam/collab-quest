@extends('layouts.todo')
@section('title', 'Login')

@section('content')
  <h1>Login</h1>
  <div id="alert" class="alert"></div>

  <form id="form-login">
    <label>Email
      <input type="email" name="email" required>
      <span class="error-text" data-error="email"></span>
    </label>

    <label>Password
      <input type="password" name="password" required>
      <span class="error-text" data-error="password"></span>
    </label>

    <button type="submit">Login</button>
  </form>

  <p class="link">Belum punya akun? <a href="/todo/register">Daftar</a></p>
@endsection
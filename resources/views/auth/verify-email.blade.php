@extends('layouts.app')

@section('content')

<div class="card">
  <div class="card-bg card-bg-1 signin"></div>
  <div class="card-bg card-bg-2 signin"></div>

  <div class="form signin active">
    <form method="POST" action="/email/verification-notification">
      @csrf

      <h2>Vérifiez votre email</h2>

      @if (session('message'))
        <p style="color:green">{{ session('message') }}</p>
      @endif

      <p>Un email de vérification vous a été envoyé.</p>

      <button type="submit">Renvoyer</button>

      <a href="/logout">Se déconnecter</a>

    </form>
  </div>
</div>

@endsection

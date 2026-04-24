@extends('layouts.app')

@section('content')

<div class="card">
  <div class="card-bg card-bg-1 signin"></div>
  <div class="card-bg card-bg-2 signin"></div>

  <div class="form signin active">
    <form method="POST" action="/profile/update" enctype="multipart/form-data">
      @csrf

      <h2>Mon Profil</h2>

      @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
      @endif

      <input name="name" value="{{ auth()->user()->name }}" placeholder="Nom">

      <input name="email" value="{{ auth()->user()->email }}">

      <input name="phone" value="{{ auth()->user()->phone }}">

      <input type="file" name="profile_photo">

      <button>Mettre à jour</button>

    </form>
  </div>
</div>

@endsection

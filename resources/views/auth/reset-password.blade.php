<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="/css/styles.css">
</head>
<body>

<div class="card">
  <div class="card-bg card-bg-1 signin"></div>
  <div class="card-bg card-bg-2 signin"></div>

  <div class="form signin active">
    <form method="POST" action="/reset-password">
      @csrf

      <input type="hidden" name="token" value="{{ $request->route('token') }}">

      <h2>Nouveau mot de passe</h2>

      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Nouveau mot de passe" required>
      <input type="password" name="password_confirmation" placeholder="Confirmer mot de passe" required>

      <button type="submit">MODIFIER</button>

    </form>
  </div>
</div>

</body>
</html>

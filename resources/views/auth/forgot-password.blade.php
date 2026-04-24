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
    <form method="POST" action="/forgot-password">
      @csrf

      <h2>Mot de passe oublié</h2>

      @if(session('status'))
        <p style="color:green">{{ session('status') }}</p>
      @endif

      @if(session('error'))
        <p style="color:red">{{ session('error') }}</p>
      @endif

      <input type="email" name="email" placeholder="Votre email" required>

      <button type="submit">ENVOYER LE LIEN</button>

      <a href="/login">
        Retour à la connexion
      </a>

    </form>
  </div>
</div>

</body>
</html>

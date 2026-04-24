<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="/css/styles.css">
</head>
<body>

<div class="left">
  <div class="left-content">
    <h1>Bienvenue 👋</h1>
    <p>Connectez-vous pour continuer votre expérience</p>
  </div>
</div>

<div class="right">
  <div class="form-box">

    <h2>Connexion</h2>

    @if(session('error'))
      <p style="color:red">{{ session('error') }}</p>
    @endif

    <form method="POST" action="/login">
      @csrf

      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Mot de passe" required>

      <label>
        <input type="checkbox" name="remember"> Se souvenir de moi
      </label>

      <button>Connexion</button>

      <a href="/forgot-password">Mot de passe oublié ?</a>
    </form>

    <!-- GOOGLE -->
    <a href="/auth/google">
      <button class="google-btn">Continuer avec Google</button>
    </a>

    <a href="/register">Créer un compte</a>

  </div>
</div>

</body>
</html>

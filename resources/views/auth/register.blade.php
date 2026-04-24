<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="/css/styles.css">
<script defer src="/js/register.js"></script>
</head>
<body>

<div class="left">
  <div class="left-content">
    <h1>Rejoignez-nous 🚀</h1>
    <p>Créez votre compte et démarrez maintenant</p>
  </div>
</div>

<div class="right">
<div class="form-box">

<h2>Inscription</h2>

<form method="POST" action="/register" enctype="multipart/form-data">
@csrf

<!-- STEP 1 -->
<div id="step1">

<input name="name" placeholder="Nom complet" required>
<input name="email" placeholder="Email" required>
<input name="phone" placeholder="Téléphone" required>

<input type="password" name="password" placeholder="Mot de passe" required>
<input type="password" name="password_confirmation" placeholder="Confirmer" required>

<select name="role" id="role">
  <option value="client">Client</option>
  <option value="vendor">Vendor</option>
  <option value="freelancer">Freelancer</option>
</select>

<button type="button" onclick="nextStep()">Suivant</button>
</div>

<!-- STEP 2 -->
<div id="step2" style="display:none;">

<div id="vendorFields" style="display:none;">
  <input name="store_name" placeholder="Boutique">
</div>

<div id="freelancerFields" style="display:none;">
  <input name="domain" placeholder="Domaine">
</div>

<button type="button" onclick="prevStep()">Retour</button>
<button type="submit">S'inscrire</button>

</div>

</form>

<!-- GOOGLE -->
<a href="/auth/google">
<button class="google-btn">S'inscrire avec Google</button>
</a>

<a href="/login">Déjà un compte ?</a>

</div>
</div>

</body>
</html>

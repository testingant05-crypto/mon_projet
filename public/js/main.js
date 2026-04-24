document.addEventListener("DOMContentLoaded", () => {

  const signinForm = document.querySelector(".form.signin");
  const signupForm = document.querySelector(".form.signup");
  const cardBg1 = document.querySelector(".card-bg-1");
  const cardBg2 = document.querySelector(".card-bg-2");

  // SWITCH SIGNIN / SIGNUP
  window.toggleView = () => {
    const signinActive = signinForm?.classList.contains("active");

    signinForm?.classList.toggle("active", !signinActive);
    signupForm?.classList.toggle("active", signinActive);

    [cardBg1, cardBg2].forEach(el => {
      el.classList.toggle("signin", signinActive);
      el.classList.toggle("signup", !signinActive);
    });
  };

  // STEP MANAGEMENT
  window.nextStep = () => {
    const role = document.getElementById("role").value;

    document.getElementById("step1").style.display = "none";
    document.getElementById("step2").style.display = "block";

    document.getElementById("vendorFields").style.display = "none";
    document.getElementById("freelancerFields").style.display = "none";

    if (role === "vendor") {
      document.getElementById("vendorFields").style.display = "block";
    }

    if (role === "freelancer") {
      document.getElementById("freelancerFields").style.display = "block";
    }
  };

  window.prevStep = () => {
    document.getElementById("step1").style.display = "block";
    document.getElementById("step2").style.display = "none";
  };

});

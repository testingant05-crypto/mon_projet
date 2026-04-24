function nextStep() {
  let role = document.getElementById("role").value;

  if (role === "client") {
    document.querySelector("form").submit();
    return;
  }

  document.getElementById("step1").style.display = "none";
  document.getElementById("step2").style.display = "block";

  if (role === "vendor") {
    document.getElementById("vendorFields").style.display = "block";
  }

  if (role === "freelancer") {
    document.getElementById("freelancerFields").style.display = "block";
  }
}

function prevStep() {
  document.getElementById("step1").style.display = "block";
  document.getElementById("step2").style.display = "none";
}

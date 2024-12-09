function validEmail(email) {
  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailPattern.test(email);
}

function validateLogin() {
  const email = document.getElementById("email").value;
  const password = document.getElementById("password").value;

  if (!email) {
    alert("Email is required.");
    return false;
  }

  if (!password) {
    alert("Password is required.");
    return false;
  }

  if (!validEmail(email)) {
    alert("Please enter a valid email address.");
    return false;
  }

  return true;
}

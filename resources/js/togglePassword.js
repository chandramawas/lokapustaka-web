window.togglePasswordVisibility = function(id) {
  const input = document.getElementById(id);
  const showPassword = document.getElementById(`show-${id}`);
  const hidePassword = document.getElementById(`hide-${id}`);

  if (!input || !showPassword || !hidePassword) return;

  if (input.type === 'password') {
      input.type = 'text';
      showPassword.classList.add('hidden');
      hidePassword.classList.remove('hidden');
  } else {
      input.type = 'password';
      showPassword.classList.remove('hidden');
      hidePassword.classList.add('hidden');
  }
}

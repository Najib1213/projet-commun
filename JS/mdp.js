document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('register-form');
  const email = form.querySelector('input[name="email"]');
  const password = form.querySelector('input[name="password"]');
  const confirm = form.querySelector('input[name="confirm_password"]');

  const emailRegex = /@.+\.isep\.fr$/i;

  const criteriaBox = document.createElement('ul');
  criteriaBox.id = 'password-criteria';
  criteriaBox.className = 'text-sm mt-2 space-y-1';
  criteriaBox.style.display = 'none';

  const createItem = (id, text) => {
    const li = document.createElement('li');
    li.id = id;
    li.className = 'text-red-500 flex items-center gap-2';
    li.innerHTML = `<i class="fas fa-times"></i><span>${text}</span>`;
    return li;
  };

  const criteria = {
    length: createItem('length', '8 caractères minimum'),
    lowercase: createItem('lowercase', '1 minuscule'),
    uppercase: createItem('uppercase', '1 majuscule'),
    number: createItem('number', '1 chiffre'),
    special: createItem('special', '1 caractère spécial')
  };

  Object.values(criteria).forEach(item => criteriaBox.appendChild(item));
  password.parentNode.appendChild(criteriaBox);

  const updateCriteria = (value) => {
    criteria.length.className = value.length >= 8 ? 'text-green-500 flex items-center gap-2' : 'text-red-500 flex items-center gap-2';
    criteria.length.innerHTML = `${value.length >= 8 ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>'} <span>8 caractères minimum</span>`;

    criteria.lowercase.className = /[a-z]/.test(value) ? 'text-green-500 flex items-center gap-2' : 'text-red-500 flex items-center gap-2';
    criteria.lowercase.innerHTML = `${/[a-z]/.test(value) ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>'} <span>1 minuscule</span>`;

    criteria.uppercase.className = /[A-Z]/.test(value) ? 'text-green-500 flex items-center gap-2' : 'text-red-500 flex items-center gap-2';
    criteria.uppercase.innerHTML = `${/[A-Z]/.test(value) ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>'} <span>1 majuscule</span>`;

    criteria.number.className = /\d/.test(value) ? 'text-green-500 flex items-center gap-2' : 'text-red-500 flex items-center gap-2';
    criteria.number.innerHTML = `${/\d/.test(value) ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>'} <span>1 chiffre</span>`;

    criteria.special.className = /[^a-zA-Z0-9]/.test(value) ? 'text-green-500 flex items-center gap-2' : 'text-red-500 flex items-center gap-2';
    criteria.special.innerHTML = `${/[^a-zA-Z0-9]/.test(value) ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>'} <span>1 caractère spécial</span>`;
  };

  password.addEventListener('focus', () => {
    criteriaBox.style.display = 'block';
  });

  password.addEventListener('blur', () => {
    setTimeout(() => criteriaBox.style.display = 'none', 150); // laisse le temps d'afficher si on clique vite
  });

  password.addEventListener('input', () => {
    updateCriteria(password.value);
  });

  form.addEventListener('submit', function (e) {
    let valid = true;

    if (!emailRegex.test(email.value)) {
      alert("L'adresse email doit se terminer par '@xxxxx.isep.fr'.");
      valid = false;
    }

    if (password.value !== confirm.value) {
      alert("Les mots de passe ne correspondent pas.");
      valid = false;
    }

    const failed = Object.values(criteria).some(li => li.classList.contains('text-red-500'));
    if (failed) {
      alert("Le mot de passe ne respecte pas les critères de sécurité.");
      valid = false;
    }

    if (!valid) e.preventDefault();
  });
});

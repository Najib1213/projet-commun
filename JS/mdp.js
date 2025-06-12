document.addEventListener('DOMContentLoaded', () => {
    const passwordInput = document.getElementById('password');
    const criteriaList = document.getElementById('password-criteria');

    const criteria = {
        length: { regex: /.{8,}/, element: document.getElementById('length') },
        lowercase: { regex: /[a-z]/, element: document.getElementById('lowercase') },
        uppercase: { regex: /[A-Z]/, element: document.getElementById('uppercase') },
        number: { regex: /[0-9]/, element: document.getElementById('number') },
        special: { regex: /[^a-zA-Z0-9]/, element: document.getElementById('special') }
    };

    passwordInput.addEventListener('focus', () => {
        criteriaList.classList.remove('hidden');
    });

    passwordInput.addEventListener('blur', () => {
        criteriaList.classList.add('hidden');
    });

    passwordInput.addEventListener('input', () => {
        const value = passwordInput.value;
        for (const key in criteria) {
            const { regex, element } = criteria[key];
            if (regex.test(value)) {
                element.classList.remove('text-red-500');
                element.classList.add('text-green-500');
            } else {
                element.classList.remove('text-green-500');
                element.classList.add('text-red-500');
            }
        }
    });
});

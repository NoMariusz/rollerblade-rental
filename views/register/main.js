const loginForm = document.getElementById('login-form');
const errorMessage = document.getElementById('error-message');

loginForm.addEventListener('submit', async (event) => {
	event.preventDefault();

	const password = document.getElementById('password').value;
	const confirmPassword = document.getElementById('confirm-password').value;

	if (password !== confirmPassword) {
		errorMessage.textContent = 'Passwords do not match!';
		errorMessage.style.display = 'block';
		return;
	}

	const formJson = {};

	for (const input of [
		'first-name',
		'last-name',
		'email',
		'username',
		'password',
	]) {
		formJson[input] = document.getElementById(input).value;
	}

	try {
		const response = await fetch('/register', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
			},
			body: JSON.stringify(formJson),
		});

		const data = await response.json();

		if (response.ok) {
			// Redirect or handle success
			window.location.href = '/dashboard';
		} else {
			// Display error message
			errorMessage.textContent = data.error || 'An error occurred';
			errorMessage.style.display = 'block';
		}
	} catch (error) {
		errorMessage.textContent = 'Network error. Please try again later.';
		errorMessage.style.display = 'block';
	}
});

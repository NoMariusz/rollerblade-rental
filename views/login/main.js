const loginForm = document.getElementById('login-form');
const errorMessage = document.getElementById('error-message');

loginForm.addEventListener('submit', async (event) => {
	event.preventDefault();

	const username = document.getElementById('username').value;
	const password = document.getElementById('password').value;

	try {
		const response = await fetch('/login', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
			},
			body: JSON.stringify({ username, password }),
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

const navigateToRollerblade = (id) => {
	window.location.href = `/rollerblade?id=${id}`;
};

const toggleDescription = () => {
	document.getElementById('description-content').classList.toggle('hidden');
	document.getElementById('description-arrow').classList.toggle('rotate');
};

const form = document.getElementById('rent-form');
const errorMessage = document.getElementById('error-message');

form.addEventListener('submit', async (event) => {
	event.preventDefault();
	const formData = new FormData(form);

	try {
		const response = await fetch('/rent', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
			},
			body: JSON.stringify(Object.fromEntries(formData)),
		});

		if (response.status === 401) {
			window.location.href = '/login';
			return;
		}

		if (response.ok) {
			// Redirect or handle success
			window.location.href = '/my-rentals';
			return;
		}

		const data = await response.json();

		// Display error message
		errorMessage.textContent = data.error || 'An error occurred';
		errorMessage.style.display = 'block';
	} catch (error) {
		console.error('An error occurred:', error);
		errorMessage.textContent = 'Network error. Please try again later.';
		errorMessage.style.display = 'block';
	}
});

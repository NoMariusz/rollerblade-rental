// Fetch data from the /ratings endpoint
async function fetchRatings() {
	try {
		const response = await fetch('/ratings');
		if (!response.ok) {
			throw new Error('Failed to fetch ratings');
		}
		const data = await response.json();
		displayRatings(data);
	} catch (error) {
		console.error('Error:', error);
	}
}

// Display ratings in the review section
function displayRatings(ratings) {
	const container = document.getElementById('review-cards');
	container.innerHTML = ''; // Clear any existing content

	ratings.forEach((rating) => {
		const card = document.createElement('div');
		card.classList.add('review-card', 'card');

		card.innerHTML = `
			<p><strong>"${rating.title}"</strong></p>
			<div class="review-card-bottom">
				<img src="${rating.icon}" alt="${rating.website_name}" />
				<div class="review-card-texts">
					<p>${rating.user_name}</p>
					<p>${rating.website_name}</p>
				</div>
			</div>
        `;

		container.appendChild(card);
	});
}

// Call fetchRatings when the page loads
fetchRatings();

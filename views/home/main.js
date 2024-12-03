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
		card.className = 'review-card';

		card.innerHTML = `
            <img src="${rating.icon}" alt="${rating.website_name}" />
            <p><strong>${rating.title}</strong></p>
            <p>${rating.user_name}</p>
            <p>${rating.website_name}</p>
        `;

		container.appendChild(card);
	});
}

// Call fetchRatings when the page loads
fetchRatings();

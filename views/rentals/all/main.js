// Function to change rental status
async function changeRentalStatus(rentalId, statusId) {
	try {
		const response = await fetch('/rental/status', {
			method: 'PATCH',
			headers: {
				'Content-Type': 'application/json',
			},
			body: JSON.stringify({
				rentalId: rentalId,
				statusId: statusId,
			}),
		});

		if (response.ok) {
			alert('Rental status updated successfully!');
			window.location.reload();
		} else {
			const errorData = await response.json();
			alert('Error: ' + errorData.message);
		}
	} catch (error) {
		alert('An unexpected error occurred: ' + error.message);
	}
}

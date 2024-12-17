// Function to change rental status
async function changeRentalStatus(rentalId, newStatus) {
	try {
		const response = await fetch('/change-rental-status', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
			},
			body: JSON.stringify({
				id: rentalId,
				status: newStatus,
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

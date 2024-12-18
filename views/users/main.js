// Function to change user role
function changeUserRole(userId, newRoleId, newRole) {
	if (
		!confirm(
			`Are you sure you want to change this user's role to ${newRole}?`
		)
	)
		return;

	fetch(`/user/role?userId=${userId}&roleId=${newRoleId}`, {
		method: 'PATCH',
		headers: {
			'Content-Type': 'application/json',
		},
	})
		.then((response) => response.json())
		.then((data) => {
			if (data.message) {
				window.location.reload();
			} else if (data.error) {
				alert('Error: ' + data.error);
			}
		})
		.catch((error) => {
			console.error('Error:', error);
			alert('An unexpected error occurred.');
		});
}

// Function to delete user
function deleteUser(userId) {
	if (
		!confirm(
			'Are you sure you want to delete this user? This action cannot be undone.'
		)
	)
		return;

	fetch(`/user?id=${userId}`, {
		method: 'DELETE',
		headers: {
			'Content-Type': 'application/json',
		},
	})
		.then((response) => response.json())
		.then((data) => {
			if (data.message) {
				alert('User deleted successfully!');
				window.location.reload();
			} else if (data.error) {
				alert('Error: ' + data.error);
			}
		})
		.catch((error) => {
			console.error('Error:', error);
			alert('An unexpected error occurred.');
		});
}

function changePassword(userId) {
	const newPassword = prompt('Enter a new password for the user:');

	if (!newPassword) {
		alert('Password change canceled.');
		return;
	}

	// Send the request to change the password
	fetch('/user/password', {
		method: 'PATCH',
		headers: {
			'Content-Type': 'application/json',
		},
		body: JSON.stringify({
			userId: userId,
			newPassword: newPassword,
		}),
	})
		.then((response) => {
			if (response.ok) {
				alert('Password changed successfully.');
			} else {
				return response.json().then((errorData) => {
					alert(`Error: ${errorData.error}`);
				});
			}
		})
		.catch((error) => {
			alert(`An unexpected error occurred: ${error.message}`);
		});
}

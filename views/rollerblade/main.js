const navigateToRollerblade = (id) => {
	window.location.href = `/rollerblade?id=${id}`;
};

const toggleDescription = () => {
	document.getElementById('description-content').classList.toggle('hidden');
	document.getElementById('description-arrow').classList.toggle('rotate');
};

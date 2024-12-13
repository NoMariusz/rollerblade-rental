// price sliders

document.addEventListener('DOMContentLoaded', function () {
	const priceMin = document.getElementById('priceMin');
	const priceMax = document.getElementById('priceMax');
	const minPriceValue = document.getElementById('minPriceValue');
	const maxPriceValue = document.getElementById('maxPriceValue');

	function updateLabels() {
		minPriceValue.textContent = priceMin.value;
		maxPriceValue.textContent = priceMax.value;
	}

	priceMin.addEventListener('input', function () {
		if (parseInt(priceMin.value) > parseInt(priceMax.value)) {
			priceMin.value = priceMax.value;
		}
		updateLabels();
	});

	priceMax.addEventListener('input', function () {
		if (parseInt(priceMax.value) < parseInt(priceMin.value)) {
			priceMax.value = priceMin.value;
		}
		updateLabels();
	});

	// Initialize labels
	updateLabels();
});

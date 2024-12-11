<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Contact Us</title>
	<link rel="stylesheet" href="/views/contact/styles.css" />
	<link rel="stylesheet" href="/views/shared/index.css" />
</head>

<body>
	<?php include_once './views/shared/header.php'; ?>

	<main>
		<h1>Contact Us</h1>
		<p>
			Feel free to get in touch with us using the details provided
			below.
		</p>

		<div class="contact-container">
			<div class="info-container">
				<h2>Our Contact Information</h2>
				<ul>
					<li>
						📞 Phone:
						<a href="tel:+48123123123">+48 123-123-123</a>
					</li>
					<li>📍 Address: Cracow, Main Square</li>
					<li>
						📧 Email:
						<a href="mailto:random-email@rr.com">random-email@rr.com</a>
					</li>
				</ul>
			</div>
			<div class="map-container">
				<iframe
					src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2564.796288233143!2d19.93598431571604!3d50.06194737942339!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47165b0cdd59b8af%3A0x40dbfef8d7c2410!2sRynek%20G%C5%82%C3%B3wny%2C%20Krak%C3%B3w%2C%20Poland!5e0!3m2!1sen!2sus!4v1694780801708!5m2!1sen!2sus"
					allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
				</iframe>
			</div>
		</div>
	</main>

	<footer>
		<p>© 2024 Rollerblade Rental. All rights reserved.</p>
	</footer>
</body>

</html>
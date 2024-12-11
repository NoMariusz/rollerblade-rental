<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Rollerblade Rental</title>
	<link rel="stylesheet" href="/views/home/styles.css" />
	<link rel="stylesheet" href="/views/shared/index.css" />
</head>

<body>
	<?php include_once './views/shared/header.php'; ?>

	<section class="hero">
		<h1>Rollerblade Rental</h1>
		<p>Place to rent roller blades for you!</p>
		<div class="cta-buttons">
			<button class="btn btn-secondary">
				<a href="/login">Sign in</a>
			</button>
			<p>or</p>
			<button class="btn btn-primary">
				<a href="/rollerskates">Rent!</a>
			</button>
		</div>
	</section>

	<section class="skater-image">
		<img src="/views/home/skater.jpg" alt="Skater" />
	</section>

	<section class="reviews">
		<div class="texts">
			<h2>Our reviews</h2>
			<p>We look forward to hearing from you!</p>
		</div>
		<div class="review-cards" id="review-cards"></div>
	</section>

	<footer>
		<div class="footer-icons">
			<img src="/assets/rr_logo.png" alt="Rollerblade rental" />
			<div class="footer-socials">
				<a href="#"><img src="/assets/x_logo.png" alt="Twitter" /></a>
				<a href="#"><img src="/assets/instagram_logo.png" alt="Instagram" /></a>
				<a href="#"><img src="/assets/youtube_logo.png" alt="Youtube" /></a>
				<a href="#"><img src="/assets/inkedin_logo.png" alt="Inkedin" /></a>
			</div>
		</div>
		<div class="footer-texts">
			<h2>Explore</h2>
			<div class="footer-page-links">
				<a href="#">Rent your roller blades</a>
				<a href="#">Our roller blades</a>
				<a href="#">Contact</a>
			</div>
		</div>
	</footer>

	<script src="/views/home//main.js"></script>
</body>

</html>
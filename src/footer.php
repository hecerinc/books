	</div>
</main>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>
	$(function() {
		// Check local storage for menu position
		/* let navstatus = localStorage.getItem('navStatus'); */

		/* // Default status is collapsed */
		/* if(navstatus === 'expanded') { */
		/* 	$('.main-sidebar, .main-content').css('transition', 'none'); */
		/* 	setTimeout(toggleNavMenu, 100); */
		/* 	setTimeout(_ => { */
		/* 		$('.main-sidebar, .main-content').css('transition', ''); */
		/* 	}, 200); */
		/* } */
		/* else if(navstatus == null) { */
		/* 	localStorage.setItem('navStatus', 'collapsed'); */
		/* } */
		$('.menu-toggle').click(function(e) {
			e.preventDefault();
			toggleNavMenu();
			const isExpanded = !$('.main-sidebar').hasClass('collapsed');
			savePreferences(isExpanded);
			/* let ns = localStorage.getItem('navStatus'); */
			/* ns = ns === 'collapsed' ? 'expanded' : 'collapsed'; */
			/* localStorage.setItem('navStatus', ns); */
		});
		function toggleNavMenu() {
			$('.main-sidebar .side-menu').toggleClass('collapsed');
			/* setTimeout(function() { */
				$('.main-sidebar').toggleClass('collapsed');
				$('.main-content').toggleClass('fw');
			/* }, 200); */
		}

		async function savePreferences(isExpanded) {
			try {
				const res = await fetch('/session.php', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json'
					},
					referrerPolicy: 'no-referrer',
					body: JSON.stringify({isExpanded})
				});
				const response = await res.json();
				if(response.msg && response.msg === 'success') {
					console.log('Saved preferences successfully');
				}
				else {
					console.error('Something went wrong');
				}
			}
			catch (err) {
				console.error('Could not save session preferences');
				console.error(err);
			}
		}
	});
</script>
<?= $vblock->get('scripts'); ?>
</body>
</html>

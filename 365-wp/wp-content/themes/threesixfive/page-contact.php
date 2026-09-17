<?php
/**
 * Template Name: Contact Page
 * Real working contact form (First/Last name, Email, Subject, Message)
 * sent via wp_mail() to the site's contact address — matches the fields
 * on the original site's Contact Us page.
 *
 * NOTE: wp_mail() uses PHP's built-in mail() by default, which often
 * cannot deliver reliably from a container host like Railway (no local
 * MTA, easy to land in spam, sometimes silently dropped). Install an
 * SMTP plugin (WP Mail SMTP is the common one) and point it at a real
 * provider (Gmail, SES, Postmark, etc.) before relying on this in
 * production — see the README.
 */
get_header();
$info = threesixfive_company_info();

$submitted = false;
$errors    = array();

if ( isset( $_POST['threesixfive_contact_nonce'] ) && wp_verify_nonce( $_POST['threesixfive_contact_nonce'], 'threesixfive_contact_submit' ) ) {

	$first_name = isset( $_POST['first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) : '';
	$last_name  = isset( $_POST['last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) : '';
	$from_email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$subject    = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : '';
	$message    = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	if ( '' === $first_name || '' === $last_name ) {
		$errors[] = 'Please enter your first and last name.';
	}
	if ( '' === $from_email || ! is_email( $from_email ) ) {
		$errors[] = 'Please enter a valid email address.';
	}
	if ( '' === $message ) {
		$errors[] = 'Please enter a message.';
	}

	if ( ! $errors ) {
		$to      = $info['email'];
		$subject_line = sprintf( '[365 Technologies Contact] %s', $subject ? $subject : 'New inquiry' );
		$body    = "New contact form submission:\n\n"
			. "Name: {$first_name} {$last_name}\n"
			. "Email: {$from_email}\n"
			. "Subject: {$subject}\n\n"
			. "Message:\n{$message}\n";
		$headers = array( 'Reply-To: ' . $first_name . ' ' . $last_name . ' <' . $from_email . '>' );

		$sent = wp_mail( $to, $subject_line, $body, $headers );

		if ( $sent ) {
			$submitted = true;
		} else {
			$errors[] = 'Sorry — something went wrong sending your message. Please email us directly at ' . $info['email'] . '.';
		}
	}
}

while ( have_posts() ) :
	the_post();
	?>
	<section class="page-header">
		<div class="container">
			<div class="breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> / <?php the_title(); ?></div>
			<h1><?php the_title(); ?></h1>
		</div>
	</section>

	<section class="page-content">
		<div class="container">
			<div class="contact-grid">
				<div class="entry-content">
					<?php if ( $submitted ) : ?>
						<div class="placeholder-block" style="text-align:left;border-style:solid;border-color:#2f7d4f;background:#eef7f0;">
							<span class="eyebrow" style="color:#2f7d4f;">Message sent</span>
							<p style="color:#1c2a35;">Thanks — your message has been sent. We'll get back to you shortly.</p>
						</div>
					<?php else : ?>
						<?php if ( $errors ) : ?>
							<div class="placeholder-block" style="text-align:left;border-style:solid;border-color:#c2651b;background:#fbf1e8;">
								<span class="eyebrow">Please fix the following</span>
								<ul style="margin:0;padding-left:18px;color:#1c2a35;">
									<?php foreach ( $errors as $error ) : ?>
										<li><?php echo esc_html( $error ); ?></li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php endif; ?>

						<form method="post" class="contact-form" style="margin-top:24px;">
							<?php wp_nonce_field( 'threesixfive_contact_submit', 'threesixfive_contact_nonce' ); ?>

							<div style="margin-bottom:18px;">
								<label for="first_name" style="display:block;font-weight:700;font-size:0.85rem;margin-bottom:6px;">First name</label>
								<input type="text" id="first_name" name="first_name" placeholder="Enter your first name" value="<?php echo isset( $_POST['first_name'] ) ? esc_attr( wp_unslash( $_POST['first_name'] ) ) : ''; ?>" style="width:100%;padding:10px 12px;border:1px solid var(--color-border);border-radius:4px;">
							</div>

							<div style="margin-bottom:18px;">
								<label for="last_name" style="display:block;font-weight:700;font-size:0.85rem;margin-bottom:6px;">Last name</label>
								<input type="text" id="last_name" name="last_name" placeholder="Enter your last name" value="<?php echo isset( $_POST['last_name'] ) ? esc_attr( wp_unslash( $_POST['last_name'] ) ) : ''; ?>" style="width:100%;padding:10px 12px;border:1px solid var(--color-border);border-radius:4px;">
							</div>

							<div style="margin-bottom:18px;">
								<label for="email" style="display:block;font-weight:700;font-size:0.85rem;margin-bottom:6px;">Email</label>
								<input type="email" id="email" name="email" placeholder="example@gmail.com" value="<?php echo isset( $_POST['email'] ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" style="width:100%;padding:10px 12px;border:1px solid var(--color-border);border-radius:4px;">
							</div>

							<div style="margin-bottom:18px;">
								<label for="subject" style="display:block;font-weight:700;font-size:0.85rem;margin-bottom:6px;">Subject</label>
								<input type="text" id="subject" name="subject" placeholder="Subject" value="<?php echo isset( $_POST['subject'] ) ? esc_attr( wp_unslash( $_POST['subject'] ) ) : ''; ?>" style="width:100%;padding:10px 12px;border:1px solid var(--color-border);border-radius:4px;">
							</div>

							<div style="margin-bottom:22px;">
								<label for="message" style="display:block;font-weight:700;font-size:0.85rem;margin-bottom:6px;">Message</label>
								<textarea id="message" name="message" rows="6" placeholder="Type here" style="width:100%;padding:10px 12px;border:1px solid var(--color-border);border-radius:4px;font-family:inherit;"><?php echo isset( $_POST['message'] ) ? esc_textarea( wp_unslash( $_POST['message'] ) ) : ''; ?></textarea>
							</div>

							<button type="submit" class="btn">Send Message</button>
						</form>
					<?php endif; ?>
				</div>

				<div class="contact-card">
					<dl>
						<dt>Address</dt>
						<dd><?php echo esc_html( $info['address1'] ); ?><br><?php echo esc_html( $info['address2'] ); ?></dd>

						<dt>Phone</dt>
						<dd><a href="tel:<?php echo esc_attr( $info['phone_href'] ); ?>"><?php echo esc_html( $info['phone'] ); ?></a></dd>

						<dt>Email</dt>
						<dd><a href="mailto:<?php echo esc_attr( $info['email'] ); ?>"><?php echo esc_html( $info['email'] ); ?></a></dd>
					</dl>
				</div>
			</div>
		</div>
	</section>
	<?php
endwhile;

get_footer();

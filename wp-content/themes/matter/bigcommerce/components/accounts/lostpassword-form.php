<?php

/**
 * Template for the lost password form
 *
 * @var string $form_action
 * @var string $login_url
 * @var string $register_url
 * @var string $redirect_to
 * @var string $message
 */

global $no_footer;
$no_footer = true;

?>

<div class="bc-account-login-page">
	<section class="bc-account-login bc-account-lost-password">
		<div class="visual"></div>
		<div class="bc-account-login__form">
			<div class="bc-account-login__form-inner">
				<h3>Reset Password</h3>
				<p><?php esc_html_e( 'Fill in your email address below to request a password. An email will be sent containing a link to verify your email address.', 'bigcommerce' ); ?></p>
				<?php echo $message; ?>
				<form class="bc-form bc-account-form--lost-password" action="<?php echo esc_url( $form_action ); ?>" method="post">
					<p class="label-placeholder">
						<input type="email" name="user_login" id="bc-account-user-email" value="">
						<label><?php echo esc_html( __( 'Email', 'bigcommerce' ) ); ?></label>
					</p>
					<?php do_action( 'lostpassword_form' ); ?>
					<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to ); ?>?msg=retrieve"/>
					<div class="bc-account-lost-password__actions">
						<button class="btn primary" aria-label="<?php __( 'Reset Password', 'bigcommerce' ); ?>"
										type="submit" name="wp-submit"><?php echo esc_html( __( 'Reset Password', 'bigcommerce' ) ); ?></button>
					</div>
				</form>
				<ul class="bc-account-lost-password__account-actions">
					<li class="bc-account-lost-password__account-link">
						<a href="<?php echo esc_url( $login_url ); ?>"><?php esc_html_e( 'Log in', 'bigcommerce' ) ?></a>
					</li>

					<?php if ( $register_url ) { ?>
					<li class="bc-account-lost-password__account-link">
						<a href="<?php echo esc_url( $register_url ); ?>"><?php esc_html_e( 'Register', 'bigcommerce' ); ?></a>
					</li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</section>
</div>

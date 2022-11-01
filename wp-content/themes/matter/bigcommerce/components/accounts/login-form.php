<?php
/**
 * @var string $form
 * @var string $register_link
 * @var string $message
 */

global $no_footer;
$no_footer = true;

if ($message == '') {
	switch ($_REQUEST['msg']) {
		case 'retrieve':
			$message = '<div class="bc-alert bc-alert--success">Please check your email for further instructions to reset your password.</div>';
			break;

		default:
			$message = '';
			break;
	}
}

?>

<div class="bc-account-login-page">
	<section class="bc-account-login">
		<div class="visual"></div>
		<div class="bc-account-login__form">
			<div class="bc-account-login__form-inner">
				<?php echo $message; ?>
				<h3>Log In</h3>
				<form name="loginform" id="loginform" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post">
					<p class="login-username label-placeholder">
						<input type="text" name="log" id="user_login" class="input placeholder-control" value="" size="20" autocomplete="off">
						<label for="user_login">Email Address</label>
					</p>
					<p class="login-password label-placeholder">
						<input type="password" name="pwd" id="user_pass" class="input placeholder-control" value="" size="20" autocomplete="off">
						<label for="user_pass">Password</label>
					</p>
					<div class="flex">
						<div class="faux-checkbox">
							<input name="rememberme" type="checkbox" id="rememberme" value="forever" checked="checked">
							<span></span>
							<label for="rememberme">Remember Me</label>
						</div>
						<a href="<?php echo esc_url( wp_lostpassword_url( get_permalink() ) ); ?>"
							title="<?php echo esc_attr( 'Forgot Password', 'bigcommerce' ); ?>">
							<?php esc_html_e( 'Forgot your password?', 'bigcommerce' ); ?>
						</a>
					</div>
					
					<button type="submit" name="wp-submit" class="btn primary">Login</button>
					<input type="hidden" name="redirect_to" value="<?php echo isset( $_GET[ 'redirect_to' ] ) ? wp_sanitize_redirect( $_GET[ 'redirect_to' ] ) : home_url( '/' ); ?>">
					
				</form>
				<?php if ( $register_link ) { ?>
					<p class="text-center rego-line">
					Don't have an account? <a href="<?php echo esc_url( $register_link ); ?>"
						 title="<?php esc_attr( 'Sign up', 'bigcommerce' ); ?>"><?php esc_html_e( 'Sign up', 'bigcommerce' ); ?></a>
					</p>
				<?php } ?>
			</div>
		</div>
	</section>
</div>
<?php
/**
 * Template for displaying search forms in matter
 *
 * @package WordPress
 * @subpackage matter
 * @since matter 1.0
 */
?>
	<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label>
		  <input class="search-field" type="text" class="field" name="s" id="keyword" onkeyup="fetch()" placeholder="<?php esc_attr_e( 'Type something here...', 'matter' ); ?>" />
    </label>
		<div id="datafetch" style="display: flex; flex-flow: nowrap;"> 
    </div>
		<input class="search-submit" type="submit" class="submit" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'SEE MORE', 'matter' ); ?>" />
	</form>

	
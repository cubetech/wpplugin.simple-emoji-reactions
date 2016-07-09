<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://www.cubetech.ch
 * @since      1.0.0
 *
 * @package    Simple_Emoji_Reactions
 * @subpackage Simple_Emoji_Reactions/public/partials
 */
?>

<div class="simple-emoji-reactions-container">
	<?php
		$max = 0;
		$total = 0;
		$name = '';
		foreach( $votes as $key => $v ):
			if( $v > $max ):
				$max = $v;
				$name = $key;
			endif;
			
			$total = $total + $v;
		endforeach;
	?>
	<div class="simple-emoji-reactions-text">
		<?php
			if( $total > 0 ):
				echo floor( ( $max / $total ) * 100 )  . __( '% feel', 'simple-emoji-reactions' ) . ' ' . __( str_replace( ':', '', $name ), 'simple-emoji-reactions' );
			else:
				echo __( 'Nobody had feels yet', 'simple-emoji-reactions' );
			endif;
		?>. <?php echo __( 'And how do you feel?', 'simple-emoji-reactions' ); ?></div>
	<div class="simple-emoji-reactions-list">
		<?php foreach( $emojis as $key => $e ): ?>
			<div class="simple-emoji-reactions-entry">
				<div class="simple-emoji-reactions-inline">
					<span class="simple-emoji-reactions-count"><?php echo $votes[$key]; ?></span>
					<span class="simple-emoji-reactions-image"><img data-emoji="<?php echo str_replace( ':', '', $key ); ?>" class="simple-emoji-reactions-img" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . '../vendor/emojione/assets/svg/' . $e . '.svg'; ?>" alt="<?php echo $key; ?>"></span>
					<span class="simple-emoji-reactions-name"><?php echo ucfirst( __( str_replace( ':', '', $key ), 'simple-emoji-reactions' ) ); ?></span>
				</div>
			</div>
		<?php endforeach; ?>
		<div class="simple-emoji-reactions-clearfix"></div>
	</div>
</div>

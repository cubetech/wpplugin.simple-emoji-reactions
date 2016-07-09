<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.cubetech.ch
 * @since      1.0.0
 *
 * @package    Simple_Emoji_Reactions
 * @subpackage Simple_Emoji_Reactions/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Simple_Emoji_Reactions
 * @subpackage Simple_Emoji_Reactions/public
 * @author     cubetech GmbH <info@cubetech.ch>
 */
class Simple_Emoji_Reactions_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Activated emojis.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $emojis    Array with emojis name.
	 */
	protected $emojis;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->emojis = array(
			':thumbsup:',
			':heart:',
			':joy:',
			':heart_eyes:',
			':blush:',
			':cry:',
			':rage:',
		);

		$this->ajax_register_action();

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Simple_Emoji_Reactions_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Simple_Emoji_Reactions_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/simple-emoji-reactions-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Simple_Emoji_Reactions_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Simple_Emoji_Reactions_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		global $post;

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/simple-emoji-reactions-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'Ajax', array( 'postid' => $post->ID, 'url' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce( $this->plugin_name . '_nonce' ) ) );

	}

	/**
	 * Returns an array with emoji paths.
	 *
	 * @since   	1.0.0
	 * @param      	array    	$emojis       	Emojis to get.
     * @return 		array						Associative array with emoji file codes
	 */
	public function get_emojis( $emojis = array() ) {

		if( empty( $emojis ) )
			$emojis = $this->emojis;

		$return_emojis = array();

		// Include the Emojione thing
		$ruleset = new \Emojione\Ruleset;
		$shortcode_replace = $ruleset->getShortcodeReplace();

		foreach($emojis as $e):
			$return_emojis[$e] = @$shortcode_replace[$e];
		endforeach;

		return $return_emojis;

	}

	/**
	 * Checks if emoji is valid.
	 *
	 * @since    	1.0.0
	 * @param      	array    	$emoji       	Emoji to check.
     * @return 		bool
	 */
	public function check_emoji( $emoji ) {

		$return = $this->get_emojis( array( ':' . $emoji . ':' ) );

		if( $return[':' . $emoji . ':'] === NULL )
			return false;
		else
			return true;

	}

	/**
	 * Add the public view below posts.
	 *
	 * @since    1.0.0
	 */
	public function add_public_post_view( $content ) {

		global $emojis, $votes, $post;
		$template_content 	= '';
		$emojis 			= $this->get_emojis();
		$votes				= array();

		foreach( $emojis as $key => $e ):
			$votes[$key] = $this->get_vote( $post->ID, str_replace( ':', '', $key ) );
		endforeach;

		// Check if it's single view
		if( is_single() ):
			ob_start();
			$template = $this->locate_template('simple-emoji-reactions-public-display.php', false);
			include_once($template);
			$template_content = ob_get_clean();
		endif;

		return $content . $template_content;

	}

    /**
     * Ajax register action
     *
     * @since	1.0.0
     */
    public function ajax_register_action() {
        add_action( 'wp_ajax_upvote', array( $this, 'upvote' ) );
        add_action( 'wp_ajax_nopriv_upvote', array( $this, 'upvote' ) );
    }

    /**
     * Get votes
     *
     * @since	1.0.0
     */
    public function get_vote( $post_id, $key ) {

		if( !$post_id || !$key ):
			return false;
		endif;

		$likes = get_post_meta( $post_id, $this->plugin_name . 'likes', true );

		if( empty( $likes ) ):
			add_post_meta( $post_id, $this->plugin_name . 'likes', array( $key => 0 ), true);
			return '0';
		endif;

		if( !isset( $likes[$key] ) ):
			$likes[$key] = 0;
			update_post_meta( $post_id, $this->plugin_name . 'likes', $likes );
			return $likes[$key];
		endif;

		return $likes[$key];

    }

    /**
     * Upvote function for ajax action
     *
     * @since	1.0.0
     */
	public function upvote() {

		check_ajax_referer( $this->plugin_name . '_nonce', 'nonce' );
		$post_id = $_POST['postID'];
		$emoji = $_POST['emoji'];

		if( ! $this->check_emoji( $emoji ) ):
			echo 'Invalid emoji'; exit;
		endif;

		if( !$post_id ):
			echo 'No post'; exit;
		endif;

		add_post_meta( $post_id, $this->plugin_name . 'likes', array(), true);

		$likes = get_post_meta($post_id, $this->plugin_name . 'likes', true);
		if( !isset($likes[$emoji]) || $likes[$emoji] < 1 ):
			$likes[$emoji] = 0;
		endif;

		$likes[$emoji] = $likes[$emoji] + 1;

		update_post_meta( $post_id, $this->plugin_name . 'likes', $likes );

		echo $likes[$emoji];

		exit;

	}

    /**
     * Retrieve the name of the highest priority template file that exists.
     *
     * Searches in the STYLESHEETPATH before TEMPLATEPATH so that themes which
     * inherit from a parent theme can just overload one file. If the template is
     * not found in either of those, it looks in the theme-compat folder last.
     *
     * Taken from bbPress
     *
     * @since 	1.0.0
     *
     * @param string|array $template_names Template file(s) to search for, in order.
     * @param bool $load If true the template file will be loaded if it is found.
     * @param bool $require_once Whether to require_once or require. Default true.
     *                            Has no effect if $load is false.
     * @return string The template filename if one is located.
     */
    public static function locate_template($template_names, $load = false, $require_once = true) {

        // No file found yet
        $located = false;

        // Try to find a template file
        foreach ((array) $template_names as $template_name) {

            // Continue if template is empty
            if (empty($template_name))
                continue;

            // Trim off any slashes from the template name
            $template_name = ltrim($template_name, '/');

            // Check child theme first
            if (file_exists(trailingslashit(get_stylesheet_directory()) . 'partials/' . $template_name)) {
                $located = trailingslashit(get_stylesheet_directory()) . 'partials/' . $template_name;
                break;

            // Check parent theme next
            } elseif (file_exists(trailingslashit(get_template_directory()) . 'partials/' . $template_name)) {
                $located = trailingslashit(get_template_directory()) . 'partials/' . $template_name;
                break;

            // Check theme compatibility last
            } elseif (file_exists(trailingslashit(Simple_Emoji_Reactions_Public::get_templates_dir()) . $template_name)) {
                $located = trailingslashit(Simple_Emoji_Reactions_Public::get_templates_dir()) . $template_name;
                break;
            }
        }

		// Load if option passed
        if ( ( $load === true ) && !empty( $located ) )
            load_template($located, $require_once);

		// Return
        return $located;

    }

    /**
     * Retrieve the template partials dir.
     *
     * @since	1.0.0
     *
     * @return string The directory or false if it doesn't exists.
     */
    public static function get_templates_dir() {

		// Get path from actual file
		$partsdir 	= '../public/partials';
        $path 		= plugin_dir_path( __FILE__ );

		// Return the dir or false if it doesn't exists.
		if( file_exists( $path . $partsdir ) ):
        	return $path . $partsdir;
        else:
			return false;
		endif;

    }

}

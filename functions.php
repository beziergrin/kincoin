<?php
/**
 * MarketerPro functions and definitions
 *
 * @package MarketerPro
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

if ( ! function_exists( 'marketerpro_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function marketerpro_setup() {

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on MarketerPro, use a find and replace
	 * to change 'marketerpro' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'marketerpro', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails on posts and pages
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	//add_theme_support( 'post-thumbnails' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'marketerpro' ),
	) );

	/**
	 * Enable support for Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

	/**
	 * Setup the WordPress core custom background feature.
	 */
	add_theme_support( 'custom-background', apply_filters( 'marketerpro_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // marketerpro_setup
add_action( 'after_setup_theme', 'marketerpro_setup' );

/**
 * Register widgetized area and update sidebar with default widgets
 */
function marketerpro_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'marketerpro' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'marketerpro_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function marketerpro_scripts() {
	wp_enqueue_style( 'marketerpro-style', get_stylesheet_uri() );

	wp_enqueue_script( 'marketerpro-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'marketerpro-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'marketerpro-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}
}
add_action( 'wp_enqueue_scripts', 'marketerpro_scripts' );

/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';


// Theme Setting Tutorial


function setup_theme_admin_menus() {

    add_menu_page('Theme settings', 'Example theme', 'manage_options',
        'tut_theme_settings', 'theme_settings_page');

    add_submenu_page('tut_theme_settings',
        'Front Page Elements', 'Front Page', 'manage_options',
        'front-page-elements', 'theme_front_page_settings');

    add_submenu_page('themes.php',
    'Front Page Elements', 'Front Page', 'manage_options',
    'front-page-elements', 'theme_front_page_settings');

}

// This tells WordPress to call the function named "setup_theme_admin_menus"
// when it's time to create the menu pages.
add_action("admin_menu", "setup_theme_admin_menus");

function theme_front_page_settings() {

    // Check that the user is allowed to update options
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }

    ?>
    <div class="wrap">


        <script type="text/javascript">
            var elementCounter = 0;
            jQuery(document).ready(function() {
                jQuery("#add-featured-post").click(function() {
                    var elementRow = jQuery("#front-page-element-placeholder").clone();
                    var newId = "front-page-element-" + elementCounter;

                    elementRow.attr("id", newId);
                    elementRow.show();

                    var inputField = jQuery("select", elementRow);
                    inputField.attr("name", "element-page-id-" + elementCounter);

                    var labelField = jQuery("label", elementRow);
                    labelField.attr("for", "element-page-id-" + elementCounter);

                    elementCounter++;
                    jQuery("input[name=element-max-id]").val(elementCounter);

                    jQuery("#featured-posts-list").append(elementRow);

                    return false;
                });
            });
        </script>


        <?php screen_icon('themes'); ?> <h2>Front page elements</h2>

        <form method="POST" action="">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">
                        <label for="num_elements">
                            Number of elements on a row:
                        </label>
                    </th>
                    <td>
                        <input type="text" name="num_elements" size="25" />
                    </td>
                </tr>
            </table>

            <h3>Featured posts</h3>

            <ul id="featured-posts-list">
            </ul>

            <input type="hidden" name="element-max-id" />

            <a href="#" id="add-featured-post">Add featured post</a>
        </form>

        <li class="front-page-element" id="front-page-element-placeholder" style="display:none;">
            <label for="element-page-id">Featured post:</label>
            <select name="element-page-id">
                <?php foreach ($posts as $post) : ?>
                    <option value="<?php echo $post->ID; ?>">
                        <?php echo $post->post_title; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <a href="#">Remove</a>
        </li>

    </div>
<?php
}





<?php


/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
	});

	add_filter('template_include', function( $template ) {
		return get_stylesheet_directory() . '/static/no-timber.html';
	});

	return;
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array( 'templates/pages', 'templates/components' );

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;


/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class StarterSite extends Timber\Site {
	/** Add timber support. */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
		add_filter( 'timber/context', array( $this, 'add_to_context' ) );
		add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		parent::__construct();
	}
	/** This is where you can register custom post types. */
	public function register_post_types() {

	}
	/** This is where you can register custom taxonomies. */
	public function register_taxonomies() {

	}

	/** This is where you add some context
	 *
	 * @param string $context context['this'] Being the Twig's {{ this }}.
	 */
	public function add_to_context( $context ) {
		$logo = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ) , 'full' );
		  $context['logo']['url']    = $logo[0];
		  $context['logo']['width']  = $logo[1];
		  $context['logo']['height'] = $logo[2];
		  $context['menu'] = new Timber\Menu( 'Main navigation' );
			$context['site'] = $this;
			$context['footer_widgets'] = Timber::get_widgets('footer_widgets');
			// $context['resource_sidebar'] = Timber::get_widgets('resource_sidebar');
		return $context;
	}

	public function theme_supports() {
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		
		add_image_size( 'team-portrait', 200, 200, true );
		add_image_size( 'slim-hero', 1200, 400, true );		

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5', array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		add_theme_support( 'menus' );
		add_theme_support( 'custom-logo' );
	}

	/** This is where you can add your own functions to twig.
	 *
	 * @param string $twig get extension.
	 */
	public function add_to_twig( $twig ) {
		$twig->addExtension( new Twig_Extension_StringLoader() );
		$twig->addFilter( new Twig_SimpleFilter( 'myfoo', array( $this, 'myfoo' ) ) );
		return $twig;
	}

}

new StarterSite();
/**
 * END TWIG STUFF
 */



/**
 * enqueue scripts, fonts and styles.
 */
function wpdocs_crisp_scripts() {
    wp_enqueue_style( 'uikit', get_template_directory_uri().'/dist/css/style.css' );
	  wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Montserrat:300,500' );
		wp_enqueue_script( 'uikit-js', get_template_directory_uri() . '/dist/js/bundle.js', array(), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'wpdocs_crisp_scripts' );



// setup footer
if ( function_exists('register_sidebar') )
  register_sidebar(
		array(
			'name' => 'Footer Widgets',
			'id' => 'footer_widgets',    // ID should be LOWERCASE  ! ! !
			'before_widget' => '<div><div class="widget">',
			'after_widget' => '</div></div>',
			'before_title' => '<h3>',
			'after_title' => '</h3>',
		)
	);
  register_sidebar(
		array(
			'name' => 'Resource Sidebar',
			'id' => 'resource_sidebar',    // ID should be LOWERCASE  ! ! !
			'before_widget' => '<div class ="widget">',
			'after_widget' => '</div>',
			'before_title' => '<h3>',
			'after_title' => '</h3>',
		)
	);
  register_sidebar(
		array(
			'name' => 'Proposal Sidebar',
			'id' => 'proposal_sidebar',    // ID should be LOWERCASE  ! ! !
			'before_widget' => '<div class ="widget uk-margin-large-top">',
			'after_widget' => '</div>',
			'before_title' => '<div class="uk-background-secondary uk-padding-small uk-light"><h3>',
			'after_title' => '</h3></div>',
		)
	);
$data['footer_widgets'] = Timber::get_widgets( 'footer_widgets' );

/**
 * add Options page via acf. can add multiple named
 * 
 */
if( function_exists('acf_add_options_page') ) {
	acf_add_options_page();
	acf_add_options_sub_page('Glossary');
	acf_add_options_sub_page('Members');
}

/* functions.php */
add_filter( 'timber_context', 'mytheme_timber_context'  );
function mytheme_timber_context( $context ) {
    $context['options_members'] = get_fields('options');
    return $context;
}

function add_gf_cap()
{
    $role = get_role( 'editor' );
    $role->add_cap( 'gform_full_access' );
}
add_action( 'admin_init', 'add_gf_cap' );


add_filter( 'gform_field_css_class', 'custom_class', 10, 3 );
function custom_class( $classes, $field, $form ) {
    if ( ($field->type == 'text') ||  ($field->type == 'text' )) {
        $classes .= ' uk-input';
    }
}

/**
 * Filters the next, previous and submit buttons.
 * Replaces the forms <input> buttons with <button> while maintaining attributes from original <input>.
 *
 * @param string $button Contains the <input> tag to be filtered.
 * @param object $form Contains all the properties of the current form.
 *
 * @return string The filtered button.
 */
add_filter( 'gform_next_button', 'add_custom_css_classes', 10, 2 );
add_filter( 'gform_previous_button', 'add_custom_css_classes', 10, 2 );
add_filter( 'gform_submit_button', 'add_custom_css_classes', 10, 2 );
function add_custom_css_classes( $button, $form ) {
    $dom = new DOMDocument();
    $dom->loadHTML( $button );
    $input = $dom->getElementsByTagName( 'input' )->item(0);
    $classes = $input->getAttribute( 'class' );
    $classes .= " uk-button uk-button-primary";
    $input->setAttribute( 'class', $classes );
    return $dom->saveHtml( $input );
}


/**
 * Filter the CSS class for a nav menu based on a condition.
 *
 * @param array  $classes The CSS classes that are applied to the menu item's <li> element.
 * @param object $item    The current menu item.
 * @return array (maybe) modified nav menu class.
 */

function footer_menu_classes( $classes, $item, $args ) {
    // Only affect the menu placed in the 'footer' wp_nav_bar() theme location
    if ($args){  
    // print_r($args->menu)	;
		 //if ( strpos($args->menu, 'footer') !== false ) {
			// $classes[] = 'uk-list';
   //     }
	}
    return $classes;
}

 // add_filter( 'nav_menu_css_class', 'footer_menu_classes', 10, 3 ); 
 // NOT WORKING, NOT SURE WHY


/**
 * Gravity Forms Bootstrap Styles
 *
 * Applies bootstrap classes to various common field types.
 * Requires Bootstrap to be in use by the theme.
 *
 * Using this function allows use of Gravity Forms default CSS
 * in conjuction with Bootstrap (benefit for fields types such as Address).
 *
 * @see  gform_field_content
 * @link http://www.gravityhelp.com/documentation/page/Gform_field_content
 *
 * @return string Modified field content
 */
add_filter("gform_field_content", "bootstrap_styles_for_gravityforms_fields", 10, 5);
function bootstrap_styles_for_gravityforms_fields($content, $field, $value, $lead_id, $form_id){
	// Currently only applies to most common field types, but could be expanded.
	if($field["type"] != 'hidden' && $field["type"] != 'list' && $field["type"] != 'multiselect' && $field["type"] != 'checkbox' && $field["type"] != 'fileupload' && $field["type"] != 'date' && $field["type"] != 'html' && $field["type"] != 'address') {
		$content = str_replace('class=\'medium', 'class=\'uk-input medium', $content);
	}
	
if($field["type"] == 'text') {
		$content = str_replace('<input ', '<input class=\'uk-input\' ', $content);
	}
	if($field["type"] == 'name' || $field["type"] == 'address') {
		$content = str_replace('<input ', '<input class=\'uk-input\' ', $content);
	}
	if($field["type"] == 'textarea') {
		$content = str_replace('class=\'textarea', 'class=\'uk-textarea', $content);
	}
	if($field["type"] == 'checkbox') {
		$content = str_replace('li class=\'', 'li class=\'uk-checkbox ', $content);
	}
	if($field["type"] == 'radio') {
		$content = str_replace('li class=\'', 'li class=\'radio ', $content);
	}
	return $content;
} 

add_action('wp_head','my_analytics', 20); 
  function my_analytics() {
		?> 
			<!-- Google Tag Manager -->
			<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','dataLayer','GTM-TQSJRCG');</script>
			<!-- End Google Tag Manager -->
		<?php
}

add_action('__before_header','tag_manager2', 20);
  function tag_manager2(){
		?>

			<!-- Google Tag Manager (noscript) -->
			<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TQSJRCG"
			height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
			<!-- End Google Tag Manager (noscript) -->

		<?php
} 

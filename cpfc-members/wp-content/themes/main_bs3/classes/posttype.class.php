<?php
/**
 * Post Type Class to allow for easy generation of post types for a WordPress Theme/Site
 */
class postType {
	protected $id;
	protected $label;
	protected $args;

	public function __construct($id, $label, $args = array())
	{
		$this->id = $id;
		$this->label = $label;
		$this->args = (array) $args;
		add_action( 'init', array( &$this , 'addPostType') );
		add_action( 'admin_head',  array( &$this , 'addPostTypeIcon') );
	}

	/**
	 * Create Post Type based on arguments in constructor
	 */
	public function addPostType()
	{
		//Register new post type
	    $args = array_merge($this->getDefaults(), $this->args);
	    $args['label'] = $this->label;

	    register_post_type($this->id, $args);
	}

	/**
	 * Add Post Type Icon Stylings
	 */
	public function addPostTypeIcon() { ?>
	    <style type="text/css" media="screen">
	        #menu-posts-story .wp-menu-image {
	            background: url(<?php bloginfo('template_url') ?>/images/admin/story_icon.png) no-repeat 6px 6px !important;
	        }
		 #menu-posts-story:hover .wp-menu-image, #menu-posts-story.wp-has-current-submenu .wp-menu-image {
	            background-position:6px -27px !important;
	        }
		</style>
	<?php
	}

	/**
	 * Get Defaults for Post Type
	 * @return array Defaults
	 */
	private function getDefaults()
	{
		return array(
			'public'              => true,
			'show_ui'             => true,
			'has_archive'         => false,
			'exclude_from_search' => false,
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'rewrite'             => true,
			'query_var'			  => true,
			'supports'			  => array(
				'title',
				'editor',
				'thumbnail',
				'revisions',
			)
		);
	}
}
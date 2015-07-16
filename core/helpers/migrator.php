<?php /**
 * Widget Exporter
 *
 * This file contains the widget Export/Import functionality in Layers
 *
 * @package Layers
 * @since Layers 1.0.0
 */

class Layers_Widget_Migrator {
	
	/**
	 * Used to check against to stop duplicate images beaing adeed to the library.
	 *
	 * @var array
	 */
	public $images_in_widgets;
	
	/**
	 * Collect a report of what happned during the image import process for debugging purposes.
	 *
	 * @var array
	 */
	public $images_report = array();
	
	/**
	 * Used to collect images as they are found in the preset-layout to be added to the zip package.
	 *
	 * @var array
	 */
	public $images_collected;
	
	
	private static $instance;
	/**
	*  Initiator
	*/
	public static function get_instance(){
		if ( ! isset( self::$instance ) ) {
			self::$instance = new Layers_Widget_Migrator();
		}
		return self::$instance;
	}
	/**
	*  Constructor
	*/
	public function __construct() {
	}

	public function init() {

		// Add current builder pages as presets
		add_filter( 'layers_preset_layouts' , array( $this , 'add_builder_preset_layouts') );

	}
	
	/**
	*  Make sure $wp_filesystem is ready in the global.
	*/
	function init_filesystem(){
		
		return WP_Filesystem();
	}

	/**
	*  Make sure that the template directory is nice ans clean for JSON
	*/

	function get_translated_dir_uri(){
		return str_replace('/', '\/', get_template_directory_uri() );
	}

	/**
	*  Make sure that the stylesheet directory is nice ans clean for JSON
	*/

	function get_translated_stylesheet_uri(){
		return str_replace('/', '\/', get_stylesheet_directory_uri() );
	}

	/**
	*  Layers Preset Widget Page Layouts
	*/
	function get_preset_layouts(){
		$layers_preset_layouts = array();

		$layers_preset_layouts = array(

			'application' => array(
					'title' => __( 'Application' , 'layerswp' ),
					'screenshot' => 'http://layerswp.s3.amazonaws.com/preset-layouts/application.png',
					'screenshot_type' => 'png',
					'json' => esc_attr( '{"obox-layers-builder-555":{"layers-widget-slide-152":{"show_slider_arrows":"on","show_slider_dots":"on","slide_time":"","slide_height":"550","design":{"advanced":{"customclass":"","customcss":"","padding":{"top":"","right":"","bottom":"","left":""},"margin":{"top":"","right":"","bottom":"","left":""}}},"slide_ids":"575","slides":{"575":{"design":{"background":{"image":"' . $this->get_translated_dir_uri() . '\/assets\/images\/preset-layouts\/tile.png","color":"#efefef","repeat":"repeat","position":"center"},"featuredimage":"","featuredvideo":"","imagealign":"image-top","fonts":{"align":"text-center","size":"large","color":""}},"title":"Incredible Application","excerpt":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse vitae massa velit, eu laoreet massa.","link":"#","link_text":"Purchase Now"}}},"layers-widget-column-125":{"design":{"layout":"layout-boxed","gutter":"on","fonts":{"align":"text-center","size":"medium","color":""},"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"advanced":{"customclass":"","customcss":"","padding":{"top":"","right":"","bottom":"","left":""},"margin":{"top":"","right":"","bottom":"","left":""}}},"title":"Unbelievable Features","excerpt":"Our services run deep and are backed by over ten years of experience.","column_ids":"347,191","columns":{"347":{"design":{"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"featuredimage":"' . $this->get_translated_dir_uri() . '\/assets\/images\/preset-layouts\/demo-image.png","featuredvideo":"","imageratios":"image-no-crop","imagealign":"image-top","fonts":{"align":"text-center","size":"medium","color":""}},"width":"6","title":"Your feature title","excerpt":"Give us a brief description of the feature that you are promoting. Try keep it short so that it is easy for people to scan your page.","link":"","link_text":""},"191":{"design":{"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"featuredimage":"' . $this->get_translated_dir_uri() . '\/assets\/images\/preset-layouts\/demo-image.png","featuredvideo":"","imagealign":"image-top","fonts":{"align":"text-center","size":"medium","color":""}},"width":"6","title":"Your feature title","excerpt":"Give us a brief description of the feature that you are promoting. Try keep it short so that it is easy for people to scan your page.","link":"","link_text":""}}},"layers-widget-slide-154":{"show_slider_arrows":"on","show_slider_dots":"on","slide_time":"","slide_height":"350","design":{"advanced":{"customclass":"","customcss":"","padding":{"top":"","right":"","bottom":"","left":""},"margin":{"top":"","right":"","bottom":"","left":""}}},"slide_ids":"701","slides":{"701":{"design":{"background":{"image":"' . $this->get_translated_dir_uri() . '\/assets\/images\/preset-layouts\/tile.png","color":"#efefef","repeat":"repeat","position":"center"},"featuredimage":"","featuredvideo":"","imagealign":"image-top","fonts":{"align":"text-center","size":"medium","color":""}},"title":"Purchase for $0.99","excerpt":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse vitae massa velit, eu laoreet massa.","link":"#","link_text":"Purchase Now"}}}}}' )
				),
			'contact-page' => array(
					'title' => __( 'Contact Page' , 'layerswp' ),
					'screenshot' => 'http://layerswp.s3.amazonaws.com/preset-layouts/contact-page.png',
					'screenshot_type' => 'png',
					'json' => esc_attr( '{"obox-layers-builder-557":{"layers-widget-map-15":{"design":{"layout":"layout-fullwidth","fonts":{"align":"text-center","size":"medium","color":""},"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"advanced":{"customclass":"","customcss":"","padding":{"top":"0","right":"","bottom":"0","left":""},"margin":{"top":"","right":"","bottom":"","left":""}}},"map_height":"400","show_google_map":"on","title":"","excerpt":"","google_maps_location":"Green Point, Cape Town, South Africa","google_maps_long_lat":"","address_shown":"","contact_form":""},"layers-widget-column-130":{"design":{"layout":"layout-boxed","gutter":"on","fonts":{"align":"text-left","size":"medium","color":""},"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"advanced":{"customclass":"","customcss":"","padding":{"top":"","right":"","bottom":"","left":""},"margin":{"top":"","right":"","bottom":"","left":""}}},"title":"","excerpt":"","column_ids":"154,981","columns":{"154":{"design":{"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"featuredimage":"","featuredvideo":"","imagealign":"image-top","fonts":{"align":"text-left","size":"large","color":""}},"width":"9","title":"About Us","excerpt":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse vitae massa velit, eu laoreet massa.\r\n\r\nMauris sit amet semper massa. Aliquam vitae nunc vestibulum mauris tempor suscipit id sed lacus. Vestibulum arcu risus, porta eget auctor id, rhoncus et massa. Aliquam erat volutpat.\r\n\r\nSed ac orci libero, eu dignissim enim. Aenean urna sem, cursus ut elementum sed, pellentesque ac massa.\r\n\r\nMauris sit amet semper massa. Aliquam vitae nunc vestibulum mauris tempor suscipit id sed lacus. Vestibulum arcu risus, porta eget auctor id, rhoncus et massa. Aliquam erat volutpat.","link":"","link_text":""},"981":{"design":{"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"featuredimage":"' . $this->get_translated_dir_uri() . '\/assets\/images\/preset-layouts\/demo-image.png","featuredvideo":"","imageratios":"image-landscape","imagealign":"image-top","fonts":{"align":"text-left","size":"medium","color":""}},"width":"3","title":"Come visit us!","excerpt":"Building\r\nStreet\r\nTown\r\nCountry","link":"","link_text":""}}},"layers-widget-column-131":{"design":{"layout":"layout-boxed","gutter":"on","fonts":{"align":"text-left","size":"medium","color":""},"background":{"image":"","color":"#f3f3f3","repeat":"no-repeat","position":"center"},"advanced":{"customclass":"","customcss":"","padding":{"top":"","right":"","bottom":"","left":""},"margin":{"top":"","right":"","bottom":"","left":""}}},"title":"","excerpt":"","column_ids":"962,93,77,478","columns":{"962":{"design":{"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"featuredimage":"","featuredvideo":"","imagealign":"image-top","fonts":{"align":"text-left","size":"small","color":""}},"width":"3","title":"Philosophy title","excerpt":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse vitae massa velit, eu laoreet massa.","link":"","link_text":""},"93":{"design":{"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"featuredimage":"","featuredvideo":"","imagealign":"image-top","fonts":{"align":"text-left","size":"small","color":""}},"width":"3","title":"Philosophy title","excerpt":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse vitae massa velit, eu laoreet massa.","link":"","link_text":""},"77":{"design":{"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"featuredimage":"","featuredvideo":"","imagealign":"image-top","fonts":{"align":"text-left","size":"small","color":""}},"width":"3","title":"Philosophy title","excerpt":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse vitae massa velit, eu laoreet massa.","link":"","link_text":""},"478":{"design":{"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"featuredimage":"","featuredvideo":"","imagealign":"image-top","fonts":{"align":"text-left","size":"small","color":""}},"width":"3","title":"Philosophy title","excerpt":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse vitae massa velit, eu laoreet massa.","link":"","link_text":""}}}}}' )
				),
			'landing' => array(
					'title' => __( 'Landing Page' , 'layerswp' ),
					'screenshot' => 'http://layerswp.s3.amazonaws.com/preset-layouts/landing.png',
					'screenshot_type' => 'png',
					'json' => esc_attr( '{"obox-layers-builder-556":{"layers-widget-column-127":{"design":{"layout":"layout-boxed","gutter":"on","fonts":{"align":"text-center","size":"large","color":""},"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"advanced":{"customclass":"","customcss":"","padding":{"top":"","right":"","bottom":"","left":""},"margin":{"top":"","right":"","bottom":"","left":""}}},"title":"Amazing Opportunity","excerpt":"Lorem ipsum dolor sit amet, consectetur adipiscing elit.","column_ids":"187,228,881","columns":{"187":{"design":{"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"featuredimage":"' . $this->get_translated_dir_uri() . '\/assets\/images\/preset-layouts\/demo-image.png","featuredvideo":"","imagealign":"image-top","fonts":{"align":"text-center","size":"small","color":""}},"width":"4","title":"Unique Selling Point","excerpt":"Give us a brief description of what you are promoting. Try keep it short so that it is easy for people to scan your page.","link":"","link_text":""},"228":{"design":{"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"featuredimage":"' . $this->get_translated_dir_uri() . '\/assets\/images\/preset-layouts\/demo-image.png","featuredvideo":"","imagealign":"image-top","fonts":{"align":"text-center","size":"small","color":""}},"width":"4","title":"Unique Selling Point","excerpt":"Give us a brief description of what you are promoting. Try keep it short so that it is easy for people to scan your page.","link":"","link_text":""},"881":{"design":{"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"featuredimage":"' . $this->get_translated_dir_uri() . '\/assets\/images\/preset-layouts\/demo-image.png","featuredvideo":"","imagealign":"image-top","fonts":{"align":"text-center","size":"small","color":""}},"width":"4","title":"Unique Selling Point","excerpt":"Give us a brief description of what you are promoting. Try keep it short so that it is easy for people to scan your page.","link":"","link_text":""}}},"layers-widget-column-128":{"design":{"layout":"layout-boxed","gutter":"on","fonts":{"align":"text-left","size":"medium","color":""},"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"advanced":{"customclass":"","customcss":"","padding":{"top":"","right":"","bottom":"","left":""},"margin":{"top":"","right":"","bottom":"","left":""}}},"title":"","excerpt":"","column_ids":"187","columns":{"187":{"design":{"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"featuredimage":"' . $this->get_translated_dir_uri() . '\/assets\/images\/preset-layouts\/demo-image.png","featuredvideo":"","imageratios":"image-no-crop","imagealign":"image-top","fonts":{"align":"text-center","size":"medium","color":""}},"width":"12","title":"Featured Image","excerpt":"","link":"","link_text":""}}},"layers-widget-slide-156":{"show_slider_arrows":"on","show_slider_dots":"on","slide_time":"","slide_height":"350","design":{"advanced":{"customclass":"","customcss":"","padding":{"top":"","right":"","bottom":"","left":""},"margin":{"top":"","right":"","bottom":"","left":""}}},"slide_ids":"236","slides":{"236":{"design":{"background":{"image":"' . $this->get_translated_dir_uri() . '\/assets\/images\/preset-layouts\/tile.png","color":"#efefef","repeat":"repeat","position":"center"},"featuredimage":"","featuredvideo":"","imagealign":"image-top","fonts":{"align":"text-center","size":"medium","color":""}},"title":"Wow. Just wow. I\'ve yet to use a site builder that\'s as good as this.","excerpt":"Mrs. WordPress","link":"","link_text":""}}}}}' )
				),
			'lookbook-page' => array(
					'title' => __( 'Lookbook Page' , 'layerswp' ),
					'screenshot' => 'http://layerswp.s3.amazonaws.com/preset-layouts/lookbook-page.png',
					'screenshot_type' => 'png',
					'json' => esc_attr( '{"obox-layers-builder-563":{"layers-widget-slide-158":{"design":{"layout":"layout-full-screen","advanced":{"customclass":"","customcss":"","padding":{"top":"","right":"","bottom":"","left":""},"margin":{"top":"","right":"","bottom":"","left":""}}},"show_slider_arrows":"on","show_slider_dots":"on","slide_time":"","slide_height":"550","slide_ids":"276,257,405,523","slides":{"276":{"design":{"background":{"image":"' . $this->get_translated_dir_uri() . '\/assets\/images\/preset-layouts\/tile.png","color":"#efefef","repeat":"repeat","position":"center"},"featuredimage":"","featuredvideo":"","imagealign":"image-top","fonts":{"align":"text-center","size":"large","color":""}},"title":"2015 Lookbook","excerpt":"","link":"","link_text":""},"257":{"design":{"background":{"image":"' . $this->get_translated_dir_uri() . '\/assets\/images\/preset-layouts\/tile.png","color":"#efefef","repeat":"repeat","position":"center"},"featuredimage":"","featuredvideo":"","imagealign":"image-right","fonts":{"align":"text-center","size":"large","color":""}},"title":"Unique ecommerce item","excerpt":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse vitae massa velit, eu laoreet massa. Sed ac orci libero, eu dignissim enim.","link":"","link_text":""},"405":{"design":{"background":{"image":"' . $this->get_translated_dir_uri() . '\/assets\/images\/preset-layouts\/tile.png","color":"#efefef","repeat":"repeat","position":"center"},"featuredimage":"","featuredvideo":"","imagealign":"image-left","fonts":{"align":"text-center","size":"large","color":""}},"title":"Unique ecommerce item","excerpt":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse vitae massa velit, eu laoreet massa. Sed ac orci libero, eu dignissim enim.","link":"","link_text":""},"523":{"design":{"background":{"image":"' . $this->get_translated_dir_uri() . '\/assets\/images\/preset-layouts\/tile.png","color":"#efefef","repeat":"repeat","position":"center"},"featuredimage":"","featuredvideo":"","imagealign":"image-right","fonts":{"align":"text-center","size":"large","color":""}},"title":"Unique ecommerce item","excerpt":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse vitae massa velit, eu laoreet massa. Sed ac orci libero, eu dignissim enim.","link":"","link_text":""}}}}}' )
				),
			'one-pager' => array(
					'title' => __( 'One Pager' , 'layerswp' ),
					'screenshot' => 'http://layerswp.s3.amazonaws.com/preset-layouts/one-pager.png',
					'screenshot_type' => 'png',
					'json' => esc_attr( '{"obox-layers-builder-566":{"layers-widget-slide-160":{"design":{"layout":"layout-full-screen","advanced":{"customclass":"","customcss":"","padding":{"top":"","right":"","bottom":"","left":""},"margin":{"top":"","right":"","bottom":"","left":""}}},"show_slider_arrows":"on","show_slider_dots":"on","slide_time":"","slide_height":"550","slide_ids":"817,77,954","slides":{"817":{"design":{"background":{"image":"' . $this->get_translated_dir_uri() . '\/assets\/images\/preset-layouts\/tile.png","color":"#efefef","repeat":"repeat","position":"center"},"featuredimage":"","featuredvideo":"","imagealign":"image-top","fonts":{"align":"text-center","size":"large","color":""}},"title":"One Page Feature Title","excerpt":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse vitae massa velit, eu laoreet massa. Sed ac orci libero, eu dignissim enim.","link":"","link_text":""},"77":{"design":{"background":{"image":"' . $this->get_translated_dir_uri() . '\/assets\/images\/preset-layouts\/tile.png","color":"#efefef","repeat":"repeat","position":"center"},"featuredimage":"","featuredvideo":"","imagealign":"image-left","fonts":{"align":"text-center","size":"large","color":""}},"title":"Second Feature Title","excerpt":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse vitae massa velit, eu laoreet massa. Sed ac orci libero, eu dignissim enim.","link":"","link_text":""},"954":{"design":{"background":{"image":"' . $this->get_translated_dir_uri() . '\/assets\/images\/preset-layouts\/tile.png","color":"#efefef","repeat":"repeat","position":"center"},"featuredimage":"","featuredvideo":"","imagealign":"image-right","fonts":{"align":"text-center","size":"large","color":""}},"title":"Third Feature Title","excerpt":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse vitae massa velit, eu laoreet massa. Sed ac orci libero, eu dignissim enim.","link":"","link_text":""}}},"layers-widget-column-138":{"design":{"layout":"layout-boxed","gutter":"on","fonts":{"align":"text-left","size":"medium","color":""},"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"advanced":{"customclass":"","customcss":"","padding":{"top":"","right":"","bottom":"0","left":""},"margin":{"top":"","right":"","bottom":"","left":""}}},"title":"","excerpt":"","column_ids":"488","columns":{"488":{"design":{"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"featuredimage":"","featuredvideo":"https:\/\/vimeo.com\/29959550","imagealign":"image-top","fonts":{"align":"text-left","size":"medium","color":""}},"width":"12","title":"","excerpt":"","link":"","link_text":""}}},"layers-widget-column-139":{"design":{"layout":"layout-boxed","gutter":"on","fonts":{"align":"text-left","size":"medium","color":""},"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"advanced":{"customclass":"","customcss":"","padding":{"top":"30","right":"","bottom":"","left":""},"margin":{"top":"","right":"","bottom":"","left":""}}},"title":"","excerpt":"","column_ids":"575,52,488","columns":{"575":{"design":{"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"featuredimage":"","featuredvideo":"","imagealign":"image-top","fonts":{"align":"text-left","size":"small","color":""}},"width":"4","title":"Your service title","excerpt":"Give us a brief description of the service that you are promoting. Try keep it short so that it is easy for people to scan your page.","link":"","link_text":""},"52":{"design":{"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"featuredimage":"","featuredvideo":"","imagealign":"image-top","fonts":{"align":"text-left","size":"small","color":""}},"width":"4","title":"Your service title","excerpt":"Give us a brief description of the service that you are promoting. Try keep it short so that it is easy for people to scan your page.","link":"","link_text":""},"488":{"design":{"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"featuredimage":"","featuredvideo":"","imagealign":"image-top","fonts":{"align":"text-left","size":"small","color":""}},"width":"4","title":"Your service title","excerpt":"Give us a brief description of the service that you are promoting. Try keep it short so that it is easy for people to scan your page.","link":"","link_text":""}}},"layers-widget-column-140":{"design":{"layout":"layout-boxed","gutter":"on","fonts":{"align":"text-center","size":"medium","color":""},"background":{"image":"","color":"#f3f3f3","repeat":"no-repeat","position":"center"},"advanced":{"customclass":"","customcss":"","padding":{"top":"","right":"","bottom":"","left":""},"margin":{"top":"","right":"","bottom":"","left":""}}},"title":"Our Work","excerpt":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse vitae massa velit, eu laoreet massa.","column_ids":"575,953,410,16,796,728","columns":{"575":{"design":{"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"featuredimage":"' . $this->get_translated_dir_uri() . '\/assets\/images\/preset-layouts\/demo-image.png","featuredvideo":"","imagealign":"image-top","fonts":{"align":"text-left","size":"small","color":""}},"width":"4","title":"Portfolio Item","excerpt":"Give us a brief description of the work you\u2019ve meticulously created.","link":"","link_text":""},"953":{"design":{"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"featuredimage":"' . $this->get_translated_dir_uri() . '\/assets\/images\/preset-layouts\/demo-image.png","featuredvideo":"","imagealign":"image-top","fonts":{"align":"text-left","size":"small","color":""}},"width":"4","title":"Portfolio Item","excerpt":"Give us a brief description of the work you\u2019ve meticulously created.","link":"","link_text":""},"410":{"design":{"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"featuredimage":"' . $this->get_translated_dir_uri() . '\/assets\/images\/preset-layouts\/demo-image.png","featuredvideo":"","imagealign":"image-top","fonts":{"align":"text-left","size":"small","color":""}},"width":"4","title":"Portfolio Item","excerpt":"Give us a brief description of the work you\u2019ve meticulously created.","link":"","link_text":""},"16":{"design":{"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"featuredimage":"' . $this->get_translated_dir_uri() . '\/assets\/images\/preset-layouts\/demo-image.png","featuredvideo":"","imagealign":"image-top","fonts":{"align":"text-left","size":"small","color":""}},"width":"4","title":"Portfolio Item","excerpt":"Give us a brief description of the work you\u2019ve meticulously created.","link":"","link_text":""},"796":{"design":{"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"featuredimage":"' . $this->get_translated_dir_uri() . '\/assets\/images\/preset-layouts\/demo-image.png","featuredvideo":"","imagealign":"image-top","fonts":{"align":"text-left","size":"small","color":""}},"width":"4","title":"Portfolio Item","excerpt":"Give us a brief description of the work you\u2019ve meticulously created.","link":"","link_text":""},"728":{"design":{"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"featuredimage":"' . $this->get_translated_dir_uri() . '\/assets\/images\/preset-layouts\/demo-image.png","featuredvideo":"","imagealign":"image-top","fonts":{"align":"text-left","size":"small","color":""}},"width":"4","title":"Portfolio Item","excerpt":"Give us a brief description of the work you\u2019ve meticulously created.","link":"","link_text":""}}},"layers-widget-map-17":{"design":{"layout":"layout-fullwidth","fonts":{"align":"text-center","size":"medium","color":""},"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"advanced":{"customclass":"","customcss":"","padding":{"top":"","right":"","bottom":"0","left":""},"margin":{"top":"","right":"","bottom":"","left":""}}},"map_height":"400","show_google_map":"on","title":"Find Us","excerpt":"We are based in one of the most beautiful places on earth. Come visit us!","google_maps_location":"Green Point, Cape Town, South Africa","google_maps_long_lat":"","address_shown":"","contact_form":""}}}' )
				),
			'portfolio-page' => array(
					'title' => __( 'Portfolio Page' , 'layerswp' ),
					'screenshot' => 'http://layerswp.s3.amazonaws.com/preset-layouts/portfolio-page.png',
					'screenshot_type' => 'png',
					'json' => esc_attr( '{"obox-layers-builder-565":{"layers-widget-column-136":{"design":{"layout":"layout-boxed","gutter":"on","fonts":{"align":"text-left","size":"medium","color":""},"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"advanced":{"customclass":"","customcss":"","padding":{"top":"","right":"","bottom":"","left":""},"margin":{"top":"","right":"","bottom":"","left":""}}},"title":"Our Amazing Work","excerpt":"Our services run deep and are backed by over ten years of experience.","column_ids":"552,654,592,854,454,939","columns":{"552":{"design":{"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"featuredimage":"' . $this->get_translated_dir_uri() . '\/assets\/images\/preset-layouts\/demo-image.png","featuredvideo":"","imagealign":"image-top","fonts":{"align":"text-left","size":"small","color":""}},"width":"4","title":"Portfolio Item","excerpt":"Give us a brief description of the work you\'ve meticulously created.","link":"","link_text":""},"654":{"design":{"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"featuredimage":"' . $this->get_translated_dir_uri() . '\/assets\/images\/preset-layouts\/demo-image.png","featuredvideo":"","imagealign":"image-top","fonts":{"align":"text-left","size":"small","color":""}},"width":"4","title":"Portfolio Item","excerpt":"Give us a brief description of the work you\'ve meticulously created.","link":"","link_text":""},"592":{"design":{"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"featuredimage":"' . $this->get_translated_dir_uri() . '\/assets\/images\/preset-layouts\/demo-image.png","featuredvideo":"","imagealign":"image-top","fonts":{"align":"text-left","size":"small","color":""}},"width":"4","title":"Portfolio Item","excerpt":"Give us a brief description of the work you\'ve meticulously created.","link":"","link_text":""},"854":{"design":{"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"featuredimage":"' . $this->get_translated_dir_uri() . '\/assets\/images\/preset-layouts\/demo-image.png","featuredvideo":"","imagealign":"image-top","fonts":{"align":"text-left","size":"small","color":""}},"width":"4","title":"Portfolio Item","excerpt":"Give us a brief description of the work you\'ve meticulously created.","link":"","link_text":""},"454":{"design":{"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"featuredimage":"' . $this->get_translated_dir_uri() . '\/assets\/images\/preset-layouts\/demo-image.png","featuredvideo":"","imagealign":"image-top","fonts":{"align":"text-left","size":"small","color":""}},"width":"4","title":"Portfolio Item","excerpt":"Give us a brief description of the work you\'ve meticulously created.","link":"","link_text":""},"939":{"design":{"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"featuredimage":"' . $this->get_translated_dir_uri() . '\/assets\/images\/preset-layouts\/demo-image.png","featuredvideo":"","imagealign":"image-top","fonts":{"align":"text-left","size":"small","color":""}},"width":"4","title":"Portfolio Item","excerpt":"Give us a brief description of the work you\'ve meticulously created.","link":"","link_text":""}}}}}' )
				),
			'video-page' => array(
					'title' => __( 'Video Page' , 'layerswp' ),
					'screenshot' => 'http://layerswp.s3.amazonaws.com/preset-layouts/video-page.png',
					'screenshot_type' => 'png',
					'json' => esc_attr( '{"obox-layers-builder-564":{"layers-widget-column-133":{"design":{"layout":"layout-boxed","gutter":"on","fonts":{"align":"text-center","size":"large","color":""},"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"advanced":{"customclass":"","customcss":"","padding":{"top":"","right":"","bottom":"0","left":""},"margin":{"top":"","right":"","bottom":"","left":""}}},"title":"Welcome to our short film","excerpt":"Lorem ipsum dolor sit amet, consectetur adipiscing elit.","column_ids":"153","columns":{"153":{"design":{"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"featuredimage":"","featuredvideo":"https:\/\/vimeo.com\/29959550","imagealign":"image-top","fonts":{"align":"text-left","size":"medium","color":""}},"width":"12","title":"","excerpt":"","link":"","link_text":""}}},"layers-widget-column-134":{"design":{"layout":"layout-boxed","gutter":"on","fonts":{"align":"text-left","size":"medium","color":""},"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"advanced":{"customclass":"","customcss":"","padding":{"top":"","right":"","bottom":"","left":""},"margin":{"top":"","right":"","bottom":"","left":""}}},"title":"","excerpt":"","column_ids":"621,795","columns":{"621":{"design":{"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"featuredimage":"","featuredvideo":"","imagealign":"image-top","fonts":{"align":"text-left","size":"medium","color":""}},"width":"6","title":"About this video","excerpt":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse vitae massa velit, eu laoreet massa. Sed ac orci libero, eu dignissim enim. Aenean urna sem, cursus ut elementum sed, pellentesque ac massa.\r\n\r\nAenean urna sem, cursus ut elementum sed, pellentesque ac massa. Mauris sit amet semper massa. Aliquam vitae nunc vestibulum mauris tempor suscipit id sed lacus. Vestibulum arcu risus, porta eget auctor id, rhoncus et massa. Aliquam erat volutpat.","link":"","link_text":""},"795":{"design":{"background":{"image":"","color":"","repeat":"no-repeat","position":"center"},"featuredimage":"","featuredvideo":"","imagealign":"image-top","fonts":{"align":"text-left","size":"small","color":""}},"width":"6","title":"Video Credits","excerpt":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse vitae massa velit, eu laoreet massa. Sed ac orci libero, eu dignissim enim. Aenean urna sem, cursus ut elementum sed, pellentesque ac massa. Mauris sit amet semper massa.","link":"","link_text":""}}}}}' )
				),
			'blank' => array(
					'title' => __( 'Blank Page' , 'layerswp' ),
					'screenshot' => NULL,
					'json' => esc_attr( '{}' ),
					'container-css' => 'blank-product'
				),
		);

		return apply_filters( 'layers_preset_layouts' , $layers_preset_layouts );
	}

	/**
	*  Add our builder pages as presets
	*
	* @param array array of preset layouts that have been set
	*/
	function add_builder_preset_layouts( $presets ){

		// Get array of builder pages that exist
		$builder_pages = layers_get_builder_pages();

		// Start preset page bucket
		$page_presets = array();

		// Loop through the pages and add them to the preset list
		foreach ( $builder_pages as $page ) {
			$page_presets[ $page->ID ] = array(
				'title' => esc_attr( $page->post_title ),
				'screenshot' => get_permalink( $page->ID ),
				'screenshot_type' => 'dynamic',
				'json' =>  esc_attr( json_encode( $this->export_data( $page ) ) ),
				'container-css' => 'layers-hide layers-existing-page-preset'
			);
		}

		return array_merge( $presets, $page_presets );
	}

	/**
	* Layers Page Layout Screenshot Generator
	*
	* Generates an image tag for the screenshot for use in the preset layout selector
	*
	* @param string URL to use for the screenshot
	* @param string png (for static images) | dynamic (for existing pages)
	* @return string <img> tag
	*/
	function generate_preset_layout_screenshot( $url = NULL, $type = 'screenshot' ){

		// If there is no URL to parse, return nothing
		if( NULL == $url ) return;

		// Dynamic types generate a screenshot from the WordPress mshots service
		if( 'dynamic' == $type ) {
			$image_url =  'http://s.wordpress.com/mshots/v1/' . urlencode( $url ) . '?w=' . 320 . '&h=' . 480;
		} else {
			$image_url = $url;
		}

		$img = '<img src="' . esc_url( $image_url ) . '" width="320" />';

		return $img;

	}


	/**
	*  Get all available widgets
	*/

	function available_widgets() {

		global $wp_registered_widget_controls;

		$widget_controls = $wp_registered_widget_controls;

		// Kick off a blank readable array
		$available_widgets = array();

		// Loop through widget controls and add the wiget ID and Name
		foreach ( $widget_controls as $widget ) {

			if ( ! empty( $widget['id_base'] ) && ! isset( $available_widgets[$widget['id_base']] ) ) { // no dupes

				$available_widgets[$widget['id_base']]['id_base'] = $widget['id_base'];
				$available_widgets[$widget['id_base']]['name'] = $widget['name'];

			}

		}

		return $available_widgets;
	}

	/**
	*  Widget Instances and their data
	*/

	function get_widget_instances(){

		// Get all available widgets site supports
		$available_widgets = $this->available_widgets();
		foreach ( $available_widgets as $widget_data ) {

			// Get all instances for this ID base
			$instances = get_option( 'widget_' . $widget_data['id_base'] );

			// Have instances
			if ( ! empty( $instances ) ) {

				// Loop instances
				foreach ( $instances as $instance_id => $instance_data ) {

					// Key is ID (not _multiwidget)
					if ( is_numeric( $instance_id ) ) {
						$unique_instance_id = $widget_data['id_base'] . '-' . $instance_id;
						$widget_instances[$unique_instance_id] = $instance_data;
					}

				}

			}
		}

		return $widget_instances;
	}

	/**
	*  Get valid sidebars for a specific page
	*
	* @param object Post Object of page to generate export data for
	* @return array An array of sidebar ids that are valid for this page
	*/

	public function get_valid_sidebars( $post_object ) {
		global $layers_widgets;

		// Get all widget instances for each widget
		$widget_instances = $this->get_widget_instances();

		// Get page sidebar ID
		$page_sidebar_id = 'obox-layers-builder-' . $post_object->ID;

		// Get sidebars and their unique widgets IDs
		$sidebars_widgets = get_option( 'sidebars_widgets' );

		// Setup valid_sidebars() array
		$valid_sidebars = array();

		// Setup valid sidebars
		$valid_sidebars[] = $page_sidebar_id;

		// Get all dynamic sidebars
		$dynamic_sidebars = $layers_widgets->get_dynamic_sidebars();

		// Double check that the page we are looking for has a sidebar registered
		if( !isset( $sidebars_widgets[ $page_sidebar_id ] ) ) return;

		foreach ( $sidebars_widgets as $sidebar_id => $widget_ids ) {

			// If this sidebar ID does not match the ID of the page, continue to the next sidebar
			if( $sidebar_id !=  $page_sidebar_id ) continue;

			// Skip inactive widgets
			if ( 'wp_inactive_widgets' == $sidebar_id ) {
				continue;
			}

			// Skip if no data or not an array (array_version)
			if ( ! is_array( $widget_ids ) || empty( $widget_ids ) ) {
				continue;
			}

			//TODO - Add sidebar mapper to map page ID to new page ID and dynamic sidebar keys to new sidebar keys

			// Loop widget IDs for this sidebar to find the Dynamic Sidebar Widget and its sidebar IDs
			foreach ( $widget_ids as $widget_id ) {
				if( FALSE !== strpos( $widget_id, 'layers-widget-sidebar' ) ) {

					if( !empty( $widget_instances[ $widget_id ][ 'sidebars' ] ) ) {
						foreach( $widget_instances[ $widget_id ][ 'sidebars' ] as $key => $options ) {
							$valid_sidebars[] = $widget_id . '-' . $key;
						}
					}
				}
			}
		}

		return $valid_sidebars;

	}

	/**
	*  Populate Sidebars/Widgets array
	*
	* @return array Array including page sidebar & widget settings
	*/

	public function page_sidebars_widgets( $post_object = NULL ) {

		if( NULL == $post_object ){
			global $post;
		} else {
			$post = $post_object;
		}

		// Get all widget instances for each widget
		$widget_instances = $this->get_widget_instances();

		// Get valid sidebars to query
		$valid_sidebars = $this->get_valid_sidebars( $post );

		if ( NULL == $valid_sidebars ) return;

		// Gather sidebars with their widget instances
		$sidebars_widgets = get_option( 'sidebars_widgets' ); // get sidebars and their unique widgets IDs
		$sidebars_widget_instances = array();

		foreach ( $sidebars_widgets as $sidebar_id => $widget_ids ) {

			// If this sidebar ID is not present in the valid sidebar array, continue
			if( !in_array( $sidebar_id , $valid_sidebars )  ) continue;

			// Skip inactive widgets
			if ( 'wp_inactive_widgets' == $sidebar_id ) {
				continue;
			}

			// Skip if no data or not an array (array_version)
			if ( ! is_array( $widget_ids ) || empty( $widget_ids ) ) {
				continue;
			}

			// Loop widget IDs for this sidebar
			foreach ( $widget_ids as $widget_id ) {

				// Is there an instance for this widget ID?
				if ( isset( $widget_instances[$widget_id] ) ) {

					// Add to array
					$sidebars_widget_instances[$sidebar_id][$widget_id] = $widget_instances[$widget_id];
				}

			}

		}

		return $sidebars_widget_instances;
	}

	/**
	*  Export sidebar data
	*
	* @return array Array of sidebar settings including image options translated via $this->validate_data()
	*/

	function export_data( $post = NULL ){

		if( NULL == $post ) {
			global $post;
		}

		// Get sidebar and widget data for this page
		$sidebars_widgets = $this->page_sidebars_widgets( $post );

		if( empty( $sidebars_widgets ) ) return;

		// Loop through options and look for images @TODO: Add categories to this, could be useful, also add dynamic sidebar widgets
		foreach( $sidebars_widgets as $option => $data ){
			$sidebars_widgets[ $option ] = $this->validate_data( $data );
		}

		// Return modified sidebar widgets
		return $sidebars_widgets;
	}

	/**
	*  Validate Input (Look for images)
	*/

	public function validate_data( $data ) {
		global $wp_filesystem;

		$validated_data = array();

		foreach( $data as $option => $option_data ){

			if( is_array( $option_data ) ) {

				$validated_data[ $option ] = $this->validate_data( $option_data );

			} elseif( 'image' == $option || 'featuredimage' == $option ) {
				$get_image_url = $this->get_attachment_url_from_id( $option_data );

				if( NULL != $get_image_url ) {
					$validated_data[ $option ] = $get_image_url;
				} else {
					$validated_data[ $option ] = stripslashes( $option_data );
				}
				
				if( is_object( $wp_filesystem ) && isset( $validated_data[ $option ] ) && '' != $validated_data[ $option ] ) {
					$this->images_collected[] = array(
						'url' => $validated_data[ $option ],
						'path'  => str_replace( trailingslashit( WP_CONTENT_URL ), $wp_filesystem->wp_content_dir(), $validated_data[ $option ] ),
					);
				}
				
			} else {
				$validated_data[ $option ] = stripslashes( $option_data );
			}
		}

		return $validated_data;
	}
	
	/**
	* Get image url from their attachment ID.
	*/

	function get_attachment_url_from_id( $id ){

		$get_image = wp_get_attachment_image_src( $id, 'full' );
		if( $get_image ) {
			return $get_image[0];
		} else {
			return NULL;
		}
	}

	/**
	*  Get image ID from their url.
	*/

	function get_attachment_id_from_url( $url ) {
		global $wpdb;

		if( is_object( $url ) ) return;

		preg_match("/src='([^']+)/i", $url , $img_url_almost );

		if( empty( $img_url_almost ) ) return NULL;

		$url = str_ireplace( "src='", "",  $img_url_almost[0]);

		return $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE guid=%s", $url ) );
	}

	/**
	*  Import Images
	*/

	public function import_images( $data, $args = array() ) {

		if( !is_array( $data ) ) return stripslashes( $data );
		
		$defaults = array(
			'create_new_image_if_name_exists' => FALSE,
			'download_images'                 => TRUE,
		);
		$args = wp_parse_args( $args, $defaults );
		
		$validated_data = array();
		
		foreach( $data as $option => $option_data ){

			if( is_array( $option_data ) ) {

				$validated_data[ $option ] = $this->import_images( $option_data, $args );

			} elseif( 'image' == $option || 'featuredimage' == $option ) {

				// Check to see if this image exists in our media library already
				$check_for_image = $this->search_for_import_image( $option_data, $args );

				if( NULL != $check_for_image ) {
					$get_image_id = $check_for_image;
				} else {
					// @TODO: Try improve the image loading
					$import_image = media_sideload_image( $option_data , 0 );

					if( NULL != $import_image && !is_wp_error( $import_image ) ) {
						$get_image_id = $this->get_attachment_id_from_url( $import_image );
					}
				}

				if( isset( $get_image_id ) ) {
					$validated_data[ $option ] = $get_image_id;
				} else {
					$validated_data[ $option ] = stripslashes( $option_data );
				}

			} else {

				$validated_data[ $option ] = stripslashes( $option_data );

			}
		}

		return $validated_data;
	}
	
	/**
	*  Search for an image
	*
	*  Search in media library, common locations, or filter created custom locations.
	*/

	public function search_for_import_image( $image_url = NULL, $args = array() ){
		global $wpdb;

		if( NULL == $image_url ) return;
		
		$defaults = array(
			'create_new_image_if_name_exists' => FALSE,
			'download_images'                 => TRUE,
		);
		$args = wp_parse_args( $args, $defaults );

		// Get and store the FileName.
		$image_pieces = explode( '/', $image_url );
		$file_name = $image_pieces[count($image_pieces)-1];

		// Specify common locations to look for packaged images.
		$common_locations = array(
			array(
				'path' => get_stylesheet_directory() . '/assets/preset-images/', // Child Theme default image location
				'url' => get_stylesheet_directory_uri() . '/assets/preset-images/',
			)
		);
		
		// Allow adding of other locations to look for image names in.
		$check_image_locations = apply_filters( 'layers_check_image_locations', $common_locations );
		
		// Check if the image is in the DB.
		$db_image = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE guid LIKE %s", "%$file_name%" ) );
		
		// Check if the image is in any of the disk locations, first check if we have added it to the db in this session too (to stop duplicate images in the Media Library).
		$disk_image = false;
		
		$status = array( $file_name );
		
		if ( $db_image && $args['create_new_image_if_name_exists'] ) {
			if ( isset( $this->images_in_widgets[$file_name]['url'] ) ) {
				$status[] = 'Have previously uploaded image from disk';
				$disk_image = $this->images_in_widgets[$file_name]['url'];
			}
			else {
				foreach ( $check_image_locations as $location ) {
					if( file_exists( $location[ 'path' ] . $file_name ) ) {
						$status[] = 'Uploaded image from disk';
						if( TRUE == $args['download_images'] ) {
							// If set to FALSE then this can be used for test purposes to see how many image would have been downloaded
					 		$disk_image = $this->get_attachment_id_from_url( media_sideload_image( $location[ 'url' ] . $file_name, 0 ) );
					 	}
					 	else{
					 		// Otherwise just leave the value as it is.
					 		$disk_image = $image_url;
					 	}
					 	$this->images_in_widgets[$file_name] = array();
						$this->images_in_widgets[$file_name]['url'] = $disk_image;
					}
				}
			}
		}
		
		// Find which image to use?
		$found_image = NULL;
		if ( $db_image && $disk_image && TRUE == $args['create_new_image_if_name_exists'] ) {
			$status[] = 'Used the disk image';
			$found_image = $disk_image;
		}
		elseif ( $db_image && $disk_image ) {
			$status[] = 'Found both disk & DB images, used DB';
			$found_image = $db_image;
		}
		elseif ( $db_image ) {
			$status[] = 'Used DB image';
			$found_image = $db_image;
		}
		elseif ( $disk_image ) {
			$status[] = 'Used disk image';
			$found_image = $disk_image;
		}
		
		// Debugging purposes.
		//echo implode( ' -> ', $status ) . '<br>';
		$this->images_report[] = implode( ' -> ', $status );
		
		// If nothing is found, just return NULL
		return $found_image;
	}
	
	/**
	* Search & Replace Widgets in a specific page
	*/
	
	public function process_widgets_in_page( $page_ids = array() ) {
		
		global $wp_registered_sidebars, $wp_registered_widgets;
		
		// This allows for the argument to be a single page ID, as appose to array
		if ( !is_array( $page_ids ) ){
			$page_ids = array( $page_ids );
		}
		
		foreach ( $page_ids as $page_id ) {
			
			$sidebar_id = 'obox-layers-builder-' . $page_id;
	
			// Holds the final data to return
			$output = array();
			
			// Loop over all of the registered sidebars looking for the one with the same name as $sidebar_id
			$sibebar_id = false;
			foreach( $wp_registered_sidebars as $sidebar ) {
				if( $sidebar['name'] == $sidebar_id ) {
					// We now have the Sidebar ID, we can stop our loop and continue.
					$sidebar_id = $sidebar['id'];
					break;
				}
			}
			
			if( !$sidebar_id ) {
				// There is no sidebar registered with the name provided.
				return $output;
			}
			
			// A nested array in the format $sidebar_id => array( 'widget_id-1', 'widget_id-2' ... );
			$sidebars_widgets = wp_get_sidebars_widgets();
			$widget_ids = $sidebars_widgets[$sidebar_id];
			
			if( !$widget_ids ) {
				// Without proper widget_ids we can't continue.
				return array();
			}
			
			// Loop over each widget_id so we can fetch the data out of the wp_options table.
			foreach( $widget_ids as $id ) {
				
				// The name of the option in the database is the name of the widget class.
				$option_name = $wp_registered_widgets[$id]['callback'][0]->option_name;
				
				// Widget data is stored as an associative array. To get the right data we need to get the right key which is stored in $wp_registered_widgets
				$key = $wp_registered_widgets[$id]['params'][0]['number'];
				
				$widget_data = get_option( $option_name );
				
				// Add the widget data on to the end of the output array.
				//$widgets[] = (object) $widget_data[$key];
				
				$widget_data = $widget_data[$key];
				
				$widget_data = array_merge( array(
					'option_name' => $option_name,
					'key' => $key,
					'id' => $id,
				), $widget_data );
				
				$widgets[] = $widget_data;
			}
			
			// if ( FALSE && isset( $widgets[0]['slides'][488]['title'] ) ) {
			// 	$widgets[0]['slides'][488]['title'] = "Business development and acquisition specialist yeah!";
			// }
			
			$widgets_modified = apply_filters( 'layers_filter_widgets', $widgets, $page_id );
			
			// Nothing has been modified in the widgets so just continue to the next Page
			if ( $widgets_modified === $widgets ) continue;
			
			// Loop through the widgets and put them back into their own individual option fields
			foreach ( $widgets_modified as $widget ) {
				
				$option_name = $widget['option_name'];
				$key = $widget['key'];
				$id = $widget['id'];
				
				unset( $widget['option_name'] );
				unset( $widget['key'] );
				unset( $widget['id'] );
				
				$widget_option = get_option( $option_name );
				
				$widget_option[$key] = $widget;
				
				update_option( $option_name, $widget_option );
			}
		}
	}
	
	/**
	* Search and Replace Widget Data
	*/

	public function search_and_replace_images_in_widget( $data, $args = array() ) {
		
		if( !is_array( $data ) ) return stripslashes( $data );
		
		$validated_data = array();
		
		foreach( $data as $option => $option_data ){

			if( is_array( $option_data ) ) {
				
				// Recursively call this function
				$validated_data[ $option ] = $this->search_and_replace_images_in_widget( $option_data, $args );

			} elseif( 'image' == $option || 'featuredimage' == $option ) {
				
				// Get and store the FileName.
				$image_pieces = explode( '/', $option_data );
				$file_name = $image_pieces[count( $image_pieces )-1];
				
				// Check if we have passed a 'file_name' to 'id' replacement to this array.
				if ( array_key_exists( $file_name, $args ) ){
					$option_data = $args[$file_name]['id'];
				}
				
				// Return the value
				$validated_data[ $option ] = stripslashes( $option_data );

			} else {
				
				// Return the value
				$validated_data[ $option ] = stripslashes( $option_data );
			}
		}

		return $validated_data;
	}

	/**
	* Ajax Import Instantiator
	*
	* This function takes on the widget_data post object and runs the import() function
	*/

	public function do_ajax_import(){

		if( !check_ajax_referer( 'layers-migrator-import', 'nonce', false ) ) die( 'You threw a Nonce exception' ); // Nonce

		// Set the page ID
		$import_data[ 'post_id' ] = $_POST[ 'post_id' ];

		// Set the Widget Data for import
		$import_data[ 'widget_data' ] = $_POST[ 'widget_data' ];

		// Run data import
		$import_progress = $this->import( $import_data );

		$results = array(
			'post_id' => $import_data[ 'post_id' ],
			'data_report' => $import_progress,
			'customizer_location' => admin_url() . 'customize.php?url=' . esc_url( get_the_permalink( $import_data[ 'post_id' ] ) )
		);

		die( json_encode( $results ) );
	}

	/**
	* Ajax Duplication
	*
	* This function takes a page, generates export cod and creates a duplicate
	*/

	public function do_ajax_duplicate(){

		if( !check_ajax_referer( 'layers-migrator-duplicate', 'nonce', false ) ) die( 'You threw a Nonce exception' ); // Nonce

		// We need a page title and post ID for this to work
		if( !isset( $_POST[ 'post_title' ] ) || !isset( $_POST[ 'post_id' ]  ) ) return;

		$pageid = layers_create_builder_page( esc_attr( $_POST[ 'post_title' ] . ' ' . __( '(Copy)' , 'layerswp' ) ) );

		// Create the page sidebar on the fly
		Layers_Widgets::register_builder_sidebar( $pageid );

		// Set the page ID
		$import_data[ 'post_id' ] = $pageid;

		$post = get_post( $_POST[ 'post_id' ] );

		// Set the Widget Data for import
		$import_data[ 'widget_data' ] = $this->export_data( $post );

		// Run data import
		$import_progress = $this->import( $import_data );

		$results = array(
			'post_id' => $pageid,
			'data_report' => $import_progress,
			'page_location' => admin_url() . 'post.php?post=' . $pageid . '&action=edit&message=1'
		);

		die( json_encode( $results ) );

	}

	/**
	* Ajax Update Builder Page details
	*
	* This function will update the builder page with a new page title and more (once we add more)
	*/

	public function update_builder_page(){

		// We need a page title and post ID for this to work
		if( !isset( $_POST[ 'post_title' ] ) || !isset( $_POST[ 'post_id' ]  ) ) return;

		$pageid = layers_create_builder_page( esc_attr( $_POST[ 'post_title' ] ), esc_attr( $_POST[ 'post_id' ] ) );

		die( $pageid );
	}

	/**
	* Ajax Create a Builder Page from a preset page
	*
	* This function takes on the Preset Page Data and runs the import() function
	*/

	public function create_builder_page_from_preset_ajax() {

		if( !check_ajax_referer( 'layers-migrator-preset-layouts', 'nonce', false ) ) die( 'You threw a Nonce exception' ); // Nonce

		die( json_encode( $this->create_builder_page_from_preset( array(
			'post_title' => ( isset( $_POST[ 'post_title' ] ) ) ? $_POST[ 'post_title' ] : NULL ,
			'widget_data' => ( isset( $_POST[ 'widget_data' ] ) ) ? $_POST[ 'widget_data' ] : NULL ,
		) ) ) );
	}
	
	/**
	* Ajax Create a Builder Page from a preset page
	*
	* This function takes on the Preset Page Data and runs the import() function
	*/

	public function create_builder_page_from_preset( $args = array() ){
		global $layers_widgets;
		
		$defaults = array(
			'post_id'                         => '', //@TODO: allow an id to be passed, then have one function that deals with all kind of preset-layout imports
			'post_title'                      => '',
			'widget_data'                     => '',
			'create_new_image_if_name_exists' => FALSE,
			'download_images'                 => TRUE,
		);
		$args = wp_parse_args( $args, $defaults );

		$check_builder_pages = layers_get_builder_pages();

		if( isset( $args[ 'post_title' ] ) ) {
			$post_title = $args[ 'post_title' ];
		} else {
			$post_title = __( 'Home Page' , 'layerswp' );
		}

		// Generate builder page and return page ID
		$import_data[ 'post_id' ] = layers_create_builder_page( $post_title );
		$new_page = get_post( $import_data[ 'post_id' ] );

		// Register Builder Sidebar
		$layers_widgets->register_builder_sidebar( $import_data[ 'post_id' ] );

		// Add Widget Data to the import array
		if( isset( $args[ 'widget_data' ] ) ) {
			$import_data[ 'widget_data' ] = $args[ 'widget_data' ];
		} else {
			$import_data[ 'widget_data' ] = NULL;
		}

		// Run data import
		$import_progress = $this->import( $import_data, array(
			'create_new_image_if_name_exists' => $args['create_new_image_if_name_exists'],
			'download_images'                 => $args['download_images'],
		) );

		if( count( $check_builder_pages ) == 0 ){
			update_option( 'page_on_front', $import_data[ 'post_id' ] );
			update_option( 'show_on_front', 'page' );
		}

		$results = array(
			'post_id' => $import_data[ 'post_id' ],
			'post_title' => $new_page->post_title,
			'data_report' => $import_progress,
			'customizer_location' => admin_url() . 'customize.php?url=' . esc_url( get_the_permalink( $import_data[ 'post_id' ] ) )
		);

		return $results;
	}
	
	/**
	*  Import
	*/

	public function import( $import_data = NULL, $args = array() ) {

		if( NULL == $import_data ) return;

		global $wp_registered_sidebars;
		
		$defaults = array(
			'create_new_image_if_name_exists' => FALSE,
			'download_images'                 => TRUE,
		);
		$args = wp_parse_args( $args, $defaults );

		// Get all available widgets site supports
		$available_widgets = $this->available_widgets();

		// Get all existing widget instances
		$widget_instances = array();
		foreach ( $available_widgets as $widget_data ) {
			$widget_instances[ $widget_data[ 'id_base' ] ] = get_option( 'widget_' . $widget_data['id_base'] );
		}

		// Begin results
		$results = array();

		if( empty( $import_data[ 'widget_data' ] ) ) return;

		foreach( $import_data[ 'widget_data' ] as $sidebar_id => $sidebar_data ) {

			// If this is a builder page, set the ID to the current page we are importing INTO
			if( FALSE !== strpos( $sidebar_id , 'obox-layers-builder-' ) ) $sidebar_id = 'obox-layers-builder-' . $import_data[ 'post_id' ];

			// Check if sidebar is available on this site
			// Otherwise add widgets to inactive, and say so
			if ( isset( $wp_registered_sidebars[$sidebar_id] ) ) {
				$sidebar_available = true;
				$use_sidebar_id = $sidebar_id;
				$sidebar_message_type = 'success';
				$sidebar_message = '';
			} else {
				$sidebar_available = false;
				$use_sidebar_id = 'wp_inactive_widgets'; // add to inactive if sidebar does not exist in theme
				$sidebar_message_type = 'error';
				$sidebar_message = __( 'Sidebar does not exist in theme (using Inactive)' , 'layerswp' );
			}

			// Result for sidebar
			$results[$sidebar_id]['name'] = ! empty( $wp_registered_sidebars[$sidebar_id]['name'] ) ? $wp_registered_sidebars[$sidebar_id]['name'] : $sidebar_id; // sidebar name if theme supports it; otherwise ID
			$results[$sidebar_id]['message_type'] = $sidebar_message_type;
			$results[$sidebar_id]['message'] = $sidebar_message;
			$results[$sidebar_id]['widgets'] = array();

			// Loop widgets
			foreach ( $sidebar_data as $widget_instance_id => $widget ) {
				
				// Check for and import images
				foreach ( $widget as $option => $widget_data ){
					$widget[ $option ] = $this->import_images( $widget_data, $args );
				}

				$fail = false;

				// Get id_base (remove -# from end) and instance ID number
				$id_base = preg_replace( '/-[0-9]+$/', '', $widget_instance_id );
				$instance_id_number = str_replace( $id_base . '-', '', $widget_instance_id );

				// Does widget with identical settings already exist in same sidebar?
				if ( ! $fail && isset( $widget_instances[$id_base] ) ) {

					// Get existing widgets in this sidebar
					$sidebars_widgets = get_option( 'sidebars_widgets' );
					$sidebar_widgets = isset( $sidebars_widgets[$use_sidebar_id] ) ? $sidebars_widgets[$use_sidebar_id] : array(); // check Inactive if that's where will go

					// Loop widgets with ID base
					$single_widget_instances = ! empty( $widget_instances[$id_base] ) ? $widget_instances[$id_base] : array();
					foreach ( $single_widget_instances as $check_id => $check_widget ) {

						// Is widget in same sidebar and has identical settings?
						if ( in_array( "$id_base-$check_id", $sidebar_widgets ) && (array) $widget == $check_widget ) {

							$fail = true;
							$widget_message_type = 'warning';
							$widget_message = __( 'Widget already exists' , 'layerswp' ); // explain why widget not imported

							break;

						}

					}

				}

				// No failure
				if ( ! $fail ) {

					// Add widget instance
					$single_widget_instances = get_option( 'widget_' . $id_base ); // all instances for that widget ID base, get fresh every time
					$single_widget_instances = ! empty( $single_widget_instances ) ? $single_widget_instances : array( '_multiwidget' => 1 ); // start fresh if have to
					$single_widget_instances[] = (array) $widget; // add it

						// Get the key it was given
						end( $single_widget_instances );
						$new_instance_id_number = key( $single_widget_instances );

						// If key is 0, make it 1
						// When 0, an issue can occur where adding a widget causes data from other widget to load, and the widget doesn't stick (reload wipes it)
						if ( '0' === strval( $new_instance_id_number ) ) {
							$new_instance_id_number = 1;
							$single_widget_instances[$new_instance_id_number] = $single_widget_instances[0];
							unset( $single_widget_instances[0] );
						}

						// Move _multiwidget to end of array for uniformity
						if ( isset( $single_widget_instances['_multiwidget'] ) ) {
							$multiwidget = $single_widget_instances['_multiwidget'];
							unset( $single_widget_instances['_multiwidget'] );
							$single_widget_instances['_multiwidget'] = $multiwidget;
						}

						// Update option with new widget
						update_option( 'widget_' . $id_base, $single_widget_instances );

					// Assign widget instance to sidebar
					$sidebars_widgets = get_option( 'sidebars_widgets' ); // which sidebars have which widgets, get fresh every time
					$new_instance_id = $id_base . '-' . $new_instance_id_number; // use ID number from new widget instance
					$sidebars_widgets[$use_sidebar_id][] = $new_instance_id; // add new instance to sidebar
					update_option( 'sidebars_widgets', $sidebars_widgets ); // save the amended data

					// Success message
					if ( $sidebar_available ) {
						$widget_message_type = 'success';
						$widget_message = __( 'Imported' , 'layerswp' );
					} else {
						$widget_message_type = 'warning';
						$widget_message = __( 'Imported to Inactive' , 'layerswp' );
					}

				}
				// Result for widget instance
				$results[$sidebar_id]['widgets'][$widget_instance_id]['name'] = isset( $available_widgets[$id_base]['name'] ) ? $available_widgets[$id_base]['name'] : $id_base; // widget name or ID if name not available (not supported by site)
				$results[$sidebar_id]['widgets'][$widget_instance_id]['title'] = isset( $widget->title ) ? $widget->title : __( 'No Title' , 'layerswp' ); // show "No Title" if widget instance is untitled
				$results[$sidebar_id]['widgets'][$widget_instance_id]['message_type'] = $widget_message_type;
				$results[$sidebar_id]['widgets'][$widget_instance_id]['message'] = $widget_message;

			}
		}

		return $results;
	}
	
}

if( !function_exists( 'layers_builder_export_init' ) ) {
	function layers_builder_export_init(){
		global $pagenow, $post;

		// Make sure we're on the post edit screen
		if( 'post.php' != $pagenow ) return;

		// Make sure we're editing a post
		if( 'page' != get_post_type( $post->ID ) || 'builder.php' != basename( get_page_template() ) ) return;

		$layers_migrator = new Layers_Widget_Migrator();
		$layers_migrator->init();

	}
}
add_action( 'admin_head' , 'layers_builder_export_init', 10 );

if( !function_exists( 'layers_builder_export_ajax_init' ) ) {
	function layers_builder_export_ajax_init(){
		$layers_migrator = new Layers_Widget_Migrator();

		add_action( 'wp_ajax_layers_import_widgets', array( $layers_migrator, 'do_ajax_import' ) );
		add_action( 'wp_ajax_layers_create_builder_page_from_preset_ajax', array( $layers_migrator, 'create_builder_page_from_preset_ajax' ) );
		add_action( 'wp_ajax_layers_update_builder_page', array( $layers_migrator, 'update_builder_page' ) );
		add_action( 'wp_ajax_layers_duplicate_builder_page', array( $layers_migrator, 'do_ajax_duplicate' ) );
	}
}
add_action( 'init' , 'layers_builder_export_ajax_init' );


/**
*  Simple output of a JSON'd string of the widget data
*/

function layers_create_export_file(){

	$layers_migrator = new Layers_Widget_Migrator();

	// Make sur a post ID exists or return
	if( !isset( $_GET[ 'post' ] ) ) return;

	// Get the post ID
	$post_id = $_GET[ 'post' ];

	$post = get_post( $post_id );

	$widget_data = json_encode( $layers_migrator->export_data( $post ) );

	$filesize = strlen( $widget_data );

	// Headers to prompt "Save As"
	header( 'Content-Type: application/json' );
	header( 'Content-Disposition: attachment; filename=' . $post->post_name .'-' . date( 'Y-m-d' ) . '.json' );
	header( 'Expires: 0' );
	header( 'Cache-Control: must-revalidate' );
	header( 'Pragma: public' );
	header( 'Content-Length: ' . $filesize );

	// Clear buffering just in case
	@ob_end_clean();
	flush();

	// Output file contents
	die( $widget_data );

	// Stop execution
	wp_redirect( admin_url( 'post.php?post=' . $post->ID . '&action=edit'  ) );

}
if( isset( $_GET[ 'layers-export' ] ) ) {
	add_action( 'init' , 'layers_create_export_file' );
}
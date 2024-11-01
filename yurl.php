<?php
/*
 Plugin Name: yURL ReTwitt
 Plugin URI: http://www.php-experts.org/wordpress/plugin-yurl-retwitt/
 Description: Fetch, store (cache) and display, a tiny-version of the URL of each post. Allow your users to re-twitt it in one click. You can specify the RT default text. Can be quite handy if you're using Twitter :)
 Author: Didier Sampaolo
 Version: 1.4
 Author URI: http://www.php-experts.org/
 */

/*
 * Déclaration
 */
if (!class_exists("yURL")) {
	class yURL {
		/**
		 * Construct (/!\ PHP 5 /!\)
		 */
		public function __construct() {
			/* Actions */				
			add_action('admin_menu', array(&$this, 'action_AdminMenu'));
			add_action('publish_post', array($this, 'store_yurl'));
		}

		/*
		 * construct php4
		 */
		function yURL() {
			$this->__construct();
		}
		function show_rt_link() { 
			$yurl = unserialize(get_option('yurl'));
			
			if ($yurl['link_enable'] == '1') {
				$id = get_the_ID();
				$yurl['yurl'] = get_post_custom_values('yurl');
				
				if ($yurl['yurl'] == NULL) {
					$this->store_yurl($id);
					$yurl['yurl'] = get_post_custom_values('yurl',$id);
				}
				$yurl['yurl'] = $yurl['yurl'][0]; // tricky, but works just fine
				$yurl['title'] = get_the_title();
				
				$search = array('[LOGIN]','[TITLE]','[YURL]');
				$replace = array($yurl['login'], $yurl['title'], $yurl['yurl']); 

				$yurl['href'] = 'http://twitter.com/home?status='.urlencode(str_replace($search, $replace, $yurl['syntax']));
				echo '<a rel="nofollow" href="'.$yurl['href'].'">'.$yurl['anchor'].'</a>';
			}
		}
		
		/*
		 * action qui stocke la yURL quand on publie
		 */
		function store_yurl($id) {
			// get yURL according to post ID
			$yurl = file_get_contents("http://yurl.fr/create.php?url=".get_permalink($id));

			// save yurl
			$unique = true;
			add_post_meta($id, "yurl", $yurl, $unique);
		}

		/**
		 * Register l'action qui ajoute la page d'amin "yURL"
		 */
		public function action_AdminMenu() {
			add_options_page("yURL/Twitter Settings", "yURL", 1, "yurl", array(&$this, "do_AdminMenu"));
		}
		/**
		 * Appel du code de la page d'admin
		 */
		public function do_AdminMenu() {
			include("yurl_admin.php");
		}
	}
}
/*
 * Instanciation
 */
if (class_exists("yURL")) {
	$yURL = new yURL();
}
/*
 * template function
 */
function yurl_show() {
	global $yURL;
	if (isset($yURL)) {
		
		$yURL->show_rt_link();
	} 
}
?>
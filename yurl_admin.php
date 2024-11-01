<?php
global $wpdb;
if (isset($_POST['yurl_submit'])) {
		
	// saving :)
	$yurl['login'] = $_POST['yurl_login'];
	$yurl['anchor'] = $_POST['yurl_anchor'];
	$yurl['syntax'] = $_POST['yurl_syntax'];
	$yurl['link_enable'] = isset($_POST['yurl_link_enable']) ? '1' : 0;
	
	add_option('yurl',serialize($yurl));
	$option_name = "yurl";
	if ( !get_option($option_name) ) {
	    // register values (first time)
	    add_option($option_name, serialize($$option_name));  
	} else {
	  	//update values
	    update_option($option_name, serialize($$option_name));
	  }
	unset($yurl);
}

// loading
$yurl = get_option('yurl');
if (!is_array($yurl)) { 
	$yurl = unserialize($yurl); 
}

if ($yurl === false) {
	$yurl['anchor'] = "ReTwitt this post";
	$yurl['syntax'] = "RT @[LOGIN] : [TITLE] [YURL]";
	 add_option($option_name, serialize("yurl"));  
}

?>
<div class="wrap">
	<h2>yURL Options</h2>
	In order for this plugin to work, you have to give your Twitter login (login only). This will enable a link which will look like "Re-Twitt this post"<br />
The RT will look like "RT @[LOGIN] : [TITLE] [YURL]"<br />
	<br />
	<form method="post" name="yurl_settings">
	<input type="checkbox" name="yurl_link_enable" <?php if ($yurl['link_enable'] == '1') { echo 'checked'; } ?>/> Show a link under my post, allowing my readers to easily re-twitt it<br />
	Login : <input type="text" name="yurl_login" value="<?php echo $yurl['login']; ?>" /> <br />
	Link anchor : <input type="text" name="yurl_anchor" value="<?php echo $yurl['anchor']; ?>" /> <br />
	Re-Twit syntax :<input size="80" type="text" name="yurl_syntax" value="<?php echo $yurl['syntax']; ?>" /> <br />
	(<em>You can use whichever syntax you would like. [VARIABLES] will be replaced by their values.</em>)
	<br />
	Don't forget to add "<code><?php yurl_show(); ?></code>" anywhere in your template to show the link.
</div>
<div class="submit"><input type="submit" name="yurl_submit" value="Save" /></div>
</form>
</div>
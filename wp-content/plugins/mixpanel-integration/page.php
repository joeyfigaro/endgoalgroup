<?php
	add_action( 'wp_head', array('MixPanel','insert_tracker' ));
	add_action( 'wp_footer', array('MixPanel','insert_event' ));

  	class MixPanel {

    /*
    * Gets the value of the key mixpanel_event_label for this specific Post
    * @return string the value of the meta box set on the page
    */
    static function get_post_event_label() {
		global $post;
		return get_post_meta( $post->ID, 'mixpanel_event_label', true );
    }

    /*
    * Inserts the value for the mixpanel.track() API Call
    * @return boolean technically this should be html..
    */
    function insert_event() {
		$event_label = self::get_post_event_label();
		$settings = (array) get_option( 'mixpanel_settings' );

		if(!isset($settings['token_id'])) {
			self::no_mixpanel_token_found();
			return false;
		}

		echo "<script type='text/javascript'>
		var rightNow = new Date();
		var humanDate = rightNow.toDateString();

		mixpanel.register_once({
			'first_wp_page': document.title,
			'first_wp_contact': humanDate
		});
		mixpanel.track(\"Viewed Page\", {
			'Page Name': ";
		$event_label == "" ? $page_name = "document.title" : $page_name = "'$event_label'";
		echo $page_name;
		echo ", 'Page URL': window.location.pathname
		});
		</script>";

      return true;
    }

    /**
    * Adds the Javascript necessary to start tracking via MixPanel.
    * this gets added to the <head> section usually.
    *
    * @return [type] [description]
    */
    function insert_tracker() {
		$settings = (array) get_option( 'mixpanel_settings' );
		if(!isset($settings['token_id'])) {
		self::no_mixpanel_token_found();
		return false;
    }

    require_once dirname(__FILE__) . '/mixpaneljs.php';
    return true;
    }

    static function no_mixpanel_token_found() {
		echo "<!-- No MixPanel Token Defined -->";
    }
  }
?>

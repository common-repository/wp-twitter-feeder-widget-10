<?php
/*
Plugin Name: WP Twitter Feeder Widget 1.0

Version: 1.6.2

Plugin URI: http://pooks.com/extensions/wordpress.html

Description: Show your latest tweets in the sidebar of your wordpress blog. Very easy to use and setup. This is one of the simplest widgets for Wordpress to display your latest tweets to the world. Download it now and start showing your latest tweets in minutes.

Author: Tony Alford

Author URI: http://pooks.com/extensions/wordpress.html

*/

/* 
    Copyright 2010  TonY Alford (info@pooks.com)
	
    This program is free software; you can redistribute it and/or modify	
	
    it under the terms of the GNU General Public License as published by
	
    the Free Software Foundation; either version 2 of the License, or	
	
    (at your option) any later version.
	
	************************************************************************
    This program is distributed in the hope that it will be useful,	
	
    but WITHOUT ANY WARRANTY; without even the implied warranty of	
	
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the	
	
    GNU General Public License for more details.
	
   *************************************************************************
   
    You should have received a copy of the GNU General Public License	
	
    along with this program; if not, write to the Free Software	
	
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * TwitterFeederWidget Class
 */
 
class TwitterFeederWidget extends WP_Widget {
	private /** @type {string} */ $languagePath;
	
    /** constructor */
	
    function TwitterFeederWidget() {
		$this->languagePath = basename(dirname(__FILE__)).'/languages';
		
        load_plugin_textdomain('tfw', 'false', $this->languagePath);
		
		$this->options = array(
			array(
				'label' => __( 'Twitter Authentication options', 'tfw' ),
				'type'	=> 'separator', 	'notes' => __('You will need to create an API account using your twitter login information and generate the keys needed for this plugin to work. You can do this by going here', 'tfw' ).' <a href="https://dev.twitter.com/apps" target="_blank">'.__('here', 'tfw' ).'</a><br /><br />'	),
			array(
				'name'	=> 'consumer_key',	'label'	=> 'Consumer Key',
				
				'type'	=> 'text',	'default' => ''			),
				
			array(
				'name'	=> 'consumer_secret',	'label'	=> 'Consumer Secret',
				
				'type'	=> 'text',	'default' => ''			),
				
			array(
				'name'	=> 'access_token',	'label'	=> 'Access Token',
				
				'type'	=> 'text',	'default' => ''			),
				
			array(
				'name'	=> 'access_token_secret',	'label'	=> 'Access Token Secret',
				
				'type'	=> 'text',	'default' => ''			),
				
			array(
				'label' => __( 'Twitter data options', 'tfw' ),
				
				'type'	=> 'separator'			),
				
			array(
				'name'	=> 'username',		'label'	=> __( 'Twitter Username', 'tfw' ),
				
				'type'	=> 'text',	'default' => ''			),
				
			array(
				'name'	=> 'num',			'label'	=> __( 'Show # of Tweets', 'tfw' ),
				
				'type'	=> 'text',	'default' => '5'			),
				
			array(
				'name'	=> 'skip_text',		'label'	=> __( 'Skip tweets containing this text', 'tfw' ),
				
				'type'	=> 'text',	'default' => ''			),
				
			array(
				'name'	=> 'skip_replies',		'label'	=> __( 'Skip replies', 'tfw' ),
				
				'type'	=> 'checkbox',	'default' => true	),
				
			array(
				'name'	=> 'skip_retweets',		'label'	=> __( 'Skip retweets', 'tfw' ),
				
				'type'	=> 'checkbox',	'default' => false	),
				
			array(
				'label' => __( 'Widget title options', 'tfw' ),
				
				'type'	=> 'separator'			),
				
			array(
				'name'	=> 'title',	'label'	=> __( 'Title', 'tfw' ),
				
				'type'	=> 'text',	'default' => __( 'Last Tweets', 'tfw' )			),
				
			array(
				'name'	=> 'title_icon',	'label'	=> __( 'Show Twitter icon on title', 'tfw' ),
				
				'type'	=> 'checkbox',	'default' => false			),
				
			array(
				'name'	=> 'link_title',	'label'	=> __( 'Link above Title with Twitter user', 'tfw' ),
				
				'type'	=> 'checkbox',	'default' => false			),
				
			array(			
				'label' => __( 'Links and display options', 'tfw' ),
				
				'type'	=> 'separator'			),
				
			array(
				'name'	=> 'linked',    'label'	=> __( 'Show this linked text at the end of each Tweet', 'tfw' ),
				
				'type'	=> 'text',	'default' => ''			),
				
			array(
				'name'	=> 'update',	'label'	=> __( 'Show timestamps', 'tfw' ),
				
				'type'	=> 'checkbox',	'default' => true			),
				
			array(
				'name'	=> 'thumbnail',	'label'	=> __( 'Include thumbnail before tweets', 'tfw' ),
				
				'type'	=> 'checkbox',	'default' => false			),		
				
			array(
				'name'	=> 'thumbnail_retweets',	'label'	=> __( 'Use author thumb for retweets', 'tfw' ),
				
				'type'	=> 'checkbox',	'default' => false			),	
				
			array(
				'name'	=> 'hyperlinks',	'label'	=> __( 'Find and show hyperlinks', 'tfw' ),
				
				'type'	=> 'checkbox',	'default' => true			),
				
			array(
				'name'	=> 'replace_link_text',	'label'	=> __( 'Replace hyperlinks text inside tweets with fixed text (e.g. "-->")', 'tfw' ),
				
				'type'	=> 'text',	'default' => ''			),
				
			array(
				'name'	=> 'twitter_users',	'label'	=> __( 'Find Replies in Tweets', 'tfw' ),
				
				'type'	=> 'checkbox',	'default' => true			),
				
			array(
				'name'	=> 'link_target_blank',	'label'	=> __( 'Create links on new window / tab', 'tfw' ),
				
				'type'	=> 'checkbox',	'default' => false			),
				
			array(
				'label' => __( 'Widget footer options', 'tfw' ),
				
				'type'	=> 'separator'			),
				
			array(
				'name'	=> 'link_user',		'label'	=> __( 'Show a footer link to the Twitter user profile', 'tfw' ),
				
				'type'	=> 'checkbox',	'default' => false			),
				
			array(
				'name'	=> 'add_credits',		'label'	=> __( 'Show credits in the footer of your plugin', 'tfw' ),
				
				'type'	=> 'checkbox',	'default' => true			),
				
			array(
				'name'	=> 'link_user_text',	'label'	=> __( 'Text for footer link', 'tfw' ),
				
				'type'	=> 'text',	'default' => 'Follow me on Twitter'			),
				
			array(
				'label' => __( 'Debug options', 'tfw' ),
				
				'type'	=> 'separator'			),
				
			array(
				'name'	=> 'debug',	'label'	=> __( 'Show debug info', 'tfw' ),
				
				'type'	=> 'checkbox',	'default' => false			),
				
			array(
				'name'	=> 'erase_cached_data',	'label'	=> __( 'Erase cached data (use it only for a few minutes, when having issues)', 'tfw' ),
				
				'type'	=> 'checkbox',	'default' => false			),
				
			array(
				'name'	=> 'encode_utf8',	'label'	=> __( 'Force UTF8 Encode (use it only if having issues)', 'tfw' ),
				
				'type'	=> 'checkbox',	'default' => false			),
				
		);
		
        $control_ops = array('width' => 400);
		
        parent::WP_Widget(false, 'Twitter Feeder Widget', array(), $control_ops);	
		
    }
	
    /** @see WP_Widget::widget */
	
    function widget($args, $instance) {		
		extract( $args );
		
		$title = apply_filters('widget_title', $instance['title']);
		
		echo $before_widget;  
		
		if ( $title != '') {
			echo $before_title;
			
			$title_icon = ($instance['title_icon']) ? '<img src="'.WP_PLUGIN_URL.'/'.basename(dirname(__FILE__)).'/twitter_small.png" alt="'.$title.'" title="'.$title.'" /> ' : '';
			
			if ( $instance['link_title'] === true ) {
				$link_target = ($instance['link_target_blank']) ? ' target="_blank" ' : '';
				
				echo '<a href="http://twitter.com/' . $instance['username'] . '" class="twitter_title_link" '.$link_target.'>'. $title_icon . $title . '</a>';
				
			} else {
				echo $title_icon . $title;
				
			}
			
			echo $after_title;
			
		}
		
		echo $this->twitter_feeder_messages($instance);
		
		echo $after_widget;
		
    }
	
    /** @see WP_Widget::update */
	
    function update($new_instance, $old_instance) {		
		$instance = $old_instance;
		
		foreach ($this->options as $val) {
			if ($val['type']=='text') {
				$instance[$val['name']] = strip_tags($new_instance[$val['name']]);
				
			} else if ($val['type']=='checkbox') {
				$instance[$val['name']] = ($new_instance[$val['name']]=='on') ? true : false;
				
			}
			
		}
		
        return $instance;
		
    }
	
    /** @see WP_Widget::form */
	
    function form($instance) {
		if (empty($instance)) {
			foreach ($this->options as $val) {
				if ($val['type']=='separator') {
					continue;
				}
				$instance[$val['name']] = $val['default'];
			}
		}				
		
		// CHECK AUTHORIZATION
		
		if (!function_exists('curl_init')) {
			echo __('CURL extension not found. You need enable it to use this Widget');
			return;
			
		}
		
		foreach ($this->options as $val) {
			if ($val['type']=='separator') {
				if ($val['label']!='') {
					echo '<h3>'.$val['label'].'</h3>';
					
				} else {
					echo '<hr />';
				}
				if ($val['notes']!='') {
					echo '<span class="description">'.$val['notes'].'</span>';
				}
			} else if ($val['type']=='text') {
				$label = '<label for="'.$this->get_field_id($val['name']).'">'.$val['label'].'</label>';
				
				echo '<p>'.$label.'<br />';
				
				echo '<input class="widefat" id="'.$this->get_field_id($val['name']).'" name="'.$this->get_field_name($val['name']).'" type="text" value="'.esc_attr($instance[$val['name']]).'" /></p>';
				
			} else if ($val['type']=='checkbox') {
				$label = '<label for="'.$this->get_field_id($val['name']).'">'.$val['label'].'</label>';
				
				$checked = ($instance[$val['name']]) ? 'checked="checked"' : '';
				
				echo '<input id="'.$this->get_field_id($val['name']).'" name="'.$this->get_field_name($val['name']).'" type="checkbox" '.$checked.' /> '.$label.'<br />';
			}
		}
	}
	protected function debug ($options, $text) {
		if ($options['debug']) {
			echo $text."\n";
		}
	}
	
	// Display Twitter messages
	
	protected function twitter_feeder_messages($options) {
		// CHECK OPTIONS
		if ($options['username'] == '') {
			return __('Twitter username is not configured','tfw');
		} 
		if (!is_numeric($options['num']) or $options['num']<=0) {
			return __('Number of tweets is not valid','tfw');
			
		}
		
		if ($options['consumer_key'] == '' or $options['consumer_secret'] == '' or $options['access_token'] == '' or $options['access_token_secret'] == '') {
			return __('Twitter Authentication data is incomplete','tfw');
			
		} 
		
		if (!class_exists('Codebird')) {
			require ('lib/codebird.php');
			
		}
		
		Codebird::setConsumerKey($options['consumer_key'], $options['consumer_secret']); 
		
		$this->cb = Codebird::getInstance();	
		
		$this->cb->setToken($options['access_token'], $options['access_token_secret']);
		
		// From Codebird documentation: For API methods returning multiple data (like statuses/home_timeline), you should cast the reply to array
		
		$this->cb->setReturnFormat(CODEBIRD_RETURNFORMAT_ARRAY);
		
		// SET THE NUMBER OF ITEMS TO RETRIEVE - IF "SKIP TEXT" IS ACTIVE, GET MORE ITEMS
		
		$max_items_to_retrieve = $options['num'];
		
		if ($options['skip_text']!='' or $options['skip_replies'] or $options['skip_retweets']) {
			$max_items_to_retrieve *= 3;
			
		}
		
		// TWITTER API GIVES MAX 200 TWEETS PER REQUEST
		
		if ($max_items_to_retrieve>200) {
			$max_items_to_retrieve = 200;
			
		}
		
		$transient_name = 'twitter_data_'.$options['username'].$options['skip_text'].'_'.$options['num'];
		
		if ($options['erase_cached_data']) {
			$this->debug($options, '<!-- '.__('Fetching data from Twitter').'... -->');
			
			$this->debug($options, '<!-- '.__('Erase cached data option enabled').'... -->');
			
			delete_transient($transient_name);
			
			delete_transient($transient_name.'_status');
			
			delete_option($transient_name.'_valid');
			
			try {
				$twitter_data =  $this->cb->statuses_userTimeline(array(
							'screen_name'=>$options['username'], 
							'count'=>$max_items_to_retrieve,
							'exclude_replies'=>$options['skip_replies'],
							'include_rts'=>(!$options['skip_retweets'])
					));
					
			} catch (Exception $e) { return __('Error retrieving tweets','tfw'); }
			if (isset($twitter_data['errors'])) {
				$this->debug($options, __('Twitter data error:','tfw').' '.$twitter_data['errors'][0]['message'].'<br />');
			}
		} else {
		
			// USE TRANSIENT DATA, TO MINIMIZE REQUESTS TO THE TWITTER FEED
			
			$timeout = 10 * 60; //10m
			$error_timeout = 5 * 60; //5m
			$twitter_data = get_transient($transient_name);
			
			$twitter_status = get_transient($transient_name.'_status');
			
			// Twitter Status
			
			if(!$twitter_status || !$twitter_data) {
				try {
					$twitter_status = $this->cb->application_rateLimitStatus();
					
					set_transient($transient_name."_status", $twitter_status, $error_timeout);
					
				} catch (Exception $e) { 
					$this->debug($options, __('Error retrieving twitter rate limit').'<br />');
					
				}
				
			}
			
			// Tweets
			
			if (empty($twitter_data) or count($twitter_data)<1 or isset($twitter_data['errors'])) {
				$calls_limit   = (int)$twitter_status['resources']['statuses']['/statuses/user_timeline']['limit'];
				
				$remaining     = (int)$twitter_status['resources']['statuses']['/statuses/user_timeline']['remaining'];
				
				$reset_seconds = (int)$twitter_status['resources']['statuses']['/statuses/user_timeline']['reset']-time();
				
				$this->debug($options, '<!-- '.__('Fetching data from Twitter').'... -->');
				
				$this->debug($options, '<!-- '.__('Requested items').' : '.$max_items_to_retrieve.' -->');
				
				$this->debug($options, '<!-- '.__('API calls left').' : '.$remaining.' of '.$calls_limit.' -->');
				
				$this->debug($options, '<!-- '.__('Seconds until reset').' : '.$reset_seconds.' -->');
				
				if($remaining <= 7 and $reset_seconds >0) {
				    $timeout       = $reset_seconds;
					
				    $error_timeout = $reset_seconds;
					
				}
				
				try {
					$twitter_data =  $this->cb->statuses_userTimeline(array(
							'screen_name'=>$options['username'], 
							'count'=>$max_items_to_retrieve, 
							'exclude_replies'=>$options['skip_replies'],
							'include_rts'=>(!$options['skip_retweets'])
						));
						
				} catch (Exception $e) { return __('Error retrieving tweets','tfw'); }
				if(!isset($twitter_data['errors']) and (count($twitter_data) >= 1) ) {
				    set_transient($transient_name, $twitter_data, $timeout);
					
				    update_option($transient_name."_valid", $twitter_data);
					
				} else {
				    set_transient($transient_name, $twitter_data, $error_timeout);	
					
					// Wait 5 minutes before retry
					
					if (isset($twitter_data['errors'])) {
						$this->debug($options, __('Twitter data error:','tfw').' '.$twitter_data['errors'][0]['message'].'<br />');
						
					}
					
				}
				
			} else {
				$this->debug($options, '<!-- '.__('Using cached Twitter data').'... -->');
				
				if(isset($twitter_data['errors'])) {
					$this->debug($options, __('Twitter cache error:','tfw').' '.$twitter_data['errors'][0]['message'].'<br />');
					
				}
				
			}
			
			if (empty($twitter_data) and false === ($twitter_data = get_option($transient_name."_valid"))) {
			    return __('No public tweets','tfw');
				
			}
			
			if (isset($twitter_data['errors'])) {
			
				// STORE ERROR FOR DISPLAY
				
				$twitter_error = $twitter_data['errors'];
				
			    if(false === ($twitter_data = get_option($transient_name."_valid"))) {
					$debug = ($options['debug']) ? '<br /><i>Debug info:</i> ['.$twitter_error[0]['code'].'] '.$twitter_error[0]['message'].' - username: "'.$options['username'].'"' : '';
					
				    return __('Unable to get tweets'.$debug,'tfw');
					
				}
			}
		}
		
		if (empty($twitter_data) or count($twitter_data)<1) {
		    return __('No public tweets','tfw');
			
		}
		
		$link_target = ($options['link_target_blank']) ? ' target="_blank" ' : '';
		
		$out = '<ul class="twitter_feeder_widget">';
		
		$i = 0;
		
		foreach($twitter_data as $message) {
		
			// CHECK THE NUMBER OF ITEMS SHOWN
			
			if ($i>=$options['num']) {
				break;
				
			}
			
			$msg = $message['text'];
			
			// RECOVER ORIGINAL MESSAGE FOR RETWEETS
			
			if (count($message['retweeted_status'])>0) {
				$msg = 'RT @'.$message['retweeted_status']['user']['screen_name'].': '.$message['retweeted_status']['text'];
				
				if ($options['thumbnail_retweets']) {
					$message = $message['retweeted_status'];
					
				}
			}
			if ($msg=='') {			
				continue;
			}
			if ($options['skip_text']!='' and strpos($msg, $options['skip_text'])!==false) {
				continue;
			}
			if($options['encode_utf8']) $msg = utf8_encode($msg);
			
			$out .= '<li>';
			
			// TODO: LINK
			
			if ($options['thumbnail'] and $message['user']['profile_image_url_https']!='') {
				$out .= '<img src="'.$message['user']['profile_image_url_https'].'" />';
			}
			if ($options['hyperlinks']) {
				if ($options['replace_link_text']!='') {
					// match protocol://address/path/file.extension?some=variable&another=asf%
					$msg = preg_replace('/\b([a-zA-Z]+:\/\/[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"$1\" class=\"twitter-link\" ".$link_target." title=\"$1\">".$options['replace_link_text']."</a>", $msg);
					// match www.something.domain/path/file.extension?some=variable&another=asf%
					$msg = preg_replace('/\b(?<!:\/\/)(www\.[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"http://$1\" class=\"twitter-link\" ".$link_target." title=\"$1\">".$options['replace_link_text']."</a>", $msg);  
				} else {
					// match protocol://address/path/file.extension?some=variable&another=asf%
					$msg = preg_replace('/\b([a-zA-Z]+:\/\/[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"$1\" class=\"twitter-link\" ".$link_target.">$1</a>", $msg);
					// match www.something.domain/path/file.extension?some=variable&another=asf%
					$msg = preg_replace('/\b(?<!:\/\/)(www\.[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"http://$1\" class=\"twitter-link\" ".$link_target.">$1</a>", $msg); 
				}
				// match name@address
				$msg = preg_replace('/\b([a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]*\@[a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]{2,6})\b/i',"<a href=\"mailto://$1\" class=\"twitter-link\" ".$link_target.">$1</a>", $msg);
				//NEW mach #trendingtopics
				//$msg = preg_replace('/#([\w\pL-.,:>]+)/iu', '<a href="http://twitter.com/#!/search/\1" class="twitter-link">#\1</a>', $msg);
				//NEWER mach #trendingtopics
				$msg = preg_replace('/(^|\s)#(\w*[a-zA-Z_]+\w*)/', '\1<a href="http://twitter.com/#!/search/%23\2" class="twitter-link" '.$link_target.'>#\2</a>', $msg);
			}
			if ($options['twitter_users'])  { 
				$msg = preg_replace('/([\.|\,|\:|\¡|\¿|\>|\{|\(]?)@{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', "$1<a href=\"http://twitter.com/$2\" class=\"twitter-user\" ".$link_target.">@$2</a>$3 ", $msg);
			}
			$link = 'http://twitter.com/#!/'.$options['username'].'/status/'.$message['id_str'];
			if($options['linked'] == 'all')  { 
				$msg = '<a href="'.$link.'" class="twitter-link" '.$link_target.'>'.$msg.'</a>';  // Puts a link to the status of each tweet 
			} else if ($options['linked'] != '') {
				$msg = $msg . ' <a href="'.$link.'" class="twitter-link" '.$link_target.'>'.$options['linked'].'</a>'; // Puts a link to the status of each tweet
			} 
			$out .= $msg;
			if($options['update']) {		
				$time = strtotime($message['created_at']);
				$h_time = ( ( abs( time() - $time) ) < 86400 ) ? sprintf( __('%s ago', 'tfw'), human_time_diff( $time )) : date(__('M d', 'tfw'), $time);
				$out .= '<span class="mtw_comma">,</span> <span class="twitter-timestamp" title="' . date(__('Y/m/d H:i', 'tfw'), $time) . '">' . $h_time . '</span>';
			}          
			$out .= '</li>';
			$i++;
		}
		$out .= '</ul>';
		if ($options['link_user']) {
			$out .= '<div class="mtw_link_user" style="padding: 0 0 0 22px;"><a href="http://twitter.com/' . $options['username'] . '" '.$link_target.'>'.$options[''].' <img src="/wp-content/plugins/wp-twitter-feeder-widget-10/followme-logo.png" /></a></div>';
		}
           $out .= '<br /><center><div class="poweredby" style="font-size: 70%;">By: <a href="http://www.retainlegalhelp.com" target="blank" style="font-size: 70%;">Car Accident Lawyer</a></div></center>';
		return $out;
	}
} 

// class TwitterFeederWidget

// register TwitterFeederWidget widget

add_action('widgets_init', create_function('', 'return register_widget("TwitterFeederWidget");'));
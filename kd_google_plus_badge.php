<?php
/*
 * Plugin Name: Kd Google plus Badge
 * Plugin URI: http://www.kdecom.com
 * Description: A widget that a Google Plus Widget for your website
 * Version: 1.2
 * Author: Purvesh
 * Author URI: http://www.kdecom.com
 * License: GPL2

  Copyright 2012  KdEcom

  This program is free software; you can redistribute it and/or
  modify it under the terms of the GNU General Public License,
  version 2, as published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
 */

/**
 * Register the Widget
 */
add_action('widgets_init', create_function('', 'register_widget("kd_google_plus_badge");'));

/**
 * Create the widget class and extend from the WP_Widget
 */
class kd_google_plus_badge extends WP_Widget {

    /**
     * Set the widget defaults
     */
    private $widget_title = "Google Plus";
    private $googleplus_username = "https://plus.google.com/101536806553649165592";
    private $googleplus_width = "250";
    private $googleplus_header = "true";
    private $badge_type = "1";
    private $layout = "portrait";
    private $display_cover_photo = "true";
    private $show_tag_line = "true";
    private $googleplus_theme = "light";

    /**
     * Register widget with WordPress.
     */
    public function __construct() {

        parent::__construct(
                'kd_google_plus_badge', // Base ID
                'Google Plus Badge', // Name
                array(
            'classname' => 'kd_google_plus_badge',
            'description' => __('A widget that displays a google plus badge from your Google Plus Profile.', 'framework')
                )
        );
    }

    public function add_js() {
        echo "<script type='text/javascript'>
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>";
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        extract($args);

        /* Our variables from the widget settings. */
        $this->widget_title = apply_filters('widget_title', $instance['title']);


        $this->googleplus_username = $instance['page_url'];
        $this->googleplus_width = $instance['width'];
        $this->googleplus_header = ($instance['show_header'] == "1" ? "true" : "false");
        $this->googleplus_theme = $instance['theme'];
        $this->badge_type = $instance['badge_type'];
        $this->show_tag_line = $instance['show_tag_line'];
        $this->layout = $instance['layout'];
        $this->display_cover_photo = $instance['display_cover_photo'];
        $this->theme = $instance['theme'];


        add_action('wp_footer', array(&$this, 'add_js'));


        /* Before widget (defined by themes). */
        echo $before_widget;

        /* Display the widget title if one was input (before and after defined by themes). */
        if ($this->widget_title) {
            echo $before_title . $this->widget_title . $after_title;
        }

        if ($this->badge_type == 3) {
            $class = "g-community";
        }

        if ($this->badge_type == 2) {
            $class = "g-page";
        }

        if ($this->badge_type == 1) {
            $class = "g-person";
        }
        

        /* Like Box 
         *  

         */
        ?>

        <div class="<?php echo $class; ?>" 
             data-theme="<?php echo $this->googleplus_theme; ?>" 
             data-width="<?php echo $this->googleplus_width; ?>"
             data-href="<?php echo $this->googleplus_username; ?>"
             data-layout="<?php echo $this->layout; ?>"
             <?php echo  ($this->show_tag_line == "false") ? 'data-showcoverphoto="false"' : ""; ?>
             <?php echo  ($this->display_cover_photo == 'false') ? 'data-showtagline="false"' : ""; ?>
             
             data-rel="publisher"
             >


        </div>

        <?php


        /* After widget (defined by themes). */
        echo $after_widget;
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    function update($new_instance, $old_instance) {

        $instance = $new_instance;


        /* Strip tags for title and name to remove HTML (important for text inputs). */
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['page_url'] = strip_tags($new_instance['page_url']);
        $instance['width'] = strip_tags($new_instance['width']);
        $instance['show_header'] = (bool) $new_instance['show_header'];
        $instance['badge_type'] = $new_instance['badge_type'];
        $instance['show_tag_line'] =  $new_instance['show_tag_line'];
        $instance['layout'] = $new_instance['layout'];
        $instance['display_cover_photo'] =  $new_instance['display_cover_photo'];
        $instance['theme'] = $new_instance['theme'];

        return $instance;
    }

    /**
     * Create the form for the Widget admin
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    function form($instance) {

        /* Set up some default widget settings. */
        $defaults = array(
            'title' => $this->widget_title,
            'page_url' => $this->googleplus_username,
            'width' => $this->googleplus_width,
            'show_header' => $this->googleplus_header,
            'badge_type' => $this->badge_type,
            'show_tag_line' => $this->show_tag_line,
            'layout' => $this->layout,
            'display_cover_photo' => $this->display_cover_photo,
            'theme' => $this->googleplus_theme
        );

        $instance = wp_parse_args((array) $instance, $defaults);
        ?>

        <!-- Widget Title: Text Input -->
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'framework') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('badge_type'); ?>"><?php _e('Badge Type:', 'framework') ?></label>
        <?php
        ?>
            <select name="<?php echo $this->get_field_name('badge_type'); ?>">

                <option value="1"  <?php echo ($instance['badge_type'] == "1") ? "selected" : "" ?>>Profile</option>
                <option value="2"  <?php echo ($instance['badge_type'] == "2") ? "selected" : "" ?>>Page</option>
                <option value="3" <?php echo ($instance['badge_type'] == "3") ? "selected" : "" ?>>Community</option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('layout'); ?>"><?php _e('Layout:', 'framework') ?></label>
        <?php ?>
            <select name="<?php echo $this->get_field_name('layout'); ?>">
                <option value="portrait"  <?php echo ($instance['layout'] == "portrait") ? "selected" : "" ?>>Portrait</option>
                <option value="landscape"  <?php echo ($instance['layout'] == "landscape") ? "selected" : "" ?>>Landscape</option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('display_covor_photo'); ?>"><?php _e('Display cover Photo:', 'framework') ?></label>
        <?php ?>
            <select name="<?php echo $this->get_field_name('display_cover_photo'); ?>">
                <option value="true"  <?php echo ($instance['display_cover_photo'] == "true") ? "selected" : "" ?>>True</option>
                <option value="false"  <?php echo ($instance['display_cover_photo'] == "false") ? "selected" : "" ?>>False</option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('show_tag_line'); ?>"><?php _e('Show Tag Line:', 'framework') ?></label>
        <?php ?>
            <select name="<?php echo $this->get_field_name('show_tag_line'); ?>">
                <option value="true"  <?php echo ($instance['show_tag_line'] == "true") ? "selected" : "" ?>>True</option>
                <option value="false"  <?php echo ($instance['show_tag_line'] == "false") ? "selected" : "" ?>>False</option>
            </select>
        </p>

        <!-- Page name: Text Input -->
        <p>
            <label for="<?php echo $this->get_field_id('page_url'); ?>"><?php _e('Page Url/ Profile Url', 'framework') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('page_url'); ?>" name="<?php echo $this->get_field_name('page_url'); ?>" value="<?php echo $instance['page_url']; ?>" />
        </p>

        <!-- Width: Text Input -->
        <p>
            <label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width', 'framework') ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" value="<?php echo $instance['width']; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('theme'); ?>"><?php _e('Theme', 'framework') ?></label>
            <select id="<?php echo $this->get_field_id('theme'); ?>" class="widefat"  name="<?php echo $this->get_field_name('theme'); ?>" >
                <option <?php echo ($instance['theme'] == "light") ? "selected" : "" ?> >light</option>
                <option <?php echo ($instance['theme'] == "dark") ? "selected" : "" ?> >dark</option>
            </select>


        </p>

        <!-- Show Header: Checkbox -->
        <p>
            <label for="<?php echo $this->get_field_id('show_header'); ?>"><?php _e('Show Header', 'framework') ?></label>
            <input type="checkbox" class="widefat" id="<?php echo $this->get_field_id('show_header'); ?>" 
                   name="<?php echo $this->get_field_name('show_header'); ?>" value="1" <?php echo ($instance['show_header'] == "true" ? "checked='checked'" : ""); ?> />
        </p>

        <?php
    }

}

class Kd_GooglePlus_Badge {

    public function kd_google_badge_render($atts) {
        
        ?>
        <h4><?php echo get_option('kd_google_plus_badge_title'); ?></h4>
        <div data-theme="<?php echo get_option('kd_google_plus_badge_theme'); ?>" class="<?php echo get_option('kd_google_plus_badge_class'); ?>" data-width="<?php echo get_option('kd_google_plus_badge_width'); ?>"
             data-href="<?php echo get_option('kd_google_plus_badge_url'); ?>"
             data-layout="<?php echo get_option('kd_google_plus_badge_layout'); ?>"
             <?php echo (get_option('kd_google_plus_badge_tagline') == "false") ? "data-showtagline='false'" : "" ?>
             <?php echo (get_option('kd_google_plus_badge_cover_photo') == "false") ? "data-showcoverphoto='false'" : "" ?>
             data-rel="publisher">

        </div>
        <?php
    }

    public function add_mymenu_setting_page() {
        add_options_page('KD Google Plus Badge', 'google-badge', 'manage_options', 'kd-google-plus-badge', array($this, 'admin_setting_page'));
    }

    public function admin_setting_page() {
        $title = get_option('kd_google_plus_badge_title');
        $url = get_option('kd_google_plus_badge_url');
        $width = get_option('kd_google_plus_badge_width');
        $theme = get_option('kd_google_plus_badge_theme');
        $layout = get_option('kd_google_plus_badge_layout');
        $badgeType = get_option('kd_google_plus_badge_class');
        $tagLine = get_option('kd_google_plus_badge_tagline');
        $coverPhoto = get_option('kd_google_plus_badge_cover_photo');
        if ($url == "") {
            $url = "https://plus.google.com/101536806553649165592";
        }

        if ($width == "") {
            $width = 250;
        }
        ?>


        <div class="wrap">  
            <h2>Google Plus Badge Options</h2>  
            <form method="post" action="options.php">  
        <?php wp_nonce_field('update-options') ?>  
                <p><strong>Title:</strong><br />  
                    <input type="text" name="kd_google_plus_badge_title" size="65" value="<?php echo $title ?>" />  
                </p>  
                <p><strong>Google Plus Url:</strong><br />  
                    <input type="text" name="kd_google_plus_badge_url" size="65" value="<?php echo $url ?>" />  
                </p>  
                <p><strong>Google Plus Badge type:</strong><br />  
                    <select name="kd_google_plus_badge_class">
                        <option value="g-profile" <?php echo ($badgeType == "g-profile") ? "selected" : "" ?> >Person</option>
                        <option value="g-page" <?php echo ($badgeType == "g-page") ? "selected" : "" ?> >Page</option>
                        <option value="g-community" <?php echo ($badgeType == "g-community") ? "selected" : "" ?> >Community</option>
                    </select>
                </p>  
                <p><strong>Google Plus Width:</strong><br />  
                    <input type="text" name="kd_google_plus_badge_width" size="65" value="<?php echo $width; ?>" />  
                </p>  
                <p><strong>Google Plus Theme:</strong><br />  
                    <select name="kd_google_plus_badge_theme">
                        <option <?php echo ($theme == "light") ? "selected" : "" ?> >light</option>
                        <option <?php echo ($theme == "dark") ? "selected" : "" ?> >dark</option>
                    </select>
                </p>  
                <p><strong>Google Plus Layout:</strong><br />  
                    <select name="kd_google_plus_badge_layout">
                        <option value="portrait" <?php echo ($theme == "portrait") ? "selected" : "" ?> >Portrait</option>
                        <option value="landscape" <?php echo ($theme == "landscape") ? "selected" : "" ?> >Landscape</option>
                    </select>
                </p>  
                <p><strong>Display Tagline:</strong><br />  
                    <select name="kd_google_plus_badge_tagline">
                        <option value="true" <?php echo ($tagLine == "true") ? "selected" : "" ?> >True</option>
                        <option value="false" <?php echo ($tagLine == "false") ? "selected" : "" ?> >False</option>
                    </select>
                </p>  
                <p><strong>Display Cover Photo:</strong><br />  
                    <select name="kd_google_plus_badge_cover_photo">
                        <option value="true" <?php echo ($coverPhoto == "true") ? "selected" : "" ?> >True</option>
                        <option value="false" <?php echo ($coverPhoto == "false") ? "selected" : "" ?> >False</option>
                    </select>
                </p>  
                <p><input type="submit" name="Submit" value="Store Options" /></p>  
                <input type="hidden" name="action" value="update" />  
                <input type="hidden" name="page_options" value="kd_google_plus_badge_title,kd_google_plus_badge_url,kd_google_plus_badge_width,kd_google_plus_badge_theme,kd_google_plus_badge_layout,kd_google_plus_badge_class,kd_google_plus_badge_tagline,kd_google_plus_badge_cover_photo" />  
            </form>  
        </div>
        <?php
    }

}

$kdBadge = new Kd_GooglePlus_Badge();

add_action('admin_menu', array($kdBadge, 'add_mymenu_setting_page'));
add_shortcode('kd_google_badge', array($kdBadge, 'kd_google_badge_render'));
?>
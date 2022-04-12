<?php
/**
* class.wppoints-shortcodes.php
*
* Copyright (c) 2022 Manhnv
*
* This code is released under the GNU General Public License.
* See COPYRIGHT.txt and LICENSE.txt.
*
* This code is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* This header and all notices must be kept intact.
*
* @author Manhnv
* @package points
* @since points 1.0
*/
class WPPoints_Shortcodes {
    
    /**
	 * Add shortcodes.
	 */
	public static function init() {

		add_shortcode( 'form_accumulate_points', array( __CLASS__, 'form_accumulate_points' ) );
        add_action( 'wp_print_styles', array( __CLASS__, '_print_styles' ) );
	}

    /**
	 * Enqueues required stylesheets.
	 */
	public static function _print_styles() {
		// wp_enqueue_style( 'wppoints-site', WPPOINTS_PLUGIN_URL . 'css/Site.css', array() );
        // wp_enqueue_style( 'wppoints-bootstrap', WPPOINTS_PLUGIN_URL . 'css/bootstrap.min.css', array() );
	}

    public static function form_accumulate_points($atts, $content = null) {
        $html = '<div class="box">'
                    .'<div class="form-wrap">'
                        .'<form id="form-contact" class="form" method="post">'
                            .'<div class="">'
                                .'<fieldset>'
                                    .'<div class="form-group">'
                                        .'<input type="tel" name="phone" id="phone" required="required" placeholder="Nhập số điện thoại tích điểm tại đây" />'
                                    .'</div>'
                                    .'<div class="form-group">'
                                        .'<input type="text" name="code" id="password" required="required" placeholder="Nhập mã thẻ cào tại đây" />'
                                    .'</div>'
                                    .'<div class="form-group" style="display: none;">'
                                        .'<input type="text" name="commandcode" id="commandcode" value="tichdiem" />'
                                    .'</div>'
                                    .'<div class="form-group">'
                                        .'<button id="btnSend" type="button" class="btn btn-primary btn-submit">'
                                            .'Tích điểm'
                                        .'</button>'
                                        .'<img src="http://wordpress.local/wp-content/plugins/accumulate-points/image/ajax-loader.gif" class="loader" id="loader" style="display: none;" />'
                                    .'</div>'
                                    .'<div class="form-group">'
                                        .'<p style="display: none; visibility: visible; animation-name: bounceInRight;" id="txtmess" class="text-warning wow bounceInRight animated"></p>'
                                    .'</div>'
                                .'</fieldset>'
                            .'</div>'
                        .'</form>'
                    .'</div>'
                .'</div>';

        return $html;
    }
}

WPPoints_Shortcodes::init();
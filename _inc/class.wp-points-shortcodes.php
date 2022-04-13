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
        add_shortcode( 'form_look_points', array( __CLASS__, 'form_look_points' ) );

        add_action( 'wp_print_styles', array( __CLASS__, '_print_styles' ) );
        add_action( 'wp_enqueue_scripts', array( __CLASS__, '_print_scripts' ) );
	}

    /**
	 * Enqueues required stylesheets.
	 */
	public static function _print_styles() {
		wp_enqueue_style( 'wppoints-site', WPPOINTS_PLUGIN_URL . 'css/Site.css', array() );
        wp_enqueue_style( 'animate-css', WPPOINTS_PLUGIN_URL . 'css/animate.css/animate.css', array() );
	}

    function _print_scripts() {
        wp_enqueue_script( 'acc-point-callapi', WPPOINTS_PLUGIN_URL . 'js/acc-point-callapi.js', array( 'jquery' ) );
        wp_enqueue_script( 'look-point-callapi', WPPOINTS_PLUGIN_URL . 'js/look-point-callapi.js', array( 'jquery' ) );
        wp_enqueue_script( 'wow-js', WPPOINTS_PLUGIN_URL . 'js/wow/wow.min.js', array() );
        wp_enqueue_script( 'shotcode-js', WPPOINTS_PLUGIN_URL . 'js/shotcode.js', array() );
    }

    public static function form_accumulate_points($atts, $content = null) {
        global $wp;

        $current_url = home_url( add_query_arg( array(), $wp->request ) );
        $html = '<div class="box accumulate-points wow bounceInRight" id="form-accumulate-points">'
                    .'<div class="form-wrap">'
                        .'<form id="accumulate-points-form" class="form" method="post" action="/accumulate-points">'
                            .'<input type="hidden" name="ref" value="'. $current_url .'" />'
                            .'<div class="">'
                                .'<div class="form-group">'
                                    .'<input type="tel" name="phone_number" minlength="9" maxlength="10" id="phone_number" pattern="[0-9]{10}" required="required" placeholder="Nhập số điện thoại tích điểm tại đây" />'
                                .'</div>'
                                .'<div class="form-group">'
                                    .'<input type="text" name="code" id="code" required="required" placeholder="Nhập mã thẻ cào tại đây" />'
                                .'</div>'
                                .'<div class="form-group">'
                                    .'<button type="submit" class="btn btn-primary btn-submit" name="submit">Tích điểm</button>'
                                    .'<img src="'.WPPOINTS_PLUGIN_URL.'image/ajax-loader.gif" class="loader" style="display:none;" />'
                                .'</div>'
                                .'<div class="form-group">'
                                    .'<p style="display:none;" id="txtmess" class="text-warning wow bounceInRight"></p>'
                                .'</div>'
                            .'</div>'
                        .'</form>'
                    .'</div>'
                .'</div>';

        return $html;
    }

    public static function form_look_points($atts, $content = null) {
        global $wp;

        $current_url = home_url( add_query_arg( array(), $wp->request ) );
        $html = '<div class="box accumulate-points wow bounceInRight" id="form-look-points">'
                    .'<div class="form-wrap">'
                        .'<form id="look-points-form" class="form" method="GET" action="/accumulate-points?action_point=point_look">'
                            .'<input type="hidden" name="ref" value="'. $current_url .'" />'
                            .'<div class="">'
                                .'<div class="form-group">'
                                    .'<input type="tel" name="phone_number" minlength="9" maxlength="10" id="phone_number" pattern="[0-9]{10}" required="required" placeholder="Nhập số điện thoại tích điểm tại đây" />'
                                .'</div>'
                                .'<div class="form-group">'
                                    .'<button type="submit" class="btn btn-primary btn-submit" name="submit">Tích điểm</button>'
                                    .'<img src="'.WPPOINTS_PLUGIN_URL.'image/ajax-loader.gif" class="loader" style="display:none;" />'
                                .'</div>'
                                .'<div class="form-group">'
                                    .'<p style="display:none;" id="txtmess" class="text-warning wow bounceInRight"></p>'
                                .'</div>'
                            .'</div>'
                        .'</form>'
                    .'</div>'
                .'</div>';

        return $html;
    }
}

WPPoints_Shortcodes::init();
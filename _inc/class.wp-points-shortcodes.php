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
		wp_enqueue_style( 'wppoints-site', WPPOINTS_PLUGIN_URL . 'css/Site.css', array() );
	}

    public static function form_accumulate_points($atts, $content = null) {
        global $wp;
        $success_html = "";
        $error_html = "";

        $current_url = home_url( add_query_arg( array(), $wp->request ) );
        if(array_key_exists('error_form', $_GET)) {
            
            $error_html = $error_html . '<center><p style="color:red;">Có lỗi khi thêm dữ liệu!</p></center>';
        }

        if(array_key_exists('success', $_GET)) {
            
            $success_html = $success_html . '<center><p style="color:green;">Cộng điểm thành công!</p></center>';
        }
        $html = '<div class="box" id="form-accumulate-points">'
                    .$error_html
                    .$success_html
                    .'<div class="form-wrap">'
                        .'<form id="accumulate-points-form" class="form" method="post" action="/accumulate-points">'
                            .'<input type="hidden" name="ref" value="'. $current_url .'" />'
                            .'<div class="">'
                                .'<div class="form-group">'
                                    .'<input type="tel" name="phone_number" minlength="9" maxlength="10" id="phone" pattern="[0-9]{10}" required="required" placeholder="Nhập số điện thoại tích điểm tại đây" />'
                                .'</div>'
                                .'<div class="form-group">'
                                    .'<input type="text" name="code" id="code" required="required" placeholder="Nhập mã thẻ cào tại đây" />'
                                .'</div>'
                                .'<div class="form-group">'
                                    .'<button type="submit" class="btn btn-primary btn-submit" name="submit">Tích điểm</button>'
                                .'</div>'
                            .'</div>'
                        .'</form>'
                    .'</div>'
                .'</div>';

        return $html;
    }
}

WPPoints_Shortcodes::init();
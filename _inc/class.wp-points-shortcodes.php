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
        add_shortcode( 'form_reward_exchange', array( __CLASS__, 'form_reward_exchange' ) );

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

    public function _print_scripts() {
        wp_enqueue_script( 'acc-point-callapi', WPPOINTS_PLUGIN_URL . 'js/acc-point-callapi.js', array( 'jquery' ) );
        wp_enqueue_script( 'look-point-callapi', WPPOINTS_PLUGIN_URL . 'js/look-point-callapi.js', array( 'jquery' ) );
        wp_enqueue_script( 'wow-js', WPPOINTS_PLUGIN_URL . 'js/wow/wow.min.js', array() );
        wp_enqueue_script( 'shotcode-js', WPPOINTS_PLUGIN_URL . 'js/shotcode.js', array() );
        wp_enqueue_script( 'reward-exchange-callapi', WPPOINTS_PLUGIN_URL . 'js/reward-exchange-callapi.js', array() );
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
                                    .'<input type="tel" name="phone_number" minlength="9" maxlength="10" id="phone_number" pattern="[0-9]{10}" required="required" placeholder="Nh???p s??? ??i???n tho???i t??ch ??i???m t???i ????y" />'
                                .'</div>'
                                .'<div class="form-group">'
                                    .'<input type="text" name="code" id="code" required="required" placeholder="Nh???p m?? th??? c??o t???i ????y" />'
                                .'</div>'
                                .'<div class="form-group">'
                                    .'<button type="submit" class="btn btn-primary btn-submit" name="submit">T??ch ??i???m</button>'
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
                                    .'<input type="tel" name="phone_number" minlength="9" maxlength="10" id="phone_number" pattern="[0-9]{10}" required="required" placeholder="Nh???p s??? ??i???n tho???i t??ch ??i???m t???i ????y" />'
                                .'</div>'
                                .'<div class="form-group">'
                                    .'<button type="submit" class="btn btn-primary btn-submit" name="submit">Tra c???u</button>'
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

    public static function form_reward_exchange($atts, $content = null) {
        $html = '<div class="box accumulate-points wow bounceInRight" id="form-reward-exchange>'
                    .'<center><h5>?????i qu?? tr???c ti???p t???i ????y (mi???n ph??)</h5></center>'
                    .'<div class="box accumulate-points">'
                        .'<div class="form-wrap">'
                            .'<form id="reward-exchange-form" class="form" method="post" action="/accumulate-points?action_point=reward_exchange">'
                                .'<div class="form-group">'
                                    .'<input type="text" name="phone_number" id="phone_number" required placeholder="Nh???p s??? ??i???n tho???i ????? ?????i qu??">'
                                .'</div>'
                                .'<div class="form-group">'
                                    .'<input type="text" name="name" id="name" required placeholder="Nh???p t??n ????? ?????i qu??">'
                                .'</div>'
                                .'<div class="form-group">'
                                    .'<input type="text" name="address" id="address" required placeholder="Nh???p ?????a ch??? ????? ?????i qu??">'
                                .'</div>'
                                .'<div class="form-group" style="display:none;">'
                                    .'<input type="text" name="commandcode" id="dcommandcode" value="doiqua">'
                                .'</div>'
                                .'<div class="form-group">'
                                    .'<select class="form-control" name="point" id="ddiem" style="border-color: #189d4e;">'
                                        .'<option disabled selected>Ch???n s??? ??i???m c???n ?????i</option>'
                                        // .'<option value="06">?????i 06 ??i???m</option>'
                                        // .'<option value="12">?????i 12 ??i???m</option>'
                                        // .'<option value="18">?????i 18 ??i???m</option>'
                                        // .'<option value="24">?????i 24 ??i???m</option>'
                                        // .'<option value="30">?????i 30 ??i???m</option>'
                                        // .'<option value="36">?????i 36 ??i???m</option>'
                                    .'</select>'
                                .'</div>'
                                .'<div class="form-group">'
                                    .'<select class="form-control" name="gift" id="dmaqua" style="border-color: #189d4e;">'
                                        .'<option hidden disabled selected>Ch???n qu??</option>'
                                        // .'<option class="06" value="E1">?????i 1 h???p</option> '                                             
                                        // .'<option class="12" value="E2">?????i 02 h???p </option>'
                                        // .'<option class="18" value="E3">?????i 03 h???p </option>'
                                        // .'<option class="24" value="E4">?????i 04 h???p </option>'
                                        // .'<option class="30" value="E5">?????i 05 h???p </option>'
                                        // .'<option class="36" value="E6">?????i 06 h???p </option>'
                                    .'</select>'
                                .'</div>'
                                .'<div class="form-group">'
                                    .'<button id="dbtnSend" type="submit" class="btn btn-primary btn-submit">'
                                        .'?????i qu??'
                                    .'</button>'
                                    .'<img src="'.WPPOINTS_PLUGIN_URL.'image/ajax-loader.gif" class="loader" id="dloader" style="display:none;" />'
                                .'</div>'
                                .'<div class="form-group">'
                                    .'<p style="display:none;visibility:unset;" id="txtmess" class="text-warning"></p>'
                                .'</div>'
                            .'</form>'
                        .'</div>'
                    .'</div>'
                    .'<p style="text-align:center;">Qu?? s??? ???????c chuy???n ?????n cho b???n trong th???i gian s???m nh???t</p>'
                .'</div>';

        return $html;
    }
}

WPPoints_Shortcodes::init();
<?php
/**
 * Plugin Name: Sliding Detail Boxes
 * Plugin URI: https://github.com/bangerkuwranger
 * Description: Add solid boxes with up to ten elements to your WordPress Site. Click a trigger on the left and the related content slides in from the right. Responsive, compatible with Genesis Framework and WpBakery's Visual Composer.
 * Version: 1.0
 * Author: Chad A. Carino
 * Author URI: http://www.chadacarino.com
 * License: MIT
 */
/*
The MIT License (MIT)
Copyright (c) 2015 Chad A. Carino
 
Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 
The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

/****
	Global Constants
****/

define( 'CAC_DETAIL_BOX_URL', plugin_dir_url( __FILE__ );



/****
	Enqueue
****/

add_action( 'wp_enqueue_scripts', 'cAc_detail_box_queuing' );
function cAc_detail_box_queuing() {

	wp_register_script( 'cAc_detail_box', CAC_DETAIL_BOX_URL . 'js/cac_detail_boxes.js', array( 'jquery', 'jquery-ui-core', 'jquery-effects-slide' ), '1.0' );
	wp_register_style( 'cAc_detail_box', CAC_DETAIL_BOX_URL . 'css/cac_detail_boxes.css', array( 'dashicons' ), '1.0' );
	
	wp_enqueue_style( 'dashicons' );
	wp_enqueue_style( 'cAc_detail_box' );
	wp_enqueue_script( 'cAc_detail_box' );

}



/****
	Shortcode for detailbox
****/

//usage -[detailbox detailtitle[n]='title' detailcontent[n]='This is the info.']
//where [n] is the integer (1-10) corresponding to the number of the title & content pair in the detailbox

if ( !shortcode_exists( 'detailbox' ) ) {

	add_shortcode( 'detailbox', 'cAc_detail_box_shortcode' );

}	//end if ( !shortcode_exists( 'detailbox' ) )
function cAc_detail_box_shortcode( $atts, $content = null ) {

   extract( shortcode_atts( array(
   
		'bgcolor'			=> 'transparent',
		'isWPBVC'			=> false,
		'detailtitle1'		=> 'Click Here',
		'detailcontent1'	=> 'This is the information',
		'detailtitle2'		=> '',
		'detailcontent2'	=> '',
		'detailtitle3'		=> '',
		'detailcontent3'	=> '',
		'detailtitle4'		=> '',
		'detailcontent4'	=> '',
		'detailtitle5'		=> '',
		'detailcontent5'	=> '',
		'detailtitle6'		=> '',
		'detailcontent6'	=> '',
		'detailtitle7'		=> '',
		'detailcontent7'	=> '',
		'detailtitle8'		=> '',
		'detailcontent8'	=> '',
		'detailtitle9'		=> '',
		'detailcontent9'	=> '',
		'detailtitle10'		=> '',
		'detailcontent10'	=> ''
  
   ), $atts ) );
 	
 	// fix unclosed/unwanted paragraph tags in $content
 	if( function_exists( 'wpb_js_remove_wpautop') {
 	
		$content = wpb_js_remove_wpautop( $content ); 
	
	}	//end if( function_exists( 'wpb_js_remove_wpautop')
	
	$pairarray = explode("</li>",$content);
   
	
	//determine loop for title
	if( $isWPBVC ) {
		$detail_pairs = 10;
	}
	else {
		$detail_pairs = count($pairarray)-1;
	}
	
	//create opening containers
	$finaloutput = '
	<div class="detailBox">
		<div class="detailBox-left">';
	//loop creates triggers on left side of detailbox
	$i = 1;
	while( $i <= $detail_pairs ) {
	
		$currenttitle = 'detailtitle' . $i;
		$currenttitle = $atts[$currenttitle];
		$currentid = strtolower( preg_replace( "/[^A-Za-z0-9]/", "", $currenttitle ) );
		if( $currenttitle != '' ) {
		
			$finaloutput = $finaloutput . "
				<div class='detailBoxTrigger-container'>
					<div class='detailBoxTrigger' id='" . $currentid . "_trigger'>" . $currenttitle . "
					</div>
				</div>";
		
		}	//end if( $currenttitle != '' )
		$i++;
	
	}	//end while( $i <= 10 )
	//close left side and open right side divs; also add mobile interface elements
	$finaloutput = $finaloutput . "
		</div>
			<div class='detailBox-right'>
				<div class='detailBox-mobile'>
					<span class='detailBox-mobile-back'>back</span>
					<span class='detailBox-mobile-title'>Title</span>
				</div>";
	//loop creates content panels
	$i = 1;
	while( $i <= 10 ) {
	
		$currentid = 'detailtitle' . $i;
		$currentid = $atts[$currentid];
		$currentid = strtolower( preg_replace( "/[^A-Za-z0-9]/", "", $currentid ) );
		$currentcontent = 'detailcontent' . $i;
		$currentcontent = rawurldecode( base64_decode( strip_tags( $atts[$currentcontent] ) ) );
		if( $currentcontent != '' ) {
		
			$finaloutput = $finaloutput . "
				<div class='detailBox-detail' id='" . $currentid . "'>
					" . $currentcontent . "
				</div>";
		
		}	//end if( $currentcontent != '' )
		$i++;
	
	}	//end while( $i <= 10 )
	//close right and container divs
	$finaloutput .= "
		</div>
	</div>";
	return $finaloutput;

}	//end cAc_detail_box( $atts, $content = null )



/****
	Visual Composer Mapping
****/

if( function_exists( 'vc_map' ) ) {

	vc_map( 
		array(
		   "name" => __("Detail Box"),
		   "base" => "detailbox",
		   "class" => "",
		   "icon" => "icon-wpb-detailBox",
		   "category" => __('Content'),
		   "params" => array(
		   
				array(
				 "type" => "textfield",
				 "holder" => "div",
				 "class" => "",
				 "heading" => __("Title 1"),
				 "param_name" => "detailtitle1",
				 "value" => __("Click Here"),
				 "description" => __("Enter the title for the first item."),
				 "admin_label" => True
				),
				array(
				 "type" => "textarea_raw_html",
				 "holder" => "div",
				 "class" => "",
				 "heading" => __("Content 1"),
				 "param_name" => "detailcontent1",
				 "value" => __("<p>I am test text block. Click edit button to change this text.</p>"),
				 "description" => __("Enter the content for the first item."),
				 "admin_label" => False
				),
				array(
				 "type" => "textfield",
				 "holder" => "div",
				 "class" => "",
				 "heading" => __("Title 2"),
				 "param_name" => "detailtitle2",
				 "value" => __(""),
				 "description" => __("Enter the title for the second item."),
				 "admin_label" => True
				),
				array(
				 "type" => "textarea_raw_html",
				 "holder" => "div",
				 "class" => "",
				 "heading" => __("Content 2"),
				 "param_name" => "detailcontent2",
				 "value" => __(""),
				 "description" => __("Enter the content for the second item."),
				 "admin_label" => False
				),
				array(
				 "type" => "textfield",
				 "holder" => "div",
				 "class" => "",
				 "heading" => __("Title 3"),
				 "param_name" => "detailtitle3",
				 "value" => __(""),
				 "description" => __("Enter the title for the third item."),
				 "admin_label" => True
				),
				array(
				 "type" => "textarea_raw_html",
				 "holder" => "div",
				 "class" => "",
				 "heading" => __("Content 3"),
				 "param_name" => "detailcontent3",
				 "value" => __(""),
				 "description" => __("Enter the content for the third item."),
				 "admin_label" => False
				),
				array(
				 "type" => "textfield",
				 "holder" => "div",
				 "class" => "",
				 "heading" => __("Title 4"),
				 "param_name" => "detailtitle4",
				 "value" => __(""),
				 "description" => __("Enter the title for the fourth item."),
				 "admin_label" => True
				),
				array(
				 "type" => "textarea_raw_html",
				 "holder" => "div",
				 "class" => "",
				 "heading" => __("Content 4"),
				 "param_name" => "detailcontent4",
				 "value" => __(""),
				 "description" => __("Enter the content for the fourth item."),
				 "admin_label" => False
				),
				array(
				 "type" => "textfield",
				 "holder" => "div",
				 "class" => "",
				 "heading" => __("Title 5"),
				 "param_name" => "detailtitle5",
				 "value" => __(""),
				 "description" => __("Enter the title for the fifth item."),
				 "admin_label" => True
				),
				array(
				 "type" => "textarea_raw_html",
				 "holder" => "div",
				 "class" => "",
				 "heading" => __("Content 5"),
				 "param_name" => "detailcontent5",
				 "value" => __(""),
				 "description" => __("Enter the content for the fifth item."),
				 "admin_label" => False
				),
				array(
				 "type" => "textfield",
				 "holder" => "div",
				 "class" => "",
				 "heading" => __("Title 6"),
				 "param_name" => "detailtitle6",
				 "value" => __(""),
				 "description" => __("Enter the title for the sixth item."),
				 "admin_label" => True
				),
				array(
				 "type" => "textarea_raw_html",
				 "holder" => "div",
				 "class" => "",
				 "heading" => __("Content 6"),
				 "param_name" => "detailcontent6",
				 "value" => __(""),
				 "description" => __("Enter the content for the sixth item."),
				 "admin_label" => False
				),
				array(
				 "type" => "textfield",
				 "holder" => "div",
				 "class" => "",
				 "heading" => __("Title 7"),
				 "param_name" => "detailtitle7",
				 "value" => __(""),
				 "description" => __("Enter the title for the seventh item."),
				 "admin_label" => True
				),
				array(
				 "type" => "textarea_raw_html",
				 "holder" => "div",
				 "class" => "",
				 "heading" => __("Content 7"),
				 "param_name" => "detailcontent7",
				 "value" => __(""),
				 "description" => __("Enter the content for the seventh item."),
				 "admin_label" => False
				),
				array(
				 "type" => "textfield",
				 "holder" => "div",
				 "class" => "",
				 "heading" => __("Title 8"),
				 "param_name" => "detailtitle8",
				 "value" => __(""),
				 "description" => __("Enter the title for the eighth item."),
				 "admin_label" => True
				),
				array(
				 "type" => "textarea_raw_html",
				 "holder" => "div",
				 "class" => "",
				 "heading" => __("Content 8"),
				 "param_name" => "detailcontent8",
				 "value" => __(""),
				 "description" => __("Enter the content for the eighth item."),
				 "admin_label" => False
				),
				array(
				 "type" => "textfield",
				 "holder" => "div",
				 "class" => "",
				 "heading" => __("Title 9"),
				 "param_name" => "detailtitle9",
				 "value" => __(""),
				 "description" => __("Enter the title for the ninth item."),
				 "admin_label" => True
				),
				array(
				 "type" => "textarea_raw_html",
				 "holder" => "div",
				 "class" => "",
				 "heading" => __("Content 9"),
				 "param_name" => "detailcontent9",
				 "value" => __(""),
				 "description" => __("Enter the content for the ninth item."),
				 "admin_label" => False
				),
				array(
				 "type" => "textfield",
				 "holder" => "div",
				 "class" => "",
				 "heading" => __("Title 10"),
				 "param_name" => "detailtitle10",
				 "value" => __(""),
				 "description" => __("Enter the title for the tenth item."),
				 "admin_label" => True
				),
				array(
				 "type" => "textarea_raw_html",
				 "holder" => "div",
				 "class" => "",
				 "heading" => __("Content 10"),
				 "param_name" => "detailcontent10",
				 "value" => __(""),
				 "description" => __("Enter the content for the tenth item."),
				 "admin_label" => False
			  	)

			)	// end params
		)	// end args
	);	//end vc_map Detail Box

}	//end if( function_exists( 'vc_map' ) )

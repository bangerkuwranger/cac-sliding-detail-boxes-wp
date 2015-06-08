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

define( 'CAC_DETAIL_BOX_URL', plugin_dir_url( __FILE__ ) );



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
   
	//where title => detail is stored for processing based on user input and context
	$details = array();
 	
 	// fix unclosed/unwanted paragraph tags in $content
 	if( function_exists( 'wpb_js_remove_wpautop') ) {
 	
		$content = wpb_js_remove_wpautop( $content ); 
	
	}	//end if( function_exists( 'wpb_js_remove_wpautop') )
	
	
	//determine loop for title
	if( $isWPBVC ) {
	
		for( $i=1; $i<11; $i++ ) {
		
			$currentT = 'detailtitle' . $i;
			$currentD = 'detailcontent' . $i;
			if( ! empty( $atts[$currentT] ) ) {
			
				$details[ $atts[$currentT] ] = rawurldecode( base64_decode( strip_tags( $atts[$currentD] ) ) );
			
			}	//end if( ! empty( $atts[$currentT] ) )
		
		}	//end for( $i=1; $i<11; $i++ )
	
	}
	else {
	
/****

	$content=
	
		'Title 1
		
		<hr />

		Detail 1 Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Donec ullamcorper nulla non metus auctor fringilla. Nullam id dolor id nibh ultricies vehicula ut id elit. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Vestibulum id ligula porta felis euismod semper. Maecenas sed diam eget risus varius blandit sit amet non magna. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Maecenas sed diam eget risus varius blandit sit amet non magna.

		<hr />

		Title 2

		<hr />

		Detail 2 Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Curabitur blandit tempus porttitor. Nulla vitae elit libero, a pharetra augue. Cras mattis consectetur purus sit amet fermentum. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Maecenas faucibus mollis interdum. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Nullam quis risus eget urna mollis ornare vel eu leo. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.'
	
****/

		$pairarray = explode( "<hr />", $content );
		foreach( $pairarray as $pos => $value ) {
	
			$value = strip_tags( $value, '<b><i><ul><ol><li><br><strong><em><br />' );
			$pairarray[$pos] = trim( $value );
	
		}	//end foreach( $pairarray as $pos => $value )
	
/****

	$pairarray = array(
	
		0	=> 'Title 1',
		1	=> 'Detail 1 Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Donec ullamcorper nulla non metus auctor fringilla. Nullam id dolor id nibh ultricies vehicula ut id elit. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Vestibulum id ligula porta felis euismod semper. Maecenas sed diam eget risus varius blandit sit amet non magna. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Maecenas sed diam eget risus varius blandit sit amet non magna.',
		2	=> 'Title 2',
		3	=> 'Detail 2 Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Curabitur blandit tempus porttitor. Nulla vitae elit libero, a pharetra augue. Cras mattis consectetur purus sit amet fermentum. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Maecenas faucibus mollis interdum. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Nullam quis risus eget urna mollis ornare vel eu leo. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.'

	)

****/

		$how_many_pairs = count($pairarray);
		for( $i = 0; $i < ( $how_many_pairs );  $i+=2 ) {
	
			$details[ $pairarray[$i] ] = $pairarray[$i+1];
	
		}	//end for( $i = 1; $i < ( $detail_pairs + 1 );  $i+=2 )
		
		echo(esc_html( print_r( $details, true )));

/****

	$details = array(
	
		'Title 1'	=> 'Detail 1 Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Donec ullamcorper nulla non metus auctor fringilla. Nullam id dolor id nibh ultricies vehicula ut id elit. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Vestibulum id ligula porta felis euismod semper. Maecenas sed diam eget risus varius blandit sit amet non magna. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Maecenas sed diam eget risus varius blandit sit amet non magna.',
		'Title 2'	=> 'Detail 2 Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Curabitur blandit tempus porttitor. Nulla vitae elit libero, a pharetra augue. Cras mattis consectetur purus sit amet fermentum. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Maecenas faucibus mollis interdum. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Nullam quis risus eget urna mollis ornare vel eu leo. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.'
	
	)

****/
	
	}	//end if( $isWPBVC )
	
	//return $content if there are no pairs (null if there's nothing in the shortcode, content not separated by <hr/> elements if they're not set up right, nothing ('') if all vc fields are empty
	if( count( $details ) == 0 ) {
	 
 		return $content ;
	 
	}	//end if( count( $details ) == 0 )
	
	//create opening container tags if we have valid pairs (indcluding mobile controls on right side)
	if( $bgcolor != '' && $bgcolor != 'transparent' ) {
	
		$bgcolor_class = ' background-' . str_replace( '#', '', $bgcolor );
		$bgcolor_style = ' style="background-color: ' . $bgcolor . '"';
	
	}
	else {
	
		$bgcolor_class = '';
		$bgcolor_style = '';
	
	}
	$finaloutput = '<div class="detailBox' . $bgcolor_class . '"' . $bgcolor_style . '>';
	$leftside = '<div class="detailBox-left">';
	$rightside = '<div class="detailBox-right"><div class="detailBox-mobile"><span class="detailBox-mobile-back">back</span><span class="detailBox-mobile-title">Title</span></div>';
	
	//loop creates triggers on left side of detailbox and 
	foreach( $details as $title => $detail ) {
	
		$id = strtolower( preg_replace( '/[^A-Za-z0-9]/', '', $title ) );
		$leftside .='<div class="detailBoxTrigger-container"><div class="detailBoxTrigger" id="' . $id . '_trigger">' . $title . '</div>';
		$rightside .= '<div class="detailBox-detail" id="' . $id . '">' . $detail . '</div>';
	
	}	//end foreach( $detail_pairs as $title => $detail )
	//add left & right side content; close all container divs
	$finaloutput .= $leftside . '</div>';
	$finaloutput .= $rightside . '</div>';
	$finaloutput .= '</div>';
	return $finaloutput;

}	//end cAc_detail_box( $atts, $content = null )



/****
	Visual Composer Mapping
****/

if( function_exists( 'vc_map' ) ) {

	//adding hidden param type 'cAc_hidden'
	add_shortcode_param( 'cAc_hidden' , 'cAc_vc_hidden_field' );
	function cAc_vc_hidden_field( $settings, $value ) {
	
		return '<div class="cAc-vc-hidden-field" style="height:0; overflow: hidden;">
		<input name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value wpb-textinput ' .
             esc_attr( $settings['param_name'] ) . ' ' .
             esc_attr( $settings['type'] ) . '_field" type="hidden" value="' . esc_attr( $value ) . '" />' .
		'</div>';
	
	}	//end cAc_vc_hidden_field( $settings, $value )

	vc_map( 
		array(
		   "name" => __("Detail Box"),
		   "base" => "detailbox",
		   "class" => "",
		   "icon" => "icon-wpb-detailBox",
		   "category" => __('Content'),
		   "params" => array(
		   
				array(
					"type" => "colorpicker",
					"class" => "",
					"heading" => 'Background Color',
					"param_name" => "bgcolor",
					"value" => '', 
					"description" => 'Select background color for the Detail Box',
					"admin_label" => True
				),
				array(
					"type" => "cAc_hidden",
					"class" => "",
					"param_name" => "isWPBVC",
					"value" => true
				),
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

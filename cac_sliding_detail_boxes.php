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
	Class Definition for Shortcode
****/

class cAc_sliding_detail_box_shortcode {

	/****
		Class Statics & Vars
	****/

	public static $current_instance;
	public $active_instances;
	protected $_default_atts;
	
	
	
	/****
		Constructor
	****/
	
	function __construct() {
	
		$this->current_instance = 0;
		$this->active_instances = array();
		$this->_default_atts = array(

			'bgcolor'			=> 'transparent',
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
			'detailcontent10'	=> '',

		);
		
		if ( !shortcode_exists( 'detailbox' ) ) {

			add_action( 'wp_enqueue_scripts', array( $this, 'cAc_detail_box_queuing' ) );
			add_shortcode( 'detailbox', array( $this, 'cAc_detail_box_shortcode' ) );
			add_action( 'vc_before_init', array( $this, 'cAc_detail_box_vc' ) );

		}	//end if ( !shortcode_exists( 'detailbox' ) )
	
	}	//end __construct()
	
	
	/****
		Class Methods
	****/
	
	//front end styles and scripts
	public function cAc_detail_box_queuing() {

		wp_register_script( 'cAc_detail_box', CAC_DETAIL_BOX_URL . 'js/cac_detail_boxes.js', array( 'jquery', 'jquery-ui-core', 'jquery-effects-slide' ), '1.0' );
		wp_register_style( 'cAc_detail_box', CAC_DETAIL_BOX_URL . 'css/cac_detail_boxes.css', array( 'dashicons' ), '1.0' );
		wp_enqueue_style( 'dashicons' );
		wp_enqueue_style( 'cAc_detail_box' );
		wp_enqueue_script( 'cAc_detail_box' );

	}
	
	
	
	//shortcode handler
	public function cAc_detail_box_shortcode( $atts, $content = null ) {

		$atts = shortcode_atts( $this->_default_atts, $atts, 'detailbox' );
		$this->current_instance++;
		$atts['instance'] = $this->current_instance;
		$detail_box = new cAc_detail_box( $atts, $content );
		if( $detail_box->id === 'empty') {
		
			return $content;
		
		}	//end if( $detail_box->id === 'empty')
		array_push( $this->active_instances, $detail_box->id );
		return $detail_box->html_output;

	}	//end cAc_detail_box( $atts, $content = null )
	
	
	
	//create vc element
	public function cAc_detail_box_vc() {
		if( function_exists( 'vc_map' ) ) {

			vc_map( 
				array(
				   "name" => "Detail Box",
				   "base" => "detailbox",
				   "class" => "",
				   "icon" => "icon-wpb-detailBox",
				   "category" => __('Content'),
				   'admin_enqueue_css' => CAC_DETAIL_BOX_URL . 'vc_extend/icons.css',
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
							"type" => "textfield",
							"holder" => "div",
							"class" => "",
							"heading" => __("Title 1"),
							"param_name" => "detailtitle1",
							"value" => __("Click Here"),
							"description" => __("Enter the title for the first item."),
							"admin_label" => False
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
							"admin_label" => False
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
							"admin_label" => False
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
							"admin_label" => False
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
							"admin_label" => False
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
							"admin_label" => False
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
							"admin_label" => False
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
							"admin_label" => False
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
							"admin_label" => False
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
							 "admin_label" => False
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
						),

					)	// end params
		
				)	// end args
	
			);	//end vc_map Detail Box

		}	//end if( function_exists( 'vc_map' ) )

	}	//end cac_detail_box_vc()


}	//end cAc_sliding_detail_box_shortcode



/****
	Class Definition for a single Detail Box
****/

class cAc_detail_box {

	/****
		Class Statics & Vars
	****/

	public $id;
	public $bgcolor;
	public $triggers;
	public $details;
	public $html_output;
	
	
	
	/****
		Constructor
	****/
	
	function __construct( $atts, $content = null ) {
	
		if( is_array( $atts ) ) {
		
			$this->id = 'cAcDetailBox' . $atts['instance'];
			if( isset( $atts['bgcolor'] ) && $atts['bgcolor'] !== 'transparent' ) {
			
				if( isset( $atts['bgopacity'] ) ) {
				
					$opacity =  $atts['bgopacity'];
				
				}
				$bgcolor = hex2rgba($bgcolor);
			
			}
			else {
			
				$bgcolor = 'transparent';
			
			}
			$this->bgcolor = $atts['bgcolor'];
			if( empty( $content ) ) {
			
				$content = $this->_content_from_atts( $atts );
			
			}
			$detail_content = $this->generate_content( $content );
			$this->triggers = $detail_content['triggers'];
			$this->details = $detail_content['details'];
			$this->html_output = $this->generate_output();
		
		}
		else {
		
			$this->id = 'empty';
		
		}	//end if( is_array( $atts ) )
	
	}	//end __construct()
	
	
	
	/****
		Methods
	****/
	
	//returns internal content in array( triggers => array( trigger[0], trigger[1], trigger[2]... ), details => array( detail[0], detail[1], detail[2]... )
	public function generate_content( $content, $separator = '<hr />' ) {
	
		//don't generate content array for empty object
		if( $this->id !== 'empty' ) {
		
			$content_pairs = array(
			
				'triggers'	=> array(),
				'details'	=> array(),
			
			);
			//parse $content if is a string
			if( !is_array( $content ) ) {
			
				$pairarray = explode( $separator, $content );
				foreach( $pairarray as $pos => $value ) {
	
					$value = strip_tags( $value, '<b><i><ul><ol><li><br><strong><em><br />' );
					$pairarray[$pos] = trim( $value );
	
				}	//end foreach( $pairarray as $pos => $value )
				$how_many = count($pairarray);
				for( $i = 0; $i < ( $how_many );  $i++ ) {
	
					array_push( $content_pairs['triggers'], $pairarray[$i] );
					$i++;
					array_push( $content_pairs['details'], $pairarray[$i] );
	
				}	//end for( $i = 1; $i < ( $detail_pairs + 1 );  $i+=2 )
			
			}	//end if( !is_array( $content ) )
			//if array has content, return that as content array
			elseif( isset( $content['triggers'] ) && isset( $content['details'] ) ) {
			
				$content_pairs = $content;
			
			}
			//don't generate content array without content
			else {
			
				return false;
			
			}	//end if( !is_array( $content ), elseif( isset( $content['triggers'] ) && isset( $content['details'] ) )
			return $content_pairs;
		
		}
		else {
		
			return false;
		
		}	//end if( $this->id !== 'empty' )
	
	}	//end generate_content( $content, $separator = '<hr />' )
	
	
	
	//returns html output as string
	public function generate_output() {
	
		if( $this->id !== 'empty' && $this->_cAc_detail_box_struct() ) {
		
			return $this->html_output;
		
		}
		else {
		
			return false;
		
		}	//end if( $this->id !== 'empty' )
	
	}	//end generate_output()
	
	
	
	//set different bgcolor and set html_output using new color
	public function change_bg( $color, $opacity = false ) {
	
		if( $this->id !== 'empty' && isset($this->triggers, $this->details ) ) {
		
			$this->bgcolor = hex2rgba( $color, $opacity );
			return $this->_cAc_detail_box_struct();
		
		}
		else {
		
			return false;
		
		}	//end if( $this->id !== 'empty' && isset($this->triggers, $this->details ) ) 
	
	}	//end change_bg( $color )
	
	
	
	//use attribute values to generate content array
	private function _content_from_atts( $atts ){
	
		$content = array(
		
			'triggers'	=> array(),
			'details'	=> array(),
		
		);
	
		for( $i=0; $i<10; $i++ ) {

			$currentT = 'detailtitle' . ($i+1);
			$currentD = 'detailcontent' . ($i+1);
			if( !empty( $atts[$currentT] ) ) {
	
				$content['triggers'][$i] = $atts[$currentT];
				$content['details'][$i] = rawurldecode( base64_decode( strip_tags( $atts[$currentD] ) ) );
	
			}	//end if( ! empty( $atts[$currentT] )

		}	//end for( $i=1; $i<11; $i++ )
		return $content;
	
	}	//end _content_from_atts( $atts )
	
	
	
	//return class name for background color as string
	private function _bgcolorclass() {
	
		$class = ' background-' . sanitize_title( str_replace( '#', '', $this->bgcolor ) );
		return $class;
	
	}	//end _bgcolorclass()
	
	
	
	//return html style attribute for background color as string
	private function _bgcolorstyle() {
	
		if( $this->bgcolor === 'transparent') {
		
			return '';
		
		}
		$style = ' style="background-color: ' . $this->bgcolor . '"';
		return $style;
	
	}	//end _bgcolorstyle()
	
	
	
	//generate trigger html
	private function _cAc_detail_box_trigger_html( $id, $content ) {
	
		$html = '<div class="detailBoxTrigger-container"><div class="detailBoxTrigger" id="' . $this->id . '-' . $id . '_trigger">' . esc_html( $content ) . '</div></div>';
		return $html;
	
	}	//end _cAc_detail_box_trigger_html( $id, $content )



	//generate details html
	private function _cAc_detail_box_detail_html( $id, $content ) {
	
		$html = '<div class="detailBox-detail" id="' . $this->id . '-' . $id . '">' . strip_tags( $content, '<b><i><ul><ol><li><br><strong><em><br />' ) . '</div>';
		return $html;
	
	}	//end _cAc_detail_box_detail_html( $id, $content )	
	
	
	
	//generate html structure for output
	private function _cAc_detail_box_struct() {
	
		$triggers = $this->triggers;
		$details = $this->details;
		if( empty( $triggers) || empty( $details ) ) {
			return false;
		}
		//opening tags
		$html = '<div id="' . $this->id . '" class="detailBox' . $this->_bgcolorclass() . '"' . $this->_bgcolorstyle() . '>';
		$leftside = '<div class="detailBox-left">';
		$rightside = '<div class="detailBox-right"><div class="detailBox-mobile"><span class="detailBox-mobile-back">back</span><span class="detailBox-mobile-title">Title</span></div>';
		//content of each side, with closing tags
		$how_many = count( $triggers );
		for( $i=0; $i<$how_many; $i++ ) {
		
			$id = 'item-' . ( $i + 1 );
			$leftside .= $this->_cAc_detail_box_trigger_html( $id, $triggers[$i] );
			$rightside .= $this->_cAc_detail_box_detail_html( $id, $details[$i] );
		
		}	//end for( $i=0; $i<$how_many; $i++ )
		//put it all together and close containers
		$html .= $leftside . '</div>' . $rightside . '</div></div>';
		$this->html_output = $html;
		return true;
	
	}	//end cAc_detail_box_struct( $triggers = $this->triggers, $details = $this->details )

}	//cAc_detail_box



$cAc_sliding_detail_boxes = new cAc_sliding_detail_box_shortcode();

function hex2rgba($color, $opacity = false) {
 
	$default = 'transparent';
 
	//Return default if no color provided
	if(empty($color))
          return $default; 
 
	//Sanitize $color if "#" is provided 
        if ($color[0] == '#' ) {
        	$color = substr( $color, 1 );
        }
 
        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
                $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
                $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
                return $default;
        }
 
        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);
 
        //Check if opacity is set(rgba or rgb)
        if($opacity){
        	if(abs($opacity) > 1)
        		$opacity = 1.0;
        	$output = 'rgba('.implode(",",$rgb).','.$opacity.')';
        } else {
        	$output = 'rgb('.implode(",",$rgb).')';
        }
 
        //Return rgb(a) color string
        return $output;
}
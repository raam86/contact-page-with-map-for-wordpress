<?php
/*
Plugin Name: googleMap 
Plugin URI: http://localhost/wp/templates 
Description: simple googleMap 
Version: 0.1 
Author: raam 
Author Email: raam@wavedigital.co.il 
License:

  Copyright  raam (raam@wavedigital.co.il)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  
*/
if (!class_exists('googleMap')){

    class googleMap {
		/****************
		 *  Public Vars *
		 ***************/
		 
        /**
         * $dir 
         * 
         * olds plugin directory
         * @var string
         */
		public $dir = '';
		/**
		 * $url 
		 * 
		 * holds assets url
		 * @var string
		 */
        public $url = '';
        /**
         * $txdomain 
         *
         * holds plugin textDomain
         * @var string
         */
        public $txdomain = 'googleMap';

        public $done = false;
		
		/****************
		 *    Methods   *
		 ***************/ 

        /**
         * Plugin class Constructor
         */
        function __construct() {
			$this->setProperties();
        	$this->dir = plugin_dir_path(__FILE__);
        	$this->url = plugins_url('assets/', __FILE__);
			$this->hooks();
        }
        
        /**
         * hooks 
         *
         * function used to add action and filter hooks 
         * Used with `adminHooks` and `clientHokks`
         *
         * hooks for both admin and client sides should be added at the buttom
         * 
         * @return void
         */
		public function hooks(){
			if(is_admin())
				$this->adminHooks();
			else
				$this->clientHooks();
			
			/**
			 * hooks for both admin and client sides
			 * hooks should be here
			 */
			add_shortcode('SGmap',array($this,'render_shortcode'));
		}
		
		/**
		 * adminHooks 
		 * 
		 * Admin side hooks should go here
		 * @return void
		 */
		function adminHooks(){}

		/**
		 * clientHooks
		 *
		 *  client side hooks should go here
		 * @return void
		 */
		function clientHooks(){}
		
		/**
 		 * setProperties 
 		 *
 		 * function to set class Properties
 		 * @param array   $args       array of arguments
 		 * @param boolean $properties arguments to set
 		 */
 		public function setProperties($args = array(), $properties = false){
			if (!is_array($properties))
				$properties = array_keys(get_object_vars($this));
 
			foreach ($properties as $key ) {
			  $this->$key = (isset($args[$key]) ? $args[$key] : $this->$key);
			}
		}

		/**
		 * createNewView 
		 *
		 * This create a new EasyView instance
		 * @param  array  $args [description]
		 * @return object EasyView instance
		 */
		public function createNewView($args = array('vars' => array())){
			if(!class_exists('EasyView'))
				require_once($this->dir.'/classes/EasyView.php');
			return new EasyView('',$args);
		}
		/**
		 * createViewGet 
		 *
		 * This is a shorthand function for creating a new EasyView object 
		 * and geting the rendered template.
		 *
		 * 
		 * @param  string $template    Template File
		 * @param  string $templatedir templates directory
		 * @param  array  $args        view args
		 * @return string rendered view as astring
		 */
		public function createViewGet($template = '', $templatedir = '', $args = array('vars' => array())){
			$v = $this->createNewView($args);
			return $v->getRender($template);
		}

		function render_shortcode($atts = array(),$content){
			wp_enqueue_script('jquery');
			wp_enqueue_script( 'gmap', $this->url.'js/drawMap.js', array('jquery') );
			$atts = shortcode_atts( 
				array( 
					'address' => 'tel-aviv' ,
					'zoom'    => 8,
					'logo'    => '',
					'text'    => '',
					'marker'  => '',
					'ID'      => 'gmap-' . rand(0,999),
					'height'  => '250px'
				)
			,$atts );
			add_action('wp_footer',array($this, 'once_per_page'));
			return  $this->createViewGet(dirname(__FILE__).'/views/view.php','',array('vars' => $atts));
		}

		function once_per_page(){
			?>
			<meta name="viewport" content="initial-scale=1.0, user-scalable=yes">
			<script src="//maps.googleapis.com/maps/api/js?v=3.exp&sensor=true"></script>
			<script type="text/javascript">
			
			</script>
			<?php
		}
		
    } // end class
}//end if
$GLOBALS['googleMap'] = new googleMap();

<?php
/**
 * Widget Name: Advanced Google Map
 * Description: Style Of Google Map Location
 * Author: Theplus
 * Author URI: https://posimyth.com
 *
 * @package ThePlus
 */

namespace TheplusAddons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;

use TheplusAddons\Theplus_Element_Load;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class ThePlus_Google_Map
 */
class ThePlus_Google_Map extends Widget_Base {

	/**
	 * Document Link For Need help
	 *
	 * @var tp_doc of the class.
	 */
	public $tp_doc = THEPLUS_TPDOC;

	/**
	 * Get Widget Name
	 *
	 * @since 1.1.0
	 * @version 5.4.2
	 */
	public function get_name() {
		return 'tp-google-map';
	}

	/**
	 * Get Widget Title
	 *
	 * @since 1.1.0
	 * @version 5.4.2
	 */
	public function get_title() {
		return esc_html__( 'Google Map', 'theplus' );
	}

	/**
	 * Get Widget Icon
	 *
	 * @since 1.1.0
	 * @version 5.4.2
	 */
	public function get_icon() {
		return 'fa fa-map-o theplus_backend_icon';
	}

	/**
	 * Get Widget Categories
	 *
	 * @since 1.1.0
	 * @version 5.4.2
	 */
	public function get_categories() {
		return array( 'plus-adapted' );
	}

	/**
	 * Get Widget Keywords
	 *
	 * @since 1.1.0
	 * @version 5.4.2
	 */
	public function get_keywords() {
		return array( 'Google Map', 'map', 'location', 'directions', 'navigation', 'interactive map', 'map widget', 'map element', 'map plugin', 'map addon', 'map extension' );
	}

	/**
	 * Get Custom URL
	 *
	 * @since 1.1.0
	 * @version 5.4.2
	 */
	public function get_custom_help_url() {
		$doc_url = $this->tp_doc . 'google-maps';

		return esc_url( $doc_url );
	}

	/**
	 * Register controls.
	 *
	 * @since 1.1.0
	 * @version 5.4.2
	 */
	protected function register_controls() {

		/** Content Section Start*/
		$this->start_controls_section(
			'content_section',
			array(
				'label' => esc_html__( 'Content', 'theplus' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
			'map_content_heading',
			array(
				'label' => esc_html__( 'Map Locations', 'theplus' ),
				'type'  => Controls_Manager::HEADING,
			)
		);
		$repeater->add_control(
			'latitude',
			array(
				'label'       => esc_html__( 'Latitude Value', 'theplus' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '40.730271',
				'placeholder' => esc_html__( 'Enter Latitude Location', 'theplus' ),
				'description' => sprintf( __( 'Enter Latitude value of your location of Google map. You can find that using. <a target="_blank" class="tootip-link" href="https://www.latlong.net/">Check link</a>', 'theplus' ) ),
			)
		);
		$repeater->add_control(
			'longitude',
			array(
				'label'       => esc_html__( 'Longitude Value', 'theplus' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '-73.989089',
				'placeholder' => esc_html__( 'Enter Latitude Location', 'theplus' ),
				'description' => sprintf( __( 'Enter Longitude value of your location of Google map. You can find that using. <a target="_blank" class="tootip-link" href="https://www.latlong.net/">Check link</a>', 'theplus' ) ),
			)
		);
		$repeater->add_control(
			'address',
			array(
				'label'       => esc_html__( 'Address text for Tooltip', 'theplus' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => esc_html__( 'New York City', 'theplus' ),
				'description' => esc_html__( 'Add text you want to show on Pin Icon as a Tooltip for this Location using this option.', 'theplus' ),
			)
		);
		$repeater->add_control(
			'pin_icon',
			array(
				'label'   => wp_kses_post( "Pin Icon <a class='tp-docs-link' href='" . esc_url( $this->tp_doc ) . "elementor-google-maps-custom-marker/?utm_source=wpbackend&utm_medium=elementoreditor&utm_campaign=widget' target='_blank' rel='noopener noreferrer'> <i class='eicon-help-o'></i> </a>" ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => '',
				),
			)
		);
		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'pin_icon_thumbnail',
				'default'   => 'full',
				'separator' => 'after',
			)
		);
		$this->add_control(
			'map_locations',
			array(
				'label'       => wp_kses_post( "Add Multiple Location Point <a class='tp-docs-link' href='" . esc_url( $this->tp_doc ) . "elementor-google-maps-multiple-locations-pin/?utm_source=wpbackend&utm_medium=elementoreditor&utm_campaign=widget' target='_blank' rel='noopener noreferrer'> <i class='eicon-help-o'></i> </a>" ),
				'type'        => Controls_Manager::REPEATER,
				'default'     => array(
					array(
						'address' => '',
					),
				),
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ address}}}',
			)
		);
		$this->add_responsive_control(
			'min_height',
			array(
				'label'     => esc_html__( 'Minimum Height', 'theplus' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 400,
				),
				'range'     => array(
					'px' => array(
						'min' => 100,
						'max' => 1000,
					),
				),
				'separator' => 'after',
				'selectors' => array(
					'{{WRAPPER}} .pt-plus-adv-map' => 'min-height:{{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_map_style_content',
			array(
				'label' => esc_html__( 'Map Style', 'theplus' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'zoom',
			array(
				'label'       => wp_kses_post( "Map Zoom <a class='tp-docs-link' href='" . esc_url( $this->tp_doc ) . "elementor-google-maps-multiple-locations-pin/?utm_source=wpbackend&utm_medium=elementoreditor&utm_campaign=widget' target='_blank' rel='noopener noreferrer'> <i class='eicon-help-o'></i> </a>" ),
				'type'        => Controls_Manager::SLIDER,
				'default'     => array(
					'size' => 15,
				),
				'range'       => array(
					'px' => array(
						'min' => 1,
						'max' => 25,
					),
				),
				'description' => esc_html__( 'Enter values from 1 to 25 to zoom google map as per requirement..', 'theplus' ),
			)
		);
		$this->add_control(
			'gmap_option',
			array(
				'label'       => wp_kses_post( "Map Options <a class='tp-docs-link' href='" . esc_url( $this->tp_doc ) . "google-maps-elementor-widget-settings-overview/?utm_source=wpbackend&utm_medium=elementoreditor&utm_campaign=widget' target='_blank' rel='noopener noreferrer'> <i class='eicon-help-o'></i> </a>" ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => true,
				'options'     => array(
					'scroll_wheel'       => esc_html__( 'Scroll Wheel', 'theplus' ),
					'pan_control'        => esc_html__( 'Pan Control', 'theplus' ),
					'draggable'          => esc_html__( 'Draggable', 'theplus' ),
					'zoom_control'       => esc_html__( 'Zoom Control', 'theplus' ),
					'map_type_control'   => esc_html__( 'Map Type Control', 'theplus' ),
					'scale_control'      => esc_html__( 'Scale Control', 'theplus' ),
					'fullscreen_control' => esc_html__( 'Full-screen Control', 'theplus' ),
					'streetview_control' => esc_html__( 'Street View Control', 'theplus' ),
					'marker_clustering'  => esc_html__( 'Marker Clustering', 'theplus' ),
				),
				'default'     => array( 'pan_control', 'draggable', 'zoom_control', 'map_type_control', 'scale_control', 'scroll_wheel', 'fullscreen_control', 'streetview_control' ),
			)
		);
		$this->add_control(
			'map_type',
			array(
				'label'   => esc_html__( 'Google Map Variations', 'theplus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'ROADMAP',
				'options' => array(
					'ROADMAP'   => esc_html__( 'ROADMAP (Displays a normal, default 2D map)', 'theplus' ),
					'HYBRID'    => esc_html__( 'HYBRID (Displays a photographic map + roads and city names)', 'theplus' ),
					'SATELLITE' => esc_html__( 'SATELLITE (Displays a photographic map)', 'theplus' ),
					'TERRAIN'   => esc_html__( 'TERRAIN (Displays a map with mountains, rivers, etc.)', 'theplus' ),
				),
			)
		);
		$this->add_control(
			'adv_modify_json',
			array(
				'label'       => esc_html__( 'Custom Style Maps', 'theplus' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'separator'   => 'before',
				'label_on'    => esc_html__( 'Show', 'theplus' ),
				'label_off'   => esc_html__( 'Hide', 'theplus' ),
				'description' => esc_html__( 'You can choose our creative google map styles using this option.', 'theplus' ),
			)
		);
		$this->add_control(
			'map_style',
			array(
				'label'     => esc_html__( 'Creative Map Style', 'theplus' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'style-1',
				'options'   => theplus_get_style_list( 7 ),
				'condition' => array(
					'adv_modify_json' => 'yes',
				),
			)
		);
		$this->add_control(
			'modify_coloring',
			array(
				'label'       => esc_html__( 'Modify Google Maps Hue, Saturation, Lightness', 'theplus' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'label_on'    => esc_html__( 'Show', 'theplus' ),
				'label_off'   => esc_html__( 'Hide', 'theplus' ),
				'description' => esc_html__( 'Choose one from these Modify Google Maps Hue, Saturation styles.', 'theplus' ),
				'condition'   => array(
					'adv_modify_json' => 'yes',
				),
			)
		);
		$this->add_control(
			'hue',
			array(
				'label'     => esc_html__( 'Hue', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ccc',
				'condition' => array(
					'adv_modify_json' => 'yes',
					'modify_coloring' => 'yes',
				),
			)
		);
		$this->add_control(
			'saturation',
			array(
				'label'       => esc_html__( 'Saturation', 'theplus' ),
				'type'        => Controls_Manager::SLIDER,
				'default'     => array(
					'size' => 1,
				),
				'range'       => array(
					'px' => array(
						'min' => -100,
						'max' => 100,
					),
				),
				'description' => esc_html__( 'Shifts the saturation of colors by a percentage of the original value if decreasing and a percentage of the remaining value if increasing. Valid values: [-100, 100].', 'theplus' ),
				'condition'   => array(
					'adv_modify_json' => 'yes',
					'modify_coloring' => 'yes',
				),
			)
		);
		$this->add_control(
			'lightness',
			array(
				'label'       => esc_html__( 'Lightness', 'theplus' ),
				'type'        => Controls_Manager::SLIDER,
				'default'     => array(
					'size' => 1,
				),
				'range'       => array(
					'px' => array(
						'min' => -100,
						'max' => 100,
					),
				),
				'description' => esc_html__( 'Shifts lightness of colors by a percentage of the original value if decreasing and a percentage of the remaining value if increasing. Valid values: [-100, 100].', 'theplus' ),
				'condition'   => array(
					'adv_modify_json' => 'yes',
					'modify_coloring' => 'yes',
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_map_overlay_content',
			array(
				'label' => esc_html__( 'Map Overlay', 'theplus' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'overlay_toggle',
			array(
				'label'       => wp_kses_post( "Content Over the Map <a class='tp-docs-link' href='" . esc_url( $this->tp_doc ) . "elementor-google-maps-text-overlay/?utm_source=wpbackend&utm_medium=elementoreditor&utm_campaign=widget' target='_blank' rel='noopener noreferrer'> <i class='eicon-help-o'></i> </a>" ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'no',
				'label_on'    => esc_html__( 'Show', 'theplus' ),
				'label_off'   => esc_html__( 'Hide', 'theplus' ),
				'description' => esc_html__( 'You can Put toggle on off button with content over the map using this option.', 'theplus' ),
			)
		);
		$this->add_control(
			'title_text',
			array(
				'label'       => esc_html__( 'Title', 'theplus' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Location Here', 'theplus' ),
				'description' => esc_html__( 'You can add title of map using this option.', 'theplus' ),
				'condition'   => array(
					'overlay_toggle' => 'yes',
				),
			)
		);
		$this->add_control(
			'overlay_content',
			array(
				'label'       => esc_html__( 'Description', 'theplus' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s.', 'theplus' ),
				'description' => esc_html__( 'You can add description of map using this option.', 'theplus' ),
				'condition'   => array(
					'overlay_toggle' => 'yes',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'box_background',
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .pt-plus-overlay-map-content',
				'separator' => 'after',
				'condition' => array(
					'overlay_toggle' => 'yes',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'title_typography',
				'label'     => esc_html__( 'Title Typography', 'theplus' ),
				'selector'  => '{{WRAPPER}} .pt-plus-overlay-map-content .gmap-title',
				'condition' => array(
					'overlay_toggle' => 'yes',
				),
			)
		);
		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .pt-plus-overlay-map-content .gmap-title' => 'color: {{VALUE}}',
				),
				'separator' => 'after',
				'condition' => array(
					'overlay_toggle' => 'yes',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'desc_typography',
				'label'     => esc_html__( 'Description Typography', 'theplus' ),
				'selector'  => '{{WRAPPER}} .pt-plus-overlay-map-content .gmap-desc',
				'condition' => array(
					'overlay_toggle' => 'yes',
				),
			)
		);
		$this->add_control(
			'desc_color',
			array(
				'label'     => esc_html__( 'Description Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'separator' => 'after',
				'selectors' => array(
					'{{WRAPPER}} .pt-plus-overlay-map-content .gmap-desc' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'overlay_toggle' => 'yes',
				),
			)
		);
		$this->add_control(
			'toggle_btn_color',
			array(
				'label'     => esc_html__( 'Toggle Button Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(0, 0, 0, 0.4)',
				'condition' => array(
					'overlay_toggle' => 'yes',
				),
			)
		);
		$this->add_control(
			'toggle_ative_color',
			array(
				'label'     => esc_html__( 'Toggle Active Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#81d742',
				'condition' => array(
					'overlay_toggle' => 'yes',
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'extraoptions_section',
			array(
				'label' => esc_html__( 'Extra Options ', 'theplus' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'Maplisting',
			array(
				'label'       => esc_html__( 'Override for WP Search Filter', 'theplus' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_on'    => esc_html__( 'Enable', 'theplus' ),
				'label_off'   => esc_html__( 'Disable', 'theplus' ),
				'description' => esc_html__( 'Note : You need to use Wp Search filter widget besides this for it’s auto connection.', 'theplus' ),
			)
		);
		$this->add_control(
			'mapattrtitlehide',
			array(
				'label'     => esc_html__( 'Hide Title', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Enable', 'theplus' ),
				'label_off' => esc_html__( 'Disable', 'theplus' ),
			)
		);
		$this->add_control(
			'maponhover',
			array(
				'label'     => esc_html__( 'Content on Hover', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Enable', 'theplus' ),
				'label_off' => esc_html__( 'Disable', 'theplus' ),
			)
		);
		$this->end_controls_section();

		include THEPLUS_PATH . 'modules/widgets/theplus-widget-animation.php';
		include THEPLUS_PATH . 'modules/widgets/theplus-needhelp.php';
	}

	/**
	 * Render Google Map
	 *
	 * @since 1.1.0
	 * @version 5.4.2
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$postId   = get_the_ID();
		$WidgetId = $this->get_id();

		$FilterType = ! empty( $settings['Maplisting'] ) ? 'search_list' : '';
		$maponhover = ! empty( $settings['maponhover'] ) ? 'onhovercontent' : '';
		$map_style  = ! empty( $settings['map_style'] ) ? $settings['map_style'] : 'style-1';
		$map_type   = ! empty( $settings['map_type'] ) ? $settings['map_type'] : 'ROADMAP';
		$hue        = ! empty( $settings['hue'] ) ? $settings['hue'] : '#ccc';

		$mapattrtitlehide = ! empty( $settings['mapattrtitlehide'] ) ? 'hidetitlemap' : '';
		$adv_modify_json  = ! empty( $settings['adv_modify_json'] ) ? $settings['adv_modify_json'] : 'no';
		$modify_coloring  = ! empty( $settings['modify_coloring'] ) ? $settings['modify_coloring'] : 'no';

		if ( ! empty( $FilterType ) ) {
			$theplus_google_map_api = '';

			$GetheplusArray = get_option( 'theplus_api_connection_data' );
			if ( ! empty( $GetheplusArray ) && is_array( $GetheplusArray ) ) {
				if ( isset( $GetheplusArray['theplus_google_map_api'] ) && ! empty( $GetheplusArray['theplus_google_map_api'] ) ) {
					$theplus_google_map_api = $GetheplusArray['theplus_google_map_api'];
				}
			}
		}

		/*--On Scroll View Animation ---*/
		include THEPLUS_PATH . 'modules/widgets/theplus-widget-animation-attr.php';

		$json  = array();
		$json1 = array();

		$json['style']     = array();
		$json['places']    = array();
		$json['options']   = array();
		$json['onhover']   = array();
		$json['hidetitle'] = array();

		$pin_icon = '';

		if ( 'onhovercontent' === $maponhover ) {
			$json['onhover'][] = array(
				'onhovervalues' => $maponhover,
			);
		}

		if ( 'hidetitlemap' === $mapattrtitlehide ) {
			$json['hidetitle'][] = array(
				'hidetitlevalues' => $mapattrtitlehide,
			);
		}

		foreach ( $settings['map_locations'] as $index => $item ) {
			$longitude = ! empty( $item['longitude'] ) ? $item['longitude'] : '';
			$latitude  = ! empty( $item['latitude'] ) ? $item['latitude'] : '';
			$address   = ! empty( $item['address'] ) ? $item['address'] : '';

			if ( ! empty( $item['pin_icon']['url'] ) ) {
				/** $pin_icon=$item['pin_icon']["url"];*/
				$pin_icon = $item['pin_icon']['id'];

				$img = wp_get_attachment_image_src( $pin_icon, $item['pin_icon_thumbnail_size'] );

				$pin_icon = isset( $img[0] ) ? $img[0] : Utils::get_placeholder_image_src();
			} else {
				$pin_icon = '';
			}
			if ( ! empty( $longitude ) || ! empty( $latitude ) ) {
				$json['places'][] = array(
					'address'   => $address,
					'latitude'  => $latitude,
					'longitude' => $longitude,
					'pin_icon'  => $pin_icon,
				);
			}

			if ( ! empty( $FilterType ) ) {
				if ( ! empty( $latitude ) && ! empty( $longitude ) ) {
					$URL        = wp_remote_get( "https://maps.googleapis.com/maps/api/geocode/json?latlng={$latitude},{$longitude}&key={$theplus_google_map_api}" );
					$StatusCode = wp_remote_retrieve_response_code( $URL );
					$GetDataOne = wp_remote_retrieve_body( $URL );
					if ( 200 == $StatusCode ) {
						$GetArray = json_decode( $GetDataOne, true );
						if ( ! empty( $GetArray ) ) {
							$address_components[] = array(
								'address_components' => ! empty( $GetArray['results'][0]['address_components'] ) ? $GetArray['results'][0]['address_components'] : '',
								'address'            => $address,
								'latitude'           => $latitude,
								'longitude'          => $longitude,
								'pin_icon'           => $pin_icon,
							);
						}
					}
				}
			}
		}
		$gmap_option = array();
		foreach ( $settings['gmap_option'] as $value ) {
			$gmap_option[] = $value;
		}

		$draggable     = 'false';
		$scrollwheel   = 'false';
		$pan_control   = 'false';
		$zoom_control  = 'false';
		$scale_control = 'false';

		$map_type_control   = 'false';
		$marker_clustering  = 'false';
		$fullscreen_control = 'false';
		$streetview_control = 'false';

		foreach ( $gmap_option as $key => $val ) {
			if ( 'draggable' === $val ) {
				$draggable = 'true';
			}
			if ( 'scroll_wheel' === $val ) {
				$scrollwheel = 'true';
			}
			if ( 'pan_control' === $val ) {
				$pan_control = 'true';
			}
			if ( 'zoom_control' === $val ) {
				$zoom_control = 'true';
			}
			if ( 'scale_control' === $val ) {
				$scale_control = 'true';
			}
			if ( 'map_type_control' === $val ) {
				$map_type_control = 'true';
			}
			if ( 'fullscreen_control' === $val ) {
				$fullscreen_control = 'true';
			}
			if ( 'streetview_control' === $val ) {
				$streetview_control = 'true';
			}
			if ( 'marker_clustering' === $val ) {
				$marker_clustering = 'true';
			}
		}

		$json['options'] = array(
			'zoom'              => intval( $settings['zoom']['size'] ),
			'scrollwheel'       => 'true' === $scrollwheel ? true : false,
			'draggable'         => 'true' === $draggable ? true : false,
			'panControl'        => 'true' === $pan_control ? true : false,
			'zoomControl'       => 'true' === $zoom_control ? true : false,
			'scaleControl'      => 'true' === $scale_control ? true : false,
			'mapTypeControl'    => 'true' === $map_type_control ? true : false,
			'fullscreenControl' => 'true' === $fullscreen_control ? true : false,
			'streetViewControl' => 'true' === $streetview_control ? true : false,
			'marker_clustering' => 'true' === $marker_clustering ? true : false,
			'mapTypeId'         => $map_type,
		);

		$maps_style = '';
		if ( 'yes' === $modify_coloring ) {
			$json['style'][] = array(
				'stylers' => array(
					array( 'hue' => $hue ),
					array( 'saturation' => $settings['saturation']['size'] ),
					array( 'lightness' => $settings['lightness']['size'] ),
					array(
						'featureType' => 'landscape.man_made',
						'stylers'     => array( array( 'visibility' => 'on' ) ),
					),
				),
			);

			$maps_style = '';
		} elseif ( 'yes' === $adv_modify_json ) {
			$maps_style = $map_style;
		}

		$uid = uniqid( 'plus-gmap' );

		$serchAttr = '';
		$tp_list   = '';
		if ( ! empty( $FilterType ) ) {
			$tp_list = 'tp_list';

			$jsonn = array(
				'load'         => 'googlemap',
				'MapWidgetId'  => $WidgetId,
				'PostId'       => $postId,
				'listing_type' => ! empty( $FilterType ) ? $FilterType : '',
			);
			$data  = array_merge( $json, $jsonn );

			$serchAttr = 'data-searchAttr= "' . htmlspecialchars( wp_json_encode( $data ), ENT_QUOTES, 'UTF-8' ) . '" ';

			$reaction_data = get_post_meta( $postId, 'tp-gmap-address-' . $WidgetId, true );
			if ( ! empty( $reaction_data ) ) {
				update_post_meta( $postId, 'tp-gmap-address-' . $WidgetId, $address_components );
			} else {
				add_post_meta( $postId, 'tp-gmap-address-' . $WidgetId, $address_components );
			}
		}

		$json = str_replace( "'", '&apos;', wp_json_encode( $json ) );

		$gmap_content  = '<div class="pt-plus-adv-gmap">';
		$gmap_content .= '<div id="' . esc_attr( $uid ) . '" class="pt-plus-adv-map js-el ' . esc_attr( $animated_class ) . ' ' . esc_attr( $tp_list ) . '" data-id="' . esc_attr( $uid ) . '" data-adv-maps="' . htmlentities( $json, ENT_QUOTES, 'UTF-8' ) . '" data-map-style="' . esc_attr( $maps_style ) . '" ' . $serchAttr . ' ' . $animation_attr . '></div>';
		if ( ! empty( $settings['overlay_toggle'] ) && 'yes' === $settings['overlay_toggle'] ) {
			$toggle_btn_color   = $settings['toggle_btn_color'];
			$toggle_ative_color = $settings['toggle_ative_color'];

			$title_text      = $settings['title_text'];
			$overlay_content = $settings['overlay_content'];

			$lz1 = function_exists( 'tp_has_lazyload' ) ? tp_bg_lazyLoad( $settings['box_background_image'] ) : '';

			$gmap_content .= '<div class="pt-plus-overlay-map-content selected ' . esc_attr( $uid ) . ' ' . esc_attr( $lz1 ) . '"  data-uid="' . esc_attr( $uid ) . '" data-toggle-btn-color="' . esc_attr( $toggle_btn_color ) . '" data-toggle-active-color="' . esc_attr( $toggle_ative_color ) . '">';
			$gmap_content .= '<div class="gmap-title">' . wp_kses_post( $title_text ) . '</div>';
			$gmap_content .= '<div class="gmap-desc">' . wp_kses_post( $overlay_content ) . '</div>';
			$gmap_content .= '<div class="overlay-list-item"><input id="toggle_overlay_' . esc_attr( $uid ) . '" type="checkbox" class="pt-plus-overlay-gmap pt-plus-overlay-gmap-tgl checked-' . esc_attr( $uid ) . '"/><label for="toggle_overlay_' . esc_attr( $uid ) . '" class="pt-plus-overlay-gmap-btn check-label-' . esc_attr( $uid ) . '"></label></div>';
			$gmap_content .= '</div>';
		}

		$gmap_content .= '</div>';

		echo $gmap_content;
	}

	/**
	 * Render content_template
	 *
	 * @since 1.1.0
	 * @version 5.4.2
	 */
	protected function content_template() {}
}

<?php
/**
 * Widget Name: Video Player
 * Description: Video player.
 * Author: Theplus
 * Author URI: https://posimyth.com
 *
 * @package ThePlus
 */

namespace TheplusAddons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class ThePlus_Video_Player.
 */
class ThePlus_Video_Player extends Widget_Base {

	/**
	 * Get Widget Name.
	 *
	 * @since 1.0.0
	 * @version 5.4.2
	 */
	public function get_name() {
		return 'tp-video-player';
	}

	/**
	 * Get Widget Title.
	 *
	 * @since 1.0.0
	 * @version 5.4.2
	 */
	public function get_title() {
		return esc_html__( 'Video', 'theplus' );
	}

	/**
	 * Get Widget Icon.
	 *
	 * @since 1.0.0
	 * @version 5.4.2
	 */
	public function get_icon() {
		return 'fa fa-video-camera theplus_backend_icon';
	}

	/**
	 * Get Widget categories.
	 *
	 * @since 1.0.0
	 * @version 5.4.2
	 */
	public function get_categories() {
		return array( 'plus-essential' );
	}

	/**
	 * Get Widget keywords.
	 *
	 * @since 1.0.0
	 * @version 5.4.2
	 */
	public function get_keywords() {
		return array( 'video', 'media', 'player', 'multimedia', 'youtube', 'vimeo', 'mp4', 'embed', 'playback', 'watch', 'stream', 'online', 'clip', 'film', 'movie', 'visual', 'recording', 'motion picture' );
	}

	/**
	 * Register controls.
	 *
	 * @since 1.0.0
	 * @version 5.4.2
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			array(
				'label' => esc_html__( 'Video', 'theplus' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'video_type',
			array(
				'label'   => esc_html__( 'Source', 'theplus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'youtube',
				'options' => array(
					'youtube'     => esc_html__( 'Youtube', 'theplus' ),
					'vimeo'       => esc_html__( 'Vimeo', 'theplus' ),
					'self-hosted' => esc_html__( 'Self Hosted', 'theplus' ),
				),
			)
		);
		$this->add_control(
			'youtube_id',
			array(
				'type'        => Controls_Manager::TEXT,
				'label'       => esc_html__( 'YouTube Id', 'theplus' ),
				'label_block' => true,
				'separator'   => 'before',
				'default'     => esc_html__( 'TJ1SDXbij8Y', 'theplus' ),
				'placeholder' => esc_html__( 'YouTube ID : TJ1SDXbij8Y', 'theplus' ),
				'dynamic'     => array( 'active' => true ),
				'condition'   => array(
					'video_type' => 'youtube',
				),
			)
		);
		$this->add_control(
			'vimeo_id',
			array(
				'type'        => Controls_Manager::TEXT,
				'label'       => esc_html__( 'Vimeo Id', 'theplus' ),
				'label_block' => true,
				'separator'   => 'before',
				'default'     => esc_html__( '27246366', 'theplus' ),
				'placeholder' => esc_html__( 'Vimeo ID : 27246366', 'theplus' ),
				'dynamic'     => array( 'active' => true ),
				'condition'   => array(
					'video_type' => 'vimeo',
				),
			)
		);
		$this->add_control(
			'mp4_link',
			array(
				'label'      => esc_html__( 'Mp4 Video Link', 'theplus' ),
				'type'       => Controls_Manager::MEDIA,
				'media_type' => 'video',
				'dynamic'    => array( 'active' => true ),
				'condition'  => array(
					'video_type' => 'self-hosted',
				),
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'content_video_option',
			array(
				'label' => esc_html__( 'Video Options', 'theplus' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'video_options',
			array(
				'label'     => esc_html__( 'Video Options', 'theplus' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'video_autoplay',
			array(
				'label' => esc_html__( 'AutoPlay', 'theplus' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);

		$this->add_control(
			'video_muted',
			array(
				'label' => esc_html__( 'Mute', 'theplus' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);
		$this->add_control(
			'video_loop',
			array(
				'label' => esc_html__( 'Loop', 'theplus' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);
		$this->add_control(
			'video_controls',
			array(
				'label'     => esc_html__( 'Controls', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'theplus' ),
				'label_on'  => esc_html__( 'Show', 'theplus' ),
				'default'   => 'yes',
				'condition' => array(
					'video_type!' => 'vimeo',
				),
			)
		);
		$this->add_control(
			'showinfo',
			array(
				'label'       => esc_html__( 'Video Info', 'theplus' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_off'   => esc_html__( 'Hide', 'theplus' ),
				'label_on'    => esc_html__( 'Show', 'theplus' ),
				'default'     => 'yes',
				'description' => 'Video Info is <a href="https://developers.google.com/youtube/player_parameters#showinfo" class="theplus-btn" target="_blank">deprecated.</a>',
				'condition'   => array(
					'video_type' => array( 'youtube' ),
				),
			)
		);

		$this->add_control(
			'video_touch_disable',
			array(
				'label'     => esc_html__( 'Video Touch Disable', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'No', 'theplus' ),
				'label_on'  => esc_html__( 'Yes', 'theplus' ),
				'default'   => 'no',
			)
		);
		$this->add_control(
			'modest_branding',
			array(
				'label'     => esc_html__( 'Modest Branding', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => array(
					'video_type'     => array( 'youtube' ),
					'video_controls' => 'yes',
				),
			)
		);
		$this->add_control(
			'video_color',
			array(
				'label'     => esc_html__( 'Controls Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'video_type' => array( 'vimeo' ),
				),
			)
		);
		$this->add_control(
			'rel',
			array(
				'label'       => esc_html__( 'Suggested Videos', 'theplus' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_off'   => esc_html__( 'Hide', 'theplus' ),
				'label_on'    => esc_html__( 'Show', 'theplus' ),
				'description' => 'Suggested Videos <a href="https://developers.google.com/youtube/player_parameters#rel" class="theplus-btn" target="_blank">Parameter change.</a>',
				'condition'   => array(
					'video_type' => 'youtube',
				),
			)
		);

		$this->add_control(
			'yt_privacy',
			array(
				'label'       => esc_html__( 'Privacy Mode', 'theplus' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => esc_html__( 'When you turn on privacy mode, YouTube won\'t store information about visitors on your website unless they play the video.', 'theplus' ),
				'condition'   => array(
					'video_type' => 'youtube',
				),
			)
		);
		$this->add_control(
			'vimeo_title',
			array(
				'label'     => esc_html__( 'Intro Title', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'theplus' ),
				'label_on'  => esc_html__( 'Show', 'theplus' ),
				'default'   => 'yes',
				'condition' => array(
					'video_type' => 'vimeo',
				),
			)
		);

		$this->add_control(
			'vimeo_portrait',
			array(
				'label'     => esc_html__( 'Intro Portrait', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'theplus' ),
				'label_on'  => esc_html__( 'Show', 'theplus' ),
				'default'   => 'yes',
				'condition' => array(
					'video_type' => 'vimeo',
				),
			)
		);

		$this->add_control(
			'vimeo_byline',
			array(
				'label'     => esc_html__( 'Intro Byline', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'theplus' ),
				'label_on'  => esc_html__( 'Show', 'theplus' ),
				'default'   => 'yes',
				'condition' => array(
					'video_type' => 'vimeo',
				),
			)
		);

		$this->add_control(
			'view',
			array(
				'label'   => esc_html__( 'View', 'theplus' ),
				'type'    => Controls_Manager::HIDDEN,
				'default' => 'youtube',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_image_icon',
			array(
				'label' => esc_html__( 'Image/Icon', 'theplus' ),
			)
		);
		$this->add_control(
			'image_banner',
			array(
				'label'   => esc_html__( 'Only Icon / Full Banner', 'theplus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'banner_img',
				'options' => array(
					'only_icon'  => esc_html__( 'Only Icon image', 'theplus' ),
					'banner_img' => esc_html__( 'Banner Image', 'theplus' ),
				),
			)
		);
		$this->add_control(
			'only_img',
			array(
				'label'     => esc_html__( 'Choose Image', 'theplus' ),
				'type'      => Controls_Manager::MEDIA,
				'dynamic'   => array( 'active' => true ),
				'condition' => array(
					'image_banner' => 'only_icon',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'only_img_thumbnail',
				'default'   => 'full',
				'separator' => 'none',
				'condition' => array(
					'image_banner' => 'only_icon',
				),
			)
		);

		$this->add_control(
			'icon_align',
			array(
				'label'     => esc_html__( 'Icon Align', 'theplus' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'theplus' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'theplus' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'theplus' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'toggle'    => true,
				'condition' => array(
					'image_banner' => 'only_icon',
				),
			)
		);
		$this->add_control(
			'display_banner_image',
			array(
				'label'     => esc_html__( 'Banner Image', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Off', 'theplus' ),
				'label_on'  => esc_html__( 'On', 'theplus' ),
				'default'   => 'no',
				'condition' => array(
					'image_banner' => 'banner_img',
				),
			)
		);
		$this->add_control(
			'banner_image',
			array(
				'label'     => esc_html__( 'Image Upload', 'theplus' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => '',
				),
				'dynamic'   => array( 'active' => true ),
				'condition' => array(
					'display_banner_image' => 'yes',
					'image_banner'         => 'banner_img',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'banner_image_thumbnail',
				'default'   => 'full',
				'separator' => 'none',
				'condition' => array(
					'display_banner_image' => 'yes',
					'image_banner'         => 'banner_img',
				),
			)
		);
		$this->add_control(
			'image_video',
			array(
				'label'     => esc_html__( 'Icon Upload', 'theplus' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => '',
				),
				'dynamic'   => array( 'active' => true ),
				'condition' => array(
					'display_banner_image' => 'yes',
					'image_banner'         => 'banner_img',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'image_video_thumbnail',
				'default'   => 'full',
				'separator' => 'none',
				'condition' => array(
					'display_banner_image' => 'yes',
					'image_banner'         => 'banner_img',
				),
			)
		);
		$this->add_control(
			'video_title',
			array(
				'type'        => Controls_Manager::TEXT,
				'label'       => esc_html__( 'Title of Video', 'theplus' ),
				'label_block' => true,
				'separator'   => 'before',
				'default'     => esc_html__( 'Video Title', 'theplus' ),
				'dynamic'     => array( 'active' => true ),
				'condition'   => array(
					'display_banner_image' => 'yes',
					'image_banner'         => 'banner_img',
				),
			)
		);
		$this->add_control(
			'video_desc',
			array(
				'type'        => Controls_Manager::TEXTAREA,
				'label'       => esc_html__( 'Video Description', 'theplus' ),
				'label_block' => true,
				'separator'   => 'before',
				'default'     => '',
				'dynamic'     => array( 'active' => true ),
				'condition'   => array(
					'display_banner_image' => 'yes',
					'image_banner'         => 'banner_img',
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_schema_markup',
			array(
				'label' => esc_html__( 'Schema Markup', 'theplus' ),
			)
		);
		$this->add_control(
			'markupSch',
			array(
				'label'     => esc_html__( 'Schema Markup', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Off', 'theplus' ),
				'label_on'  => esc_html__( 'On', 'theplus' ),
				'default'   => 'no',
				'separator' => 'before',
			)
		);
		$this->add_control(
			'video_date',
			array(
				'label'     => __( 'Video Date', 'theplus' ),
				'type'      => Controls_Manager::DATE_TIME,
				'condition' => array(
					'markupSch' => 'yes',
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_styling',
			array(
				'label'     => esc_html__( 'Video Title', 'theplus' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'display_banner_image' => 'yes',
					'image_banner'         => 'banner_img',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'video_title_typography',
				'label'     => esc_html__( 'Title Typography', 'theplus' ),
				'selector'  => '{{WRAPPER}} .ts-video-caption-text',
				'separator' => 'before',
				'condition' => array(
					'display_banner_image' => 'yes',
				),
			)
		);
		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ts-video-caption-text' => 'color: {{VALUE}};',
				),
				'default'   => '#313131',
				'condition' => array(
					'display_banner_image' => 'yes',
				),
			)
		);
		$this->add_control(
			'background_color',
			array(
				'label'     => esc_html__( 'Title Background Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ts-video-caption-text' => 'background: {{VALUE}};',
				),
				'default'   => '#ffffff',
				'condition' => array(
					'display_banner_image' => 'yes',
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_desc_styling',
			array(
				'label'     => esc_html__( 'Video Description', 'theplus' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'display_banner_image' => 'yes',
					'image_banner'         => 'banner_img',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'video_desc_typography',
				'label'     => esc_html__( 'Description Typography', 'theplus' ),
				'selector'  => '{{WRAPPER}} .tp-video-desc',
				'separator' => 'before',
				'condition' => array(
					'display_banner_image' => 'yes',
				),
			)
		);
		$this->add_control(
			'desc_color',
			array(
				'label'     => esc_html__( 'Description Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tp-video-desc' => 'color: {{VALUE}};',
				),
				'default'   => '#888',
				'condition' => array(
					'display_banner_image' => 'yes',
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_video_styling',
			array(
				'label' => esc_html__( 'Video Styling', 'theplus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'popup_video',
			array(
				'label'     => esc_html__( 'Video On Popup', 'theplus' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Off', 'theplus' ),
				'label_on'  => esc_html__( 'On', 'theplus' ),
				'default'   => 'no',
				'condition' => array(
					'image_banner' => 'banner_img',
				),
			)
		);
		$this->start_controls_tabs( 'tabs_effect_video' );

		$this->start_controls_tab(
			'tab_effect_normal',
			array(
				'label' => esc_html__( 'Normal', 'theplus' ),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'video_border',
				'label'    => esc_html__( 'Border', 'theplus' ),
				'selector' => '{{WRAPPER}} .pt_plus_video-box-shadow',
			)
		);
		$this->add_responsive_control(
			'video_bor_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pt_plus_video-box-shadow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};overflow:hidden;',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'theplus' ),
				'selector' => '{{WRAPPER}} .pt_plus_video-box-shadow',
			)
		);
		$this->add_control(
			'video_transform',
			array(
				'type'        => Controls_Manager::TEXT,
				'label'       => esc_html__( 'Transform Effect', 'theplus' ),
				'label_block' => true,
				'separator'   => 'before',
				'placeholder' => esc_html__( 'rotate(2deg) skew(50deg)', 'theplus' ),
				'default'     => '',
			)
		);
		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'css_filters',
				'selector' => '{{WRAPPER}} .pt_plus_video-box-shadow',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_effect_hover',
			array(
				'label' => esc_html__( 'Hover', 'theplus' ),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'video_border_h',
				'label'    => esc_html__( 'Border', 'theplus' ),
				'selector' => '{{WRAPPER}} .pt_plus_video-box-shadow:hover',
			)
		);
		$this->add_responsive_control(
			'video_bor_radius_h',
			array(
				'label'      => esc_html__( 'Border Radius', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pt_plus_video-box-shadow:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};overflow:hidden;',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'hover_box_shadow',
				'label'    => esc_html__( 'Hover Box Shadow', 'theplus' ),
				'selector' => '{{WRAPPER}} .pt_plus_video-box-shadow:hover',
			)
		);
		$this->add_control(
			'hover_video_transform',
			array(
				'type'        => Controls_Manager::TEXT,
				'label'       => esc_html__( 'Hover Transform Effect', 'theplus' ),
				'label_block' => true,
				'placeholder' => esc_html__( 'rotate(2deg) skew(50deg)', 'theplus' ),
				'default'     => '',
			)
		);
		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'css_filters_hover',
				'selector' => '{{WRAPPER}} .pt_plus_video-box-shadow:hover',
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
		$this->start_controls_section(
			'section_icon_styling',
			array(
				'label' => esc_html__( 'Icon Setting', 'theplus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'icon_continuous_animation',
			array(
				'label'       => esc_html__( 'Continuous Animation', 'theplus' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_on'    => esc_html__( 'Yes', 'theplus' ),
				'label_off'   => esc_html__( 'No', 'theplus' ),
				'render_type' => 'template',
				'separator'   => 'before',
			)
		);
		$this->add_control(
			'icon_animation_effect',
			array(
				'label'       => esc_html__( 'Animation Effect', 'theplus' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'pulse',
				'options'     => array(
					'pulse'      => esc_html__( 'Pulse', 'theplus' ),
					'floating'   => esc_html__( 'Floating', 'theplus' ),
					'tossing'    => esc_html__( 'Tossing', 'theplus' ),
					'rotating'   => esc_html__( 'Rotating', 'theplus' ),
					'drop_waves' => esc_html__( 'Drop Waves', 'theplus' ),
				),
				'render_type' => 'template',
				'condition'   => array(
					'icon_continuous_animation' => 'yes',
				),
			)
		);
		$this->add_control(
			'icon_animation_hover',
			array(
				'label'       => esc_html__( 'Hover Animation', 'theplus' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_on'    => esc_html__( 'Yes', 'theplus' ),
				'label_off'   => esc_html__( 'No', 'theplus' ),
				'render_type' => 'template',
				'condition'   => array(
					'icon_continuous_animation' => 'yes',
				),
			)
		);
		$this->add_control(
			'icon_animation_duration',
			array(
				'label'      => esc_html__( 'Duration Time', 'theplus' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => 's',
				'range'      => array(
					's' => array(
						'min'  => 0.5,
						'max'  => 50,
						'step' => 0.1,
					),
				),
				'default'    => array(
					'unit' => 's',
					'size' => 2.5,
				),
				'selectors'  => array(
					'{{WRAPPER}} .pt_plus_video_player .tp-video-icon-inner,{{WRAPPER}} .pt_plus_video_player .tp-video-popup,{{WRAPPER}} .pt_plus_video_player .tp-video-popup-icon .tp-video-icon' => 'animation-duration: {{SIZE}}{{UNIT}};-webkit-animation-duration: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'icon_continuous_animation' => 'yes',
					'icon_animation_effect!'    => 'drop_waves',
				),
			)
		);
		$this->add_control(
			'icon_transform_origin',
			array(
				'label'       => esc_html__( 'Transform Origin', 'theplus' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'center center',
				'options'     => array(
					'top left'      => esc_html__( 'Top Left', 'theplus' ),
					'top center"'   => esc_html__( 'Top Center', 'theplus' ),
					'top right'     => esc_html__( 'Top Right', 'theplus' ),
					'center left'   => esc_html__( 'Center Left', 'theplus' ),
					'center center' => esc_html__( 'Center Center', 'theplus' ),
					'center right'  => esc_html__( 'Center Right', 'theplus' ),
					'bottom left'   => esc_html__( 'Bottom Left', 'theplus' ),
					'bottom center' => esc_html__( 'Bottom Center', 'theplus' ),
					'bottom right'  => esc_html__( 'Bottom Right', 'theplus' ),
				),
				'selectors'   => array(
					'{{WRAPPER}} .pt_plus_video_player .tp-video-icon-inner,{{WRAPPER}} .pt_plus_video_player .tp-video-popup,{{WRAPPER}} .pt_plus_video_player .tp-video-popup-icon .tp-video-icon' => '-webkit-transform-origin: {{VALUE}};-moz-transform-origin: {{VALUE}};-ms-transform-origin: {{VALUE}};-o-transform-origin: {{VALUE}};transform-origin: {{VALUE}};',
				),
				'render_type' => 'template',
				'condition'   => array(
					'icon_continuous_animation' => 'yes',
					'icon_animation_effect'     => 'rotating',
				),
			)
		);
		$this->add_control(
			'drop_waves_color',
			array(
				'label'     => esc_html__( 'Drop Wave Color', 'theplus' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_video_player .image-drop_waves:after,{{WRAPPER}} .pt_plus_video_player .hover_drop_waves:after' => 'background: {{VALUE}}',
				),
				'condition' => array(
					'icon_continuous_animation' => 'yes',
					'icon_animation_effect'     => 'drop_waves',
				),
			)
		);
		$this->add_control(
			'icon_radius',
			array(
				'label'      => esc_html__( 'Icon Radius', 'theplus' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pt_plus_video_player .tp-video-icon-inner,{{WRAPPER}} .pt_plus_video_player .tp-video-popup,{{WRAPPER}} .pt_plus_video_player .tp-video-popup-icon .tp-video-icon,{{WRAPPER}} .pt_plus_video_player .image-drop_waves:after,{{WRAPPER}} .pt_plus_video_player .hover_drop_waves:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'play_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'theplus' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => 'px',
				'range'      => array(
					'px' => array(
						'min'  => 20,
						'max'  => 500,
						'step' => 2,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 100,
				),
				'selectors'  => array(
					'{{WRAPPER}} .pt_plus_video_player .tp-video-icon-inner,{{WRAPPER}} .pt_plus_video_player .tp-video-popup,{{WRAPPER}} .pt_plus_video_player .tp-video-popup-icon' => 'max-width: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};max-height: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_plus_extra_adv',
			array(
				'label' => esc_html__( 'Plus Extras', 'theplus' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'section_image_masking_styling',
			array(
				'label' => esc_html__( 'Mask Image', 'theplus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'mask_image_display',
			array(
				'label'       => esc_html__( 'Mask Image Shape', 'theplus' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => esc_html__( 'Use PNG image with the shape you want to mask around Media.', 'theplus' ),
				'label_on'    => esc_html__( 'On', 'theplus' ),
				'label_off'   => esc_html__( 'Off', 'theplus' ),
				'default'     => 'no',
				'separator'   => 'before',
			)
		);
		$this->add_control(
			'mask_shape_image',
			array(
				'label'     => esc_html__( 'Mask Image', 'theplus' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_video-box-shadow.creative-mask-media' => 'mask-image: url({{URL}});-webkit-mask-image: url({{URL}});',
				),
				'condition' => array(
					'mask_image_display' => 'yes',
				),
			)
		);
		$this->add_responsive_control(
			'mask_shape_image_position',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => esc_html__( 'Mask Position', 'theplus' ),
				'default'   => 'center center',
				'options'   => theplus_get_image_position_options(),
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_video-box-shadow.creative-mask-media' => '-webkit-mask-position:{{VALUE}};',
				),
				'condition' => array(
					'mask_image_display' => 'yes',
				),
			)
		);
		$this->add_responsive_control(
			'mask_shape_image_repeat',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => esc_html__( 'Mask Repeat', 'theplus' ),
				'default'   => 'no-repeat',
				'options'   => theplus_get_image_reapeat_options(),
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_video-box-shadow.creative-mask-media' => 'mask-repeat:{{VALUE}};-webkit-mask-repeat:{{VALUE}};',
				),
				'condition' => array(
					'mask_image_display' => 'yes',
				),
			)
		);
		$this->add_responsive_control(
			'mask_shape_image_size',
			array(
				'type'      => Controls_Manager::SELECT,
				'label'     => esc_html__( 'Mask Size', 'theplus' ),
				'default'   => 'contain',
				'options'   => theplus_get_image_size_options(),
				'selectors' => array(
					'{{WRAPPER}} .pt_plus_video-box-shadow.creative-mask-media' => 'mask-size:{{VALUE}};-webkit-mask-size:{{VALUE}};',
				),
				'condition' => array(
					'mask_image_display' => 'yes',
				),
			)
		);
		$this->end_controls_section();
		include THEPLUS_PATH . 'modules/widgets/theplus-widget-animation.php';
	}

	/**
	 * Render Video-player.
	 *
	 * @since 1.0.0
	 * @version 5.4.2
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		$mainsch   = '';
		$thumbsch  = '';
		$titlesch  = '';
		$descsch   = '';
		$uploadate = '';

		$markup_on = ! empty( $settings['markupSch'] ) ? $settings['markupSch'] : '';

		if ( 'yes' === $markup_on ) {
			$mainsch   = 'itemscope="" itemprop="VideoObject" itemtype="http://schema.org/VideoObject"';
			$thumbsch  = 'itemprop="thumbnailUrl"';
			$titlesch  = 'itemprop="name"';
			$descsch   = 'itemprop="description"';
			$uploadate = $this->get_settings( 'video_date' );
		}

		$uid = uniqid( 'video_player' );

		$video_type = ! empty( $settings['video_type'] ) ? $settings['video_type'] : 'youtube';
		$youtube_id = '';
		$vimeo_id   = '';
		$mp4_link   = '';

		$y_id = ! empty( $settings['youtube_id'] ) ? $settings['youtube_id'] : '';
		$v_id = ! empty( $settings['vimeo_id'] ) ? $settings['vimeo_id'] : '';

		if ( ! empty( $y_id ) ) {
			$youtube_id = $y_id;
		}

		if ( ! empty( $v_id ) ) {
			$vimeo_id = $v_id;
		}

		if ( ! empty( $settings['mp4_link']['url'] ) ) {
			$mp4_link = $settings['mp4_link']['url'];
		}
		$icon_effect = '';

		$conti_ani = ! empty( $settings['icon_continuous_animation'] ) ? $settings['icon_continuous_animation'] : '';

		if ( 'yes' === $conti_ani ) {

			$icon_ani = ! empty( $settings['icon_animation_hover'] ) ? $settings['icon_animation_hover'] : '';

			if ( 'yes' === $icon_ani ) {
				$animation_class = 'hover_';
			} else {
				$animation_class = 'image-';
			}
			$icon_effect = $animation_class . $settings['icon_animation_effect'];
		}

		$title = '';

		$banner_url  = '';
		$video_space = '';
		$image_video = '';
		$image_alt   = '';
		$only_image  = '';

		$video_content    = '';
		$banner_image     = '';
		$image_video_url  = '';
		$icon_align_video = '';

		$v_title    = ! empty( $settings['video_title'] ) ? $settings['video_title'] : '';
		$video_desc = ! empty( $settings['video_desc'] ) ? $settings['video_desc'] : '';

		if ( ! empty( $v_title ) ) {

			$title = '<div class="ts-video-caption-text" >';

			$title .= '<span ' . esc_attr( $titlesch ) . '>' . wp_kses_post( $v_title ) . '</span>';

			if ( ! empty( $video_desc ) ) {
				$title .= '<div class="tp-video-desc" ' . esc_attr( $descsch ) . ' >';

				$title .= wp_kses_post( $video_desc );

				$title .= '</div>';
			}

			$title .= '</div>';
		}

		$img_url = ! empty( $settings['only_img'] ) ? $settings['only_img'] : '';

		if ( ! empty( $img_url['url'] ) ) {

			$only_img = $img_url['id'];
			$img      = wp_get_attachment_image_src( $only_img, $settings['only_img_thumbnail_size'] );

			$only_img_icon = isset( $img[0] ) ? $img[0] : '';
			$only_image   .= '<img class="ts-video-only-icon" src="' . esc_url( $only_img_icon ) . '" alt="' . esc_html__( 'play-icon', 'theplus' ) . '" />';
		}

		$vid_url = ! empty( $settings['image_video'] ) ? $settings['image_video'] : '';

		if ( ! empty( $vid_url['url'] ) ) {

			$image_video_src = $vid_url['id'];

			$img         = wp_get_attachment_image_src( $image_video_src, $settings['image_video_thumbnail_size'] );
			$image_video = isset( $img[0] ) ? $img[0] : '';

			$image_id  = $vid_url['id'];
			$image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );

			if ( ! $image_alt ) {
				$image_alt = get_the_title( $image_id );
			} elseif ( ! $image_alt ) {
				$image_alt = 'Plus video thumb';
			}

			$image_video_url .= '<div class="tp-video-icon-inner ' . esc_attr( $icon_effect ) . '"><img class="ts-video-icon" src="' . esc_url( $image_video ) . '"  alt="' . esc_attr( $image_alt ) . '" /></div>';
		}

		$bimg_url = ! empty( $settings['banner_image'] ) ? $settings['banner_image'] : '';

		if ( ! empty( $bimg_url['url'] ) ) {
			$banner_image = $bimg_url['id'];

			$img = wp_get_attachment_image_src( $banner_image, $settings['banner_image_thumbnail_size'] );

			$banner_image = isset( $img[0] ) ? $img[0] : '';
			$banner_url  .= '<img class="ts-video-image-zoom set-image" ' . esc_attr( $thumbsch ) . ' content="' . esc_url( $banner_image ) . '" src="' . esc_url( $banner_image ) . '" alt="" /><div class="tp-video-popup-icon"> <div class="tp-video-icon ' . esc_attr( $icon_effect ) . '"><img class="ts-video-caption" src="' . esc_url( $image_video ) . '" alt="' . esc_attr( $image_alt ) . '" /></div></div>' . $title;
		}

		$youtube_attr    = '';
		$video_touchable = '';
		$self_video_attr = '';

		$vimeo_frame_attr   = '';
		$youtube_frame_attr = '';

		$vid_autoplay = ! empty( $settings['video_autoplay'] ) ? $settings['video_autoplay'] : '';
		if ( 'yes' === $vid_autoplay ) {

			if ( 'youtube' === $video_type ) {
				$youtube_frame_attr .= '&amp;autoplay=1&amp;version=3';
				$youtube_attr       .= ' allow="autoplay; encrypted-media"  ';
			}

			if ( 'vimeo' === $video_type ) {
				$vimeo_frame_attr .= '&amp;autoplay=1';
			}

			if ( 'self-hosted' === $video_type ) {
				$self_video_attr .= ' autoplay';
				$self_video_attr .= ' playsinline=""';
			}
		}

		$vid_loop = ! empty( $settings['video_loop'] ) ? $settings['video_loop'] : '';

		if ( 'yes' === $vid_loop ) {

			if ( 'youtube' === $video_type ) {
				$youtube_frame_attr .= '&amp;loop=1&amp;playlist=' . esc_attr( $y_id );
			}
			if ( 'vimeo' === $video_type ) {
				$vimeo_frame_attr .= '&amp;loop=1';
			}
			if ( 'self-hosted' === $video_type ) {
				$self_video_attr .= ' loop ';
			}
		}

		$vid_control = ! empty( $settings['video_controls'] ) ? $settings['video_controls'] : '';

		if ( 'yes' === $vid_control ) {

			if ( 'youtube' === $video_type ) {
				$youtube_frame_attr .= '&amp;controls=1';
			}

			if ( 'self-hosted' === $video_type ) {
				$self_video_attr .= ' controls ';
			}
		} elseif ( 'youtube' === $video_type ) {
			$youtube_frame_attr .= '&amp;controls=0';
		}

		$vid_info = ! empty( $settings['showinfo'] ) ? $settings['showinfo'] : '';

		if ( 'yes' === $vid_info ) {

			if ( 'youtube' === $video_type ) {
				$youtube_frame_attr .= '&amp;showinfo=1';
			}
		} elseif ( 'youtube' === $video_type ) {
			$youtube_frame_attr .= '&amp;showinfo=0';
		}

		$mode_brand = ! empty( $settings['modest_branding'] ) ? $settings['modest_branding'] : '';

		if ( 'yes' === $mode_brand ) {
			if ( 'youtube' === $video_type ) {
				$youtube_frame_attr .= '&amp;modestbranding=1';
			}
		} elseif ( 'youtube' === $video_type ) {
			$youtube_frame_attr .= '&amp;modestbranding=0';
		}

		$sugg_vid = ! empty( $settings['rel'] ) ? $settings['rel'] : '';

		if ( 'yes' === $sugg_vid ) {
			if ( 'youtube' === $video_type ) {
				$youtube_frame_attr .= '&amp;rel=0';
			}
		} elseif ( 'youtube' === $video_type ) {
			$youtube_frame_attr .= '&amp;rel=1';
		}
		$youtube_privacy = '';

		$y_pricacy = ! empty( $settings['yt_privacy'] ) ? $settings['yt_privacy'] : '';

		if ( 'yes' === $y_pricacy ) {

			if ( 'youtube' === $video_type ) {
				$youtube_privacy .= '-nocookie';
			}
		} elseif ( 'youtube' === $video_type ) {
				$youtube_privacy .= '';
		}

		$v_mute = ! empty( $settings['video_muted'] ) ? $settings['video_muted'] : '';

		if ( 'yes' === $v_mute ) {

			if ( 'youtube' === $video_type ) {
				$youtube_frame_attr .= '&amp;mute=1';
			}
			if ( 'vimeo' === $video_type ) {
				$vimeo_frame_attr .= '&amp;muted=1';
			}
			if ( 'self-hosted' === $video_type ) {
				$self_video_attr .= ' muted ';
			}
		}

		if ( ! empty( $settings['video_color'] ) ) {
			if ( 'vimeo' === $video_type ) {
				$video_color       = str_replace( '#', '', $settings['video_color'] );
				$vimeo_frame_attr .= '&amp;color=' . $video_color . ';';
			}
		}

		$in_title = ! empty( $settings['vimeo_title'] ) ? $settings['vimeo_title'] : '';

		if ( 'yes' === $in_title ) {
			if ( 'vimeo' === $video_type ) {
				$vimeo_frame_attr .= '&amp;title=1;';
			}
		} else {
			$vimeo_frame_attr .= '&amp;title=0;';
		}

		$vimo_portit = ! empty( $settings['vimeo_portrait'] ) ? $settings['vimeo_portrait'] : '';

		if ( 'yes' === $vimo_portit ) {
			if ( 'vimeo' === $video_type ) {
				$vimeo_frame_attr .= '&amp;portrait=1;';
			}
		} else {
			$vimeo_frame_attr .= '&amp;portrait=0;';
		}

		$vim_byline = ! empty( $settings['vimeo_byline'] ) ? $settings['vimeo_byline'] : '';

		if ( 'yes' === $vim_byline ) {

			if ( 'vimeo' === $video_type ) {
				$vimeo_frame_attr .= '&amp;byline=1;';
			}
		} else {
			$vimeo_frame_attr .= '&amp;byline=0;';
		}

		$vid_touch = ! empty( $settings['video_touch_disable'] ) ? $settings['video_touch_disable'] : '';
		if ( 'yes' === $vid_touch ) {
			$video_touchable = ' not-touch ';
		}

		$image_banner = ! empty( $settings['image_banner'] ) ? $settings['image_banner'] : 'banner_img';

		$display_banner_image = ! empty( $settings['display_banner_image'] ) ? $settings['display_banner_image'] : '';

		if ( 'banner_img' === $image_banner ) {

			if ( 'yes' === $display_banner_image ) {

				$popup_vid = ! empty( $settings['popup_video'] ) ? $settings['popup_video'] : 'no';

				if ( 'yes' === $popup_vid ) {

					if ( 'youtube' === $video_type ) {
						$video_content .= '<a href="https://www.youtube' . esc_attr( $youtube_privacy ) . '.com/embed/' . esc_attr( $youtube_id ) . '" data-lity >' . $banner_url . '</a>';
					} elseif ( 'vimeo' === $video_type ) {
						$video_content .= '<a href="https://player.vimeo.com/video/' . esc_attr( $vimeo_id ) . '" data-lity >' . $banner_url . '</a>';
					} elseif ( 'self-hosted' === $video_type ) {
						$video_content .= '<a href="' . esc_url( $mp4_link ) . '" data-lity type="video/mp4">' . $banner_url . '</a>';
					}

					$video_space = '';
				} elseif ( 'youtube' === $video_type ) {
						$video_content .= '<div class="ts-video-wrapper ts-video-hover-effect-zoom ts-type-' . esc_attr( $video_type ) . '" data-mode="lazyload" data-provider="' . esc_attr( $video_type ) . '" id="ts-video-video-6" ' . esc_attr( $mainsch ) . ' data-grow=""><div class="ts-video-embed-container" ><img class="ts-video-thumbnail" data-object-fit="" ' . esc_attr( $thumbsch ) . ' content="' . esc_url( $banner_image ) . '" src="' . esc_url( $banner_image ) . '" alt="' . esc_attr( 'Video Thumbnail' ) . '"><h5 class="ts-video-title">' . $title . '</h5><span class="ts-video-lazyload" data-allowfullscreen="" data-class="pt-plus-video-frame fitvidsignore" data-frameborder="0" data-scrolling="no" data-src="https://www.youtube' . esc_attr( $youtube_privacy ) . '.com/embed/' . esc_attr( $youtube_id ) . '?html5=1&amp;title=0&amp;byline=0&amp;portrait=0&amp;autoplay=1' . esc_attr( $youtube_frame_attr ) . '"  data-sandbox="allow-scripts allow-same-origin allow-presentation allow-forms" data-width="480" data-height="270"></span><button class="ts-video-play-btn ts-video-blay-btn-youtube" type="button">' . $image_video_url . '</button>';
					if ( ! empty( $settings['markupSch'] ) ) {
						$video_content .= '<div class="tp-video-upload" itemprop="uploadDate" content="' . esc_attr( $uploadate ) . '" style="display: none;"></div><div class="tp-video-upload" itemprop="contentUrl" content="https://www.youtube' . esc_attr( $youtube_privacy ) . '.com/embed/' . esc_attr( $youtube_id ) . '?html5=1&amp;title=0&amp;byline=0&amp;portrait=0&amp;autoplay=1' . esc_attr( $youtube_frame_attr ) . '" style="display: none;"></div>';
					}
						$video_content .= '</div></div>';
				} elseif ( 'vimeo' === $video_type ) {
					$video_content .= '<div class="ts-video-wrapper ts-video-hover-effect-zoom ts-type-' . esc_attr( $video_type ) . '" data-mode="lazyload" data-provider="' . esc_attr( $video_type ) . '" id="ts-video-video-6" ' . esc_attr( $mainsch ) . ' data-grow=""><div class="ts-video-embed-container" ><img class="ts-video-thumbnail" data-object-fit="" ' . esc_attr( $thumbsch ) . ' content="' . esc_url( $banner_image ) . '" src="' . esc_url( $banner_image ) . '" alt="' . esc_attr( 'Video Thumbnail' ) . '"><h5 class="ts-video-title">' . $title . '</h5><span class="ts-video-lazyload" data-allowfullscreen="" data-class="pt-plus-video-frame fitvidsignore" data-frameborder="0" data-scrolling="no" data-src="https://player.vimeo.com/video/' . esc_attr( $vimeo_id ) . '?html5=1&amp;title=0&amp;byline=0&amp;portrait=0&amp;autoplay=1" data-sandbox="allow-scripts allow-same-origin allow-presentation allow-forms" data-width="480" data-height="270"></span><button class="ts-video-play-btn ts-video-blay-btn-youtube" type="button">' . $image_video_url . '</button>';
					if ( ! empty( $settings['markupSch'] ) ) {
						$video_content .= '<div class="tp-video-upload" itemprop="uploadDate" content="' . esc_attr( $uploadate ) . '" style="display: none;"></div><div class="tp-video-upload" itemprop="contentUrl" content="https://player.vimeo.com/video/' . esc_attr( $vimeo_id ) . '?html5=1&amp;title=0&amp;byline=0&amp;portrait=0&amp;autoplay=1" style="display: none;"></div>';
					}
					$video_content .= '</div></div>';
				} elseif ( 'self-hosted' === $video_type ) {
					$video_content .= '<div class="ts-video-wrapper ts-video-hover-effect-zoom ts-type-' . esc_attr( $video_type ) . '" data-mode="lazyload" data-provider="' . esc_attr( $video_type ) . '" id="ts-video-video-6" ' . esc_attr( $mainsch ) . ' data-grow=""><div class="ts-video-embed-container" ><img class="ts-video-thumbnail" data-object-fit="" ' . esc_attr( $thumbsch ) . ' content="' . esc_url( $banner_image ) . '" src="' . esc_url( $banner_image ) . '" alt="' . esc_attr( 'Video Thumbnail' ) . '"><h5 class="ts-video-title">' . $title . '</h5><div class="video_container"><video class="ts-video-poster" width="100%" poster="' . esc_url( $banner_image ) . '" controls > <source src="' . esc_url( $mp4_link ) . '" type="video/mp4" ></video></div></span><button class="ts-video-play-btn ts-video-blay-btn-youtube" type="button">' . $image_video_url . '</button>';
					if ( ! empty( $settings['markupSch'] ) ) {
						$video_content .= '<div class="tp-video-upload" itemprop="uploadDate" content="' . esc_attr( $uploadate ) . '" style="display: none;"></div><div class="tp-video-upload" itemprop="contentUrl" content="' . esc_url( $mp4_link ) . '" style="display: none;"></div>';
					}
					$video_content .= '</div></div>';
				}
			} elseif ( 'youtube' === $video_type ) {
					$video_content .= '<div class="ts-video-wrapper embed-container  ts-type-' . esc_attr( $video_type ) . '"><iframe id="' . esc_attr( $uid ) . '" width="100%"  src="https://www.youtube' . esc_attr( $youtube_privacy ) . '.com/embed/' . esc_attr( $youtube_id ) . '?&amp;autohide=1&amp;showtitle=0' . esc_attr( $youtube_frame_attr ) . '" ' . esc_attr( $youtube_attr ) . ' frameborder="0" allowfullscreen></iframe></div>';
			} elseif ( 'vimeo' === $video_type ) {
				$video_content .= '<div class="ts-video-wrapper embed-container  ts-type-' . esc_attr( $video_type ) . '"><iframe id="' . esc_attr( $uid ) . '" src="https://player.vimeo.com/video/' . esc_attr( $vimeo_id ) . '?html5=1&amp;title=0&amp;byline=0&amp;portrait=0&amp;' . esc_attr( $vimeo_frame_attr ) . '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>';
			} elseif ( 'self-hosted' === $video_type ) {
				$video_content .= '<div class="ts-video-wrapper ts-type-' . esc_attr( $video_type ) . '"><video class="lazy" width="100%" ' . esc_attr( $self_video_attr ) . '> <source src="' . esc_url( $mp4_link ) . '" type="video/mp4" ></video></div>';
			}
		} elseif ( 'only_icon' === $image_banner ) {
			if ( 'yes' !== $display_banner_image ) {
				if ( 'youtube' === $video_type ) {
					$video_content .= '<a href="https://www.youtube.com/embed/' . esc_attr( $youtube_id ) . '" class="tp-video-popup ' . esc_attr( $icon_effect ) . '" data-lity >' . $only_image . '</a>';
				} elseif ( 'vimeo' === $video_type ) {
					$video_content .= '<a href="https://player.vimeo.com/video/' . esc_attr( $vimeo_id ) . '" class="tp-video-popup ' . esc_attr( $icon_effect ) . '" data-lity >' . $only_image . '</a>';
				} elseif ( 'self-hosted' === $video_type ) {
					$video_content .= '<a href="' . esc_url( $mp4_link ) . '" class="tp-video-popup ' . esc_attr( $icon_effect ) . '" data-lity type="video/mp4">' . $only_image . '</a>';
				}
			}

			$icon_align_video = $settings['icon_align'];
		}

		include THEPLUS_PATH . 'modules/widgets/theplus-widget-animation-attr.php';

		$PlusExtra_Class = '';
		include THEPLUS_PATH . 'modules/widgets/theplus-widgets-extra.php';

		$mask_image = '';

		$mask_img = ! empty( $settings['mask_image_display'] ) ? $settings['mask_image_display'] : '';

		if ( 'yes' === $mask_img ) {
			$mask_image = ' creative-mask-media';
		}

		$video_player  = '<div class="pt_plus_video-box-shadow ' . esc_attr( $uid ) . ' ' . esc_attr( $animated_class ) . ' ' . esc_attr( $mask_image ) . '" ' . $animation_attr . '>';
		$video_player .= '<div class="pt_plus_video_player ' . esc_attr( $video_touchable ) . ' ' . esc_attr( $video_space ) . ' text-' . esc_attr( $icon_align_video ) . '">';
		$video_player .= $video_content;

		if ( 'yes' !== $display_banner_image ) {
			$video_player .= $banner_url;
		}

		$video_player .= '</div>';
		$video_player .= '</div>';

		$css_rules = '';
		if ( ! empty( $settings['video_transform'] ) || ! empty( $settings['hover_video_transform'] ) ) {
			$css_rules .= '<style>';
			if ( ! empty( $settings['video_transform'] ) ) {
				$css_rules .= '.' . esc_attr( $uid ) . '.pt_plus_video-box-shadow{-webkit-transform: ' . esc_attr( $settings['video_transform'] ) . ';-ms-transform: ' . esc_attr( $settings['video_transform'] ) . ';-moz-transform: ' . esc_attr( $settings['video_transform'] ) . ';-o-transform: ' . esc_attr( $settings['video_transform'] ) . ';transform: ' . esc_attr( $settings['video_transform'] ) . ';}';
			}
			if ( ! empty( $settings['hover_video_transform'] ) ) {
				$css_rules .= '.' . esc_attr( $uid ) . '.pt_plus_video-box-shadow:hover{-webkit-transform: ' . esc_attr( $settings['hover_video_transform'] ) . ';-ms-transform: ' . esc_attr( $settings['hover_video_transform'] ) . ';-moz-transform: ' . esc_attr( $settings['hover_video_transform'] ) . ';-o-transform: ' . esc_attr( $settings['hover_video_transform'] ) . ';transform: ' . esc_attr( $settings['hover_video_transform'] ) . ';}';
			}
			$css_rules .= '</style>';
		}

			echo $css_rules . $before_content . $video_player . $after_content;
	}
}
<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor image gallery widget.
 *
 * Elementor widget that displays a set of images in an aligned grid.
 *
 * @since 1.0.0
 */
class Widget_Photo_Album extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve image gallery widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'photo-album';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve image gallery widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Photo Album', 'elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve image gallery widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	/**
	 * Add lightbox data to image link.
	 *
	 * Used to add lightbox data attributes to image link HTML.
	 *
	 * @since 1.6.0
	 * @access public
	 *
	 * @param string $link_html Image link HTML.
	 *
	 * @return string Image link HTML with lightbox data attributes.
	 */
	public function add_lightbox_data_to_image_link( $link_html ) {
		return preg_replace( '/^<a/', '<a ' . $this->get_render_attribute_string( 'link' ), $link_html );
	}

	/**
	 * Register image gallery widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_gallery',
			[
				'label' => __( 'Photo Album', 'elementor' ),
			]
		);

		$this->add_control(
			'wp_gallery',
			[
				'label'      => __( 'Add Images', 'elementor' ),
				'type'       => Controls_Manager::GALLERY,
				'show_label' => false,
				'dynamic'    => [
					'active' => true,
				],
			]
		);


		$this->end_controls_section();
	}

	/**
	 * Render image gallery widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( ! $settings['wp_gallery'] ) {
			return;
		}

		$ids = wp_list_pluck( $settings['wp_gallery'], 'id' );

		?>
        <div class="photo-item" id="<?php echo $this->get_id() ?>">
            <img src="<?php echo wp_get_attachment_image_url( $ids[2] ) ?>"
                 alt="">
            <img src="<?php echo wp_get_attachment_image_url( $ids[1] ) ?>"
                 alt="">
            <img src="<?php echo wp_get_attachment_image_url( $ids[0] ) ?>"
                 alt="">
            <p><?php echo __(get_post( $ids[0] )->post_content) ?></p>
            <button class="show-all" data-id="<?php echo $this->get_id() ?>">Show all</button>
        </div>
        <div class="photo-wrapper" id="photo-<?php echo $this->get_id() ?>">
            <button class="btn-close">X</button>
            <div class="photo-box">
				<?php
				foreach ( $ids as $item ):
					?>
                    <div data-id="<?php echo $this->get_id() ?>">
                        <img src="<?php echo wp_get_attachment_image_url( $item ) ?>"
                             alt="">
                        <p><?php echo __(get_post( $item )->post_content) ?></p>
                    </div>
				<?php endforeach; ?>
            </div>
        </div>
		<?php
	}
}

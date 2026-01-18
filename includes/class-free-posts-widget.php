<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;

class FPE_Free_Posts_Widget extends Widget_Base {

    public function get_name() {
        return 'fpe_free_posts';
    }

    public function get_title() {
        return esc_html__( 'Free Posts', 'free-posts-in-elementor' );
    }

    public function get_icon() {
        return 'eicon-posts-grid';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    public function get_keywords() {
        return [ 'posts', 'blog', 'grid', 'loop', 'archive', 'free posts' ];
    }

    public function get_style_depends() {
        return [ 'fpe-widget' ];
    }

    protected function register_controls() {
        $this->register_query_controls();
        $this->register_layout_controls();
        $this->register_content_controls();
        $this->register_style_controls();
    }

    private function register_query_controls() {
        $this->start_controls_section(
            'section_query',
            [
                'label' => esc_html__( 'Query', 'free-posts-in-elementor' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'source',
            [
                'label'   => esc_html__( 'Source', 'free-posts-in-elementor' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'posts',
                'options' => [
                    'posts' => esc_html__( 'Posts', 'free-posts-in-elementor' ),
                    'page'  => esc_html__( 'Pages', 'free-posts-in-elementor' ),
                ],
            ]
        );

        $this->add_control(
            'categories',
            [
                'label'       => esc_html__( 'Categories', 'free-posts-in-elementor' ),
                'type'        => Controls_Manager::SELECT2,
                'options'     => $this->get_categories_options(),
                'multiple'    => true,
                'label_block' => true,
                'condition'   => [ 'source' => 'posts' ],
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label'   => esc_html__( 'Posts Per Page', 'free-posts-in-elementor' ),
                'type'    => Controls_Manager::NUMBER,
                'min'     => 1,
                'max'     => 50,
                'step'    => 1,
                'default' => 6,
            ]
        );

        $this->add_control(
            'order_by',
            [
                'label'   => esc_html__( 'Order By', 'free-posts-in-elementor' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'date'          => esc_html__( 'Date', 'free-posts-in-elementor' ),
                    'title'         => esc_html__( 'Title', 'free-posts-in-elementor' ),
                    'menu_order'    => esc_html__( 'Menu Order', 'free-posts-in-elementor' ),
                    'modified'      => esc_html__( 'Last Modified', 'free-posts-in-elementor' ),
                    'comment_count' => esc_html__( 'Comment Count', 'free-posts-in-elementor' ),
                    'rand'          => esc_html__( 'Random', 'free-posts-in-elementor' ),
                ],
            ]
        );

        $this->add_control(
            'order',
            [
                'label'   => esc_html__( 'Order', 'free-posts-in-elementor' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => [
                    'DESC' => esc_html__( 'Descending', 'free-posts-in-elementor' ),
                    'ASC'  => esc_html__( 'Ascending', 'free-posts-in-elementor' ),
                ],
            ]
        );

        $this->add_control(
            'ignore_sticky_posts',
            [
                'label'        => esc_html__( 'Ignore Sticky Posts', 'free-posts-in-elementor' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'free-posts-in-elementor' ),
                'label_off'    => esc_html__( 'No', 'free-posts-in-elementor' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [ 'source' => 'posts' ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_layout_controls() {
        $this->start_controls_section(
            'section_layout',
            [
                'label' => esc_html__( 'Layout', 'free-posts-in-elementor' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label'          => esc_html__( 'Columns', 'free-posts-in-elementor' ),
                'type'           => Controls_Manager::SELECT,
                'default'        => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options'        => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
                'selectors'      => [
                    '{{WRAPPER}} .fpe-posts' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
                ],
            ]
        );

        $this->add_responsive_control(
            'gap',
            [
                'label'     => esc_html__( 'Gap', 'free-posts-in-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units'=> [ 'px', 'em', 'rem' ],
                'range'     => [
                    'px'  => [ 'min' => 0, 'max' => 80 ],
                    'em'  => [ 'min' => 0, 'max' => 5 ],
                    'rem' => [ 'min' => 0, 'max' => 5 ],
                ],
                'default'   => [ 'size' => 24, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .fpe-posts' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_ratio',
            [
                'label'   => esc_html__( 'Image Ratio', 'free-posts-in-elementor' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '16/9',
                'options' => [
                    '16/9' => '16:9',
                    '4/3'  => '4:3',
                    '1/1'  => '1:1',
                    '3/4'  => '3:4',
                ],
                'selectors' => [
                    '{{WRAPPER}} .fpe-thumb' => 'aspect-ratio: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_content_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Content', 'free-posts-in-elementor' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_image',
            [
                'label'        => esc_html__( 'Show Image', 'free-posts-in-elementor' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'free-posts-in-elementor' ),
                'label_off'    => esc_html__( 'Hide', 'free-posts-in-elementor' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'show_title',
            [
                'label'        => esc_html__( 'Show Title', 'free-posts-in-elementor' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'free-posts-in-elementor' ),
                'label_off'    => esc_html__( 'Hide', 'free-posts-in-elementor' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'show_excerpt',
            [
                'label'        => esc_html__( 'Show Excerpt', 'free-posts-in-elementor' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'free-posts-in-elementor' ),
                'label_off'    => esc_html__( 'Hide', 'free-posts-in-elementor' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'excerpt_length',
            [
                'label'     => esc_html__( 'Excerpt Length (words)', 'free-posts-in-elementor' ),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 0,
                'max'       => 80,
                'step'      => 1,
                'default'   => 20,
                'condition' => [ 'show_excerpt' => 'yes' ],
            ]
        );

        $this->add_control(
            'show_date',
            [
                'label'        => esc_html__( 'Show Date', 'free-posts-in-elementor' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'free-posts-in-elementor' ),
                'label_off'    => esc_html__( 'Hide', 'free-posts-in-elementor' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    private function register_style_controls() {
        // Card
        $this->start_controls_section(
            'section_style_card',
            [
                'label' => esc_html__( 'Card', 'free-posts-in-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_bg',
            [
                'label'     => esc_html__( 'Background Color', 'free-posts-in-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .fpe-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'card_border',
                'selector' => '{{WRAPPER}} .fpe-card',
            ]
        );

        $this->add_control(
            'card_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'free-posts-in-elementor' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'selectors'  => [
                    '{{WRAPPER}} .fpe-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label'      => esc_html__( 'Padding', 'free-posts-in-elementor' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', 'rem' ],
                'default'    => [ 'top' => 18, 'right' => 18, 'bottom' => 18, 'left' => 18, 'unit' => 'px', 'isLinked' => true ],
                'selectors'  => [
                    '{{WRAPPER}} .fpe-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Title
        $this->start_controls_section(
            'section_style_title',
            [
                'label' => esc_html__( 'Title', 'free-posts-in-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Color', 'free-posts-in-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fpe-title, {{WRAPPER}} .fpe-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .fpe-title',
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label'     => esc_html__( 'Spacing Bottom', 'free-posts-in-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units'=> [ 'px', 'em', 'rem' ],
                'range'     => [
                    'px'  => [ 'min' => 0, 'max' => 40 ],
                    'em'  => [ 'min' => 0, 'max' => 3 ],
                    'rem' => [ 'min' => 0, 'max' => 3 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .fpe-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Excerpt
        $this->start_controls_section(
            'section_style_excerpt',
            [
                'label' => esc_html__( 'Excerpt', 'free-posts-in-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'excerpt_color',
            [
                'label'     => esc_html__( 'Color', 'free-posts-in-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fpe-excerpt' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'excerpt_typography',
                'selector' => '{{WRAPPER}} .fpe-excerpt',
            ]
        );

        $this->end_controls_section();

        // Meta
        $this->start_controls_section(
            'section_style_meta',
            [
                'label' => esc_html__( 'Meta', 'free-posts-in-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'meta_color',
            [
                'label'     => esc_html__( 'Color', 'free-posts-in-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .fpe-meta' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'meta_typography',
                'selector' => '{{WRAPPER}} .fpe-meta',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $args = [
            'post_type'           => $settings['source'] === 'page' ? 'page' : 'post',
            'posts_per_page'      => isset( $settings['posts_per_page'] ) ? (int) $settings['posts_per_page'] : 6,
            'orderby'             => isset( $settings['order_by'] ) ? sanitize_key( $settings['order_by'] ) : 'date',
            'order'               => ( isset( $settings['order'] ) && in_array( $settings['order'], [ 'ASC', 'DESC' ], true ) ) ? $settings['order'] : 'DESC',
            'no_found_rows'       => true,
            'ignore_sticky_posts' => ( isset( $settings['ignore_sticky_posts'] ) && 'yes' === $settings['ignore_sticky_posts'] ) ? 1 : 0,
        ];

        if ( 'posts' === $settings['source'] && ! empty( $settings['categories'] ) && is_array( $settings['categories'] ) ) {
            $args['category__in'] = array_map( 'intval', $settings['categories'] );
        }

        $q = new WP_Query( $args );

        if ( ! $q->have_posts() ) {
            echo '<div class="fpe-empty">' . esc_html__( 'No posts found.', 'free-posts-in-elementor' ) . '</div>';
            return;
        }

        echo '<div class="fpe-posts" role="list">';

        while ( $q->have_posts() ) {
            $q->the_post();
            $post_id = get_the_ID();
            $permalink = get_permalink( $post_id );

            echo '<article class="fpe-card" role="listitem">';

            if ( 'yes' === ( $settings['show_image'] ?? 'yes' ) ) {
                echo '<a class="fpe-thumb" href="' . esc_url( $permalink ) . '" aria-label="' . esc_attr( get_the_title( $post_id ) ) . '">';
                if ( has_post_thumbnail( $post_id ) ) {
                    // Use WordPress core responsive image output.
                    echo get_the_post_thumbnail( $post_id, 'large', [ 'class' => 'fpe-thumb-img', 'loading' => 'lazy' ] );
                } else {
                    echo '<span class="fpe-thumb-placeholder" aria-hidden="true"></span>';
                }
                echo '</a>';
            }

            echo '<div class="fpe-content">';

            if ( 'yes' === ( $settings['show_title'] ?? 'yes' ) ) {
                echo '<h3 class="fpe-title"><a href="' . esc_url( $permalink ) . '">' . esc_html( get_the_title( $post_id ) ) . '</a></h3>';
            }

            if ( 'yes' === ( $settings['show_excerpt'] ?? 'yes' ) ) {
                $excerpt = get_the_excerpt( $post_id );
                $excerpt = wp_strip_all_tags( $excerpt, true );
                $words = preg_split( '/\s+/', trim( $excerpt ) );
                $limit = isset( $settings['excerpt_length'] ) ? max( 0, (int) $settings['excerpt_length'] ) : 20;
                if ( $limit > 0 && is_array( $words ) && count( $words ) > $limit ) {
                    $excerpt = implode( ' ', array_slice( $words, 0, $limit ) ) . '…';
                }
                echo '<div class="fpe-excerpt">' . esc_html( $excerpt ) . '</div>';
            }

            if ( 'yes' === ( $settings['show_date'] ?? 'yes' ) ) {
                echo '<div class="fpe-meta">' . esc_html( get_the_date( '', $post_id ) ) . '</div>';
            }

            echo '</div>';
            echo '</article>';
        }

        wp_reset_postdata();

        echo '</div>';
    }

    private function get_categories_options() {
        $cats = get_categories( [ 'hide_empty' => false ] );
        $options = [];
        if ( ! is_wp_error( $cats ) ) {
            foreach ( $cats as $cat ) {
                $options[ (string) $cat->term_id ] = $cat->name;
            }
        }
        return $options;
    }
}

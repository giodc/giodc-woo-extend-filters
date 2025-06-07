<?php
/**
 * GIODC WooCommerce Extended Filters Widget
 *
 * @package GIODC_Woo_Extend_Filters
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * GIODC WooCommerce Extended Filters Widget Class
 */
class GIODC_WEF_Widget extends WP_Widget {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'giodc_wef_widget',
            __( 'GIODC Woo Extended Filters', 'giodc-woo-extend-filters' ),
            array(
                'description' => __( 'Display additional WooCommerce filters (On Sale and In Stock)', 'giodc-woo-extend-filters' ),
                'classname'   => 'giodc-wef-widget',
            )
        );

        // Add filter for the WooCommerce query
        add_filter( 'woocommerce_product_query_meta_query', array( $this, 'filter_products_in_stock' ), 10, 2 );
        add_filter( 'woocommerce_product_query', array( $this, 'filter_products_on_sale' ), 10, 2 );
        add_filter( 'woocommerce_product_query', array( $this, 'filter_recent_products' ), 10, 2 );
    }

    /**
     * Widget front-end display
     *
     * @param array $args     Widget arguments
     * @param array $instance Saved values from database
     */
    public function widget( $args, $instance ) {
        // Only show on WooCommerce pages
        if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
            return;
        }

        $title = ! empty( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : __( 'Product Filters', 'giodc-woo-extend-filters' );

        // Get current filter states from URL parameters
        $on_sale = isset( $_GET['on_sale'] ) ? (bool) $_GET['on_sale'] : false;
        $in_stock = isset( $_GET['in_stock'] ) ? (bool) $_GET['in_stock'] : false;
        $recent = isset( $_GET['recent'] ) ? (bool) $_GET['recent'] : false;

        echo $args['before_widget'];
        
        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        // Get current URL for form action
        $current_url = remove_query_arg( array( 'on_sale', 'in_stock', 'recent' ) );

        // Start filter form
        ?>
        <form method="get" action="<?php echo esc_url( $current_url ); ?>" class="giodc-wef-filter-form">
            <?php
            // Preserve existing query parameters
            foreach ( $_GET as $key => $value ) {
                if ( ! in_array( $key, array( 'on_sale', 'in_stock', 'recent' ) ) ) {
                    echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '">';
                }
            }
            ?>
            <div class="giodc-wef-filter-option">
                <label for="giodc-wef-on-sale">
                    <input type="checkbox" id="giodc-wef-on-sale" name="on_sale" value="1" <?php checked( $on_sale ); ?>>
                    <?php esc_html_e( 'On Sale?', 'giodc-woo-extend-filters' ); ?>
                </label>
            </div>
            <div class="giodc-wef-filter-option">
                <label for="giodc-wef-in-stock">
                    <input type="checkbox" id="giodc-wef-in-stock" name="in_stock" value="1" <?php checked( $in_stock ); ?>>
                    <?php esc_html_e( 'In Stock?', 'giodc-woo-extend-filters' ); ?>
                </label>
            </div>
            <div class="giodc-wef-filter-option">
                <label for="giodc-wef-recent">
                    <input type="checkbox" id="giodc-wef-recent" name="recent" value="1" <?php checked( $recent ); ?>>
                    <?php esc_html_e( 'Recent Products?', 'giodc-woo-extend-filters' ); ?>
                </label>
            </div>
            <div class="giodc-wef-filter-actions">
                <button type="submit" class="button"><?php esc_html_e( 'Apply Filters', 'giodc-woo-extend-filters' ); ?></button>
                <?php if ( $on_sale || $in_stock || $recent ) : ?>
                    <a href="<?php echo esc_url( remove_query_arg( array( 'on_sale', 'in_stock', 'recent' ) ) ); ?>" class="giodc-wef-reset-filters">
                        <?php esc_html_e( 'Reset Filters', 'giodc-woo-extend-filters' ); ?>
                    </a>
                <?php endif; ?>
            </div>
        </form>
        <?php

        echo $args['after_widget'];
    }

    /**
     * Widget backend form
     *
     * @param array $instance Previously saved values from database
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Product Filters', 'giodc-woo-extend-filters' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
                <?php esc_html_e( 'Title:', 'giodc-woo-extend-filters' ); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" 
                   name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" 
                   type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p class="description">
            <?php esc_html_e( 'This widget adds "On Sale" and "In Stock" filter options to your WooCommerce shop.', 'giodc-woo-extend-filters' ); ?>
        </p>
        <p class="description">
            <?php esc_html_e( 'You can also use the shortcode [giodc_wef_filters] to display these filters anywhere.', 'giodc-woo-extend-filters' ); ?>
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved
     *
     * @param array $new_instance Values just sent to be saved
     * @param array $old_instance Previously saved values from database
     * @return array Updated safe values to be saved
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        return $instance;
    }

    /**
     * Filter products to show only in-stock items
     *
     * @param array $meta_query The meta query
     * @param object $query The WC_Query object
     * @return array Modified meta query
     */
    public function filter_products_in_stock( $meta_query, $query ) {
        if ( ! is_admin() && isset( $_GET['in_stock'] ) && $_GET['in_stock'] ) {
            $meta_query[] = array(
                'key'     => '_stock_status',
                'value'   => 'instock',
                'compare' => '=',
            );
        }
        return $meta_query;
    }

    /**
     * Filter products to show only on-sale items
     *
     * @param object $query The WC_Query object
     * @return object Modified query
     */
    public function filter_products_on_sale( $query ) {
        if ( ! is_admin() && isset( $_GET['on_sale'] ) && $_GET['on_sale'] ) {
            $product_ids_on_sale = wc_get_product_ids_on_sale();
            if ( empty( $product_ids_on_sale ) ) {
                $product_ids_on_sale = array( 0 );
            }
            $query->set( 'post__in', (array) $product_ids_on_sale );
        }
        return $query;
    }
    
    /**
     * Filter products to show only recent products
     *
     * @param object $query The WC_Query object
     * @return object Modified query
     */
    public function filter_recent_products( $query ) {
        if ( ! is_admin() && isset( $_GET['recent'] ) && $_GET['recent'] ) {
            // Set ordering parameters to show recent products
            $query->set( 'orderby', 'date' );
            $query->set( 'order', 'DESC' );
            
            // Optionally limit to products from the last 30 days
            $thirty_days_ago = date( 'Y-m-d', strtotime( '-30 days' ) );
            $query->set( 'date_query', array(
                'after' => $thirty_days_ago,
                'inclusive' => true
            ));
        }
        return $query;
    }
}

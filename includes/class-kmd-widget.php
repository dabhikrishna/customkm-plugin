<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Kmd_Widget extends WP_Widget {
	public function __construct() {
		$widget_options = array(
			'classname'   => 'recent_posts_widget',
			'description' => 'Displays a list of recent posts.',
		);
		parent::__construct( 'recent_posts_widget', 'Recent Posts Widget', $widget_options );
	}

	public function widget( $args, $instance ) {
		$title           = apply_filters( 'widget_title', $instance['title'] );
		$number_of_posts = ! empty( $instance['number_of_posts'] ) ? $instance['number_of_posts'] : 5;

		echo esc_attr( $args['before_widget'] );

		if ( ! empty( $title ) ) {
			echo esc_attr( $args['before_title'] ) . ' ' . esc_attr( $title ) . ' ' . esc_attr( $args['after_title'] );
		}

		$recent_posts = new WP_Query(
			array(
				'post_type'      => 'portfolio',
				'posts_per_page' => $number_of_posts,
				'orderby'        => 'date',
				'order'          => 'DESC',
			)
		);

		if ( $recent_posts->have_posts() ) {
			echo '<ul>';
			while ( $recent_posts->have_posts() ) {
				$recent_posts->the_post();
				echo '<li><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></li>';

			}
			echo '</ul>';
			wp_reset_postdata();
		}

		echo esc_attr( $args['after_widget'] );
	}

	public function form( $instance ) {
		$title           = ! empty( $instance['title'] ) ? $instance['title'] : 'Recent Posts';
		$number_of_posts = ! empty( $instance['number_of_posts'] ) ? $instance['number_of_posts'] : 4;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__( 'Title:', 'text_domain' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'number_of_posts' ) ); ?>">Number of posts to show:</label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number_of_posts' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number_of_posts' ) ); ?>" type="number" min="1" max="10" value="<?php echo esc_attr( $number_of_posts ); ?>">
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance                    = array();
		$instance['title']           = ! empty( $new_instance['title'] ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['number_of_posts'] = ! empty( $new_instance['number_of_posts'] ) ? strip_tags( $new_instance['number_of_posts'] ) : '';
		return $instance;
	}
}
	$my_widget = new Kmd_Widget();
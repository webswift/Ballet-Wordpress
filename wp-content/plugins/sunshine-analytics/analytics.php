<?php
/*
Plugin Name: Sunshine Photo Cart - Analytics with Profit Data
Plugin URI: http://www.sunshinephotocart.com/addon/analytics
Description: Add-on for Sunshine Photo Cart - Displays sales, gallery views and other analytical data
Version: 1.0.7
Author: Sunshine Photo Cart
Author URI: http://www.sunshinephotocart.com
Text Domain: sunshine
*/
define( 'SUNSHINE_ANALYTICS_VERSION', '1.0.7' );

add_action( 'init', 'sunshine_analytics_license', 1 );
function sunshine_analytics_license() {
	if( class_exists( 'Sunshine_License' ) && is_admin() ) {
		$sunshine_analytics_license = new Sunshine_License( __FILE__, 'Analytics with Profit Data', SUNSHINE_ANALYTICS_VERSION, 'Sunshine Photo Cart' );
	}
}

register_activation_hook( __FILE__, 'sunshine_analytics_activate' );
function sunshine_analytics_activate() {
	if ( function_exists( 'sunshine_addon_manager_activate_license' ) )
		sunshine_addon_manager_activate_license( 'Analytics with Profit Data', __FILE__ );
}

register_deactivation_hook( __FILE__, 'sunshine_analytics_deactivate' );
function sunshine_analytics_deactivate() {
	if ( function_exists( 'sunshine_addon_manager_deactivate_license' ) )
		sunshine_addon_manager_deactivate_license( 'Analytics with Profit Data', __FILE__ );
}

add_action( 'admin_init', 'sunshine_analytics_has_parent_plugin' );
function sunshine_analytics_has_parent_plugin() {
	if ( is_admin() && current_user_can( 'activate_plugins' ) &&  !is_plugin_active( 'sunshine-photo-cart/sunshine-photo-cart.php' ) ) {
		add_action( 'admin_notices', 'sunshine_analytics_child_plugin_notice' );

		deactivate_plugins( plugin_basename( __FILE__ ) );

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
	}
}

function sunshine_analytics_child_plugin_notice() {
?>
	<div class="error"><p><?php _e( 'Sorry, all Sunshine add-ons require that the main Sunshine Photo Cart plugin first be active','sunshine' ); ?></p></div>
<?php
}

function sunshine_analytics_add_gallery_view( $gallery_id ) {
	global $current_user;
	$galleries_viewed = SunshineSession::instance()->galleries_viewed;
	if ( is_array( $galleries_viewed ) && in_array( SunshineFrontend::$current_gallery->ID, SunshineSession::instance()->galleries_viewed ) ) {
		return;
	}

	$view['time'] = current_time( 'timestamp' );
	$view['user'] = $current_user->ID;
	$view['gallery'] = $gallery_id;
	add_post_meta( $gallery_id, 'sunshine_gallery_view', $view );
	$view_count = get_post_meta( $gallery_id, 'sunshine_gallery_view_count', true );
	$view_count++;
	update_post_meta( $gallery_id, 'sunshine_gallery_view_count', $view_count );
	$galleries_viewed[] = $gallery_id;
	SunshineSession::instance()->galleries_viewed = $galleries_viewed;
}

add_action( 'wp_head', 'sunshine_analytics_track_gallery_view' );
function sunshine_analytics_track_gallery_view() {
	global $wp_query;
	if ( SunshineFrontend::$current_gallery && !SunshineFrontend::$current_image && !current_user_can( 'manage_options' ) && !post_password_required( SunshineFrontend::$current_gallery->ID ) ) {
		sunshine_analytics_add_gallery_view( SunshineFrontend::$current_gallery->ID );
	}
}

function sunshine_analytics_add_image_view( $image_id ) {
	global $current_user;
	$view['time'] = current_time( 'timestamp' );
	$view['user'] = $current_user->ID;
	$view['image'] = $image_id;
	add_post_meta( $image_id, 'sunshine_image_view', $view );
	$view_count = get_post_meta( $image_id, 'sunshine_image_view_count', true );
	$view_count++;
	update_post_meta( $image_id, 'sunshine_image_view_count', $view_count );
	$images_viewed = SunshineSession::instance()->images_viewed;
	$images_viewed[] = $image_id;
	SunshineSession::instance()->images_viewed = $images_viewed;
}

add_action( 'wp_head', 'sunshine_analytics_track_image_view' );
function sunshine_analytics_track_image_view() {
	global $wp_query;
	if ( SunshineFrontend::$current_image && !current_user_can( 'manage_options' ) ) {
		sunshine_analytics_add_image_view( SunshineFrontend::$current_image->ID );
	}
}

add_action( 'wp_head', 'sunshine_analytics_lightbox_js' );
function sunshine_analytics_lightbox_js() {
?>
	<script>
	jQuery(document).ready(function($){
		jQuery(document).on('click', 'a.sunshine-lightbox', function(e) {
			var image_id = $(this).data('image-id');
			jQuery.ajax({
			  	type: 'POST',
			  	url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
			  	data: {
			  		action: 'sunshine_analytics_track_image_view',
					image_id: image_id
				}
			});

		});
	});
	</script>
<?php
}

add_action( 'wp_ajax_sunshine_analytics_track_image_view', 'sunshine_analytics_lightbox_track_image_view' );
add_action( 'wp_ajax_nopriv_sunshine_analytics_track_image_view', 'sunshine_analytics_lightbox_track_image_view' );
function sunshine_analytics_lightbox_track_image_view() {
	if ( isset( $_POST['image_id'] ) ) {
		sunshine_analytics_add_image_view( intval( $_POST['image_id'] ) );
	}
}


add_action( 'add_meta_boxes', 'sunshine_add_analytics_gallery_meta_boxes' );
add_action( 'sunshine_admin_products_meta', 'sunshine_analytics_product_cost' );
add_action( 'admin_head', 'sunshine_analytics_gallery_js' );

function sunshine_analytics_gallery_js() {
	if ( isset( $_GET['page'] ) && $_GET['page'] == 'sunshine_admin' ) {
?>
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<?php
	}
}

function sunshine_add_analytics_gallery_meta_boxes() {
	if ( isset( $_GET['action'] ) && $_GET['action'] == 'edit' ) {
		add_meta_box(
			'sunshine_gallery_analytics',
			__( 'Gallery Analytics', 'sunshine' ),
			'sunshine_gallery_analytics_box',
			'sunshine-gallery',
			'advanced',
			'low'
		);
	}
}

function sunshine_analytics_gallery_views_sort( $a, $b ) {
	return $b['time'] - $a['time'];
}

function sunshine_gallery_analytics_box( $post ) {
	echo '<table class="sunshine-meta">';
	echo '<tr><th>' . __( 'Total Unique Views', 'sunshine' ) . ':</th><td>'.get_post_meta( $post->ID,'sunshine_gallery_view_count', true ).'</td></tr>';
	echo '<tr><th>' . __( 'Latest Visitors', 'sunshine' ) . ':</th><td>';
	$latest_gallery_views = get_post_meta( $post->ID,'sunshine_gallery_view', false );
	usort( $latest_gallery_views, 'sunshine_analytics_gallery_views_sort' );
	if ( $latest_gallery_views ) {
		//$latest_gallery_views = array_reverse($latest_gallery_views);
		echo '<ul>';
		foreach ( $latest_gallery_views as $view ) {
			$visitor = get_user_by( 'id', $view['user'] );
			if ( $visitor && in_array( 'administrator', $visitor->roles ) ) continue;
			echo '<li>';
			if ( $view['user'] > 0 )
				echo '<a href="user-edit.php?user_id='.$view['user'].'">'.$visitor->display_name.'</a>';
			else
				_e( 'Anonymous', 'sunshine' );
			echo ' on '.date( get_option( 'date_format' ).' @ g:ia', $view['time'] );
			echo '</li>';
		}
		echo '</ul>';
	} else {
		echo '<p>'.__( 'No views yet','sunshine' ).'</p>';
	}
	echo '</td></tr>';
	echo '<tr><th>'.__( 'Images viewed','sunshine' ).':</th><td>';

	$args = array(
		'post_type' => 'attachment',
		'post_parent' => $post->ID,
		'orderby' => 'menu_order ID',
		'nopaging' => true,
		'order' => 'ASC',
		'meta_key' => 'sunshine_image_view_count',
		'orderby' => 'meta_value_num',
		'order' => 'DESC'
	);
	$images_viewed = get_posts( $args );
	if ( $images_viewed ) {
		$images_viewed_html = '';
		foreach ( $images_viewed as $image ) {
			//$image_views = get_post_meta($image->ID,'sunshine_image_view', false);
			$image_view_count = get_post_meta( $image->ID,'sunshine_image_view_count',true );
			if ( $image_view_count ) {
				//$visitor = get_user_by('id', $view['user']);
				$thumb = wp_get_attachment_image_url( $image->ID, 'sunshine-thumbnail' );
				$images_viewed_html .= '<li>';
				$images_viewed_html .= '<img src="'.$thumb.'" alt="" height="75" /><br />';
				$images_viewed_html .= $image->post_title.'<br />'.__( 'Total views','sunshine' ).': '.$image_view_count;
				$images_viewed_html .= '</li>';
			}
		}
		if ( $images_viewed_html ) {
			echo '<ul id="sunshine-gallery-images-viewed">';
			echo $images_viewed_html;
			echo '</ul>';
		} else {
			_e( 'No images viewed yet','sunshine' );
		}
	} else {
		_e( 'No images for this gallery yet','sunshine' );
	}
	echo '</td></tr>';

	echo '<tr><th>'.__( 'Images Purchased', 'sunshine' ).':</th><td>';

	$args = array(
		'post_type' => 'attachment',
		'post_parent' => $post->ID,
		'orderby' => 'menu_order ID',
		'nopaging' => true,
		'order' => 'ASC',
		'meta_key' => 'sunshine_purchase_count',
		'orderby' => 'meta_value_num',
		'order' => 'DESC'
	);
	$images_purchased = get_posts( $args );
	if ( $images_purchased ) {
		$images_purchased_html = '';
		foreach ( $images_purchased as $image ) {
			$image_purchase_count = get_post_meta( $image->ID,'sunshine_purchase_count',true );
			if ( $image_purchase_count ) {
				$thumb = wp_get_attachment_image_url( $image->ID, 'sunshine-thumbnail' );
				$images_purchased_html .= '<li>';
				$images_purchased_html .= '<img src="'.$thumb.'" alt="" height="75" /><br />';
				$images_purchased_html .= $image->post_title.'<br />'.__( 'Total purchases','sunshine' ).': '.$image_purchase_count;
				$images_purchased_html .= '</li>';
			}
		}
		if ( $images_purchased_html ) {
			echo '<ul id="sunshine-gallery-images-purchased">';
			echo $images_purchased_html;
			echo '</ul>';
		} else {
			_e( 'No images purchased yet','sunshine' );
		}
	} else {
		_e( 'No images for this gallery yet','sunshine' );
	}
	echo '</td></tr>';
	do_action( 'sunshine_gallery_analytics_box', $post );
	echo '</table>';

}

function sunshine_analytics_product_cost( $product ) {
	global $sunshine;
	switch ( stripslashes( $sunshine->options['currency_symbol_position'] ) ) {
	case 'left' :
		$format = '%1$s%2$s';
		break;
	case 'right' :
		$format = '%2$s%1$s';
		break;
	case 'left_space' :
		$format = '%1$s&nbsp;%2$s';
		break;
	case 'right_space' :
		$format = '%2$s&nbsp;%1$s';
		break;
	}
	$currency_symbol = sunshine_currency_symbol();

	echo '<tr><th><label for="sunshine_product_cost">'.__( 'Product Cost', 'sunshine' ).'</label></th>';
	echo '<td>';
	$text_field = '<input type="text" name="sunshine_product_cost" value="'.esc_attr( get_post_meta( $product->ID, 'sunshine_product_cost', true ) ).'" />';
	echo sprintf( $format, $currency_symbol, $text_field );
	echo '</td></tr>';

}

add_action( 'save_post', 'sunshine_analytics_save_product_meta' );
function sunshine_analytics_save_product_meta( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;
	if ( isset( $_POST['post_type'] ) && $_POST['post_type'] == 'sunshine-product' && isset( $_POST['sunshine_product_cost'] ) ) {
		update_post_meta( $post_id, 'sunshine_product_cost', $_POST['sunshine_product_cost'] );
	}
}


add_action( 'wp_login', 'sunshine_analytics_user_login', 10, 2 );
function sunshine_analytics_user_login( $user_login, $user ) {
	add_user_meta( $user->ID, 'sunshine_login', current_time( 'timestamp' ) );
	$login_count = get_user_meta( $user->ID, 'sunshine_login_count', true );
	$login_count++;
	update_user_meta( $user->ID, 'sunshine_login_count', $login_count );
}

add_filter( 'manage_users_columns', 'sunshine_analytics_user_login_count_column_header' );
function sunshine_analytics_user_login_count_column_header( $columns ) {
	$columns['sunshine_login_count'] = 'Logins';
	$columns['sunshine_login'] = 'Last Login';
	return $columns;
}

add_filter( 'manage_users_custom_column', 'sunshine_analytics_user_login_count_column', 10, 3 );
function sunshine_analytics_user_login_count_column( $val, $column_name, $user_id ) {
	switch ( $column_name ) {
	case 'sunshine_login_count' :
		return get_user_meta( $user_id, 'sunshine_login_count', true );
		break;
	case 'sunshine_login' :
		$logins = get_user_meta( $user_id, 'sunshine_login' );
		if ( $logins ) {
			$logins = array_reverse( $logins );
			return date( get_option( 'date_format' ).' @ '.get_option( 'time_format' ), $logins[0] );
		}
		return '';
		break;
	default:
	}
	return $return;
}

add_filter( 'sunshine_dashboard_widgets', 'sunshine_analytics_dashboard_widgets' );
function sunshine_analytics_dashboard_widgets( $widgets ) {
	global $wpdb;

	if ( ! $recent_users = get_transient( 'susnhine_analytics_recent_users' ) ) {

		// Recent User Logins
		$args = array(
			'role' => 'Subscriber'
		);
		$user_query = new WP_User_Query( $args );
		$user_logins = $user_query->get_results();
		if ( !empty( $user_logins ) ) {
			foreach ( $user_logins as $user_login ) {
				$login_dates = get_user_meta( $user_login->ID, 'sunshine_login' );
				if ( $login_dates ) {
					$login_dates = array_reverse( $login_dates );
					$date = date( get_option( 'date_format' ).' @ '.get_option( 'time_format' ), $login_dates[0] );
					$users[$login_dates[0]] = '<li><a href="user-edit.php?user_id='.$user_login->ID.'">'.$user_login->display_name.'</a> at '.$date.'</li>';
				}
			}
		}
		if ( isset( $users ) && is_array( $users ) ) {
			$recent_users = '<ul>';
			ksort( $users );
			$recent_users .= implode( array_reverse( $users ) );
			$recent_users .= '</ul>';
		} else
			$recent_users = '<p>'.__( 'No users have logged in yet','sunshine' ).'</p>';

		set_transient( 'sunshine_analytics_recent_users', $recent_users, 60*60*6 );
	}

	$widgets[] = array(
		'title' => __( 'Recent User Logins', 'sunshine' ),
		'content' => $recent_users
	);

	if ( ! $order_totals = get_transient( 'susnhine_analytics_order_totals' ) ) {

		// Order totals
		$args = array(
			'post_type' => 'sunshine-order',
			'nopaging' => true,
			'tax_query' => array(
				array(
					'taxonomy' => 'sunshine-order-status',
					'field'    => 'slug',
					'terms'    => array( 'new', 'processing', 'shipped', 'pickup' ),
				)
			)
		);
		$orders = get_posts( $args );
		$month_sales = array();
		$total = 0;
		$completed_sales = 0;
		$total_profit = 0;
		if ( is_array( $orders ) ) {
			foreach ( $orders as $order ) {

				$order_data = unserialize( get_post_meta( $order->ID, '_sunshine_order_data', true ) );
				$total += $order_data['total'];
				$completed_sales++;
				$month = date( 'Ym', strtotime( $order->post_date ) );
				if ( !isset( $month_sales[$month] ) )
					$month_sales[$month] = 0;
				$month_sales[$month] += $order_data['total'];

				// Actual revenue using product cost
				$items = maybe_unserialize( get_post_meta( $order->ID, '_sunshine_order_items', true ) );
				if ( is_array( $items ) ) {
					foreach ( $items as $item ) {
						if ( !isset( $item['cost'] ) )
							$item['cost'] = get_post_meta( $item['product_id'], 'sunshine_product_cost', true );
						if ( !isset( $item['price'] ) ) {
							if ( !isset( $item['price_level'] ) )
								$item['price_level'] = $sunshine->cart->get_default_price_level();
							$item['price'] = get_post_meta( $item['product_id'], 'sunshine_product_price_'.$item['price_level'], true );
						}
						$item_profit = ( $item['price'] - $item['cost'] ) * $item['qty'];
						$total_profit += $item_profit;
						if ( !isset( $month_profit[$month] ) )
							$month_profit[$month] = 0;
						$month_profit[$month] += $item_profit;
					}

					if ( $order_data['discount_total'] > 0 ) {
						$total_profit -= $order_data['discount_total'];
						$month_profit[$month] -= $order_data['discount_total'];
					}
					if ( $order_data['credits'] > 0 ) {
						$total_profit -= $order_data['credits'];
						$month_profit[$month] -= $order_data['credits'];
					}
				}

			}
		}
		if ( $completed_sales > 0 ) {
			$order_totals = '
				<table id="sunshine-sales-totals">
				<tr><th>'.__( 'Total Sales Revenue','sunshine' ).':</th>
				<td>'.sunshine_money_format( $total,false ).'</td></tr>
				<tr><th>'.__( 'Total Orders Completed','sunshine' ).':</th>
				<td>'.$completed_sales.'</td></tr>
				<tr><th>'.__( 'Average Sale Total','sunshine' ).':</th>
				<td>'.sunshine_money_format( $total/$completed_sales,false ).'</td></tr>
				<tr><th>'.__( 'Actual Profit','sunshine' ).':</th>
				<td>'.sunshine_money_format( $total_profit,false ).'</td></tr>
				</table>
			';
			$current_month = date( 'Ym', current_time( 'timestamp' ) );
			$current_month_formatted = date( 'M Y', current_time( 'timestamp' ) );
			if ( isset( $month_sales[$current_month] ) )
				$monthly_sales_chart_data[] = "['".$current_month_formatted."', ".number_format( $month_sales[$current_month],2,'.','' ).", ".number_format( $month_profit[$current_month],2,'.','' )."]";
			else
				$monthly_sales_chart_data[] = "['".$current_month_formatted."', 0, 0]";
			for ( $i = 1; $i <= 5; $i++ ) {
				$month_as_time = strtotime( "-$i month" );
				$month = date( 'Ym', $month_as_time );
				$month_formatted = date( 'M Y', $month_as_time );
				if ( isset( $month_sales[$month] ) )
					$monthly_sales_chart_data[] = "['".$month_formatted."', ".number_format( $month_sales[$month],2,'.','' ).", ".number_format( $month_profit[$month],2,'.','' )."]";
			}
			ob_start();
?>
			<script type="text/javascript">
			jQuery(document).ready(function () {
			    function sunshineAnalyticsMonthlySales() {
		            var data = google.visualization.arrayToDataTable([
			          ['Month', 'Revenue', 'Profit'],
			          <?php echo join( ',',array_reverse( $monthly_sales_chart_data ) ); ?>
			        ]);
			        var options = {
			          	'title': '<?php _e( 'Last 6 Months Revenue/Profit','sunshine' ); ?>',
						'backgroundColor': '#f8f8f8',
						'chartArea':{width:"75%"},
						'colors': ['blue','green'],
						'legend': {position:'bottom', alignment: 'center'},
			        };
			        var chart = new google.visualization.AreaChart(document.getElementById('sunshine-monthly-sales-chart'));
			        chart.draw(data, options);
			    }
				google.load("visualization", "1", { packages:["corechart"], "callback" : sunshineAnalyticsMonthlySales });
			});
			</script>
			<div id="sunshine-monthly-sales-chart" style="height: 400px;"></div>
			<?php
			$order_totals .= ob_get_contents();
			ob_end_clean();
		} else
			$order_totals = '<p>'.__( 'You do not have any sales yet','sunshine' ).'</p>';

		set_transient( 'sunshine_analytics_order_totals', $order_totals, 60*60*24 );
	}

	$widgets[] = array(
		'title' => __( 'Order Totals', 'sunshine' ),
		'content' => $order_totals,
	);

	if ( ! $popular_products = get_transient( 'susnhine_analytics_popular_products' ) ) {

		// Most Popular Products
		$args = array(
			'post_type' => 'sunshine-product',
			'posts_per_page' => 10,
			'meta_key' => 'sunshine_purchase_count',
			'orderby' => 'meta_value_num',
			'order' => 'DESC'
		);
		$products = get_posts( $args );
		if ( $products ) {
			$popular_products = '<table>';
			foreach ( $products as $product ) {
				$cat = wp_get_post_terms( $product->ID, 'sunshine-product-category' );
				$cat_name = '';
				if ( isset( $cat[0]->name ) )
					$cat_name = $cat[0]->name;
				$purchase_count = get_post_meta( $product->ID, 'sunshine_purchase_count', true );
				$popular_products .= '<tr><th>'.$purchase_count.'</th><td><a href="post.php?post='.$product->ID.'&amp;action=edit">'.$cat_name.' - '.$product->post_title.'</td></tr>';
				$products_chart_data[] = "['".$cat_name.' - '.$product->post_title."', ".$purchase_count."]";
			}
			$popular_products .= '</table>';
			ob_start();
?>
			<script type="text/javascript">
			jQuery(document).ready(function () {
			    function sunshineAnalyticsPopularProducts() {
			        var data = google.visualization.arrayToDataTable([
			          ['Product', 'Sold'],
			          <?php echo join( ',',$products_chart_data ); ?>
			        ]);
			        var options = {
			          	'title': '<?php _e( 'Popular Products Sold', 'sunshine' ); ?>',
						'backgroundColor': '#f8f8f8',
						'chartArea':{left:0,top:10,width:"100%"},
			        };
			        var chart = new google.visualization.PieChart(document.getElementById('sunshine-popular-products-chart'));
			        chart.draw(data, options);
			    }
				google.load("visualization", "1", { packages:["corechart"], "callback" : sunshineAnalyticsPopularProducts });
			});
			</script>
			<div id="sunshine-popular-products-chart" style="height: 400px;"></div>
			<?php
			$popular_products = ob_get_contents();
			ob_end_clean();
		} else
			$popular_products = '<p>'.__( 'No sales data currently recorded','sunshine' ).'</p>';

		set_transient( 'sunshine_analytics_popular_products', $popular_products, 60*60*24 );
	}

	$widgets[] = array(
		'title' => __( 'Most Popular Products Sold', 'sunshine' ),
		'content' => $popular_products,
		'links' => '<a href="' . wp_nonce_url( admin_url( 'admin.php?page=sunshine_admin&reset_popular_products=1' ), 'reset_popular_products', 'sunshine_analytics_reset' ) . '" onclick="return confirm(\'' . __('Are you sure? This cannot be undone!', 'sunshine' ) . '\')">' . __( 'Reset data', 'sunshine' ) . '</a>'
	);

	if ( ! $gallery_sales_html = get_transient( 'susnhine_analytics_gallery_sales' ) ) {

		// Best galleries by sales
		$args = array(
			'post_type' => 'sunshine-gallery',
			'meta_key' => 'sunshine_gallery_sales_total',
			'orderby' => 'meta_key_num',
			'order' => 'DESC'
		);
		$gallery_sales = new WP_Query( $args );
		if ( $gallery_sales->have_posts() ) {
			$gallery_sales_html = '<table><tr><th>'.__( 'Gallery','sunshine' ).'</th><th>'.__( 'Total Sales','sunshine' ).'</th><th>'.__( 'Profit','sunshine' ).'</th><th>'.__( 'Price Level','sunshine' ).'</th></tr>';
			while ( $gallery_sales->have_posts() ) : $gallery_sales->the_post();
			$sales = get_post_meta( get_the_ID(), 'sunshine_gallery_sales_total', true );
			$cost = get_post_meta( get_the_ID(), 'sunshine_gallery_cost_total', true );
			$price_level_id = get_post_meta( get_the_ID(), 'sunshine_gallery_price_level', true );
			$price_level = get_term_by( 'id', $price_level_id, 'sunshine-product-price-level' );
			$gallery_sales_html .= '<tr>';
			$gallery_sales_html .= '<td><a href="post.php?post='.get_the_ID().'&action=edit">'.get_the_title( get_the_ID() ).'</a></td>';
			$gallery_sales_html .= '<td>'.sunshine_money_format( $sales,false ).'</td>';
			$gallery_sales_html .= '<td>'.sunshine_money_format( ( $sales-$cost ),false ).'</td>';
			$gallery_sales_html .= '<td>'.$price_level->name.'</td>';
			$gallery_sales_html .= '</tr>';
			endwhile; wp_reset_postdata();
			$gallery_sales_html .= '</table>';
		} else
			$gallery_sales_html = '<p>'.__( 'No sales data currently recorded', 'sunshine' ).'</p>';

		set_transient( 'sunshine_analytics_gallery_sales', $gallery_sales_html, 60*60*24 );
	}

	$widgets[] = array(
		'title' => __( 'Galleries With Best Sales', 'sunshine' ),
		'content' => $gallery_sales_html,
		'links' => '<a href="' . wp_nonce_url( admin_url( 'admin.php?page=sunshine_admin&reset_gallery_sales=1' ), 'reset_gallery_sales', 'sunshine_analytics_reset' ) . '" onclick="return confirm(\'' . __('Are you sure? This cannot be undone!', 'sunshine' ) . '\')">' . __( 'Reset data', 'sunshine' ) . '</a>'
	);

	if ( ! $popular_images = get_transient( 'susnhine_analytics_popular_images' ) ) {
		// Most popular images of all time
		$args = array(
			'post_type' => 'attachment',
			'orderby' => 'menu_order ID',
			'posts_per_page' => 10,
			'meta_key' => 'sunshine_purchase_count',
			'orderby' => 'meta_value_num',
			'order' => 'DESC'
		);
		$images = get_posts( $args );
		if ( $images ) {
			$popular_images = '<ul id="sunshine-gallery-images-purchased">';
			foreach ( $images as $image ) {
				$image_purchase_count = get_post_meta( $image->ID,'sunshine_purchase_count',true );
				if ( $image_purchase_count ) {
					$thumb = wp_get_attachment_thumb_url( $image->ID );
					$popular_images .= '<li>';
					$popular_images .= '<img src="'.$thumb.'" alt="" height="75" /><br />';
					$popular_images .= $image->post_title.'<br />'.__( 'Total purchases','sunshine' ).': '.$image_purchase_count;
					$popular_images .= '</li>';
				}
			}
			$popular_images .= '</ul><br clear="all" />';
		} else
			$popular_images = '<p>'.__( 'No sales data currently recorded', 'sunshine' ).'</p>';

		set_transient( 'sunshine_analytics_popular_images', $popular_images, 60*60*24 );
	}

	$widgets[] = array(
		'title' => __( 'Most Purchased Images of All Time', 'sunshine' ),
		'content' => $popular_images,
		'links' => '<a href="' . wp_nonce_url( admin_url( 'admin.php?page=sunshine_admin&reset_popular_images=1' ), 'reset_popular_images', 'sunshine_analytics_reset' ) . '" onclick="return confirm(\'' . __('Are you sure? This cannot be undone!', 'sunshine' ) . '\')">' . __( 'Reset data', 'sunshine' ) . '</a>'
	);

	return $widgets;
}

/**
 * Clear cached sales totals after a new order
 *
 * @since 1.0
 * @return void
 */
add_action( 'sunshine_add_order_end', 'sunshine_analytics_add_order_end' );
function sunshine_analytics_add_order_end( $var ) {
	delete_transient( 'sunshine_analytics_order_totals' );
	delete_transient( 'sunshine_analytics_popular_products' );
	delete_transient( 'sunshine_analytics_gallery_sales' );
	delete_transient( 'sunshine_analytics_popular_images' );
}

/**
 * Clear cached sales totals after a new order
 *
 * @since 1.0
 * @return void
 */
add_action( 'admin_notices', 'sunshine_analytics_reset_data' );
function sunshine_analytics_reset_data() {

	if ( isset( $_GET['reset_popular_products'] ) && wp_verify_nonce( $_GET['sunshine_analytics_reset'], 'reset_popular_products' ) ) {

		$args = array(
			'post_type' => 'sunshine-product',
			'nopaging' => true,
			'meta_query' => array(
				array(
					'key' => 'sunshine_purchase_count',
					'compare' => 'EXISTS'
				)
			)
		);
		$products = get_posts( $args );
		foreach ( $products as $product ) {
			delete_post_meta( $product->ID, 'sunshine_purchase_count' );
		}
		echo '<div id="message" class="updated"><p>' . __('Popular product data has been reset', 'sunshine' ) . '</p></div>';

	} elseif ( isset( $_GET['reset_gallery_sales'] ) && wp_verify_nonce( $_GET['sunshine_analytics_reset'], 'reset_gallery_sales' ) ) {

		delete_post_meta_by_key( 'sunshine_gallery_sales_total' );
		delete_post_meta_by_key( 'sunshine_gallery_cost_total' );
		echo '<div id="message" class="updated"><p>' . __('Gallery sales data has been reset', 'sunshine' ) . '</p></div>';

	} elseif ( isset( $_GET['reset_popular_images'] ) && wp_verify_nonce( $_GET['sunshine_analytics_reset'], 'reset_popular_images' ) ) {

		$args = array(
			'post_type' => 'attachment',
			'nopaging' => true,
			'meta_query' => array(
				array(
					'key' => 'sunshine_purchase_count',
					'compare' => 'EXISTS'
				)
			)
		);
		$attachments = get_posts( $args );
		foreach ( $attachments as $attachment ) {
			delete_post_meta( $attachment->ID, 'sunshine_purchase_count' );
		}
		echo '<div id="message" class="updated"><p>' . __('Popular images data has been reset', 'sunshine' ) . '</p></div>';

	}

}

add_action( 'sunshine_add_order_end', 'sunshine_analytics_purchase_count', 10, 3 );
function sunshine_analytics_purchase_count( $order_id, $data, $items ) {
	foreach ( $items as $item ) {
		// Increase purchase count after order for product
		$product_purchase_count = get_post_meta( $item['product_id'], 'sunshine_purchase_count', true );
		update_post_meta( $item['product_id'], 'sunshine_purchase_count', $product_purchase_count + $item['qty'] );

		// Increase purchase count after order for image
		$image_purchase_count = get_post_meta( $item['image_id'], 'sunshine_purchase_count', true );
		update_post_meta( $item['image_id'], 'sunshine_purchase_count', $image_purchase_count + $item['qty'] );

		// Set sales total for the gallery
		if ( $item['gallery_id'] ) {
			$gallery_id = $item['gallery_id'];
		} elseif ( $item['image_id'] ) {
			$image = get_post( $item['image_id'] );
			$gallery_id = $image->post_parent;
		}
		$gallery_sales_total = get_post_meta( $gallery_id, 'sunshine_gallery_sales_total', true );
		update_post_meta( $gallery_id, 'sunshine_gallery_sales_total', $gallery_sales_total + $item['total'] );

		// Set cost total
		$product_cost = get_post_meta( $item['product_id'], 'sunshine_product_cost', true );
		if ( $product_cost > 0 ) {
			$gallery_cost_total = get_post_meta( $gallery_id, 'sunshine_gallery_cost_total', true );
			update_post_meta( $gallery_id, 'sunshine_gallery_cost_total', $gallery_cost_total + ( $product_cost * $item['qty'] ) );
		}
	}
}

add_filter( 'sunshine_order_items', 'sunshine_analytics_order_items' );
function sunshine_analytics_order_items( $order_items ) {
	if ( $order_items ) {
		foreach ( $order_items as &$order_item ) {
			$order_item['cost'] = get_post_meta( $order_item['product_id'], 'sunshine_product_cost', true );
		}
	}
	return $order_items;
}

add_filter( 'manage_edit-sunshine-product_columns', 'sunshine_analytics_product_columns', 99 );
function sunshine_analytics_product_columns( $columns ) {
	$columns['cost'] = 'Cost';
	$columns['purchases'] = 'Purchases';
	return $columns;
}

add_action( 'manage_sunshine-product_posts_custom_column', 'sunshine_analytics_product_columns_content', 10, 2 );
function sunshine_analytics_product_columns_content( $column, $post_id ) {
	global $post;
	switch( $column ) {
	case 'cost':
		$cost = get_post_meta( $post_id, 'sunshine_product_cost', true );
		echo ( $cost != '' ) ? sunshine_money_format( $cost,false ) : '<a href="post.php?post='.$post_id.'&action=edit">Add cost to track profits!</a>';
		break;
	case 'purchases':
		$purchases = get_post_meta( $post_id, 'sunshine_purchase_count', true );
		echo ( $purchases ) ? $purchases : 0;
		break;
	default:
		break;
	}
}

function sunshine_analytics_gallery_columns( $columns ) {
	$columns['sales'] = __( 'Sales Data', 'sunshine' );
	return $columns;
}
add_filter( 'manage_edit-sunshine-gallery_columns', 'sunshine_analytics_gallery_columns', 100 );

add_action( 'manage_sunshine-gallery_posts_custom_column', 'sunshine_analytics_gallery_columns_content', 10, 2 );
function sunshine_analytics_gallery_columns_content( $column, $post_id ) {
	global $post;
	switch( $column ) {
	case 'sales':
		$cost = get_post_meta( $post_id, 'sunshine_gallery_cost_total', true );
		$sales = get_post_meta( $post_id, 'sunshine_gallery_sales_total', true );
		$profit = ( $sales - $cost );
		echo 'Sales Total: ';
		sunshine_money_format( $sales );
		echo '<br />Profit: ';
		sunshine_money_format( $profit );
		break;
	default:
		break;
	}
}

add_action( 'sunshine_bulk_add_product_item', 'sunshine_analytics_bulk_add_product_item', 50 );
function sunshine_analytics_bulk_add_product_item() {
?>
	<th>Cost:</th>
	<td>
		<?php
	$currency_symbol_format = sunshine_currency_symbol_format();
	$currency_symbol = sunshine_currency_symbol();
	$text_field = '<input type="text" size="6" name="cost[]" />';
	echo sprintf( $currency_symbol_format, $currency_symbol, $text_field );
?>
	</td>
<?php
}

add_action( 'sunshine_bulk_add_product', 'sunshine_analytics_bulk_add_product', 10, 3 );
function sunshine_analytics_bulk_add_product( $product_id, $array, $i ) {
	add_post_meta( $product_id, 'sunshine_product_cost', $array['cost'][$i] );
}

add_action( 'show_user_profile', 'sunshine_analytics_user_gallery_views', 999 );
add_action( 'edit_user_profile', 'sunshine_analytics_user_gallery_views', 999 );
function sunshine_analytics_user_gallery_views( $user ) {
	if ( current_user_can( 'manage_options' ) ) {
		$galleries = get_posts( 'post_type=sunshine-gallery&nopaging=true' );
		$gallery_views = $gallery_view_counts = array();
		foreach ( $galleries as $gallery ) {
			$gallery_views[ $gallery->ID ] = array();
			$gallery_view_counts[ $gallery->ID ] = 0;
			$this_gallery_views = get_post_meta( $gallery->ID, 'sunshine_gallery_view' );
			if ( !empty( $this_gallery_views ) ) {
				foreach ( $this_gallery_views as $this_gallery_view ) {
					if ( $this_gallery_view['user'] == $user->ID ) {
						$gallery_views[ $gallery->ID ][] = $this_gallery_view['time'];
						$gallery_view_counts[ $gallery->ID ]++;
					}
				}
			}
		}
		if ( !empty( $gallery_view_counts ) ) {
			echo '<h3>' . __( 'Galleries Viewed', 'sunshine' ) . '</h3>';
			echo '<ul>';
			foreach ( $gallery_view_counts as $gallery_id => $gallery_view_count ) {
				if ( $gallery_view_count == 0 ) {
					continue;
				}
				$viewed_galleries = true;
				echo '<li><strong>' . get_the_title( $gallery_id ) . '</strong>: ' . $gallery_view_count . ' ' . __( 'total views', 'sunshine' ) . '<ul>';
				foreach ( $gallery_views[ $gallery_id ] as $gallery_view_time ) {
					echo '<li>' . date( get_option( 'date_format' ) . ' @ ' . get_option( 'time_format' ), $gallery_view_time ) . '</li>';
				}
				echo '</ul>';
			}
			if ( !isset( $viewed_galleries ) ) {
				echo '<li>' . __( 'User has not viewed any galleries', 'sunshine' ) . '</li>';
			}
			echo '</ul>';
		}
	}
}


?>

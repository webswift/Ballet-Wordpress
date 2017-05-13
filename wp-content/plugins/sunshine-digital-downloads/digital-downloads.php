<?php
/*
Plugin Name: Sunshine Photo Cart - Digital Downloads
Plugin URI: http://www.sunshinephotocart.com/addon/digital-downloads
Description: Add-on for Sunshine Photo Cart - Allows clients to order and download digital negatives
Version: 2.2.3
Author: Sunshine Photo Cart
Author URI: http://www.sunshinephotocart.com
Text Domain: sunshine
Domain Path: /languages
*/

define( 'SUNSHINE_DIGITAL_DOWNLOADS_VERSION', '2.2.3' );

add_action( 'init', 'sunshine_digital_downloads_license', 1 );
function sunshine_digital_downloads_license() {
	if( class_exists( 'Sunshine_License' ) && is_admin() ) {
		$sunshine_license = new Sunshine_License( __FILE__, 'Digital Downloads', SUNSHINE_DIGITAL_DOWNLOADS_VERSION, 'Sunshine Photo Cart' );
	}
}

register_activation_hook( __FILE__, 'sunshine_digital_downloads_activate' );
function sunshine_digital_downloads_activate() {
	sunshine_download_update_options();
	if ( function_exists( 'sunshine_addon_manager_activate_license' ) )
		sunshine_addon_manager_activate_license( 'Digital Downloads', __FILE__ );
}

register_deactivation_hook( __FILE__, 'sunshine_digital_downloads_deactivate' );
function sunshine_digital_downloads_deactivate() {
	if ( function_exists( 'sunshine_addon_manager_deactivate_license' ) )
		sunshine_addon_manager_deactivate_license( 'Digital Downloads', __FILE__ );
}

add_action( 'admin_init', 'sunshine_digital_downloads_has_parent_plugin' );
function sunshine_digital_downloads_has_parent_plugin() {
	if ( is_admin() && current_user_can( 'activate_plugins' ) &&  !is_plugin_active( 'sunshine-photo-cart/sunshine-photo-cart.php' ) ) {
		add_action( 'admin_notices', 'sunshine_child_plugin_notice' );

		deactivate_plugins( plugin_basename( __FILE__ ) );

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
	}
}

add_action( 'sunshine_admin_products_meta', 'sunshine_downloads_admin_product_meta', 20 );
function sunshine_downloads_admin_product_meta( $post ) {
	$downloadable = get_post_meta( $post->ID, 'sunshine_product_download', true );
	$width = get_post_meta( $post->ID, 'sunshine_product_download_width', true );
	$height = get_post_meta( $post->ID, 'sunshine_product_download_height', true );
	$free = get_post_meta( $post->ID, 'sunshine_product_download_free', true );
	echo '<tr><th><label for="sunshine_product_download">'.__( 'Downloadable', 'sunshine' ).'</label></th>';
	echo '<td><input type="checkbox" name="sunshine_product_download" value="1" '.checked( $downloadable, 1, 0 ).' /> ' . __( 'This product is downloadable', 'sunshine' ) . '</td></tr>';
	$style = '';
	if ( !$downloadable ) {
		$style = 'display: none;';
	}
	echo '<tr style="' . $style . '" id="sunshine-product-downloadable-size"><th><label for="sunshine_product_download_width">'.__( 'Dimensions', 'sunshine' ).'</label></th>';
	echo '<td><input type="text" size="5" name="sunshine_product_download_width" value="' . esc_attr( $width ) . '" />px by <input type="text" size="5" name="sunshine_product_download_height" value="'. esc_attr( $height ) .'" />px <span class="desc">' . __( 'Leave blank for full resolution', 'sunshine' ) . '</span></td></tr>';
	echo '<tr style="' . $style . '" id="sunshine-product-downloadable-free"><th><label for="sunshine_product_download_free">'.__( 'Free Download', 'sunshine' ).'</label></th>';
	echo '<td><input type="checkbox" name="sunshine_product_download_free" value="1" '.checked( $free, 1, 0 ).' /> ' . __( 'This should be included in any free downloads', 'sunshine' ) . '</td></tr>';
	?>
	<script>
	jQuery(document).ready(function($){
		$('input[name="sunshine_product_download"]').change(function(){
			$('#sunshine-product-downloadable-size').toggle();
			$('#sunshine-product-downloadable-free').toggle();
		});
	});
	</script>
	<?php
}

add_action( 'sunshine_admin_galleries_meta', 'sunshine_downloads_admin_gallery_meta' );
function sunshine_downloads_admin_gallery_meta( $post ) {
	if ( empty( $post ) ) {
		$post_id = '';
	} else {
		$post_id = $post->ID;
	}

	$free_image_downloads = get_post_meta( $post_id, 'sunshine_free_image_downloads', true );
	$free_gallery_downloads = get_post_meta( $post_id, 'sunshine_free_gallery_downloads', true );

	echo '<tr class="sunshine-download-extra"><th><label for="sunshine_free_downloads">'.__( 'Allow free downloads?','sunshine' ).'</label></th>';
	echo '<td>
			<label><input type="checkbox" name="sunshine_free_image_downloads" value="1" '.checked( $free_image_downloads, 1, 0 ).' /> '.__( 'Allow users to download images in this gallery for free (no checkout required)','sunshine' ).'</label><br />
			<label><input type="checkbox" name="sunshine_free_gallery_downloads" value="1" '.checked( $free_gallery_downloads, 1, 0 ).' /> '.__( 'Allow users to download entire gallery as zip file (no checkout required)','sunshine' ).'</span></label>
		</td></tr>';

	$download_users = get_post_meta( $post_id, 'sunshine_download_users', true );
	echo '<tr id="sunshine-download-users"><th><label for="sunshine_download_users">'.__( 'Restrict downloads to users?','sunshine' ).'</label></th>';
	echo '<td><select name="sunshine_download_users[]" multiple="multiple" class="sunshine-multiselect">';
	$users = get_users();
	if ( $users ) {
		foreach ( $users as $user ) {
			$selected = ( is_array( $download_users ) && in_array( $user->ID, $download_users ) ) ? ' selected="selected"' : '';
			echo '<option value="'.$user->ID.'"'.$selected.' /> '.esc_attr( $user->display_name ) . '</option>';
		}
	}
	echo '</select></td></tr>';

}

add_action( 'save_post', 'sunshine_downloads_save_post_meta', 50 );
function sunshine_downloads_save_post_meta( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;
	if ( isset( $_POST['post_type'] ) && $_POST['post_type'] == 'sunshine-product' ) {
		update_post_meta( $post_id, 'sunshine_product_download', ( !empty( $_POST['sunshine_product_download'] ) ) ? 1 : 0 );
		update_post_meta( $post_id, 'sunshine_product_download_width', ( $_POST['sunshine_product_download_width'] > 0 ) ? intval( $_POST['sunshine_product_download_width'] ) : '' );
		update_post_meta( $post_id, 'sunshine_product_download_height', ( $_POST['sunshine_product_download_height'] > 0 ) ? intval( $_POST['sunshine_product_download_height'] ) : '' );
		update_post_meta( $post_id, 'sunshine_product_download_free', ( !empty( $_POST['sunshine_product_download_free'] ) ) ? 1 : 0 );
	}
	if ( isset( $_POST['post_type'] ) && $_POST['post_type'] == 'sunshine-gallery' ) {
		sunshine_downloads_save_post_meta_process( $post_id, $_POST );
	}
}

function sunshine_downloads_save_post_meta_process( $post_id, $data ) {
	update_post_meta( $post_id, 'sunshine_free_image_downloads', ( !empty( $data['sunshine_free_image_downloads'] ) ? 1 : 0 ) );
	update_post_meta( $post_id, 'sunshine_free_gallery_downloads', ( !empty( $data['sunshine_free_gallery_downloads'] ) ? 1 : 0 ) );
	update_post_meta( $post_id, 'sunshine_download_users', ( !empty( $data['sunshine_download_users'] ) ? $data['sunshine_download_users'] : '' ) );
}

// Hook into the bulk gallery add-on
add_action( 'sunshine_bulk_galleries_save_row', 'sunshine_downloads_save_post_meta_process', 10, 2 );

add_filter( 'sunshine_order_line_item_comments', 'sunshine_downloads_order_line_item_comments', 20, 3 );
function sunshine_downloads_order_line_item_comments( $comments='', $order_id, $item ) {
	if ( $item['type'] == 'package' ) return $comments;
	$downloadable = get_post_meta( $item['product_id'], 'sunshine_product_download', true );
	$status = sunshine_get_order_status( $order_id );
	if ( $downloadable && ( $status->slug != 'pending' && $status->slug != 'cancelled' ) ) {
		if ( $comments )
			$comments .= '<br />';
		$comments .= '<a href="'.get_permalink( $order_id ).'?action=order_download_image&hash='.$item['hash'].'" target="_blank">'.__( 'Download', 'sunshine' ).'</a>';
	}
	if ( isset( $item['type'] ) && $item['type'] == 'gallery_download' && ( $status->slug != 'pending' && $status->slug != 'cancelled' ) )
		$comments .= '<br /><a href="'.get_permalink( $order_id ).'?action=order_download_gallery&gallery_id='.$item['gallery_id'].'" target="_blank">'.__( 'Download all images from gallery', 'sunshine' ).'</a>';
	return $comments;
}


add_action( 'template_redirect', 'sunshine_download_order_all', 1 );
function sunshine_download_order_all() {
	global $current_user, $post, $sunshine;
	if ( isset( $_GET['action'] ) && $_GET['action'] == 'download_all' && isset( SunshineFrontend::$current_order ) ) {

		$customer_id = get_post_meta( SunshineFrontend::$current_order->ID, '_sunshine_customer_id', true );
		if ( $customer_id && $current_user->ID != $customer_id ) {
			$login_url = wp_login_url( sunshine_current_url( false ) );
			wp_die( sprintf( __( 'Sorry, you do not have access to this download. <a href="%s">Please login</a> to the account used for this order.', 'sunshine' ), $login_url ) );
			exit;
		}

		// Order status OK?
		$status = sunshine_get_order_status( SunshineFrontend::$current_order->ID );
		if ( $status->slug == 'pending' || $status->slug == 'cancelled' ) {
			wp_die( __( 'Sorry, your item is not available for download yet', 'sunshine' ) );
			exit;
		}

		// Does it have a file available to download?
		$upload_dir = wp_upload_dir();
		$extensions = sunshine_allowed_file_extensions();
		$order_items = sunshine_get_order_items( SunshineFrontend::$current_order->ID );
		foreach ( $order_items as $item ) {
			if ( $item['type'] == 'package' ) {
				if ( is_array( $item['package_products'] ) ) {
					foreach ( $item['package_products'] as $package_product_item ) {
						$downloadable = get_post_meta( $package_product_item['product_id'], 'sunshine_product_download', true );
						if ( $downloadable == 1 ) {
							$file_downloads[] = array( 'image_id' => $package_product_item['image_id'], 'product_id' => $package_product_item['product_id'] );
						}
					}
				} else {
					$selected_products = get_post_meta( $item['product_id'], 'sunshine_product_package_option' );
					foreach ( $selected_products as $selected_product ) {
						if ( $selected_product['product_id'] == 'GALLERY' ) {
							$downloadable = 1;
							// Get all images from gallery added to download
							$images = get_children( array( 'post_parent' => $item['gallery_id'] ) );
							foreach ( $images as $image ) {
								$file_downloads[] = array( 'image_id' => $image->ID, 'product_id' => 'FULL' );
							}
						}
					}
				}
			} elseif ( $item['product_id'] == 'GALLERY' ) {
				$images = get_children( array( 'post_parent' => $item['gallery_id'] ) );
				foreach ( $images as $image ) {
					$file_downloads[] = array( 'image_id' => $image->ID, 'product_id' => 'FULL' );
				}
			} else {
				$downloadable = get_post_meta( $item['product_id'], 'sunshine_product_download', true );
				if ( $downloadable == 1 ) {
					$file_downloads[] = array( 'image_id' => $item['image_id'], 'product_id' => $item['product_id'] );
				}
			}
		}

		if ( count( $file_downloads ) ) {

			if ( $sunshine->options['download_alternate_method'] ) {

				$destination = $upload_dir['basedir'].'/sunshine-downloads/order-'.SunshineFrontend::$current_order->ID.'-download.zip';
				if ( !is_dir( $upload_dir['basedir'].'/sunshine-downloads' ) ) {
					wp_mkdir_p( $upload_dir['basedir'].'/sunshine-downloads' );
				}

				if ( file_exists( $destination ) ) {
					@unlink( $destination );
				}

				$zip = new ZipArchive();
				if( $zip->open( $destination,ZIPARCHIVE::CREATE ) !== true ) {
					wp_die( __( 'Could not create zip file for download','sunshine' ) );
					exit;
				}

				foreach ( $file_downloads as $download ) {

					$image_id = $download['image_id'];
					$product_id = $download['product_id'];

					sunshine_download_image_update_count( $download['image_id'] );

					$file = get_attached_file( $image_id );
					$file_name = basename( $file );

					$width = get_post_meta( $product_id, 'sunshine_product_download_width', true );
					$height = get_post_meta( $product_id, 'sunshine_product_download_height', true );
					if ( $width && $height ) {
						$product = get_post( $product_id );
						$path = sunshine_make_intermediate_download_size( $image_id, $product->post_name, $width, $height );
						if ( !$path ) {
							continue;
						}
						$zip->addFile( $path, basename( $path ) );
					} elseif ( !$width && !$height ) {
						$base_file_name = basename( $file, '.jpg' );
						$base_file_path = str_replace( $file_name, '', $file );
						foreach ( $extensions as $extension ) {
							$extra_file = $base_file_path . $base_file_name . '.' . $extension;
							if ( file_exists( $extra_file ) ) {
								$zip->addFile( $extra_file, basename( $extra_file ) );
							}
						}
					}

					if ( $sunshine->options['download_lowres'] ) {
						$lowres = image_get_intermediate_size( $image_id, 'sunshine-lowres' );
						$zip->addFile( $upload_dir['basedir'] . '/' . $lowres['path'], basename( $lowres['path'] ) );
					}

				}

				if ( $sunshine->options['print_release'] ) {
					$print_release_file = wp_get_attachment_url( $sunshine->options['print_release'] );
					$response = sunshine_get_http_response_code( $print_release_file );
					if ( $response == 200 ) {
						$zip->addFromString( basename( $print_release_file ), file_get_contents( $print_release_file ) );
					}
				}

				$zip->close();

				if ( !file_exists( $destination ) ) {
					wp_die( __( 'Could not create zip file for download','sunshine' ) );
					exit;
				}

				if ( ! wp_next_scheduled( 'sunshine_download_alternate_clean' ) ) {
					wp_schedule_event( time(), 'daily', 'sunshine_download_alternate_clean' );
				}

				$url = $upload_dir['baseurl'].'/sunshine-downloads/order-'.SunshineFrontend::$current_order->ID.'-download.zip';
				wp_redirect( $url );

			} else {

				include( 'vendor/autoload.php' );
				$zip = new \PHPZip\Zip\Stream\ZipStream( 'order-' . SunshineFrontend::$current_order->ID . '-download.zip' );

				foreach ( $file_downloads as $download ) {

					$image_id = $download['image_id'];
					$product_id = $download['product_id'];

					sunshine_download_image_update_count( $download['image_id'] );

					$file = get_attached_file( $image_id );
					$file_name = basename( $file );

					$width = get_post_meta( $product_id, 'sunshine_product_download_width', true );
					$height = get_post_meta( $product_id, 'sunshine_product_download_height', true );
					if ( $width && $height ) {
						$product = get_post( $product_id );
						$path = sunshine_make_intermediate_download_size( $image_id, $product->post_name, $width, $height );
						if ( !$path ) {
							continue;
						}
						sunshine_add_image_to_zip( $zip, $image_id, $product->post_name );
					} elseif ( !$width && !$height ) {
						sunshine_add_image_to_zip( $zip, $image_id, 'full' );
					}

					if ( $sunshine->options['download_lowres'] ) {
						sunshine_add_image_to_zip( $zip, $image_id, 'sunshine-lowres' );
					}

				}

				if ( $sunshine->options['print_release'] ) {
					sunshine_add_image_to_zip( $zip, $sunshine->options['print_release'] );
				}

				$zip->finalize();
			}

			exit;

		} else {
			wp_die( __( 'Sorry, one of the download files does not exist','sunshine' ) );
			exit;
		}

	}
}

add_filter( 'sunshine_shipping_methods', 'sunshine_downloads_shipping_methods', 999 );
function sunshine_downloads_shipping_methods( $shipping_methods ) {
	global $sunshine;
	$has_shippable_item = 0;
	$cart = $sunshine->cart->get_cart();
	if ( is_array( $cart ) ) {
		foreach ( $cart as $item ) {
			$downloadable = get_post_meta( $item['product_id'], 'sunshine_product_download', true );
			if ( $downloadable != 1 && $item['type'] != 'gallery_download' ) {
				if ( $item['type'] == 'package' && is_array( $item['package_products'] ) ) {
					foreach ( $item['package_products'] as $package_item ) {
						if ( $package_item['type'] == 'gallery_download' ) {
							continue;
						}
						$downloadable = get_post_meta( $package_item['product_id'], 'sunshine_product_download', true );
						if ( $downloadable != 1 ) {
							$has_shippable_item = 1;
							break;
						}
					}
				} else {
					$has_shippable_item = 1;
					break;
				}
			}
		}
		if ( $has_shippable_item == 0 ) {
			foreach ( $shipping_methods as $id => $method ) {
				if ( $id != 'download' )
					unset( $shipping_methods[$id] );
			}
		} else {
			unset( $shipping_methods['download'] );
		}
	}
	return $shipping_methods;
}

add_filter( 'sunshine_cart_shipping_method_cost', 'sunshine_downloads_cart_shipping_method_cost', 999 );
function sunshine_downloads_cart_shipping_method_cost( $cost ) {
	return '';
}


add_action( 'sunshine_before_order_items', 'sunshine_download_all_link', 10, 3 );
function sunshine_download_all_link( $order_id, $order_items ) {
	$allow_download_link = false;
	foreach ( $order_items as $item ) {
		if ( $item['type'] == 'package' ) {
			if ( is_array( $item['package_products'] ) ) {
				foreach ( $item['package_products'] as $package_product_item ) {
					$package_item_downloadable = get_post_meta( $package_product_item['product_id'], 'sunshine_product_download', true );
					if ( $package_item_downloadable || $package_product_item['type'] != 'gallery_download' ) {
						$allow_download_link = true;
					}
				}
			} else {
				$selected_products = get_post_meta( $item['product_id'], 'sunshine_product_package_option' );
				foreach ( $selected_products as $selected_product ) {
					if ( $selected_product['product_id'] == 'GALLERY' ) {
						$allow_download_link = true;
					}
				}
			}
		} elseif ( $item['product_id'] == 'GALLERY' ) {
			$allow_download_link = true;
		} else {
			$allow_download_link = get_post_meta( $item['product_id'], 'sunshine_product_download', true );
		}
		$status = sunshine_get_order_status( $order_id );
		if ( $allow_download_link && ( $status->slug != 'pending' && $status->slug != 'cancelled' ) ) {
			echo '<p class="sunshine-download-all"><a href="'.get_permalink( $order_id ).'?action=download_all" class="sunshine-button">'.__( 'Download all images from this order','sunshine' ).'</a></p>';
			return;
		}
	}
}

add_filter( 'sunshine_before_order_receipt_items', 'sunshine_download_all_link_email', 10, 3 );
function sunshine_download_all_link_email( $text, $order_id, $order_items ) {
	foreach ( $order_items as $item ) {
		$downloadable = get_post_meta( $item['product_id'], 'sunshine_product_download', true );
		$status = sunshine_get_order_status( $order_id );
		if ( $downloadable && ( $status->slug != 'pending' && $status->slug != 'cancelled' ) ) {
			$text .= '<p class="sunshine-download-all"><a href="'.get_permalink( $order_id ).'?action=download_all">'.__( 'Download all images from this order','sunshine' ).'</a></p>';
			break;
		}
	}
	return $text;
}

add_filter( 'sunshine_action_menu', 'sunshine_downloads_build_action_menu' );
function sunshine_downloads_build_action_menu( $menu ) {
	global $post, $wp_query, $sunshine, $current_user;

	if ( !isset( SunshineFrontend::$current_gallery->ID ) ) return $menu;

	$download_users = get_post_meta( SunshineFrontend::$current_gallery->ID, 'sunshine_download_users', true );
	$download_user_access = true;
	if ( is_array( $download_users ) && ( !is_user_logged_in() || !in_array( $current_user->ID, $download_users ) ) )
		$download_user_access = false;

	$price_level = get_post_meta( SunshineFrontend::$current_gallery->ID, 'sunshine_gallery_price_level', true );
	if ( isset( $sunshine->options['gallery_download_price_'.$price_level] ) )
		$gallery_download_price = $sunshine->options['gallery_download_price_'.$price_level];

	if ( isset( SunshineFrontend::$current_image->ID ) && get_post_meta( SunshineFrontend::$current_gallery->ID, 'sunshine_free_image_downloads', true ) && !sunshine_is_gallery_expired()  && $download_user_access ) {
		$menu[50] = array(
			'icon' => 'download',
			'name' => __( 'Download Image','sunshine' ),
			'class' => 'sunshine-download',
			'url' => add_query_arg( 'action', 'free_image_download', get_permalink( SunshineFrontend::$current_image->ID ) )
		);
	} elseif ( isset( SunshineFrontend::$current_gallery->ID ) && get_post_meta( SunshineFrontend::$current_gallery->ID, 'sunshine_free_gallery_downloads', true ) && !sunshine_is_gallery_expired() && $download_user_access ) {
		$menu[50] = array(
			'icon' => 'download',
			'name' => __( 'Download All Images','sunshine' ),
			'class' => 'sunshine-download',
			'url' => add_query_arg( 'action', 'free_gallery_download', get_permalink( SunshineFrontend::$current_gallery->ID ) )
		);
	} elseif ( isset( $gallery_download_price ) && $gallery_download_price > 0 && !get_post_meta( SunshineFrontend::$current_gallery->ID, 'sunshine_gallery_disable_products', true ) ) {

		$disable_gallery_purchase_link = false;
		$cart = $sunshine->cart->get_cart();
		foreach ( $cart as $item ) {
			if ( isset( $item['type'] ) && $item['type'] == 'gallery_download' && $item['gallery_id'] == SunshineFrontend::$current_gallery->ID ) {
				$disable_gallery_purchase_link = true;
			}
		}
		if ( !$disable_gallery_purchase_link ) {
			$menu[60] = array(
				'icon' => 'shopping-cart',
				'name' => __( 'Purchase entire gallery digital downloads','sunshine' ),
				'class' => 'sunshine-gallery-download-purchase',
				'url' => add_query_arg( 'action', 'add_gallery_download', get_permalink( SunshineFrontend::$current_gallery->ID ) )
			);
		}
	}

	return $menu;
}

add_filter( 'sunshine_image_menu', 'sunshine_downloads_build_image_menu', 47, 2 );
function sunshine_downloads_build_image_menu( $menu, $image ) {
	global $current_user;
	if ( get_post_meta( $image->post_parent, 'sunshine_free_image_downloads', true ) ) {
		$download_users = get_post_meta( $image->post_parent, 'sunshine_download_users', true );
		$download_user_access = true;
		if ( is_array( $download_users ) && ( !is_user_logged_in() || !in_array( $current_user->ID, $download_users ) ) )
			$download_user_access = false;

		if ( !sunshine_is_gallery_expired() && $download_user_access ) {
			$menu[99] = array(
				'icon' => 'download',
				'name' => __( 'Download Image','sunshine' ),
				'url' => add_query_arg( 'action', 'free_image_download', get_permalink( $image->ID ) )
			);
		}
	}
	return $menu;
}

add_filter( 'sunshine_lightbox_menu', 'sunshine_downloads_lightbox_menu', 30, 2 );
function sunshine_downloads_lightbox_menu( $menu, $image ) {
	if ( get_post_meta( $image->post_parent, 'sunshine_free_image_downloads', true ) ) {
		$menu .= ' <a href="'.add_query_arg( 'action', 'free_image_download', get_permalink( $image->ID ) ).'" ><i class="fa fa-download"></i></a>';
	}
	return $menu;
}

function sunshine_download_gallery( $gallery_id, $sizes = array() ) {
	global $sunshine;

	set_time_limit(0);

	if ( !empty( $sizes ) ) {
		foreach ( $sizes as $product_id ) {
			if ( !is_numeric( $product_id ) ) {
				continue;
			}
			$width = get_post_meta( $product_id, 'sunshine_product_download_width', true );
			$height = get_post_meta( $product_id, 'sunshine_product_download_height', true );
			if ( $width && $height ) {
				$product_sizes[ $product_id ][ 'width' ] = $width;
				$product_sizes[ $product_id ][ 'height' ] = $height;
				$product = get_post( $product_id );
				$product_sizes[ $product_id ][ 'name' ] = $product->post_name;
			} elseif ( !$width && !$height ) {
				$product_sizes['full'] = 'full';
			}
		}
	}

	if ( empty( $product_sizes ) ) {
		$product_sizes = array( 'full' );
	}

	if ( $sunshine->options['download_alternate_method'] == 1 ) {
		sunshine_download_gallery_alternate( $gallery_id, $product_sizes );
		return;
	}

	$upload_dir = wp_upload_dir();

	$gallery = get_post( $gallery_id );

	/*
	include( 'zipstream.php' );
	$zip = new ZipStream( 'gallery-'.$gallery_id.'-download.zip' );
	*/
	include( 'vendor/autoload.php' );
	$zip = new \PHPZip\Zip\Stream\ZipStream( $gallery->post_name . '.zip' );

	$extensions = sunshine_allowed_file_extensions();
	$attachments = get_posts( array( 'post_type' => 'attachment', 'post_parent' => $gallery_id, 'nopaging' => 1 ) );
	foreach ( $attachments as $attachment ) {

		sunshine_download_image_update_count( $attachment->ID );

		foreach ( $product_sizes as $product_id => $size ) {
			if ( !is_numeric( $product_id ) || empty( $size['width'] ) ) {
				continue;
			}
			$product = get_post( $product_id );
			$path = sunshine_make_intermediate_download_size( $attachment->ID, $product->post_name, $size['width'], $size['height'] );
			sunshine_add_image_to_zip( $zip, $attachment->ID, $size['name'] );
		}

		if ( in_array( 'full', $product_sizes ) ) {
			sunshine_add_image_to_zip( $zip, $attachment->ID, 'full' );
		}

		if ( !empty( $sunshine->options['download_lowres'] ) ) {
			sunshine_add_image_to_zip( $zip, $attachment->ID, 'sunshine-lowres' );
		}

	}

	if ( $sunshine->options['print_release'] ) {
		sunshine_add_image_to_zip( $zip, $sunshine->options['print_release'] );
	}

	$zip->finalize();
	exit;

}

function sunshine_download_gallery_alternate( $gallery_id, $sizes ) {
	global $sunshine;
	ignore_user_abort( true );
	$upload_dir = wp_upload_dir();
	$dir = get_post_meta( $gallery_id, 'sunshine_gallery_images_download_directory', true );

	$destination = $upload_dir['basedir'].'/sunshine-downloads/gallery-'.$gallery_id.'-download.zip';
	if ( !is_dir( $upload_dir['basedir'].'/sunshine-downloads' ) )
		wp_mkdir_p( $upload_dir['basedir'].'/sunshine-downloads' );

	$zip = new ZipArchive();
	if( $zip->open( $destination,ZIPARCHIVE::CREATE ) !== true ) {
		wp_die( __( 'Could not create zip file for download','sunshine' ) );
		exit;
	}

	$extensions = sunshine_allowed_file_extensions();
	$attachments = get_posts( array( 'post_type' => 'attachment', 'post_parent' => $gallery_id, 'nopaging' => 1 ) );
	foreach ( $attachments as $attachment ) {

		sunshine_download_image_update_count( $attachment->ID );

		$file = get_attached_file( $attachment->ID );
		$file_name = basename( $file );

		foreach ( $sizes as $product_id => $size ) {
			if ( !is_numeric( $product_id ) ) {
				continue;
			}
			$product = get_post( $product_id );
			$path = sunshine_make_intermediate_download_size( $attachment->ID, $product->post_name, $size['width'], $size['height'] );
			$zip->addFile( $path, basename( $path ) );
		}

		if ( in_array( 'full', $sizes ) ) {
			$base_file_name = basename( $file, '.jpg' );
			$base_file_path = str_replace( basename( $file ), '', $file );
			foreach ( $extensions as $extension ) {
				$extra_file = $base_file_path . $base_file_name . '.' . $extension;
				if ( file_exists( $extra_file ) ) {
					$zip->addFile( $extra_file, basename( $extra_file ) );
				}
			}
		}

		if ( $sunshine->options['download_lowres'] ) {
			$lowres = image_get_intermediate_size( $attachment->ID, 'sunshine-lowres' );
			$zip->addFile( $upload_dir['basedir'] . '/' . $lowres['path'], basename( $lowres['path'] ) );
		}

	}

	if ( $sunshine->options['print_release'] ) {
		$print_release_file = get_attached_file( $sunshine->options['print_release'] );
		$zip->addFile( $print_release_file, basename( $print_release_file ) );
	}

	$zip->close();

	if ( !file_exists( $destination ) ) {
		wp_die( __( 'Could not create zip file for download','sunshine' ) );
		exit;
	}

	if ( ! wp_next_scheduled( 'sunshine_download_alternate_clean' ) ) {
		wp_schedule_event( time(), 'daily', 'sunshine_download_alternate_clean' );
	}

	$url = $upload_dir['baseurl'].'/sunshine-downloads/gallery-'.$gallery_id.'-download.zip';
	wp_redirect( $url );
	exit;

}

add_action( 'sunshine_download_gallery_alternate_clean', 'sunshine_download_gallery_alternate_clean_process' );
function sunshine_download_gallery_alternate_clean_process() {
	$upload_dir = wp_upload_dir();
	$files = glob( $upload_dir['basedir'].'/sunshine-downloads/*' ); // get all file names
	foreach( $files as $file ) { // iterate files
		if( is_file( $file ) )
			unlink( $file ); // delete file
	}
}

function sunshine_download_image_update_count( $image_id ) {
	if ( isset( SunshineFrontend::$current_order ) ) {
		$download_count = get_post_meta( $image_id, 'download_count_order_' . SunshineFrontend::$current_order->ID, true );
		$download_count++;
		update_post_meta( $image_id, 'download_count_order_' . SunshineFrontend::$current_order->ID, $download_count );
	} else {
		$download_count = get_post_meta( $image_id, 'download_count', true );
		$download_count++;
		update_post_meta( $image_id, 'download_count', $download_count );
	}
}

function sunshine_download_image( $image_id, $sizes = array() ) {
	global $sunshine;

	if ( empty( $sizes ) ) {
		$sizes = array( 'full' );
	}

	// Update download count
	sunshine_download_image_update_count( $image_id );

	// Only one file to download, send just the one .jpg file
	if ( count( $sizes ) == 1 && !$sunshine->options['download_lowres'] && !$sunshine->options['print_release'] ) {
		foreach ( $sizes as $product_id ) {
			if ( $product_id == 'full' ) {
				$file = get_attached_file( $image_id );
			} else {
				$width = get_post_meta( $product_id, 'sunshine_product_download_width', true );
				$height = get_post_meta( $product_id, 'sunshine_product_download_height', true );
				if ( $width && $height ) {
					$product = get_post( $product_id );
					$file = sunshine_make_intermediate_download_size( $image_id, $product->post_name, $width, $height );
					if ( !$file ) {
						continue;
					}
				} else {
					$file = get_attached_file( $image_id );
				}
			}
			if ( !isset( $file ) ) {
				wp_die( __( 'Sorry, file not available for download', 'sunshine' ) );
				exit;
			}
			$quoted = sprintf( '"%s"', addcslashes( basename( $file ), '"\\' ) );
			$size   = filesize( $file );
			header( 'Content-Description: File Transfer' );
			header( 'Content-Type: application/octet-stream' );
			header( 'Content-Disposition: attachment; filename=' . $quoted );
			header( 'Content-Transfer-Encoding: binary' );
			header( 'Connection: Keep-Alive' );
			header( 'Expires: 0' );
			header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
			header( 'Pragma: public' );
			header( 'Content-Length: ' . $size );
			readfile( $file );
			exit;
		}
	}

	if ( $sunshine->options['download_alternate_method'] == 1 ) {
		sunshine_download_image_alternate( $image_id, $sizes );
		return;
	}

	$upload_dir = wp_upload_dir();

	include( 'vendor/autoload.php' );
	$zip = new \PHPZip\Zip\Stream\ZipStream( 'image-' . $image_id . '-download.zip' );

	$file = get_attached_file( $image_id );
	$file_name = basename( $file );

	if ( !file_exists( $file ) ) {
		wp_die( __( 'Download file does not exist','sunshine' ) );
		exit;
	}

	if ( $sunshine->options['download_lowres'] ) {
		sunshine_add_image_to_zip( $zip, $image_id, 'sunshine-lowres' );
	}

	foreach ( $sizes as $product_id ) {
		if ( !is_numeric( $product_id ) ) {
			continue;
		}
		$width = get_post_meta( $product_id, 'sunshine_product_download_width', true );
		$height = get_post_meta( $product_id, 'sunshine_product_download_height', true );
		if ( $width && $height ) {
			$product = get_post( $product_id );
			$path = sunshine_make_intermediate_download_size( $image_id, $product->post_name, $width, $height );
			if ( !$path ) {
				continue;
			}
			$file_name = basename( $path, '.jpg' ) . '-' . sanitize_title( get_the_title( $product_id ) ) . '.jpg';
			sunshine_add_image_to_zip( $zip, $image_id, $product->post_name );
		} elseif ( !$width && !$height ) {
			$sizes[] = 'full';
		}
	}

	if ( in_array( 'full', $sizes ) ) {
		$extensions = sunshine_allowed_file_extensions();
		$base_file_name = basename( $file, '.jpg' );
		$base_file_path = str_replace( basename( $file ), '', $file );
		foreach ( $extensions as $extension ) {
			$extra_file = $base_file_path . $base_file_name . '.' . $extension;
			if ( file_exists( $extra_file ) ) {
				//$zip->addLargeFile( $extra_file, basename( $save_file_name, '.jpg' ) . '.' . $extension );
				$file_name = basename( $extra_file, '.jpg' ) . '-' . sanitize_title( __( 'Full Resolution', 'sunshine' ) ) . '.jpg';
				//sunshine_add_image_to_zip( $zip, $extra_file, $file_name );
				sunshine_add_image_to_zip( $zip, $image_id, 'full' );
			}
		}
	}

	if ( $sunshine->options['print_release'] ) {
		$print_release_file = get_attached_file( $sunshine->options['print_release'] );
		if ( file_exists( $print_release_file ) ) {
			//$zip->addLargeFile( $print_release_file, basename( $print_release_file ) );
			//sunshine_add_image_to_zip( $zip, $print_release_file );
			sunshine_add_image_to_zip( $zip, $sunshine->options['print_release'] );
		}
	}

	$zip->finalize();
	exit;

}

function sunshine_download_image_alternate( $image_id, $sizes = '' ) {
	global $sunshine;

	$upload_dir = wp_upload_dir();
	$destination = $upload_dir['basedir'].'/sunshine-downloads/image-'.$image_id.'-download.zip';
	if ( !is_dir( $upload_dir['basedir'].'/sunshine-downloads' ) ) {
		wp_mkdir_p( $upload_dir['basedir'].'/sunshine-downloads' );
	}

	if ( file_exists( $destination ) ) {
		@unlink( $destination );
	}

	$file = wp_get_attachment_url( $image_id );
	$file_name = basename( $file );

	/*
	$response = sunshine_get_http_response_code( $file );
	if ( $response == 404 ) {
		wp_die( __( 'Download file does not exist','sunshine' ) );
		exit;
	}
	*/

	$zip = new ZipArchive();
	if( $zip->open( $destination,ZIPARCHIVE::CREATE ) !== true ) {
		wp_die( __( 'Could not create zip file for download','sunshine' ) );
		exit;
	}

	if ( $sunshine->options['print_release'] ) {
		$print_release_file = get_attached_file( $sunshine->options['print_release'] );
		$zip->addFile( $print_release_file, basename( $print_release_file ) );
	}

	if ( $sunshine->options['download_lowres'] ) {
		$lowres = image_get_intermediate_size( $image_id, 'sunshine-lowres' );
		if ( file_exists( $upload_dir['basedir'] . '/' . $lowres['path'] ) ) {
			$zip->addFile( $upload_dir['basedir'] . '/' . $lowres['path'], basename( $lowres['path'] ) );
		}
	}

	foreach ( $sizes as $product_id ) {
		if ( !is_numeric( $product_id ) ) {
			continue;
		}
		$width = get_post_meta( $product_id, 'sunshine_product_download_width', true );
		$height = get_post_meta( $product_id, 'sunshine_product_download_height', true );
		if ( $width && $height ) {
			$product = get_post( $product_id );
			$path = sunshine_make_intermediate_download_size( $image_id, $product->post_name, $width, $height );
			if ( !$path ) {
				continue;
			}
			$zip->addFile( $path, basename( $path ) );
		} elseif ( !$width && !$height ) {
			$sizes[] = 'full';
		}
	}

	if ( in_array( 'full', $sizes ) ) {
		$extensions = sunshine_allowed_file_extensions();
		$base_file_name = basename( $file, '.jpg' );
		$file_path = get_attached_file( $image_id );
		$base_file_path = str_replace( $file_name, '', $file_path );
		foreach ( $extensions as $extension ) {
			$extra_file = $base_file_path . $base_file_name . '.' . $extension;
			if ( file_exists( $extra_file ) ) {
				$zip->addFile( $extra_file, $file_name );
			}
		}
	}

	$zip->close();

	if ( !file_exists( $destination ) ) {
		wp_die( __( 'Could not finalize zip file for download','sunshine' ) );
		exit;
	}

	if ( ! wp_next_scheduled( 'sunshine_download_alternate_clean' ) ) {
		wp_schedule_event( time(), 'daily', 'sunshine_download_alternate_clean' );
	}

	$url = $upload_dir['baseurl'].'/sunshine-downloads/image-'.$image_id.'-download.zip';
	wp_redirect( $url );
	exit;

}



add_action( 'template_redirect', 'sunshine_free_gallery_download', 1 );
function sunshine_free_gallery_download() {
	if ( isset( $_GET['action'] ) && $_GET['action'] == 'free_gallery_download' && isset( SunshineFrontend::$current_gallery->ID ) && !post_password_required( SunshineFrontend::$current_gallery->ID ) ) {

		// Is this allowed?
		if ( !isset( SunshineFrontend::$current_gallery->ID ) || !get_post_meta( SunshineFrontend::$current_gallery->ID, 'sunshine_free_gallery_downloads', true ) ) {
			wp_die( __( 'You do not have permission to download these files.','sunshine' ) );
			exit;
		}

		do_action( 'sunshine_free_gallery_download', SunshineFrontend::$current_gallery->ID );

		$product_sizes = array();
		$downloadable_products = get_posts( 'post_type=sunshine-product&meta_key=sunshine_product_download_free&meta_value=1&nopaging=true' );
		foreach ( $downloadable_products as $product ) {
			$product_sizes[] = $product->ID;
		}

		sunshine_download_gallery( SunshineFrontend::$current_gallery->ID, $product_sizes );

	}
}

add_action( 'template_redirect', 'sunshine_free_image_download', 1 );
function sunshine_free_image_download() {
	if ( isset( $_GET['action'] ) && $_GET['action'] == 'free_image_download' && isset( SunshineFrontend::$current_image->ID ) && !post_password_required( SunshineFrontend::$current_gallery->ID ) ) {

		// Is this allowed?
		if ( !isset( SunshineFrontend::$current_image->ID ) || !get_post_meta( SunshineFrontend::$current_gallery->ID, 'sunshine_free_image_downloads', true ) ) {
			wp_die( __( 'You do not have permission to download this file.','sunshine' ) );
			exit;
		}

		// Increment download count
		$download_count = get_post_meta( SunshineFrontend::$current_image->ID, 'sunshine_download_count', true );
		$download_count++;
		update_post_meta( SunshineFrontend::$current_image->ID, 'sunshine_download_count', $download_count );

		do_action( 'sunshine_free_image_download', SunshineFrontend::$current_image->ID, SunshineFrontend::$current_gallery->ID );

		$downloadable_products = get_posts( 'post_type=sunshine-product&meta_key=sunshine_product_download_free&meta_value=1&nopaging=true' );
		$product_sizes = array();
		foreach ( $downloadable_products as $product ) {
			$product_sizes[] = $product->ID;
		}

		sunshine_download_image( SunshineFrontend::$current_image->ID, $product_sizes );
		exit;

	}
}


add_action( 'template_redirect', 'sunshine_order_gallery_download', 1 );
function sunshine_order_gallery_download() {
	global $current_user;
	if ( isset( $_GET['action'] ) && $_GET['action'] == 'order_download_gallery' && isset( $_GET['gallery_id'] ) && $_GET['gallery_id'] > 0 && isset( SunshineFrontend::$current_order->ID ) ) {

		// Must be logged in
		if ( !is_user_logged_in() ) {
			wp_die( sprintf( __( 'Sorry, you must be <a href="%s">logged in</a> to download this file','sunshine' ), wp_login_url( sunshine_current_url( false ) ) ) );
			exit;
		}

		// Check if user can download file
		$can_download = false;

		// Is this for an order associated with this user?
		if ( get_post_meta( SunshineFrontend::$current_order->ID, '_sunshine_customer_id', true ) != $current_user->ID ) {
			wp_die( __( 'Sorry, this is not your order', 'sunshine' ) );
			exit;
		}

		// Order status OK?
		$status = sunshine_get_order_status( SunshineFrontend::$current_order->ID );
		if ( $status->slug == 'pending' || $status->slug == 'cancelled' ) {
			wp_die( __( 'Sorry, your item is not available for download yet', 'sunshine' ) );
			exit;
		}

		sunshine_download_gallery( $_GET['gallery_id'] );

	}

}

add_action( 'template_redirect', 'sunshine_add_gallery_download', 87 );
function sunshine_add_gallery_download() {
	global $sunshine;
	if ( isset( $_GET['action'] ) && $_GET['action'] == 'add_gallery_download' && isset( SunshineFrontend::$current_gallery->ID ) ) {
		$cart = $sunshine->cart->get_cart();
		if ( !empty( $cart ) ) {
			foreach ( $cart as $item ) {
				if ( $item['type'] == 'gallery_download' && $item['gallery_id'] == SunshineFrontend::$current_gallery->ID ) {
					$sunshine->add_error( sprintf( __( 'You have already added the gallery download for %s to your cart','sunshine' ), get_the_title( SunshineFrontend::$current_gallery->ID ) ) );
					return;
				}
			}
		}
		$result = $sunshine->cart->add_to_cart( 0, 0, 1, '', '', 'gallery_download' );
		$sunshine->add_message( __( 'Gallery digital negatives added to cart!','sunshine' ).' <a href="'.sunshine_url( 'cart' ).'" target="_top">'.__( 'View cart','sunshine' ).'</a>' );
	}
}

add_filter( 'sunshine_add_to_cart_item', 'sunshine_gallery_download_add_to_cart' );
function sunshine_gallery_download_add_to_cart( $item ) {
	global $sunshine;
	if ( $item['type'] == 'gallery_download' ) {
		$price_level = get_post_meta( SunshineFrontend::$current_gallery->ID, 'sunshine_gallery_price_level', true );
		$price = $sunshine->options['gallery_download_price_'.$price_level];
		$item['price_level'] = $price_level;
		$item['price'] = $price;
		$item['total'] = $price;
		$item['gallery_id'] = SunshineFrontend::$current_gallery->ID;
	}
	return $item;
}

add_filter( 'sunshine_add_to_cart_increment_qty', 'sunshine_gallery_download_add_to_cart_increment_qty', 10, 3 );
function sunshine_gallery_download_add_to_cart_increment_qty( $status, $cart_item, $item ) {
	if ( $item['type'] == 'gallery_download' )
		return false;
	return $status;
}

add_filter( 'sunshine_cart_image_html', 'sunshine_gallery_download_cart_image_html', 53, 3 );
add_filter( 'sunshine_order_image_html', 'sunshine_gallery_download_cart_image_html', 53, 3 );
function sunshine_gallery_download_cart_image_html( $image_html, $item, $thumb ) {
	if ( $item['type'] == 'gallery_download' ) {
		if ( has_post_thumbnail( $item['gallery_id'] ) )
			$src = wp_get_attachment_image_src( get_post_thumbnail_id( $item['gallery_id'] ), 'sunshine-thumbnail' );
		else if ( $images = get_children( array(
					'post_parent' => $item['gallery_id'],
					'post_type' => 'attachment',
					'numberposts' => 1,
					'post_mime_type' => 'image',
					'orderby' => 'menu_order ID',
					'order' => 'ASC' ) ) ) {
			foreach( $images as $image )
				$src = wp_get_attachment_image_src( $image->ID, 'sunshine-thumbnail' );
		}
		$image_html = '<a href="'.get_permalink( $item['gallery_id'] ).'"><img src="'.$src[0].'" alt="" class="sunshine-thumbnail" /></a>';
	}
	return $image_html;
}

add_filter( 'sunshine_cart_item_category', 'sunshine_gallery_download_cart_item_category', 50, 2 );
function sunshine_gallery_download_cart_item_category( $cat, $item ) {
	if ( $item['type'] == 'gallery_download' )
		$cat = __( 'Gallery Digital Negatives', 'sunshine' );
	return $cat;
}

add_filter( 'sunshine_cart_item_name', 'sunshine_gallery_download_cart_item_name', 50, 2 );
function sunshine_gallery_download_cart_item_name( $title, $item ) {
	if ( $item['type'] == 'gallery_download' )
		$title = get_the_title( $item['gallery_id'] );
	return $title;
}

add_filter( 'sunshine_get_line_item_price', 'sunshine_gallery_download_get_line_item_price', 10, 2 );
function sunshine_gallery_download_get_line_item_price( $price, $item ) {
	if ( $item['type'] == 'gallery_download' ) {
		$price = $item['price'];
	}
	return $price;
}

add_filter( 'sunshine_cart_item_comments', 'sunshine_download_admin_download_count', 10, 2 );
function sunshine_download_admin_download_count( $comments, $item ) {
	if ( is_admin() && isset( $_GET['post'] ) ) {
		if ( isset( $item['image_id'] ) && is_numeric( $item['product_id'] ) ) {
			$downloadable = get_post_meta( $item['product_id'], 'sunshine_product_download', true );
			if ( $downloadable ) {
				$download_count = get_post_meta( $item['image_id'], 'download_count_order_' . $_GET['post'], true );
				$comments .= '<br />' . sprintf( __( 'Downloaded count: %s', 'sunshine' ), ( $download_count ) ? $download_count : '0' );
			}
		}
	}
	return $comments;
}

/**
 * Set order status to "completed" if only digital downloads in an order
 * and payment method is not offline
 *
 * @return void
 */
add_action( 'set_object_terms', 'sunshine_digital_downloads_order_status', 10, 4 );
function sunshine_digital_downloads_order_status( $order_id, $terms, $tt_ids, $taxonomy ) {

	if ( $taxonomy != 'sunshine-order-status' || is_object_in_term( $order_id, 'sunshine-order-status', 'shipped' ) ) {
		return;
	}

	foreach ( $terms as $term ) {
		if ( $term == 'pending' || $term == 'cancelled' ) {
			return;
		}
	}

	$order_data = unserialize( get_post_meta( $order_id, '_sunshine_order_data', true ) );
	if ( $order_data['payment_method'] == 'offline' ) {
		return;
	}

	$items = unserialize( get_post_meta( $order_id, '_sunshine_order_items', true ) );
	foreach ( $items as $item ) {
		$download = get_post_meta( $item['product_id'], 'sunshine_product_download', true );
		if ( $download == 0 ) {
			return;
		}
	}
	wp_set_post_terms( $order_id, 'shipped', 'sunshine-order-status' );
}

add_action( 'sunshine_bulk_add_product_item', 'sunshine_digital_downloads_bulk_add_product_item', 10 );
function sunshine_digital_downloads_bulk_add_product_item( $i ) {
?>
	<th>Downloadable:</th>
	<td><input type="checkbox" value="1" name="downloadable[]" /></td>
<?php
}

add_action( 'sunshine_bulk_add_product', 'sunshine_digital_downloads_bulk_add_product', 10, 3 );
function sunshine_digital_downloads_bulk_add_product( $product_id, $array, $i ) {
	if ( isset( $array['downloadable'] ) && $array['downloadable'][$i] == 1 )
		add_post_meta( $product_id, 'sunshine_product_download', 1 );
}

add_filter( 'sunshine_add_shipping_methods', 'sunshine_init_shipping_downloads', 5 );
function sunshine_init_shipping_downloads( $methods ) {
	$methods['download'] = array(
		'id' => 'download',
		'title' => __( 'No shipping, download only', 'sunshine' ),
		'taxable' => 0,
		'cost' => 0
	);
	return $methods;
}

add_filter( 'sunshine_options_extra', 'sunshine_digital_downloads_options', 1 );
function sunshine_digital_downloads_options( $options ) {
	global $sunshine;

	$options[] = array( 'name' => __( 'Downloads', 'sunshine' ), 'type' => 'heading' );

	$options[] = array( 'name' => __( 'Low Resolution Image Options','sunshine' ), 'type' => 'title' );
	$options[] = array(
		'name' => __( 'Image Width', 'sunshine' ),
		'id'   => 'lowres_width',
		'type' => 'text',
		'css' => 'width: 50px;'
	);
	$options[] = array(
		'name' => __( 'Image Height', 'sunshine' ),
		'id'   => 'lowres_height',
		'type' => 'text',
		'css' => 'width: 50px;'
	);

	$options[] = array( 'name' => __( 'Gallery Digital Negatives','sunshine' ), 'type' => 'title', 'desc' => __( 'Allow users to purchase digital negatives of an the entire session. Leave price blank to disable this feature for a specific price level.','sunshine' ) );
	$options[] = array(
		'name' => __( 'Tax gallery digital negatives', 'sunshine' ),
		'id'   => 'tax_gallery_download',
		'type' => 'checkbox',
		'tip' => __( 'Apply a tax to the gallery digital negatives','sunshine' ),
		'options' => array( 1 )
	);
	$price_levels = get_terms( 'sunshine-product-price-level', array( 'hide_empty' => false ) );
	foreach ( $price_levels as $price_level ) {
		$options[] = array(
			'name' => $price_level->name.' ('.sunshine_currency_symbol().')',
			'id'   => 'gallery_download_price_'.$price_level->term_id,
			'type' => 'text',
			'css' => 'width: 50px;'
		);
	}

	$options[] = array( 'name' => __( 'More Digital Download Options','sunshine' ), 'type' => 'title', 'desc' => '' );
	$options[] = array(
		'name' => __( 'Alternate Download Method', 'sunshine' ),
		'id'   => 'download_alternate_method',
		'type' => 'checkbox',
		'desc' => __( 'Check this if downloads are not working for your users or to integrate with Amazon S3. This method is less efficient but more likely to work.','sunshine' ),
		'options' => array( 1 )
	);
	$download_lowres_desc_extra = '';
	if ( $sunshine->options['watermark_image'] ) {
		$download_lowres_desc_extra = __( ' (This image will have your selected watermark on it)', 'sunshine' );
	}
	$options[] = array(
		'name' => __( 'Include low res files in download', 'sunshine' ),
		'id'   => 'download_lowres',
		'type' => 'checkbox',
		'desc' => __( 'Check this if you want to include the low resolution images in downloads','sunshine' ) . $download_lowres_desc_extra,
		'options' => array( 1 )
	);

	$attachments = get_posts( array( 'post_type' => 'attachment', 'post_mime_type' => 'application/pdf', 'post_parent' => 0, 'posts_per_page' => -1 ) );
	$files[0] = __( 'None', 'sunshine' );
	foreach ( $attachments as $attachment ) {
		$files[$attachment->ID] = $attachment->post_title;
	}

	$options[] = array(
		'name' => __( 'Print Release', 'sunshine' ),
		'id'   => 'print_release',
		'type' => 'select',
		'options' => $files,
		'select2' => true,
		'desc' => __( 'Print release file to be included with any file download. <strong>PDF required</strong>. Upload a file to your <a href="upload.php">Media gallery</a>, then select it here.','sunshine' )
	);

	return $options;
}

// Make the quality 90%, we don't want crap quality images
add_filter( 'jpeg_quality', create_function( '', 'return 90;' ) );

add_filter( 'sunshine_install_options', 'sunshine_download_install_options' );
add_filter( 'sunshine_update_options', 'sunshine_download_install_options' );
function sunshine_download_install_options( $options ) {
	if ( $options['lowres_width'] == '' )
		$options['lowres_width'] = '800';
	if ( $options['lowres_height'] == '' )
		$options['lowres_height'] = '800';
	return $options;
}

function sunshine_download_update_options() {
	$options = get_option('sunshine_options');
	if ( $options['lowres_width'] == '' )
		$options['lowres_width'] = '800';
	if ( $options['lowres_height'] == '' )
		$options['lowres_height'] = '800';
	update_option('sunshine_options', $options);
}

add_action( 'init', 'sunshine_download_image_sizes_init' );
function sunshine_download_image_sizes_init() {
	global $sunshine;
	$lowres_width = ( $sunshine->options['lowres_width'] > 0 ) ? $sunshine->options['lowres_width'] : 800;
	$lowres_height = ( $sunshine->options['lowres_height'] > 0 ) ? $sunshine->options['lowres_height'] : 800;
	add_image_size( 'sunshine-lowres', $lowres_width, $lowres_height, false );
	$args = array(
		'post_type' => 'sunshine-product',
		'meta_key' => 'sunshine_product_download_width',
		'nopaging' => true
	);
	$product_image_sizes = get_posts( $args );
	if ( !empty( $product_image_sizes ) ) {
		foreach ( $product_image_sizes as $product ) {
			$width = get_post_meta( $product->ID, 'sunshine_product_download_width', true );
			$height = get_post_meta( $product->ID, 'sunshine_product_download_height', true );
			if ( $width && $height ) {
				add_image_size( $product->post_name, $width, $height, false );
			}
		}
	}

}

add_filter( 'sunshine_image_sizes', 'sunshine_download_image_sizes' );
function sunshine_download_image_sizes( $image_sizes ) {
	$image_sizes[] = 'sunshine-lowres';
	return $image_sizes;
}

add_filter( 'sunshine_image_size', create_function( '', 'return "sunshine-lowres";' ) );

add_action( 'template_redirect', 'sunshine_download_order_image' );
function sunshine_download_order_image() {
	global $current_user, $post, $sunshine;
	if ( isset( $_GET['action'] ) && $_GET['action'] == 'order_download_image' && isset( $_GET['hash'] ) ) {

		$customer_id = get_post_meta( SunshineFrontend::$current_order->ID, '_sunshine_customer_id', true );
		if ( $customer_id && $current_user->ID != $customer_id ) {
			$login_url = wp_login_url( sunshine_current_url( false ) );
			wp_die( sprintf( __( 'Sorry, you do not have access to this download. <a href="%s">Please login</a> to the account used to purchase this image.', 'sunshine' ), $login_url ) );
			exit;
		}

		// Order status OK?
		$status = sunshine_get_order_status( SunshineFrontend::$current_order->ID );
		if ( $status->slug == 'pending' || $status->slug == 'cancelled' ) {
			wp_die( __( 'Sorry, your item cannot yet be downloaded', 'sunshine' ) );
			exit;
		}

		$order_items = sunshine_get_order_items( SunshineFrontend::$current_order->ID );
		$image_in_order = false;
		foreach ( $order_items as $item ) {
			if ( $item['type'] == 'package' ) {
				foreach ( $item['package_products'] as $package_product_item ) {
					if ( $package_product_item['hash'] == $_GET['hash'] ) {
						$image_in_order = true;
						$downloadable = get_post_meta( $package_product_item['product_id'], 'sunshine_product_download', true );
						if ( $downloadable != 1 ) {
							wp_die( __( 'Sorry, this is not a downloadable product' , 'sunshine' ) );
							exit;
						}
						$image_id = $package_product_item['image_id'];
						$products[] = $package_product_item['product_id'];
					}
				}
			} elseif ( $item['hash'] == $_GET['hash'] ) {
				$image_in_order = true;
				$downloadable = get_post_meta( $item['product_id'], 'sunshine_product_download', true );
				if ( $downloadable != 1 ) {
					wp_die( __( 'Sorry, this is not a downloadable product' , 'sunshine' ) );
					exit;
				}
				$image_id = $item['image_id'];
				$products[] = $item['product_id'];
			}
		}
		if ( !$image_in_order ) {
			wp_die( __( 'Sorry, this image was not in your order', 'sunshine' ) );
			exit;
		}

		sunshine_download_image( $image_id, $products );
		exit;

	}
}

add_action( 'delete_attachment', 'sunshine_digital_download_delete_attachment' );
function sunshine_digital_download_delete_attachment( $attachment_id ) {
	global $sunshine;
	$gallery = get_post( $attachment_id );
	if ( get_post_type( $gallery->post_parent ) == 'sunshine-gallery' ) {

		$downloadable_products = get_posts( 'post_type=sunshine-product&meta_key=sunshine_product_download_width&nopaging=true' );
		$product_sizes = array();
		foreach ( $downloadable_products as $product ) {
			$product_sizes[] = $product->ID;
		}

		foreach ( $product_sizes as $product_id ) {
			if ( !is_numeric( $product_id ) ) {
				continue;
			}
			$width = get_post_meta( $product_id, 'sunshine_product_download_width', true );
			$height = get_post_meta( $product_id, 'sunshine_product_download_height', true );
			if ( $width && $height ) {
				$image_sizes[] = array( $width, $height );
			}
		}

		// Get upload directory info
		$path = get_attached_file( $attachment_id );
	    $upload_info = wp_upload_dir();
	    $upload_dir  = $upload_info['basedir'];
	    $upload_url  = $upload_info['baseurl'];
	    $path_info = pathinfo( $path );
	    $ext       = $path_info['extension'];
	    $rel_path  = str_replace( array( $upload_dir, ".$ext" ), '', $path );

		foreach ( $image_sizes as $image_size ) {

		    // Get file path info
		    $suffix    = "{$image_size[0]}x{$image_size[1]}";
		    $dest_path = "{$upload_dir}{$rel_path}-{$suffix}.{$ext}";

		    // If file exists delete it
		    if ( file_exists( $dest_path ) ) {
		        @unlink( $dest_path );
			}

		}
	}
}

add_filter( 'sunshine_product_class', 'sunshine_digital_download_product_class', 10, 2 );
function sunshine_digital_download_product_class( $classes, $product_id ) {
	$downloadable = get_post_meta( $product_id, 'sunshine_product_download', true );
	if ( $downloadable )
		$classes[] = 'sunshine-download';
	return $classes;
}

add_action( 'sunshine_after_cart_items', 'sunshine_digital_download_after_cart_items' );
function sunshine_digital_download_after_cart_items() {
?>
	<script>
	jQuery(document).ready(function($){
		$('.sunshine-download .sunshine-qty').attr('disabled', true);
	});
	</script>
<?php
}

add_action( 'sunshine_checkout_end_form', 'sunshine_digital_download_checkout_end_form' );
function sunshine_digital_download_checkout_end_form() {
	global $sunshine;
	$methods = $sunshine->shipping->get_shipping_methods();
	if ( count( $methods ) == 1 && isset( $methods['download'] ) ) {
?>
		<input type="hidden" name="shipping_method" value="download" />
		<script>
		jQuery( document ).ready( function() {
			jQuery('#sunshine-billing-toggle input').attr('checked', false).trigger('change');
			jQuery('#sunshine-checkout-step-shipping, #sunshine-checkout-step-shipping-methods, .sunshine-shipping').remove();
			jQuery('#sunshine-billing-toggle').remove();
		});
		</script>
<?php
	}

}

add_action( 'sunshine_after_add_to_cart_form', 'sunshine_digital_download_after_add_to_cart_form' );
function sunshine_digital_download_after_add_to_cart_form() {
?>
	<script>
	jQuery(document).ready(function($){
		$('input[name="sunshine_product"]').change(function(){
			$('.sunshine-qty').attr('disabled', false);
			if ($(this).closest('li').hasClass('sunshine-download')) {
				if ($(this).is(':checked')) {
					$('.sunshine-qty').attr({ 'disabled': true, 'value': 1 });
				}
			}
		});
	});
	</script>
<?php
}

add_action( 'sunshine_gallery_analytics_box', 'sunshine_digital_download_analytics_box' );
function sunshine_digital_download_analytics_box( $post ) {
	echo '<tr><th>'.__( 'Images Downloaded', 'sunshine' ).':</th><td>';
	$args = array(
		'post_type' => 'attachment',
		'post_parent' => $post->ID,
		'orderby' => 'menu_order ID',
		'nopaging' => true,
		'order' => 'ASC',
		'meta_key' => 'sunshine_download_count',
		'orderby' => 'meta_value_num',
		'order' => 'DESC'
	);
	$images_downloaded = get_posts( $args );
	if ( $images_downloaded ) {
		$images_download_html = '';
		foreach ( $images_downloaded as $image ) {
			$sunshine_download_count = get_post_meta( $image->ID,'sunshine_download_count',true );
			if ( $sunshine_download_count ) {
				$thumb = wp_get_attachment_thumb_url( $image->ID );
				$images_download_html .= '<li>';
				$images_download_html .= '<img src="'.$thumb.'" alt="" height="75" /><br />';
				$images_download_html .= $image->post_title.'<br />'.__( 'Total downloads','sunshine' ).': '.$sunshine_download_count;
				$images_download_html .= '</li>';
			}
		}
		if ( $images_download_html ) {
			echo '<ul id="sunshine-gallery-images-downloaded">';
			echo $images_download_html;
			echo '</ul>';
		} else {
			_e( 'No images purchased yet','sunshine' );
		}
	} else {
		_e( 'No images for this gallery yet','sunshine' );
	}
	echo '</td></tr>';

}

/**
 * Allow file extensions for digital downloads
 *
 * @since 1.8
 * @return array
 */
add_filter( 'sunshine_allowed_file_extensions', 'sunshine_digital_download_allowed_file_extensions' );
function sunshine_digital_download_allowed_file_extensions( $extensions ) {
	$download_extensions = array( 'zip','tif','tiff','dng','png','raw','psd','pdf' );
	return array_merge( $extensions, $download_extensions );
}


/**************************
	EMAIL AUTOMATION
	Add trigger
**************************/
add_filter( 'sunshine_email_triggers', 'sunshine_digital_download_email_triggers' );
function sunshine_digital_download_email_triggers( $triggers ) {
	$triggers['free_image_download'] = __( 'After user downloads free image', 'sunshine' );
	$triggers['free_gallery_download'] = __( 'After user downloads free gallery', 'sunshine' );
	return $triggers;
}

add_action( 'admin_notices', 'sunshine_digital_download_htaccess' );
function sunshine_digital_download_htaccess() {
	$upload_dir = wp_upload_dir();
	$file = $upload_dir['basedir'] . '/sunshine/.htaccess';
	if ( !file_exists( $file ) ) {
		$url = get_bloginfo( 'url' );
		$url = str_replace( array( 'http://', 'https://', 'www.' ), '', $url );
 		$data = "RewriteEngine on
RewriteCond %{HTTP_REFERER} !^http(s)?://(www\.)?$url [NC]
RewriteCond %{REQUEST_FILENAME} !-\d+x\d+\.jpg
RewriteRule \.(jpg)$ /noimageforyou [NC,R,L]";
		file_put_contents( $file, $data );
		?>
		<div class="updated"><p><?php _e('An <a href="https://en.wikipedia.org/wiki/.htaccess" target="_blank">.htaccess</a> file has been created for your "wp-content/uploads" directory to prevent any direct access to images, protecting your digital download files', 'sunshine'); ?></p></div>
		<?php
	}
}

/**************************
	UPGRADE ROUTINE
	Upgrade to 2.0
**************************/
add_action( 'admin_init', 'sunshine_download_upgrade_notice' );
function sunshine_download_upgrade_notice() {
	global $sunshine;
	$upgrade_version = get_option( 'sunshine_digital_download_upgrade' );
	if ( version_compare( $upgrade_version, '2.0', '<' ) ) {
		add_action( 'admin_notices', 'sunshine_digital_downloads_upgrade_notice_display' );
	}

	// Updated htaccess method
	if ( version_compare( $upgrade_version, '2.1.11', '<' ) ) {
		$upload_dir = wp_upload_dir();
		$file = $upload_dir['basedir'] . '/sunshine/.htaccess';
		if ( $file ) {
			unlink( $file );
		}
		$url = get_bloginfo( 'url' );
		$url = str_replace( array( 'http://', 'https://', 'www.' ), '', $url );
 		$data = "RewriteEngine on
RewriteCond %{HTTP_REFERER} !^http(s)?://(www\.)?$url [NC]
RewriteCond %{REQUEST_FILENAME} !-\d+x\d+\.jpg
RewriteRule \.(jpg)$ /noimageforyou [NC,R,L]";
		file_put_contents( $file, $data );
	}

	// Move .htaccess to Sunshine folder
	if ( version_compare( $upgrade_version, '2.1.14', '<' ) ) {
		$upload_dir = wp_upload_dir();
		$file = $upload_dir['basedir'] . '/.htaccess';
		if ( file_exists( $file ) ) {
			rename( $upload_dir['basedir'] . '/.htaccess', $upload_dir['basedir'] . '/sunshine/.htaccess' );
		}
	}
}

function sunshine_digital_downloads_upgrade_notice_display() {
	$galleries = get_posts( 'post_type=sunshine-gallery&nopaging=true&meta_key=upgraded&meta_compare=NOT EXISTS' );
	if ( !$galleries ) return;
?>
	<div id="message" class="error"><p><?php _e('Sunshine\'s Digital Downloads add-on requires you to run an upgrade process. Please note this may take a while if you have a lot of galleries and images.', 'sunshine'); ?><br /><a href="#" id="start-digital-download-upgrade"><?php _e( 'Click here to start', 'sunshine'); ?></a></p></div>
	<?php
	foreach ( $galleries as $gallery ) {
		$galleries_array[] = $gallery->ID;
	}
	?>
	<script>
	jQuery(document).ready(function($) {
		var total = <?php echo count( $galleries_array ); ?>;
		var processed = 0;
		var galleries = <?php echo json_encode( $galleries_array ); ?>;
		function sunshine_digital_download_upgrade_gallery( gallery_id ) {
			success = true;
			var data = {
				'action': 'sunshine_digital_download_upgrade_gallery',
				'gallery': gallery_id
			};
			$.post(ajaxurl, data, function(response) {
				var obj = $.parseJSON( response );
				processed++;
				$('#galleries-processed').html(processed);
				if ( obj.error ) {
					$('#message').addClass('error');
					$('#message p').html( obj.error );
					success = false;
				}
			});
			return success;
		}
		$('#start-digital-download-upgrade').click(function(){
			var success = true;
			$(this).hide();
			$('#message p').append('<strong><span id="galleries-processed">0</span> / ' + total + '</strong>');
			for ( i = 0; i < total; i++ ) {
				success = sunshine_digital_download_upgrade_gallery( galleries[ i ] );
				if ( !success )
					break;
				if ( ( i + 1 ) == total ) {
					var data = {
						'action': 'sunshine_digital_download_upgrade_complete'
					};
					$.post(ajaxurl, data, function(response) {
						$('#message').removeClass('error').addClass('updated');
						$('#message p').html('<?php _e( 'Galleries successfully upgraded!', 'sunshine' ); ?>');
					});
				}
			}
		});
	});
	</script>
<?php
}

add_action( 'wp_ajax_sunshine_digital_download_upgrade_gallery', 'sunshine_digital_download_upgrade_gallery' );
function sunshine_digital_download_upgrade_gallery() {

	set_time_limit(0);
	error_reporting(0);

	if ( !isset( $_POST['gallery'] ) ) {
		$result['error'] = __( 'No gallery ID', 'sunshine' );
	} else {
		$gallery_id = (int) $_POST['gallery'];
		$dir = get_post_meta( $gallery_id, 'sunshine_gallery_images_download_directory', true );
		$upgraded = get_post_meta( $gallery_id, 'upgraded', true );
		if ( $dir && $upgraded < SUNSHINE_DIGITAL_DOWNLOADS_VERSION ) {
			$upload_dir = wp_upload_dir();
			$images = get_children( array(
				'post_parent' => $gallery_id,
				'post_type' => 'attachment',
				'post_mime_type' => 'image'
			) );
			foreach ( $images as $image ) {
				$file_name = get_post_meta( $image->ID, 'sunshine_file_name', true );
				$original_lowres_file = get_attached_file( $image->ID );
				$download_directory_file = $upload_dir['basedir'] . '/sunshine/' . $dir . '/' . $file_name;

				// Delete current low res file
				if ( file_exists( $original_lowres_file ) ) {
					@unlink( $original_lowres_file );
				}

				// Move high res file over to former image spot
				if ( file_exists( $download_directory_file ) ) {
					rename( $download_directory_file, $original_lowres_file );
				}

				// Move any other file types as well
				$extensions = sunshine_allowed_file_extensions();
				$base_file_name = basename( $file_name, '.jpg' );
				$base_file_path = str_replace( $file_name, '', $download_directory_file );
				foreach ( $extensions as $extension ) {
					$extra_file = $base_file_path . $base_file_name . '.' . $extension;
					if ( file_exists( $extra_file ) ) {
						rename( $extra_file, dirname( $original_lowres_file ) . '/' . basename( $extra_file ) );
					}
				}

				// Regenerate image meta data which regenerates thumbnails
				$metadata = wp_generate_attachment_metadata( $image->ID, $original_lowres_file );
				wp_update_attachment_metadata($image->ID, $metadata);
			}
			update_post_meta( $gallery_id, 'upgraded', SUNSHINE_DIGITAL_DOWNLOADS_VERSION );
			@rmdir( $base_file_path );
		}
	}

	echo json_encode( $result );
	exit;
}

add_filter( 'intermediate_image_sizes', 'sunshine_digital_download_upgrade_image_sizes', 9999 );
function sunshine_digital_download_upgrade_image_sizes( $image_sizes ) {
	global $post;
	if ( isset( $_POST['action'] ) && $_POST['action'] == 'sunshine_digital_download_upgrade_gallery' ) {
		unset( $image_sizes );
		$image_sizes[] = 'sunshine-thumbnail';
		$image_sizes[] = 'sunshine-lowres';
	}
	return $image_sizes;
}

add_action( 'wp_ajax_sunshine_digital_download_upgrade_complete', 'sunshine_digital_download_upgrade_complete' );
function sunshine_digital_download_upgrade_complete() {
	update_option( 'sunshine_digital_download_upgrade', SUNSHINE_DIGITAL_DOWNLOADS_VERSION );
	exit;
}

function sunshine_make_intermediate_download_size( $attachment_id, $size, $width, $height, $crop = false ) {
    $path = get_attached_file( $attachment_id );
	$src = wp_get_attachment_image_src( $attachment_id, 'full' );
	$constrained_dimensions = wp_constrain_dimensions( $src[1], $src[2], $width, $height );

	// Get upload directory info
    $upload_info = wp_upload_dir();
    $upload_dir  = $upload_info['basedir'];
    $upload_url  = $upload_info['baseurl'];

    // Get file path info
    $path_info = pathinfo( $path );
    $ext       = $path_info['extension'];
    $rel_path  = str_replace( array( $upload_dir, ".$ext" ), '', $path );
    $suffix    = "{$constrained_dimensions[0]}x{$constrained_dimensions[1]}";
    $dest_path = "{$upload_dir}{$rel_path}-{$suffix}.{$ext}";

	$meta = wp_get_attachment_metadata( $attachment_id );
	$filetype = wp_check_filetype( $dest_path );
	$meta['sizes'][ $size ] = array(
		'file' => basename( $dest_path ),
		'width' => $constrained_dimensions[0],
		'height' => $constrained_dimensions[1],
		'mime-type' => $filetype['type']
	);
	wp_update_attachment_metadata( $attachment_id, $meta );

   	// If file exists just return path
    if ( file_exists( $dest_path ) ) {
        return $dest_path;
	}
    // Generate thumbnail
    if ( image_make_intermediate_size( $path, $width, $height, $crop ) ) {
        return $dest_path;
	}

    // Fallback to full size
    return $path;
}

add_action( 'init', 'sunshine_download_noimageforyou' );
function sunshine_download_noimageforyou() {
	if ( isset( $_GET['noimageforyou'] ) ) {
		wp_die( __( 'You do not have permission to access this image', 'sunshine' ), __( 'Permission denied', 'sunshine'), array( 'response' => 403 ) );
	}
}

function sunshine_get_http_response_code( $url ) {
    $headers = get_headers( $url );
    return intval( substr( $headers[0], 9, 3) );
}

function sunshine_add_image_to_zip( $zip, $image_id, $size = '' ) {

	$file_path = get_attached_file( $image_id );

	// AWS S3 paths start with "s3"
	if ( substr( $file_path, 0, 2 ) == 's3' && function_exists( 'as3cf_get_secure_attachment_url' ) ) {
		$remote_url = as3cf_get_secure_attachment_url( $image_id, 900, $size );
		$remote_url = preg_replace('/\?.*/', '', $remote_url );
		$file_name = basename( $remote_url );
		$file_resource = fopen( $remote_url, "r" );
		$zip->addLargeFile( $file_resource, $file_name );
		fclose( $file_resource );
	} else { // Otherwise assume local
		$upload_dir = wp_upload_dir();
		if ( $size ) {
			$image = image_get_intermediate_size( $image_id, $size );
			if ( $image && $image['path'] != $file_path ) {
				if ( file_exists( $upload_dir['basedir'] . '/'. $image['path'] ) ) {
					$file_path = $upload_dir['basedir'] . '/'. $image['path'];
				}
			}
		}
		// Some reason we don't have a valid file, BAIL!
		if ( !file_exists( $file_path ) ) {
			return;
		}
		$file_name = basename( $file_path );
		$zip->addLargeFile( $file_path, $file_name );
	}

}
?>

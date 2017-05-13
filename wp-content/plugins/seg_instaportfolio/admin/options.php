<?php

$this->my_opts = array(

	array(

		'title' => 'Options',
		'icon' => 'config.png',
		'tabs' => array(

			///////////////////////////////////////
			///// GENERAL OPTIONS
			///////////////////////////////////////
			array(

				'title' => 'General Options',
				'class' => 'general-options',
				'desc' => '',
				'fields' => array(
					array(
						'type' => 'text',
						'name' => $this->prefix . 'access_token',
						'title' => 'Access Token',
						'desc' => 'Paste your Access Token here. Generate your Instagram Access Token <a href="http://instagramwordpress.rafsegat.com/docs/get-access-token/" target="_blank">here</a>.',
						'shortcode' => '',
						'default' => '',
					),
					array(
						'type' => 'select',
						'name' => $this->prefix . 'instagram_mode',
						'title' => 'Instagram Mode',
						'options' => array('User', 'Hashtag', 'Location'),
						'desc' => 'This feature allows you to choose between user, hashtag, location or multiusers to display your photos. See how it works <a href="http://instagramwordpress.rafsegat.com/docs/instagram-mode-usernamehashtag/" target="_blank">here</a>.',
						'default' => 'User',
					),
					array(
						'type' => 'text',
						'name' => $this->prefix . 'username_instagram',
						'title' => 'Username/Hashtag',
						'desc' => 'Choose user/hashtag/location/multiuser on Instagram to display your photos. See how it works <a href="http://instagramwordpress.rafsegat.com/docs/instagram-mode-usernamehashtag/" target="_blank">here</a>. If you do not know how to find Location ID from Foursquare, follow these <a href="http://instagramwordpress.rafsegat.com/docs/find-location-id/" target="_blank">instructions</a>.',
						'shortcode' => '[seg_instaportfolio user="barackobama"] or <br />[seg_instaportfolio hashtag="#thailand"]',
						'default' => '',
					),
					array(
						'type' => 'text',
						'name' => $this->prefix . 'filter_users',
						'title' => 'Filter User(s)',
						'desc' => 'This feature will not show these users on your gallery feed. Fill the users separated by comma. See how it works <a href="http://instagramwordpress.rafsegat.com/docs/filter/" target="_blank">here</a>. ',
						'shortcode' => '[seg_instaportfolio filter_users="chelseafc,manchesterunited,fcbarcelona,realmadrid"]',
						'default' => '',
					),
					array(
						'type' => 'checkbox',
						'name' => $this->prefix . 'load_more',
						'title' => 'Load More',
						'desc' => 'Load More button will load more photos to you portfolio. See how it works <a href="http://instagramwordpress.rafsegat.com/docs/load-more/" target="_blank">here</a>.',
						'shortcode' => '[seg_instaportfolio load_more="on/off"]',
						'default' => 'off',
					),
					array(
						'type' => 'checkbox',
						'name' => $this->prefix . 'lightbox',
						'title' => 'Lightbox',
						'desc' => 'Lightbox will open your photos in a lightbox. See how it works <a href="http://instagramwordpress.rafsegat.com/docs/lightbox/" target="_blank">here</a>.',
						'shortcode' => '[seg_instaportfolio lightbox="on/off"]',
						'default' => 'on',
					),
					array(
						'type' => 'checkbox',
						'name' => $this->prefix . 'display_social_icons',
						'title' => 'Display Social Icons',
						'desc' => 'Choose if display Social Icons.',
						'shortcode' => '[seg_instaportfolio display_social_icons="on/off"]',
						'default' => 'on',
					),
					array(
						'type' => 'checkbox',
						'name' => $this->prefix . 'responsive',
						'title' => 'Responsive',
						'desc' => 'Choose if is responsive or not. Resposive will display on mobile 1 column and tablet 3 columns.',
						'shortcode' => '[seg_instaportfolio responsive="on/off"]',
						'default' => 'on',
					)
				),
			), // END GENERAL OPTIONS
			

			///////////////////////////////////////
			///// LAYOUT OPTIONS
			///////////////////////////////////////
			array(

				'title' => 'Layout Options',
				'class' => 'layout-options',
				'desc' => '',
				'fields' => array(
					array(
						'type' => 'text_number',
						'name' => $this->prefix . 'width',
						'title' => 'Width',
						'desc' => 'Choose the appropriate width size of your portfolio. Eg. 700px, 50&#37;... Default size is 100&#37;. See how it works <a href="http://instagramwordpress.rafsegat.com/docs/number-photos-columns-using-width/" target="_blank">here</a>.',
						'shortcode' => '[seg_instaportfolio width="700px/50&#37;"]',
						'default' => '100%',
					),
					array(
						'type' => 'text_number',
						'name' => $this->prefix . 'number_photos',
						'title' => 'Number Photos',
						'desc' => 'Choose the appropriate number of photos to display in your portfolio. The default setting is 9. If this number is bigger than 33 automatically will add a Load More button. See how it works <a href="http://instagramwordpress.rafsegat.com/docs/number-photos-columns-using-width/" target="_blank">here</a>.',
						'shortcode' => '[seg_instaportfolio number_photos="12"]',
						'default' => '9',
					),
					array(
						'type' => 'text_number',
						'name' => $this->prefix . 'height',
						'title' => 'Height',
						'desc' => 'Choose the appropriate height for your photos. The default setting is 350px. See how it works <a href="http://instagramwordpress.rafsegat.com/docs/number-photos-columns-using-width/" target="_blank">here</a>.',
						'shortcode' => '[seg_instaportfolio height="150px"]',
						'default' => '350',
					),
					array(
						'type' => 'checkbox',
						'name' => $this->prefix . 'fixed_height',
						'title' => 'Fixed Height',
						'desc' => 'Decide if you want to have a fixed height. This mean that all the rows will be exactly with the specified rowHeight. See how it works <a href="http://instagramwordpress.rafsegat.com/docs/fixed-height/" target="_blank">here</a>.',
						'shortcode' => '[seg_instaportfolio fixed_height="on/off"]',
						'default' => 'on',
					),
					array(
						'type' => 'select',
						'name' => $this->prefix . 'last_row',
						'title' => 'Last Row',
						'options' => array('Justify', 'Left', 'Right', 'Center', 'Hide'),
						'desc' => 'Select if the last row is justified, no justified or hide it. By default, using "nojustify", the last row images are aligned to the left. See how it works <a href="http://instagramwordpress.rafsegat.com/docs/last-row/" target="_blank">here</a>.',
						'shortcode' => '[seg_instaportfolio last_row="justify/left/right/center/hide"]',
						'default' => 'Justify',
					),
					array(
						'type' => 'text_number',
						'name' => $this->prefix . 'padding',
						'title' => 'Padding Around Photos',
						'desc' => 'Set the padding size around each photo. Eg. 5px, 6px, 10px. See how it works <a href="http://instagramwordpress.rafsegat.com/docs/padding-around-photos/" target="_blank">here</a>.',
						'shortcode' => '[seg_instaportfolio padding="10px"]',
						'default' => '10',
					),
					array(
						'type' => 'checkbox',
						'name' => $this->prefix . 'shadow',
						'title' => 'Shadow',
						'desc' => 'Check if display a beautiful shadow in each image. See how it works <a href="http://instagramwordpress.rafsegat.com/docs/shadow/" target="_blank">here</a>.',
						'shortcode' => '[seg_instaportfolio shadow="on/off"]',
						'default' => 'on',
					),
					array(
						'type' => 'colour',
						'name' => $this->prefix . 'loading_color',
						'title' => 'Loading Spinner Color',
						'desc' => 'Choose the appropriate loading spinner colour.',
						'shortcode' => '[seg_instaportfolio loading_color="#fff"]',
						'default' => '#333',
					),
				),
			),

			///////////////////////////////////////
			///// HEADER OPTIONS
			///////////////////////////////////////
			array(

				'title' => 'Header Options',
				'class' => 'header-options',
				'desc' => '',
				'fields' => array(
					array(
						'type' => 'checkbox',
						'name' => $this->prefix . 'display_header',
						'title' => 'Header',
						//'options' => array('No Header', 'Classic', 'Fast'),
						'desc' => 'Choose if display a header bar or not. See examples <a href="http://instagramwordpress.rafsegat.com/docs/header/" target="_blank">here</a>.',
						'shortcode' => '[seg_instaportfolio display_header="on/off"]',
						'default' => 'off',
					),
					array(
						'type' => 'colour',
						'name' => $this->prefix . 'header_background',
						'title' => 'Background Colour',
						'desc' => 'Choose the appropriate header background colour. See how it works <a href="http://instagramwordpress.rafsegat.com/docs/header/" target="_blank">here</a>.',
						'shortcode' => '[seg_instaportfolio header_background="#fff"]',
						'default' => '',
					),
					array(
						'type' => 'colour',
						'name' => $this->prefix . 'header_panel_button_colour',
						'title' => 'Header Panel/Button Colour',
						'desc' => 'Choose the appropriate header panel and button colour. See how it works <a href="http://instagramwordpress.rafsegat.com/docs/header/" target="_blank">here</a>.',
						'shortcode' => '[seg_instaportfolio header_panel_button_colour="#285989"]',
						'default' => '#285989',
					),
					array(
						'type' => 'colour',
						'name' => $this->prefix . 'header_text_colour',
						'title' => 'Header Text Colour',
						'desc' => 'Choose the appropriate header text colour. This option will affect all header texts. See how it works <a href="http://instagramwordpress.rafsegat.com/docs/header/" target="_blank">here</a>.',
						'shortcode' => '[seg_instaportfolio header_text_colour="#9B30FF"]',
						'default' => '',
					),
				),
			),

			//////////////////////////////////////////
			///// PHOTO OPTIONS
			/////////////////////////////////////////
			array(

				'title' => 'Photo Options',
				'class' => 'photo-options',
				'desc' => '',
				'fields' => array(
					array(
						'type' => 'select',
						'name' => $this->prefix . 'photo_effect',
						'title' => 'Photo Filter Effects',
						'options' => array('Normal', 'Grayscale', 'Sepia', 'Saturate', 'Hue-rotate', 'Invert', 'Brightness', 'Contrast', 'Blur'),
						'desc' => 'Choose the appropriate photo effect. See examples of all the amazing effects <a href="http://instagramwordpress.rafsegat.com/docs/photo-filter-effects/" target="_blank">here</a>.',
						'shortcode' => '[seg_instaportfolio photo_effect="normal/.../blur"]',
						'default' => 'normal',
					),
					array(
						'type' => 'colour',
						'name' => $this->prefix . 'photo_background',
						'title' => 'Photo Background',
						'desc' => 'Choose the appropriate background colour of each photo in your portfolio. See how it works <a href="http://instagramwordpress.rafsegat.com/docs/photo-background/" target="_blank">here</a>.',
						'shortcode' => '[seg_instaportfolio photo_background="#9B30FF"]',
						'default' => '',
					),
					array(
						'type' => 'colour',
						'name' => $this->prefix . 'photo_background2',
						'title' => 'Photo Background 2',
						'desc' => 'Choose the appropriate background colour of each photo in your portfolio. This color will generate a gradient color. See how it works <a href="http://instagramwordpress.rafsegat.com/docs/photo-background/" target="_blank">here</a>.',
						'shortcode' => '[seg_instaportfolio photo_background2="white"]',
						'default' => '',
					),
					array(
						'type' => 'opacity',
						'name' => $this->prefix . 'photo_opacity',
						'title' => 'Photo Opacity',
						'desc' => 'Set the opacity level of each photo in your portfolio. See how it works <a href="http://instagramwordpress.rafsegat.com/docs/photo-opacity/" target="_blank">here</a>. ',
						'shortcode' => '[seg_instaportfolio photo_opacity="6"]',
						'default' => '3',
					),
					array(

						'type' => 'select',
						'name' => $this->prefix . 'photo_shape',
						'title' => 'Photo Shape',
						'options' => array('Square', 'Circle'),
						'desc' => 'Choose the shape of the photo. See how it works <a href="http://instagramwordpress.rafsegat.com/docs/photo-shape/" target="_blank">here</a>.',
						'shortcode' => '[seg_instaportfolio photo_shape="square/circle"]',
						'default' => 'Square',

					),

				),

			), // END PHOTO OPTIONS

			//////////////////////////////////////////
			///// HOVER OPTIONS
			/////////////////////////////////////////
			array(

				'title' => 'Hover Options',
				'class' => 'hover-options',
				'desc' => '',
				'fields' => array(

					array(

						'type' => 'select',
						'name' => $this->prefix . 'hover_effect',
						'title' => 'Hover Effects',
						'options' => $this->effects_hover,
						'desc' => 'Choose the appropriate effect to be enabled when hovering over the pics. See all the amazing hover effects <a href="http://instagramwordpress.rafsegat.com/docs/hover-effects/" target="_blank">here</a>.',
						'shortcode' => '[seg_instaportfolio hover_effect="flinders/.../angkor"]',
						'default' => 'Flinders',

					),
					array(
						'type' => 'colour',
						'name' => $this->prefix . 'hover_background',
						'title' => 'Photo Background',
						'desc' => 'Choose the appropriate background colour when hovering over each photo. See how it works <a href="http://instagramwordpress.rafsegat.com/docs/hover-photo-background/" target="_blank">here</a>.',
						'shortcode' => '[seg_instaportfolio hover_background="#c98620"]',
						'default' => '#c98620',
					),
					array(
						'type' => 'colour',
						'name' => $this->prefix . 'hover_background2',
						'title' => 'Photo Background 2',
						'desc' => 'Set the second color background for each image when hover the item. This generate a gradient effect. Leave it blank if you do not want gradient color. See how it works <a href="http://instagramwordpress.rafsegat.com/docs/hover-photo-background/" target="_blank">here</a>.',
						'shortcode' => '[seg_instaportfolio hover_background2="#e73827"]',
						'default' => '#e73827',
					),
					array(

						'type' => 'opacity',
						'name' => $this->prefix . 'hover_opacity',
						'title' => 'Photo Opacity',
						'desc' => 'Set the opacity level when hovering over each photo in your portfolio. We recommend level 3. See how it works <a href="http://instagramwordpress.rafsegat.com/docs/hover-photo-opacity/" target="_blank">here</a>. ',
						'shortcode' => '[seg_instaportfolio hover_opacity="6"]',
						'default' => '3',
					),

					array(

						'type' => 'select',
						'name' => $this->prefix . 'hover_photo_filter_effect',
						'title' => 'Hover Photo Filter Effects',
						'options' => array('Normal', 'Grayscale', 'Sepia', 'Saturate', 'Hue-rotate', 'Invert', 'Brightness', 'Contrast', 'Blur'),
						'desc' => 'If appropriate, choose the photo effect when hovering. See how it works <a href="http://instagramwordpress.rafsegat.com/docs/hover-photo-filter-effects/" target="_blank">here</a>.',
						'shortcode' => '[seg_instaportfolio hover_photo_filter_effect="normal/.../blur"]',
						'default' => 'normal',

					),

					array(

						'type' => 'checkbox',
						'name' => $this->prefix . 'display_icon_instagram',
						'title' => 'Display Instagram Icon',
						'desc' => ' If the Hover effect chosen has an Instagram icon, you can choose whether you want to display it or not. See how it works <a href="http://instagramwordpress.rafsegat.com/docs/instagram-icon/" target="_blank">here</a>.',
						'shortcode' => '[seg_instaportfolio display_icon_instagram="on/off"]',
						'default' => 'on',

					),

								array(

									'type' => 'colour',
									'name' => $this->prefix . 'icon_instagram_colour',
									'title' => 'Instagram Icon Colour',
									'desc' => 'Choose your Instagram Icon colour. See some examples <a href="http://instagramwordpress.rafsegat.com/docs/instagram-icon/" target="_blank">here</a>.',
									'class' => 'group-sub-options ' . $this->prefix . 'display_icon_instagram_child',
									'shortcode' => '[seg_instaportfolio icon_instagram_colour="white/#fff"]',
									'default' => '',

								),

								array(

									'type' => 'select',
									'name' => $this->prefix . 'icon_instagram_size',
									'title' => 'Icon Instagram Size',
									'options' => array('Small', 'Medium', 'Large'),
									'desc' => 'Choose the appropriate Instagram icon size. See some examples <a href="http://instagramwordpress.rafsegat.com/docs/instagram-icon/" target="_blank">here</a>.',
									'class' => 'group-sub-options ' . $this->prefix . 'display_photo_likes_child',
									'shortcode' => '[seg_instaportfolio icon_instagram_size="small/medium/large"]',
									'default' => 'Medium',

								),


					array(

						'type' => 'checkbox',
						'name' => $this->prefix . 'display_animated_lines',
						'title' => 'Display Animated Lines',
						'desc' => 'If the Hover Effect chosen has animated lines, you can choose whether you want to display them or not. See how it works <a href="http://instagramwordpress.rafsegat.com/docs/animated-lines/" target="_blank">here</a>.',
						'shortcode' => '[seg_instaportfolio display_animated_lines="on/off"]',
						'default' => 'on',

					),

								array(

									'type' => 'colour',
									'name' => $this->prefix . 'animated_lines_colour',
									'title' => 'Animated Lines Colour',
									'desc' => 'Choose colour for your Animated Lines. Check some examples <a href="http://instagramwordpress.rafsegat.com/docs/animated-lines/" target="_blank">here</a>.',
									'class' => 'group-sub-options ' . $this->prefix . 'display_animated_lines_child',
									'shortcode' => '[seg_instaportfolio animated_lines_colour="#2b052b/green"]',
									'default' => '',

								),


					array(

						'type' => 'checkbox',
						'name' => $this->prefix . 'display_photo_description',
						'title' => 'Display Photo Description',
						'desc' => 'If the Hover Effect choosen has description you can check if display or not. See how it works <a href="http://instagramwordpress.rafsegat.com/docs/instagram-description/" target="_blank">here</a>.',
						'shortcode' => '[seg_instaportfolio display_photo_description="on/off"]',
						'default' => 'on',
					),

								array(

									'type' => 'colour',
									'name' => $this->prefix . 'photo_description_colour',
									'title' => 'Photo Description Colour',
									'desc' => 'Choose the colour for your Photo Description. Check some examples <a href="http://instagramwordpress.rafsegat.com/docs/instagram-description/" target="_blank">here</a>.',
									'class' => 'group-sub-options ' . $this->prefix . 'display_photo_description_child',
									'shortcode' => '[seg_instaportfolio photo_description_colour="#2c052b/red"]',
									'default' => '',

								),

								array(

									'type' => 'select',
									'name' => $this->prefix . 'photo_description_size',
									'title' => 'Photo Description Size',
									'options' => array('Small', 'Medium', 'Large'),
									'desc' => 'Choose the size for your Photo Description. Check some examples <a href="http://instagramwordpress.rafsegat.com/docs/instagram-description/" target="_blank">here</a>.',
									'class' => 'group-sub-options ' . $this->prefix . 'display_photo_likes_child',
									'shortcode' => '[seg_instaportfolio photo_description_size="small/medium/large"]',
									'default' => 'Medium',

								),

								array(

									'type' => 'text',
									'name' => $this->prefix . 'photo_description_limit',
									'title' => 'Photo Description Character Limits',
									'desc' => 'Choose number characters limits. E.g. 10, 22. Check some examples <a href="http://instagramwordpress.rafsegat.com/docs/instagram-description/" target="_blank">here</a>.',
									'class' => 'group-sub-options ' . $this->prefix . 'display_photo_description_child',
									'shortcode' => '[seg_instaportfolio photo_description_limit="15"]',
									'default' => '',

								),

					array(

						'type' => 'checkbox',
						'name' => $this->prefix . 'display_photo_likes',
						'title' => 'Display Photo Likes',
						'desc' => 'If the Hover Effect chosen has a quantity of likes, you can choose whether you want to display it or not. See how it works <a href="http://instagramwordpress.rafsegat.com/docs/photo-likes/" target="_blank">here</a>.',
						'shortcode' => '[seg_instaportfolio display_photo_likes="on/off"]',
						'default' => 'on',

					),

								array(

									'type' => 'colour',
									'name' => $this->prefix . 'photo_likes_colour',
									'title' => 'Photo Likes Colour',
									'desc' => 'Choose the appropriate photo likes colour. Check some examples <a href="http://instagramwordpress.rafsegat.com/docs/photo-likes/" target="_blank">here</a>.',
									'class' => 'group-sub-options ' . $this->prefix . 'display_photo_likes_child',
									'shortcode' => '[seg_instaportfolio photo_likes_colour="#c404c4/pink"]',
									'default' => '',

								),

								array(

									'type' => 'select',
									'name' => $this->prefix . 'photo_likes_size',
									'title' => 'Photo Likes Size',
									'options' => array('Small', 'Medium', 'Large'),
									'desc' => 'Choose the appropriate photo likes size. Check some examples <a href="http://instagramwordpress.rafsegat.com/docs/photo-likes/" target="_blank">here</a>.',
									'class' => 'group-sub-options ' . $this->prefix . 'display_photo_likes_child',
									'shortcode' => '[seg_instaportfolio photo_likes_size="small/medium/large"]',
									'default' => 'Medium',

								),

				),

			), // END HOVER OPTIONS


			//////////////////////////////////////////
			///// SCROLL EFFECTS OPTIONS
			/////////////////////////////////////////
			array(

				'title' => 'Scroll Options',
				'class' => 'scroll-options',
				'desc' => '',
				'fields' => array(
					array(
						'type' => 'select',
						'name' => $this->prefix . 'scroll_effect',
						'title' => 'Scroll Effects',
						'options' => $this->effects_scroll,
						'desc' => 'Choose the appropriate photo effect. It is triggered when the portfolio is visible on the scroll screen. See how it works <a href="http://instagramwordpress.rafsegat.com/docs/scroll-photo-effect/" target="_blank">here</a>.',
						'shortcode' => '[seg_instaportfolio scroll_effect="fade/scale"]',
						'default' => 'Scale',
					),
					array(
						'type' => 'text_number',
						'name' => $this->prefix . 'scroll_delay',
						'title' => 'Delay Effect',
						'desc' => 'Choose the delay effect that best fits your scroll effect. The default setting is 100.',
						'shortcode' => '[seg_instaportfolio scroll_delay="250"]',
						'default' => '100',
					),
				),
			), // END SCROLL OPTIONS

			//////////////////////////////////////////
			///// CUSTOM OPTIONS
			/////////////////////////////////////////
			array(

				'title' => 'Custom Options',
				'class' => 'custom-options',
				'desc' => '',
				'fields' => array(
					array(
						'type' => 'textarea',
						'name' => $this->prefix . 'custom_css',
						'title' => 'Custom CSS',
						'desc' => 'Paste your custom CSS here.',
						'default' => '',
					),
				)
			)
		),

	),

);


?>

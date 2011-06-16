/**
 * Copyright (C) 2011 Bowe Frankema
 *
 * Contact Information:
 *     bowromir@gmail.com
 *     http://bp-tricks.com
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
(function($){
	$(document).ready(function() {

		// set pattern element height to height of document
		jQuery('#pattern').css({
			'height': jQuery(document).height() + 'px'
		});

		// add grid class to content for buddypress and bbpress combatibilty
		jQuery('#content').addClass('grid_8');

	/*-----------------------------------------------------------------------------------*/
	/*	Superfish Settings - http://users.tpg.com.au/j_birch/plugins/superfish/
	/*-----------------------------------------------------------------------------------*/

		// main navigation setup (superfish)
		if(jQuery().superfish) {

			jQuery('#base-menu ul.sf-menu').superfish({
				delay: 200,
				animation: {
					opacity: 'show',
					height: 'show'
				},
				speed: 'fast',
				dropShadows: false
			});

			jQuery('#base-menu li li a').hover(
				function() {
					jQuery(this)
						.find('span')
						.not('span.sf-sub-indicator')
						.stop()
						.animate({
							paddingLeft: 5
						},
						200,
						'jswing'
					);
				},
				function() {
					jQuery(this)
						.find('span')
						.not('span.sf-sub-indicator')
						.stop()
						.animate({
							paddingLeft: 0
						},
						200,
						'jswing'
					);
				}
			);

		}

		// buddypress avatars, post thumbnails support and menus hover effect
		function infinity_overlay()
		{
			jQuery('.wp-post-image,img.avatar, ul.item-list li img.avatar, img#header-logo-image, #primary-nav li a span, a.button-callout, #sidebar a img').hover( function() {
				jQuery(this).stop().animate({opacity : 0.7}, 200);
			}, function() {
				jQuery(this).stop().animate({opacity : 1}, 200);
			});

			jQuery('.plus').hover( function() {
				jQuery(this).parent('.post-thumb').find('img').stop().animate({opacity : 0.8}, 200);
			}, function() {
				jQuery(this).parent('.post-thumb').find('img').stop().animate({opacity : 1}, 200);
			});
		}
		infinity_overlay();
		
	});
})(jQuery);
<?php
/**
 * Primary Nav Walker.
 *
 * @package    Theme_Package
 * @subpackage Theme_Package/Includes/Classes
 * @author     Theme_Author <Theme_Author_Email>
 * @copyright  Copyright (c) 2018, Theme_Author
 * @license    GNU General Public License v2 or later
 * @version    1.0.0
 */

namespace Theme_Package\Includes\Classes;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

if ( ! class_exists( __NAMESPACE__ . '\\Primary_Nav_Walker' ) ) {

	/**
	 * Primary Nav Walker.
	 *
	 * @author Theme_Author
	 * @since  1.0.0
	 */
	class Primary_Nav_Walker extends \Walker_Nav_Menu {

		/**
		 * Starts the list before the elements are added.
		 *
		 * @author Theme_Author
		 * @since  1.0.0
		 *
		 * @see Walker::start_lvl()
		 *
		 * @param string   $output Used to append additional content (passed by reference).
		 * @param int      $depth  Depth of menu item. Used for padding.
		 * @param stdClass $args   An object of wp_nav_menu() arguments.
		 */
		public function start_lvl( &$output, $depth = 0, $args = array() ) {
			if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
				$t = '';
				$n = '';
			} else {
				$t = "\t";
				$n = "\n";
			}
			$indent        = str_repeat( $t, $depth );
			$display_depth = ( $depth + 1 );
			$classes       = array(
				( $display_depth >= 2 ) ? 'menu__sub-sub' : 'menu__sub',
				'menu__depth-' . $display_depth,
			);
			$class_names   = 'class="' . implode( ' ', $classes ) . '"';

			$has_sub = ( $display_depth >= 1 ) ? 'role="menu" aria-hidden="true"' : '';

			// Build HTML for output.
			$output .= "\n" . $indent . '<ul ' . $class_names . $has_sub . '>' . "\n";
		}

		/**
		 * Starts the element output.
		 *
		 * @author Theme_Author
		 * @since  1.0.0
		 *
		 * @see Walker::start_el()
		 *
		 * @param string   $output Used to append additional content (passed by reference).
		 * @param WP_Post  $item   Menu item data object.
		 * @param int      $depth  Depth of menu item. Used for padding.
		 * @param stdClass $args   An object of wp_nav_menu() arguments.
		 * @param int      $id     Current item ID.
		 */
		public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
			if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
				$t = '';
				$n = '';
			} else {
				$t = "\t";
				$n = "\n";
			}
			$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';
			$display_depth = ( $depth + 1 );

			$classes = empty( $item->classes ) ? array() : (array) $this->remove_item_classes( $item->classes );

			/**
			 * Filters the arguments for a single nav menu item.
			 *
			 * @since 4.4.0
			 *
			 * @param stdClass $args  An object of wp_nav_menu() arguments.
			 * @param WP_Post  $item  Menu item data object.
			 * @param int      $depth Depth of menu item. Used for padding.
			 */
			$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

			/**
			 * Filters the CSS class(es) applied to a menu item's list item element.
			 *
			 * @since 3.0.0
			 * @since 4.1.0 The `$depth` parameter was added.
			 *
			 * @param array    $classes The CSS classes that are applied to the menu item's `<li>` element.
			 * @param WP_Post  $item    The current menu item.
			 * @param stdClass $args    An object of wp_nav_menu() arguments.
			 * @param int      $depth   Depth of menu item. Used for padding.
			 */
			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

			/**
			 * Filters the ID applied to a menu item's list item element.
			 *
			 * @since 3.0.1
			 * @since 4.1.0 The `$depth` parameter was added.
			 *
			 * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
			 * @param WP_Post  $item    The current menu item.
			 * @param stdClass $args    An object of wp_nav_menu() arguments.
			 * @param int      $depth   Depth of menu item. Used for padding.
			 */
			$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
			$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
			$role = ' role="menuitem"';

			$output .= $indent . '<li' . $id . $class_names . $role . ' >';

			$atts = array();
			$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
			$atts['target'] = ! empty( $item->target ) ? $item->target : '';
			$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
			$atts['href']   = ! empty( $item->url ) ? $item->url : '';

			/**
			 * Filters the HTML attributes applied to a menu item's anchor element.
			 *
			 * @since 3.6.0
			 * @since 4.1.0 The `$depth` parameter was added.
			 *
			 * @param array $atts {
			 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
			 *
			 *     @type string $title  Title attribute.
			 *     @type string $target Target attribute.
			 *     @type string $rel    The rel attribute.
			 *     @type string $href   The href attribute.
			 * }
			 * @param WP_Post  $item  The current menu item.
			 * @param stdClass $args  An object of wp_nav_menu() arguments.
			 * @param int      $depth Depth of menu item. Used for padding.
			 */
			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}

			/** This filter is documented in wp-includes/post-template.php */
			$title = apply_filters( 'the_title', $item->title, $item->ID );

			/**
			 * Filters a menu item's title.
			 *
			 * @since 4.4.0
			 *
			 * @param string   $title The menu item's title.
			 * @param WP_Post  $item  The current menu item.
			 * @param stdClass $args  An object of wp_nav_menu() arguments.
			 * @param int      $depth Depth of menu item. Used for padding.
			 */
			$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

			$item_output = $args->before;
			$item_output .= '<a' . $attributes . '>';
			$item_output .= $args->link_before . $title . $args->link_after;
			$item_output .= '</a>';
			$item_output .= $args->after;

			/**
			 * Filters a menu item's starting output.
			 *
			 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
			 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
			 * no filter for modifying the opening and closing `<li>` for a menu item.
			 *
			 * @since 3.0.0
			 *
			 * @param string   $item_output The menu item's starting HTML output.
			 * @param WP_Post  $item        Menu item data object.
			 * @param int      $depth       Depth of menu item. Used for padding.
			 * @param stdClass $args        An object of wp_nav_menu() arguments.
			 */
			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}

		/**
		 * Remove Item Classes.
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param array $current_classes And array of menu item classes.
		 *
		 * @return array
		 */
		private function remove_item_classes( $current_classes ) {
			$removed_classes = array(
				'menu-item-type-custom',
				'menu-item-object-custom',
				'menu-item-type-post_type',
				'menu-item-object-page',
			);

			foreach ( $removed_classes as $removed_class ) {
				foreach ( array_keys( $current_classes, $removed_class, true ) as $key ) {
					unset( $current_classes[ $key ] );
				}
			}

			$current_classes = $this->replace_item_classes( $current_classes );

			return $current_classes;
		}

		/**
		 * Replace Item Classes.
		 *
		 * @author Jason Witt
		 * @since  1.0.0
		 *
		 * @param array $current_classes And array of menu item classes.
		 *
		 * @return array
		 */
		private function replace_item_classes( $current_classes ) {
			$new_classes     = array();
			$replace_classes = array(
				'menu-item'              => 'menu__item',
				'current-menu-item'      => 'menu__current-item',
				'current_page_item'      => 'menu__current_page_item',
				'menu-item-home'         => 'menu__item-home',
				'menu-item-has-children' => 'menu__item-has-children',
			);

			foreach ( $current_classes as $current_class ) {
				foreach ( $replace_classes as $key => $value ) {
					if ( $key === $current_class ) {
						$new_classes[] = $value;
					}
				}
			}

			return $new_classes;
		}
	}
}

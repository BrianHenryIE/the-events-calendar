<?php
/**
 * The Events Calendar Template Tags
 *
 * Display functions (template-tags) for use in WordPress templates.
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( class_exists( 'Tribe__Events__Main' ) ) {

	/**
	 * Instantiate the template class, preparing a view file for use. If no name is passed, defaults to the class for the current view
	 *
	 * @param bool|string $class Classname you want to instantiate
	 *
	 * @uses Tribe__Events__Templates::instantiate_template_class()
	 **/
	function tribe_initialize_view( $class = false ) {
		do_action( 'tribe_pre_initialize_view' );
		Tribe__Events__Templates::instantiate_template_class( $class );
	}

	/**
	 * Includes a view file, runs hooks around the view
	 *
	 * @param bool|string $view View slug
	 *
	 **/
	function tribe_get_view( $view = false ) {

		do_action( 'tribe_pre_get_view' );

		if ( ! $view ) {
			$template_file = tribe_get_current_template();
		} else {
			$template_file = Tribe__Events__Templates::getTemplateHierarchy( $view, array( 'disable_view_check' => true ) );
		}

		if ( file_exists( $template_file ) ) {
			do_action( 'tribe_events_before_view', $template_file );
			include( $template_file );
			do_action( 'tribe_events_after_view', $template_file );
		}
	}

	/**
	 * Get Event Label Singular.
	 * Returns the singular version of the Event Label.
	 *
	 * Note: the output of this function is not escaped.
	 * You should escape it wherever you use it!
	 *
	 * @since 3.10
	 * @since5.1.6 remove escaping.
	 *
	 * @return string The singular version of the Event Label.
	 */
	function tribe_get_event_label_singular() {
		/**
		 * Allows customization of the singular version of the Event Label.
		 * Note: the output of this filter is not escaped!
		 *
		 * @since 3.10
		 * @since5.1.6 Remove escaping.
		 *
		 * @param string $label The singular version of the Event label, defaults to "Event" (uppercase)
		 */
		return apply_filters(
			'tribe_event_label_singular',
			__( 'Event', 'the-events-calendar' )
		);
	}

	/**
	 * Get Event Label Singular lowercase.
	 * Returns the lowercase singular version of the Event Label.
	 *
	 * Note: the output of this function is not escaped.
	 * You should escape it wherever you use it!
	 *
	 * @since 4.1.1
	 * @since5.1.6 remove escaping.
	 *
	 * @return string The lowercase singular version of the Event Label.
	 */
	function tribe_get_event_label_singular_lowercase() {
		/**
		 * Allows customization of the singular lowercase version of the Event Label.
		 * Note: the output of this filter is not escaped!
		 *
		 * @since 4.1.1
		 * @since5.1.6 Remove escaping.
		 *
		 * @param string $label The singular lowercase version of the Event label, defaults to "event" (lowercase)
		 */
		return apply_filters(
			'tribe_event_label_singular_lowercase',
			__( 'event', 'the-events-calendar' )
		);
	}

	/**
	 * Get Event Label Plural.
	 * Returns the plural version of the Event Label.
	 *
	 * Note: the output of this function is not escaped.
	 * You should escape it wherever you use it!
	 *
	 * @since 3.10
	 * @since5.1.6 remove escaping.
	 *
	 * @return string The plural version of the Event Label.
	 */
	function tribe_get_event_label_plural() {
		/**
		 * Allows customization of the plural version of the Event Label.
		 * Note: the output of this filter is not escaped!
		 *
		 * @since 3.10
		 * @since5.1.6 Remove escaping.
		 *
		 * @param string $label The plural version of the Event label, defaults to "Events" (uppercase)
		 */
		return apply_filters(
			'tribe_event_label_plural',
			__( 'Events', 'the-events-calendar' )
		);
	}

	/**
	 * Get Event Label Plural lowercase.
	 * Returns the plural version of the Event Label.
	 *
	 * Note: the output of this function is not escaped.
	 * You should escape it wherever you use it!
	 *
	 * @since 4.1.1
	 * @since5.1.6 remove escaping.
	 *
	 * @return string The lowercase plural version of the Event Label.
	 */
	function tribe_get_event_label_plural_lowercase() {
		/**
		 * Allows customization of the plural lowercase version of the Event Label.
		 * Note: the output of this filter is not escaped!
		 *
		 * @since 4.1.1
		 * @since5.1.6 Remove escaping.
		 *
		 * @param string $label The plural lowercase version of the Event label, defaults to "events" (lowercase)
		 */
		return apply_filters(
			'tribe_event_label_plural_lowercase',
			__( 'events', 'the-events-calendar' )
		);
	}

	/**
	 * Includes a template part, similar to the WP get template part, but looks
	 * in the correct directories for Tribe Events templates
	 *
	 * @param string      $slug
	 * @param null|string $name
	 * @param array       $data optional array of vars to inject into the template part
	 *
	 * @uses Tribe__Templates::getTemplateHierarchy
	 **/
	function tribe_get_template_part( $slug, $name = null, array $data = null ) {

		// Execute code for this part
		do_action( 'tribe_pre_get_template_part_' . $slug, $slug, $name, $data );
		// Setup possible parts
		$templates = array();
		if ( isset( $name ) ) {
			$templates[] = $slug . '-' . $name . '.php';
		}
		$templates[] = $slug . '.php';

		// Allow template parts to be filtered
		$templates = apply_filters( 'tribe_get_template_part_templates', $templates, $slug, $name );

		// Make any provided variables available in the template's symbol table
		if ( is_array( $data ) ) {
			extract( $data );
		}

		// loop through templates, return first one found.
		foreach ( $templates as $template ) {
			$file = Tribe__Events__Templates::getTemplateHierarchy( $template, array( 'disable_view_check' => true ) );
			$file = apply_filters( 'tribe_get_template_part_path', $file, $template, $slug, $name );
			$file = apply_filters( 'tribe_get_template_part_path_' . $template, $file, $slug, $name );
			if ( file_exists( $file ) ) {
				ob_start();
				do_action( 'tribe_before_get_template_part', $template, $file, $template, $slug, $name );
				include( $file );
				do_action( 'tribe_after_get_template_part', $template, $file, $slug, $name );
				$html = ob_get_clean();
				echo apply_filters( 'tribe_get_template_part_content', $html, $template, $file, $slug, $name );
				break; // We found our template, no need to continue the loop
			}
		}
		do_action( 'tribe_post_get_template_part_' . $slug, $slug, $name, $data );
	}

	/**
	 * Check if the current request is for a tribe view via ajax
	 *
	 * @category Events
	 * @param bool $view
	 * @return bool
	 */
	function tribe_is_ajax_view_request( $view = false ) {
		$is_ajax_view_request = false;
		if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) && ! empty( $_REQUEST['action'] ) ) {
			switch ( $view ) {
				case false:
					$is_ajax_view_request = ( ! empty( $_REQUEST['tribe_event_display'] ) || ! empty( $_REQUEST['eventDate'] ) || ! empty( $_REQUEST['tribe-bar-date'] ) || ! empty( $_REQUEST['tribe_paged'] ) );
					break;
				case 'month' :
					$is_ajax_view_request = ( $_REQUEST['action'] == Tribe__Events__Template__Month::AJAX_HOOK );
					break;
				case 'list' :
					$is_ajax_view_request = ( $_REQUEST['action'] == Tribe__Events__Template__List::AJAX_HOOK );
					break;
				case 'day' :
					$is_ajax_view_request = ( $_REQUEST['action'] == Tribe__Events__Template__Day::AJAX_HOOK );
					break;
			}
		}

		return apply_filters( 'tribe_is_ajax_view_request', $is_ajax_view_request, $view );
	}

	/**
	 * Event Type Test
	 *
	 * Checks type of $postId to determine if it is an Event
	 *
	 * @category Events
	 * @param int $postId (optional)
	 *
	 * @return bool true if this post is an Event post type
	 */
	function tribe_is_event( $postId = null ) {
		/**
		 * Filter: 'tribe_is_event'.
		 *
		 * @param bool $is_event
		 * @param int $postId
		 */
		return apply_filters( 'tribe_is_event', Tribe__Events__Main::instance()->isEvent( $postId ), $postId );
	}

	/**
	 * Get Event
	 *
	 * Queries the events using WordPress get_posts() by setting the post type and sorting by event date.
	 *
	 * @category Events
	 *
	 * @link http://codex.wordpress.org/Template_Tags/get_posts
	 * @link http://codex.wordpress.org/Function_Reference/get_post
	 *
	 * @uses get_posts()
	 *
	 * @param array $args {
	 *		Optional. Array of Query parameters.
	 *
	 *		@type string    $start_date      Minimum start date of the Event.
	 *		@type string    $end_date        Maximum end date of the Event.
	 *		@type string    $eventDate       A specific Event date for the Query.
	 *		@type bool      $hide_upcoming   Hide events that are not on eventDate, internal usage
	 *		@type int       $venue           Select events from a specific Venue
	 *		@type int       $organizer       Select events from a specific Organizer
	 *		@type string    $eventDisplay    How to display the Events, internal usage
	 *
	 *		@see  get_posts()  for more params
	 * }
	 *
	 * @param bool $full Whether to return an array of event posts (default) or the query object
	 *                   to fetch them.
	 *
	 * @return array|WP_Query A list of event posts matching the query arguments or a WP_Query instance
	 *                        if the `$full` argument is set to `true`.
	 */
	function tribe_get_events( $args = array(), $full = false ) {
		if ( empty ( $args['eventDisplay'] ) ) {
			$args['eventDisplay'] = 'custom';
		}

		return apply_filters( 'tribe_get_events', Tribe__Events__Query::getEvents( $args, $full ), $args, $full );
	}

	/**
	 * Returns the current event post object (if there is one) or else null.
	 *
	 * Optionally the post object or ID of an event can be passed in and,
	 * again, the event post object will be returned if possible.
	 *
	 * @category Events
	 * @param $event
	 * @return null|WP_Post
	 */
	function tribe_events_get_event( $event = null ) {
		global $post;

		if ( null === $event ) {
			return $post;
		}

		if ( $event instanceof WP_Post && Tribe__Events__Main::POSTTYPE === get_post_type( $event ) ) {
			return $post;
		}

		if ( is_numeric( $event ) && $event == intval( $event ) ) {
			$event = get_post( $event );

			if ( null !== $event && Tribe__Events__Main::POSTTYPE === get_post_type( $event ) ) {
				return $event;
			}
		}

		return null;
	}

	/**
	 * All Day Event Test
	 *
	 * Returns true if the event is an all day event
	 *
	 * @category Events
	 * @param int $postId (optional)
	 *
	 * @return bool
	 */
	function tribe_event_is_all_day( $postId = null ) {
		$output = Tribe__Date_Utils::is_all_day( tribe_get_event_meta( $postId, '_EventAllDay', true ) );

		return apply_filters( 'tribe_event_is_all_day', $output, $postId );
	}

	/**
	 * Multi-day Event Test
	 *
	 * Returns true if the event spans multiple days
	 *
	 * @category Events
	 * @param int $postId (optional)
	 *
	 * @return bool true if event spans multiple days
	 */
	function tribe_event_is_multiday( $postId = null ) {
		$postId = Tribe__Events__Main::postIdHelper( $postId );
		$start  = tribe_get_start_date( $postId, true, Tribe__Date_Utils::DBDATETIMEFORMAT );
		$end    = tribe_get_end_date( $postId, true, Tribe__Date_Utils::DBDATETIMEFORMAT );

		$end    = strtotime( $end );
		$output = ( $end > strtotime( tribe_end_of_day( $start ) ) );

		return apply_filters( 'tribe_event_is_multiday', $output, $postId, $start, $end );
	}

	/**
	 * Detect if an Event is Past
	 *
	 * Returns true if the current time is past the event end time
	 *
	 * @param null $event
	 *
	 * @return bool
	 */
	function tribe_is_past_event( $event = null ) {

		if ( is_null( $event ) ) {
			global $post;
			$event = $post;
		}

		// Are we using the site wide timezone or the local event timezone?
		$timezone_name = Tribe__Events__Timezones::EVENT_TIMEZONE === Tribe__Events__Timezones::mode()
			? Tribe__Events__Timezones::get_event_timezone_string( $event->ID )
			: Tribe__Events__Timezones::wp_timezone_string();

		$format = 'Y-m-d G:i';
		$end_date = tribe_get_end_date( $event, false, $format );

		// Try to create a a current and end date with the timezone to avoid using the WP timezone if is not the setup case.
		try {
			$timezone = new DateTimeZone( $timezone_name );
			$current  = date_create( 'now', $timezone );
			$end      = date_create( $end_date, $timezone );
		} catch( Exception $exception ) {
			$current = false;
			$end = false;
		}

		// If date_create throws an error or was not created correctly we fallback to the original solution
		if ( false === $current || false === $end ) {
			$gmt_offset = ( get_option( 'gmt_offset' ) >= '0' ) ? ' +' . get_option( 'gmt_offset' ) : ' ' . get_option( 'gmt_offset' );
			$gmt_offset = str_replace( array( '.25', '.5', '.75' ), array( ':15', ':30', ':45' ), $gmt_offset );
			return strtotime( $end_date . $gmt_offset ) < time();
		} else {
			return $current > $end;
		}
	}

	/**
	 * Event Category ID's
	 *
	 * Display the event category ID as a class for events wrapper
	 *
	 * @category Events
	 * @uses wp_get_object_terms()
	 */
	function tribe_get_event_cat_ids( $post_id = 0 ) {
		$post_id = Tribe__Events__Main::postIdHelper( $post_id );

		return wp_list_pluck( (array) get_the_terms( $post_id, Tribe__Events__Main::TAXONOMY ), 'term_id' );
	}

	/**
	 * Event Category slugs
	 *
	 * Display the event category ID as a class for events wrapper
	 *
	 * @category Events
	 * @uses wp_get_object_terms()
	 */
	function tribe_get_event_cat_slugs( $post_id = 0 ) {
		$post_id = Tribe__Events__Main::postIdHelper( $post_id );
		$slugs   = wp_list_pluck( (array) get_the_terms( $post_id, Tribe__Events__Main::TAXONOMY ), 'slug' );

		return apply_filters( 'tribe_get_event_cat_slugs', $slugs, $post_id );
	}

	/**
	 * Single event taxonomy terms
	 *
	 * Get the term list by taxonomy (default categories) for a single event
	 *
	 * @category Events
	 * @param  int   $post_id
	 * @param  array $args
	 *
	 * @return string HTML string of taxonomy terms
	 */
	function tribe_get_event_taxonomy( $post_id = null, $args = array() ) {
		$post_id   = Tribe__Events__Main::postIdHelper( $post_id );
		$tribe_ecp = Tribe__Events__Main::instance();
		$defaults  = array(
			'taxonomy' => $tribe_ecp->get_event_taxonomy(),
			'before'   => '<li>',
			'sep'      => '</li><li>',
			'after'    => '</li>',
		);
		$args      = wp_parse_args( $args, $defaults );
		extract( $args, EXTR_SKIP );
		$taxonomy = get_the_term_list( $post_id, $taxonomy, $before, $sep, $after );

		return apply_filters( 'tribe_get_event_taxonomy', $taxonomy, $post_id, $args );
	}

	/**
	 * Event Categories (Display)
	 *
	 * Display the event categories with display param
	 *
	 * @category Events
	 * @uses     tribe_get_event_taxonomy()
	 * @replaces tribe_meta_event_cats()
	 *
	 * @param int   $post_id
	 * @param array $args
	 *
	 * @return string $html (echo if provided in $args)
	 */
	function tribe_get_event_categories( $post_id = null, $args = array() ) {
		$events_label_singular = tribe_get_event_label_singular();

		$post_id    = is_null( $post_id ) ? get_the_ID() : $post_id;
		$defaults   = array(
			'echo'         => false,
			'label'        => null,
			'label_before' => '<div>',
			'label_after'  => '</div>',
			'wrap_before'  => '<ul class="tribe-event-categories">',
			'wrap_after'   => '</ul>',
		);
		$args       = wp_parse_args( $args, $defaults );
		$categories = tribe_get_event_taxonomy( $post_id, $args );

		// check for the occurrences of links in the returned string
		if ( null === $args[ 'label' ] ) {
			$label = sprintf(
				/* translators: %s is the singular translation of "Event" */
				_nx( '%s Category', '%s Categories', substr_count( $categories, '<a href' ), 'category list label', 'the-events-calendar' ),
				$events_label_singular
			);
		}
		else {
			$label = $args[ 'label' ];
		}

		$html = ! empty( $categories ) ? sprintf(
			'%s%s:%s %s%s%s',
			$args['label_before'],
			$label,
			$args['label_after'],
			$args['wrap_before'],
			$categories,
			$args['wrap_after']
		) : '';
		if ( $args['echo'] ) {
			echo apply_filters( 'tribe_get_event_categories', $html, $post_id, $args, $categories );
		} else {
			return apply_filters( 'tribe_get_event_categories', $html, $post_id, $args, $categories );
		}
	}

	/**
	 * Event Tags (Display)
	 *
	 * Display the event tags
	 *
	 * @category Events
	 * @param null|string $label
	 * @param string      $separator
	 * @param bool        $echo
	 *
	 * @return array
	 * @uses the_terms()
	 */
	function tribe_meta_event_tags( $label = null, $separator = ', ', $echo = true ) {
		if ( ! $label ) {
			$label = esc_html__( 'Tags:', 'the-events-calendar' );
		}

		$tribe_ecp = Tribe__Events__Main::instance();
		$list      = get_the_term_list( get_the_ID(), 'post_tag', '<dt>' . $label . '</dt><dd class="tribe-event-tags">', $separator, '</dd>' );
		$list      = apply_filters( 'tribe_meta_event_tags', $list, $label, $separator, $echo );
		if ( $echo ) {
			echo $list;
		} else {
			return $list;
		}
	}

	/**
	 * Event Post Meta
	 *
	 * Get event post meta.
	 *
	 * @category Events
	 * @param int|null    $postId (optional)
	 * @param string|bool $meta   name of the meta_key
	 * @param bool        $single determines if the results should be a single item or an array of items.
	 *
	 * @return mixed meta value(s)
	 */
	function tribe_get_event_meta( $postId = null, $meta = false, $single = true ) {
		$postId    = Tribe__Events__Main::postIdHelper( $postId );
		$tribe_ecp = Tribe__Events__Main::instance();
		$output    = $tribe_ecp->getEventMeta( $postId, $meta, $single );

		return apply_filters( 'tribe_get_event_meta', $output, $postId, $meta, $single );
	}

	/**
	 * Event Category Name
	 *
	 * Return the current event category name based the url.
	 *
	 * @category Events
	 * @return string Name of the Event Category
	 */
	function tribe_meta_event_category_name() {
		$tribe_ecp   = Tribe__Events__Main::instance();
		$current_cat = get_query_var( 'tribe_events_cat' );
		if ( $current_cat ) {
			$term_info = get_term_by( 'slug', $current_cat, $tribe_ecp->get_event_taxonomy() );

			return apply_filters( 'tribe_meta_event_category_name', $term_info->name, $current_cat, $term_info );
		}
	}

	/**
	 * Current Template
	 *
	 * Get the current page template that we are on
	 *
	 * @category Events
	 * @todo Update the function name to ensure there are no namespace conflicts.
	 * @return string Page template
	 */
	function tribe_get_current_template() {
		return apply_filters( 'tribe_get_current_template', Tribe__Events__Templates::get_current_page_template() );
	}

	/**
	 * Venue Type Test
	 *
	 * Checks type of $postId to determine if it is a Venue
	 *
	 * @category Venues
	 * @param int $postId (optional)
	 *
	 * @return bool True if post type id Venue
	 */
	function tribe_is_venue( $postId = null ) {
		$tribe_ecp = Tribe__Events__Main::instance();

		return apply_filters( 'tribe_is_venue', $tribe_ecp->isVenue( $postId ), $postId );
	}

	/**
	 * Organizer Type Test
	 *
	 * Checks type of $postId to determine if it is a Organizer
	 *
	 * @category Organizers
	 * @param int $postId (optional)
	 *
	 * @return bool True if post type id Venue
	 */
	function tribe_is_organizer( $postId = null ) {
		$tribe_ecp = Tribe__Events__Main::instance();

		return apply_filters( 'tribe_is_organizer', $tribe_ecp->isOrganizer( $postId ), $postId );
	}

	/**
	 * HTML Before Event (Display)
	 *
	 * Display HTML to output before the event template
	 *
	 * @category Events
	 */
	function tribe_events_before_html() {
		$events_label_plural = tribe_get_event_label_plural();

		$before = stripslashes( tribe_get_option( 'tribeEventsBeforeHTML', '' ) );
		$before = wptexturize( $before );
		$before = convert_chars( $before );
		$before = wpautop( $before );
		$before = do_shortcode( stripslashes( shortcode_unautop( $before ) ) );
		$before = '<div class="tribe-events-before-html">' . $before . '</div>';
		$before = $before . '<span class="tribe-events-ajax-loading"><img class="tribe-events-spinner-medium" src="' . esc_url( tribe_events_resource_url( 'images/tribe-loading.gif' ) ) . '" alt="' . sprintf( esc_attr__( 'Loading %s', 'the-events-calendar' ), $events_label_plural ) . '" /></span>';

		echo apply_filters( 'tribe_events_before_html', $before );
	}

	/**
	 * HTML After Event (Display)
	 *
	 * Display HTML to output after the event template
	 *
	 * @category Events
	 */
	function tribe_events_after_html() {
		$after = stripslashes( tribe_get_option( 'tribeEventsAfterHTML', '' ) );
		$after = wptexturize( $after );
		$after = convert_chars( $after );
		$after = wpautop( $after );
		$after = do_shortcode( stripslashes( shortcode_unautop( $after ) ) );
		$after = '<div class="tribe-events-after-html">' . $after . '</div>';

		echo apply_filters( 'tribe_events_after_html', $after );
	}

	/**
	 * Prints out or returns classes on an event wrapper
	 *
	 * @category Events
	 * @param $event |0 post id or object
	 * @param $echo  |true
	 *
	 **/
	function tribe_events_event_classes( $event = 0, $echo = true ) {
		global $post, $wp_query;

		// May be called when the global $post object does not exist - ie during ajax loads of various views
		// ... creating a dummy object allows the method to proceed semi-gracefully (interim measure only)

		//If $post object doesn't exist and an $event_id wasn't specified, then use a dummy object
		if ( $event instanceof WP_Post ) {
			$event_id = $event->ID;
		} elseif ( $event !== 0 ) {
			$event_id = $event;
		} else {
			$event_id = $post->ID;
		}

		if ( ! $event_id ) {
			return '';
		}

		$classes         = array( 'type-tribe_events', 'post-' . $event_id, 'tribe-clearfix' );
		$tribe_cat_slugs = tribe_get_event_cat_slugs( $event_id );

		foreach ( $tribe_cat_slugs as $tribe_cat_slug ) {
			if ( ! empty( $tribe_cat_slug ) ) {
				$classes[] = 'tribe-events-category-' . $tribe_cat_slug;
			}
		}
		if ( $venue_id = tribe_get_venue_id( $event_id ) ) {
			$classes[] = 'tribe-events-venue-' . $venue_id;
		}
		foreach ( tribe_get_organizer_ids( $event_id ) as $organizer_id ) {
			$classes[] = 'tribe-events-organizer-' . $organizer_id;
		}
		// added first class for css
		if ( ( $wp_query->current_post == 0 ) && ! tribe_is_day() ) {
			$classes[] = 'tribe-events-first';
		}
		// added last class for css
		if ( $wp_query->current_post == $wp_query->post_count - 1 ) {
			$classes[] = 'tribe-events-last';
		}

		// Mark 'featured' events
		if ( tribe( 'tec.featured_events' )->is_featured( $event_id ) ) {
			$classes[] = 'tribe-event-featured';
		}

		/**
		 * Filters the event wrapper classes before they are returned
		 *
		 * @since 4.6.20 added the $event_id parameter
		 *
		 * @param array $classes  The classes that will be returned
		 * @param int   $event_id Current event ID
		 */
		$classes = apply_filters( 'tribe_events_event_classes', $classes, $event_id );

		if ( $echo ) {
			echo implode( ' ', $classes );
		} else {
			return implode( ' ', $classes );
		}
	}

	/**
	 * Prints out data attributes used in the template header tags
	 *
	 * @category Events
	 * @param string|null $current_view
	 *
	 * @todo move to template classes
	 **/
	function tribe_events_the_header_attributes( $current_view = null ) {

		if ( ! $wp_query = tribe_get_global_query_object() ) {
			return;
		}

		$attrs        = array();
		$current_view = ! empty( $current_view ) ? $current_view : basename( tribe_get_current_template() );
		$term         = false;
		$term_name    = get_query_var( Tribe__Events__Main::TAXONOMY );

		if ( ! empty( $term_name ) ) {
			$term_obj = get_term_by( 'slug', $term_name, Tribe__Events__Main::TAXONOMY );
		}

		if ( ! empty( $term_obj ) ) {
			$term = 0 < $term_obj->term_id ? $term_obj->term_id : false;
		}

		// wp_title was deprecated in WordPress 4.4. Fetch the document title with the new function (added in 4.4) if available
		if ( function_exists( 'wp_get_document_title' ) ) {
			$attrs['data-title'] = wp_get_document_title();
		} else {
			$attrs['data-title'] = wp_title( '|', false, 'right' );
		}

		$attrs['data-viewtitle'] = tribe_get_events_title( true );

		switch ( $current_view ) {
			case 'month.php' :
				$attrs['data-view']    = 'month';
				$attrs['data-date']    = date( 'Y-m', strtotime( tribe_get_month_view_date() ) );
				$attrs['data-baseurl'] = tribe_get_gridview_link( false );
				break;
			case 'day.php' :
				$attrs['data-startofweek'] = get_option( 'start_of_week' );
				break;
			case 'list.php' :
				$attrs['data-startofweek'] = get_option( 'start_of_week' );
				$attrs['data-view'] = 'list';
				if ( tribe_is_upcoming() ) {
					$attrs['data-baseurl'] = tribe_get_listview_link( $term );
				} elseif ( tribe_is_past() ) {
					$attrs['data-view']    = 'past';
					$attrs['data-baseurl'] = tribe_get_listview_past_link( $term );
				}
				break;
		}

		if ( has_filter( 'tribe_events_mobile_breakpoint' ) ) {
			$attrs['data-mobilebreak'] = tribe_get_mobile_breakpoint();
		}

		$attrs = apply_filters( 'tribe_events_header_attributes', $attrs, $current_view );
		foreach ( $attrs as $attr => $value ) {
			echo " $attr=" . '"' . esc_attr( $value ) . '"';
		}
	}

	/**
	 * Return an array with the days of the week, numbered with respect to the start_of_week WP option
	 *
	 * @category Events
	 * @param string $format the display format for the days of the week
	 *
	 * @return array Days of the week.
	 **/
	function tribe_events_get_days_of_week( $format = null ) {

		switch ( $format ) {
			case 'min' :
				$days_of_week = Tribe__Events__Main::instance()->daysOfWeekMin;
				break;

			case 'short' :
				$days_of_week = Tribe__Events__Main::instance()->daysOfWeekShort;
				break;

			default:
				$days_of_week = Tribe__Events__Main::instance()->daysOfWeek;
				break;
		}

		$start_of_week = get_option( 'start_of_week', 0 );
		for ( $i = 0; $i < $start_of_week; $i ++ ) {
			$day = $days_of_week[ $i ];
			unset( $days_of_week[ $i ] );
			$days_of_week[ $i ] = $day;
		}

		return apply_filters( 'tribe_events_get_days_of_week', $days_of_week );
	}

	/**
	 * Display Cost Field
	 *
	 * Conditional tag to determine if the cost field should be shown in the admin editors.
	 *
	 * @category Cost
	 * @return bool
	 */
	function tribe_events_admin_show_cost_field() {
		$modules = null;

		if ( class_exists( 'Tribe__Tickets__Tickets' ) ) {
			$modules = Tribe__Tickets__Tickets::modules();
		}

		$event_origin = get_post_meta( get_the_ID(), '_EventOrigin', true );
		$show_cost    = empty( $modules ) ||
						class_exists( 'Tribe__Events__Tickets__Eventbrite__Main' ) ||
						in_array( $event_origin, apply_filters( 'tribe_events_admin_show_cost_field_origin', array( 'community-events' ) ) );

		return apply_filters( 'tribe_events_admin_show_cost_field', $show_cost, $modules );
	}

	/**
	 * Get an event's cost
	 *
	 * @category Cost
	 * @param null|int $post_id             (optional)
	 * @param bool     $with_currency_symbol Include the currency symbol
	 *
	 * @return string Cost of the event.
	 */
	function tribe_get_cost( $post_id = null, $with_currency_symbol = false ) {
		$tribe_ecp = Tribe__Events__Main::instance();
		$post_id    = Tribe__Events__Main::postIdHelper( $post_id );

		$cost_utils = tribe( 'tec.cost-utils' );
		$cost = $cost_utils->get_formatted_event_cost( $post_id, $with_currency_symbol );

		return apply_filters( 'tribe_get_cost', $cost, $post_id, $with_currency_symbol );
	}

	/**
	 * Returns the event cost complete with currency symbol.
	 *
	 * Essentially an alias of tribe_get_cost(), as if called with the $withCurrencySymbol
	 * argument set to true. Useful for callbacks.
	 *
	 * @category Cost
	 * @param null $postId
	 *
	 * @return mixed|void
	 */
	function tribe_get_formatted_cost( $postId = null ) {
		return apply_filters( 'tribe_get_formatted_cost', tribe_get_cost( $postId, true ) );
	}

	/**
	 * Get the minimum cost of all events.
	 *
	 * @category Cost
	 * @return int the minimum cost.
	 */
	function tribe_get_minimum_cost() {
		return tribe( 'tec.cost-utils' )->get_minimum_cost();
	}

	/**
	 * Get the maximum cost of all events.
	 *
	 * @category Cost
	 * @return int the maximum cost.
	 */
	function tribe_get_maximum_cost() {
		return tribe( 'tec.cost-utils' )->get_maximum_cost();
	}

	/**
	 * Indicates if there events without a specific cost (as distinct from
	 * free events).
	 *
	 * @return bool if uncosted events exist
	 */
	function tribe_has_uncosted_events() {
		return tribe( 'tec.cost-utils' )->has_uncosted_events();
	}

	/**
	 * Maps the cost array to make finding the minimum and maximum costs possible.
	 *
	 * @category Cost
	 * @param array $costs
	 *
	 * @return array $costs
	 */
	function tribe_map_cost_array_callback( $costs ) {
		return $costs;
	}

	/**
	 * Event in Category Conditional
	 *
	 * Returns true if the event is in the specified category slug
	 *
	 * @category Events
	 * @param string $event_cat_slug
	 * @param int    $event_id
	 *
	 * @return bool
	 */
	function tribe_event_in_category( $event_cat_slug, $event_id = null ) {

		if ( empty( $event_id ) ) {
			$event_id = get_the_ID();
		}

		$term = term_exists( $event_cat_slug, Tribe__Events__Main::TAXONOMY );

		if ( tribe_is_event( $event_id ) && is_object_in_term( $event_id, Tribe__Events__Main::TAXONOMY, array( $term['term_id'] ) ) ) {
			$return = true;
		} else {
			$return = false;
		}

		return apply_filters( 'tribe_event_in_category', $return );
	}

	/**
	 * Placeholder function that is used for ticketing plugins meant to be filtered by such plugins
	 *
	 * @todo possible candidate for deprecation - confirm if still required by other plugins
	 * @category Tickets
	 */
	function tribe_get_ticket_form() {
		$ticket_form = apply_filters( 'tribe_get_ticket_form', false );
		if ( $ticket_form && is_string( $ticket_form ) ) {
			echo $ticket_form;
		} else {
			return $ticket_form;
		}
	}

	/**
	 * Return the featured image for an event (within the loop automatically will get event ID).
	 *
	 * Where possible, the image will be returned as a well formed <img> tag contained in a link
	 * element and wrapped in a div used for targetting featured images from stylesheet. By setting
	 * the two final and optional parameters to false, however, it is possible to retrieve only
	 * the image URL itself.
	 *
	 * @category Events
	 *
	 * @param int    $post_id
	 * @param string $size
	 * @param bool   $link
	 * @param bool   $wrapper
	 *
	 * @return string
	 */
	function tribe_event_featured_image( $post_id = null, $size = 'full', $link = true, $wrapper = true ) {
		if ( is_null( $post_id ) ) {
			$post_id = get_the_ID();
		}

		/**
		 * Provides an opportunity to modify the featured image size.
		 *
		 * @param string $size
		 * @param int    $post_id
		 */
		$size = apply_filters( 'tribe_event_featured_image_size', $size, $post_id );

		$featured_image = $wrapper
			? get_the_post_thumbnail( $post_id, $size )
			: wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $size, false );

		if ( is_array( $featured_image ) ) {
			$featured_image = $featured_image[ 0 ];
		}

		/**
		 * Controls whether the featured image should be wrapped in a link
		 * or not.
		 *
		 * @param bool $link
		 */
		if ( ! empty( $featured_image ) && apply_filters( 'tribe_event_featured_image_link', $link ) ) {
			$featured_image = '<a href="' . esc_url( tribe_get_event_link( $post_id ) ) . '" tabindex="-1">' . $featured_image . '</a>';
		}

		/**
		 * Whether to wrap the featured image in our standard div (used to
		 * assist in targeting featured images from stylesheets, etc).
		 *
		 * @param bool $wrapper
		 */
		if ( ! empty( $featured_image ) && apply_filters( 'tribe_events_featured_image_wrap', $wrapper ) ) {
			$featured_image = '<div class="tribe-events-event-image">' . $featured_image . '</div>';
		}

		/**
		 * Provides an opportunity to modify the featured image HTML.
		 *
		 * @param string $featured_image
		 * @param int    $post_id
		 * @param string $size
		 */
		return apply_filters( 'tribe_event_featured_image', $featured_image, $post_id, $size );
	}

	/**
	 * Return the details of the start/end date/time.
	 *
	 * The highest level means of customizing this function's output is simply to adjust the date format settings under
	 * Events > Settings > Display, and WordPress time formats (via the General Settings admin screen).
	 * Beyond that, however, there are two filters which can be used to exercise further control here.
	 *
	 * The first is 'tribe_events_event_schedule_details_formatting' which allows an array of format settings to be
	 * altered - it's basic make-up is as a simple set of key:value pairs as follows.
	 *
	 * "show_end_time": for single day events only (not including all day events) it may not always be desirable to
	 *     include the end time. In that situation, this setting can be set to false and the end time will not be
	 *     displayed.
	 *
	 * "time": if it is undesirable to show times and only dates should be displayed then this setting can be set to
	 *     false. If it is false it will by extension cause 'show_end_time' to be false.
	 *
	 * The resulting string can also be caught and manipulated, or completely overridden, using the
	 * 'tribe_events_event_schedule_details' filter, should none of the above settings be sufficient.
	 *
	 * @category Events
	 * @TODO use tribe_get_datetime_format() and related functions if possible
	 *
	 * @param int|null $event The event post ID, or `null` to use the global event.
	 * @param string $before A string to prepend before the schedule details.
	 * @param string $after A string to append after the schedule details.
	 * @param bool $html Whether to use HTML elements in the output string or not; defaults to `true`.
	 *
	 * @return string The human-readable event schedule details formatted according to the current settings.
	 */
	function tribe_events_event_schedule_details( $event = null, $before = '', $after = '', $html = true ) {
		static $cache_var_name = __FUNCTION__;

		if ( is_null( $event ) ) {
			global $post;
			$event = $post;
		}

		if ( is_numeric( $event ) ) {
			$event = get_post( $event );
		}

		// if the post is password protected, don't return the schedule details
		if ( post_password_required( $event ) ) {
			return '';
		}

		$cache_details = tribe_get_var( $cache_var_name, [] );
		$cache_details_key    = "{$event->ID}:{$before}:{$after}:{$html}";

		if ( ! isset( $cache_details[ $cache_details_key ] ) ) {
			$inner                    = $html ? '<span class="tribe-event-date-start">' : '';
			$format                   = '';
			$date_without_year_format = tribe_get_date_format();
			$date_with_year_format    = tribe_get_date_format( true );
			$time_format              = get_option( 'time_format' );
			$datetime_separator       = tribe_get_option( 'dateTimeSeparator', ' @ ' );
			$time_range_separator     = tribe_get_option( 'timeRangeSeparator', ' - ' );

			$settings = array(
				'show_end_time' => true,
				'date'          => true,
				'time'          => true,
			);

			$settings = wp_parse_args( apply_filters( 'tribe_events_event_schedule_details_formatting', $settings ), $settings );
			if ( ! $settings['time'] ) {
				$settings['show_end_time'] = false;
			}

			/**
			 * @var $show_end_time
			 * @var $date
			 * @var $time
			 */
			extract( $settings );

			$format = $date_with_year_format;

			/**
			 * If a yearless date format should be preferred.
			 *
			 * By default, this will be true if the event starts and ends in the current year.
			 *
			 * @param bool    $use_yearless_format
			 * @param WP_Post $event
			 */
			$use_yearless_format = apply_filters( 'tribe_events_event_schedule_details_use_yearless_format',
				(
					tribe_get_start_date( $event, false, 'Y' ) === date_i18n( 'Y' )
					&& tribe_get_end_date( $event, false, 'Y' ) === date_i18n( 'Y' )
				),
				$event
			);

			if ( $use_yearless_format ) {
				$format = $date_without_year_format;
			}

			if ( tribe_event_is_multiday( $event ) ) { // multi-date event

				$format2ndday = apply_filters( 'tribe_format_second_date_in_range', $format, $event );

				if ( tribe_event_is_all_day( $event ) ) {
					$inner .= $date ? tribe_get_start_date( $event, true, $format ) : '';
					$inner .= $html ? '</span>' : '';
					$inner .= $date ? $time_range_separator : '';
					$inner .= $html ? '<span class="tribe-event-date-end">' : '';

					$end_date_full = tribe_get_end_date( $event, true, Tribe__Date_Utils::DBDATETIMEFORMAT );
					$end_date_full_timestamp = strtotime( $end_date_full );

					// if the end date is <= the beginning of the day, consider it the previous day
					if ( $end_date_full_timestamp <= strtotime( tribe_beginning_of_day( $end_date_full ) ) ) {
						$end_date = tribe_format_date( $end_date_full_timestamp - DAY_IN_SECONDS, false, $format2ndday );
					} else {
						$end_date = tribe_get_end_date( $event, false, $format2ndday );
					}

					$inner .= $date ? $end_date : '';
				} else {
					$inner .= $date ? tribe_get_start_date( $event, false, $format ) : '';
					$inner .= $date && $time ? $datetime_separator : '';
					$inner .= $time ? tribe_get_start_date( $event, false, $time_format ) : '';
					$inner .= $html ? '</span>' : '';
					$inner .= $date && $time ? $time_range_separator : '';
					$inner .= $html ? '<span class="tribe-event-date-end">' : '';
					$inner .= $date ? tribe_get_end_date( $event, false, $format2ndday ) : '';
					$inner .= $date && $time ? $datetime_separator : '';
					$inner .= $time ? tribe_get_end_date( $event, false, $time_format ) : '';
				}
			} elseif ( tribe_event_is_all_day( $event ) ) { // all day event
				$inner .= $date ? tribe_get_start_date( $event, true, $format ) : '';
			} else { // single day event
				if ( tribe_get_start_date( $event, false, 'g:i A' ) === tribe_get_end_date( $event, false, 'g:i A' ) ) { // Same start/end time
					$inner .= $date ? tribe_get_start_date( $event, false, $format ) : '';
					$inner .= $date && $time ? $datetime_separator : '';
					$inner .= $time ? tribe_get_start_date( $event, false, $time_format ) : '';
				} else { // defined start/end time
					$inner .= $date ? tribe_get_start_date( $event, false, $format ) : '';
					$inner .= $date && $time ? $datetime_separator : '';
					$inner .= $time ? tribe_get_start_date( $event, false, $time_format ) : '';
					$inner .= $html ? '</span>' : '';
					$inner .= $show_end_time ? $time_range_separator : '';
					$inner .= $html ? '<span class="tribe-event-time">' : '';
					$inner .= $show_end_time ? tribe_get_end_date( $event, false, $time_format ) : '';
				}
			}

			$inner .= $html ? '</span>' : '';

			$cache_details[ $cache_details_key ] = $inner;
			tribe_set_var( $cache_var_name, $cache_details );
		}

		/**
		 * Provides an opportunity to modify the *inner* schedule details HTML (ie before it is
		 * wrapped).
		 *
		 * @param string $inner_html  the output HTML
		 * @param int    $event_id    post ID of the event we are interested in
		 */
		$inner = apply_filters( 'tribe_events_event_schedule_details_inner', $cache_details[ $cache_details_key ], $event->ID );

		// Wrap the schedule text
		$schedule = $before . $inner . $after;

		/**
		 * Provides an opportunity to modify the schedule details HTML for a specific event after
		 * it has been wrapped in the before and after markup.
		 *
		 * @param string $schedule  the output HTML
		 * @param int    $event_id  post ID of the event we are interested in
		 * @param string $before    part of the HTML wrapper that was prepended
		 * @param string $after     part of the HTML wrapper that was appended
		 */
		return apply_filters( 'tribe_events_event_schedule_details', $schedule, $event->ID, $before, $after );
	}

	/**
	 * Returns json for javascript templating functions throughout the plugin.
	 *
	 * @category Events
	 *
	 * @param $event
	 * @param $additional
	 *
	 * @return string
	 */
	function tribe_events_template_data( $event = null, array $additional = null ) {
		// Base JSON variable
		$json = array(
			'i18n' => array(),
		);

		if ( ! is_null( $event ) ) {
			$event = get_post( $event );
			// Check if we are dealing with an Event
			if ( is_object( $event ) && $event instanceof WP_Post && tribe_is_event( $event->ID ) ) {
				$has_image      = false;
				$image_src      = '';
				$image_tool_src = '';

				/**
				 * Fires before the $date_display is called
				 *
				 * @since 4.7.2
				 *
				 * @param $event
				 */
				do_action( 'tribe_events_before_event_template_data_date_display', $event );
				$date_display = strip_tags( tribe_events_event_schedule_details( $event ) );
				/**
				 * Fires after the $date_display is called
				 *
				 * @since 4.7.2
				 *
				 * @param $event
				 */
				do_action( 'tribe_events_after_event_template_data_date_display', $event );

				if ( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail( $event->ID ) ) {
					$has_image      = true;
					$image_src      = tribe_event_featured_image( $event->ID, 'large', false, false );
					$image_tool_src = tribe_event_featured_image( $event->ID, 'medium', false, false );
				}

				$category_classes = tribe_events_event_classes( $event->ID, false );
				$day              = tribe_events_get_current_month_day();
				$event_id         = "{$event->ID}-{$day['date']}";

				$json['eventId']         = $event_id;
				$json['title']           = wp_kses_post( apply_filters( 'the_title', $event->post_title, $event->ID ) );
				$json['permalink']       = tribe_get_event_link( $event->ID );
				$json['imageSrc']        = $image_src;
				$json['dateDisplay']     = $date_display;
				$json['imageTooltipSrc'] = $image_tool_src;
				$json['excerpt']         = ! post_password_required( $event ) ? tribe_events_get_the_excerpt( $event, null, true ) : '';
				$json['categoryClasses'] = $category_classes;

				/**
				 * Template overrides (of month/tooltip.php) set up in 3.9.3 or earlier may still expect
				 * these vars and will break without them, so they are being kept temporarily for
				 * backwards compatibility purposes.
				 *
				 * @todo consider removing in 4.0
				 */
				$json['startTime'] = tribe_get_start_date( $event );
				$json['endTime']   = tribe_get_end_date( $event );
			}
		}

		/**
		 * Internationalization Strings
		 */
		$json['i18n']['find_out_more'] = esc_attr__( 'Find out more »', 'the-events-calendar' );
		$json['i18n']['for_date'] = sprintf( esc_attr__( '%s for', 'the-events-calendar' ), tribe_get_event_label_plural() );

		if ( $additional ) {
			$json = array_merge( (array) $json, (array) $additional );
		}

		$json = apply_filters( 'tribe_events_template_data_array', $json, $event, $additional );

		$json = tribe_prepare_for_json_deep( $json );

		return json_encode( $json );
	}

	/**
	 * Include the List view
	 *
	 * Accepts an array of query arguments, retrieves them, and returns the html for those events in list view
	 *
	 * Optional inline example:
	 * < code >
	 * <?php
	 * echo myfunction();
	 * ?>
	 * </ code >
	 *
	 * @category Events
	 *
	 * @param array $args       Args to be passed to Tribe__Events__Query::getEvents()
	 * @param bool  $initialize Whether the list view template class needs to be included and initialized
	 *
	 * @return string
	 **/
	function tribe_include_view_list( $args = null, $initialize = true ) {

		global $wp_query;

		// hijack the main query to load the events via provided $args
		if ( ! is_null( $args ) || ! ( $wp_query->tribe_is_event || $wp_query->tribe_is_event_category ) ) {
			$reset_q  = $wp_query;
			$wp_query = Tribe__Events__Query::getEvents( $args, true );
		}

		// single-event notices are jumping in on this init when loading as a module
		Tribe__Notices::remove_notice( 'event-past' );

		// get the list view template
		ob_start();
		if ( $initialize ) {
			tribe_initialize_view( 'Tribe__Events__Template__List' );
		}
		tribe_get_view( 'list/content' );
		$list_view_html = ob_get_clean();

		// fix the error of our ways
		if ( ! empty( $reset_q ) ) {
			$wp_query = $reset_q;
		}

		// return the parsed template
		return $list_view_html;
	}

	/**
	 * Get a list of the views that are enabled
	 *
	 * @category Events
	 *
	 * @param $deprecated deprecated
	 *
	 * @return array
	 * @see tribeEnableViews option
	 * @todo remove deprecated param in 4.0
	 **/
	function tribe_events_enabled_views( $deprecated = null ) {
		if ( isset( $deprecated ) ) {
			_deprecated_argument( __FUNCTION__, '3.10' );
		}
		return tribe_get_option( 'tribeEnableViews', array(
			'list',
			'month',
		) );
	}

	/**
	 * Get a list of the views that are disabled
	 *
	 * @category Events
	 *
	 * @return array
	 * @deprecated
	 * @todo remove in 4.0
	 **/
	function tribe_events_disabled_views() {
		_deprecated_function( __FUNCTION__, '3.10', 'tribe_events_is_view_enabled( $view )' );
		static $disabled;
		if ( isset( $disabled ) ) {
			return $disabled;
		}
		$views    = apply_filters( 'tribe-events-bar-views', array(), false );
		$enabled  = tribe_events_enabled_views( $views );
		$disabled = array();
		foreach ( $views as $view ) {
			if ( ! in_array( $view['displaying'], $enabled ) ) {
				$disabled[] = $view['displaying'];
			}
		}

		return $disabled;
	}

	/**
	 * Display the Events Calendar promo banner
	 *
	 * @category Events
	 *
	 * @param bool $echo Whether or not to echo the banner, if false, it's returned
	 *
	 * @return string
	 **/
	function tribe_events_promo_banner( $echo = true ) {
		if ( tribe_get_option( 'donate-link', false ) == true && ! tribe_is_bot() ) {
			$promo = apply_filters( 'tribe_events_promo_banner_message', sprintf( esc_html__( 'Calendar powered by %s', 'the-events-calendar' ), '<a class="tribe-common-anchor-thin vcard url org fn" href="' . Tribe__Events__Main::$tecUrl . 'product/wordpress-events-calendar/?utm_medium=plugin-tec&utm_source=banner&utm_campaign=in-app">' . esc_html__( 'The Events Calendar', 'the-events-calendar' ) . '</a>' ) );
			$html  = apply_filters( 'tribe_events_promo_banner', sprintf( '<p class="tribe-events-promo tribe-common-b1 tribe-events-c-promo">%s</p>', $promo ), $promo );
			if ( $echo ) {
				echo $html;
			} else {
				return $html;
			}
		}
	}

	/**
	 * Return the filters registered in the tribe bar
	 *
	 * @category Events
	 *
	 * @return array
	 **/
	function tribe_events_get_filters() {
		return apply_filters( 'tribe-events-bar-filters', array() );
	}

	/**
	 * Return the views registered in the tribe bar
	 *
	 * @category Events
	 *
	 * @return array
	 **/
	function tribe_events_get_views() {
		return apply_filters( 'tribe-events-bar-views', array() );
	}

	/**
	 * Returns the URL for use in the tribe bar form's action attribute.
	 *
	 * @return string URL for current tribe bar form action.
	 */
	function tribe_events_get_current_filter_url() {
		global $wp;

		$url = esc_url( add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
		return apply_filters( 'tribe_events_get_current_filter_url', $url );
	}

	/**
	 * Get and increment tab index in form fields
	 *
	 */
	function tribe_events_get_tab_index() {
		$tribe_events = Tribe__Events__Main::instance();

		return apply_filters( 'tribe_events_tab_index', $tribe_events->tabIndex() );
	}

	/**
	 * Echo and increment tab index in form fields
	 *
	 */
	function tribe_events_tab_index() {
		echo tribe_events_get_tab_index();
	}

	/**
	 * Check if a particular view is enabled
	 *
	 * @category Events
	 *
	 * @param string $view Name of view to check, should match what's in Tribe__Events__Main->displaying when on that view
	 *
	 * @return bool
	 **/
	function tribe_events_is_view_enabled( $view ) {
		$enabled_views = tribe_events_enabled_views();
		$enabled = in_array( $view, $enabled_views );
		return apply_filters( 'tribe_events_is_view_enabled', $enabled, $view, $enabled_views );
	}

	/**
	 * A Excerpt method used across the board on our Events Plugin Suite.
	 *
	 * By default it removes all shortcodes, the reason for this is that shortcodes added by other plugins/themes
	 * may not have been registered by the time our ajax responses are generated. To avoid leaving unparsed
	 * shortcodes in our excerpts then we strip out anything that looks like one.
	 *
	 * @category Events
	 *
	 * @param WP_Post|int|null $post The Post Object|ID, if null defaults to `get_the_ID()`
	 * @param array $allowed_html The wp_kses compatible array
	 * @param boolean $skip_postdata_manipulation Optional. Defaults to false. When true, the resetting of global $post variable is disabled. (Useful for some contexts like month view.)
	 *
	 * @return string|null Will return null on Bad Post Instances
	 */
	function tribe_events_get_the_excerpt( $post = null, $allowed_html = null, $skip_postdata_manipulation = false ) {
		static $cache_var_name = __FUNCTION__;

		$cache_excerpts = tribe_get_var( $cache_var_name, [] );

		// If post is not numeric or instance of WP_Post it defaults to the current Post ID
		if ( ! is_numeric( $post ) && ! $post instanceof WP_Post ) {
			$post = get_the_ID();
		}

		// If not a WP_Post we try to fetch it as one
		if ( is_numeric( $post ) ) {
			$post = WP_Post::get_instance( $post );
		}

		// Prevent Non usable $post instances
		if ( ! $post instanceof WP_Post ) {
			return null;
		}

		// Default Allowed HTML
		if ( ! is_array( $allowed_html ) ) {
			$base_attrs = array(
				'class' => array(),
				'id' => array(),
				'style' => array(),
			);
			$allowed_html = array(
				'a' => array(
					'class' => array(),
					'id' => array(),
					'style' => array(),
					'href' => array(),
					'rel' => array(),
					'target' => array(),
				),
				'b' => $base_attrs,
				'strong' => $base_attrs,
				'em' => $base_attrs,
				'span' => $base_attrs,
				'ul' => $base_attrs,
				'li' => $base_attrs,
				'ol' => $base_attrs,
			);
		}

		$post_id = $post->ID;

		/**
		 * Allow developers to filter what are the allowed HTML on the Excerpt
		 *
		 * @param array Must be compatible to wp_kses structure.
		 * @param WP_Post $post The current post object.
		 *
		 * @link https://codex.wordpress.org/Function_Reference/wp_kses
		 */
		$allowed_html = apply_filters( 'tribe_events_excerpt_allowed_html', $allowed_html, $post );

		/**
		 * Allow shortcodes to be Applied on the Excerpt or not
		 *
		 * @param bool Whether shortcodes are allowed in the Event excerpt or not.
		 * @param WP_Post $post The current post object.
		 */
		$allow_shortcodes = apply_filters( 'tribe_events_excerpt_allow_shortcode', false, $post );

		/**
		 * Filter to stop removal of shortcode markup in the excerpt.
		 *
		 * This will remove all text that resembles a shortcode [shortcode 5].
		 *
		 * @since 5.1.0
		 *
		 * @param bool $remove_shortcodes Whether shortcodes content should be removed from the excerpt or not.
		 * @param WP_Post $post The current post object.
		 */
		$remove_shortcodes = apply_filters( 'tribe_events_excerpt_shortcode_removal', true, $post );

		/**
		 * Filters whether the content produced by block editor blocks should be removed or not from the excerpt.
		 *
		 * If truthy then block whose content does not belong in the excerpt, will be removed.
		 * This removal is done using WordPress Core `excerpt_remove_blocks` function.
		 *
		 * @since 5.1.0
		 *
		 * @param bool $remove_blocks Whether blocks whose content should not be part of the excerpt should be removed
		 *                            or not from the excerpt.
		 * @param WP_Post $post The current post object.
		 *
		 * @see   excerpt_remove_blocks() The WordPress Core function that will handle the block removal from the excerpt.
		 */
		$remove_blocks = (bool) apply_filters( 'tribe_events_excerpt_blocks_removal', true, $post );

		$cache_excerpts_key = implode( ':', [
			$post->ID,
			$skip_postdata_manipulation,
			$allow_shortcodes,
			$remove_shortcodes,
			json_encode( $allowed_html )
		] );

		if ( ! isset( $cache_excerpts[ $cache_excerpts_key ] ) ) {
			// Get the Excerpt or content based on what is available.
			$excerpt = has_excerpt( $post->ID ) ? $post->post_excerpt : $post->post_content;

			/*
			 * If blocks should be removed from the excerpt, remove them now.
			 */
			if ( $remove_blocks && function_exists( 'excerpt_remove_blocks' ) ) {
				$excerpt = excerpt_remove_blocks( $excerpt );
			}

			// If shortcode filter is enabled let's process them.
			if ( $allow_shortcodes ) {
				$excerpt = do_shortcode( $excerpt );
			}

			// Remove all shortcode Content before removing HTML
			if ( $remove_shortcodes ) {
				$excerpt = preg_replace( '#\[.+\]#U', '', $excerpt );
			}

			// Remove "all" HTML based on what is allowed
			$excerpt = wp_kses( $excerpt, $allowed_html );

			if ( ! has_excerpt( $post->ID ) ) {
				// Temporarily alter the global post in preparation for our filters.
				$global_post = isset( $GLOBALS['post'] ) ? $GLOBALS['post'] : null;
				$GLOBALS['post'] = $post;

				// We will only trim Excerpt if it comes from Post Content

				/**
				 * Filter the number of words in an excerpt.
				 *
				 * @param int $number The number of words. Default 55.
				 */
				$excerpt_length = apply_filters( 'excerpt_length', 55 );

				/**
				 * Filter the string in the "more" link displayed after a trimmed excerpt.
				 *
				 * @param string $more_string The string shown within the more link.
				 */
				$excerpt_more = apply_filters( 'excerpt_more', ' [&hellip;]' );

				// Now we actually trim it
				$excerpt = wp_trim_words( $excerpt, $excerpt_length, $excerpt_more );

				// Original post is back in action!
				$GLOBALS['post'] = $global_post;
			}

			$cache_excerpts[ $cache_excerpts_key ] = wpautop( $excerpt );
			tribe_set_var( $cache_var_name, $cache_excerpts );
		}

		if ( ! $skip_postdata_manipulation ) {
			// Setup post data to be able to use WP template tags
			setup_postdata( $post );
		}

		/**
		 * Filter the event excerpt used in various views.
		 *
		 * @param string  $excerpt
		 * @param WP_Post $post
		 */
		$excerpt = apply_filters( 'tribe_events_get_the_excerpt', $cache_excerpts[ $cache_excerpts_key ], $post );

		if ( ! $skip_postdata_manipulation ) {
			wp_reset_postdata();
		}

		return $excerpt;
	}

	/**
	 * Returns the latest known event end date, which can be expected to be a string
	 * in MySQL datetime format (unless some other specific format is provided).
	 *
	 * If this is impossible to determine it will return boolean false.
	 *
	 * @category Events
	 *
	 * @param string $format
	 *
	 * @return mixed bool|string
	 */
	function tribe_events_latest_date( $format = Tribe__Date_Utils::DBDATETIMEFORMAT ) {
		// Check if the latest end date is already known
		$latest = tribe_get_option( 'latest_date', false );
		if ( false !== $latest ) {
			return Tribe__Date_Utils::reformat( $latest, $format );
		}

		// If not, try to determine now
		Tribe__Events__Dates__Known_Range::instance()->rebuild_known_range();
		$latest = tribe_get_option( 'latest_date', false );
		if ( false !== $latest ) {
			return Tribe__Date_Utils::reformat( $latest, $format );
		}

		return false;
	}

	/**
	 * Returns the earliest known event start date, which can be expected to be a string
	 * in MySQL datetime format (unless some other specific format is provided).
	 *
	 * If this is impossible to determine it will return boolean false.
	 *
	 * @category Events
	 *
	 * @param string $format
	 *
	 * @return mixed bool|string
	 */
	function tribe_events_earliest_date( $format = Tribe__Date_Utils::DBDATETIMEFORMAT ) {
		// Check if the earliest start date is already known
		$earliest = tribe_get_option( 'earliest_date', false );
		if ( false !== $earliest ) {
			return Tribe__Date_Utils::reformat( $earliest, $format );
		}

		// If not, try to determine now
		Tribe__Events__Dates__Known_Range::instance()->rebuild_known_range();
		$earliest = tribe_get_option( 'earliest_date', false );
		if ( false !== $earliest ) {
			return Tribe__Date_Utils::reformat( $earliest, $format );
		}

		return false;
	}

	/**
	 * Get the default value for a field
	 *
	 * @param string $field
	 * @return mixed
	 */
	function tribe_get_default_value( $field ) {
		$field = strtolower( $field );
		$defaults = Tribe__Events__Main::instance()->defaults();
		$value = call_user_func( array( $defaults, $field ) );
		return $value;
	}

	/**
	 * Gets the render context of the given query
	 *
	 * @param WP_Query $query Query object
	 * @return string
	 */
	function tribe_get_render_context( $query = null ) {

		global $wp_query;

		if ( ! $query instanceof WP_Query ) {
			$query = $wp_query;
		}

		if ( empty( $query->query['tribe_render_context'] ) ) {
			return 'default';
		}

		return $query->query['tribe_render_context'];
	}

	/**
	 * Returns or echoes a url to a file in the Events Calendar plugin resources directory
	 *
	 * @category Events
	 * @param string $resource the filename of the resource
	 * @param bool   $echo     whether or not to echo the url
	 * @param string $root_dir directory to hunt for resource files (src or common)
	 *
	 * @return string
	 **/
	function tribe_events_resource_url( $resource, $echo = false, $root_dir = 'src' ) {
		$extension = pathinfo( $resource, PATHINFO_EXTENSION );

		if ( 'src' !== $root_dir ) {
			return tribe_resource_url( $resource, $echo, $root_dir );
		}

		$resources_path = $root_dir . '/resources/';
		switch ( $extension ) {
			case 'css':
				$resource_path = $resources_path .'css/';
				break;
			case 'js':
				$resource_path = $resources_path .'js/';
				break;
			case 'scss':
				$resource_path = $resources_path .'scss/';
				break;
			default:
				$resource_path = $resources_path;
				break;
		}

		$path = $resource_path . $resource;

		$file = wp_normalize_path( Tribe__Events__Main::instance()->plugin_path . $path );

		// Turn the Path into a URL
		$url = plugins_url( basename( $file ), $file );

		/**
		 * Deprecated the tribe_events_resource_url filter in 4.0 in favor of tribe_resource_url. Remove in 5.0
		 */
		$url = apply_filters( 'tribe_events_resource_url', $url, $resource );

		if ( $echo ) {
			echo esc_url( $url );
		}

		return $url;
	}

	/**
	 * Utility function to compile separated lists.
	 *
	 * @param string $body
	 * @param string $separator
	 * @param string $field
	 *
	 * @return string
	 */
	function tribe_separated_field( $body, $separator, $field ) {
		$body_and_separator = $body ? $body . $separator : $body;

		return $field ? $body_and_separator . $field : $body;
	}

	/**
	 * Tests if we are on the site homepage and if it is set to display the main events page.
	 *
	 * As WordPress front page it might be different from is_home, if we have a front page on the reading options and
	 * if the User is on that page, this function will return true otherwise will return false. So either if the User has
	 * the frontpage set on the reading options and the User is visiting this page.
	 *
	 * Another consideration about this is it might behave as a WordPress function which means after any Ajax action is
	 * fired the result of call this function via Ajax might not be the expected result so ideally can be used to test
	 * if you are on the front page on first load of the page only.
	 *
	 * @since 4.6.9
	 *
	 * @return bool
	 */
	function tribe_is_events_front_page() {

		$wp_query = tribe_get_global_query_object();

		$events_as_front_page = tribe_get_option( 'front_page_event_archive', false );
		// If the reading option has an events page as front page and we are on that page is on the home of events.
		return (
			$wp_query->is_main_query()
			&& $events_as_front_page
			&& $wp_query->tribe_is_event
			&& true === get_query_var( 'tribe_events_front_page' )
		);
	}

	/**
	 * Test if we are on the home of events either if is set to frontpage or the default /events page.
	 *
	 * Utility function to test if we are on the home of events, it makes a test in cases when the page is set to be on
	 * the frontpage of the site and if the User is on that page is on the homepage or if the User is on the events page
	 * where the eventDisplay is set to default.
	 *
	 * Also consider this might not work as expected inside of Ajax Calls as this one is fired on initial loading of the
	 * page so be aware of unexpected results via Ajax calls.
	 *
	 * @since 4.6.9
	 *
	 * @return bool
	 */
	function tribe_is_events_home() {

		$wp_query = tribe_get_global_query_object();

		if ( tribe_is_events_front_page() ) {
			return true;
		}

		$events_as_front_page = tribe_get_option( 'front_page_event_archive', false );
		// If the readme option does not has an event page as front page and if id the 'default' view on the main query
		// as is going to set to 'default' when is loading the root of events/ rewrite rule also makes sure is not on
		// a taxonomy or a tag.
		if (
			! $events_as_front_page
			&& $wp_query->is_main_query()
			&& $wp_query->tribe_is_event // Make sure following conditionals operate only on events
			&& ( isset( $wp_query->query['eventDisplay'] ) && 'default' === $wp_query->query['eventDisplay'] )
			&& is_post_type_archive()
			&& ! is_tax()
			&& ! is_tag()
		) {
			return true;
		}

		// No condition was true so is not on home of events.
		return false;
	}
}

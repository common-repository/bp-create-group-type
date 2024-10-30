<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Bp_Add_Group_Types
 * @subpackage Bp_Add_Group_Types/public
 */

if ( ! class_exists( 'Bp_Add_Group_Types_Public' ) ) :

	/**
	 * The public-facing functionality of the plugin.
	 *
	 * Defines the plugin name, version, and two examples hooks for how to
	 * enqueue the admin-specific stylesheet and JavaScript.
	 *
	 * @package    Bp_Add_Group_Types
	 * @subpackage Bp_Add_Group_Types/public
	 * @author     Wbcom Designs <admin@wbcomdesigns.com>
	 */
	class Bp_Add_Group_Types_Public {

		/**
		 * The ID of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $plugin_name    The ID of this plugin.
		 */
		private $plugin_name;

		/**
		 * The version of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $version    The current version of this plugin.
		 */
		private $version;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 * @param      string $plugin_name       The name of the plugin.
		 * @param      string $version    The version of this plugin.
		 */
		public function __construct( $plugin_name, $version ) {

			$this->plugin_name = $plugin_name;
			$this->version     = $version;
		}

		/**
		 * Register the stylesheets for the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles() {

			/**
			 * This function is provided for demonstration purposes only.
			 *
			 * An instance of this class should be passed to the run() function
			 * defined in Bp_Add_Group_Types_Loader as all of the hooks are defined
			 * in that particular class.
			 *
			 * The Bp_Add_Group_Types_Loader will then create the relationship
			 * between the defined hooks and the functions defined in this
			 * class.
			 */
			global $bp;
			$bp_template_option = bp_get_option( '_bp_theme_package_id' );

			if ( bp_is_groups_component() ) {

				$rtl_css = is_rtl() ? '-rtl' : '';

				wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css' . $rtl_css . '/bp-add-group-types-public.css', array(), $this->version, 'all' );

				if ( 'nouveau' == $bp_template_option ) {
					wp_enqueue_style( $this->plugin_name . '-nouveau-css', plugin_dir_url( __FILE__ ) . 'css' . $rtl_css . '/bp-add-group-types-public-nouveau.css', array(), $this->version, 'all' );
				}
			}
		}

		/**
		 * Register the JavaScript for the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts() {

			/**
			 * This function is provided for demonstration purposes only.
			 *
			 * An instance of this class should be passed to the run() function
			 * defined in Bp_Add_Group_Types_Loader as all of the hooks are defined
			 * in that particular class.
			 *
			 * The Bp_Add_Group_Types_Loader will then create the relationship
			 * between the defined hooks and the functions defined in this
			 * class.
			 */
			global $bp;
			global $bp_grp_types;
			$bp_template_option = bp_get_option( '_bp_theme_package_id' );

			if ( bp_is_groups_component() ) {

				if ( 'nouveau' == $bp_template_option ) {
					wp_enqueue_script( $this->plugin_name . '-nouveau', plugin_dir_url( __FILE__ ) . 'js/bp-add-group-types-public-nouveau.js', array( 'jquery' ), $this->version, false );
				} else {

					wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bp-add-group-types-public.js', array( 'jquery' ), $this->version, false );

					wp_localize_script(
						$this->plugin_name,
						'bpgt_front_js_object',
						array(
							'ajaxurl' => admin_url( 'admin-ajax.php' ),
						)
					);
				}
			}

		}

		/**
		 * Change the group search template.
		 *
		 * @param string $search_form_html The seach form html.
		 * @since    1.0.0
		 */
		public function bpgt_modified_group_search_form( $search_form_html ) {
			global $bp_grp_types;
			if ( ! isset( $bp_grp_types->group_type_search_template ) || 'textbox' !== $bp_grp_types->group_type_search_template ) {
				$group_types = bp_groups_get_group_types( array(), 'objects' );

				$group_select_html = '';
				if ( ! empty( $group_types ) && is_array( $group_types ) ) {
					$group_select_html .= '<div class="bpgt-groups-search-group-type select-wrap" id="bpgt-groups-nouveau-search-group-type"><select class="bpgt-groups-search-group-type">';
					$group_select_html .= '<option value="">' . __( 'All Types', 'bp-add-group-types' ) . '</option>';
					foreach ( $group_types as $group_type_slug => $group_type ) {
						$term_id      = $group_type->db_id;
						$bp_type_name = get_term_meta( $term_id, 'bp_type_name', true );

						$group_select_html .= '<option value="' . $group_type_slug . '">' . ( ( isset( $group_type->labels['name'] ) && '' != $group_type->labels['name'] ) ? $group_type->labels['name'] : $bp_type_name ) . '</option>';
					}
					$group_select_html .= '</select><span class="select-arrow" aria-hidden="true"></span></div>';
				}

				if ( isset( $bp_grp_types->group_type_search_template ) && 'both' === $bp_grp_types->group_type_search_template ) {
					$search_html       = $search_form_html;
					$search_form_html  = '';
					$search_form_html .= $group_select_html;
					$search_form_html .= $search_html;
				} else {
					$search_form_html = $group_select_html;
				}
			}
			return $search_form_html;
		}

		/**
		 * Change the group search template.
		 *
		 * @param string $bp_ajax_querystring The seach form html.
		 * @param string $object The seach form html.
		 * @since    1.0.0
		 */
		public function bpgt_alter_bp_ajax_querystring( $bp_ajax_querystring, $object ) {
			global $bp;
			$object             = filter_input( INPUT_POST, 'object' );
			$query_extras       = filter_input( INPUT_POST, 'extras' );
			$scope              = filter_input( INPUT_POST, 'scope' );
			$bp_template_option = bp_get_option( '_bp_theme_package_id' );

			if ( empty( $object ) ) {
				if ( bp_is_groups_directory() ) {
					$object = 'groups';
				}
			}
			if ( 'legacy' == $bp_template_option ) {
				parse_str( $query_extras, $legacy_extra );
				if ( empty( $legacy_extra['group_type'] ) ) {
					if ( isset( $_COOKIE['current_bpgt_tab'] ) ) {
						$current_tab = sanitize_text_field( wp_unslash( $_COOKIE['current_bpgt_tab'] ) );
						if ( 'all' == $_COOKIE['current_bpgt_tab'] ) {
							$query_extras = '';
						} else {
							$query_extras = 'group_type=' . $current_tab;
						}
					}
				}
			} else {
				if ( empty( $query_extras ) ) {
					if ( isset( $_COOKIE['current_bpgt_tab'] ) ) {
						$current_tab = sanitize_text_field( wp_unslash( $_COOKIE['current_bpgt_tab'] ) );
						if ( 'all' == $_COOKIE['current_bpgt_tab'] ) {
							$query_extras = '';
						} else {
							$query_extras = 'group_type=' . $current_tab;
						}
					}
				}
			}
			parse_str( $query_extras, $extras );
			if ( null !== $extras && isset( $extras['group_type'] ) ) {
				if ( 'all' === $extras['group_type'] ) {
					$extras = null;
				}
			}

			if ( ( null !== $object ) && ( 'groups' === $object ) && ( null !== $extras ) && ! empty( $extras ) ) {
				if ( ! empty( $extras ) && is_array( $extras ) ) {
					if ( ! empty( $extras['group_type'] ) ) {
						$bp_ajax_querystring = add_query_arg( 'group_type', $extras['group_type'], $bp_ajax_querystring );
						if ( ! empty( $scope ) && 'all' !== $scope ) {
							if ( 'all' !== $extras['group_type'] && ! empty( $extras['group_type'] ) ) {
								$allgroups = groups_get_groups(
									array(
										'status'   => array( 'public', 'private', 'hidden' ),
										'per_page' => 999,
									)
								);
								if ( ! empty( $allgroups ) && array_key_exists( 'groups', $allgroups ) ) {
									$include_groups = array();
									$exclude_groups = array();
									foreach ( $allgroups['groups'] as $group ) {
										$group_type = (array) bp_groups_get_group_type( $group->id, false );
										if ( ! empty( $group_type ) && is_array( $group_type ) ) {
											if ( in_array( $extras['group_type'], $group_type, true ) && in_array( $scope, $group_type, true ) ) {
												array_push( $include_groups, $group->id );
											}
										}
										array_push( $exclude_groups, $group->id );
									}

									if ( ! empty( $include_groups ) ) {
										$include_groups      = implode( ',', $include_groups );
										$bp_ajax_querystring = add_query_arg( 'include', $include_groups, $bp_ajax_querystring );
									} elseif ( ! empty( $exclude_groups ) ) {
										$exclude_groups      = implode( ',', $exclude_groups );
										$bp_ajax_querystring = add_query_arg( 'exclude', $exclude_groups, $bp_ajax_querystring );
									}
								}
							}
						}
					}
				}
			}

			return $bp_ajax_querystring;
		}


		/**
		 * Ajax served to search groups
		 *
		 * @since 1.0.0
		 */
		public function bpgt_search_groups() {
			if ( ( null !== filter_input( INPUT_POST, 'action' ) ) && 'bpgt_search_groups' === filter_input( INPUT_POST, 'action' ) ) {
				$_POST['object'] = 'groups';
				bp_legacy_theme_object_template_loader();
				die;
			}
		}

		/**
		 * Add group type tabs in nouveau template.
		 *
		 * @since 2.0.0
		 *
		 * @param array $tabs The list of the groups directory nav items.
		 */
		public function bpgt_nouveau_display_directory_tabs( $tabs ) {
			global $wp, $bp_grp_types;

			$current_url = home_url( add_query_arg( array(), $wp->request ) ) . '/';
			$group_types = bp_groups_get_group_types();
			if ( bp_get_groups_directory_permalink() === $current_url || bp_is_groups_component() ) {
				if ( ! empty( $group_types ) ) {
					foreach ( $group_types as $key => $group_type ) :

						$bp_group_type_term = get_term_by( 'slug', $key, 'bp_group_type' );
						if ( ! empty( $bp_group_type_term ) ) {
							$bp_group_type_id = $bp_group_type_term->term_id;

							$display = get_term_meta( $bp_group_type_id, 'bp_group_type_display_as_tab', true );
							$name    = get_term_meta( $bp_group_type_id, 'bp_type_singular_name', true );

							if ( 1 == $display ) {
								$tabs[ esc_attr( $key ) ] = array(
									'component' => 'groups',
									'slug'      => esc_attr( $key ),
									'li_class'  => array(
										'0' => 'bpgt-type-tab',
									),
									'link'      => bp_get_groups_directory_permalink(),
									'text'      => esc_attr( $name ),
									'count'     => $this->bb_count_group_types( $key ),
									'position'  => 16,
								);
							}
						}

						/*
						$query = new WP_Query(
							array(
								'post_type'  => 'bp_group_type',
								'meta_key'   => 'type_id',
								'meta_value' => $key,
							)
						);
						$posts = $query->posts;
						if ( isset( $query->posts ) ) {
							foreach ( $query->posts as $post ) {
								$display = get_post_meta( $post->ID, 'display_as_tab', true );
								$name    = get_post_meta( $post->ID, 'singular_name', true );
								if ( 'on' == $display ) {
									$tabs[ esc_attr( $key ) ] = array(
										'component' => 'groups',
										'slug'      => esc_attr( $key ),
										'li_class'  => array(
											'0' => 'bpgt-type-tab',
										),
										'link'      => bp_get_groups_directory_permalink(),
										'text'      => esc_attr( $name ),
										'count'     => $this->bb_count_group_types( $key ),
										'position'  => 16,
									);
								}
							}
						}
						*/
					endforeach;
				}
			}

			return $tabs;
		}

		/**
		 * Add group type tabs in legacy template.
		 *
		 * @since 1.0.0
		 */
		public function bb_display_directory_tabs() {
			global $bp_grp_types;
			global $bp;
			$group_types        = bp_groups_get_group_types();
			$bp_template_option = bp_get_option( '_bp_theme_package_id' );
			if ( 'legacy' == $bp_template_option ) {
				if ( ! empty( $group_types ) ) {
					foreach ( $group_types as $key => $group_type ) :

						$bp_group_type_term = get_term_by( 'slug', $key, 'bp_group_type' );
						if ( ! empty( $bp_group_type_term ) ) {
							$bp_group_type_id = $bp_group_type_term->term_id;

							$display = get_term_meta( $bp_group_type_id, 'bp_group_type_display_as_tab', true );
							$name    = get_term_meta( $bp_group_type_id, 'bp_type_singular_name', true );

							if ( 1 == $display ) {
								?>
								<li id="groups-<?php echo esc_attr( $key ); ?>" class="bpgt-type-tab" >
									<a href="<?php bp_groups_directory_permalink(); ?>"><?php printf( '%s <span>%d</span>', esc_attr( $name ), esc_attr( $this->bb_count_group_types( $key ) ) ); ?></a>
								</li>
								<?php
							}
						}

						/*
						$query = new WP_Query(
							array(
								'post_type'  => 'bp_group_type',
								'meta_key'   => 'type_id',
								'meta_value' => $key,
							)
						);
						if ( isset( $query->posts ) ) {
							foreach ( $query->posts as $post ) {
								$display = get_post_meta( $post->ID, 'display_as_tab', true );
								$name    = get_post_meta( $post->ID, 'singular_name', true );
								if ( 'on' == $display ) {
									?>
							<li id="groups-<?php echo esc_attr( $key ); ?>" class="bpgt-type-tab" >
								<a href="<?php bp_groups_directory_permalink(); ?>"><?php printf( '%s <span>%d</span>', esc_attr( $name ), esc_attr( $this->bb_count_group_types( $key ) ) ); ?></a>
							</li>
									<?php
								}
							}
						}
						*/
					endforeach;
				}
			}
		}

		/**
		 * Get group count of group type tabs groups.
		 *
		 * @param string $group_type The group type.
		 * @param string $taxonomy The group taxonomy.
		 * @since 1.0.0
		 */
		public function bb_count_group_types( $group_type = '', $taxonomy = 'bp_group_type' ) {
			global $wpdb;
			$group_types = bp_groups_get_group_types();
			if ( empty( $group_type ) || empty( $group_types[ $group_type ] ) ) {
				return false;
			}
			$count_types = wp_cache_get( 'bpex_count_group_types', 'using_gt_bp_group_type' );
			if ( ! $count_types ) {
				if ( ! bp_is_root_blog() ) {
					switch_to_blog( bp_get_root_blog_id() );
				}
				$count_types = $wpdb->get_results( $wpdb->prepare( "SELECT t.slug, tt.count FROM {$wpdb->term_taxonomy} tt LEFT JOIN {$wpdb->terms} t ON tt.term_id = t.term_id WHERE tt.taxonomy = %s", $taxonomy ) );
				wp_cache_set( 'bpex_count_group_types', $count_types, 'using_gt_bp_group_type' );
				restore_current_blog();
			}
			$type_count = wp_filter_object_list( $count_types, array( 'slug' => $group_type ), 'and', 'count' );
			$type_count = array_values( $type_count );
			if ( empty( $type_count ) ) {
				return 0;
			}
			return (int) $type_count[0];
		}

		/**
		 * Get group type args.
		 *
		 * @param array $args The group type.
		 * @since 1.0.0
		 */
		public function bb_set_has_groups_type_arg( $args = array() ) {
			$display_group_types = get_site_option( 'bpgt_type_display_settings' );
			if ( ! empty( $display_group_types ) && is_array( $display_group_types ) ) {
				// Get group types to check scope.
				$group_types = bp_groups_get_group_types();
				// Set the group type arg if scope match one of the registered group type.
				if ( ! empty( $args['scope'] ) && ! empty( $group_types[ $args['scope'] ] ) ) {
					$args['group_type'] = $args['scope'];
				}
			}
			return $args;
		}

		/**
		 * Display group type.
		 *
		 * @param string $group_id The group id.
		 * @since 1.0.0
		 */
		public function bb_group_directory_show_group_type( $group_id = null ) {
			if ( empty( $group_id ) ) {
				$group_id = bp_get_group_id();
			}
			// Group directory.
			if ( bp_is_active( 'groups' ) && bp_is_groups_directory() ) {
				// Passing false means supporting multiple group types.
				$group_types = (array) bp_groups_get_group_type( $group_id, false );
				if ( ! empty( $group_types ) ) {
					$types = array();
					foreach ( $group_types as $type ) {
						$obj = bp_groups_get_group_type_object( $type );
						// Group type name/description.
						if ( ! empty( $obj ) ) {
							array_push( $types, esc_attr( $obj->labels['singular_name'] ) );
						}
					}
					if ( ! empty( $types ) ) {
						$types = implode( ', ', $types );
						echo '<div class="item-meta wb-bpgt-group-types">' . esc_html__( 'Group Types', 'bp-add-group-types' ) . ': ' . $types . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
				}
			}
		}

		/**
		 * Displays shortcode data.
		 *
		 * @since 2.0.0
		 *
		 * @param array $atts Group Object.
		 *
		 * @return false|string
		 */
		public function bp_group_type_short_code( $atts ) {
			global $wpdb;
			ob_start();
			if ( ! empty( $atts['type'] ) ) {
				?>
			<div id="buddypress" class="buddypress-wrap round-avatars bp-dir-hori-nav bp-shortcode-wrap">
				<div class="screen-content">
					<div class="subnav-filters filters no-ajax" id="subnav-filters">
						<?php bp_get_template_part( 'common/filters/grid-filters' ); ?>
					</div>
					<div id="groups-dir-list" class="groups dir-list">
						<?php
						$atts['group_type'] = $atts['type'];

						if ( ! bp_is_root_blog() ) {
							switch_to_blog( bp_get_root_blog_id() );
						}
						$bp_group_type      = 'bp_group_type';
						$bp_atts_group_type = $atts['group_type'];
						$bp_get_group_ids   = $wpdb->get_results( $wpdb->prepare( "SELECT tr.object_id FROM {$wpdb->term_relationships} tr LEFT JOIN {$wpdb->terms} t ON tr.term_taxonomy_id = t.term_id LEFT JOIN {$wpdb->term_taxonomy} tt ON tt.term_id = t.term_id WHERE tt.taxonomy = %s AND t.slug= %s", $bp_group_type, $bp_atts_group_type ) );
						$group_id           = array();
						if ( ! empty( $bp_get_group_ids ) ) {
							foreach ( $bp_get_group_ids as $id ) {
								$group_id[] = $id->object_id;
							}
						}
						restore_current_blog();

						if ( ! empty( $atts['type'] ) ) {

							$name = str_replace( array( ' ', ',' ), array( '-', '-' ), strtolower( $atts['type'] ) );

							// Set the "current" profile type, if one is provided, in member directories.
							buddypress()->groups->current_directory_type = $name;
							buddypress()->current_component              = 'groups';
							buddypress()->is_directory                   = true;
						}

						/*
						unset( $atts['type'] );
						$bp_group_type_query = build_query( $atts );
						if ( ! empty( $bp_group_type_query ) ) {
							$bp_group_type_query = '&' . $bp_group_type_query;
						}

						update_option( 'bp_group_type_short_code_query_build', $bp_group_type_query );

						add_filter(
							'bp_ajax_querystring',
							function ( $qs ) {
								return $qs .= get_option( 'bp_group_type_short_code_query_build' );;
							}
						);
						*/

						// Get a BuddyPress groups-loop template part for display in a theme.
						bp_get_template_part( 'groups/groups-loop' );
						?>
					</div>
				</div>
			</div>
				<?php
			}
			return ob_get_clean();
		}


		/**
		 * Displays shortcode data on group tab.
		 *
		 * @since 2.0.0
		 *
		 * @param array $atts Group Object.
		 *
		 * @return false|string
		 */
		public function bp_group_type_short_code_in_group_tab( $atts ) {
			global $wpdb;
			ob_start();
			$exist_gp_type = bp_groups_get_group_types();
			if ( ! empty( $atts['type'] ) && in_array( $atts['type'], $exist_gp_type ) ) {
				if ( bp_is_group() ) {
					?>
					<div>
						<p>
							<i>
								<?php esc_html_e( 'Viewing groups of the type:', 'bp-add-group-types' ); ?>
								<span><b><?php esc_html_e( $atts['type'], 'bp-add-group-types' ); ?></b></span>
							</i>	
						</p>
					</div>
					<ul id="groups-list" class="<?php bp_nouveau_loop_classes(); ?>">	
						<?php
							$get_group = groups_get_groups(
								array(
									'group_type' => $atts['type'],
									'status'     => array( 'Public', 'Private', 'Hidden' ),
								)
							);
							if ( ! empty( $get_group['groups'] ) ) {
								foreach ( $get_group['groups'] as $get_group_v_k => $get_group_value ) {
						?>
						<li class="item-entry public group-has-avatar" data-bp-item-id="<?php echo esc_attr( $get_group_value->id ); ?>" data-bp-item-component="groups">
								<div class="list-wrap">
									<div class="buddyx-grp-cover-wrapper">
										<div class="buddyx-grp-cover-img">
											<img src="<?php echo esc_attr( bp_get_group_cover_url( $get_group_value->id ) ); ?>">							
										</div>
									</div>
										<?php if ( ! bp_disable_group_avatar_uploads() ) : ?>
											<?php
											$args              = '';
											$group_avatar_args = bp_parse_args(
												$args,
												array(
													'type'    => 'full',
													'width'   => false,
													'height'  => false,
													'class'   => 'avatar',
													'no_grav' => false,
													'html'    => true,
													'id'      => false,
													// translators: %1$s is the name of the group.
													'alt'     => sprintf( __( 'Group logo of %1$s', 'buddypress' ), $get_group_value->name ),
												),
												'get_group_avatar'
											);
											$group_avatar      = bp_core_fetch_avatar(
												array(
													'item_id'    => $get_group_value->id,
													'avatar_dir' => 'group-avatars',
													'object'     => 'group',
													'type'       => $group_avatar_args['type'],
													'html'       => $group_avatar_args['html'],
													'alt'        => $group_avatar_args['alt'],
													'no_grav'    => $group_avatar_args['no_grav'],
													'css_id'     => $group_avatar_args['id'],
													'class'      => $group_avatar_args['class'],
													'width'      => $group_avatar_args['width'],
													'height'     => $group_avatar_args['height'],
												)
											);
											?>
									<div class="item-avatar">
										<a href="<?php echo esc_attr( bp_get_group_permalink( $get_group_value->id ) ); ?>"><?php echo wp_kses_post( $group_avatar ); ?></a>
									</div>
								<?php endif; ?>
								<div class="item">
									<div class="item-block">
										<h2 class="list-title groups-title">
											<a href="<?php echo esc_attr( bp_get_group_permalink( $get_group_value->id ) ); ?>"><?php echo esc_html( $get_group_value->name ); ?></a>	
										</h2>
										<?php if ( bp_nouveau_group_has_meta() ) : ?>
											<p class="item-meta group-details">
												<?php
												$gp_count = BP_Groups_Group::get_total_member_count( $get_group_value->id );
												echo esc_html( ucfirst( $get_group_value->status ) ) . ' Group / ' . esc_html( $gp_count ) . ' members';
												?>
											</p>
										<?php endif; ?>
										<p class="last-activity item-meta">
											<?php
												printf(
												/* translators: %s: last activity timestamp (e.g. "Active 1 hour ago") */
													esc_html__( 'Active %s', 'buddypress' ),
													sprintf(
														'<span data-livestamp="">%1$s</span>',
														esc_html(
															bp_get_group_last_active(
																array(
																	'relative' => true,
																),
																'group_last_active'
															)
														)
													)
												);
											?>
										</p>
										<p>
											<div class="item-meta wb-bpgt-group-types"> 
												<?php
												echo sprintf(
													esc_html__(
														'Group Types: %s',
														'bp-add-group-types'
													),	
													bp_groups_get_group_type( $get_group_value->id ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
												);
												?>
											</div>
										</p>
									</div>
									<div class="group-desc">
										<p>
											<?php
											echo esc_html( substr( $get_group_value->description, 0, 100 ) . '...' );
											?>
										</p>
									</div>
										<?php bp_nouveau_groups_loop_item(); ?>
										<?php bp_nouveau_groups_loop_buttons(); ?>
									</div>
								</div>
							</li>
							<?php
							}
						}
					?>
				</ul>
					<?php
				}
			} else {
				?>
			<div class="bp-invites-feedback">
					<div class="bp-invites-feedback">
						<div class="bp-feedback info">
							<span class="bp-icon" aria-hidden="true"></span>
							<p><?php esc_html_e( 'Sorry, there were no groups found.', 'bp-add-group-types' ); ?></p>
						</div>
					</div>
				</div>
				<?php
			}
			$content = ob_get_contents();
			ob_end_clean();
			return $content;
		}


		/**
		 * Return create screen checked by default on create group type.
		 *
		 * @since 2.4.0
		 *
		 * @param array  $types     group type objects, keyed by name.
		 * @param array  $args      Array of key=>value arguments for filtering.
		 * @param string $operator  'or' to match any of $args, 'and' to require all.
		 *
		 * @return group types object
		 */
		public function bpgt_groups_get_group_types( $types, $args, $operator ) {

			if ( ! empty( $types ) ) {
				foreach ( $types as $key => $value ) {
					$term_id                             = $value->db_id;
					$bp_group_type_create_screen_checked = get_term_meta( $term_id, 'bp_group_type_create_screen_checked', true );

					if ( 1 == $bp_group_type_create_screen_checked ) {
						$types[ $key ]->create_screen_checked = 1;
					}
				}
			}

			return $types;
		}
	}

endif;

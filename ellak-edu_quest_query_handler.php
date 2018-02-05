<?php

/**
 * ellak - Edu_quest query handler.
 *
 * @package     none
 * @author      David Bromoiras
 * @copyright   2018 eellak
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Edu_quest query handler.
 * Plugin URI:  https://github.com/eellak/ellak-edu_quest_query_handler
 * Description: This plugin implements the post request handling that comes from submitting the filter form terms in the questionary page.
 * Version:     1.0
 * Author:      David Bromoiras
 * Author URI:  https://www.anchor-web.gr
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txtd
 *
 **/
if(! function_exists('ellak_edu_quest_query_handler')){
	function ellak_edu_quest_query_handler(){
			$institution=filter_input(INPUT_POST, 'institution', FILTER_SANITIZE_SPECIAL_CHARS);
			$department=filter_input(INPUT_POST, 'department', FILTER_SANITIZE_SPECIAL_CHARS);
			$course=filter_input(INPUT_POST, 'course', FILTER_SANITIZE_SPECIAL_CHARS);

//			$institution=$_POST['institution'];
//			$department=$_POST['department'];
//			$course=$_POST['course'];

			wp_redirect(get_bloginfo()->url."/edu_quest_post_type/?institution=$institution&department=$department&course=$course");
//			error_log('1-2');
	}
}
add_action('admin_post_handle_edu_quest_query', 'ellak_edu_quest_query_handler');
add_action('admin_post_nopriv_handle_edu_quest_query', 'ellak_edu_quest_query_handler');

if(! function_exists('filter_quest_query_by_fields')){
	function filter_quest_query_by_fields($query){
			if($query->is_main_query() && is_post_type_archive('edu_quest_post_type')){
					$tax_query=array('relation'=>'AND');

					if (isset($_GET['institution'])){
							$institution=$_GET['institution'];
							if($institution!=='null_option'){
								
								array_push(
												$tax_query,
												array(
														'key'=>'edu_quest_institution',
														'value'=>$institution
										));
							}
					}

					if (isset($_GET['department'])){
							$department=$_GET['department'];
//							echo $department;
							if($department!=='null_option'){
									array_push(
													$tax_query,
													array(
													'key'=>'edu_quest_department',
													'value'=>$department
											));
							}
					}

					if (isset($_GET['course'])){
							$course=$_GET['course'];
							if($course!=='null_option'){
									array_push(
													$tax_query,
													array(
													'key'=>'edu_quest_course',
													'value'=>$course
											));
							}
					}
					$query->set('orderby', 'title');
					$query->set('order', 'ASC');
					$query->set('posts_per_page', '30');
					$query->set('post_type', 'edu_quest_post_type');
					$query->set('meta_query', $tax_query);
			}
	}
}
add_action('pre_get_posts', 'filter_quest_query_by_fields');

if( !function_exists('add_edu_quest_query_vars')){
	function add_edu_quest_query_vars_filter($vars){
    $vars[]='institution';
    $vars[]='department';
    $vars[]='course';
    return $vars;
	}
}
add_filter('query_vars', 'add_edu_quest_query_vars_filter');

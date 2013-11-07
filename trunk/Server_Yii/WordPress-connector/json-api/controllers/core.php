<?php
/*
Controller name: Core
Controller description: Basic introspection methods
*/

class JSON_API_Core_Controller {
  
  public function info() {
    global $json_api;
    $php = '';
    if (!empty($json_api->query->controller)) {
      return $json_api->controller_info($json_api->query->controller);
    } else {
      $dir = json_api_dir();
      if (file_exists("$dir/json-api.php")) {
        $php = file_get_contents("$dir/json-api.php");
      } else {
        // Check one directory up, in case json-api.php was moved
        $dir = dirname($dir);
        if (file_exists("$dir/json-api.php")) {
          $php = file_get_contents("$dir/json-api.php");
        }
      }
      if (preg_match('/^\s*Version:\s*(.+)$/m', $php, $matches)) {
        $version = $matches[1];
      } else {
        $version = '(Unknown)';
      }
      $active_controllers = explode(',', get_option('json_api_controllers', 'core'));
      $controllers = array_intersect($json_api->get_controllers(), $active_controllers);
      return array(
        'json_api_version' => $version,
        'controllers' => array_values($controllers)
      );
    }
}
 
  
public function login() {
        
    $_POST['pwd'] = urldecode($_POST['pwd']);
    $posts = wp_signon();
    return $this->posts_result($posts);
  }
  
  public function changePassword(){
      $_POST['passowrd'] = urldecode($_POST['passowrd']);
      $posts = wp_set_password($_POST['passowrd'], $_POST['id']);
      return $this->posts_result($posts);
  }

   public function lostPassword(){
       
//       $check_email = email_exists($_POST['user_login']);
       $check_username = username_exists($_POST['user_login']);
//       $posts = $check_email;
       $posts = $check_username;
//       if($posts->posts == 'false'){
           if(empty($posts) || $posts == 'false'){
            return $this->posts_result($posts);
       }else{      	
		global $wpdb, $current_site;
		$errors = new WP_Error();

		if ( empty( $_POST['user_login'] ) ) {
			$errors->add('empty_username', __('<strong>ERROR</strong>: Enter a username or e-mail address.'));
		} else if ( strpos( $_POST['user_login'], '@' ) ) {
			$user_data = get_user_by( 'email', trim( $_POST['user_login'] ) );
			if ( empty( $user_data ) )
				$errors->add('invalid_email', __('<strong>ERROR</strong>: There is no user registered with that email address.'));
			} else {
			$login = trim($_POST['user_login']);
			$user_data = get_user_by('login', $login);
			}

		do_action('lostpassword_post');

		if ( $errors->get_error_code() )
			return $errors;

		if ( !$user_data ) {
			$errors->add('invalidcombo', __('<strong>ERROR</strong>: Invalid username or e-mail.'));
			return $errors;
		}

		// redefining user_login ensures we return the right case in the email
		$user_login = $user_data->user_login;
		$user_email = $user_data->user_email;

		do_action('retreive_password', $user_login);  // Misspelled and deprecated
		do_action('retrieve_password', $user_login);

		$allow = apply_filters('allow_password_reset', true, $user_data->ID);

		if ( ! $allow )
			return new WP_Error('no_password_reset', __('Password reset is not allowed for this user'));
		else if ( is_wp_error($allow) )
			return $allow;

		$key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));
		if ( empty($key) ) {
			// Generate something random for a key...
			$key = wp_generate_password(20, false);
			do_action('retrieve_password_key', $user_login, $key);
			// Now insert the new md5 key into the db
			$wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));
		}
		$message = __('Someone requested that the password be reset for the following account:') . "\r\n\r\n";
		$message .= network_home_url( '/' ) . "\r\n\r\n";
		$message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
		$message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n";
		$message .= __('To reset your password, visit the following address:') . "\r\n\r\n";
		$message .= '<' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . ">\r\n";

		if ( is_multisite() )
			$blogname = $GLOBALS['current_site']->site_name;
		else
		// The blogname option is escaped with esc_html on the way into the database in sanitize_option
		// we want to reverse this for the plain text arena of emails.
		$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

	$title = sprintf( __('[%s] Password Reset'), $blogname );

	$title = apply_filters('retrieve_password_title', $title);
	$message = apply_filters('retrieve_password_message', $message, $key);

	if ( $message && !wp_mail($user_email, $title, $message) )
		wp_die( __('The e-mail could not be sent.') . "<br />\n" . __('Possible reason: your host may have disabled the mail() function.') );
        
        
        
        return $this->posts_result($posts);
       }
	
  }

  public function register() {
    
//showing error of email exixting condition
    $check_email = email_exists($_POST['user_email']);      
    $posts = $check_email;

    if(!$posts){
      // urldecode function is use to decode the $_POST parameter
    $_POST['user_pass'] = urldecode($_POST['user_pass']);
    $posts = wp_insert_user($_POST);
    return $this->posts_result($posts);
    }else{
          return $this->posts_result('Email already exists');
    }
  } 

   public function registorold() {
    
//showing error of email exixting condition
    $check_email = email_exists($_POST['user_email']);      
    $posts = $check_email;

    if(!$posts){
      // urldecode function is use to decode the $_POST parameter
    $_POST['user_pass'] = urldecode($_POST['user_pass']);
    $posts = wp_insert_user($_POST);
    return $this->posts_result($posts);
    }else{
          return $this->posts_result('Email already exists');
    }
  } 
  
  public function get_recent_posts() {
    global $json_api;
    $posts = $json_api->introspector->get_posts();
    return $this->posts_result($posts);
  }
  
  public function get_posts() {
    global $json_api;
    $url = parse_url($_SERVER['REQUEST_URI']);
    $defaults = array(
      'ignore_sticky_posts' => true
    );
    $query = wp_parse_args($url['query']);
    unset($query['json']);
    unset($query['post_status']);
    $query = array_merge($defaults, $query);
    $posts = $json_api->introspector->get_posts($query);
    $result = $this->posts_result($posts);
    $result['query'] = $query;
    return $result;
  }
  
  public function get_post() {
    global $json_api, $post;
    $post = $json_api->introspector->get_current_post();
    if ($post) {
      $previous = get_adjacent_post(false, '', true);
      $next = get_adjacent_post(false, '', false);
      $response = array(
        'post' => new JSON_API_Post($post)
      );
      if ($previous) {
        $response['previous_url'] = get_permalink($previous->ID);
      }
      if ($next) {
        $response['next_url'] = get_permalink($next->ID);
      }
      return $response;
    } else {
      $json_api->error("Not found.");
    }
  }

  public function get_page() {
    global $json_api;
    extract($json_api->query->get(array('id', 'slug', 'page_id', 'page_slug', 'children')));
    if ($id || $page_id) {
      if (!$id) {
        $id = $page_id;
      }
      $posts = $json_api->introspector->get_posts(array(
        'page_id' => $id
      ));
    } else if ($slug || $page_slug) {
      if (!$slug) {
        $slug = $page_slug;
      }
      $posts = $json_api->introspector->get_posts(array(
        'pagename' => $slug
      ));
    } else {
      $json_api->error("Include 'id' or 'slug' var in your request.");
    }
    
    // Workaround for https://core.trac.wordpress.org/ticket/12647
    if (empty($posts)) {
      $url = $_SERVER['REQUEST_URI'];
      $parsed_url = parse_url($url);
      $path = $parsed_url['path'];
      if (preg_match('#^http://[^/]+(/.+)$#', get_bloginfo('url'), $matches)) {
        $blog_root = $matches[1];
        $path = preg_replace("#^$blog_root#", '', $path);
      }
      if (substr($path, 0, 1) == '/') {
        $path = substr($path, 1);
      }
      $posts = $json_api->introspector->get_posts(array('pagename' => $path));
    }
    
    if (count($posts) == 1) {
      if (!empty($children)) {
        $json_api->introspector->attach_child_posts($posts[0]);
      }
      return array(
        'page' => $posts[0]
      );
    } else {
      $json_api->error("Not found.");
    }
  }
  
  public function get_date_posts() {
    global $json_api;
    if ($json_api->query->date) {
      $date = preg_replace('/\D/', '', $json_api->query->date);
      if (!preg_match('/^\d{4}(\d{2})?(\d{2})?$/', $date)) {
        $json_api->error("Specify a date var in one of 'YYYY' or 'YYYY-MM' or 'YYYY-MM-DD' formats.");
      }
      $request = array('year' => substr($date, 0, 4));
      if (strlen($date) > 4) {
        $request['monthnum'] = (int) substr($date, 4, 2);
      }
      if (strlen($date) > 6) {
        $request['day'] = (int) substr($date, 6, 2);
      }
      $posts = $json_api->introspector->get_posts($request);
    } else {
      $json_api->error("Include 'date' var in your request.");
    }
    return $this->posts_result($posts);
  }
  
  public function get_category_posts() {
    global $json_api;
    $category = $json_api->introspector->get_current_category();
    if (!$category) {
      $json_api->error("Not found.");
    }
    $posts = $json_api->introspector->get_posts(array(
      'cat' => $category->id
    ));
    return $this->posts_object_result($posts, $category);
  }
  
  public function get_tag_posts() {
    global $json_api;
    $tag = $json_api->introspector->get_current_tag();
    if (!$tag) {
      $json_api->error("Not found.");
    }
    $posts = $json_api->introspector->get_posts(array(
      'tag' => $tag->slug
    ));
    return $this->posts_object_result($posts, $tag);
  }
  
  public function get_author_posts() {
    global $json_api;
    $author = $json_api->introspector->get_current_author();
    if (!$author) {
      $json_api->error("Not found.");
    }
    $posts = $json_api->introspector->get_posts(array(
      'author' => $author->id
    ));
    return $this->posts_object_result($posts, $author);
  }
  
  public function get_search_results() {
    global $json_api;
    if ($json_api->query->search) {
      $posts = $json_api->introspector->get_posts(array(
        's' => $json_api->query->search
      ));
    } else {
      $json_api->error("Include 'search' var in your request.");
    }
    return $this->posts_result($posts);
  }
  
  public function get_date_index() {
    global $json_api;
    $permalinks = $json_api->introspector->get_date_archive_permalinks();
    $tree = $json_api->introspector->get_date_archive_tree($permalinks);
    return array(
      'permalinks' => $permalinks,
      'tree' => $tree
    );
  }
  
  public function get_category_index() {
    global $json_api;
    $args = null;
    if (!empty($json_api->query->parent)) {
      $args = array(
        'parent' => $json_api->query->parent
      );
    }
    $categories = $json_api->introspector->get_categories($args);
    return array(
      'count' => count($categories),
      'categories' => $categories
    );
  }
  
  public function get_tag_index() {
    global $json_api;
    $tags = $json_api->introspector->get_tags();
    return array(
      'count' => count($tags),
      'tags' => $tags
    );
  }
  
  public function get_author_index() {
    global $json_api;
    $authors = $json_api->introspector->get_authors();
    return array(
      'count' => count($authors),
      'authors' => array_values($authors)
    );
  }
  
  public function get_page_index() {
    global $json_api;
    $pages = array();
    $post_type = $json_api->query->post_type ? $json_api->query->post_type : 'page';
    
    // Thanks to blinder for the fix!
    $numberposts = empty($json_api->query->count) ? -1 : $json_api->query->count;
    $wp_posts = get_posts(array(
      'post_type' => $post_type,
      'post_parent' => 0,
      'order' => 'ASC',
      'orderby' => 'menu_order',
      'numberposts' => $numberposts
    ));
    foreach ($wp_posts as $wp_post) {
      $pages[] = new JSON_API_Post($wp_post);
    }
    foreach ($pages as $page) {
      $json_api->introspector->attach_child_posts($page);
    }
    return array(
      'pages' => $pages
    );
  }
  
  public function get_nonce() {
    global $json_api;
    extract($json_api->query->get(array('controller', 'method')));
    if ($controller && $method) {
      $controller = strtolower($controller);
      if (!in_array($controller, $json_api->get_controllers())) {
        $json_api->error("Unknown controller '$controller'.");
      }
      require_once $json_api->controller_path($controller);
      if (!method_exists($json_api->controller_class($controller), $method)) {
        $json_api->error("Unknown method '$method'.");
      }
      $nonce_id = $json_api->get_nonce_id($controller, $method);
      return array(
        'controller' => $controller,
        'method' => $method,
        'nonce' => wp_create_nonce($nonce_id)
      );
    } else {
      $json_api->error("Include 'controller' and 'method' vars in your request.");
    }
  }
  
  protected function get_object_posts($object, $id_var, $slug_var) {
    global $json_api;
    $object_id = "{$type}_id";
    $object_slug = "{$type}_slug";
    extract($json_api->query->get(array('id', 'slug', $object_id, $object_slug)));
    if ($id || $$object_id) {
      if (!$id) {
        $id = $$object_id;
      }
      $posts = $json_api->introspector->get_posts(array(
        $id_var => $id
      ));
    } else if ($slug || $$object_slug) {
      if (!$slug) {
        $slug = $$object_slug;
      }
      $posts = $json_api->introspector->get_posts(array(
        $slug_var => $slug
      ));
    } else {
      $json_api->error("No $type specified. Include 'id' or 'slug' var in your request.");
    }
    return $posts;
  }
  
  protected function posts_result($posts) {
    global $wp_query;
    return array(
      'count' => count($posts),
      'count_total' => (int) $wp_query->found_posts,
      'pages' => $wp_query->max_num_pages,
      'posts' => $posts
    );
  }
  
  protected function posts_object_result($posts, $object) {
    global $wp_query;
    // Convert something like "JSON_API_Category" into "category"
    $object_key = strtolower(substr(get_class($object), 9));
    return array(
      'count' => count($posts),
      'pages' => (int) $wp_query->max_num_pages,
      $object_key => $object,
      'posts' => $posts
    );
  }
  
}

?>

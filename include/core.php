<?php

use function PHPSTORM_META\elementType;

class MUAP_MPMTI_Core
{
    var $entities = [];
    var $alerts = [];
    public function __construct()
    {
        add_action('admin_enqueue_scripts',  array($this, "styles"));
        add_action('admin_enqueue_scripts', array($this, "scripts"));
        add_action("admin_menu", array($this, "menu"));
        add_action("admin_init", array($this, "permission"));
        add_action('init', array($this, 'wpdocs_load_textdomain'));
        add_action("init", array($this, "permission_front"));
        add_action('in_admin_header', [$this, 'remove_admin_notices']);
        add_action('wp_dashboard_setup', array($this, 'remove_all_metaboxes'), 99);
        add_action('editable_roles', array($this, 'wpse32738_get_editable_roles'), 99);

        // register_activation_hook(MUAP_MPMTI_FILE, array($this, "install"));

    }

    public function wpdocs_load_textdomain()
    {

        $domain = 'muap-mbmti';
        $locale = apply_filters('plugin_locale', determine_locale(), $domain);

        $mofile = $domain . '-' . $locale . '.mo';


        load_textdomain($domain,  MUAP_MPMTI_BASE . 'languages/' . $mofile);
    }

    public function styles()
    {
        wp_enqueue_style(
            'muap-styles-tree',
            MUAP_MPMTI_URI . 'assets/css/tree.css',
            array(),
            1.0
        );

        wp_enqueue_style(
            'muap-styles',
            MUAP_MPMTI_URI . 'assets/css/admin.css?ver=1.0.3',
            array(),
            1.0
        );

        wp_enqueue_style(
            'muap-tab-styles',
            MUAP_MPMTI_URI . 'assets/css/tab.css?ver=1.0.4',
            array(),
            1.0
        );
    }

    public function scripts()
    {
        wp_enqueue_script(
            'muap_tree',
            MUAP_MPMTI_URI . 'assets/js/tree.js',
            array(),
            1,
            false
        );

        wp_enqueue_script(
            'muap_tree_admin',
            MUAP_MPMTI_URI . 'assets/js/admin-tree.js',
            array(),
            1,
            true
        );

        wp_enqueue_script(
            'muap_tab_admin',
            MUAP_MPMTI_URI . 'assets/js/tab.js?ver=1.0.4',
            array(),
            1,
            true
        );
        wp_enqueue_script(
            'muap_admin',
            MUAP_MPMTI_URI . 'assets/js/admin.js?ver=1.0.19',
            array('jquery'),
            1,
            true
        );
    }

    public function dashboard()
    {
    }

    public function role()
    {
        //  global $submenu;
        // var_dump($submenu);
        if (isset($_GET["sub_page"])) {
            $sub_page = sanitize_text_field($_GET["sub_page"]);
            if(!current_user_can($sub_page) && $this->is_admin()==false)
            {
                wp_die(
                    '<h1>' . __('You need a higher level of permission.') . '</h1>',
                    403
                );
                return;
            }

    
            if ($sub_page == 'muap_access') {
                $MUAP_MPMTI_Access = new MUAP_MPMTI_Access;
                $MUAP_MPMTI_Access->index();
            } else if ($sub_page == 'muap_cap') {
                $MUAP_MPMTI_Access = new MUAP_MPMTI_Access;
                $MUAP_MPMTI_Access->cap();
            } else if ($sub_page == 'muap_other_roles') {
                $MUAP_MPMTI_Access = new MUAP_MPMTI_Access;
                $MUAP_MPMTI_Access->AccessOtherRoles();
            }
        } else {
            $MUAP_MPMTI_Role = new MUAP_MPMTI_Role;
            $MUAP_MPMTI_Role->index();
        }
    }

    public function access()
    {
    }
    public function cap()
    {
    }

    public function url()
    {
        // $MUAP_MPMTI_Access = new MUAP_MPMTI_Access;
        // $MUAP_MPMTI_Access->url();
    }

    public function menu()
    {
        add_menu_page(__('Manage Access User', 'muap-mbmti'), __('Manage Access User', 'muap-mbmti'), 'manage_options', 'muap_user_access', array($this, 'role'));
        add_submenu_page('muap_user_access', __('Manage Roles', 'muap-mbmti'), __('Manage Roles', 'muap-mbmti'), 'manage_options', 'muap_role', array($this, 'role'));
        //add_submenu_page('users.php', __('Manage menu access', 'muap-mbmti'), __('Manage menu access', 'muap-mbmti'), 'manage_options', 'muap_access', array($this, 'access'));
        // add_submenu_page('users.php', __('Manage capabilities', 'muap-mbmti'), __('Manage capabilities', 'muap-mbmti'), 'manage_options', 'muap_cap', array($this, 'cap'));
        //add_submenu_page('users.php', __('Manage custom url', 'muap-mbmti'), __('Manage custom url', 'muap-mbmti'), 'manage_options', 'muap_url', array($this, 'url'));
    }


    public function get_url($item)
    {
        $url = menu_page_url($item[2], false);
        if (strlen($url) == 0) {
            $url = admin_url($item[2]);
        }
        $ret = ["title" => $item[0], "slug" => $item[2], "url" => $url];
        return $ret;
    }

    public function is_admin()
    {
        $is_admin = false;

        $user = wp_get_current_user(); // getting & setting the current user 
        $roles = (array) $user->roles; // obtaining the role 

        $role_name = "";

        foreach ($roles as $role) {
            if ($role == "administrator") {
                $is_admin = true;
            }
        }
        return $is_admin;
    }

    public function get_tree_value()
    {
        $tree_value = [];
        $is_admin = false;

        $user = wp_get_current_user(); // getting & setting the current user 
        $roles = (array) $user->roles; // obtaining the role 

        $role_name = "";

        foreach ($roles as $role) {
            if ($role == "administrator") {
                $is_admin = true;
            }

            $role_name = $role;

            $temp_value = get_option('muap_id_' . $role);
            $arr = explode(',', $temp_value);
            foreach ($arr as $item) {
                $tree_value[$item] = $item;
            }
        }

        return ["tree_value" => $tree_value, "is_admin" => $is_admin, "role_name" => $role_name];
    }

    public function get_full_url()
    {
        $server_on = "";
        $server_host = "";
        $server_uri = "";

        if (isset($_SERVER['HTTPS'])) {
            $server_on = sanitize_text_field($_SERVER['HTTPS']);
        }

        if (isset($_SERVER['HTTP_HOST'])) {
            $server_host = sanitize_text_field($_SERVER['HTTP_HOST']);
        }

        if (isset($_SERVER['REQUEST_URI'])) {
            $server_uri = sanitize_text_field($_SERVER['REQUEST_URI']);
        }

        $full_url = ($server_on === 'on' ? "https" : "http") . "://" . $server_host . $server_uri;

        return  $full_url;
    }

    public function permission_front()
    {

        global  $wp;
        $permission1 = true;
        $full_url = $this->get_full_url();


        $get_tree_value = $this->get_tree_value();

        $is_admin = $get_tree_value["is_admin"];

        $role_name = $get_tree_value["role_name"];

        $json_url = get_option('muap_id_url');

        $json_url_role = get_option('muap_id_url_' . $role_name);


        $urls_role = [];
        $urls = [];

        if (strlen($json_url) > 0) {
            $urls = json_decode($json_url, true);
        }

        if (strlen($json_url_role) > 0) {
            $urls_role = json_decode($json_url_role, true);
        }

        foreach ($urls as $key => $url) {
            if ($url["url"] == $full_url || ($url["url"] . '/') == $full_url || ($url["url"]) == ($full_url . '/')) {
                if (!isset($urls_role[$key])) {
                    $permission1 = false;
                }
            }
        }

        if ($permission1 == false && ($is_admin == false)) {
            wp_die(
                '<h1>' . __('You need a higher level of permission.') . '</h1>',
                403
            );
        }
    }

    public function permission()
    {
        global $menu;
        global $submenu, $wp;
        global $pagenow;

        $permission1 = true;

        $full_url = $this->get_full_url();


        $get_tree_value = $this->get_tree_value();

        $tree_value = $get_tree_value["tree_value"];

        $is_admin = $get_tree_value["is_admin"];


        $urls = [];
//var_dump($menu);
        foreach ($menu as $item) {

            if (isset($item[4]) && $item[4] != "wp-menu-separator") {

                $flag = false;
                if (isset($submenu[$item[2]])) {
                    foreach ($submenu[$item[2]] as $sub_item) {

                        // if ($sub_item[2] == "muap_access" || $sub_item[2] == "muap_cap" || $sub_item[2] == "muap_url") {
                        //     $full_url = strtok($full_url, "&");
                        //     remove_submenu_page($item[2], $sub_item[2]);
                        // }

                        if (isset($tree_value[$item[2] . '-' . $sub_item[2]]) || $is_admin) {
                            $flag = true;
                        } else {
                            $get_url = $this->get_url($sub_item);

                            if ($full_url == $get_url["url"]) {
                                $permission1 = false;
                            }

                            remove_submenu_page($item[2], $sub_item[2]);
                        }
                    }
                } else {
                    if (isset($tree_value[$item[2]]) || $is_admin) {
                        $flag = true;
                    } else {
                        $flag = false;
                    }
                }
            }
            if ($flag == false) {
                $get_url = $this->get_url($item);

                if ($full_url == $get_url["url"]) {
                    $permission1 = false;
                }
                remove_menu_page($item[2]);
            }
        }


        if (str_contains($full_url, "post.php") && isset($_GET["post"]) && isset($_GET["action"])) {

            $post_id = sanitize_text_field($_GET["post"]);
            $action = sanitize_text_field($_GET["action"]);
            $post_type = get_post_type($post_id);

            if (!isset($tree_value['muap_post_edit_edit'])  && $action == "edit" && $post_type == "post") {
                $permission1 = false;
            }

            if (!isset($tree_value['muap_post_edit_' . $post_type])  && $action == "edit" && $post_type != "post") {
                $permission1 = false;
            }
        }

        $temp_url = strtok($full_url, '?');

        if ($temp_url == admin_url("edit.php")) {
            $post_type = "post";
            if (isset($_GET["post_type"])) {
                $post_type = sanitize_text_field($_GET["post_type"]);
            }

            if ($post_type == "post") {
                add_filter('post_row_actions', function ($actions) {

                    $MUAP_MPMTI_Core = new MUAP_MPMTI_Core;
                    $get_tree_value = $MUAP_MPMTI_Core->get_tree_value();

                    $tree_value = $get_tree_value["tree_value"];

                    $is_admin = $get_tree_value["is_admin"];

                    if (!isset($tree_value['muap_post_edit_edit']) && $is_admin == false) {
                        unset($actions['edit']);
                        unset($actions['inline hide-if-no-js']);
                    }

                    if (!isset($tree_value['muap_post_delete_edit']) && $is_admin == false) {
                        unset($actions['trash']);
                    }

                    return $actions;
                }, 10, 2);

                add_filter('bulk_actions-' . 'edit-post', function ($actions) {

                    $MUAP_MPMTI_Core = new MUAP_MPMTI_Core;
                    $get_tree_value = $MUAP_MPMTI_Core->get_tree_value();

                    $tree_value = $get_tree_value["tree_value"];

                    $is_admin = $get_tree_value["is_admin"];

                    if (!isset($tree_value['muap_post_edit_edit']) && $is_admin == false) {
                        unset($actions['edit']);
                    }

                    if (!isset($tree_value['muap_post_delete_edit']) && $is_admin == false) {
                        unset($actions['trash']);
                    }

                    return $actions;
                }, 10, 2);
            } else {
                add_filter('post_row_actions', function ($actions) {

                    $MUAP_MPMTI_Core = new MUAP_MPMTI_Core;
                    $get_tree_value = $MUAP_MPMTI_Core->get_tree_value();

                    $tree_value = $get_tree_value["tree_value"];

                    $is_admin = $get_tree_value["is_admin"];

                    $post_type = sanitize_text_field($_GET["post_type"]);

                    if (!isset($tree_value['muap_post_edit_' . $post_type]) && $is_admin == false) {
                        unset($actions['edit']);
                        unset($actions['inline hide-if-no-js']);
                    }

                    if (!isset($tree_value['muap_post_delete_' . $post_type]) && $is_admin == false) {
                        unset($actions['trash']);
                    }

                    return $actions;
                }, 10, 2);

                add_filter('bulk_actions-' . 'edit-post', function ($actions) {
                    $MUAP_MPMTI_Core = new MUAP_MPMTI_Core;
                    $get_tree_value = $MUAP_MPMTI_Core->get_tree_value();

                    $tree_value = $get_tree_value["tree_value"];

                    $is_admin = $get_tree_value["is_admin"];
                    $post_type = sanitize_text_field($_GET["post_type"]);
                    if (!isset($tree_value['muap_post_edit_' . $post_type]) && $is_admin == false) {
                        unset($actions['edit']);
                    }

                    if (!isset($tree_value['muap_post_delete_' . $post_type]) && $is_admin == false) {
                        unset($actions['trash']);
                    }

                    return $actions;
                }, 10, 2);
            }
        }


        if ($permission1 == false && ($is_admin == false)) {
            wp_die(
                '<h1>' . __('You need a higher level of permission.') . '</h1>',
                403
            );
        }
    }

    public function install()
    {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $sql = new MUAP_MPMTI_Sql_Scripts;
        dbDelta($sql->get_install_script());
    }


    public function remove_admin_notices()
    {
        if (!$this->is_admin()) {
            remove_all_actions('network_admin_notices');
            remove_all_actions('user_admin_notices');
            remove_all_actions('admin_notices');
            remove_all_actions('all_admin_notices');
        }
    }
    public function remove_all_metaboxes()
    {
        if (!current_user_can("disable_dashboard_alert_notifi") && !$this->is_admin()) {
            global $wp_meta_boxes;
            global $ViewData;

            $wp_meta_boxes['dashboard']['normal']['core'] = array();
            $wp_meta_boxes['dashboard']['side']['core'] = array();
            $wp_meta_boxes['dashboard'] = [];
        }
    }
    public function wpse32738_get_editable_roles($editable_roles)
    {
        if (!$this->is_admin()) {
            global $wp_roles;
            $all_roles = $wp_roles->roles;



            $user = wp_get_current_user(); // getting & setting the current user 
            $roles = (array) $user->roles; // obtaining the role 

            $access_roles = [];


            foreach ($roles as $role) {

                $str = get_option('access_to_other_roles_' . $role, '[]');

                $current_role = json_decode($str, true);
                foreach ($current_role as $key => $in_role) {
                    $access_roles[$key] = $key;
                }
            }

            foreach ($all_roles as $key => $role) {
                if (!isset($access_roles[$key])) {
                    unset($editable_roles[$key]);
                }
            }
        }

        return $editable_roles;
    }
}

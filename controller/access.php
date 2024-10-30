<?php

use MailPoet\Config\Capabilities;

class MUAP_MPMTI_Access
{
    public function index()
    {
        global $menu;
        global $submenu;
        global $wp;
        global $wp_roles;
        //var_dump($submenu);
        $all_roles = $wp_roles->roles;

        $cap_admin = $this->get_admin_cap();

        $role_name = '';
        if (isset($_GET["id"])) {
            $role_name = sanitize_text_field($_GET["id"]);
        }

        if ($role_name == 'administrator') {
            return;
        }

        $tree_value = '[]';
        $current_capabilities = [];

        $current_role = $wp_roles->roles[$role_name];

        if (isset($current_role["capabilities"])) {
            $current_capabilities = $current_role["capabilities"];
        }

        if (isset($_POST["muap-tree"])) {
            $tree_value = sanitize_text_field($_POST["muap-tree"]);
            update_option('muap_id_' . $role_name, $tree_value);

            $cap = $this->get_admin_cap();

            if (isset($current_role["capabilities"]['plugin']) && $current_role["capabilities"]['plugin'] == "muap") {
                $cap['plugin'] = "muap";
            }

            $is_cap_value = get_option('muap_id_cap_' . $role_name);

            if ($is_cap_value != "yes") {
                $role = get_role($role_name);
                foreach ($cap as $key => $item) {
                    // Add a new capability.
                    //   $role->add_cap($key, true);
                }
            }


            $current_role = $wp_roles->roles[$role_name];
        }

        $temp_value = get_option('muap_id_' . $role_name);

        if (strlen($temp_value) > 0) {
            $tree_value = $temp_value;
        }
        $temp_tree = explode(",", $tree_value);

        $tree_json = json_encode($temp_tree);

        $tree = [];

        foreach ($menu as $item) {

            if ($item[4] != "wp-menu-separator") {
                if ($item[2] == '') {
                    continue;
                }
                $tree_ch = [];
                if (isset($submenu[$item[2]])) {
                    foreach ($submenu[$item[2]] as $sub_item) {

                        $tree_ch[] = ["id" => $item[2] . '-' . $sub_item[2], "text" => strip_tags($sub_item[0]), "children" => []];
                        // if ($sub_item[2] == "muap_role") {
                        //     $tree_ch[] = ["id" => $item[2] . '-' . "muap_access", "text" => esc_html(__('Manage menu access', 'muap-mbmti')), "children" => []];
                        //     $tree_ch[] = ["id" => $item[2] . '-' . "muap_cap", "text" => esc_html(__('Manage capabilities', 'muap-mbmti')), "children" => []];
                        //     $tree_ch[] = ["id" => $item[2] . '-' . "muap_url", "text" => esc_html(__('Manage custom url', 'muap-mbmti')), "children" => []];
                        // }

                        if (str_contains($sub_item[2], 'edit.php?post_type=')) {
                            $tree_ch[] = ["id" =>  "muap_post_edit_" . str_replace('edit.php?post_type=', '', $sub_item[2]), "text" => strip_tags(esc_html(__('Edit', 'muap-mbmti')) . ' ' . strip_tags($sub_item[0])), "children" => []];
                            $tree_ch[] = ["id" =>  "muap_post_delete_" . str_replace('edit.php?post_type=', '', $sub_item[2]), "text" => strip_tags(esc_html(__('Delete', 'muap-mbmti')) . ' ' . strip_tags($sub_item[0])), "children" => []];
                        } else if ($sub_item[2] == 'post-new.php') {
                            $tree_ch[] = ["id" =>  "muap_post_edit_edit", "text" => strip_tags(esc_html(__('Edit', 'muap-mbmti')) . ' ' . esc_html(__('Post'))), "children" => []];
                            $tree_ch[] = ["id" =>  "muap_post_delete_edit", "text" => strip_tags(esc_html(__('Delete', 'muap-mbmti')) . ' ' . esc_html(__('Post'))), "children" => []];
                        }
                    }
                }
                $str = explode('<', $item[0]);
                if (strlen($str[0]) > 0) {
                    $tree[] = ["id" => $item[2], "text" => $str[0], "children" => $tree_ch];
                }
            }
        }

        // $tree=[];
        // $tree[] = ["id" => 'node-1', "text" => 'node 1', "children" => []];
        // $tree[] = ["id" => 'node-2', "text" => 'node 1', "children" => []];
        // $tree[] = ["id" => 'node-3', "text" => 'node 1', "children" => []];

        $data = json_encode($tree);
        $HeaderTitle = sprintf(__('Manage menu access for %s role', 'muap-mbmti'), $role_name);

?>
        <div class="wrap">
            <h1 class="wp-heading-inline"><?php echo esc_html($HeaderTitle) ?></h1>
            <a href="?page=muap_role" class="button"><?php echo esc_html(__('Back')) ?></a>

            <div class="muap-content">
                <form style="background-color: #fff;" method="POST" action="?page=muap_user_access&sub_page=muap_access&id=<?php echo esc_html($role_name) ?>">
                    <p><?php echo esc_html(__('To allow access to any part, select it and finally save', 'muap-mbmti'))  ?></p>
                    <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo esc_html(__('Save', 'muap-mbmti')) ?>"></p>

                    <div class="muap-tree"></div>
                    <input id="muap-tree" name="muap-tree" value="" type="hidden" />
                    <input id="muap-tree-data" name="muap-tree-data" value="<?php echo esc_html($data) ?>" type="hidden" />
                    <input id="muap-tree-value" name="muap-tree-value" value="<?php echo esc_html($tree_json); ?>" type="hidden" />
                </form>
            </div>
        </div>
    <?php
    }

    function get_meta_boxes($screen = 'dashboard', $context = 'advanced')
    {
        global $wp_meta_boxes;

        if (empty($screen))
            $screen = get_current_screen();
        elseif (is_string($screen))
            $screen = convert_to_screen($screen);


        $page = $screen->id;

        //  var_dump($wp_meta_boxes);

        return $wp_meta_boxes[$page];
    }

    public function get_admin_cap()
    {
        global $wp_roles;

        $all_roles = $wp_roles->roles;

        $cap_admin = $all_roles["administrator"]["capabilities"];
        $cap_admin["muap_access"] = true;
        $cap_admin["muap_cap"] = true;
        $cap_admin["muap_other_roles"] = true;
        $cap_admin["disable_dashboard_alert_notifi"] = true;
        return $cap_admin;
    }

    public function AccessOtherRoles()
    {


        global $wp_roles;
        $all_roles = $wp_roles->roles;

        $role_name = '';
        if (isset($_GET["id"])) {
            $role_name = sanitize_text_field($_GET["id"]);
        }

        if (isset($_POST["update_other_roles"])) {
            $arr = [];

            foreach ($all_roles as $key => $item) {
                if (isset($_POST["role_" . $key])) {
                    $arr[$key] = $key;
                }
            }
            update_option('access_to_other_roles_' . $role_name, json_encode($arr));
        }

        $str = get_option('access_to_other_roles_' . $role_name, '[]');

        $current_role = json_decode($str, true);
    ?>

        <h1 class="wp-heading-inline"><?php echo esc_html(__('Manage Access Other Roles', 'muap-mbmti').' '.$role_name) ?></h1>
        <a href="?page=muap_role" class="button"><?php echo esc_html(__('Back')) ?></a>
        <div style="display: block;" class="muap-content">
            <form style="background-color: #fff;padding:10px" method="POST" action="?page=muap_user_access&sub_page=muap_other_roles&id=<?php echo esc_html($role_name) ?>">
                <input type="hidden" id="update_other_roles" name="update_other_roles" />
                <p class="submit"><input style="font-size: 16px;width:200px" type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo esc_html(__('Save', 'muap-mbmti')) ?>"></p>
                <ul class="capabilities-checkbox-ul">

                    <?php
                    foreach ($all_roles as $key => $item) {
                        if ($key == "administrator" || $key == $role_name) {
                            continue;
                        }
                    ?>

                        <li class="capabilities-checkbox-li">
                            <input <?php echo esc_html((isset($current_role[$key]) && $current_role[$key] == true) ? 'checked' : ''); ?> id="<?php echo esc_html('role_' . $key) ?>" name="<?php echo esc_html('role_' . $key) ?>" type="checkbox" />
                            <label><?php echo esc_html($item["name"]);
                                    echo ' ' . '(' . esc_html($key) . ')' ?></label>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
            </form>
        </div>
    <?php
    }

    public function cap()
    {
        global $menu;
        global $submenu;
        global $wp;
        global $wp_roles;

        $cap_admin = $this->get_admin_cap();

        $role_name = '';
        if (isset($_GET["id"])) {
            $role_name = sanitize_text_field($_GET["id"]);
        }

        if ($role_name == 'administrator') {
            return;
        }

        $current_capabilities = [];

        $current_role = $wp_roles->roles[$role_name];

        if (isset($current_role["capabilities"])) {
            $current_capabilities = $current_role["capabilities"];
        }

        if (isset($_POST["muap-cap-value"])) {

            $role = get_role($role_name);

            foreach ($current_capabilities as $key => $item) {
                if ($key != 'plugin') {
                    $role->add_cap($key, false);
                }
            }

            foreach ($_POST as $key => $item) {
                $role->add_cap($key, true);
            }

            update_option('muap_id_cap_' . $role_name, 'yes');
        }


        $current_role = $wp_roles->roles[$role_name];

        if (isset($current_role["capabilities"])) {
            $current_capabilities = $current_role["capabilities"];
        }

        $HeaderTitle = sprintf(__('Manage capabilities for %s role', 'muap-mbmti'), $role_name);

    ?>
        <div class="wrap">
            <h1 class="wp-heading-inline"><?php echo esc_html($HeaderTitle) ?></h1>
            <a href="?page=muap_role" class="button"><?php echo esc_html(__('Back')) ?></a>
            <div style="display: block;" class="muap-content">
                <form id="fomr-access-cap" style="background-color: #fff;padding:10px" method="POST" action="?page=muap_user_access&sub_page=muap_cap&id=<?php echo esc_html($role_name) ?>">
                    <p><?php echo esc_html(__('To allow access to any part, select it and finally save', 'muap-mbmti'))  ?></p>
                    <p class="submit"><input style="font-size: 16px;width:200px" type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo esc_html(__('Save', 'muap-mbmti')) ?>"></p>
                    <ul class="capabilities-checkbox-ul">
                        <?php

                        // var_dump($this->get_meta_boxes());
                        include 'cap.php';
                        $cap_item = [];
                        $cap_item["others"] = [];
                        // var_dump($cap_admin);
                        $cap_item['wordpress_core'] = [];
                        $cap_item['wordpress_core']['manage_options'] = 'manage_options';
                        $cap_item['wordpress_core']['read'] = 'read';
                        foreach ($cap_admin as $key => $item) {
                            if ($key == "read" || $key == 'manage_options') {
                                continue;
                            }
                            if (isset($caps[$key])) {
                                if (!isset($cap_item[$caps[$key]])) {
                                    $cap_item[$caps[$key]] = [];
                                }
                                $cap_item[$caps[$key]][$key] = $key;
                            } else {
                                $cap_item["others"][$key] = $key;
                            }
                        }

                        ?>
                        <div class="mbm-tab">
                            <?php
                            $active = "active";
                            foreach ($group_caps as $group_key => $group) {
                            ?>
                                <button data-id="1" type="button" class="tablinks <?php echo $active ?>" onclick="openCity(event, '<?php echo $group_key ?>','mbm-tabcontent')"><?php echo $group["title"] ?></button>
                            <?php
                                $active = "";
                            }
                            ?>
                        </div>
                        <?php
                        $active = "display:block;";
                        $index = 1;
                        foreach ($group_caps as $group_key => $group) {
                            $index++;
                            echo '<div data-id="1" style="' . $active . '" id="' . $group_key . '" class="mbm-tabcontent">';
                            $active = "display:none;";
                            //  echo '  <h3>' . $group["title"] . '</h3>';
                            echo '<ul class="capabilities-checkbox-ul">';

                            echo '<div class="mbm-tab">';
                            $active = "active";

                            foreach ($group["childs"] as $child_key => $child) {

                        ?>
                                <button data-id="<?php echo $index ?>" type="button" class="tablinks  <?php echo $active ?>" onclick="openCity(event, '<?php echo $group_key . '_' . $child_key ?>','mbm-tabcontent-1')"><?php echo $child["title"] ?></button>
                                <?php
                                $active = "";
                            }
                            echo '</div>';
                            $active = "display:block;";
                            foreach ($group["childs"] as $child_key => $child) {
                                echo '<div data-id="' . $index . '" style="' . $active . '" id="' . $group_key . '_' . $child_key . '" class="mbm-tabcontent-1">';
                                $active = "display:none;";
                                //  echo '<h3>' . $child["title"] . '</h3>';
                                echo '<ul class="capabilities-checkbox-ul">';
                                foreach ($cap_item[$child_key] as $key => $item) {
                                ?>
                                    <li style="<?php if ($key == 'edit_posts') echo 'display:none' ?>" class="capabilities-checkbox-li">
                                        <input <?php echo esc_html((isset($current_capabilities[$key]) && $current_capabilities[$key] == true) ? 'checked' : ''); ?> id="<?php echo esc_html($key) ?>" name="<?php echo esc_html($key) ?>" type="checkbox" /> <label><?php echo esc_html(__($key, 'muap-mbmti'));
                                                                                                                                                                                                                                                                        if (__($key, 'muap-mbmti') != $key) echo ' ' . '(' . esc_html($key) . ')' ?></label>
                                    </li>
                        <?php
                                }

                                echo '</ul>';
                                echo '</div>';
                            }

                            echo '</div>';
                        }

                        ?>
                    </ul>
                    <input id="muap-cap-value" name="muap-cap-value" value="0" type="hidden" />
                </form>
            </div>
        </div>
    <?php

    }

    public function url()
    {
        global $wp_roles;


        $role_name = "";
        $role_title = "";

        $url_name = "";
        $url_title = "";

        $message = "";
        $message_type = "updated";
        $all_roles = $wp_roles->roles;

        $urls = [];
        $urls_role = [];

        if (isset($_GET["id"])) {
            $role_name = sanitize_text_field($_GET["id"]);
        }

        $json_url = get_option('muap_id_url');
        $json_url_role = get_option('muap_id_url_' . $role_name);



        if (strlen($json_url) > 0) {
            $urls = json_decode($json_url, true);
        }

        if (strlen($json_url_role) > 0) {
            $urls_role = json_decode($json_url_role, true);
        }



        if (isset($_GET["action"])) {


            if (isset($all_roles[$role_name])) {
                $role_title = $all_roles[$role_name]['name'];

                $action = sanitize_text_field($_GET["action"]);
                $url_code = sanitize_text_field($_GET["url"]);
                if (isset($urls[$url_code])) {
                    $url_name = $urls[$url_code]["url"];
                    $url_title = $urls[$url_code]["name"];
                }


                if ($action == "delete") {
                    unset($urls[$url_code]);
                    update_option('muap_id_url', json_encode($urls));
                    $message = __('url has been successfully removed', 'muap-mbmti');
                }
            }

            if ($action == "save") {
                $urls_role = [];
                foreach ($_POST as $key => $item) {
                    $urls_role[$key] = $key;
                }
                //var_dump($urls_role);
                update_option('muap_id_url_' . $role_name, json_encode($urls_role));
            }
        }

        if (isset($_POST['url-name'])) {
            $url_name = sanitize_text_field($_POST["url-name"]);
            $url_title = sanitize_text_field($_POST["url-title"]);
            $url_code = hash("sha256", $url_title);

            if (isset($urls[$url_title])) {
                $urls[$url_code] = ["name" => $url_title, "url" => $url_name, "code" => $url_code];
                $message = sprintf(__('%s url has been successfully updated', 'muap-mbmti'), $url_title);
            } else {
                $urls[$url_code] = ["name" => $url_title, "url" => $url_name, "code" => $url_code];
                $message = sprintf(__('%s url has been successfully added', 'muap-mbmti'), $url_title);
            }
            update_option('muap_id_url', json_encode($urls));
        }
        $HeaderTitle = sprintf(__('Manage custom url for %s role', 'muap-mbmti'), $role_name);
    ?>
        <div class="wrap">
            <h1 class="wp-heading-inline"><?php echo esc_html($HeaderTitle) ?></h1>
            <a href="?page=muap_role" class="button"><?php echo esc_html(__('Back')) ?></a>
            <div class="muap-content">
                <?php if (strlen($message) > 0) { ?>
                    <div class="<?php echo esc_html($message_type) ?> wpp-message">
                        <p><?php echo esc_html($message) ?></p>
                    </div>
                <?php } ?>
                <div class="box box30">
                    <form method="POST" action="?page=muap_user_access&sub_page=muap_url&action=new&id=<?php echo esc_html($role_name)  ?>">
                        <hr>
                        <h4><?php echo esc_html(__("Add or edit url", 'muap-mbmti')) ?></h4>
                        <div class="form-wrap">
                            <div class="form-field form-required term-name-wrap">
                                <label for="url-name"><?php echo esc_html(__('Url', 'muap-mbmti')) ?></label>
                                <input name="url-name" type="text" id="url-name" value="<?php echo esc_html($url_name) ?>" class="">
                            </div>
                            <div class="form-field form-required term-name-wrap">
                                <label for="url-title"><?php echo esc_html(__('Url title', 'muap-mbmti')) ?></label>
                                <input name="url-title" type="text" id="url-title" value="<?php echo esc_html($url_title) ?>" class="">
                            </div>
                        </div>
                        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo esc_html(__('Save url', 'muap-mbmti')) ?>"></p>
                    </form>
                </div>
                <div class="box box70">
                    <hr>
                    <form method="POST" action="?page=muap_user_access&sub_page=muap_url&action=save&id=<?php echo esc_html($role_name)  ?>">
                        <p><?php echo esc_html(__('To allow access to any part, select it and finally save', 'muap-mbmti'))  ?></p>

                        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo esc_html(__('Save access user', 'muap-mbmti')) ?>"></p>

                        <table class="list">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th><?php echo esc_html(__('Row', 'muap-mbmti')) ?></th>
                                    <th><?php echo esc_html(__('Title', 'muap-mbmti')) ?></th>
                                    <th><?php echo esc_html(__('Url', 'muap-mbmti')) ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $index = 0;
                                foreach ($urls as $key => $item) {
                                    $index++;
                                    $hash = $key;
                                ?>
                                    <tr>
                                        <td><input <?php if (isset($urls_role[$hash])) echo 'checked'; ?> id="<?php echo esc_html($hash) ?>" name="<?php echo esc_html($hash) ?>" type="checkbox" /></td>
                                        <td><?php echo esc_html($index) ?></td>
                                        <td><?php echo esc_html($item["name"]) ?></td>
                                        <td style="max-width: 200px;overflow: hidden;"><?php echo esc_html($item["url"]) ?></td>
                                        <td>
                                            <a class="button" href="?page=muap_url&action=edit&id=<?php echo esc_html($role_name)  ?>&url=<?php echo esc_html($item["code"])  ?>"><?php echo esc_html(__('Edit', 'muap-mbmti')) ?></a>
                                            <a class="button" href="?page=muap_url&action=delete&id=<?php echo esc_html($role_name)  ?>&url=<?php echo esc_html($item["code"])  ?>"><?php echo esc_html(__('Delete', 'muap-mbmti')) ?></a>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>

                    </form>
                </div>
            </div>
        </div>
<?php
    }
}
?>
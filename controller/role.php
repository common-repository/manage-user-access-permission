<?php

use function PHPSTORM_META\elementType;

class MUAP_MPMTI_Role
{
    function index()
    {
        global $wp_roles;

        $all_roles = $wp_roles->roles;
        // var_dump($all_roles["administrator"]["capabilities"]);




        $role_name = "";
        $role_title = "";
        $message = "";
        $message_type = "updated";
        $test_btn=esc_html(__('Create role', 'muap-mbmti'));

        if (isset($_GET["action"])) {
            $role_name = sanitize_text_field($_GET["id"]);

            if (isset($all_roles[$role_name])) {
                $role_title = $all_roles[$role_name]['name'];
                $test_btn=esc_html(__('Edit role', 'muap-mbmti'));

                $action = sanitize_text_field($_GET["action"]);

                if ($action == "delete") {
                    if (isset($all_roles[$role_name]['capabilities']) && isset($all_roles[$role_name]['capabilities']['plugin']) && $all_roles[$role_name]['capabilities']['plugin'] == "muap") {
                        $wp_roles->remove_role($role_name);
                        $all_roles = $wp_roles->roles;
                        $message = sprintf(__('%s role has been successfully removed', 'muap-mbmti'), $role_name);
                    } else {
                        $message_type = "error";
                        $message = sprintf(__('%s role cannot be deleted, it is a system role', 'muap-mbmti'), $role_name);
                    }
                }
            }
        }


        if (isset($_POST['role-name'])) {
            $role_name = sanitize_text_field($_POST["role-name"]);
            $role_title = sanitize_text_field($_POST["role-title"]);

            if (isset($all_roles[$role_name])) {
                $wp_roles->remove_role($role_name);
                $message = sprintf(__('%s role has been successfully updated', 'muap-mbmti'), $role_name);
            } else {
                $message = sprintf(__('%s role has been successfully added', 'muap-mbmti'), $role_name);
            }

            //  $cap=$all_roles["administrator"]["capabilities"];
            if(strlen(trim($role_name))==0 || strlen(trim($role_title))==0)
            {
                $message_type = "error";
                $message = __('Role name & Role title required!', 'muap-mbmti');
            }
            else
            {
                $cap = [];
                $cap['plugin'] = "muap";
                $result = add_role(
                    $role_name,
                    $role_title,
                    $cap
                );
    
                $role = get_role($role_name);
                $role->add_cap('read', true);
                $role->add_cap('edit_posts', true);
                $role->add_cap('manage_options', true);
                $role->add_cap('disable_dashboard_alert_notifi', true);
                
                
            }


            $all_roles = $wp_roles->roles;
        }

?>
        <div style="padding-bottom:250px ;" class="wrap">
            <h1 class="wp-heading-inline"><?php echo esc_html(__('Manage role', 'muap-mbmti')) ?></h1>
            <div class="muap-content">
                <?php if (strlen($message) > 0) { ?>
                    <div class="<?php echo esc_html($message_type) ?> wpp-message">
                        <p><?php echo esc_html($message) ?></p>
                    </div>
                <?php } ?>
                <div class="box box30">
                    <form method="POST" action="?page=muap_role">
                        <div class="form-wrap">
                            <div class="form-field form-required term-name-wrap">
                                <label for="role-name"><?php echo esc_html(__('Role name (English letters)', 'muap-mbmti')) ?></label>
                                <input name="role-name" type="text" id="role-name" value="<?php echo esc_html($role_name) ?>" class="" placeholder="<?php echo esc_html(__('Role name here', 'muap-mbmti')) ?>">
                            </div>
                            <div class="form-field form-required term-name-wrap">
                                <label for="role-title"><?php echo esc_html(__('Role title (optional characters)', 'muap-mbmti')) ?></label>
                                <input name="role-title" type="text" id="role-title" value="<?php echo esc_html($role_title) ?>" class="" placeholder="<?php echo esc_html(__('Role title here', 'muap-mbmti')) ?>">
                            </div>
                        </div>
                        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo $test_btn ?>"></p>
                    </form>
                </div>
                <div class="box box70">
                    <table class="list">
                        <thead>
                            <tr>
                                <th><?php echo esc_html(__('Row', 'muap-mbmti')) ?></th>
                                <th><?php echo esc_html(__('Name', 'muap-mbmti')) ?></th>
                                <th><?php echo esc_html(__('Title', 'muap-mbmti')) ?></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $index = 0;
                            foreach ($all_roles as $key => $item) {

                                if ($key == 'administrator') {
                                    continue;
                                }
                                // if (isset($all_roles[$key]['capabilities']) && isset($all_roles[$key]['capabilities']['plugin'])&& $all_roles[$key]['capabilities']['plugin']=="muap") 
                                // {

                                // }
                                // else
                                // {
                                //     continue;  
                                // }
                                $index++;
                            ?>
                                <tr>
                                    <td><?php echo esc_html($index) ?></td>
                                    <td><?php echo esc_html($key) ?></td>
                                    <td><?php echo esc_html($item["name"]) ?></td>
                                    <td class="menu-td">
                                        <a class="button" href="?page=muap_role&action=edit&id=<?php echo esc_html($key)  ?>"><?php echo esc_html(__('Edit', 'muap-mbmti')) ?></a>
                                        <a class="button" href="?page=muap_role&action=delete&id=<?php echo esc_html($key)  ?>"><?php echo esc_html(__('Delete', 'muap-mbmti')) ?></a>
                                        <button data-key="<?php echo $key ?>" onclick="GetContextMenuRole(jQuery(this))" class="button role-context-menu"><?php echo esc_html(__('Manage Access', 'muap-mbmti')) ?></button>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>



            </div>
        </div>
        <div id="role-context-menu" class="dropdown clearfix">
            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                <li><a onclick="RedContextMenuRoleobj(jQuery(this))" class="" href="#role-context-menu" data-key="0" data-href="?page=muap_user_access&sub_page=muap_access"><?php echo esc_html(__('Manage Access Dashboard Admin', 'muap-mbmti')) ?></a>
                </li>
                <li><a onclick="RedContextMenuRoleobj(jQuery(this))" class="" href="#role-context-menu" data-key="0" data-href="?page=muap_user_access&sub_page=muap_cap"><?php echo esc_html(__('Manage Access Actions & Capabilities', 'muap-mbmti')) ?></a>
                </li>
                <li><a onclick="RedContextMenuRoleobj(jQuery(this))" class="" href="#role-context-menu" data-key="0" data-href="?page=muap_user_access&sub_page=muap_other_roles"><?php echo esc_html(__('Manage Access Other Roles', 'muap-mbmti')) ?></a>
                </li>
            </ul>
        </div>
        <style>
            #role-context-menu {
                position: absolute;
                display: none;
            }

            #role-context-menu .dropdown-menu {
                display: block;
                position: static;
                margin-bottom: 5px;
                top: 100%;
                left: 0;
                z-index: 1000;
                float: right;
                min-width: 160px;
                padding: 5px 10px 5px 0;
                margin: 2px 0 0;
                font-size: 14px;
                text-align: right;
                list-style: none;
                background-color: #fff;
                -webkit-background-clip: padding-box;
                background-clip: padding-box;
                border: 1px solid #ccc;
                border: 1px solid rgba(0, 0, 0, .15);
                border-radius: 4px;
                -webkit-box-shadow: 0 6px 12px rgb(0 0 0 / 18%);
                box-shadow: 0 6px 12px rgb(0 0 0 / 18%);
            }

            .menu-td {}

            #role-context-menu .dropdown-menu>li>a {
                display: block;
                padding: 3px 3px;
                clear: both;
                font-weight: 400;
                line-height: 1.42857143;
                color: #333;
                white-space: nowrap;
                text-decoration: none;
            }

            #role-context-menu .dropdown-menu>li>a:hover {
                color: #262626;
                text-decoration: none;
                background-color: #f5f5f5;
            }
        </style>
<?php
    }
}

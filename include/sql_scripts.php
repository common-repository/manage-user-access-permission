<?php
class MUAP_MPMTI_Sql_Scripts
{
  public function get_install_script()
  {
    global $wpdb;
    $table_name = $wpdb->prefix . "muap_mbmti";
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
                `id` BIGINT(18) NOT NULL AUTO_INCREMENT,
                `role_title` varchar(500) CHARACTER SET utf8,
                `slug_main_menu` varchar(500) CHARACTER SET utf8,
                `slug_sub_menu` varchar(500) CHARACTER SET utf8,
                `is_menu` INT(11),
                `post_type` varchar(500) CHARACTER SET utf8,
                `action` varchar(500) CHARACTER SET utf8,
                `status` INT(11),
                PRIMARY KEY (`id`)
              )ENGINE=InnoDB $charset_collate; ";
    return $sql;
  }
}

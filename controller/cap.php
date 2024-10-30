<?php
    $post_core=["title"=>__("posts")];
    $page_core=["title"=>__("pages", 'muap-mbmti')];
    $users_core=["title"=>__("users", 'muap-mbmti')];
    $wordpress_core_1=["title"=>__("Wordpress core", 'muap-mbmti')];


    $wordpress_core=["title"=>__('Wordpress core', 'muap-mbmti'),"childs"=>[]];

    $wordpress_core["childs"]["wordpress_core"]=$wordpress_core_1;
    $wordpress_core["childs"]["post_core"]=$post_core;
    $wordpress_core["childs"]["page_core"]=$page_core;
    $wordpress_core["childs"]["users_core"]=$users_core;

    $woo_1=["title"=>__('Woocommerce', 'muap-mbmti')];
    $product=["title"=>__('Products', 'muap-mbmti')];
    $order=["title"=>__('Orders', 'muap-mbmti')];
    $dokan=["title"=>__('Dokan', 'muap-mbmti')];
    $coupon=["title"=>__('Coupon', 'muap-mbmti')];

    $woo=["title"=>__('Woocommerce', 'muap-mbmti'),"childs"=>[]];
    $woo["childs"]["woo"]=$woo_1;
    $woo["childs"]["product"]=$product;
    $woo["childs"]["order"]=$order;
    $woo["childs"]["dokan"]=$dokan;
    $woo["childs"]["coupon"]=$coupon;

    $manage_access_user=["title"=>__('Manage Access User', 'muap-mbmti')];
    $seo=["title"=>__('plugin yoast', 'muap-mbmti')];
    $loco_core=["title"=>__('plugin loco', 'muap-mbmti')];
    
    $other_plugin=["title"=>__('other plugins', 'muap-mbmti'),"childs"=>[]];
    $other_plugin["childs"]["manage_access_user"]=$manage_access_user;
    $other_plugin["childs"]["seo"]=$seo;
    $other_plugin["childs"]["loco_core"]=$loco_core;

    $others_1=["title"=>__('Others', 'muap-mbmti')];
    $other_plugin["childs"]["others"]=$others_1;

$group_caps=[];
$group_caps["wordpress_core"]=$wordpress_core;
$group_caps["woo"]=$woo;

$group_caps["other_plugin"]=$other_plugin;

$caps=[
    'manage_options' =>  'wordpress_core'
    ,'read' =>  'wordpress_core'
,'switch_themes' =>  'wordpress_core'
,'edit_themes' =>  'wordpress_core'
,'activate_plugins' =>  'wordpress_core'
,'edit_plugins' =>  'wordpress_core'
,'edit_users' =>  'users_core'
,'edit_files' =>  'wordpress_core'
,'moderate_comments' => 'wordpress_core'
,'manage_categories' =>  'wordpress_core'
,'manage_links' =>  'wordpress_core'
,'upload_files' =>  'wordpress_core'
,'import' =>  'wordpress_core'
,'unfiltered_html' =>  'wordpress_core'
,'edit_posts' =>  'post_core'
,'edit_others_posts' =>  'post_core'
,'edit_published_posts' =>  'post_core'
,'publish_posts' =>  'post_core'
,'edit_pages' =>  'page_core'
,'level_10' =>  1
,'level_9' =>  1
,'level_8' =>  1
,'level_7' =>  1
,'level_6' =>  1
,'level_5' =>  1
,'level_4' =>  1
,'level_3' =>  1
,'level_2' =>  1
,'level_1' =>  1
,'level_0' =>  1
,'edit_others_pages' =>  'page_core'
,'edit_published_pages' =>  'page_core'
,'publish_pages' =>  'page_core'
,'delete_pages' =>  'page_core'
,'delete_others_pages' =>  'page_core'
,'delete_published_pages' =>  'page_core'
,'delete_posts' =>  'post_core'
,'delete_others_posts' =>  'post_core'
,'delete_published_posts' =>  'post_core'
,'delete_private_posts' =>  'post_core'
,'edit_private_posts' =>  'post_core'
,'read_private_posts' => 'post_core'
,'delete_private_pages' =>   'page_core'
,'edit_private_pages' =>   'page_core'
,'read_private_pages' =>   'page_core'
,'delete_users' =>  'users_core'
,'create_users' =>  'users_core'
,'unfiltered_upload' =>  'wordpress_core'
,'edit_dashboard' =>  'wordpress_core'
,'update_plugins' =>  'wordpress_core'
,'delete_plugins' =>  'wordpress_core'
,'install_plugins' =>  'wordpress_core'
,'update_themes' =>  'wordpress_core'
,'install_themes' =>  'wordpress_core'
,'update_core' =>  'wordpress_core'
,'list_users' =>  'users_core'
,'remove_users' =>  'users_core'
,'promote_users' =>  'users_core'
,'edit_theme_options' =>  'wordpress_core'
,'delete_themes' => 'wordpress_core'
,'export' =>  'wordpress_core'
,'loco_admin' =>  'loco_core'
,'manage_woocommerce' =>  'woo'
,'view_woocommerce_reports' =>  'woo'
,'edit_product' =>  'product'
,'read_product' =>  'product'
,'delete_product' =>  'product'
,'edit_products' =>  'product'
,'edit_others_products' => 'product'
,'publish_products' =>  'product'
,'read_private_products' =>  'product'
,'delete_products' =>  'product'
,'delete_private_products' =>  'product'
,'delete_published_products' =>  'product'
,'delete_others_products' =>  'product'
,'edit_private_products' =>  'product'
,'edit_published_products' =>  'product'
,'manage_product_terms' =>  'product'
,'edit_product_terms' =>  'product'
,'delete_product_terms' =>  'product'
,'assign_product_terms' =>  'product'
,'edit_shop_order' =>  'order'
,'read_shop_order' =>  'order'
,'delete_shop_order' =>  'order'
,'edit_shop_orders' =>  'order'
,'edit_others_shop_orders' =>  'order'
,'publish_shop_orders' => 'order'
,'read_private_shop_orders' =>  'order'
,'delete_shop_orders' =>  'order'
,'delete_private_shop_orders' =>  'order'
,'delete_published_shop_orders' =>  'order'
,'delete_others_shop_orders' =>  'order'
,'edit_private_shop_orders' =>  'order'
,'edit_published_shop_orders' =>  'order'
,'manage_shop_order_terms' =>  'order'
,'edit_shop_order_terms' =>  'order'
,'delete_shop_order_terms' =>  'order'
,'assign_shop_order_terms' =>  'order'
,'edit_shop_coupon' =>  'coupon'
,'read_shop_coupon' =>  'coupon'
,'delete_shop_coupon' =>  'coupon'
,'edit_shop_coupons' =>  'coupon'
,'edit_others_shop_coupons' =>  'coupon'
,'publish_shop_coupons' =>  'coupon'
,'read_private_shop_coupons' =>  'coupon'
,'delete_shop_coupons' =>  'coupon'
,'delete_private_shop_coupons' =>  'coupon'
,'delete_published_shop_coupons' =>  'coupon'
,'delete_others_shop_coupons' =>  'coupon'
,'edit_private_shop_coupons' =>  'coupon'
,'edit_published_shop_coupons' =>  'coupon'
,'manage_shop_coupon_terms' =>  'coupon'
,'edit_shop_coupon_terms' =>  'coupon'
,'delete_shop_coupon_terms' =>  'coupon'
,'assign_shop_coupon_terms' =>  'coupon'
,'wpseo_manage_options' =>  'seo'
,'dokandar' =>  'dokan'
,'dokan_view_sales_overview' =>  'dokan'
,'dokan_view_sales_report_chart' =>  'dokan'
,'dokan_view_announcement' =>  'dokan'
,'dokan_view_order_report' =>  'dokan'
,'dokan_view_review_reports' =>  'dokan'
,'dokan_view_product_status_report' =>  'dokan'
,'dokan_view_overview_report' =>  'dokan'
,'dokan_view_daily_sale_report' =>  'dokan'
,'dokan_view_top_selling_report' =>  'dokan'
,'dokan_view_top_earning_report' =>  'dokan'
,'dokan_view_statement_report' =>  'dokan'
,'dokan_view_order' =>  'dokan'
,'dokan_manage_order' =>  'dokan'
,'dokan_manage_order_note' =>  'dokan'
,'dokan_manage_refund' =>  'dokan'
,'dokan_export_order' =>  'dokan'
,'dokan_add_coupon' =>  'dokan'
,'dokan_edit_coupon' =>  'dokan'
,'dokan_delete_coupon' =>  'dokan'
,'dokan_view_reviews' =>  'dokan'
,'dokan_manage_reviews' =>  'dokan'
,'dokan_manage_withdraw' =>  'dokan'
,'dokan_add_product' =>  'dokan'
,'dokan_edit_product' =>  'dokan'
,'dokan_delete_product' =>  'dokan'
,'dokan_view_product' =>  'dokan'
,'dokan_duplicate_product' =>  'dokan'
,'dokan_import_product' =>  'dokan'
,'dokan_export_product' =>  'dokan'
,'dokan_view_overview_menu' =>  'dokan'
,'dokan_view_product_menu' =>  'dokan'
,'dokan_view_order_menu' =>  'dokan'
,'dokan_view_coupon_menu' =>  'dokan'
,'dokan_view_report_menu' =>  'dokan'
,'dokan_view_review_menu' =>  'dokan'
,'dokan_view_withdraw_menu' =>  'dokan'
,'dokan_view_store_settings_menu' =>  'dokan'
,'dokan_view_store_payment_menu' =>  'dokan'
,'dokan_view_store_shipping_menu' =>  'dokan'
,'dokan_view_store_social_menu' =>  'dokan'
,'dokan_view_store_seo_menu' =>  'dokan'
,'muap_access' =>  'manage_access_user'
,'muap_cap' =>  'manage_access_user'
,'muap_other_roles' =>  'manage_access_user'
,'disable_dashboard_alert_notifi' =>  'manage_access_user'
];
$caps[]["word_press_core"]=[];
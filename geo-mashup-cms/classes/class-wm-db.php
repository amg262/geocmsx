<?php
/**
 * Created by PhpStorm.
 * User: Andy
 * Date: 10/18/2016
 * Time: 3:16 PM
 */

namespace WooMulti;


class WooMultiDB {
    private $_db, $_tbl_name, $sql, $key;

    /**
     * GsxDatabase constructor.
     * @param $action
     */
    public function __construct()
    {
        global $wpdb;//, $trail_story_options;
        global $woo_multi;
        global $woo_multi_settings;

        $this->_db = $wpdb;
        $this->_tbl_name = $this->_db->prefix . 'woo_multi_order';
        $this->key = 'db_init';

        add_action('admin_init', array($this, 'wm_install_db'));
    }


    public function wm_install_db()
    {
        global $woo_multi;
        $woo_multi = get_option('woo_multi');

        $this->sql = "CREATE TABLE IF NOT EXISTS {$this->_tbl_name} (
              id INT(200) NOT NULL AUTO_INCREMENT,
              parent_order INT(200),
              order_id INT(200),
              order_data VARCHAR(255),
              order_text LONGTEXT,
              order_ship VARCHAR(255),
              primary_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              free_shipping VARCHAR(20) DEFAULT 'true',
              is_active VARCHAR(20) DEFAULT 'true',
              UNIQUE KEY id (id)
            )";

        if ((!isset($woo_multi[$this->key])) || ($woo_multi[$this->key] === null)) {
            $charset_collate = $this->_db->get_charset_collate();
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $this->sql );

            update_option('woo_multi', array($this->key => 'true'));

        } elseif ($woo_multi[$this->key] === 'true')  {

        } else {

        }

    }

    public function wm_insert_row($args) {
        $date = new DateTime();
        $id =
            $this->_db->insert(
                $this->_tbl_name,
                array(
                    'post_id' => $args['post_id'],
                    'gps_data' => $args['gps_data']
                )
            );
        return $id;
    }
}
global $woo_multi;
$wm_db = new WooMultiDB();
var_dump($woo_multi);
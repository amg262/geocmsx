<?php

/**
 * Created by PhpStorm.
 * User: andy
 * Date: 10/13/16
 * Time: 10:16 PM
 */
class GsxDatabase
{

    private $_db, $_tbl_name, $sql, $action;

    /**
     * GsxDatabase constructor.
     * @param $action
     */
    public function __construct()
    {
        global $wpdb;//, $trail_story_options;
        $this->_db = $wpdb;
        $this->_tbl_name = $this->_db->prefix . 'etd_manager';



        add_action('admin_init', array($this, 'install_required_tables'));
    }

    /**
     * @return mixed
     */
    public function getSql()
    {
        return $this->sql;
    }

    /**
     * @param mixed $sql
     */
    public function setSql($sql)
    {
        $this->sql = $sql;
    }



    public function install_required_tables() {
        $charset_collate = $this->_db->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS {$this->_tbl_name} (
              id INT(200) NOT NULL AUTO_INCREMENT,
              post_id INT(200),
              gps_data VARCHAR(255),
              primary_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
              is_active VARCHAR(20) DEFAULT 'true',
              is_posted VARCHAR(20),
              UNIQUE KEY id (id)
            )";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    public function insert($args) {
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

$n = new GsxDatabase();
//print $n->insert(array('post_id'=>'43', 'gps_data'=>'fsdfjasldfjosdifjosidfjs', 'P'));
//var_dump($n);
//print $n->run_action($n->getSql());
<?php
/*
Plugin Name: DB Examples
Plugin URI: https://example.com/custom-database-plugin
Description: A plugin to demonstrate WordPress database operations using OOP approach.
Version: 1.0
Author: Your Name
Author URI: https://example.com
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class DB_Examples {
    private $table_name;
    private $dbv = '1.3';
    function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'custom_table'; //wp_custom_table
        add_action('init', [$this, 'init']);
        register_activation_hook(__FILE__, [$this, 'create_database_tables']);
        register_deactivation_hook(__FILE__, [$this, 'deactivate']);

        //create an admin page
        add_action('admin_menu', [$this, 'add_admin_menu']);

        $dbv = get_option('dbv');
        if ($dbv != $this->dbv) {
            $this->create_database_tables();
            update_option('dbv', $this->dbv);
        }
        // $this->create_database_tables();
    }

    function add_admin_menu() {
        add_menu_page('DB Examples', 'DB Examples', 'manage_options', 'db-examples', [$this, 'admin_page'], 'dashicons-admin-generic');
    }

    function admin_page() {
        global $wpdb;
        $total_rows = $wpdb->get_var("SELECT COUNT(*) FROM $this->table_name");
        echo '<div class="wrap">';
        echo '<h2>DB Examples</h2>';
        echo '<p>This is a custom admin page for DB Examples plugin.</p>';
        

        //display results
        $results = $wpdb->get_results("SELECT * FROM {$this->table_name}");
        // $results = $wpdb->get_results("SELECT * FROM $wpdb->custom_table"); 
        ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($results as $row) {
                    echo '<tr>';
                    echo '<td>' . esc_html($row->id) . '</td>';
                    echo '<td>' . esc_html($row->name) . '</td>';
                    echo '<td>' . esc_html($row->email) . '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
        <?php
        //display total rows
        echo '<p>Total rows: ' . esc_html($total_rows) . '</p>';
        echo '</div>';
    }

    function init() {
        // $this->create_database_tables();

        //handle form submission
        // if (isset($_POST['submit'])) {
        //     global $wpdb;
        //     $name = sanitize_text_field($_POST['name']);
        //     $email = sanitize_text_field($_POST['email']);

    }

    function deactivate() {
        global $wpdb;
        // $wpdb->query("DROP TABLE IF EXISTS $this->table_name");
    }

    function create_database_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $this->table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(50) NOT NULL,
            email varchar(50) NOT NULL,
            -- phone varchar(15) NOT NULL,
            -- gender varchar(10) NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        //drop phone column
        // $wpdb->query("ALTER TABLE $this->table_name DROP COLUMN phone");

        //insert some dummy data
        // $wpdb->insert($this->table_name, ['name' => 'John Doe', 'email' => 'john@doe.com']);
        // $wpdb->insert($this->table_name, ['name' => 'Jane Doe', 'email' => 'jane@doe.com']);
        // $wpdb->insert($this->table_name, ['name' => 'Jimmy Doe', 'email' => 'jimmy@doe.com']);

        //update 4th record
        //$wpdb->update($this->table_name, ['email' => 'wedevs@academy.local'], ['id' => 4]);
        
        //delete
        //$wpdb->delete($this->table_name, ['id' => 5]);

        //prepared insert
        // $wpdb->query($wpdb->prepare(
        //     "INSERT INTO $this->table_name (name, email) VALUES (%s, %s)", 
        //     'ABCD', 'abcd@abcd.com'));

        // $name = "XYZ";
        // $email = "xyz@xyz.com";
        // $wpdb->insert(
        //     $this->table_name,
        //     array(
        //         'name' => $wpdb->prepare('%s', $name),
        //         'email' => $wpdb->prepare('%s', $email)
        //     )
        // );

        // $wpdb->query('START TRANSACTION');
        // $wpdb->query('COMMIT');
        // $wpdb->query('ROLLBACK');


    }
}

new DB_Examples();
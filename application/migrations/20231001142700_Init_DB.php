<?php

/**
 * Class Migration_Init
 *
 * @property CI_DB_query_builder $db
 * @property CI_DB_forge         $dbforge
 */
class Migration_Init_DB extends CI_Migration {

    public function up() {
        $this->db->trans_begin();
        // import file
        $this->_import_file();
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
    }

    private function _import_file() {
        $path_file = APPPATH . DIRECTORY_SEPARATOR . "migration_files" . DIRECTORY_SEPARATOR . "init_db.sql";
        if (is_file($path_file)) {
            $lines = file($path_file);
            $temp_line = '';
            foreach ($lines as $line) {
                // Skip it if it's a comment
                if (substr($line, 0, 2) == '--' || substr($line, 0, 2) == '/*' || $line == '')
                    continue;
                // Add this line to the current segment
                $temp_line .= $line;
                // If it has a semicolon at the end, it's the end of the query
                if (substr(trim($line), -1, 1) == ';')
                {
                    // Perform the query
                    $this->db->query($temp_line);
                    // Reset temp variable to empty
                    $temp_line = '';
                }
            }
        }
    }

    public function down() {
    }
}
<?php
    class Utils {
        public static function get_settings(string $fileName, string $path) {
            $json_file = file_get_contents($fileName);
            $json_data = json_decode($json_file, true);
            return $json_data[$path];
        }

        public static function print_table_jobs($rows) {
            $mask_for_users_jobs = "| %-15.30s| %-20.30s| %-20.30s| %-20.30s| %-20.30s|\n";
            printf($mask_for_users_jobs, "personnel_id", "first_name", "last_name", "job_name", "date");
            echo "----------------------------------------------------------------------------------------------------------\n";
            foreach($rows as $job) {
                printf($mask_for_users_jobs, $job['personnel_id'], $job['first_name'], $job["last_name"], $job["name"], $job["scheduled_for"]);
            }
            echo "\n\n";
        }
    }
?>
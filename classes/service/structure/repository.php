<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.moodle.org/license>.

namespace block_dixeo_designer\service\structure;

defined('MOODLE_INTERNAL') || die();

/**
 * Repository for persisted designer structure (one row per job in block_dixeo_designer_structure).
 *
 * @package    block_dixeo_designer
 * @copyright  2026 Dixeo
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class repository {

    /** @var string Table name. */
    private const TABLE = 'block_dixeo_designer_structure';

    /**
     * Get the saved structure JSON for a job.
     *
     * @param string $jobid
     * @return string|null JSON string or null
     */
    public function get_latest_structure(string $jobid): ?string {
        global $DB;

        $record = $DB->get_record(self::TABLE, ['jobid' => $jobid], 'structure', IGNORE_MISSING);
        return $record ? $record->structure : null;
    }

    /**
     * Persist structure for a job (insert or update single row).
     *
     * @param string $jobid
     * @param int $userid
     * @param string $description
     * @param array $result Structure payload (will be JSON-encoded).
     * @return void
     */
    public function save_structure(string $jobid, int $userid, string $description, array $result): void {
        global $DB;

        $now = time();
        $json = json_encode($result);
        $existing = $DB->get_record(self::TABLE, ['jobid' => $jobid], '*', IGNORE_MISSING);

        if ($existing) {
            $existing->userid = $userid;
            $existing->description = $description;
            $existing->structure = $json;
            $existing->timecreated = $now;
            $DB->update_record(self::TABLE, $existing);
            return;
        }

        $DB->insert_record(self::TABLE, (object) [
            'jobid' => $jobid,
            'userid' => $userid,
            'description' => $description,
            'structure' => $json,
            'timecreated' => $now,
        ]);
    }

    /**
     * Delete the persisted structure row for a job.
     *
     * @param string $jobid
     * @return int Number of deleted records.
     */
    public function delete_by_jobid(string $jobid): int {
        global $DB;
        return $DB->delete_records(self::TABLE, ['jobid' => $jobid]);
    }
}

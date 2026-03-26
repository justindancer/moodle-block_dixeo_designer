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
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

namespace block_dixeo_designer\cancellation;

defined('MOODLE_INTERNAL') || die();

/**
 * Maps cancellation_context to cancellation_plan (see docs/cancellation-decision-matrix.yml).
 *
 * @package    block_dixeo_designer
 * @copyright  2026 Dixeo
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class cancellation_policy_resolver {

    /**
     * Resolve the plan from context.
     *
     * @param cancellation_context $ctx
     * @return cancellation_plan
     */
    public static function resolve(cancellation_context $ctx): cancellation_plan {
        // Hard reset only on explicit delete_structure request (footer/dashboard).
        // Keep submission payload (prompt/template/files) so users can regenerate without re-entering inputs.
        if ($ctx->delete_structure_requested) {
            return new cancellation_plan(
                true,
                false,
                true,
                true,
                false,
                false,
                true,
                true,
                false
            );
        }

        // No saved structure: keep submission payload (prompt/template/files), but reset run state.
        if (!$ctx->has_saved_structure) {
            return new cancellation_plan(
                true,  // delete_structure_rows (no-op if none).
                false, // keep submission row.
                true,  // reset submission to draft.
                true,  // delete_draft_course.
                false, // delete_generated_modules_only.
                false, // restore_draft_course_metadata.
                true,  // disable_file_sync.
                true,  // remove_files_on_disable_sync.
                false  // reset_quick_finalize_progress_fields.
            );
        }

        // Resume / in-place reset: keep draft course, structure row, submission row; reset submission to draft;
        // remove generated modules only; restore course metadata; lighter vector reset.
        // Quick-mode cancel from designer.php uses this branch too, but still resets quick progress fields.
        return new cancellation_plan(
            false, // keep structure rows.
            false, // keep submission row.
            true,  // reset submission to draft.
            false, // keep draft course.
            true,  // delete generated modules only (preserve upload resources).
            true,  // restore draft-like metadata after finalize.
            true,  // disable_file sync (pause / stop polling).
            false, // do not wipe vector store files — file resources stay tied to submission sync.
            $ctx->generation_mode === 'quick'
        );
    }
}

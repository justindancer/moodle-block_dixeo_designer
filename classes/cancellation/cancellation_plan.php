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
 * Resolved cancellation actions (source of truth for cancel_draft execution order).
 *
 * @package    block_dixeo_designer
 * @copyright  2026 Dixeo
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class cancellation_plan {

    /** @var bool Delete rows in block_dixeo_designer_structure for this job. */
    public bool $delete_structure_rows;

    /** @var bool Remove submission row entirely. */
    public bool $delete_submission_row;

    /**
     * Clear remote job id and set status draft. When the draft course is kept (two-step resume),
     * courseid is preserved so {@see designer_service::prepare_generation()} can reuse the course.
     */
    public bool $reset_submission_to_draft;

    /** @var bool Delete the Moodle course (and all modules). */
    public bool $delete_draft_course;

    /**
     * Delete only AI-generated activity modules; preserve submission file resources (course_modules.idnumber = upload tag).
     * Only used when delete_draft_course is false and courseid is set.
     */
    public bool $delete_generated_modules_only;

    /** @var bool Restore course fullname/shortname/idnumber/summary to draft-like after finalize overwrote them. */
    public bool $restore_draft_course_metadata;

    /** @var bool Call file_sync_service->disable_sync. */
    public bool $disable_file_sync;

    /**
     * Second argument to disable_sync: remove remote VectorStore files and reset local_dixeo_course_ai state.
     * For resume (keep course), false preserves vector inputs while file resources remain in the course.
     */
    public bool $remove_files_on_disable_sync;

    /** @var bool Clear finalize_progress phase/index fields for quick mode cancels. */
    public bool $reset_quick_finalize_progress_fields;

    /**
     * @param bool $delete_structure_rows
     * @param bool $delete_submission_row
     * @param bool $reset_submission_to_draft
     * @param bool $delete_draft_course
     * @param bool $delete_generated_modules_only
     * @param bool $restore_draft_course_metadata
     * @param bool $disable_file_sync
     * @param bool $remove_files_on_disable_sync
     * @param bool $reset_quick_finalize_progress_fields
     */
    public function __construct(
        bool $delete_structure_rows,
        bool $delete_submission_row,
        bool $reset_submission_to_draft,
        bool $delete_draft_course,
        bool $delete_generated_modules_only,
        bool $restore_draft_course_metadata,
        bool $disable_file_sync,
        bool $remove_files_on_disable_sync,
        bool $reset_quick_finalize_progress_fields
    ) {
        $this->delete_structure_rows = $delete_structure_rows;
        $this->delete_submission_row = $delete_submission_row;
        $this->reset_submission_to_draft = $reset_submission_to_draft;
        $this->delete_draft_course = $delete_draft_course;
        $this->delete_generated_modules_only = $delete_generated_modules_only;
        $this->restore_draft_course_metadata = $restore_draft_course_metadata;
        $this->disable_file_sync = $disable_file_sync;
        $this->remove_files_on_disable_sync = $remove_files_on_disable_sync;
        $this->reset_quick_finalize_progress_fields = $reset_quick_finalize_progress_fields;
    }
}

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
 * Inputs for cancellation policy resolution (no side effects).
 *
 * @package    block_dixeo_designer
 * @copyright  2026 Dixeo
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class cancellation_context {

    /** @var bool Whether a structure row exists for the job. */
    public bool $has_saved_structure;

    /** @var bool Web service flag: force delete structure (footer hard reset or dashboard hard reset). */
    public bool $delete_structure_requested;

    /** @var string finalize_progress.generation_mode: quick, twostep, or empty. */
    public string $generation_mode;

    /**
     * @param bool $has_saved_structure
     * @param bool $delete_structure_requested
     * @param string $generation_mode
     */
    public function __construct(
        bool $has_saved_structure,
        bool $delete_structure_requested,
        string $generation_mode = ''
    ) {
        $this->has_saved_structure = $has_saved_structure;
        $this->delete_structure_requested = $delete_structure_requested;
        $this->generation_mode = $generation_mode;
    }
}

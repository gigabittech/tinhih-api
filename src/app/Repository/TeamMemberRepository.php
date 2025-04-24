<?php

namespace App\Repository;

use App\Models\TeamMember;
use App\Repository\Implementation\BaseRepository;

class TeamMemberRepository extends BaseRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct(TeamMember $teamMember)
    {
        parent::__construct($teamMember);
    }

    public function getUserTeamMembers($userId, $teamId)
    {
        return $this->model->where('user_id', $userId)->where('workspace_id', $teamId)->get();
    }
}

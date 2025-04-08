<?php

namespace App\Repository;

use App\Models\Workspace;
use App\Repository\Implementation\BaseRepository;

class WorkspaceRepository extends BaseRepository
{
    public function __construct(Workspace $workspace)
    {
        parent::__construct($workspace);
    }


    public function getUserWorkspaces($userId)
    {
        return $this->model->where('user_id', $userId)->get();
    }

    public function toggle($user, $id)
    {
        $user->workspaces()->where('active', 1)->update(['active' => 0]);
        $workspace = $user->workspaces()->findOrFail($id);
        $workspace->active = !$workspace->active;
        $workspace->save();
        return $workspace;
    }

    public function setup($user, $data)
    {
        $workspace = $user->workspaces()->create($data);

        return $workspace;
    }
}

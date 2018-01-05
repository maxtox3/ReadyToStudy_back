<?php

namespace App\Api\V1\Controllers;

use App\Group;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function all()
    {
        $groups = Group::all();

        return response()
            ->json([
                'status' => 'ok',
                'groups' => $groups
            ]);
    }

    public function get($id)
    {
        $group = Group::find($id);
        $disciplines = $group->discipline()->get();
        $students = $group->user()->get();

        return response()
            ->json([
                'status' => 'ok',
                'group' => $group,
                'disciplines' => $disciplines,
                'students' => $students
            ]);
    }

    public function create(Request $request)
    {
    }

    public function update($id, Request $request)
    {
    }

    public function delete()
    {
    }
}

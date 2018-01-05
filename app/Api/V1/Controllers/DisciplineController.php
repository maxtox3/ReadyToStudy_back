<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\DisciplineCreateRequest;
use App\Discipline;
use App\Group;
use App\Http\Controllers\Controller;
use Auth;
use Dingo\Api\Exception\ValidationHttpException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;


class DisciplineController extends Controller
{

    use Helpers;

    public function all()
    {
        $user = Auth::user();

        if ($user->is_teacher) {
            $discipline = Discipline::where('teacher_id', '=', $user->id)->get();
            $themes = $discipline[0]->theme()->get();

            return response()
                ->json([
                    'status' => 'ok',
                    'discipline' => $discipline,
                    'themes' => $themes
                ]);
        }

        $disciplines = Group::find($user->group_id)->discipline()->get();
        $themes = $disciplines[0]->theme()->get();

        return response()
            ->json([
                'status' => 'ok',
                'disciplines' => $disciplines,
                'themes' => $themes
            ]);
    }

    public function get($id)
    {
        $user = Auth::user();

        if ($user->is_teacher) {
            $discipline = Discipline::find($id);
            $themes = $discipline->theme()->get();

            return response()
                ->json([
                    'status' => 'ok',
                    'discipline' => $discipline,
                    'themes' => $themes
                ]);
        }

        $disciplines = Group::find($user->group_id)->discipline()->get();
        $resultDiscipline = null;

        foreach ($disciplines as $discipline) {
            if ($discipline->id == $id)
                $resultDiscipline = $discipline;
        }

        if ($resultDiscipline == null) {
            throw new AccessDeniedHttpException();
        }

        $themes = $resultDiscipline->theme()->get();

        return response()
            ->json([
                'status' => 'ok',
                'discipline' => $resultDiscipline,
                'themes' => $themes
            ]);

    }

    public function create(Request $request)
    {
        $user = Auth::user();

        if (!$user->is_teacher) {
            throw new AccessDeniedHttpException();
        }

        $credentials = $request->only(['name', 'group_id']);

        $validator = Validator::make($credentials, [
            'name' => 'required',
            'group_id' => 'required',
        ]);
        if ($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }

        $discipline = new Discipline($credentials);
        $discipline['teacher_id'] = $user->id;

        if ($discipline->save()) {
            $discipline->group()->attach($credentials['group_id']);
            return $this->response->created();
        } else {
            return $this->response->error('Could not create discipline', 500);
        }
    }

    public function update($id, Request $request)
    {
        $user = Auth::user();

        if (!$user->is_teacher) {
            throw new AccessDeniedHttpException();
        }

        $discipline = Discipline::find($id);

        if (!$discipline->teacher_id == $user->id) {
            throw new AccessDeniedHttpException();
        }

        $discipline->fill($request->all());

        if ($discipline->save()) {

            if($request['group_id'] != null)
                $discipline->group()->attach($request['group_id']);

            return $this->response->accepted();
        } else {
            return $this->response->error('Could not update discipline', 500);
        }
    }

    public function delete()
    {

    }
}

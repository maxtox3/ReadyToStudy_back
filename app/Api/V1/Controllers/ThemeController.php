<?php

namespace App\Api\V1\Controllers;

use App\Discipline;
use App\Group;
use App\Http\Controllers\Controller;
use App\Theme;
use Dingo\Api\Exception\ValidationHttpException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ThemeController extends Controller
{
    use Helpers;

    public function all(Request $request)
    {
        $discipline = Discipline::find($request->query('disciplineId'));
        $themes = $discipline->theme()->get();

        return response()
            ->json([
                'status' => 'ok',
                'discipline' => $discipline,
                'themes' => $themes
            ]);
    }

    public function get($id)
    {
        $user = Auth::user();
        $theme = Theme::find($id);
        $tests = $theme->test()->get();

        if ($user->is_teacher) {
            return response()
                ->json([
                    'status' => 'ok',
                    'theme' => $theme,
                    'tests' => $tests
                ]);
        }

        //делаем проверку на принадлежность темы дисциплине преподаваемой в группе ученика
        $group = Group::find($user->group_id);
        $disciplines = $group->discipline()->get();
        $resultDiscipline = null;
        foreach ($disciplines as $discipline) {
            if ($discipline->id == $theme->discipline_id) {

                return response()
                    ->json([
                        'status' => 'ok',
                        'theme' => $theme,
                        'tests' => $tests
                    ]);
            }
        }

        throw new AccessDeniedHttpException();

    }

    public function create(Request $request)
    {
        $user = Auth::user();

        if (!$user->is_teacher) {
            throw new AccessDeniedHttpException();
        }

        $credentials = $request->only(['name', 'discipline_id']);

        $validator = Validator::make($credentials, [
            'name' => 'required',
            'discipline_id' => 'required',
        ]);
        if ($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }

        $theme = new Theme();
        $theme->fill($credentials);

        if ($theme->save()) {
            return $this->response->created();
        } else {
            return $this->response->error('Could not create discipline', 500);
        }
    }

    public function update($id, Request $request)
    {
//        $user = Auth::user();
//
//        if (!$user->is_teacher) {
//            throw new AccessDeniedHttpException();
//        }
//
//        $discipline = Discipline::find($id);
//
//        if (!$discipline->teacher_id == $user->id) {
//            throw new AccessDeniedHttpException();
//        }
//
//        $discipline->fill($request->all());
//
//        if ($discipline->save()) {
//
//            if ($request['group_id'] != null)
//                $discipline->group()->attach($request['group_id']);
//
//            return $this->response->accepted();
//        } else {
//            return $this->response->error('Could not update discipline', 500);
//        }
    }

    public function delete()
    {

    }
}

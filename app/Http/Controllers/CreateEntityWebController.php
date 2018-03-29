<?php

namespace App\Http\Controllers;

use App\Discipline;
use App\Group;
use App\Task;
use App\Test;
use App\Theme;
use App\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CreateEntityWebController extends Controller
{

    public function themesByName(Request $request)
    {
        if ($request->ajax()) {
            $discipline = Discipline::where('name', '=', $request->disciplineName)->get();
            $themes = $discipline[0]->theme()->get();
            $output = '';
            foreach ($themes as $theme) {
                $output .= '<option>' . $theme->name . '</option>';
            }

            return Response($output);
        }
    }

    public function getCreateDiscipline()
    {
        if (session()->get('user') == null) {
            session()->flash('error', 'Доступ возможен только авторизованным пользователям');
            return redirect('/login');
        }
        $user = session()->get('user');
        if (!$user->is_teacher) {
            session()->flash('error', 'У вас нет прав');
            return redirect('/');
        }

        $disciplines = Discipline::where('teacher_id', '=', $user->id)->get();
        $groupes = array();
        foreach ($disciplines as $discipline) {
            foreach ($discipline->group()->get() as $group) {
                array_push($groupes, $group->name);
            }
        }

        $groups = array_unique($groupes);
        $type = 'discipline';

        return view('create', compact(['type', 'groups']));
    }

    public function createDiscipline(Request $request)
    {
        $this->validate(request(), [
            'group_name' => 'required',
            'name' => 'required',
        ]);

        $user = session()->get('user');
        if (!$user->is_teacher) {
            session()->flash('error', 'У вас нет прав');
            return redirect('/');
        }

        $existingDiscipline = Discipline::where('name', '=', $request->name)->first();
        if ($existingDiscipline != null) {
            session()->flash('error', 'Ошибка! Дисциплина с таким названием уже существует');
            return back();
        }

        $group = Group::where('name', '=', $request->group_name)->first();

        $discipline = new Discipline();
        $discipline->teacher_id = $user->id;
        $discipline->name = $request->name;
        if ($discipline->save()) {
            $discipline->group()->attach($group->id);
            session()->flash('message', "Дисциплина " . $discipline->name . " успешно создана");
            return redirect('/');
        }
        session()->flash('error', 'Ошибка! не удалось создать дисциплину');
        return back();
    }

    public function getCreateTheme()
    {
        if (session()->get('user') == null) {
            session()->flash('error', 'Доступ возможен только авторизованным пользователям');
            return redirect('/login');
        }
        $user = session()->get('user');
        if (!$user->is_teacher) {
            session()->flash('error', 'У вас нет прав');
            return redirect('/');
        }

        $type = 'theme';
        $disciplines = Discipline::where('teacher_id', '=', $user->id)->get();
        return view('create', compact(['type', 'disciplines']));
    }

    public function createTheme(Request $request)
    {
        $this->validate(request(), [
            'discipline_name' => 'required',
            'name' => 'required',
        ]);

        if (session()->get('user') == null) {
            session()->flash('error', 'Доступ возможен только авторизованным пользователям');
            return redirect('/login');
        }
        $user = session()->get('user');
        if (!$user->is_teacher) {
            session()->flash('error', 'У вас нет прав');
            return redirect('/');
        }

        $existingTheme = Theme::where('name', '=', $request->name)->first();
        if ($existingTheme != null) {
            session()->flash('error', 'Ошибка! Тема с таким названием уже существует');
            return back();
        }

        $discipline = Discipline::where('name', '=', $request->discipline_name)->first();

        $theme = new Theme();
        $theme->name = $request->name;
        $theme->discipline_id = $discipline->id;

        if ($theme->save()) {
            session()->flash('message', "Тема " . $theme->name . " успешно создана");
            return redirect('/');
        }
        session()->flash('error', 'Ошибка! не удалось создать тему');
        return back();
    }
}

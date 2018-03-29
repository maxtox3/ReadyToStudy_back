<?php
/**
 * Created by PhpStorm.
 * User: v
 * Date: 20/03/2018
 * Time: 22:25
 */

namespace App\Http\Controllers;

use App\Discipline;
use App\Group;
use App\Task;
use App\Test;
use App\Theme;
use App\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class ImportWebController
{
    public function getImport()
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

        return view('import', compact(['disciplines']));
    }

    public function import(Request $request)
    {
        $this->validate(request(), [
            'theme_name' => 'required',
            'discipline_name' => 'required',
            'testName' => 'required',
            'import_file' => 'required'
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

        $theme = Theme::where('name', '=', $request->theme_name)->first();
        $themeId = $theme->id;

        $existingTest = Test::where('name', '=', $request->testName);
        if ($existingTest != null) {
            session()->flash('error', 'Тест с таким названием уже существует');
            return redirect()->back();
        }

        $test = new Test();
        $test->name = $request->testName;
        $test->theme_id = $themeId;

        if ($test->save()) {
            $test = Test::where('name', '=', $test->name)->first();

            $path = $request->file('import_file')->getRealPath();
            $data = Excel::load($path, function ($reader) {
            })->get();
            if (!empty($data) && $data->count()) {
                foreach ($data->toArray() as $key => $value) {
                    $task = new Task();
                    $task->test_id = $test->id;
                    $task->name = $value['name'];
                    $task->first_var = $value['first_var'];
                    $task->second_var = $value['second_var'];
                    $task->third_var = $value['third_var'];
                    $task->fourth_var = $value['fourth_var'];
                    $task->right_var = $value['right_var'];
                    if (!$task->save()) {
                        session()->flash('error', 'Ошибка! Проверьте ваш файл, или обратитесь к администратору');
                        return back();
                    }
                }
                session()->flash('message', 'Импорт прошел успешно');
                return redirect('/');
            }
        }
        session()->flash('error', 'Ошибка! Проверьте ваш файл, или обратитесь к администратору');
        return back();
    }

    public function getImportGroups()
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

        return view('importGroups');
    }

    public function importGroups(Request $request)
    {
        $this->validate(request(), [
            'import_file' => 'required'
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

        $path = $request->file('import_file')->getRealPath();
        $data = Excel::load($path, function ($reader) {
        })->get();
        if (!empty($data) && $data->count()) {
            foreach ($data->toArray() as $key => $value) {

                $existingGroup = Group::where('name', '=', $value['name'])->first();
                if ($existingGroup != null) {
                    continue;
                }

                $group = new Group();
                $group->name = $value['name'];
                if (!$group->save()) {
                    session()->flash('error', 'Ошибка! Проверьте ваш файл, или обратитесь к администратору');
                    return back();
                }
            }
            session()->flash('message', 'Импорт прошел успешно');
            return redirect('/');
        }
        session()->flash('error', 'Ошибка! Проверьте ваш файл, или обратитесь к администратору');
        return back();
    }
}
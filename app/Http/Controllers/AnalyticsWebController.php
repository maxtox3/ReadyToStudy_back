<?php
/**
 * Created by PhpStorm.
 * User: v
 * Date: 20/03/2018
 * Time: 22:32
 */

namespace App\Http\Controllers;


use App\Discipline;
use App\FinishedTest;
use App\Group;
use App\Test;
use App\Theme;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AnalyticsWebController
{
    public function getAnalytics()
    {
        if (session()->get('user') == null) {
            session()->flash('error', 'Доступ возможен только авторизованным пользователям');
            return redirect('/login');
        }

        $user = session()->get('user');
        if ($user->is_teacher) {
            $teachersDisciplines = Discipline::where('teacher_id', '=', $user->id)->get();

            $students = collect();
            $teachersGroups = collect();
            $teachersThemes = collect();
            $teachersTests = collect();
            $testsIds = array();

            foreach ($teachersDisciplines as $discipline) {
                $teachersGroups = $teachersGroups->merge($discipline->group()->get());
                $teachersThemes = $teachersThemes->merge($discipline->theme()->get());
            }
            $teachersGroups = $teachersGroups->unique('id');
            $teachersThemes = $teachersThemes->unique('id');

            foreach ($teachersThemes as $theme) {
                $teachersTests = $teachersTests->merge($theme->test()->get());
            }
            $teachersTests = $teachersTests->unique('id');

            foreach ($teachersTests as $teachersTest) {
                array_push($testsIds, $teachersTest->id);
            }

            foreach ($teachersGroups as $teachersGroup) {
                $studs = $teachersGroup->user()->get();
                foreach ($studs as $stud) {
                    $stud['group'] = $teachersGroup;
                }
                $students = $students->merge($studs);
            }

            foreach ($students as $student) {
                $student['finished_tests'] = FinishedTest::where('user_id', '=', $student->id)->whereIn('test_id', $testsIds)->get();
                $student['available_tests'] = $teachersTests;
            }

            return view('analytics', compact('students'));

        } else {
            $disciplines = Group::find($user->group_id)->discipline()->get();
            $disciplinesIds = array();
            $themesIds = array();
            foreach ($disciplines as $discipline) {
                array_push($disciplinesIds, $discipline->id);
            }

            $themes = Theme::whereIn('discipline_id', $disciplinesIds)->get();
            foreach ($themes as $theme) {
                array_push($themesIds, $theme->id);
            }

            //необходимо передать дисциплины, каждая будет содержать полное количество ответов правильных ко всем
            $student = $user;

            $student->disciplines = $disciplines;
            $student->themes = $themes;
            $student->tests = Test::whereIn('theme_id', $themesIds)->get();
            $student->finishedTests = $user->finishedTest()->get();

            $input = $student->finishedTests->avg('time_spent');
            $input = floor($input / 1000);

            $seconds = $input % 60;
            $input = floor($input / 60);

            $minutes = $input % 60;
            $input = floor($input / 60);

            $hours = $input % 60;

            if ($hours < 10) {
                $hours = '0' . $hours;
            }

            if ($minutes < 10) {
                $minutes = '0' . $minutes;
            }

            if ($seconds < 10) {
                $seconds = '0' . $seconds;
            }

            $student->avg_time = $hours . ':' . $minutes . ':' . $seconds;

            return view('analytics', compact('student'));
        }

    }

    public function getTests()
    {
        $user = session()->get('user');

        if (session()->get('user') == null) {
            session()->flash('error', 'Доступ возможен только авторизованным пользователям');
            return redirect('/login');
        }

        $resultDisciplines = collect();
        $resultThemes = collect();

        $disciplines = Group::find($user->group_id)->discipline()->get();

        foreach ($disciplines as $discipline) {
            $disciplineThemes = $discipline->theme()->get();
            $disciplineTests = collect();

            foreach ($disciplineThemes as $disciplineTheme) {
                $themeTests = $disciplineTheme->test()->get();
                $tests = collect();

                foreach ($themeTests as $themeTest) {
                    $disciplineTests->put($themeTest->id, $themeTest);

                    $tests->put($themeTest->id, $themeTest);
                }

                $disciplineTheme->tests = $tests;
                $disciplineTheme->testsCount = $tests->count();
                $disciplineTheme->testsIds = $tests->pluck('id');
                $resultThemes->put($disciplineTheme->id, $disciplineTheme);
            }

            $discipline->tests = $disciplineTests;
            $discipline->testsCount = $disciplineTests->count();
            $discipline->testsIds = $disciplineTests->pluck('id')->flatten();
            $resultDisciplines->put($discipline->id, $discipline);
        }

        $disciplinesTestsLabels = array();
        $disciplinesTestsCounts = array();
        $themesTestsLabels = array();
        $themesTestsCounts = array();

        foreach ($resultDisciplines as $disc) {
            $finishedTestsCount = FinishedTest::whereIn('test_id', $disc->testsIds)->get()->count();
            $label = $disc->name . "\n" . $finishedTestsCount . "/" . $disc->testsCount;
            $count = $finishedTestsCount;
            array_push($disciplinesTestsLabels, $label);
            array_push($disciplinesTestsCounts, $count);
            $disc->finishedTestsCount = $finishedTestsCount;
        }

        foreach ($resultThemes as $them) {
            $finishedTestsCount = FinishedTest::whereIn('test_id', $them->testsIds)->get()->count();
            $label = $them->name . "\n" . $finishedTestsCount . "/" . $them->testsCount;
            $count = $finishedTestsCount;
            array_push($themesTestsLabels, $label);
            array_push($themesTestsCounts, $count);
            $them->finishedTestsCount = $finishedTestsCount;
        }

        return response()->json([
            'disciplines' => [
                'labels' => $disciplinesTestsLabels,
                'counts' => $disciplinesTestsCounts
            ],
            'themes' => [
                'labels' => $themesTestsLabels,
                'counts' => $themesTestsCounts
            ]
        ]);

    }

    public function getTime()
    {
        $user = session()->get('user');

        if (session()->get('user') == null) {
            session()->flash('error', 'Доступ возможен только авторизованным пользователям');
            return redirect('/login');
        }

        $resultDisciplines = collect();
        $resultThemes = collect();

        $disciplines = Group::find($user->group_id)->discipline()->get();

        foreach ($disciplines as $discipline) {
            $disciplineThemes = $discipline->theme()->get();
            $disciplineTests = collect();

            foreach ($disciplineThemes as $disciplineTheme) {
                $themeTests = $disciplineTheme->test()->get();
                $tests = collect();

                foreach ($themeTests as $themeTest) {
                    $disciplineTests->put($themeTest->id, $themeTest);

                    $tests->put($themeTest->id, $themeTest);
                }

                $disciplineTheme->tests = $tests;
                $disciplineTheme->testsCount = $tests->count();
                $disciplineTheme->testsIds = $tests->pluck('id');
                $resultThemes->put($disciplineTheme->id, $disciplineTheme);
            }

            $discipline->tests = $disciplineTests;
            $discipline->testsCount = $disciplineTests->count();
            $discipline->testsIds = $disciplineTests->pluck('id')->flatten();
            $resultDisciplines->put($discipline->id, $discipline);
        }

        $disciplinesTestsLabels = array();
        $disciplinesTestsTimes = array();
        $themesTestsLabels = array();
        $themesTestsTimes = array();

        foreach ($resultDisciplines as $disc) {
            $finishedTests = FinishedTest::whereIn('test_id', $disc->testsIds)->get();
            $label = $disc->name;
            $count = $finishedTests->avg('time_spent');

            $input = floor($count / 1000);
            $seconds = $input % 60;
//            $input = floor($input / 60);
//            $minutes = $input % 60;

            array_push($disciplinesTestsLabels, $label);
            array_push($disciplinesTestsTimes, $seconds);
            $disc->finishedTestsCount = $finishedTests;
        }

        foreach ($resultThemes as $them) {
            $finishedTests = FinishedTest::whereIn('test_id', $them->testsIds)->get();
            $label = $them->name;
            $count = $finishedTests->avg('time_spent');

            $input = floor($count / 1000);
//            $seconds = $input % 60;

//            $input = floor($input / 60);
//            $minutes = $input % 60;

            array_push($themesTestsLabels, $label);
            array_push($themesTestsTimes, $input);
            $them->finishedTestsCount = $finishedTests;
        }

        return response()->json([
            'disciplines' => [
                'labels' => $disciplinesTestsLabels,
                'times' => $disciplinesTestsTimes
            ],
            'themes' => [
                'labels' => $themesTestsLabels,
                'times' => $themesTestsTimes
            ]
        ]);
    }

    public function getAnswers()
    {

    }



//        $themes = Theme::whereIn('discipline_id', $disciplinesIds)->get();
//
//        foreach ($themes as $theme) {
//            array_push($themesIds, $theme->id);
//        }
//
//        $tests = Test::whereIn('theme_id', $themesIds)->get();

//        return view('analytics.chart', compact('student'));

}


<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Test;
use App\Theme;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class TestController extends Controller
{
    use Helpers;

    public function all(Request $request)
    {
        if ($request->query('theme_id') != null) {
            $theme = Theme::find($request->query('theme_id'));
            $tests = $theme->test()->get();

            return response()
                ->json([
                    'status' => 'ok',
                    'theme' => $theme,
                    'tests' => $tests
                ]);
        } else {
            $this->response->errorNotFound();
        }
        return null;
    }

    public function get($id)
    {
        $test = Test::find($id);
        $tasks = $test->task()->get();

        return response()
            ->json([
                'status' => 'ok',
                'test' => $test,
                'tasks' => $tasks
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

<?php

namespace App\Http\Controllers;

use App\Models\Fixture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FixtureController extends Controller
{
    public function createFixture(Request $request) {
        $fixture = new Fixture;

        $fixture->user_id = Auth::user()->id;
        if ($request->sponsor_logo != '') {
            $sponsor_logo = 'sponsor_logo'.time().'.'.$request->sponsor_logo->extension(); //code for image

            $request->sponsor_logo->move(public_path('/uploads/logos/'),$sponsor_logo);

            $fixture->sponsor_logo = $sponsor_logo;
        }
        $fixture->sponsor_name = $request->sponsor_name;
        $fixture->date = $request->date;
        $fixture->time = $request->time;
        $fixture->venue = $request->venue;
        $fixture->team_a_name = $request->team_a_name;
        if ($request->team_a_logo != '') {
            $team_a_logo = 'team_a_logo'.time().'.'.$request->team_a_logo->extension(); //code for image

            $request->team_a_logo->move(public_path('/uploads/logos/'),$team_a_logo);

            $fixture->team_a_logo = $team_a_logo;
        }
        $fixture->team_b_name = $request->team_b_name;
        if ($request->team_b_logo != '') {
            $team_b_logo = 'team_b_logo'.time().'.'.$request->team_b_logo->extension(); //code for image

            $request->team_b_logo->move(public_path('/uploads/logos/'),$team_b_logo);

            $fixture->team_b_logo = $team_b_logo;
        }

        $fixture->save();
        $fixture->user;

        return response()->json([
            'success' => true,
            'message' => 'fixture added',
            'fixture' => $fixture
        ]);

    }

    public function getFixtures() {
        $fixtures = Fixture::orderBy('id','desc')->get();

        foreach ($fixtures as $fixture) {
            $fixture->user;
        }

        return response()->json([
            'success' => true,
            'fixtures' => $fixtures
        ]);
    }

    public function editFixture(Request $request) {
        $fixture = Fixture::find($request->id);

        if (Auth::user()->id != $fixture->user_id) {
            return response()->json([
                'success' => false,
                'message' => "can't edit"
            ]);
        }

        $fixture->sponsor_name = $request->sponsor_name;
        $fixture->date = $request->date;
        $fixture->time = $request->time;
        $fixture->venue = $request->venue;
        $fixture->team_a_name = $request->team_a_name;
        $fixture->team_b_name = $request->team_b_name;

        $fixture->update();

        return response()->json([
            'success' => true,
            'message' => 'fixture updated'
        ]);
    }

    public function deleteFixture(Request $request) {
        $fixture = Fixture::find($request->id);

        if (Auth::user()->id != $fixture->user_id) {
            return response()->json([
                'success' => false,
                'message' => "can't delete"
            ]);
        }

        $fixture->delete();

        return response()->json([
            'success' => true,
            'message' => 'fixture deleted'
        ]);
    }
}

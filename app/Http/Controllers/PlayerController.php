<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Player;
use App\Team;
use Validator;

class PlayerController extends Controller {

    /**
     * @created on - 07/16/2020
     * @created by - Vikas Badola
     * @param Request $request
     * @desc - Show playes list page
     */
    public function index(Request $request) {
        if (request()->ajax()) {
            return datatables()->of(Player::with('team')->latest()->get())
                ->addColumn('action', function($data) {
                    $button = '<button type="button" name="edit" playerId="' . $data->playerId . '" class="edit btn btn-primary btn-sm"> <i class="fa fa-edit"></i> Edit</button>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<button type="button" name="delete" playerId="' . $data->playerId . '" class="delete btn btn-success btn-sm"><i class="fa fa-external-link"></i> Details</button>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<button type="button" name="delete" playerId="' . $data->playerId . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        // Get all teams list
        $teamList = Team::latest()->get();
        return view('playerList', array('teamList' => $teamList));
    }
    
    /**
     * @created on - 07/16/2020
     * @created by - Vikas Badola
     * @param Request $id
     * @desc - Get playes details by team id
     */
    public function details($id) {
        if (request()->ajax()) {
            return datatables()->of(Player::with('team')->where('teamId',$id)->latest()->get())
                ->addColumn('action', function($data) {
                    $button = '<button type="button" name="edit" playerId="' . $data->playerId . '" class="edit btn btn-primary btn-sm"> <i class="fa fa-edit"></i> Edit</button>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<button type="button" name="delete" playerId="' . $data->playerId . '" class="delete btn btn-success btn-sm"><i class="fa fa-external-link"></i> Details</button>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<button type="button" name="delete" playerId="' . $data->playerId . '" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $teamList = Team::latest()->get();
        return view('playerList', array('teamList' => $teamList,'teamId'=>decrypt($id)));
    }
    
    /**
     * @created on - 07/16/2020
     * @created by - Vikas Badola
     * @param Request $request
     * @desc - Save player data
     * @return json
     */
    public function store(Request $request) {
        //validation rules
        $rules = array(
            'firstName' => 'required',
            'lastName' => 'required',
            'teamName' => 'required',
            'jerseyNo' => 'required|numeric|min:1|max:99',
            'country' => 'required',
            'imageUri' => 'required|image|max:2048'
        );
        //validate request
        $error = Validator::make($request->all(), $rules);
        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        $image = $request->file('imageUri');
        $new_name = rand() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images'), $new_name);
        $form_data = array(
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'teamId' => $request->teamName,
            'jerseyNo' => $request->jerseyNo,
            'country' => $request->country,
            'imageUri' => $new_name
        );
        // Save player data
        Player::create($form_data);
        return response()->json(['success' => 'Data Added successfully.']);
    }
    
    /**
     * @created on - 07/16/2020
     * @created by - Vikas Badola
     * @param type $id
     * @desc - Show edit page for players
     * @return json
     */
    public function edit($id) {
        if (request()->ajax()) {
            $data = Player::where('playerId', $id)->firstOrFail();
            return response()->json(['data' => $data]);
        }
    }
    
    /**
     * @created on - 07/17/2020
     * @created by - Vikas Badola
     * @param Request $request
     * @desc - Updte player details
     * @return json
     */
    public function update(Request $request) {
        $image_name = $request->hidden_image;
        $image = $request->file('imageUri');
        if ($image != '') {
            $rules = array(
                'firstName' => 'required',
                'lastName' => 'required',
                'teamName' => 'required',
                'jerseyNo' => 'required|numeric|min:1|max:99',
                'country' => 'required',
                'imageUri' => 'image|max:2048'
            );
            $error = Validator::make($request->all(), $rules);
            if ($error->fails()) {
                return response()->json(['errors' => $error->errors()->all()]);
            }
            $image_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $image_name);
        } else {
            $rules = array(
                'firstName' => 'required',
                'lastName' => 'required',
                'teamName' => 'required',
                'jerseyNo' => 'required|numeric|min:1|max:99',
                'country' => 'required'
            );
            $error = Validator::make($request->all(), $rules);
            if ($error->fails()) {
                return response()->json(['errors' => $error->errors()->all()]);
            }
        }
        $form_data = array(
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'teamId' => $request->teamName,
            'jerseyNo' => $request->jerseyNo,
            'country' => $request->country,
            'imageUri' => $image_name
        );
        // Update player details
        Player::where('playerId', $request->hidden_id)->update($form_data);
        return response()->json(['success' => 'Data is successfully updated']);
    }
    
    /**
     * @created on - 07/17/2020
     * @created by - Vikas Badola
     * @param type $id
     * @desc - Delete player entry
     */
    public function destroy($id) {
        $data = Player::where('playerId', '=', $id);
        $data->delete();
    }

}

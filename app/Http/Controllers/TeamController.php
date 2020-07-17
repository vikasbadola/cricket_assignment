<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Team;
use Validator;

class TeamController extends Controller
{
    /**
     * @created on - 07/16/2020
     * @created by - Vikas Badola
     * @param Request $request
     * @desc - Show team list page
     */
    public function index(Request $request)
    {
        if(request()->ajax())
        {
            return datatables()->of(Team::latest()->get())
                ->addColumn('action', function($data){
                    $button = '<button type="button" name="edit" teamId="'.$data->teamId.'" class="edit btn btn-primary btn-sm"> <i class="fa fa-edit"></i> Edit</button>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<button type="button" teamId="'.encrypt($data->teamId).'" class="tmDtls btn btn-success btn-sm" onclick=""><i class="fa fa-external-link"></i> Details</button>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<button type="button" name="delete" teamId="'.$data->teamId.'" class="delete btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('teamList');
    }
    
    /**
     * @created on - 07/16/2020
     * @created by - Vikas Badola
     * @param Request $request
     * @desc - Save team data
     * @return json
     */
    public function store(Request $request)
    {
        // Validation rules
        $rules = array(
            'name'    =>  'required',
            'state'     =>  'required',
            'logo'         =>  'required|image|max:2048'
        );
        // Check Validations
        $error = Validator::make($request->all(), $rules);
        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        $image = $request->file('logo');
        $new_name = rand() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images'), $new_name);
        $form_data = array(
            'teamName'        =>  $request->name,
            'state'         =>  $request->state,
            'logoUri'             =>  $new_name
        );
        // Insert data
        Team::create($form_data);
        return response()->json(['success' => 'Data Added successfully.']);
    }
    
    /**
     * @created on - 07/16/2020
     * @created by - Vikas Badola
     * @param type $id
     * @desc - Show edit page for teams
     * @return json
     */
    public function edit($id)
    {
        if(request()->ajax())
        {
            $data = Team::where('teamId', $id)->firstOrFail();
            return response()->json(['data' => $data]);
        }
    }
    
    /**
     * @created on - 07/16/2020
     * @created by - Vikas Badola
     * @param Request $request
     * @desc - Updte team details
     * @return json
     */
    public function update(Request $request)
    {
        $image_name = $request->hidden_image;
        $image = $request->file('logo');
        if($image != '')
        {
            $rules = array(
                'name'    =>  'required',
                'state'     =>  'required',
                'logoUri'         =>  'image|max:2048'
            );
            $error = Validator::make($request->all(), $rules);
            if($error->fails())
            {
                return response()->json(['errors' => $error->errors()->all()]);
            }
            $image_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $image_name);
        }
        else
        {
            $rules = array(
                'name'    =>  'required',
                'state'     =>  'required'
            );
            $error = Validator::make($request->all(), $rules);
            if($error->fails())
            {
                return response()->json(['errors' => $error->errors()->all()]);
            }
        }
        $form_data = array(
            'teamName'       =>   $request->name,
            'state'        =>   $request->state,
            'logoUri'            =>   $image_name
        );
        // Update data
        Team::where('teamId',$request->hidden_id)->update($form_data);
        return response()->json(['success' => 'Data is successfully updated']);
    }
    
    /**
     * @created on - 07/16/2020
     * @created by - Vikas Badola
     * @param type $id
     * @desc - Delete team entry
     */
    public function destroy($id)
    {
        $data = Team::where('teamId','=', $id);
        $data->delete();
    }
}

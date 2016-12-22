<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Validator;
use DB;
use Session;
use Input;

class participantsController extends Controller
{
    public function index(){


        $participants = DB::table('participants')
            ->select(
                'participants.id as participant_id',
                'participants.uuidcard_id as participant_uuidcard_id',
                'participants.clubs_name as participant_club_name',
                'participants.participants_name as participant_name',
                'uuidcards.id as uuidcards_id',
                'uuidcards.uuidcard as uuidcards_uuidcard_name',
                'clubs.id as clubs_id',
                'clubs.club_name as club_name'
            )
            ->leftJoin('uuidcards', 'uuidcards.id', '=', 'participants.uuidcard_id')
            ->leftJoin('clubs', 'clubs.id', '=', 'participants.clubs_name')

            ->paginate(100);


        return view('participants.participants', ['participants' => $participants]);

    }


    public function viewcreate(){

        $uuidlist = DB::table('uuidcards')->select('uuidcards.id as uuidcards_id', 'uuidcards.uuidcard as uuidcards_uuidcard')->get();
        $clubs = DB::table('clubs')->select('clubs.id as clubs_id', 'clubs.club_name')->get();


        return view('participants.create', ['uuidlist' => $uuidlist, 'clubs' => $clubs]);


    }


    public function create(Request $request)
    {
        $participants_name = $request->input('participants_name');
        $uuidcard_id = $request->input('uuidcard_id');
        $clubs_id = $request->input('clubs_id');


        DB::table('participants')->insertGetId(
            [
                'participants_name' => $participants_name,
                'uuidcard_id' => $uuidcard_id,
                'clubs_name' => $clubs_id
            ]
        );

        return redirect('/participants')->with('message', '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>A new participant has been added in the database.</div>');

    }


    public function remove($id) {

        DB::table('participants')->where('id', $id)->delete();

        $data = '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> The participant with <strong>ID ' . $id . '</strong> has been removed from the database.</div>';
        return redirect('/participants')->with('message', $data);
    }


    public function truncate() {

        DB::table('participants')->truncate();

        return redirect('/participants')->with('message', '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>All participants has been removed from the database.</div>');
    }


    public function edit($id){
        $participants = DB::table('participants')
            ->select(
                'participants.id as participant_id',
                'participants.uuidcard_id as participant_uuidcard_id',
                'participants.clubs_name as participant_club_name',
                'participants.participants_name as participant_name',
                'uuidcards.id as uuidcards_id',
                'uuidcards.uuidcard as uuidcards_uuidcard_name',
                'clubs.id as clubs_id',
                'clubs.club_name as club_name'
            )
            ->leftJoin('uuidcards', 'uuidcards.id', '=', 'participants.uuidcard_id')
            ->leftJoin('clubs', 'clubs.id', '=', 'participants.clubs_name')
            ->where('participants.id', '=', $id)
            ->first();

        $clubs = DB::table('clubs')->get();
        $category = DB::table('categories')->get();
        $uuidlist = DB::table('uuidcards')->get();

        return view('participants.edit', ['category' => $category, 'participants' => $participants, 'uuidlist' => $uuidlist, 'clubs' => $clubs]);

    }

    public function update(Request $request, $id){


        $participants_name = $request->input('participants_name');
        $uuidcard_id = $request->input('uuidcard_id');
        $clubs_id = $request->input('clubs_id');


        DB::table('participants')
            ->where('participants.id', '=', $id)
            ->update(['participants_name' => $participants_name, 'uuidcard_id' => $uuidcard_id, 'clubs_name' => $clubs_id]);

        $result = "<div class=\"alert alert-success alert-dismissable\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>The participant with #ID " . $id .  " have been added updated.</div>";
        return redirect('/participants')->with('message', $result);

    }



    public function manage($id){
        $participants = DB::table('participants')
            ->select(
                'participants.id as participant_id',
                'participants.uuidcard_id as participant_uuidcard_id',
                'participants.clubs_name as participant_club_name',
                'participants.participants_name as participant_name',
                'uuidcards.id as uuidcards_id',
                'uuidcards.uuidcard as uuidcards_uuidcard_name',
                'clubs.id as clubs_id',
                'clubs.club_name as club_name'

            )
            ->leftJoin('uuidcards', 'uuidcards.id', '=', 'participants.uuidcard_id')
            ->leftJoin('clubs', 'clubs.id', '=', 'participants.clubs_name')

            ->where('participants.id', '=', $id)
            ->first();


        $manage_id = $id;
        $stages = DB::table('stages')->get();
        $category = DB::table('categories')->get();
        $routes = DB::table('routes')->get();
        $participants_manage = DB::table('participants_manage')
            ->select(
                'id',
                'participants_id',
                'categories_id',
                'stages_name',
                'post_s',
                'post_1',
                'post_2',
                'post_3',
                'post_4',
                'post_5',
                'post_6',
                'post_7',
                'post_8',
                'post_9',
                'post_10',
                'post_11',
                'post_12',
                'post_f'
            )
            ->where('participants_id', '=', $id)->get();


        return view('participants.manage', ['routes' => $routes, 'participants_manage' => $participants_manage, 'stages' => $stages, 'category' => $category, 'manage_id' => $manage_id, 'participants' => $participants]);

    }

    public function manageupdate(Request $request)
    {
        $participant_id = $request->input('participant_id');
        $uuidcards_id = $request->input('uuidcards_id');
        $stage_name = $request->input('stage_name');
        $participant_category = $request->input('participant_category');
        $post_s = $request->input('post_s');
        $post_1 = $request->input('post_1');
        $post_2 = $request->input('post_2');
        $post_3 = $request->input('post_3');
        $post_4 = $request->input('post_4');
        $post_5 = $request->input('post_5');
        $post_6 = $request->input('post_6');
        $post_7 = $request->input('post_7');
        $post_8 = $request->input('post_8');
        $post_9 = $request->input('post_9');
        $post_10 = $request->input('post_10');
        $post_11 = $request->input('post_11');
        $post_12 = $request->input('post_12');
        $post_f = $request->input('post_f');

        $check_id = $participant_id[0];

        $check = DB::table('participants_manage')
            ->where('participants_id', '=', $check_id)
            ->where('stages_name', '=', $stage_name)
            ->first();


        if($check !== null)
        {
            foreach ($participant_id as $value => $value2)
            {
                $pu = $participant_id[$value];
                $xu = $uuidcards_id[$value];
                $xs = $stage_name[$value];
                $xc = $participant_category[$value];
//                $ps = $post_s[$value];
//                $p1 = $post_1[$value];
//                $p2 = $post_2[$value];
//                $p3 = $post_3[$value];
//                $p4 = $post_4[$value];
//                $p5 = $post_5[$value];
//                $p6 = $post_6[$value];
//                $p7 = $post_7[$value];
//                $p8 = $post_8[$value];
//                $p9 = $post_9[$value];
//                $p10 = $post_10[$value];
//                $p11 = $post_11[$value];
//                $p12 = $post_12[$value];
//                $pf = $post_f[$value];



                DB::table('participants_manage')
                    ->where('participants_id', '=', $check_id)
                    ->where('stages_name', '=', $xs)->update(
                        [
                             'categories_id' => $xc
//                            'post_s' => $ps,
//                            'post_1' => $p1,
//                            'post_2' => $p2,
//                            'post_3' => $p3,
//                            'post_4' => $p4,
//                            'post_5' => $p5,
//                            'post_6' => $p6,
//                            'post_7' => $p7,
//                            'post_8' => $p8,
//                            'post_9' => $p9,
//                            'post_10' => $p10,
//                            'post_11' => $p11,
//                            'post_12' => $p12,
//                            'post_f' => $pf

                        ]
                    );
            }


        } else {

            foreach ($participant_id as $value => $value2) {
                $pu = $participant_id[$value];
                $xu = $uuidcards_id[$value];
                $xs = $stage_name[$value];
                $xc = $participant_category[$value];
//                $ps = $post_s[$value];
//                $p1 = $post_1[$value];
//                $p2 = $post_2[$value];
//                $p3 = $post_3[$value];
//                $p4 = $post_4[$value];
//                $p5 = $post_5[$value];
//                $p6 = $post_6[$value];
//                $p7 = $post_7[$value];
//                $p8 = $post_8[$value];
//                $p9 = $post_9[$value];
//                $p10 = $post_10[$value];
//                $p11 = $post_11[$value];
//                $p12 = $post_12[$value];
//                $pf = $post_f[$value];


                DB::table('participants_manage')->insertGetId(
                    [
                        'participants_id' => $pu,
                        'uuidcards_id' => $xu,
                        'stages_name' => $xs,
                        'categories_id' => $xc
//                            'post_s' => $ps,
//                            'post_1' => $p1,
//                            'post_2' => $p2,
//                            'post_3' => $p3,
//                            'post_4' => $p4,
//                            'post_5' => $p5,
//                            'post_6' => $p6,
//                            'post_7' => $p7,
//                            'post_8' => $p8,
//                            'post_9' => $p9,
//                            'post_10' => $p10,
//                            'post_11' => $p11,
//                            'post_12' => $p12,
//                            'post_f' => $pf
                    ]
                );
            }

        }

        return redirect(route('post.manageupdate', array('id' => $check_id)))->with('message', '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>All records has been added in the database.</div>');

    }







}


<?php
//        $testquery =

//        $query = PornstarRank::select('*', DB::raw($query) );

//        $query = Pornstar::select($select, DB::raw($query1), DB::raw($query2), DB::raw($query3), DB::raw($query4))
//            ->join('pornstars_ranks', 'pornstars.id', '=', 'pornstars_ranks.pornstar_id');



//        $test2 = Pornstar::select('*')
//            ->join('pornstars_ranks', function ($join) {
//                $join->on('pornstars.id', '=', 'pornstars_ranks.pornstar_id')
//
//                    ->orderBy('rank_by_date', 'DESC')
//                    ->groupBy('pornstar_id');
//            })->get();
//        dd($test2[8]);




//        $query = Pornstar::select($select)
//            ->join('pornstars_ranks', 'pornstars.id', '=', 'pornstars_ranks.pornstar_id');
//        dd($query);
//        $query = Pornstar::select('*', PornstarRank::whereRaw("rank_by_date = " . date('Y-m-d') . ""))
//            ->join('pornstars_ranks', 'pornstars.id', '=', 'pornstars_ranks.pornstar_id');
use App\Pornstar;
use Illuminate\Support\Facades\DB;

$tests = DB::table('pornstars_ranks')
    ->select('*', DB::raw('(SELECT MAX(rank_by_date) as rank FROM pornstars_ranks)'))
    ->groupBy('pornstar_id')->get();

dd($tests[8]);

$latest = DB::table('pornstars')
    ->select('pornstars.fullname, pornstars_ranks.videos, pornstars_ranks.rank_by_date')
    ->joinSub('(SELECT id, MAX(rank_by_date) as max FROM pornstars_ranks GROUP BY pornstar_id)', function($x){
        $x->on('pornstars_ranks', 'pornstars.id', '=', 'pornstars_ranks.pornstars_id');
    })
    ->get();

//            ->get();

//        SELECT tbl.id, signin, signout FROM tbl INNER JOIN (
//            SELECT id, MAX(signin) AS maxsign FROM tbl GROUP BY id
//        ) ms ON tbl.id = ms.id AND signin = maxsign
//        WHERE tbl.id=1

dd($latest);

$query = DB::table('pornstars')
    ->joinSub($latest, 'last', function($join){
        $join->on('pornstars.id', '=', 'last.pornstar_id')
        ;
    })
//            ->
    ->get();
dd($query);

$test = Pornstar::leftJoin('pornstars_ranks', 'pornstars.id', 'pornstars_ranks.pornstar_id')
//            ->groupby('pornstar_id')
    ->where('pornstar_id', '9')
    ->orderByDesc('rank_by_date')
    ->select('*', DB::raw('MAX(rank_by_date) as last_rank'))

    ->get();
//        DB::connection('mysql_dev')->table('athletes')
//            ->leftJoin('performances','athletes.id','performances.athlete_id')
//            ->where('performances.event_id',1)
//            ->groupBy('athletes.id')
//            ->orderByDesc('personal_best')
//            ->select('athletes.*',DB::raw('MAX(performances.performance) AS personal_best')
//                ->paginate(100)
//        dd($test);

<?php

namespace App\Console\Commands;

use App\Pornhub;
use App\PornstarRank;
use App\Pornstars;
use Illuminate\Console\Command;

class ParsingAmateurModelsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parsing:amateur_model {page}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parsing amateur model data and store it';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $pornhub = new Pornhub();


        $page = $this->argument('page');
        for ($counter = 1; $counter <= $page; $counter++){
            $all_pornstars = $pornhub->getPornstarsByPage('amateur', $counter);

            foreach ($all_pornstars as $pornstar){
                $this->info($pornstar['username']);
                $pornstar_class = new Pornstars();
                $pornstar_rank = new PornstarRank();
                $info_pornstar = $pornhub->getPornstarTest($pornstar['type'], $pornstar['username']);



                if($pornstar_data = Pornstars::where('username', $pornstar['username'])->first()){
                    $pornstar_last_rank = PornstarRank::where('pornstar_id', $pornstar_data['id'])->latest('rank_by_date')->first();
                    if(date('Y-m-d', strtotime($pornstar_last_rank['rank_by_date'])) != date('Y-m-d')){
                        $pornstar_rank['pornstar_id'] = $pornstar_data['id'];
                        $pornstar_rank['rank_by_date'] = date('Y-m-d H:i:s');
                        $pornstar_rank->fill($info_pornstar);
                        $pornstar_rank->save();
                    }else{
                    }
                }else{
                    $info_pornstar['verified'] = $pornstar['verified'];
                    $info_pornstar['type'] = $pornstar['type'];
                    $pornstar_class->fill($info_pornstar);
                    $pornstar_class->save();

                    $info_pornstar['pornstar_id'] = $pornstar_class['id'];
                    $pornstar_rank->fill($info_pornstar);
                    $pornstar_rank->save();
                }
            }
        }
    }
}

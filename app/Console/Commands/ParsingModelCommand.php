<?php

namespace App\Console\Commands;

use App\Pornhub;
use App\PornhubActor;
use App\PornstarData;
use App\PornstarRank;
use App\Pornstars;
use Illuminate\Console\Command;
use App\ModelData;

class ParsingModelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pornhub:parsing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parsing of all top trending pornstars and models';

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
        $page = $this->ask("How many pages do you want check?");


        if($this->confirm("Are you sure you want parsing new top trending pornstars and models?")) {

           // $this->warn('Deleting all old data...');
           // AmateurModel::query()->truncate();
           // ModelData::query()->truncate();
            // $this->warn('Done!');
            $pornhub = new Pornhub();
            if ($this->confirm("Do you want parsing the new top trending Pornstars?")) {

                for ($counter = 1; $counter <= $page; $counter++) {
                    $this->info('Getting all pornstars of ' . $counter . ' page');
                    $pornstars = $pornhub->getPornstarByPage($page);
                    $n_pornstars = count($pornstars);
                    $this->info($n_pornstars . ' Pornstars taken!');
                    $this->info('Getting and storing info pornstars...');


                    $bar = $this->output->createProgressBar(count($pornstars));


                    foreach ($pornstars as $username_verified_pornstar) {
                        $pornstar_data = $pornhub->getPornstar($username_verified_pornstar['username']);

                        //if is not available
                        if ($pornstar_data['available'] == 0) {
                            if ($pornstar = Pornstars::where('full_name', $pornstar_data['pornstar_name'])->first()) {
                                $rank_date = date(PornstarRank::where('pornstar_id', $pornstar->id)->latest('rank_by_date')->first());
                                if ($rank_date != date('Y-m-d')) {
                                    PornstarRank::create([
                                        'pornstar_id' => $pornstar->id,
                                        'rank_by_date' => date('Y-m-d H:i:s')
                                    ]);
                                }

                            } else {
                                $info_pornstar = Pornstars::create([
                                    'full_name' => $pornstar_data['pornstar_name'],
                                    'username' => $username_verified_pornstar['username'],
                                    'verified' => $pornstar_data['verified'],
                                    'available' => $pornstar_data['available']
                                ]);
                                PornstarRank::create([
                                    'pornstar_id' => $info_pornstar->id,
                                    'rank_by_date' => date('Y-m-d H:i:s')
                                ]);

                            }
                        } elseif ($pornstar_data['available'] == 1) {
                            if ($pornstar = Pornstars::where('full_name', $pornstar_data['pornstar_name'])->where('birth_date', $pornstar_data['birth_date'])->first()) {
                                $pornstar->update([
                                    'link_img' => $pornstar_data['link_img'],
                                    'age' => $pornstar_data['age'],
                                    'type' => $pornstar_data['type'],
                                    'verified' => $username_verified_pornstar['verified'],
                                    'joined' => $pornstar_data['joined'],
                                    'modelhub' => $pornstar_data['modelhub'],
                                    'website' => $pornstar_data['website'],
                                    'twitter' => $pornstar_data['twitter'],
                                    'fan_centro' => $pornstar_data['fan_centro'],
                                    'instagram' => $pornstar_data['instagram']
                                ]);

                                $rank_date = date(PornstarRank::where('pornstar_id', $pornstar->id)->latest('rank_by_date')->first());

                                if ($rank_date != date('Y-m-d')) {
                                    PornstarRank::create([
                                        'pornstar_id' => $pornstar->id,
                                        'weekly' => $pornstar_data['weekly_rank'],
                                        'monthly' => $pornstar_data['monthly_rank'],
                                        'last_month' => $pornstar_data['last_month_rank'],
                                        'yearly' => $pornstar_data['yearly_rank'],
                                        'video' => $pornstar_data['videos'],
                                        'visual' => $pornstar_data['visuals'],
                                        'subscriber' => $pornstar_data['subscribers'],
                                        'rank_by_date' => date('Y-m-d H:i:s'),
                                    ]);
                                }

                            } else {
                                $info_pornstar = Pornstars::create([
                                    'full_name' => $pornstar_data['pornstar_name'],
                                    'username' => $pornstar_data['username'],
                                    'link_img' => $pornstar_data['link_img'],
                                    'type' => $pornstar_data['type'],
                                    'verified' => $username_verified_pornstar['verified'],
                                    'available' => $pornstar_data['available'],
                                    'age' => $pornstar_data['age'],
                                    'birth_date' => $pornstar_data['birth_date'],
                                    'joined' => $pornstar_data['joined'],
                                    'modelhub' => $pornstar_data['modelhub'],
                                    'website' => $pornstar_data['website'],
                                    'twitter' => $pornstar_data['twitter'],
                                    'fan_centro' => $pornstar_data['fan_centro'],
                                    'instagram' => $pornstar_data['instagram']
                                ]);

                                PornstarRank::create([
                                    'pornstar_id' => $info_pornstar->id,
                                    'weekly' => $pornstar_data['weekly_rank'],
                                    'monthly' => $info_pornstar['monthly_rank'],
                                    'last_month' => $pornstar_data['last_month_rank'],
                                    'yearly' => $pornstar_data['yearly_rank'],
                                    'video' => $pornstar_data['videos'],
                                    'visual' => $pornstar_data['visuals'],
                                    'subscriber' => $pornstar_data['subscribers'],
                                    'rank_by_date' => date('Y-m-d H:i:s'),
                                ]);
                            }
                        }
                        $bar->advance();
                    }
                    $bar->finish();
                    $this->info("\n");
                }
                $this->info("\nAll pornstars are successful insert into DB");
            }

            if ($this->confirm("Do you want parsing the new top trending models?")) {

                for ($counter = 1; $counter <= $page; $counter++) {

                    $this->info('Getting all models of ' . $counter . ' page');
                    $models = $pornhub->getModelsByPage($page);
                    $n_models = count($models);
                    $this->info($n_models . 'Models taken!');
                    $this->info('Getting and storing info models...');

                    $bar = $this->output->createProgressBar(count($models));

                    foreach ($models as $username_verified_model) {

                        $model_data = $pornhub->getModel($username_verified_model['username']);

                        if ($model_data['available'] == 0) {
                            if ($model = Pornstars::where('full_name', $model_data['model_name'])->first()) {
                                $rank_date = date(PornstarRank::where('pornstar_id', $model->id)->latest('rank_by_date')->first());
                                if ($rank_date != date('Y-m-d')) {
                                    PornstarRank::create([
                                        'pornstar_id' => $pornstar->id,
                                        'rank_by_date' => date('Y-m-d H:i:s')
                                    ]);
                                }
                            } else {
                                $info_model = Pornstars::create([
                                    'full_name' => $model_data['model_name'],
                                    'username' => $model_data['username'],
                                    'available' => $model_data['available'],
                                    'verified' => $username_verified_model['verified']
                                ]);
                                PornstarRank::create([
                                    'pornstar_id' => $info_model->id,
                                    'rank_by_date' => date('Y-m-d H:i:s')
                                ]);
                            }
                        } elseif ($model_data['available'] == 1) {

                            if ($model = Pornstars::where('full_name', $model_data['model_name'])->where('birth_date', $model_data['birth_date'])->first()) {
                                $model->update([
                                    'link_img' => $model_data['link_img'],
                                    'username' => $model_data['username'],
                                    'age' => $model_data['age'],
                                    'type' => $model_data['type'],
                                    'verified' => $username_verified_model['verified'],
                                    'joined' => $model_data['joined'],
                                    'modelhub' => $model_data['modelhub'],
                                    'website' => $model_data['website'],
                                    'twitter' => $model_data['twitter'],
                                    'fan_centro' => $model_data['fan_centro'],
                                    'instagram' => $model_data['instagram']
                                ]);

                                $rank_date = date(PornstarRank::where('pornstar_id', $model->id)->latest('rank_by_date')->first());

                                if ($rank_date != date('Y-m-d')) {
                                    PornstarRank::create([
                                        'pornstar_id' => $model->id,
                                        'weekly' => $model_data['weekly_rank'],
                                        'monthly' => $model_data['monthly_rank'],
                                        'last_month' => $model_data['last_month_rank'],
                                        'yearly' => $model_data['yearly_rank'],
                                        'video' => $model_data['videos'],
                                        'visual' => $model_data['visuals'],
                                        'subscriber' => $model_data['subscribers'],
                                        'rank_by_date' => date('Y-m-d H:i:s'),
                                    ]);
                                }

                            } else {
                                $info_model = Pornstars::create([
                                    'full_name' => $model_data['model_name'],
                                    'username' => $model_data['username'],
                                    'link_img' => $model_data['link_img'],
                                    'type' => $model_data['type'],
                                    'verified' => $username_verified_model['verified'],
                                    'available' => $model_data['available'],
                                    'age' => $model_data['age'],
                                    'birth_date' => $model_data['birth_date'],
                                    'joined' => $model_data['joined'],
                                    'modelhub' => $model_data['modelhub'],
                                    'website' => $model_data['website'],
                                    'twitter' => $model_data['twitter'],
                                    'fan_centro' => $model_data['fan_centro'],
                                    'instagram' => $model_data['instagram']
                                ]);

                                PornstarRank::create([
                                    'pornstar_id' => $info_model->id,
                                    'weekly' => $model_data['weekly_rank'],
                                    'monthly' => $model_data['monthly_rank'],
                                    'last_month' => $model_data['last_month_rank'],
                                    'yearly' => $model_data['yearly_rank'],
                                    'video' => $model_data['videos'],
                                    'visual' => $model_data['visuals'],
                                    'subscriber' => $model_data['subscribers'],
                                    'rank_by_date' => date('Y-m-d H:i:s'),
                                ]);
                            }
                        }
                        $bar->advance();

                    }
                    $bar->finish();
                }
                $this->info("\nAll models are successful insert into DB");
            }
            $this->info("\nDone!");
        }
    }
}

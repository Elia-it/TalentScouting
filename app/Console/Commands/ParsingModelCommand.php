<?php

namespace App\Console\Commands;

use App\Pornhub;
use Illuminate\Console\Command;
use App\AmateurModel;
use App\ModelRank;
use App\ModelData;

class ParsingModelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'models:parsing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parsing of all top trending amateur models';

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


        if($this->confirm("Are you sure you want parsing new top trending models?")) {

           // $this->warn('Deleting all old data...');
           // AmateurModel::query()->truncate();
           // ModelData::query()->truncate();
            // $this->warn('Done!');
            $pornhub = new Pornhub();

            $this->info('Getting all models of first ' . $page . ' pages');
            $models = $pornhub->getModelsByPage($page);
            $this->info('Models taken!');
            $this->info('Getting and storing info models...');

            $bar = $this->output->createProgressBar(count($models));

            foreach ($models as $model){
                $personal_data = $pornhub->getModel($model);
                if($personal_data['available'] == 0){
                    if(AmateurModel::where('full_name', $personal_data['model_name'])->first()){

                    }else{
                            $info_model = AmateurModel::create([
                                'full_name' => $personal_data['model_name'],
                                'available' => $personal_data['available']
                            ]);
                            ModelData::create([
                                'model_id' => $info_model->id,
                                'rank_by_date' => date('Y-m-d H:i:s')
                            ]);
                    }

                }elseif($personal_data['available'] == 1){
                    if(AmateurModel::where('full_name', $personal_data['model_name'])->where('birth_date', $personal_data['birth_date'])->first()){
                        $model_to_update = AmateurModel::where('full_name', $personal_data['model_name'])->where('birth_date', $personal_data['birth_date'])->first();
                        $model_to_update->update([
                            'link_img' => $personal_data['link_img'],
                            'age' => $personal_data['age'],
                            'birth_date' => $personal_data['birth_date'],
                            'joined' => $personal_data['joined'],
                            'modelhub' => $personal_data['modelhub'],
                            'website' => $personal_data['website'],
                            'twitter' => $personal_data['twitter'],
                            'fan_centro' => $personal_data['fan_centro'],
                            'instagram' => $personal_data['instagram']
                        ]);
                        ModelData::where('model_id', $model_to_update->id)->update([
                            'weekly' => $personal_data['weekly_rank'],
                            'monthly' => $personal_data['monthly_rank'],
                            'last_month' => $personal_data['last_month_rank'],
                            'yearly' => $personal_data['yearly_rank'],
                            'video' => $personal_data['videos'],
                            'visual' => $personal_data['visuals'],
                            'subscriber' => $personal_data['subscribers'],
                            'rank_by_date' => date('Y-m-d H:i:s', strtotime("+5day", strtotime(date('Y-m-d H:i:s')))),
                        ]);

                    }else{
                        $info_model = AmateurModel::create([
                            'full_name' => $personal_data['model_name'],
                            'link_img' => $personal_data['link_img'],
                            'available' => $personal_data['available'],
                            'age' => $personal_data['age'],
                            'birth_date' => $personal_data['birth_date'],
                            'joined' => $personal_data['joined'],
                            'modelhub' => $personal_data['modelhub'],
                            'website' => $personal_data['website'],
                            'twitter' => $personal_data['twitter'],
                            'fan_centro' => $personal_data['fan_centro'],
                            'instagram' => $personal_data['instagram']
                        ]);

                        ModelData::create([
                            'model_id' => $info_model->id,
                            'weekly' => $personal_data['weekly_rank'],
                            'monthly' => $personal_data['monthly_rank'],
                            'last_month' => $personal_data['last_month_rank'],
                            'yearly' => $personal_data['yearly_rank'],
                            'video' => $personal_data['videos'],
                            'visual' => $personal_data['visuals'],
                            'subscriber' => $personal_data['subscribers'],
                            'rank_by_date' => date('Y-m-d H:i:s'),
                        ]);
                    }
                }
                $bar->advance();
            }
            $bar->finish();
            $this->info("\nAll models are successful insert into DB");


        }
    }
}

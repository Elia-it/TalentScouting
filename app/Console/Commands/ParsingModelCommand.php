<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\AmateurModel;
use App\ModelRank;

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

        $this->warn("This action will delete all data on the database");
        if($this->confirm("Are you sure you want parsing new top trending models?")) {

            $this->warn('Deleting all old data...');
            AmateurModel::query()->truncate();
            ModelRank::query()->truncate();
            $this->warn('Done!');

            $this->info("Parsing...");
            $all_models = get_parsing_models($page);
            $this->info(' Done!');

            $bar = $this->output->createProgressBar(count($all_models));
            $bar->start();

            foreach ($all_models as $model) {

                $model_data = AmateurModel::create([
                    'model_name' => $model['model_name'],
                    'last_name' => $model['last_name'],
                    'link_img' => $model['link_img'],
                    'available' => $model['available'],
                    'age' => $model['age'],
                    'birth_date' => $model['birth_date'],
                    'joined' => $model['joined'],
                    'video' => $model['videos'],
                    'visual' => $model['visuals'],
                    'subscriber' => $model['subscribers'],
                    'modelhub' => $model['modelHub'],
                    'website' => $model['official_site'],
                    'twitter' => $model['twitter'],
                    'fan_centro' => $model['fan_centro'],
                    'instagram' => $model['instagram']
                ]);

                ModelRank::create([
                    'model_id' => $model_data->id,
                    'weekly' => $model['weekly'],
                    'monthly' => $model['monthly'],
                    'last_month' => $model['last_month'],
                    'yearly' => $model['yearly'],
                    'rank_by_date' => date('Y/m/d H:i:s'),
                ]);


                $this->info("\nSaved Model: " . $model['model_name']);
                $bar->advance();
            }
            $bar->finish();
            $this->info("\nAll models are successful insert into DB");
        }
    }
}

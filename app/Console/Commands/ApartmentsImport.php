<?php

namespace App\Console\Commands;

use App\Models\Apartment;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ApartmentsImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:apartments {filename}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Apartments from csv file';

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
     * @return int
     */
    public function handle()
    {
        try {
            $filename = $this->argument('filename') . '.csv';
            $url = Storage::path('public\exports\\' . $filename);
            $this->output->progressStart();
            $file = fopen($url, 'r');
            while (($apartment = fgetcsv($file)) !== FALSE) {
                Apartment::create([
                    'name' => $apartment[1],
                    'slug' => $apartment[2],
                    'price' => $apartment[3],
                    'currency' => $apartment[4],
                    'description' => $apartment[5],
                    'properties' =>  json_decode($apartment[6]),
                    'category_id' => $apartment[7],
                    'rating' => $apartment[8],
                ]);
                $this->output->progressAdvance();

            }
            fclose($file);
            $this->output->progressFinish();

            Log::info('Succesifull import');
            $this->info('Succesifull import');
            return Command::SUCCESS;
        } catch (Exception $e) {
            Log::info('Failed import: ' . $e->getMessage());
            $this->info('Failed import: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}

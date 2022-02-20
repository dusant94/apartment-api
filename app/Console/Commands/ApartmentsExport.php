<?php

namespace App\Console\Commands;

use App\Models\Apartment;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ApartmentsExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:apartments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export Apartments to csv file';

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
            $apartments = Apartment::all();
            $this->output->progressStart($apartments->count());

            if (!Storage::disk('')->has('public/exports')) {
                Storage::disk('')->makeDirectory('public/exports');
            }
            $filename = Carbon::now()->format("Y-m-d-H-i") . '-apartments-export.csv';
            $url = Storage::path('/public/exports/' . $filename);
            $file = fopen($url, 'w');

            foreach ($apartments as $apartment) {
                $apartment->properties = json_encode($apartment->properties);
                fputcsv($file, $apartment->toArray(), ';', '"', '\\');
                $this->output->progressAdvance();
            }
            fclose($file);
            $this->output->progressFinish();
            Log::info('Succesifull export');
            $this->info('Succesifull export');
            return Command::SUCCESS;
        } catch (Exception $e) {
            Log::info('Failed export: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}

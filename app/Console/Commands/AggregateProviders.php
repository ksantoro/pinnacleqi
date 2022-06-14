<?php

namespace App\Console\Commands;

use App\Models\CmsGovApi;
use App\Models\CmsProvider;
use Illuminate\Console\Command;
use function GuzzleHttp\Promise\all;

class AggregateProviders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cmsProviders:aggregate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Aggregate the list of all CMS providers.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            if ($cmsProviders = CmsProvider::all()->sortBy('number_of_certified_beds')) {
                $cmsProviderCount = $cmsProviders->count();
                $largeCount = $cmsProviderCount * (33/100);
                $mediumCount = $cmsProviderCount * (33/100);
                $smallCount = $cmsProviderCount * (34/100);

                $largeProviders = $cmsProviders->take($largeCount)->get();
                $mediumProviders = $cmsProviders->skip($largeCount)->take($mediumCount)->get();
                $smallProviders = $cmsProviders->skip(($largeCount+$mediumCount))->take($smallCount);

                Excel::download(new CmsProvidersExport, 'largeCmsProviders.xlsx');
            }

            return 1;
        } catch (\Exception $exception) {
            $this->output('There was an error fetching CMS providers. Error Details: ' . $exception->getMessage());
        }

        return 0;
    }
}

<?php

namespace App\Console\Commands;

use App\Models\CmsGovApi;
use Illuminate\Console\Command;

class FetchCmsProviders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cmsProviders:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch all CMS providers.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $api = new CmsGovApi();
            $api->fetchCmsProviders();
            return 1;
        } catch (\Exception $exception) {
            $this->output('There was an error fetching CMS providers. Error Details: ' . $exception->getMessage());
        }

        return 0;
    }
}

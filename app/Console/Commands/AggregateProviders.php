<?php

namespace App\Console\Commands;

use App\Models\CmsProvider;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
            if ($cmsProviders = CmsProvider::all()->sortByDesc('number_of_certified_beds')) {
                $cmsProviderCount = $cmsProviders->count();
                $largeCount = (int) ($cmsProviderCount * (33/100));
                $mediumCount = (int) ($cmsProviderCount * (33/100));
                $smallCount = $cmsProviderCount - ($largeCount + $mediumCount);

                $largeProviders = $cmsProviders->take($largeCount)->sortBy('number_of_certified_beds');
                $mediumProviders = $cmsProviders->skip($largeCount)->take($mediumCount)->sortBy('number_of_certified_beds');
                $smallProviders = $cmsProviders->skip(($largeCount+$mediumCount))->take($smallCount)->sortBy('number_of_certified_beds');

                $this->buildCsv($largeProviders, 'large-providers');
                $this->buildCsv($mediumProviders, 'medium-providers');
                $this->buildCsv($smallProviders, 'small-providers');
            }

            return 1;
        } catch (\Exception $exception) {
            Log::info('There was an error fetching CMS providers. Error Details: ', ['Exception' => $exception->getMessage()]);
        }

        return 0;
    }

    public function buildCsv($data, $name)
    {
        $fileName = "{$name}.csv";
        $filePath = public_path($fileName);

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="'.$fileName.'"');

        $columns = [
            'Beds',
            'Provider',
            'Federal Number',
        ];

        if (file_exists($filePath)){
            unlink($filePath);
        }

        $file = fopen($filePath, 'wb+');
        fputcsv($file, $columns);

        foreach ($data as $datum) {
            $row['Beds'] = $datum->number_of_certified_beds;
            $row['Provider'] = $datum->provider_name;
            $row['Federal Number'] = $datum->federal_provider_number;

            fputcsv($file, [
                $row['Beds'],
                $row['Provider'],
                $row['Federal Number'],
            ]);
        }

        fclose($file);
    }
}

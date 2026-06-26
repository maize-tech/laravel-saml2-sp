<?php

namespace Maize\Saml2Sp\Console;

use Illuminate\Console\Command;
use Maize\Saml2Sp\Facades\Saml2Sp;
use Maize\Saml2Sp\Models\SamlConfig;
use Maize\Saml2Sp\Support\Config;

class MetadataCommand extends Command
{
    protected $signature = 'saml2-sp:metadata
        {config? : The key or id of the SamlConfig to use (defaults to the first one)}
        {--output= : Write the metadata to the given file path instead of the console}';

    protected $description = 'Generate the SAML service provider metadata XML';

    public function handle(): int
    {
        $samlConfig = $this->resolveConfig();

        if (is_null($samlConfig)) {
            $this->components->error('No SAML config found.');

            return self::FAILURE;
        }

        $metadata = Saml2Sp::metadata($samlConfig);

        $output = $this->option('output');

        if (filled($output)) {
            $output = (string) $output;

            file_put_contents($output, $metadata);

            $this->components->info("Metadata written to {$output}.");

            return self::SUCCESS;
        }

        $this->line($metadata);

        return self::SUCCESS;
    }

    protected function resolveConfig(): ?SamlConfig
    {
        $model = Config::getSamlConfigModel();
        $identifier = $this->argument('config');

        if (is_null($identifier)) {
            /** @var SamlConfig|null */
            return $model->query()->first();
        }

        /** @var SamlConfig|null */
        return $model->query()
            ->where('key', $identifier)
            ->orWhere($model->getKeyName(), $identifier)
            ->first();
    }
}

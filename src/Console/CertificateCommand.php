<?php

namespace Maize\Saml2Sp\Console;

use Illuminate\Console\Command;

class CertificateCommand extends Command
{
    protected $signature = 'saml2-sp:certificate
        {--cn= : The common name (CN) for the certificate (defaults to the app url host)}
        {--days=3650 : Number of days the certificate is valid}';

    protected $description = 'Generate a self-signed certificate and private key for the SAML service provider';

    public function handle(): int
    {
        if (! extension_loaded('openssl')) {
            $this->components->error('The openssl PHP extension is required.');

            return self::FAILURE;
        }

        $commonName = (string) ($this->option('cn')
            ?: parse_url((string) config('app.url'), PHP_URL_HOST)
            ?: 'localhost');

        $days = (int) $this->option('days');

        $privateKey = openssl_pkey_new([
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);

        $csr = $privateKey === false
            ? false
            : openssl_csr_new(['commonName' => $commonName], $privateKey, ['digest_alg' => 'sha256']);

        $x509 = $csr === false
            ? false
            : openssl_csr_sign($csr, null, $privateKey, $days, ['digest_alg' => 'sha256']);

        if ($privateKey === false || $csr === false || $x509 === false) {
            $this->components->error('Unable to generate the certificate. Check your openssl configuration.');

            return self::FAILURE;
        }

        openssl_x509_export($x509, $certificate);
        openssl_pkey_export($privateKey, $key);

        $this->components->info("Certificate (CN={$commonName}, valid for {$days} days):");
        $this->line($certificate);
        $this->newLine();
        $this->components->info('Private key:');
        $this->line($key);

        return self::SUCCESS;
    }
}

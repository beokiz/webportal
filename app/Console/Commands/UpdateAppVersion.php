<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych, Pavel Karpushevskiy
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateAppVersion extends Command
{
    /**
     * @var string
     */
    protected $signature = 'version:update {type=patch}';

    /**
     * @var string
     */
    protected $description = 'Update the application version in all environment files';

    /**
     * @return void
     */
    public function handle()
    {
        // Get the update type argument
        $type = $this->argument('type');

        // Define the list of environment files to be updated
        $envFiles = [
            base_path('.env'),
            base_path('.env.dev.example'),
            base_path('.env.example'),
            base_path('.env.local'),
            base_path('.env.prod'),
        ];

        // Get the current version from the environment variable
        $version = config('app.version');

        // Match the version format and extract major, minor, and patch numbers
        if (preg_match('/(\d+)\.(\d+)\.(\d+)/', $version, $matches)) {
            list($major, $minor, $patch) = [$matches[1], $matches[2], $matches[3]];

            // Update the version based on the provided type
            switch ($type) {
                case 'major':
                    $major++;
                    $minor = 0;
                    $patch = 0;
                    break;
                case 'minor':
                    $minor++;
                    $patch = 0;
                    break;
                case 'patch':
                default:
                    $patch++;
                    break;
            }

            // Create the new version string
            $newVersion = "{$major}.{$minor}.{$patch}";

            // Update each environment file with the new version
            foreach ($envFiles as $envPath) {
                if (file_exists($envPath)) {
                    file_put_contents($envPath, preg_replace(
                        '/^APP_VERSION=.*/m',
                        'APP_VERSION=' . $newVersion,
                        file_get_contents($envPath)
                    ));
                } else {
                    $this->warn(__('commands.version_update.file_error_message', ['filepath' => $envPath]));
                }
            }

            $this->info(__('commands.version_update.success_message', ['version' => $newVersion]));
        } else {
            $this->error(__('commands.version_update.version_error_message'));
        }
    }
}

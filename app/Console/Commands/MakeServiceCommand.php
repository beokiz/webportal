<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Console\Commands;

use App\Helpers\StubGeneratorHelper;
use Illuminate\Console\Command;

/**
 * Make Service Command
 *
 * @package \App\Console\Commands
 */
class MakeServiceCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * @var string
     */
    protected $description = 'Create a new service class
                                    {name : New Service name}';

    /**
     * @var StubGeneratorHelper
     */
    protected $stubGenerator;

    /**
     * MakeServiceCommand constructor.
     *
     * @param StubGeneratorHelper $stubGenerator
     * @return void
     */
    public function __construct(StubGeneratorHelper $stubGenerator)
    {
        parent::__construct();

        $this->stubGenerator = $stubGenerator;
    }

    /**
     * @return void
     */
    public function handle()
    {
        try {
            $className = get_singular_class_name($this->argument('name'));

            $stubPath     = config('stub-generator.stub_path.service');
            $namespace    = config('stub-generator.namespace.service');
            $baseFileName = config('stub-generator.base_file_name.service');

            $fileName = $className . $baseFileName;

            $stubVars = [
                'namespace'  => $namespace,
                'class_name' => $className,
            ];

            $result = $this->stubGenerator->makeFromStub($stubPath, $namespace, $fileName, $stubVars);

            $this->info($result);
        } catch (\Exception $exception) {
            $this->error(__('artisan.common.error'));
            $this->error(__('artisan.common.exception', ['exception' => $exception->getMessage()]));
        }
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeRepository extends Command
{
    protected $signature = 'make:repository {domain} {name}';

    public function handle()
    {
        $domain = ucfirst($this->argument('domain'));
        $name   = ucfirst($this->argument('name'));

        $path = "app/Domain/{$domain}/Repositories";

        mkdir($path, 0755, true);

        file_put_contents("{$path}/{$name}.php", <<<PHP
<?php

namespace App\Domain\\{$domain}\Repositories;

class {$name}
{
    //
}
PHP);

        $this->info("Repository {$name} created.");
    }
}

<?php

namespace Junges\ACL\Console\Commands;

use Illuminate\Console\Command;

class CreateGroup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'group:create {name} {slug} {description}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new group on groups table';

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
     * @return mixed
     */
    public function handle()
    {
        try{
            $groupModel = app(config('acl.models.group'));
            if ($this->confirm("Deseja criar um grupo com o nome '"
                .$this->argument('name')."' e slug '".$this->argument('slug')."'?")){
                $groupModel->create([
                   'name' => $this->argument('name'),
                   'slug' => $this->argument('slug'),
                   'description' => $this->argument('description'),
                ]);
                $this->info("Grupo criado com sucesso!");
            }else{
                $this->info("O grupo não foi criado.");
            }

        }catch (\Exception $exception){
            $this->error("Algo deu errado. ".$exception->getMessage());
        }
    }
}

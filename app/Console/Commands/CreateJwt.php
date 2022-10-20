<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CreateJwt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jwt:create {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a jwt to work with locally';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $token = User::find($this->argument('user'))->createToken('dev');

        $this->info(sprintf('User %d JWT: %s', $this->argument('user'), $token->plainTextToken));
    }
}

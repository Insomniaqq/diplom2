<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ResetMonthlyDistributedQuantity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'norms:reset-monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Сбрасывает ежемесячно выданное количество материалов по отделам.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        \App\Models\MaterialDistribution::truncate();

        $this->info('Ежемесячное выданное количество материалов сброшено.');

        return Command::SUCCESS;
    }
}

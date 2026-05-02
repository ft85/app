<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixCurrencySeparators extends Seeder
{
    /**
     * Run the database seeds to fix currency separator settings.
     *
     * @return void
     */
    public function run()
    {
        // Fix BIF currency separators if they exist
        DB::table('currencies')
            ->where('code', 'BIF')
            ->update([
                'thousand_separator' => ',',
                'decimal_separator' => '.',
            ]);

        // Check for any currencies with incorrect separator configuration
        // (thousand separator should not be the same as decimal separator)
        $currencies = DB::table('currencies')->get();
        
        foreach ($currencies as $currency) {
            if ($currency->thousand_separator === $currency->decimal_separator) {
                // Fix: set standard separators
                DB::table('currencies')
                    ->where('id', $currency->id)
                    ->update([
                        'thousand_separator' => ',',
                        'decimal_separator' => '.',
                    ]);
            }
            
            // Fix if thousand separator is '.' and decimal is not '.'
            // This is a common misconfiguration that causes the issue
            if ($currency->thousand_separator === '.' && $currency->decimal_separator !== '.') {
                DB::table('currencies')
                    ->where('id', $currency->id)
                    ->update([
                        'thousand_separator' => ',',
                        'decimal_separator' => '.',
                    ]);
            }
        }

        $this->command->info('Currency separator settings have been fixed.');
    }
}

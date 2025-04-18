<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EmailList;
use App\Models\Subscriber;

class EmailListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmailList::factory()->count(50)->create()
        ->each(function (EmailList $list){
            
            Subscriber::factory()
            ->count(rand(50,200))
            ->create(['email_list_id'=>$list->id]);
        });
    }
}

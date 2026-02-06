<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use StaticKidz\BedcaAPI\BedcaClient;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $client = new BedcaClient();
        $groups = $client->getFoodGroups();

        if (isset($groups->food)) {
            foreach ($groups->food as $group) {
                Category::firstOrCreate(
                    ['bedca_id' => $group->fg_id],
                    ['name' => $group->fg_ori_name]
                );
            }
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Organization;

use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        $organizations = Organization::factory(100)->create();

        Contact::factory(100)
        ->each(function ($contact) use ($organizations) {
            $contact->update(['organization_id' => $organizations->random()->id]);
        });
    }
}

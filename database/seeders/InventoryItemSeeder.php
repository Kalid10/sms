<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class InventoryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $admin = User::find(1)->id;
        foreach ($this->fakeData() as $data) {
            \App\Models\InventoryItem::create($data);
        }

    }

    protected function fakeData()
    {
        return [
            [
                'name' => 'Chalk',
                'description' => 'Set of 12 assorted chalks',
                'quantity' => 10,
                'is_returnable' => false,
                'date' => '2023-09-04',
                'low_stock_threshold' => 5,
                'visibility' => 'public',
                'added_by_user_id' => User::find(1)->id,
            ],
            [
                'name' => 'Desktop Computers',
                'description' => 'Desktop computers for computer labs',
                'quantity' => 20,
                'is_returnable' => true,
                'date' => '2023-09-04',
                'low_stock_threshold' => 5,
                'visibility' => 'public',
                'added_by_user_id' => 1,
            ],
            [
                'name' => 'Advanced French Grammar',
                'description' => 'Grammar and syntax for advanced learners',
                'quantity' => 20,
                'is_returnable' => true,
                'date' => '2023-09-02',
                'low_stock_threshold' => 4,
                'visibility' => 'public',
                'added_by_user_id' => 1,
            ],
            [
                'name' => 'Math Textbook',
                'description' => 'Math textbook for grade 10 students',
                'quantity' => 100,
                'is_returnable' => true,
                'date' => '2023-09-02',
                'low_stock_threshold' => 20,
                'visibility' => 'public',
                'added_by_user_id' => User::find(1)->id,
            ],
            [
                'name' => 'Scientific Calculators',
                'description' => 'Scientific calculators for math and science classes',
                'quantity' => 30,
                'is_returnable' => true,
                'date' => '2023-09-04',
                'low_stock_threshold' => 5,
                'visibility' => 'public',
                'added_by_user_id' => 1,
            ],
            [
                'name' => 'Markers',
                'description' => 'Set of 12 assorted markers',
                'quantity' => 20,
                'is_returnable' => false,
                'date' => '2023-09-04',
                'low_stock_threshold' => 5,
                'visibility' => 'public',
                'added_by_user_id' => User::find(1)->id,
            ],
            [
                'name' => 'Projector',
                'description' => 'High-resolution classroom projector',
                'quantity' => 10,
                'is_returnable' => true,
                'date' => '2023-09-04',
                'low_stock_threshold' => 3,
                'visibility' => 'public',
                'added_by_user_id' => User::find(1)->id,
            ],
            [
                'name' => 'Chairs',
                'description' => 'Set of 20 standard school chairs',
                'quantity' => 20,
                'is_returnable' => true,
                'date' => '2023-09-04',
                'low_stock_threshold' => 0,
                'visibility' => 'public',
                'added_by_user_id' => User::find(1)->id,
            ],
            [
                'name' => 'Rulers',
                'description' => 'Set of 5 plastic rulers',
                'quantity' => 25,
                'is_returnable' => true,
                'date' => '2023-09-04',
                'low_stock_threshold' => 5,
                'visibility' => 'public',
                'added_by_user_id' => User::find(1)->id,
            ],
            [
                'name' => 'Pencils',
                'description' => 'Set of 10 pencils',
                'quantity' => 50,
                'is_returnable' => false,
                'date' => '2023-09-04',
                'low_stock_threshold' => 10,
                'visibility' => 'public',
                'added_by_user_id' => User::find(1)->id,
            ],
            [
                'name' => 'Pens',
                'description' => 'Set of 10 pens',
                'quantity' => 50,
                'is_returnable' => false,
                'date' => '2023-09-04',
                'low_stock_threshold' => 10,
                'visibility' => 'public',
                'added_by_user_id' => User::find(1)->id,
            ],
            [
                'name' => 'Staplers',
                'description' => 'Office staplers',
                'quantity' => 20,
                'is_returnable' => true,
                'date' => '2023-09-04',
                'low_stock_threshold' => 5,
                'visibility' => 'public',
                'added_by_user_id' => User::find(1)->id,
            ],
            [
                'name' => 'Notebooks',
                'description' => 'Pack of 10 college-ruled notebooks',
                'quantity' => 50,
                'is_returnable' => false,
                'date' => '2023-09-04',
                'low_stock_threshold' => 10,
                'visibility' => 'public',
                'added_by_user_id' => User::find(1)->id,
            ],
            [
                'name' => 'Physics Textbook',
                'description' => 'Physics textbook for grade 9 students',
                'quantity' => 100,
                'is_returnable' => true,
                'date' => '2023-09-02',
                'low_stock_threshold' => 20,
                'visibility' => 'public',
                'added_by_user_id' => User::find(1)->id,
            ],
            [
                'name' => 'Biology Textbook',
                'description' => 'Biology textbook for grade 10 students',
                'quantity' => 100,
                'is_returnable' => true,
                'date' => '2023-09-02',
                'low_stock_threshold' => 20,
                'visibility' => 'public',
                'added_by_user_id' => User::find(1)->id,
            ],
            [
                'name' => 'Scissors',
                'description' => 'Pack of 10 safety scissors',
                'quantity' => 40,
                'is_returnable' => true,
                'date' => '2023-09-04',
                'low_stock_threshold' => 10,
                'visibility' => 'public',
                'added_by_user_id' => User::find(1)->id,
            ],
            [
                'name' => 'Chemistry Textbook',
                'description' => 'Chemistry textbook for grade 11 students',
                'quantity' => 100,
                'is_returnable' => true,
                'date' => '2023-09-02',
                'low_stock_threshold' => 20,
                'visibility' => 'public',
                'added_by_user_id' => User::find(1)->id,
            ],
            [
                'name' => 'History Textbook',
                'description' => 'History textbook for grade 11 students',
                'quantity' => 100,
                'is_returnable' => true,
                'date' => '2023-09-02',
                'low_stock_threshold' => 20,
                'visibility' => 'public',
                'added_by_user_id' => User::find(1)->id,
            ],
            [
                'name' => 'Folders',
                'description' => 'Box of 50 assorted folders',
                'quantity' => 60,
                'is_returnable' => true,
                'date' => '2023-09-04',
                'low_stock_threshold' => 15,
                'visibility' => 'public',
                'added_by_user_id' => User::find(1)->id,
            ],
            [
                'name' => 'Whiteboard Markers',
                'description' => 'Set of 10 whiteboard markers',
                'quantity' => 30,
                'is_returnable' => true,
                'date' => '2023-09-04',
                'low_stock_threshold' => 5,
                'visibility' => 'public',
                'added_by_user_id' => User::find(1)->id,
            ],
        ];
    }
}

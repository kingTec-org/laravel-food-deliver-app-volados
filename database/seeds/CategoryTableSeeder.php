<?php

use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('category')->delete();
    	
        DB::table('category')->insert(array(
          array('id' => '1','name' => 'Cleaning','description' => 'Cleaning','status' => '1','is_top' => '1','is_dietary' => '0','most_popular' => '1','created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 21:07:17'),
          array('id' => '2','name' => 'Households','description' => 'Households','status' => '1','is_top' => '0','is_dietary' => '0','most_popular' => NULL,'created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 21:05:59'),
          array('id' => '3','name' => 'Fruits','description' => 'Fruits','status' => '1','is_top' => '1','is_dietary' => '0','most_popular' => '1','created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 21:04:49'),
          array('id' => '6','name' => 'Drinks','description' => 'Drinks','status' => '1','is_top' => '1','is_dietary' => '0','most_popular' => '1','created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 21:04:21'),
          array('id' => '7','name' => 'Kitchen','description' => 'Kitchen','status' => '1','is_top' => '0','is_dietary' => '0','most_popular' => NULL,'created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 21:04:25'),
          array('id' => '8','name' => 'Garden','description' => 'Garden','status' => '1','is_top' => '1','is_dietary' => '0','most_popular' => '1','created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 21:02:48'),
          array('id' => '9','name' => 'Vegetable','description' => 'Vegetable','status' => '1','is_top' => '0','is_dietary' => '0','most_popular' => NULL,'created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 21:01:53'),
          array('id' => '11','name' => 'Food grains','description' => 'Food grains','status' => '1','is_top' => '1','is_dietary' => '0','most_popular' => '1','created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 21:00:38'),
          array('id' => '12','name' => 'Cakes','description' => 'Cakes','status' => '1','is_top' => '1','is_dietary' => '0','most_popular' => '1','created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 21:00:02'),
          array('id' => '13','name' => 'Beverages','description' => 'Beverages','status' => '1','is_top' => '0','is_dietary' => '0','most_popular' => NULL,'created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 20:59:06'),
          array('id' => '14','name' => 'Chicken','description' => 'Chicken','status' => '1','is_top' => '1','is_dietary' => '0','most_popular' => '1','created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 20:57:32'),
          array('id' => '15','name' => 'Pets','description' => 'Pets','status' => '1','is_top' => '1','is_dietary' => '0','most_popular' => '1','created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 20:56:54'),
          array('id' => '17','name' => 'Desserts','description' => 'Desserts','status' => '1','is_top' => '0','is_dietary' => '0','most_popular' => NULL,'created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 20:54:21'),
          array('id' => '18','name' => 'Drinks','description' => 'Drinks','status' => '1','is_top' => '0','is_dietary' => '0','most_popular' => NULL,'created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 20:55:06'),
          array('id' => '19','name' => 'Fish','description' => 'Fish & Chips','status' => '1','is_top' => '0','is_dietary' => '0','most_popular' => NULL,'created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 20:56:23'),
          array('id' => '23','name' => 'Meat','description' => 'Meat','status' => '1','is_top' => '0','is_dietary' => '0','most_popular' => '1','created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 20:49:23'),
          array('id' => '24','name' => 'Ice Cream','description' => 'Ice Cream','status' => '1','is_top' => '0','is_dietary' => '0','most_popular' => NULL,'created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 20:48:41'),
          array('id' => '25','name' => 'Snacks','description' => 'Snacks','status' => '1','is_top' => '1','is_dietary' => '0','most_popular' => '1','created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 20:47:33'),
          array('id' => '28','name' => 'Oil','description' => 'Oil','status' => '1','is_top' => '0','is_dietary' => '0','most_popular' => '1','created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 20:46:50'),
          array('id' => '30','name' => 'Shirts','description' => 'Shirts','status' => '1','is_top' => '0','is_dietary' => '0','most_popular' => NULL,'created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 20:45:54'),
          array('id' => '31','name' => 'Bakery','description' => 'Bakery','status' => '1','is_top' => '1','is_dietary' => '0','most_popular' => NULL,'created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 20:44:36'),
          array('id' => '36','name' => 'Hygiene','description' => 'Hygiene','status' => '1','is_top' => '0','is_dietary' => '0','most_popular' => NULL,'created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 20:44:08'),
          array('id' => '38','name' => 'Milkshakes','description' => 'Milkshakes','status' => '1','is_top' => '0','is_dietary' => '0','most_popular' => NULL,'created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 20:43:17'),
          array('id' => '40','name' => 'Noodles','description' => 'Noodles','status' => '1','is_top' => '1','is_dietary' => '0','most_popular' => NULL,'created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 20:42:44'),
          array('id' => '42','name' => 'Beauty','description' => 'Beauty','status' => '1','is_top' => '0','is_dietary' => '0','most_popular' => NULL,'created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 20:42:05'),
          array('id' => '43','name' => 'Branded foods','description' => 'Branded foods','status' => '1','is_top' => '1','is_dietary' => '0','most_popular' => NULL,'created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 20:41:35'),
          array('id' => '44','name' => 'Video games','description' => 'Video games','status' => '1','is_top' => '1','is_dietary' => '0','most_popular' => NULL,'created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 20:40:44'),
          array('id' => '45','name' => 'Spice & Masala','description' => 'Spice & Masala','status' => '1','is_top' => '1','is_dietary' => '0','most_popular' => '1','created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 20:40:18'),
          array('id' => '46','name' => 'Outdoors','description' => 'Outdoors','status' => '1','is_top' => '0','is_dietary' => '0','most_popular' => NULL,'created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 20:40:09'),
          array('id' => '47','name' => 'Party supplies','description' => 'Party supplies','status' => '1','is_top' => '0','is_dietary' => '0','most_popular' => NULL,'created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 20:39:47'),
          array('id' => '48','name' => 'Book','description' => 'Book','status' => '1','is_top' => '0','is_dietary' => '0','most_popular' => NULL,'created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 20:39:09'),
          array('id' => '49','name' => 'Clothes','description' => 'Clothes','status' => '1','is_top' => '0','is_dietary' => '0','most_popular' => NULL,'created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 20:38:28'),
          array('id' => '50','name' => 'Sports','description' => 'Sports','status' => '1','is_top' => '1','is_dietary' => '0','most_popular' => NULL,'created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 20:37:27'),
          array('id' => '51','name' => 'Electronics','description' => 'Electronics','status' => '1','is_top' => '0','is_dietary' => '0','most_popular' => NULL,'created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 20:36:48'),
          array('id' => '52','name' => 'Toy','description' => 'Toy','status' => '1','is_top' => '0','is_dietary' => '0','most_popular' => NULL,'created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 20:36:36'),
          array('id' => '53','name' => 'Baby care','description' => 'Baby care','status' => '1','is_top' => '1','is_dietary' => '0','most_popular' => '1','created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 20:35:55'),
          array('id' => '54','name' => 'Egg','description' => 'Egg','status' => '1','is_top' => '0','is_dietary' => '0','most_popular' => NULL,'created_at' => '2018-05-28 15:40:00','updated_at' => '2019-09-14 20:35:38')
                ));
        }
    }

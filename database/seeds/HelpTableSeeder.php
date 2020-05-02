<?php

use Illuminate\Database\Seeder;

class HelpTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('help')->delete();

        DB::table('help')->insert(array(
          array('id' => '1','category_id' => '2','subcategory_id' => '3','question' => 'Donec in lectus vitae sapien faucibus congue.','answer' => '<ul style="margin: 0px; padding: 0px; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify; background-color: rgb(255, 255, 255);"><li style="margin: 0px; padding: 0px;">Donec in lectus vitae sapien faucibus congue.Donec in lectus vitae sapien faucibus congue.Donec in lectus vitae sapien faucibus congue.Donec in lectus vitae sapien faucibus congue.</li><li style="margin: 0px; padding: 0px;">Donec in lectus vitae sapien faucibus congue..Donec in lectus vitae sapien faucibus congue.</li><li style="margin: 0px; padding: 0px;">Donec in lectus vitae sapien faucibus congue.</li><li></li><li></li><li></li><li></li><li></li></ul>','suggested' => '1','status' => '1','created_at' => '2018-09-17 16:53:57','updated_at' => '2018-09-17 16:58:31'),
          array('id' => '2','category_id' => '2','subcategory_id' => '4','question' => 'Suspendisse faucibus elit eu sem porttitor luctus.','answer' => '<li>Suspendisse faucibus elit eu sem porttitor luctus.</li>','suggested' => '1','status' => '1','created_at' => '2018-09-17 17:28:00','updated_at' => '2018-09-17 17:28:00'),
          array('id' => '3','category_id' => '3','subcategory_id' => '5','question' => 'Mauris at diam vel justo pretium elementum.','answer' => '<li>Mauris at diam vel justo pretium elementum.</li>','suggested' => '1','status' => '1','created_at' => '2018-09-17 17:28:23','updated_at' => '2018-09-17 17:28:23'),
          array('id' => '4','category_id' => '4','subcategory_id' => '6','question' => 'Curabitur quis est sagittis, pharetra massa et, pulvinar enim.','answer' => '<li>Curabitur quis est sagittis, pharetra massa et, pulvinar enim.</li>','suggested' => '1','status' => '1','created_at' => '2018-09-17 17:28:41','updated_at' => '2018-09-17 17:28:41'),
          array('id' => '5','category_id' => '5','subcategory_id' => '7','question' => 'Mauris sed leo posuere, dapibus risus in, varius ante.','answer' => '<li>Mauris sed leo posuere, dapibus risus in, varius ante.</li>','suggested' => '1','status' => '1','created_at' => '2018-09-17 17:28:57','updated_at' => '2018-09-17 17:28:57'),
          array('id' => '6','category_id' => '6','subcategory_id' => '8','question' => 'Nam sit amet ligula quis dolor dignissim ultricies id eget nisi.','answer' => '<li>Nam sit amet ligula quis dolor dignissim ultricies id eget nisi.</li>','suggested' => '1','status' => '1','created_at' => '2018-09-17 17:29:12','updated_at' => '2018-09-17 17:29:12'),
          array('id' => '7','category_id' => '6','subcategory_id' => '9','question' => 'Sed dapibus tortor eget metus imperdiet, eget imperdiet ipsum volutpat.','answer' => '<li>Sed dapibus tortor eget metus imperdiet, eget imperdiet ipsum volutpat.</li>','suggested' => '1','status' => '1','created_at' => '2018-09-17 17:29:27','updated_at' => '2018-09-17 17:29:27'),
          array('id' => '8','category_id' => '7','subcategory_id' => '10','question' => 'Aliquam imperdiet sem et mollis rhoncus.','answer' => '<li>Aliquam imperdiet sem et mollis rhoncus.</li>','suggested' => '1','status' => '1','created_at' => '2018-09-17 17:29:47','updated_at' => '2018-09-17 17:29:47'),
          array('id' => '9','category_id' => '7','subcategory_id' => '11','question' => 'Nunc a ligula ac turpis venenatis tincidunt ut ut quam.','answer' => '<li>Nunc a ligula ac turpis venenatis tincidunt ut ut quam.</li>','suggested' => '1','status' => '1','created_at' => '2018-09-17 17:30:35','updated_at' => '2018-09-17 17:30:35'),
          array('id' => '10','category_id' => '8','subcategory_id' => '12','question' => 'Nunc a ligula ac turpis venenatis tincidunt ut ut quam.','answer' => '<li>Nunc a ligula ac turpis venenatis tincidunt ut ut quam.</li>','suggested' => '1','status' => '1','created_at' => '2018-09-17 17:30:52','updated_at' => '2018-09-17 17:30:52'),
          array('id' => '11','category_id' => '8','subcategory_id' => '13','question' => 'Nunc at libero non orci vestibulum commodo vitae id orci.','answer' => '<li>Nunc at libero non orci vestibulum commodo vitae id orci.</li>','suggested' => '1','status' => '1','created_at' => '2018-09-17 17:31:13','updated_at' => '2018-09-17 17:31:13'),
          array('id' => '12','category_id' => '9','subcategory_id' => '14','question' => 'Nunc at libero non orci vestibulum commodo vitae id orci.','answer' => '<li>Nunc at libero non orci vestibulum commodo vitae id orci.</li>','suggested' => '1','status' => '1','created_at' => '2018-09-17 17:31:30','updated_at' => '2018-09-17 17:31:30'),
          array('id' => '13','category_id' => '9','subcategory_id' => '15','question' => 'Maecenas non dui id eros congue commodo sit amet sit amet dui.','answer' => '<li>Maecenas non dui id eros congue commodo sit amet sit amet dui.</li>','suggested' => '1','status' => '1','created_at' => '2018-09-17 17:31:46','updated_at' => '2018-09-17 17:31:46')
        ));
    }
}

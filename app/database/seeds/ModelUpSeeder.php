<?php

class ModelUpSeeder extends Seeder
{
	public function run()
	{
		// Seed model UP
		$tenant_id = DB::table('tenants')->insertGetId(array(
			'email'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true,
			'company'		=>	'up',
			'is_model'		=>	true
		));
		
		// CATEGORIAS
		$categories = array(
			'Perfumes Masculinos'		=>	'Perfumes Masculinos',
			'Perfumes Femininos'		=>	'Perfumes Femininos',
			'Perfumes Unisex'			=>	'Perfumes Unisex',
			'Flaconetes Masculinos'		=>	'Flaconetes Masculinos',
			'Flaconetes Femininos'		=>	'Flaconetes Femininos',
			'Flaconetes Unisex'			=>	'Flaconetes Unisex',
			'Cremes'					=>	'Cremes',
			'Linha Bucal'				=>	'Linha Bucal',
			'Linha UP Hair'				=>	'Linha UP Hair',
			'Kits e Upgrades'			=>	'Kits (concessões) Oficiais e Upgrades UP!',
			'Acessórios para Kits UP!'	=>	'Acessórios para Kits UP!',
			'Acessórios em Geral'		=>	'Acessórios gerais',
			'Amostras'					=>	'Amostras'
		);

		$cats_ids = array();
		foreach ($categories as $name => $description) {
			$id_cat = DB::table('categories')->insert_get_id(array(
				'tenant_id'		=>	$tenant_id,
				'name'			=>	$name,
				'slug'			=> 	Str::slug($name),
				'description'	=>	$description,
				'created_at'	=> 	date('Y-m-d H:m:s'),
				'updated_at'	=> 	date('Y-m-d H:m:s'),
				'is_alive'		=>	true
			));
			$cats_ids[$name] = $id_cat;
		}

		// PRODUTOS
		// Variáveis
		$essencias_masc	=	array(
			'UP! 01'	=>	'UP! 01 - Azzaro',
			'UP! 03'	=>	'UP! 03 - Boss',
			'UP! 05'	=>	'UP! 05 - Bulgari Black',
			'UP! 07'	=>	'UP! 07 - Dolce & Gabbana',
			'UP! 09'	=>	'UP! 09 - Armani White',
			'UP! 11'	=>	'UP! 11 - Ferrari Black',
			'UP! 13'	=>	'UP! 13 - Ferrari Red',
			'UP! 15'	=>	'UP! 15 - Kouros',
			'UP! 17'	=>	'UP! 17 - Polo',
			'UP! 19'	=>	'UP! 19 - Polo Blue',
			'UP! 21'	=>	'UP! 21 - Polo Black',
			'UP! 31'	=>	'UP! 31 - Joop! Nightflight',
			'UP! 33'	=>	'UP! 33 - Fahrenheit',
			'UP! 35'	=>	'UP! 35 - Armani Black',
			'UP! 37'	=>	'UP! 37 - Diesel',
			'UP! 39'	=>	'UP! 39 - Allure Sport',
			'UP! 41'	=>	'UP! 41 - Lapidus',
			'UP! 43'	=>	'UP! 43 - Animale',
			'UP! 45'	=>	'UP! 45 - 212 Men',
			'UP! 47'	=>	'UP! 47 - 1 Million',
		);
		$essencias_fem	=	array(
			'UP! 02'	=>	'UP! 02 - 212 Sexy',
			'UP! 06'	=>	'UP! 06 - Amor Amor',
			'UP! 08'	=>	'UP! 08 - Angel',
			'UP! 10'	=>	'UP! 10 - Carolina Herrera',
			'UP! 14'	=>	'UP! 14 - Dolce & Gabanna Light Blue',
			'UP! 16'	=>	'UP! 16 - Dolce & Gabbana',
			'UP! 22'	=>	'UP! 22 - Flower By Kenzo',
			'UP! 24'	=>	'UP! 24 - Gabriela Sabatini',
			'UP! 26'	=>	'UP! 26 - J\'adore',
			'UP! 28'	=>	'UP! 28 - Jean Paul Gaultier',
			'UP! 30'	=>	'UP! 30 - Ralph Lauren',
			'UP! 32'	=>	'UP! 32 - Anais Anais',
			'UP! 34'	=>	'UP! 34 - Hypnose',
			'UP! 36'	=>	'UP! 36 - CK in2u Her',
			'UP! 38'	=>	'UP! 38 - Fantasy',
			'UP! 40'	=>	'UP! 40 - The One',
			'UP! 42'	=>	'UP! 42 - Ange ou Démon',
			'UP! 44'	=>	'UP! 44 - Glow by J.Lo',
			'UP! 46'	=>	'UP! 46 - Lady Million'
		);
		$essencias_uni	=	array(
			'UP! 25'	=>	'UP! 25 - CK One',
			'UP! 27'	=>	'UP! 27 - CK Be',
			'UP! 29'	=>	'UP! 29 - CK Cool Water',
		);
		// Perfumes Masculinos
		foreach ($essencias_masc as $name => $description) {
			DB::table('products')->insert(array(
				'tenant_id'		=>	$tenant_id,
				'category_id'	=>	$cats_ids['Perfumes Masculinos'],
				'name'			=>	'Perfume '.$name,
				'slug'			=>	'perfume-'.Str::slug($name),
				'description'	=>	'Perfume '.$description,
				'price'			=>	39.5,
				'margin'		=>	0.2,
				'box'			=>	20,
				'created_at'	=> 	date('Y-m-d H:m:s'),
				'updated_at'	=> 	date('Y-m-d H:m:s'),
				'is_alive'		=>	true
			));
			DB::table('products')->insert(array(
				'tenant_id'		=>	$tenant_id,
				'category_id'	=>	$cats_ids['Flaconetes Masculinos'],
				'name'			=>	'Flaconete '.$name,
				'slug'			=>	'flaconete-'.Str::slug($name),
				'description'	=>	'Flaconete '.$description,
				'price'			=>	3,
				'margin'		=>	0.2,
				'box'			=>	500,
				'created_at'	=> 	date('Y-m-d H:m:s'),
				'updated_at'	=> 	date('Y-m-d H:m:s'),
				'is_alive'		=>	true
			));
		}
		// Perfumes femininos
		foreach ($essencias_fem as $name => $description) {
			DB::table('products')->insert(array(
				'tenant_id'		=>	$tenant_id,
				'category_id'	=>	$cats_ids['Perfumes Femininos'],
				'name'			=>	'Perfume '.$name,
				'slug'			=>	'perfume-'.Str::slug($name),
				'description'	=>	'Perfume '.$description,
				'price'			=>	39.5,
				'margin'		=>	0.2,
				'box'			=>	20,
				'created_at'	=> 	date('Y-m-d H:m:s'),
				'updated_at'	=> 	date('Y-m-d H:m:s'),
				'is_alive'		=>	true
			));
			DB::table('products')->insert(array(
				'tenant_id'		=>	$tenant_id,
				'category_id'	=>	$cats_ids['Flaconetes Femininos'],
				'name'			=>	'Flaconete '.$name,
				'slug'			=>	'flaconete-'.Str::slug($name),
				'description'	=>	'Flaconete '.$description,
				'price'			=>	3,
				'margin'		=>	0.2,
				'box'			=>	500,
				'created_at'	=> 	date('Y-m-d H:m:s'),
				'updated_at'	=> 	date('Y-m-d H:m:s'),
				'is_alive'		=>	true
			));
		}
		// Perfumes Unisex
		foreach ($essencias_uni as $name => $description) {
			DB::table('products')->insert(array(
				'tenant_id'		=>	$tenant_id,
				'category_id'	=>	$cats_ids['Perfumes Unisex'],
				'name'			=>	'Perfume '.$name,
				'slug'			=>	'perfume-'.Str::slug($name),
				'description'	=>	'Perfume '.$description,
				'price'			=>	39.5,
				'margin'		=>	0.2,
				'box'			=>	20,
				'created_at'	=> 	date('Y-m-d H:m:s'),
				'updated_at'	=> 	date('Y-m-d H:m:s'),
				'is_alive'		=>	true
			));
			DB::table('products')->insert(array(
				'tenant_id'		=>	$tenant_id,
				'category_id'	=>	$cats_ids['Flaconetes Unisex'],
				'name'			=>	'Flaconete '.$name,
				'slug'			=>	'flaconete-'.Str::slug($name),
				'description'	=>	'Flaconete '.$description,
				'price'			=>	3,
				'margin'		=>	0.2,
				'box'			=>	500,
				'created_at'	=> 	date('Y-m-d H:m:s'),
				'updated_at'	=> 	date('Y-m-d H:m:s'),
				'is_alive'		=>	true
			));
		}

		// Cremes
		$essencias_cremes	=	array(
			'UP! 08'	=>	'UP! 08 - Angel',
			'UP! 10'	=>	'UP! 10 - Carolina Herrera',
			'UP! 16'	=>	'UP! 16 - Dolce & Gabbana',
			'UP! 24'	=>	'UP! 24 - Gabriela Sabatini',
			'UP! 38'	=>	'UP! 38 - Fantasy',
			'UP! 27'	=>	'UP! 27 - CK Be',
		);

		foreach ($essencias_cremes as $name => $description) {
			DB::table('products')->insert(array(
				'tenant_id'		=>	$tenant_id,
				'category_id'	=>	$cats_ids['Cremes'],
				'name'			=>	'Creme Hidratante '.$name,
				'slug'			=>	'creme-hidratante-'.Str::slug($name),
				'description'	=>	'Creme Hidratante '.$description,
				'price'			=>	16,
				'margin'		=>	0.2,
				'box'			=>	12,
				'created_at'	=> 	date('Y-m-d H:m:s'),
				'updated_at'	=> 	date('Y-m-d H:m:s'),
				'is_alive'		=>	true
			));
		}
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Cremes'],
			'name'			=>	'Creme D-Soft',
			'slug'			=>	'creme-d-soft',
			'description'	=>	'Creme D-Soft',
			'price'			=>	13.5,
			'margin'		=>	0.2,
			'box'			=>	30,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Cremes'],
			'name'			=>	'Creme Activida',
			'slug'			=>	'creme-activida',
			'description'	=>	'Creme Activida',
			'price'			=>	17,
			'margin'		=>	0.2,
			'box'			=>	30,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));

		// Linha bucal
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Linha Bucal'],
			'name'			=>	'Gel Dental',
			'slug'			=>	'gel-dental',
			'description'	=>	'Gel Dental',
			'price'			=>	5.6,
			'margin'		=>	0.2,
			'box'			=>	77,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Linha Bucal'],
			'name'			=>	'Antisséptico',
			'slug'			=>	'antisseptico',
			'description'	=>	'Antisséptico',
			'price'			=>	7.4,
			'margin'		=>	0.2,
			'box'			=>	12,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Linha Bucal'],
			'name'			=>	'Necessaire',
			'slug'			=>	'necessaire',
			'description'	=>	'Necessaire',
			'price'			=>	13,
			'margin'		=>	0.2,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		// Kits
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Kits'],
			'name'			=>	'Kit Profissional Vendedor',
			'slug'			=>	'kit-profissional-vendedor',
			'description'	=>	'Kit Profissional Vendedor',
			'price'			=>	180,
			'margin'		=>	0.20,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Kits'],
			'name'			=>	'Kit Profissional Empreendedor',
			'slug'			=>	'kit-profissional-empreendedor',
			'description'	=>	'Kit Profissional Empreendedor',
			'price'			=>	400,
			'margin'		=>	13.0825,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Kits'],
			'name'			=>	'Kit Profissional Executivo',
			'slug'			=>	'kit-profissional-executivo',
			'description'	=>	'Kit Profissional Executivo',
			'price'			=>	800,
			'margin'		=>	8.6925,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Kits'],
			'name'			=>	'Kit Profissional Master',
			'slug'			=>	'kit-profissional-master',
			'description'	=>	'Kit Profissional Master',
			'price'			=>	1900,
			'margin'		=>	11.234211,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Kits'],
			'name'			=>	'Upgrade Clássico-Executivo',
			'slug'			=>	'upgrade-classico-executivo',
			'description'	=>	'Upgrade Clássico-Executivo',
			'price'			=>	620,
			'margin'		=>	5.409677,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Kits'],
			'name'			=>	'Upgrade Clássico-Master',
			'slug'			=>	'upgrade-classico-master',
			'description'	=>	'Upgrade Clássico-Master',
			'price'			=>	1720,
			'margin'		=>	10.31686,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Kits'],
			'name'			=>	'Upgrade Executivo-Master',
			'slug'			=>	'upgrade-executivo-master',
			'description'	=>	'Upgrade Executivo-Master',
			'price'			=>	1100,
			'margin'		=>	13.082727,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		// Linha UP Hair
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Linha UP Hair'],
			'name'			=>	'Shampoo W/UP! Cabelos Normais',
			'slug'			=>	'Shampoo-wup-cabelos-normais',
			'description'	=>	'Shampoo W/UP! Cabelos Normais',
			'price'			=>	29.80,
			'margin'		=>	0.2,
			'box'			=>	12,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Linha UP Hair'],
			'name'			=>	'Shampoo W/UP! Cabelos Secos',
			'slug'			=>	'Shampoo-wup-cabelos-secos',
			'description'	=>	'Shampoo W/UP! Cabelos Secos',
			'price'			=>	29.80,
			'margin'		=>	0.2,
			'box'			=>	12,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Linha UP Hair'],
			'name'			=>	'Shampoo W/UP! Cabelos Oleosos',
			'slug'			=>	'Shampoo-wup-cabelos-oleosos',
			'description'	=>	'Shampoo W/UP! Cabelos Oleosos',
			'price'			=>	29.80,
			'margin'		=>	0.2,
			'box'			=>	12,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Linha UP Hair'],
			'name'			=>	'Condicionador W/UP! Cabelos Normais',
			'slug'			=>	'Condicionador-wup-cabelos-normais',
			'description'	=>	'Condicionador W/UP! Cabelos Normais',
			'price'			=>	29.80,
			'margin'		=>	0.2,
			'box'			=>	12,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Linha UP Hair'],
			'name'			=>	'Condicionador W/UP! Cabelos Secos',
			'slug'			=>	'Condicionador-wup-cabelos-secos',
			'description'	=>	'Condicionador W/UP! Cabelos Secos',
			'price'			=>	29.80,
			'margin'		=>	0.2,
			'box'			=>	12,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));

		// Acessórios para Kits
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Bandeira para Carro Laranja',
			'slug'			=>	'bandeira-para-carro-laranja',
			'description'	=>	'Bandeira para Carro Laranja',
			'price'			=>	8,
			'margin'		=>	0.2,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Bandeira para Carro Branca',
			'slug'			=>	'bandeira-para-carro-branca',
			'description'	=>	'Bandeira para Carro Branca',
			'price'			=>	8,
			'margin'		=>	0.2,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Flamula Oficial UP! By Car Branca',
			'slug'			=>	'flamula-oficial-up-by-car-branca',
			'description'	=>	'Flamula Oficial UP! By Car Branca',
			'price'			=>	8,
			'margin'		=>	0.2,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Flamula Oficial UP! By Car Laranja',
			'slug'			=>	'flamula-oficial-up-by-car-laranja',
			'description'	=>	'Flamula Oficial UP! By Car Laranja',
			'price'			=>	8,
			'margin'		=>	0.2,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Flamula Oficial UP! Laranja',
			'slug'			=>	'flamula-oficial-up-laranja',
			'description'	=>	'Flamula Oficial UP! Laranja',
			'price'			=>	5,
			'margin'		=>	0.2,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Flamula Oficial UP! Branca',
			'slug'			=>	'flamula-oficial-up-branca',
			'description'	=>	'Flamula Oficial UP! Branca',
			'price'			=>	5,
			'margin'		=>	0.2,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Business Cards (Cartoes de Visita Oficiais)',
			'slug'			=>	'business-cards',
			'description'	=>	'Business Cards (Cartoes de Visita Oficiais)',
			'price'			=>	0.05,
			'margin'		=>	0,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Fita Olfativa',
			'slug'			=>	'fita-olfativa',
			'description'	=>	'Fita Olfativa',
			'price'			=>	2.5,
			'margin'		=>	0,
			'box'			=>	25,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Bloco Oficial UP! de Anotações',
			'slug'			=>	'bloco-oficial-up-de-anotacoes',
			'description'	=>	'Bloco Oficial UP! de Anotações',
			'price'			=>	3,
			'margin'		=>	0,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Catálogo UP!',
			'slug'			=>	'catalogo-up',
			'description'	=>	'Catálogo UP!',
			'price'			=>	3,
			'margin'		=>	0.2,
			'box'			=>	100,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Caneta Oficial UP! Black Edition',
			'slug'			=>	'caneta-oficial-up-black-edition',
			'description'	=>	'Caneta Oficial UP! Black Edition',
			'price'			=>	9,
			'margin'		=>	0,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Chaveiro Oficial UP! Silver Edition',
			'slug'			=>	'chaveiro-oficial-up-silver-edition',
			'description'	=>	'Chaveiro Oficial UP! Silver Edition',
			'price'			=>	9,
			'margin'		=>	0,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Carta Resposta UP!',
			'slug'			=>	'carta-resposta',
			'description'	=>	'Carta Resposta UP!',
			'price'			=>	4,
			'margin'		=>	0,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Encarte Oficial W/UP!',
			'slug'			=>	'encarte-oficial-wup',
			'description'	=>	'Encarte Oficial W/UP!',
			'price'			=>	0.2,
			'margin'		=>	0,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Folder de Produtos',
			'slug'			=>	'folder-de-produtos',
			'description'	=>	'Folder de Produtos',
			'price'			=>	7.70,
			'margin'		=>	0,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Caixa de Presente',
			'slug'			=>	'caixa-de-presente',
			'description'	=>	'Caixa de Presente',
			'price'			=>	5,
			'margin'		=>	0,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Estojo Demonstrador VIP',
			'slug'			=>	'estojo-demonstrador-vip',
			'description'	=>	'Estojo Demonstrador VIP',
			'price'			=>	79,
			'margin'		=>	0.2,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Estojo Demonstrador VIP Vazio',
			'slug'			=>	'estojo-demonstrador-vip-vazio',
			'description'	=>	'Estojo Demonstrador VIP-vazio',
			'price'			=>	20,
			'margin'		=>	0.2,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Flipchart Pro MMR UP!+',
			'slug'			=>	'Flipchart-pro-MMR-UP-mais',
			'description'	=>	'Flipchart Pro MMR UP!+',
			'price'			=>	40,
			'margin'		=>	0,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Manual de Negócios UP!',
			'slug'			=>	'manual-de-negocios-up',
			'description'	=>	'Manual de Negócios UP!',
			'price'			=>	7,
			'margin'		=>	0.2,
			'box'			=>	100,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));

		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'UP! Acontece ECO',
			'slug'			=>	'up-acontece-eco',
			'description'	=>	'UP! Acontece ECO',
			'price'			=>	7,
			'margin'		=>	0,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Ficha de Pedidos',
			'slug'			=>	'ficha-de-pedidos',
			'description'	=>	'Ficha de Pedidos',
			'price'			=>	4,
			'margin'		=>	0.2,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Talão de Pedidos',
			'slug'			=>	'talao-de-pedidos',
			'description'	=>	'Talão de Pedidos',
			'price'			=>	2.2,
			'margin'		=>	0,
			'box'			=>	100,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Sacola Plástica (10 Unidades)',
			'slug'			=>	'sacola-plastica-10-unidades',
			'description'	=>	'Sacola Plástica (10 Unidades)',
			'price'			=>	2.5,
			'margin'		=>	0,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Bolsa VIP UP!',
			'slug'			=>	'bolsa-vip-up',
			'description'	=>	'Bolsa VIP UP!',
			'price'			=>	50,
			'margin'		=>	0.2,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Bolsa Pequena UP!',
			'slug'			=>	'bolsa-pequena-up',
			'description'	=>	'Bolsa Pequena UP!',
			'price'			=>	22.60,
			'margin'		=>	0.2,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		/*
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'DVD Apresentação de Negócios UP!',
			'slug'			=>	'dvd-apresentacao-negocios-up',
			'description'	=>	'DVD Apresentação de Negócios UP!',
			'price'			=>	3,
			'margin'		=>	0.2,
			'box'			=>	100,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		*/
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Pasta Oficial UP! 100% Responsável',
			'slug'			=>	'pasta-oficial-up-100pc-responsavel',
			'description'	=>	'Pasta Oficial UP! 100% Responsável',
			'price'			=>	6,
			'margin'		=>	0,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Mousepad Oficial UP! Preto',
			'slug'			=>	'mousepad-oficial-up-preto',
			'description'	=>	'Mousepad Oficial UP! Preto',
			'price'			=>	5.5,
			'margin'		=>	0,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Mousepad Oficial UP! Laranja',
			'slug'			=>	'mousepad-oficial-up-laranja',
			'description'	=>	'Mousepad Oficial UP! Laranja',
			'price'			=>	5.5,
			'margin'		=>	0,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Mousepad',
			'slug'			=>	'mousepad',
			'description'	=>	'Mousepad',
			'price'			=>	5.5,
			'margin'		=>	0.2,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Mapa Olfativo',
			'slug'			=>	'mapa-olfativo',
			'description'	=>	'Mapa Olfativo',
			'price'			=>	1,
			'margin'		=>	0,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Pin Especialista',
			'slug'			=>	'pin-especialista',
			'description'	=>	'Pin especialista',
			'price'			=>	5,
			'margin'		=>	0,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));

		// Amostras
		$essencias_amostras	=	array(
			'UP! 01'	=>	'UP! 01 - Azzaro',
			'UP! 02'	=>	'UP! 02 - 212 Sexy',
			'UP! 16'	=>	'UP! 16 - Dolce & Gabbana',
			'UP! 11'	=>	'UP! 11 - Ferrari Black',
			'UP! 38'	=>	'UP! 38 - Fantasy',
			'UP! 39'	=>	'UP! 39 - Allure Sport',
			'UP! 43'	=>	'UP! 43 - Animale',
			'UP! 44'	=>	'UP! 44 - Glow by J.Lo',
			'UP! 45'	=>	'UP! 45 - 212 Men',
			'UP! 46'	=>	'UP! 46 - Lady Million',
			'UP! 47'	=>	'UP! 47 - 1 Million',
		);

		foreach ($essencias_amostras as $name => $description) {
			DB::table('products')->insert(array(
				'tenant_id'		=>	$tenant_id,
				'category_id'	=>	$cats_ids['Amostras'],
				'name'			=>	'Amostra '.$name,
				'slug'			=>	'amostra-'.Str::slug($name),
				'description'	=>	'Amostra '.$description. ' (10 Unidades)',
				'price'			=>	7,
				'margin'		=>	0.2,
				'box'			=>	NULL,
				'created_at'	=> 	date('Y-m-d H:m:s'),
				'updated_at'	=> 	date('Y-m-d H:m:s'),
				'is_alive'		=>	true
			));
		}

		// Acessórios em Geral
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios em Geral'],
			'name'			=>	'Crédito de Repasse',
			'slug'			=>	'credito-de-repasse',
			'description'	=>	'Crédito de Repasse',
			'price'			=>	1,
			'margin'		=>	0,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios em Geral'],
			'name'			=>	'Display Demonstrador',
			'slug'			=>	'display-demonstrador',
			'description'	=>	'Display Demonstrador',
			'price'			=>	600,
			'margin'		=>	0,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
	}
}


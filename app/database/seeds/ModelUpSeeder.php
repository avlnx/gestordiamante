<?php

class ModelUpSeeder extends Seeder
{
	public function run()
	{
		/*
			We generate a model for each company so later we can 'copy' it's products and categories
			to the tenants accounts that share the company of the model.
			We call these products and categories 'protected', they are not editable by the tenant and
			are updated automatically.
		*/
		// Seed model UP
		$tenant_id = DB::table('tenants')
			->where('company', 'up')
			->where('is_model', true)
			->pluck('id');

		$margem_padrao = 0.2;

		if ($tenant_id == NULL) {
			// Model doesn't exist yet, create it
			$tenant_id = DB::table('tenants')->insertGetId(array(
				'email'			=>	'thferreira@gmail.com',
				'created_at'	=> 	date('Y-m-d H:m:s'),
				'updated_at'	=> 	date('Y-m-d H:m:s'),
				'is_alive'		=>	true,
				'company'		=>	'up',
				'is_model'		=>	true,
				'account_name'			=>	'UP! ~ MODEL'
			));
		} else {
			// Model exists, delete all its products and categories so we can update them
			// without duplicates
			DB::table('products')
				->where('tenant_id', $tenant_id)
				->delete();
			DB::table('categories')
				->where('tenant_id', $tenant_id)
				->delete();
		}

		// TODO: Create user to manipulate model tenants! if not exists
		$model_user = DB::table('users')
			->where('email', 'upmodel@gmail.com')
			->get();

		if($model_user == NULL) {
			DB::table('users')->insert(array(
				'email'			=>	'upmodel@gmail.com',
				'tenant_id'		=>	$tenant_id,
				'password'		=>	Hash::make('ntmesfecjo19'),
				'is_admin'		=> 	true,
				'is_root'		=>	false,
				'name'			=> 	'UP! ~ Model',
				'created_at'	=>	date('Y-m-d H:m:s'),
				'updated_at'	=>	date('Y-m-d H:m:s')
			));
		}		

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
			'Kits'			=>	'Kits (concessões) Oficiais e Upgrades UP!',
			'Acessórios para Kits UP!'	=>	'Acessórios para Kits UP!',
			'Acessórios em Geral'		=>	'Acessórios gerais',
			'Amostras'					=>	'Amostras',
			'Livros'					=>	'Livros',
			'Combos'					=>	'Combos Promocionais UP!'
		);

		$cats_ids = array();
		foreach ($categories as $name => $description) {
			$id_cat = DB::table('categories')->insertGetId(array(
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
			'01'	=>	'UP! 01 - Azzaro',
			'03'	=>	'UP! 03 - Boss',
			'05'	=>	'UP! 05 - Bulgari Black',
			'07'	=>	'UP! 07 - Dolce & Gabbana',
			'09'	=>	'UP! 09 - Armani White',
			'11'	=>	'UP! 11 - Ferrari Black',
			'13'	=>	'UP! 13 - Ferrari Red',
			'15'	=>	'UP! 15 - Kouros',
			'17'	=>	'UP! 17 - Polo',
			'19'	=>	'UP! 19 - Polo Blue',
			'21'	=>	'UP! 21 - Polo Black',
			'31'	=>	'UP! 31 - Joop! Nightflight',
			'33'	=>	'UP! 33 - Fahrenheit',
			'35'	=>	'UP! 35 - Armani Black',
			'37'	=>	'UP! 37 - Diesel',
			'39'	=>	'UP! 39 - Allure Sport',
			'41'	=>	'UP! 41 - Lapidus',
			'43'	=>	'UP! 43 - Animale',
			'45'	=>	'UP! 45 - 212 Men',
			'47'	=>	'UP! 47 - 1 Million',
		);
		$essencias_fem	=	array(
			'02'	=>	'UP! 02 - 212 Sexy',
			'06'	=>	'UP! 06 - Amor Amor',
			'08'	=>	'UP! 08 - Angel',
			'10'	=>	'UP! 10 - Carolina Herrera',
			'14'	=>	'UP! 14 - Dolce & Gabanna Light Blue',
			'16'	=>	'UP! 16 - Dolce & Gabbana',
			'22'	=>	'UP! 22 - Flower By Kenzo',
			'24'	=>	'UP! 24 - Gabriela Sabatini',
			'26'	=>	'UP! 26 - J\'adore',
			'28'	=>	'UP! 28 - Jean Paul Gaultier',
			'30'	=>	'UP! 30 - Ralph Lauren',
			'32'	=>	'UP! 32 - Anais Anais',
			'34'	=>	'UP! 34 - Hypnose',
			'36'	=>	'UP! 36 - CK in2u Her',
			'38'	=>	'UP! 38 - Fantasy',
			'40'	=>	'UP! 40 - The One',
			'42'	=>	'UP! 42 - Ange ou Démon',
			'44'	=>	'UP! 44 - Glow by J.Lo',
			'46'	=>	'UP! 46 - Lady Million'
		);
		$essencias_uni	=	array(
			'25'	=>	'UP! 25 - CK One',
			'27'	=>	'UP! 27 - CK Be',
			'29'	=>	'UP! 29 - CK Cool Water'
		);

		// Perfumes Masculinos
		foreach ($essencias_masc as $number => $description) {
			DB::table('products')->insert(array(
				'tenant_id'		=>	$tenant_id,
				'category_id'	=>	$cats_ids['Perfumes Masculinos'],
				'name'			=>	'Perfume UP! '.$number,
				'slug'			=>	'perfume-up-'.$number,
				'description'	=>	'Perfume '.$description,
				'price'			=>	39.5,
				'margin'		=>	$margem_padrao,
				'box'			=>	20,
				'created_at'	=> 	date('Y-m-d H:m:s'),
				'updated_at'	=> 	date('Y-m-d H:m:s'),
				'is_alive'		=>	true,
				'ref'				=> 'TM50'.$number
			));
			DB::table('products')->insert(array(
				'tenant_id'		=>	$tenant_id,
				'category_id'	=>	$cats_ids['Flaconetes Masculinos'],
				'name'			=>	'Flaconete '.$number,
				'slug'			=>	'flaconete-'.Str::slug($number),
				'description'	=>	'Flaconete '.$description,
				'price'			=>	3,
				'margin'		=>	$margem_padrao,
				'box'			=>	500,
				'created_at'	=> 	date('Y-m-d H:m:s'),
				'updated_at'	=> 	date('Y-m-d H:m:s'),
				'is_alive'		=>	true,
				'ref'				=> 'FC04'.$number
			));
		}
		// Perfumes femininos
		foreach ($essencias_fem as $number => $description) {
			if($number == "44")
			{
				$perfume_price = 48;
			} else {
				$perfume_price = 39.5;
			}
			DB::table('products')->insert(array(
				'tenant_id'		=>	$tenant_id,
				'category_id'	=>	$cats_ids['Perfumes Femininos'],
				'name'			=>	'Perfume '.$number,
				'slug'			=>	'perfume-'.Str::slug($number),
				'description'	=>	'Perfume '.$description,
				'price'			=>	$perfume_price,
				'margin'		=>	$margem_padrao,
				'box'			=>	20,
				'created_at'	=> 	date('Y-m-d H:m:s'),
				'updated_at'	=> 	date('Y-m-d H:m:s'),
				'is_alive'		=>	true,
				'ref'				=> 'TF50'.$number
			));
			DB::table('products')->insert(array(
				'tenant_id'		=>	$tenant_id,
				'category_id'	=>	$cats_ids['Flaconetes Femininos'],
				'name'			=>	'Flaconete '.$number,
				'slug'			=>	'flaconete-'.Str::slug($number),
				'description'	=>	'Flaconete '.$description,
				'price'			=>	3,
				'margin'		=>	$margem_padrao,
				'box'			=>	500,
				'created_at'	=> 	date('Y-m-d H:m:s'),
				'updated_at'	=> 	date('Y-m-d H:m:s'),
				'is_alive'		=>	true,
				'ref'				=> 'FC04'.$number
			));
		}
		// Perfumes Unisex

		foreach ($essencias_uni as $number => $description) {
			DB::table('products')->insert(array(
				'tenant_id'		=>	$tenant_id,
				'category_id'	=>	$cats_ids['Perfumes Unisex'],
				'name'			=>	'Perfume '.$number,
				'slug'			=>	'perfume-'.Str::slug($number),
				'description'	=>	'Perfume '.$description,
				'price'			=>	$perfume_price,
				'margin'		=>	$margem_padrao,
				'box'			=>	20,
				'created_at'	=> 	date('Y-m-d H:m:s'),
				'updated_at'	=> 	date('Y-m-d H:m:s'),
				'is_alive'		=>	true,
				'ref'				=> 'TU50'.$number
			));
			DB::table('products')->insert(array(
				'tenant_id'		=>	$tenant_id,
				'category_id'	=>	$cats_ids['Flaconetes Unisex'],
				'name'			=>	'Flaconete '.$number,
				'slug'			=>	'flaconete-'.Str::slug($number),
				'description'	=>	'Flaconete '.$description,
				'price'			=>	3,
				'margin'		=>	$margem_padrao,
				'box'			=>	500,
				'created_at'	=> 	date('Y-m-d H:m:s'),
				'updated_at'	=> 	date('Y-m-d H:m:s'),
				'is_alive'		=>	true,
				'ref'				=> 'FC04'.$number
			));
		}
		// Monaco
		DB::table('products')->insert(array(
				'tenant_id'		=>	$tenant_id,
				'category_id'	=>	$cats_ids['Perfumes Unisex'],
				'name'			=>	'Perfume UP! Monaco',
				'slug'			=>	'perfume-up-monaco',
				'description'	=>	'Perfume UP! Monaco',
				'price'			=>	48,
				'margin'		=>	$margem_padrao,
				'box'			=>	20,
				'created_at'	=> 	date('Y-m-d H:m:s'),
				'updated_at'	=> 	date('Y-m-d H:m:s'),
				'is_alive'		=>	true,
				'ref'				=> 'TUUPMO'
			));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Flaconetes Unisex'],
			'name'			=>	'Flaconete UP! Monaco',
			'slug'			=>	'flaconete-up-monaco',
			'description'	=>	'Flaconete UP! Monaco',
			'price'			=>	3,
			'margin'		=>	$margem_padrao,
			'box'			=>	500,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true,
			'ref'				=> 'FCUPMO'
		));

		// Cremes
		$essencias_cremes	=	array(
			'08'	=>	'UP! 08 - Angel',
			'10'	=>	'UP! 10 - Carolina Herrera',
			'16'	=>	'UP! 16 - Dolce & Gabbana',
			'24'	=>	'UP! 24 - Gabriela Sabatini',
			'38'	=>	'UP! 38 - Fantasy',
			'27'	=>	'UP! 27 - CK Be',
		);

		foreach ($essencias_cremes as $number => $description) {
			DB::table('products')->insert(array(
				'tenant_id'		=>	$tenant_id,
				'category_id'	=>	$cats_ids['Cremes'],
				'name'			=>	'Creme Hidratante '.$number,
				'slug'			=>	'creme-hidratante-'.$number,
				'description'	=>	'Creme Hidratante '.$description,
				'price'			=>	16,
				'margin'		=>	$margem_padrao,
				'box'			=>	12,
				'created_at'	=> 	date('Y-m-d H:m:s'),
				'updated_at'	=> 	date('Y-m-d H:m:s'),
				'is_alive'		=>	true,
				'ref'				=> 'HI00'.$number
			));
		}
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Cremes'],
			'name'			=>	'Creme D-Soft',
			'slug'			=>	'creme-d-soft',
			'description'	=>	'Creme D-Soft',
			'price'			=>	13.5,
			'margin'		=>	$margem_padrao,
			'box'			=>	30,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true,
			'ref'				=> 'DS0001'
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Cremes'],
			'name'			=>	'Creme Activida',
			'slug'			=>	'creme-activida',
			'description'	=>	'Creme Activida',
			'price'			=>	17,
			'margin'		=>	$margem_padrao,
			'box'			=>	30,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true,
			'ref'				=> 'ACTI01'
		));

		// Linha bucal
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Linha Bucal'],
			'name'			=>	'Gel Dental',
			'slug'			=>	'gel-dental',
			'description'	=>	'Gel Dental',
			'price'			=>	5.6,
			'margin'		=>	$margem_padrao,
			'box'			=>	77,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true,
			'ref'				=> 'GD0001'
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Linha Bucal'],
			'name'			=>	'Antisséptico',
			'slug'			=>	'antisseptico',
			'description'	=>	'Antisséptico',
			'price'			=>	7.4,
			'margin'		=>	$margem_padrao,
			'box'			=>	12,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true,
			'ref'				=> 'AB0001'
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Linha Bucal'],
			'name'			=>	'Necessaire',
			'slug'			=>	'necessaire',
			'description'	=>	'Necessaire',
			'price'			=>	13,
			'margin'		=>	$margem_padrao,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true,
			'ref'				=> 'NC0001'
		));

		// Kits
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Kits'],
			'name'			=>	'Kit Profissional Vendedor',
			'slug'			=>	'kit-profissional-vendedor',
			'description'	=>	'Kit Profissional Vendedor',
			'price'			=>	180,
			'margin'		=>	$margem_padrao,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true,
			'ref'				=> 'KTVENDT'
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Kits'],
			'name'			=>	'Kit Profissional Classico',
			'slug'			=>	'kit-profissional-classico',
			'description'	=>	'Kit Profissional Classico',
			'price'			=>	180,
			'margin'		=>	$margem_padrao,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true,
			'ref'				=> 'KTCLAST'
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
			'is_alive'		=>	true,
			'ref'				=> 'KTVEMPT'
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
			'is_alive'		=>	true,
			'ref'				=> 'KTEXECT'
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
			'is_alive'		=>	true,
			'ref'				=> 'KTMASTE'
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
			'is_alive'		=>	true,
			'ref'				=> 'KTCLEXT'
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
			'is_alive'		=>	true,
			'ref'				=> 'KTCLMAT'
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
			'is_alive'		=>	true,
			'ref'				=> 'KTEXMAT'
		));
		// Linha UP Hair
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Linha UP Hair'],
			'name'			=>	'Shampoo W/UP! Cabelos Normais',
			'slug'			=>	'Shampoo-wup-cabelos-normais',
			'description'	=>	'Shampoo W/UP! Cabelos Normais',
			'price'			=>	29.80,
			'margin'		=>	$margem_padrao,
			'box'			=>	12,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true,
			'ref'				=> 'SHNORM'
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Linha UP Hair'],
			'name'			=>	'Shampoo W/UP! Cabelos Secos',
			'slug'			=>	'Shampoo-wup-cabelos-secos',
			'description'	=>	'Shampoo W/UP! Cabelos Secos',
			'price'			=>	29.80,
			'margin'		=>	$margem_padrao,
			'box'			=>	12,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true,
			'ref'				=> 'SHSECO'
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Linha UP Hair'],
			'name'			=>	'Shampoo W/UP! Cabelos Oleosos',
			'slug'			=>	'Shampoo-wup-cabelos-oleosos',
			'description'	=>	'Shampoo W/UP! Cabelos Oleosos',
			'price'			=>	29.80,
			'margin'		=>	$margem_padrao,
			'box'			=>	12,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true,
			'ref'				=> 'SHOLEO'
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Linha UP Hair'],
			'name'			=>	'Condicionador W/UP! Cabelos Normais',
			'slug'			=>	'Condicionador-wup-cabelos-normais',
			'description'	=>	'Condicionador W/UP! Cabelos Normais',
			'price'			=>	29.80,
			'margin'		=>	$margem_padrao,
			'box'			=>	12,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true,
			'ref'				=> 'CONORM'
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Linha UP Hair'],
			'name'			=>	'Condicionador W/UP! Cabelos Secos',
			'slug'			=>	'Condicionador-wup-cabelos-secos',
			'description'	=>	'Condicionador W/UP! Cabelos Secos',
			'price'			=>	29.80,
			'margin'		=>	$margem_padrao,
			'box'			=>	12,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true,
			'ref'				=> 'COSECO'
		));

		// Acessórios para Kits
		/*
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Bandeira para Carro Laranja',
			'slug'			=>	'bandeira-para-carro-laranja',
			'description'	=>	'Bandeira para Carro Laranja',
			'price'			=>	8,
			'margin'		=>	$margem_padrao,
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
			'margin'		=>	$margem_padrao,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true
		));
		*/
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Flamula Oficial UP! By Car Branca',
			'slug'			=>	'flamula-oficial-up-by-car-branca',
			'description'	=>	'Flamula Oficial UP! By Car Branca',
			'price'			=>	8,
			'margin'		=>	$margem_padrao,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true,
			'ref'				=> 'BANC01'
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Flamula Oficial UP! By Car Laranja',
			'slug'			=>	'flamula-oficial-up-by-car-laranja',
			'description'	=>	'Flamula Oficial UP! By Car Laranja',
			'price'			=>	8,
			'margin'		=>	$margem_padrao,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true,
			'ref'				=> 'BANC02'
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Flamula Oficial UP! Laranja',
			'slug'			=>	'flamula-oficial-up-laranja',
			'description'	=>	'Flamula Oficial UP! Laranja',
			'price'			=>	5,
			'margin'		=>	$margem_padrao,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true,
			'ref'				=> 'BANT02'
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Flamula Oficial UP! Branca',
			'slug'			=>	'flamula-oficial-up-branca',
			'description'	=>	'Flamula Oficial UP! Branca',
			'price'			=>	5,
			'margin'		=>	$margem_padrao,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true,
			'ref'				=> 'BANT01'
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
			'is_alive'		=>	true,
			'ref'				=> 'BCARD1'
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
			'is_alive'		=>	true,
			'ref'				=> 'BF0001'
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
			'is_alive'		=>	true,
			'ref'				=> 'BLOCN1'
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Catálogo UP!',
			'slug'			=>	'catalogo-up',
			'description'	=>	'Catálogo UP!',
			'price'			=>	3,
			'margin'		=>	$margem_padrao,
			'box'			=>	100,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true,
			'ref'				=> 'CATA01'
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
			'is_alive'		=>	true,
			'ref'				=> 'CAN001'
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
			'is_alive'		=>	true,
			'ref'				=> 'CH0001'
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
			'is_alive'		=>	true,
			'ref'				=> 'CR0001'
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Encarte Oficial W/UP!',
			'slug'			=>	'encarte-oficial-wup',
			'description'	=>	'Encarte Oficial W/UP!',
			'price'			=>	$margem_padrao,
			'margin'		=>	0,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true,
			'ref'				=> 'ENCT02'
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
			'is_alive'		=>	true,
			'ref'				=> 'FOL001'
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
			'is_alive'		=>	true,
			'ref'				=> 'CXPT01'
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Estojo Demonstrador VIP',
			'slug'			=>	'estojo-demonstrador-vip',
			'description'	=>	'Estojo Demonstrador VIP',
			'price'			=>	79,
			'margin'		=>	$margem_padrao,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true,
			'ref'				=> 'EV0001'
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Estojo Demonstrador VIP Vazio',
			'slug'			=>	'estojo-demonstrador-vip-vazio',
			'description'	=>	'Estojo Demonstrador VIP-vazio',
			'price'			=>	20,
			'margin'		=>	$margem_padrao,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true,
			'ref'				=> 'EVVZ01'
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
			'is_alive'		=>	true,
			'ref'				=> 'FP0002'
		));

		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Guia Oficial do MRR UP!',
			'slug'			=>	'guia-oficial-do-mrr-up',
			'description'	=>	'Guia Oficial do MRR UP!',
			'price'			=>	117.90,
			'margin'		=>	$margem_padrao,
			'box'			=>	100,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true,
			'ref'				=> 'MN0001'
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
			'is_alive'		=>	true,
			'ref'				=> 'JNUP03'
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Ficha de Pedidos',
			'slug'			=>	'ficha-de-pedidos',
			'description'	=>	'Ficha de Pedidos',
			'price'			=>	4,
			'margin'		=>	$margem_padrao,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true,
			'ref'				=> 'FPCO01'
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
			'is_alive'		=>	true,
			'ref'				=> 'TP0001'
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
			'is_alive'		=>	true,
			'ref'				=> 'SP0001'
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Bolsa VIP UP!',
			'slug'			=>	'bolsa-vip-up',
			'description'	=>	'Bolsa VIP UP!',
			'price'			=>	50,
			'margin'		=>	$margem_padrao,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true,
			'ref'				=> 'BV0001'
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Bolsa MRR UP!',
			'slug'			=>	'bolsa-mrr-up',
			'description'	=>	'Bolsa MRR UP!',
			'price'			=>	22.60,
			'margin'		=>	$margem_padrao,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true,
			'ref'				=> 'BP0001'
		));
		
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'DVD Multi-itens MRR UP!+',
			'slug'			=>	'dvd-multi-itens-mrr-up-mais',
			'description'	=>	'DVD Multi-itens MRR UP!+',
			'price'			=>	117.50,
			'margin'		=>	$margem_padrao,
			'box'			=>	100,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true,
			'ref'				=> 'DVDNG1'
		));
		
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
			'is_alive'		=>	true,
			'ref'				=> 'PAST01'
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
			'is_alive'		=>	true,
			'ref'				=> 'MPAD001'
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
			'is_alive'		=>	true,
			'ref'				=> 'MPAD002'
		));
		/*
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Mousepad',
			'slug'			=>	'mousepad',
			'description'	=>	'Mousepad',
			'price'			=>	5.5,
			'margin'		=>	$margem_padrao,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true,
			'ref'				=> 'MPAD002'
		));
		*/
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
			'is_alive'		=>	true,
			'ref'				=> 'MP0001'
		));
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios para Kits UP!'],
			'name'			=>	'Pin Distribuidor Oficial UP!',
			'slug'			=>	'pin-distribuidor-oficial-up',
			'description'	=>	'Pin Distribuidor Oficial UP!',
			'price'			=>	5,
			'margin'		=>	0,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true,
			'ref'				=> 'PD0000'
		));

		// Amostras
		$essencias_amostras	=	array(
			'01'	=>	'UP! 01 - Azzaro',
			'02'	=>	'UP! 02 - 212 Sexy',
			'16'	=>	'UP! 16 - Dolce & Gabbana',
			'11'	=>	'UP! 11 - Ferrari Black',
			'38'	=>	'UP! 38 - Fantasy',
			'39'	=>	'UP! 39 - Allure Sport',
			'43'	=>	'UP! 43 - Animale',
			'44'	=>	'UP! 44 - Glow by J.Lo',
			'45'	=>	'UP! 45 - 212 Men',
			'46'	=>	'UP! 46 - Lady Million',
			'47'	=>	'UP! 47 - 1 Million',
		);

		foreach ($essencias_amostras as $number => $description) {
			DB::table('products')->insert(array(
				'tenant_id'		=>	$tenant_id,
				'category_id'	=>	$cats_ids['Amostras'],
				'name'			=>	'Amostra UP! '.$number,
				'slug'			=>	'amostra-up-'.Str::slug($number),
				'description'	=>	'Amostra '.$description. ' (10 Unidades)',
				'price'			=>	7,
				'margin'		=>	$margem_padrao,
				'box'			=>	NULL,
				'created_at'	=> 	date('Y-m-d H:m:s'),
				'updated_at'	=> 	date('Y-m-d H:m:s'),
				'is_alive'		=>	true,
				'ref'				=> 'AM01'.$number
			));
		}
		// Amostra monaco
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Amostras'],
			'name'			=>	'Amostra UP! Monaco',
			'slug'			=>	'amostra-up-monaco',
			'description'	=>	'Amostra UP! Monaco (10 Unidades)',
			'price'			=>	7,
			'margin'		=>	$margem_padrao,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true,
			'ref'				=> 'AMUPMO'
		));

		// Acessórios em Geral
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios em Geral'],
			'name'			=>	'Licença de Uso',
			'slug'			=>	'licenca-de-uso',
			'description'	=>	'Licença de Uso',
			'price'			=>	39.5,
			'margin'		=>	0,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true,
			'ref'				=> 'licenca'
		));

		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios em Geral'],
			'name'			=>	'Licença de Concessão',
			'slug'			=>	'licenca-de-concessao',
			'description'	=>	'Licença de Concessão para ser utilizada com Kits Free',
			'price'			=>	39.5,
			'margin'		=>	0,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true,
			'ref'				=> 'liconc'
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
			'is_alive'		=>	true,
			'ref'				=> 'display'
		));

		// Livros
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Livros'],
			'name'			=>	"Livro: Da Feira `à UP! - Clarel Lopes e Eugenio Bergamo",
			'slug'			=>	'livro-da-feira-a-up',
			'description'	=>	'Livro "Da Feira à UP!',
			'price'			=>	48,
			'margin'		=>	$margem_padrao,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true,
			'ref'				=> '9000001349'
		));

		// Malas
		DB::table('products')->insert(array(
			'tenant_id'		=>	$tenant_id,
			'category_id'	=>	$cats_ids['Acessórios em Geral'],
			'name'			=>	"Malas UP!",
			'slug'			=>	'malas-up',
			'description'	=>	'Este produto é composto por Mala de Despacho e Mala de Mão',
			'price'			=>	950,
			'margin'		=>	0,
			'box'			=>	NULL,
			'created_at'	=> 	date('Y-m-d H:m:s'),
			'updated_at'	=> 	date('Y-m-d H:m:s'),
			'is_alive'		=>	true,
			'ref'				=> 'CJMLUP'
		));

		// Combos
		$combos = array(
			'CMGUER'	=>	'Combo Super Mãe',
			'CMINTE'	=>	'Combo Mãe Ativa',
			'CMROMA'	=>	'Combo Mãe Amiga',
		);

		foreach ($combos as $cod => $name) {
			DB::table('products')->insert(array(
				'tenant_id'		=>	$tenant_id,
				'category_id'	=>	$cats_ids['Combos'],
				'name'			=>	$name,
				'slug'			=>	Str::slug($name),
				'description'	=>	$name,
				'price'			=>	105.1,
				'margin'		=>	$margem_padrao,
				'box'			=>	NULL,
				'created_at'	=> 	date('Y-m-d H:m:s'),
				'updated_at'	=> 	date('Y-m-d H:m:s'),
				'is_alive'		=>	true,
				'ref'				=> $cod
			));
		}
	}
}

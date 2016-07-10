<?php
class Admin{
	public static function __autoload(){
		$dbc = 	DB::connect();

	}

	public static function createDB(){
		new ORM(DB::connect());
	}

	public static function init(){
		if(session::isAdmin()){
			$reserva = new _reserva();
			$hab = new _room();

			$singleFree = $hab->free(1);
			$doubleFree = $hab->free(2);
			$tripleFree = $hab->free(3);
			$totalFree = $hab->totalFree();

			$listaPendientes = $reserva->listar(0);
			$listaActivas = $reserva->listar(1);

			$listaServicios = $hab->listaServicios();
			$sep = ';';
			$headerFile  = array(
					'Nombre', 
					'Id habitacion',
					'Habitacion',
					'Check in',
					'Check out',
					'Total dias',
					'Precio total'
					);
			$space = array('','','','','','','');
			$footerFile  = array(
					'Nombre:', 
					session::getValue('username'),
					'Fecha:',
					date('d-m-Y',time()),
					);
			$fp = fopen('web/ReservasPendientes.csv', 'w');
			fputcsv($fp,  $headerFile, $sep);
			foreach ($listaPendientes as $list) {
				$linea  = array(
					$list['nombre'], 
					$list['room_id'],
					$list['habitacion'],
					$list['check_in'],
					$list['check_out'],
					$list['total_dias'],
					$list['precio_total']
					);

			    fputcsv($fp,  $linea, $sep);
			}
			fputcsv($fp,  $space, $sep);
			fputcsv($fp,  $footerFile, $sep);
			fclose($fp);

			$fp = fopen('web/ReservasActivas.csv', 'w');
			fputcsv($fp,  $headerFile, $sep);
			foreach ($listaActivas as $list) {
				$linea  = array(
					$list['nombre'], 
					$list['room_id'],
					$list['habitacion'],
					$list['check_in'],
					$list['check_out'],
					$list['total_dias'],
					$list['precio_total']
					);
				
			    fputcsv($fp,  $linea, $sep);
			}
			fputcsv($fp,  $space, $sep);
			fputcsv($fp,  $footerFile, $sep);
			fclose($fp);

			$fp = fopen('web/ConsumoDeServicios.csv', 'w');
			$headerFile  = array(
					'Habitacion',
					'Servicio',
					'Precio',
					'Fecha'
					);
			fputcsv($fp,  $headerFile, $sep);
			foreach ($listaServicios  as $list) {
				$linea  = array(
					$list['habitacion'],
					$list['servicio'],
					$list['precio'],
					$list['fecha']
					);
				
			    fputcsv($fp,  $linea, $sep);			
			}
			fputcsv($fp,  $space, $sep);
			fputcsv($fp,  $footerFile, $sep);
			fclose($fp);

			$data = array(
				"listaPendientes" => $listaPendientes,
				"listaActivas" => $listaActivas,
				"totalFree" => $totalFree[0]['total'],
				"singleFree" => $singleFree[0]['total'],
				"doubleFree" => $doubleFree[0]['total'],
				"tripleFree" => $tripleFree[0]['total'],
				);
			View::parse("admin", $data);
		}else{
			header("location: ".HOME);
		}
		
	}

	public static function User($param){
		if(session::isAdmin()){
			$user = new _user();
			$lista = $user->listar($param);
			$data = array(
				"lista" => $lista,
				);
			View::parse("user", $data);
		}else{
			header("location: ".HOME);
		}
	}

	public static function Room(){
		if(session::isAdmin()){
			$room = new _room();
			$lista = $room->buscar();
			$listaServicios = $room->listaServicios();
			$category = new _category();
			$category2 = new _category();
			$numero = new _room();
		    $listaCat = $category->listar();
		    $listaCat2 = $category2->listar();
		    $listanum = $numero->vernum();
			$data = array(
				"lista" => $lista,
				"listaServicios" => $listaServicios,
				"listaCateg" => $listaCat,
				"listaCateg2" => $listaCat2,
				"listanum" => $listanum,
				"listanum2" => $listanum,
			);
			View::parse("room", $data);
		}else{
			header("location: ".HOME);
		}
	}
	
	public static function Precios(){
		if(session::isAdmin()){
			$precio = new _category();
			$lista = $precio->listar();
			$data = array(
				"lista" => $lista,
			);
			View::parse("precios", $data);
		}else{
			header("location: ".HOME);
		}
	}
}

 ?>
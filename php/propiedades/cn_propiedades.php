<?php
class cn_propiedades extends SIAN_sg_cn
{

	//-----------------------------------------------------------------------------------
	//---- dr_propiedades-------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function resetear()
	{
		$this->dep('dr_propiedades')->resetear();
	}

	function sincronizar()
	{
		$this->dep('dr_propiedades')->sincronizar();
	}

	//-----------------------------------------------------------------------------------
	//---- dt_propiedades -----------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function set_propiedades($datos)
	{
		$this->dep('dr_propiedades')->tabla('dt_propiedades')->set($datos);
		if (is_array($datos['imagen'])) {

			$temp_archivo = $datos['imagen']['tmp_name'];
			$fp = fopen($temp_archivo, 'rb');
			$this->dep('dr_propiedades')->tabla('dt_propiedades')->set_blob('imagen', $fp);
		}
	}

	function cargar($seleccion)
	{
		$this->dep('dr_propiedades')->cargar($seleccion);
	}

	function hay_cursor()
	{
		if ($this->dep('dr_propiedades')->tabla('dt_propiedades')->esta_cargada()) {
			return $this->dep('dr_propiedades')->tabla('dt_propiedades')->hay_cursor();
		}
	}

	function set_cursor($seleccion)
	{
		$id_fila = $this->dep('dr_propiedades')->tabla('dt_propiedades')->get_id_fila_condicion($seleccion)[0];
		$this->dep('dr_propiedades')->tabla('dt_propiedades')->set_cursor($id_fila);
	}

	function eliminar()
	{
		$this->dep('dr_propiedades')->tabla('dt_propiedades')->eliminar_todo();
	}

	function get_propiedades()
	{
		$fp_imagen = $this->dep('dr_propiedades')->tabla('dt_propiedades')->get_blob('imagen');

		$datos = $this->dep('dr_propiedades')->tabla('dt_propiedades')->get();

		if (isset($fp_imagen)) {
			$temp_nombre = 'imagen' . $datos['id_propiedad'];

			$temp_archivo = toba::proyecto()->get_www_temp($temp_nombre);

			$temp_imagen = fopen($temp_archivo['path'], 'w');
			stream_copy_to_stream($fp_imagen, $temp_imagen);
			fclose($temp_imagen);
			$tamanio_imagen = round(filesize($temp_archivo['path']) / 1024);
			$datos['imagen_vista'] = "<img src = '{$temp_archivo['url']}' alt=\"imagen\" WIDTH=180 HEIGHT=150 >";
			$datos['imagen'] = 'Tama�o foto actual: '.$tamanio_imagen.' KB';
		} else {
			$datos['imagen'] = null;
		}
		return $datos;

	}
	//-----------------------------------------------------------------------------------
	//------ dt_propiedad_x_comodidad -------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function procesar_filas_comodidades($datos)
	{
		$this->dep('dr_propiedades')->tabla('dt_propiedad_x_comodidad')->procesar_filas($datos);
	}
	function get_comodidades()
	{
		$datos = $this->dep('dr_propiedades')->tabla('dt_propiedad_x_comodidad')->get_filas();
		return $datos;
	}
	//-----------------------------------------------------------------------------------
	//---- dt_propiedad_x_composicion ----------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function procesar_filas_composicion_ambiental($datos)
	{
		$this->dep('dr_propiedades')->tabla('dt_propiedad_x_composicion')->procesar_filas($datos);
	}

	function get_composicion_ambiental()
	{
		$datos = $this->dep('dr_propiedades')->tabla('dt_propiedad_x_composicion')->get_filas();
		return $datos;
	}

//-----------------------------------------------------------------------------------
//---- dt_datos_catastrales ----------------------------------------------------------
//------------------------------------------------------------------------------------
// 	function�get_datos_catastrales()
// 	{
// 			$datos�=�[];
// 			if�(isset($this->s__datos['form_datos_catastrales']))�{
// 				� 		$datos�=�$this->s__datos['form_datos_catastrales'];
// 			} else�{
// 		 �		$datos�=�$this->dep('dr_propiedades')->tabla('dt_datos_catastrales')->get_undatocatastral();
// 		� 		$this->s__datos['form_datos_catastrales']�=�$datos;
// 		�			}
// 		return�$datos;
// 	}
//
// 	function�get_undatocatastral()�// <- Funci�n para obtener el registro cargado en la tabla hijo (dt_datos_catastrales)
// 	{
// 		�$datos�=�[];�// <- Si hay un registro lo vamos a guardar en esta variable
// 			if($this->hay_cursor())�{�// <- Hay un cursor seteado en la tabla padre
// 					$hay_datos�=�true;�// <- Vamos a suponer que hay datos en la tabla
// 					//�es solo una suposici�n, m�s adelante nos vamos a corregir si la supocici�n es incorrecta
//
// �			if�(!$this->hay_cursor('dt_datos_catastrales')) {�// <- dt_datos_catastrales NO tiene cursor seteado
// 					$datos_ml�=�$this->get_datos_catastrales();�// Obtenemos todas las filas de dt_datos_catastrales
// 					if($datos_ml)�{�// <- Obtuvimos por lo menos una fila
// 						$id_telefono�=�$this->extraerIddatosCas($datos_ml[0]);�// Obtenemos el id_dat de la primer fila (fila 0), ver la funci�n abajo
// �						$this->set_cursor($id_datos_catastrales,['dt_datos_catastrales']);�// <-
// 						- Seteamos el cursor en dt_datos_catastrales para la primera fila
//
// �					}�else�{�// <- No hay ni siquiera una fila, nuestra suposici�n estaba mal
// 						$hay_datos�=�false;�// Nos corregimos, ahora sabemos que no había ni siquiera una fila en dt_telefonos
// �						} // Fin if hay por lo menos una fila
//
// 				}�// Fin if hay cursor dt_datos_catastrales
//
// 			if�($hay_datos)�{�// <- Hay por lo menos una fila
// �					$datos�=�$this->get_datos_catastrales();�// Obtenemos los datos de la fila seteada
// 					}
// 		}�// <- Fin if hay cursor en dt padre
// 		return�$datos;
// 	}
//
// 	function�extraerIddatosCas(array�$datos)
// 	{
// 		if�(count($datos)�>�0)�{
// 				return�['id_datos_catastrales'=>�$datos['id_datos_catastrales']];
// 				}�else�{
// 					return�['id_datos_catastrales'�=>null];
// �					}
// 	}
//
// 	function�set_datos_catastrales(array�$datos)�//
// 	{
// �	// Creamos la fila nueva en memoria temporal en el Datos Tabla
// �		$this->dep('dr_propiedades')->tabla('dt_datos_catastrales')->nueva_fila($datos);
//
// 	//Obtenemos el id_fila_condicion de la nueva fila
// 		$id_fila_condicion�=�$this->dep('dr_propiedades')->tabla('dt_datos_catastrales')->get_filas()[0]['x_dbr_clave'];
//
// 	// Seteamos el cursor de dt_datos_catastrales en el nuevo registro
// 		$this->dep('dr_propiedades')->tabla('dt_datos_catastrales')->set_cursor($id_fila_condicion);
// 	}

	//-----------------------------------------------------------------------------------
	//---- dt_propiedad_x_restriccion ----------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function procesar_filas_restricciones($datos)
	{
		$this->dep('dr_propiedades')->tabla('dt_propiedad_x_restriccion')->procesar_filas($datos);
	}

	function get_restricciones()
	{
		$datos = $this->dep('dr_propiedades')->tabla('dt_propiedad_x_restriccion')->get_filas();
		return $datos;
	}
	//-----------------------------------------------------------------------------------
	//---- dt_novedades ----------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function procesar_filas_novedades($datos)
	{
		$this->dep('dr_propiedades')->tabla('dt_novedades')->procesar_filas($datos);
	}

	function get_novedades()
	{
		$datos = $this->dep('dr_propiedades')->tabla('dt_novedades')->get_filas();
		return $datos;
	}
	//-----------------------------------------------------------------------------------
	//---- dt_imagenes -----------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function procesar_filas_imagenes($datos)
		{
			$this->dep('dr_propiedades')->tabla('dt_imagenes')->procesar_filas($datos);
		}
		function get_imagenes()
		{
			$datos = $this->dep('dr_propiedades')->tabla('dt_imagenes')->get_filas();
			return $datos;
		}
		public function get_blob($datos, $id_fila)
		{
				$html_imagen = null;

				$imagen = $this->dep('dr_propiedades')->tabla('dt_imagenes')->get_blob('imagen', $id_fila);
					if (isset($imagen)) {
					$temp_nombre = md5(uniqid(time()));
					$temp_archivo = toba::proyecto()->get_www_temp($temp_nombre);
					$temp_imagen = fopen($temp_archivo['path'], 'w');
					stream_copy_to_stream($imagen, $temp_imagen);
					fclose($temp_imagen);
					fclose($imagen);
					$tamano = round(filesize($temp_archivo['path']) / 1024);
					$html_imagen =
					"<img width=\"24px\" src='{$temp_archivo['url']}' alt='' />";
					$datos['imagen'] = '<a href="'.$temp_archivo['url'].'" target="_newtab">'.$html_imagen.' Tama�o de archivo actual: '.$tamano.' kb</a>';
					$datos['imagen'.'?html'] = $html_imagen;
				  $datos['imagen'.'?url'] = $temp_archivo['url'];
				} else {
					$datos['imagen'] = null;
				}

				return $datos;
		}

		function get_blobs($datos)
		{
				$datos_r = array();
				foreach ($datos as $key => $value) {
				$datos_r[$key] = $this->get_blob($datos[$key], $key);
				}
				return $datos_r;
		}

		function set_blobs($datos)
		{
				foreach ($datos as $key => $value) {
					$this->set_blob($datos[$key], $key);
				}
		}

		public function set_blob($datos, $id_fila)
		{
			if (isset($datos['imagen'])) {
				if (is_array($datos['imagen'])) {
					$temp_archivo = $datos['imagen']['tmp_name'];
					$imagen = fopen($temp_archivo, 'rb');
					$this->dep('dr_personas')->tabla('dt_requisitos_x_persona')->set_blob('imagen',$imagen, $id_fila);
				}
			}
		}
 }
?>

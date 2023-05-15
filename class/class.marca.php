<button type="button" class="btn btn-dark" ><a href="index.php">INICIO</a></button>
<?php
class marca{
	
	
	private $id;
	private $descripcion;
	private $pais;
	private $direccion;
	private $foto;

	private $con;
	
	function __construct($cn){
		$this->con = $cn;
	   // echo "EJECUTANDOSE EL CONSTRUCTOR VEHICULO<br><br>";
	}
	

	public function get_form($id=NULL){
		// Código agregado -- //
	if(($id == NULL) || ($id == 0) ) {
			$this->descripcion = NULL;
			$this->pais = NULL;
			$this->direccion = NULL;
			$this->foto = NULL;

			
			$flag = NULL;
			$op = "new";
			$bandera = 1;
	}else{
			$sql = "SELECT * FROM marca WHERE id=$id;";
			$res = $this->con->query($sql);
			$row = $res->fetch_assoc();
            $num = $res->num_rows;
            $bandera = ($num==0) ? 0 : 1;
            
            if(!($bandera)){
                $mensaje = "tratar de actualizar el vehiculo con id= ".$id . "<br>";
                echo $this->_message_error($mensaje);
				
            }else{                
                
				
				/*echo "<br>REGISTRO A MODIFICAR: <br>";
					echo "<pre>";
						print_r($row);
					echo "</pre>";*/
			
		
             // ATRIBUTOS DE LA CLASE VEHICULO   
                $this->descripcion = $row['descripcion'];
                $this->pais = $row['pais'];
                $this->direccion = $row['direccion'];
                $this->foto = $row['foto'];
                //$this->avaluo = $row['avaluo'];
				
                //$flag = "disabled";
				$flag = "enabled";
                $op = "act"; 
            }
	}
        
	if($bandera){

		$html = '
		<form name="Form_marca" method="POST" action="marca.php" enctype="multipart/form-data">
		<input type="hidden" name="id" value="' . $id  . '">
		<input type="hidden" name="op" value="' . $op  . '">
		<div class="container">
		<table class="table table-striped table-bordered">
				<tr>
					<th colspan="2"><center>DATOS</center></th>
				</tr>

				<tr>
					<td>Descripcion:</td>
					<td><input type="text" size="15" name="descripcion" value="' . $this->descripcion. '"></td>
				</tr>

				<tr>
					<td>Pais:</td>
					<td><input type="text" size="15" name="pais" value="' . $this->pais . '"></td>
				</tr>
				<td>Direccion:</td>
					<td><input type="text" size="15" name="direccion" value="' . $this->direccion . '"></td>
				</tr>

				<tr>
					<td>Foto:</td>
					<td><input type="file" name="foto" ' . $flag . '></td>
				</tr>

				<tr>
					<th colspan="2"><input type="submit" name="Guardar" value="GUARDAR"></th>
				</tr>												
			</table>
			</div>';
		return $html;
		}
	}
	
	
	
	public function get_list(){
		$d_new = "new/0";                           //Línea agregada
        $d_new_final = base64_encode($d_new);       //Línea agregada
				
		$html = ' 
		<<div class="container">
		<table class="table table-striped table-bordered">
			<tr>
				<th colspan="8"><center>Lista de Marcas</center></th>
			</tr>
			<tr>
				<th colspan="8"><a href="marca.php?d=' . $d_new_final . '"><center><button class="btn btn-success">Nuevo</button></center></a></th>
			<tr>
				<th>Descripcion</th>
				<th>Pais</th>

				<th colspan="3"><center>Acciones</center></th>
			</tr>
			</div>';

		$sql = "SELECT id, descripcion, pais
				FROM marca;";
		//echo $sql;
		$res = $this->con->query($sql);
		
		
		
		// VERIFICA si existe TUPLAS EN EJECUCION DEL Query
		$num = $res->num_rows;
        if($num != 0){
		
		    while($row = $res->fetch_assoc()){
			/*
				echo "<br>VARIALE ROW ...... <br>";
				echo "<pre>";
						print_r($row);
				echo "</pre>";
			*/
		    		
				// URL PARA BORRAR
				$d_del = "del/" . $row['id'];
				$d_del_final = base64_encode($d_del);
				
				// URL PARA ACTUALIZAR
				$d_act = "act/" . $row['id'];
				$d_act_final = base64_encode($d_act);
				
				// URL PARA EL DETALLE
				$d_det = "det/" . $row['id'];
				$d_det_final = base64_encode($d_det);	
				
				$html .= '
					<tr>
						<td>' . $row['descripcion'] . '</td>
						<td>' . $row['pais'] . '</td>
						
						<td><a href="marca.php?d=' . $d_del_final . '"><center><button class="btn btn-danger">Borrar</button></center></a></td>
						<td><a href="marca.php?d=' . $d_act_final . '"><center><button class="btn btn-warning">Actualizar</button></center></a></td>
						<td><a href="marca.php?d=' . $d_det_final . '"><center><button class="btn btn-info">Detalle</button></center></a></td>
					</tr>';
			 
		    }
		}else{
			$mensaje = "Tabla Marca" . "<br>";
            echo $this->_message_BD_Vacia($mensaje);
			echo "<br><br><br>";
		}
		$html .= '</table>';
		return $html;
		
	}
	
	
//********************************************************************************************************
	/*
	 $tabla es la tabla de la base de datos
	 $valor es el nombre del campo que utilizaremos como valor del option
	 $etiqueta es nombre del campo que utilizaremos como etiqueta del option
	 $nombre es el nombre del campo tipo combo box (select)
	 * $defecto es el valor para que cargue el combo por defecto
	 */ 
	 
	 // _get_combo_db("marca","id","descripcion","marca",$this->marca)
	 // _get_combo_db("color","id","descripcion","color", $this->color)
	 
	 /*Aquí se agregó el parámetro:  $defecto*/
	private function _get_combo_db($tabla,$valor,$etiqueta,$nombre,$defecto=NULL){
		$html = '<select name="' . $nombre . '">';
		$sql = "SELECT $valor,$etiqueta FROM $tabla;";
		$res = $this->con->query($sql);
		$num = $res->num_rows;
		
			
		while($row = $res->fetch_assoc()){
		
		
			/*echo "<br>VARIABLE ROW <br>";
					echo "<pre>";
						print_r($row);
					echo "</pre>";*/
		
			$html .= ($defecto == $row[$valor])?'<option value="' . $row[$valor] . '" selected>' . $row[$etiqueta] . '</option>' . "\n" : '<option value="' . $row[$valor] . '">' . $row[$etiqueta] . '</option>' . "\n";
		}
		$html .= '</select>';
		return $html;
	}

	

	
//****************************************** NUEVO CODIGO *****************************************

public function get_detail_marca($id){


		$sql = "SELECT id, descripcion, pais, direccion, foto
				FROM marca
				WHERE id=$id;";
		//echo $sql;
		$res = $this->con->query($sql);
		$row = $res->fetch_assoc();
		
		// VERIFICA SI EXISTE id
		$num = $res->num_rows;
        
	if($num == 0){
        $mensaje = "desplegar el detalle del vehiculo con id= ".$id . "<br>";
        echo $this->_message_error($mensaje);
				
    }else{ 
	
	  /*  echo "<br>TUPLA<br>";
	    echo "<pre>";
				print_r($row);
		echo "</pre>";*/
	
		$html = '
		<div class="container">
		<table class="table table-striped table-bordered">

			<tr>
				<th colspan="2"><center>DATOS </center></th>
			</tr>
			<tr>
				<td>Descripcion: </td>
				<td>'. $row['descripcion'] .'</td>
			</tr>
			<tr>
				<td>Pais: </td>
				<td>'. $row['pais'] .'</td>
			</tr>
			<tr>
				<td>Direccion: </td>
				<td>'. $row['direccion'] .'</td>
			</tr>			
			<tr>
				<th colspan="2"><img src="images/' . $row['foto'] . '" width="300px"/></th>
			</tr>	
			<tr>
				<th colspan="2"><a href="marca.php"><center><button class="btn btn-outline-success">Regresar</button></center></a></th>
			</tr>																						
		</table>
		</div>';
		
		return $html;
	}	
	
}


	public function delete_marca($id){
		
		/*$mensaje = "PROXIMAMENTE SE ELIMINARA el vehiculo con id= ".$id . "<br>";
        echo $this->_message_error($mensaje);*/
		
	   
		$sql = "DELETE FROM marca WHERE id=$id;";
		if($this->con->query($sql)){
			echo $this->_message_ok("eliminó");
		}else{
			echo $this->_message_error("eliminar<br>");
		}
	
	}

	public function update_marca(){
		
		/*echo "<br>PETICION POST <br>";
		echo "<pre>";
			print_r($_POST);
		echo "</pre>";*/
			
			$id = $_POST['id'];
		
		// ATRIBUTOS DE LA CLASE VEHICULO   
				//$this->id = $id;
                $this->descripcion = $_POST['descripcion'];
                $this->pais = $_POST['pais'];
                $this->direccion = $_POST['direccion'];

				
				

		$sql = "UPDATE marca SET descripcion ='$this->descripcion', pais = '$this->pais', direccion = '$this->direccion' WHERE id=$id;";


		//echo $sql;
		
		if($this->con->query($sql)){
			echo $this->_message_ok("actualizo");
		}else{
			echo $this->_message_error("actualizar<br>");
		}
		
	}

	private function _get_name_file($nombre_original, $tamanio){
			$tmp = explode(".",$nombre_original);
			$numElm = count($tmp);
			$ext = $tmp[$numElm-1];
			$cadena = "";
					for($i=1;$i<=$tamanio;$i++){
						$c = rand(65, 122);
						if(($c >= 91) && ($c <=96)){
							$c = NULL;
								$i--;
						}else{
							$cadena .= chr($c);
						}
					}
	return $cadena . "." . $ext;
}

	public function save_marca(){

		
		// ATRIBUTOS DE LA CLASE VEHICULO   
				//$this->id = $id;
                $this->descripcion = $_POST['descripcion'];
                $this->pais = $_POST['pais'];
                $this->direccion = $_POST['direccion'];
                
                
                //$this->foto = $_POST['foto'];
                

        /*echo "<br> FILES <br>";
         echo "<pre>";
              print_r($_FILES);
         echo "<pre>";*/

         $this->foto=$this->_get_name_file($_FILES['foto']['name'],12);
         $path = "images/" . $this->foto;

         if(!move_uploaded_file($_FILES['foto']['tmp_name'], $path)){
         	$mensaje="Cargar la imagen";
         	echo $this->_message_error($mensaje);
         }
				
				
		$sql = "INSERT INTO marca VALUES (NULL,
									'$this->descripcion', 
									'$this->pais',
									'$this->direccion',
									'$this->foto');";
		


		if($this->con->query($sql)){
			echo $this->_message_ok("guardo");
		}else{
			echo $this->_message_error("guardar<br>");
		}
		
	}

	
//***************************************************************************************	
	
	private function _calculo_matricula($avaluo){
		return number_format(($avaluo * 0.10),2);
	}
	
//***************************************************************************************************************************
	
	private function _message_error($tipo){
		$html = '
		<table border="0" align="center">
			<tr>
				<th>Error al ' . $tipo . 'Favor contactar a .................... </th>
			</tr>
			<tr>
				<th><a href="marca.php"><center><button class="btn btn-outline-success">Regresar</button></a></th>
			</tr>
		</table>';
		return $html;
	}
	
	
	private function _message_BD_Vacia($tipo){
	   $html = '
		<table border="0" align="center">
			<tr>
				<th> NO existen registros en la ' . $tipo . 'Favor contactar a .................... </th>
			</tr>
	
		</table>';
		return $html;
	
	
	}
	
	private function _message_ok($tipo){
		$html = '
		<table border="0" align="center">
			<tr>
				<th>El registro se  ' . $tipo . ' correctamente</th>
			</tr>
			<tr>
				<th><a href="marca.php"><center><button class="btn btn-outline-success">Regresar</button></a></th>
			</tr>
		</table>';
		return $html;
	}
//************************************************************************************************************************************************

 
}
?>


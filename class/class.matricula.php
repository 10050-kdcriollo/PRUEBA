<button type="button" class="btn btn-dark" ><a href="index.html">INICIO</a></button>

<?php
class matricula{
	private $id;
	private $placa;
	private $marca;
	private $anio;
	private $color;
	private $avaluo;

	private $fecha;
	private $agencia;
	private $anio2;
	private $con;
	
	function __construct($cn){
		$this->con = $cn;
	}
		

	public function get_form($id){

			$sql = "SELECT * FROM vehiculo WHERE id='$id'";
			$res = $this->con->query($sql);
			$row = $res->fetch_assoc();
			
			$num = $res->num_rows;
            if($num==0){
                $mensaje = "tratar de matricular el vehiculo con id= ".$id;
                echo $this->_message_error($mensaje);
            }else{   

			$this->placa = $row['placa'];
			$flag = "disabled";
			$op = "matricular";
			}
				
		
		$html = '
		
		<form name="matricula" method="POST" action="matricula.php" enctype="multipart/form-data">
		<input type="hidden" name="id" value="' . $id  . '">
		<input type="hidden" name="op" value="' . $op  . '">
			<div class="container">
		<table class="table table-striped table-bordered">
				<tr>
					<th colspan="2" class="text-center" ><h2>DATOS MATRICULA</h2></th>
				</tr>
				<tr>
					<td>Placa:</td>
					<td><input type="text" size="6" name="placa" value="' . $this->placa . '" '. $flag .'></td>
				</tr>
				<tr>
					<td>Fecha:</td>
					<td><input type="date" size="6" name="fecha"  required></td>
				</tr>
				<tr>
					<td>Agencia:</td>
					<td>' . $this->_get_combo_agencia("agencia","id","descripcion","agencia") . '</td>
				</tr>	
				<tr>
					<td>Año:</td>
					<td>' . $this->_get_combo_anio("anio2",2000, $this->id) . '</td>
				</tr>
				<tr>
					<th colspan="2"><input type="submit" name="Guardar_Matricula" value="GUARDAR" ></th>
				</tr>	
				<tr>
					<th class="text-center" colspan="2"><button><a href="matricula.php">REGRESAR</a></button></th>
				</tr>	

			</table></div>';
		return $html;
	}
	

	
	private function _get_combo_agencia($tabla,$valor,$valor2,$nombre){
		$html = '<select name="' . $nombre . '">';
		$sql = "SELECT $valor,$valor2 FROM $tabla;";
		$res = $this->con->query($sql);
		while($row = $res->fetch_assoc()){
			//ImpResultQuery($row);
			$html .= '<option value="' . $row[$valor] . '">' . $row[$valor2] .'</option>' . "\n";
		}
		$html .= '</select>';
		return $html;
	}


private function _get_combo_anio($nombre, $anio_inicial) {
    $html = '<select name="' . $nombre . '">';

    // Obtener los años ya utilizados
    $sql = "SELECT DISTINCT anio FROM matricula";
    $result = $this->con->query($sql);
    $anios_usados = array();
    while ($row = $result->fetch_assoc()) {
        $anios_usados[] = $row['anio'];
    }

    $anio_actual = date('Y');
    for ($i = $anio_inicial; $i <= $anio_actual; $i++) {
        // Agregar el elemento de la lista solo si el año no se ha utilizado antes
        if (!in_array($i, $anios_usados)) {
            $html .= '<option value="' . $i . '">' . $i . '</option>' . "\n";
        }
    }

    $html .= '</select>';
    return $html;
}


	public function get_list_matricula(){
		$html = '
		<div class="container"> 
		<table class="table table-striped table-bordered">
			<tr>
				<th colspan="8" ><center>Lista de Vehículos</center</th>
			</tr>
			<tr>
				<th>Placa</th>
				<th>Marca</th>
				<th>Color</th>
				<th>Año</th>
				<th>Avalúo</th>
				<th><center>Accion</center></th>
			</tr>
			</div>'
			;
		$sql = "SELECT v.id, v.placa, m.descripcion as marca, c.descripcion as color, v.anio, v.avaluo  FROM vehiculo v, color c, marca m WHERE v.marca=m.id AND v.color=c.id;";	
		$res = $this->con->query($sql);
		// Sin codificar <td><a href="index.php?op=del&id=' . $row['id'] . '">Borrar</a></td>
		while($row = $res->fetch_assoc()){
			$d_matri = "matri/" . $row['id'];
			$d_matri_final = base64_encode($d_matri);				
			$html .= '
				<tr >
					<td>' . $row['placa'] . '</td>
					<td>' . $row['marca'] . '</td>
					<td>' . $row['color'] . '</td>
					<td>' . $row['anio'] . '</td>
					<td>' . $row['avaluo'] . '</td>

					<td><a href="matricula.php?d=' . $d_matri_final . '"><center><button class="btn btn-warning">Matricula</button></center></a></td>
					</tr>';
		}
		$html .= '  
		</table>
		</div>';
		
		return $html;
		
	}

	public function save_matricula(){
		
		$this->fecha = $_POST['fecha'];
		$this->id = $_POST['id'];
		$this->agencia = $_POST['agencia'];
		$this->anio2 = $_POST['anio2'];

		$sql = "INSERT INTO matricula VALUES(NULL,
											'$this->fecha',
											'$this->id',
											'$this->agencia',
											'$this->anio2');";
			
		//$validacion = "SELECT * FROM matricula WHERE vehiculo='$this->id'";
$validacion = "SELECT * FROM matricula WHERE vehiculo='$this->id' AND anio='$this->anio2' AND agencia='$this->agencia'";

		$res = $this->con->query($validacion);
		$row = $res->fetch_assoc();

		$carro = $row['vehiculo'];
		$anio_matriculado = $row['anio'];
		$agenc = $row['agencia'];
if($carro == $this->id && ($anio_matriculado == $this->anio2 || $agenc == $this->agencia)){
    echo $this->_message_error("No puede matricular dos veces el mismo vehiculo en un mismo año o una misma agencia.");
} else {
    if($this->con->query($sql)){
        echo $this->_message_ok("guardó");
    } else {
        echo $this->_message_error("guardar");
    }	
}
									
										
	}
//************************************* PARTE II ****************************************************	

	


	
	/*public function get_list_matriculados(){
		$html = '
				<div class="container"> 
		<table class="table table-striped table-bordered">
			<tr>
				<th colspan="8" class="text-center">Lista de Vehículos Matriculados</th>
			</tr>
			<tr class="text-center">
				<th>Fecha</th>
				<th>Vehiculo</th>
				<th>Agencia</th>
				<th>Año</th>
			</tr>
			</div>';
		
		/*$sql2 = "SELECT v.id, v.placa, m.vehiculo AS datos_vehiculo, a.id, a.descripcion, m.agencia AS datos_agencia FROM vehiculo v, matricula m, agencia a WHERE m.vehiculo=v.id AND m.agencia=a.id;";*/

		//$sql = "SELECT * FROM matricula ";
		/*$sql = "SELECT m.fecha, v.placa AS vehiculo, a.descripcion AS agencia
				FROM marca m, vehiculo v, agencia a
				WHERE m.vehiculo=v.id AND m.agencia=a.id ;";*/

		/*$res = $this->con->query($sql);
		// Sin codificar <td><a href="index.php?op=del&id=' . $row['id'] . '">Borrar</a></td>
		while($row = $res->fetch_assoc()){
			//$d_matri = "matri/" . $row['id'];
			//$d_matri_final = base64_encode($d_matri);	
			$html .= '
				<tr class="text-center">
					<td>' . $row['fecha'] . '</td>
					<td>' . $row['vehiculo'] . '</td>
					<td>' . $row['agencia'] . '</td>
					<td>' . $row['anio'] . '</td>
					</tr>';
		}
		$html .= '  
		</table>
		<center>
		';
		
		return $html;
		
	}
	*/
//*************************************************************************	
	
	private function _message_error($tipo){
		$html = '
		<table border="0" align="center">
			<tr>
				<th>Error al ' . $tipo . '. Favor contactar a .................... </th>
			</tr>
			<tr>
				<th><a href="matricula.php">Regresar</a></th>
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
				<th><a href="matricula.php"><center><button class="btn btn-outline-success">Regresar</button></center></a></th>
			</tr>
		</table>';
		return $html;
	}
	
//****************************************************************************	
	
} // FIN SCRPIT
?>


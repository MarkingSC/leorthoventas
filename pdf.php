<?php
  ob_start();
?>
<page backtop="10mm" backbottom="10mm" backleft="20mm" backright="20mm">
	<img src="img/lgo_ch.png" alt="" id="imgrep"> <label id="labrep" for="">Leorthopedic, Valle de Bravo</label>
	
	<div id="divuprep">
		<label id="titrep" for="" > REPORTE -------</label>
	</div>
	<div id="divrep2">
	<label for="">Reporte de Ventas</label>
	<table>
		<tr>
			<td class="tit_col">Producto</td>
			<td class="tit_col">Cantidad</td>
			<td class="tit_col">Subtotal</td>
		</tr>
		<tr>
			<td class="campo"></td>
			<td class="campo"></td>
			<td class="campo"></td>
		</tr>
	</table>
	<hr>
	<label for="">Reporte de Entradas</label>
	<table>
		<tr>
			<td class="tit_col">Producto</td>
			<td class="tit_col">Cantidad</td>
			<td class="tit_col">Costo</td>
			<td class="tit_col">Fecha</td>
		</tr>
		<tr>
			<td class="campo"></td>
			<td class="campo"></td>
			<td class="campo"></td>
			<td class="campo"></td>
		</tr>
	</table>
	<hr>
	<label for="">Reporte de Gastos</label>
	<table>
		<tr>
			<td class="tit_col">Concepto</td>
			<td class="tit_col">Valor</td>
			<td class="tit_col">Fecha</td>
		</tr>
		<tr>
			<td class="campo"></td>
			<td class="campo"></td>
			<td class="campo"></td>
		</tr>
	</table>
    </div>
</page>

<?php

  $content = ob_get_clean();
  require_once(dirname(__FILE__).'/vendor/autoload.php');
  try
  {
      $html2pdf = new HTML2PDF('P', 'A4', 'es', true, 'UTF-8', 3);
      $html2pdf->pdf->SetDisplayMode('fullpage');
      $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
      $html2pdf->Output('PDF-CF.pdf');
  }
  catch(HTML2PDF_exception $e) {
      echo $e;
      exit;
  }
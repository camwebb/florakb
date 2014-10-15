<?php defined ('MICRODATA') or exit ( 'Forbidden Access' ); ?>


<h1>Setting Profile CDC</h1>
<form method="post" action="<?=$basedomain?>profile/profileinp" enctype="multipart/form-data">
<fieldset>
<legend>Tentang CDC</legend>
<table>
	<tr>
		<td>Header</td>
		<td><input type="text" name="header_tentang" value="<?=(isset($data[0]['brief']) ? $data[0]['brief'] : '');?>" /></td>
	</tr>
	<tr>
		<td>Title</td>
		<td><input type="text" name="title_tentang" value="<?=(isset($data[0]['title']) ? $data[0]['title'] : '');?>" /></td>
	</tr>
	<tr>
		<td>Content</td>
		<td><textarea name="tentang"><?=(isset($data[0]['content']) ? $data[0]['content'] : '');?></textarea></td>
		<input type="hidden" name="id_tentang" value="<?=(isset($data[0]['id']) ? $data[0]['id'] : '');?>" />
		<input type="hidden" name="tags_tentang" value="<?=(isset($data[0]['tags']) ? $data[0]['tags'] : 'TCDC');?>" />
	</tr>
</table>
</fieldset>
<fieldset>
<legend>Sejarah CDC</legend>
<table>
	<tr>
		<td>Header</td>
		<td><input type="text" name="header_sejarah" value="<?=(isset($data[1]['brief']) ? $data[1]['brief'] : '');?>" /></td>
	</tr>
	<tr>
		<td>Title</td>
		<td><input type="text" name="title_sejarah" value="<?=(isset($data[1]['title']) ? $data[1]['title'] : '');?>" /></td>
	</tr>
	<tr>
		<td>Content</td>
		<td><textarea name="sejarah"><?=(isset($data[1]['content']) ? $data[1]['content'] : '');?></textarea></td>
		<input type="hidden" name="id_sejarah" value="<?=(isset($data[1]['id']) ? $data[1]['id'] : '');?>" />
		<input type="hidden" name="tags_sejarah" value="<?=(isset($data[1]['tags']) ? $data[1]['tags'] : 'SCDC');?>" />
	</tr>
</table>
</fieldset>
<fieldset>
<legend>Visi & Misi</legend>
<table>
	<tr>
		<td>Header</td>
		<td><input type="text" name="header_visi" value="<?=(isset($data[2]['brief']) ? $data[2]['brief'] : '');?>" /></td>
	</tr>
	<tr>
		<td>Title</td>
		<td><input type="text" name="title_visi" value="<?=(isset($data[2]['title']) ? $data[2]['title'] : '');?>" /></td>
	</tr>
	<tr>
		<td>Visi</td>
		<td><textarea name="visi"><?=(isset($data[2]['content']) ? $data[2]['content'] : '');?></textarea></td>
		<input type="hidden" name="id_visi" value="<?=(isset($data[2]['id']) ? $data[2]['id'] : '');?>" />
		<input type="hidden" name="tags_visi" value="<?=(isset($data[2]['tags']) ? $data[2]['tags'] : 'VCDC');?>" />
	</tr>
	<tr>
		<td>Misi</td>
		<td><textarea name="misi"><?=(isset($data[3]['content']) ? $data[3]['content'] : '');?></textarea></td>
		<input type="hidden" name="id_misi" value="<?=(isset($data[3]['id']) ? $data[3]['id'] : '');?>" />
		<input type="hidden" name="tags_misi" value="<?=(isset($data[3]['tags']) ? $data[3]['tags'] : 'MCDC');?>" />
	</tr>
</table>
</fieldset>
<input type="submit" value="Submit" id="submit" />
</form>